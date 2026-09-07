<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseReceiptItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Product;
use App\Models\WeightVariant;
use App\Services\Inventory\WeightCalculationService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PurchaseService
{
    public function __construct(
        protected StockService $stockService,
        protected WeightCalculationService $weightCalc
    ) {
    }

    public function generateNumber(string $prefix): string
    {
        return $prefix.'-'.date('Ymd').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function recalculateOrder(PurchaseOrder $order): void
    {
        $order->load('items');
        $subtotal = $order->items->sum('line_total');
        $order->update([
            'subtotal' => $subtotal,
            'total' => $subtotal + $order->tax - $order->discount,
        ]);
        $this->refreshOrderStatus($order);
    }

    public function refreshOrderStatus(PurchaseOrder $order): void
    {
        $order->load('items');
        $ordered = (float) $order->items->sum('ordered_qty');
        $received = (float) $order->items->sum('received_qty');

        if ($received <= 0) {
            $status = in_array($order->status, ['submitted', 'partial']) ? 'submitted' : $order->status;
        } elseif ($received + 0.0001 >= $ordered) {
            $status = 'received';
        } else {
            $status = 'partial';
        }

        if ($order->status !== 'draft' && $order->status !== 'cancelled') {
            $order->update(['status' => $status]);
        }
    }

    public function buildLinePayload(Product $product, float $quantity, ?int $weightVariantId = null, ?float $unitCost = null, ?string $unit = null, ?int $pcsPerBox = null): array
    {
        $weightVariant = null;
        $variantName = null;
        $unitWeightKg = null;
        $weightInGram = null;
        $lineUnit = $unit ?: ($product->isKg() ? 'kg' : ($product->defaultPurchaseUnit() ?: 'pc'));
        $pcs = max(1, (int) ($pcsPerBox ?: $product->pcsPerBox()));

        if ($product->isKg() && $weightVariantId) {
            $weightVariant = WeightVariant::findOrFail($weightVariantId);
            $variantName = $weightVariant->name;
            $unitWeightKg = (float) $weightVariant->weight_in_kg;
            $weightInGram = (int) $weightVariant->weight_in_gram;
            $lineUnit = $weightVariant->code;
            $baseQuantity = $this->weightCalc->calculateBaseQuantity($product, $quantity, $weightVariant);
            $resolvedUnitCost = $unitCost !== null
                ? $this->weightCalc->roundMoney($unitCost)
                : $this->weightCalc->variantPurchaseCost($product, $weightVariant);
        } elseif ($product->isKg()) {
            $baseQuantity = $this->weightCalc->roundWeight($quantity);
            $resolvedUnitCost = $unitCost !== null
                ? $this->weightCalc->roundMoney($unitCost)
                : $this->weightCalc->roundMoney((float) $product->cost_price);
            $lineUnit = 'kg';
        } else {
            $baseQuantity = (float) Product::convertToPcs($quantity, $lineUnit, $pcs);
            $resolvedUnitCost = $unitCost !== null
                ? $this->weightCalc->roundMoney($unitCost)
                : $this->weightCalc->roundMoney((float) $product->cost_price);
        }

        $lineTotal = $this->weightCalc->roundMoney($quantity * $resolvedUnitCost);

        return [
            'product_id' => $product->id,
            'weight_variant_id' => $weightVariant?->id,
            'unit' => $lineUnit,
            'pcs_per_box' => $pcs,
            'ordered_qty' => $quantity,
            'unit_cost' => $resolvedUnitCost,
            'line_total' => $lineTotal,
            'base_quantity' => $baseQuantity,
            'variant_name' => $variantName,
            'unit_weight_kg' => $unitWeightKg,
            'weight_in_gram' => $weightInGram,
        ];
    }

    public function receivePurchaseOrder(PurchaseOrder $order, array $lines, ?string $notes, int $adminId): PurchaseReceipt
    {
        if (! in_array($order->status, ['submitted', 'partial', 'received'])) {
            throw new InvalidArgumentException('Purchase order cannot be received in current status.');
        }

        return DB::transaction(function () use ($order, $lines, $notes, $adminId) {
            $receipt = PurchaseReceipt::create([
                'receipt_number' => $this->generateNumber('GRN'),
                'purchase_order_id' => $order->id,
                'warehouse_id' => $order->warehouse_id,
                'status' => 'posted',
                'receipt_date' => now()->toDateString(),
                'notes' => $notes,
                'received_by' => $adminId,
            ]);

            foreach ($lines as $line) {
                $item = PurchaseOrderItem::with('product')->where('purchase_order_id', $order->id)
                    ->where('id', $line['item_id'])
                    ->firstOrFail();
                $qty = (float) $line['qty'];

                if ($qty <= 0) {
                    continue;
                }

                if ($qty > $item->pendingQty() + 0.0001) {
                    throw new InvalidArgumentException('Receive quantity exceeds pending quantity.');
                }

                $baseQty = $item->toBaseQty($qty);

                PurchaseReceiptItem::create([
                    'purchase_receipt_id' => $receipt->id,
                    'purchase_order_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'received_qty' => $qty,
                    'unit_cost' => $item->unit_cost,
                    'weight_variant_id' => $item->weight_variant_id,
                    'base_quantity' => $baseQty,
                    'variant_name' => $item->variant_name,
                    'unit_weight_kg' => $item->unit_weight_kg,
                ]);

                $item->received_qty = (float) $item->received_qty + $qty;
                $item->save();

                $pcCost = $item->costPerPc();

                $this->stockService->stockIn(
                    $item->product_id,
                    $order->warehouse_id,
                    $baseQty,
                    'Purchase received',
                    $receipt->receipt_number,
                    $adminId,
                    'po_receive',
                    'purchase_receipt',
                    $receipt->id,
                    $order->supplier_id,
                    $pcCost > 0 ? $pcCost : null,
                    [
                        'weight_variant_id' => $item->weight_variant_id,
                        'variant_name' => $item->variant_name,
                        'unit_weight_kg' => $item->unit_weight_kg,
                        'unit' => $item->product?->isKg() ? 'kg' : 'pcs',
                    ]
                );

                if ($pcCost > 0) {
                    Product::where('id', $item->product_id)->update(['cost_price' => $pcCost]);
                }
            }

            $this->recalculateOrder($order->fresh());

            return $receipt;
        });
    }

    public function createRtv(array $data, array $lines, int $adminId): PurchaseReturn
    {
        return DB::transaction(function () use ($data, $lines, $adminId) {
            $return = PurchaseReturn::create([
                'return_number' => $this->generateNumber('RTV'),
                'supplier_id' => $data['supplier_id'],
                'warehouse_id' => $data['warehouse_id'],
                'purchase_order_id' => $data['purchase_order_id'] ?? null,
                'status' => 'posted',
                'return_date' => $data['return_date'] ?? now()->toDateString(),
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => $adminId,
            ]);

            foreach ($lines as $line) {
                $qty = (float) $line['qty'];
                if ($qty <= 0) {
                    continue;
                }

                $product = Product::findOrFail($line['product_id']);
                $weightVariant = ! empty($line['weight_variant_id'])
                    ? WeightVariant::find($line['weight_variant_id'])
                    : null;

                if ($product->isKg()) {
                    $baseQty = $this->weightCalc->calculateBaseQuantity($product, $qty, $weightVariant);
                    $unit = $weightVariant?->code ?: 'kg';
                    $pcsPerBox = 1;
                } else {
                    $unit = Product::normalizeUnit($line['unit'] ?? 'pc');
                    $pcsPerBox = max(1, (int) ($line['pcs_per_box'] ?? 1));
                    $baseQty = (float) Product::convertToPcs($qty, $unit, $pcsPerBox);
                }

                PurchaseReturnItem::create([
                    'purchase_return_id' => $return->id,
                    'product_id' => $line['product_id'],
                    'purchase_order_item_id' => $line['purchase_order_item_id'] ?? null,
                    'unit' => $unit,
                    'pcs_per_box' => $pcsPerBox,
                    'qty' => $qty,
                    'unit_cost' => (float) ($line['unit_cost'] ?? 0),
                    'weight_variant_id' => $weightVariant?->id,
                    'base_quantity' => $baseQty,
                    'variant_name' => $weightVariant?->name,
                    'unit_weight_kg' => $weightVariant?->weight_in_kg,
                ]);

                $this->stockService->stockOut(
                    (int) $line['product_id'],
                    (int) $data['warehouse_id'],
                    $baseQty,
                    'rtv',
                    $data['reason'] ?? 'Return to vendor',
                    $return->return_number,
                    $adminId,
                    'purchase_return',
                    $return->id,
                    (int) $data['supplier_id'],
                    [
                        'weight_variant_id' => $weightVariant?->id,
                        'variant_name' => $weightVariant?->name,
                        'unit_weight_kg' => $weightVariant?->weight_in_kg,
                        'unit' => $product->isKg() ? 'kg' : 'pcs',
                    ]
                );

                if (! empty($line['purchase_order_item_id'])) {
                    $poItem = PurchaseOrderItem::find($line['purchase_order_item_id']);
                    if ($poItem) {
                        $poItem->returned_qty = (float) $poItem->returned_qty + $qty;
                        $poItem->save();
                    }
                }
            }

            return $return;
        });
    }
}
