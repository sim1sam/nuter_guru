<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\WeightVariant;
use App\Services\Inventory\WeightCalculationService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StockService
{
    public function __construct(protected WeightCalculationService $weightCalc)
    {
    }

    public function getDefaultWarehouse(): Warehouse
    {
        $warehouse = Warehouse::where('is_default', 1)->where('status', 1)->first();

        if (! $warehouse) {
            $warehouse = Warehouse::where('status', 1)->first();
        }

        if (! $warehouse) {
            $warehouse = Warehouse::create([
                'name' => 'Default Warehouse',
                'code' => 'WH-001',
                'is_default' => 1,
                'status' => 1,
            ]);
        }

        return $warehouse;
    }

    public function ensureWarehouseStock(int $productId, ?int $warehouseId = null): WarehouseStock
    {
        $warehouseId = $warehouseId ?: $this->getDefaultWarehouse()->id;

        return WarehouseStock::firstOrCreate(
            ['warehouse_id' => $warehouseId, 'product_id' => $productId],
            ['qty' => 0]
        );
    }

    public function syncProductQty(int $productId): void
    {
        $total = WarehouseStock::where('product_id', $productId)->sum('qty');
        Product::where('id', $productId)->update([
            'qty' => $this->weightCalc->roundWeight($total),
        ]);
    }

    public function getAvailableStock(int $productId): float
    {
        $product = Product::find($productId);

        return $product ? $this->weightCalc->roundWeight((float) $product->qty) : 0.0;
    }

    public function hasEnoughStock(int $productId, float $baseQuantity): bool
    {
        return $this->getAvailableStock($productId) + 0.0001 >= $this->weightCalc->roundWeight($baseQuantity);
    }

    public function generateBarcode(Product $product): string
    {
        $barcode = 'P'.str_pad((string) $product->id, 10, '0', STR_PAD_LEFT);
        $product->barcode = $barcode;
        $product->save();

        return $barcode;
    }

    public function stockIn(
        int $productId,
        int $warehouseId,
        $qty,
        ?string $note = null,
        ?string $referenceNo = null,
        ?int $adminId = null,
        string $reason = 'stock_in',
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?int $supplierId = null,
        ?float $unitCost = null,
        array $meta = []
    ): StockMovement {
        $qty = $this->weightCalc->roundWeight($qty);
        if ($qty <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero.');
        }

        return DB::transaction(function () use (
            $productId, $warehouseId, $qty, $note, $referenceNo, $adminId,
            $reason, $referenceType, $referenceId, $supplierId, $unitCost, $meta
        ) {
            $stock = WarehouseStock::where('warehouse_id', $warehouseId)
                ->where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            if (! $stock) {
                $stock = $this->ensureWarehouseStock($productId, $warehouseId);
                $stock = WarehouseStock::where('id', $stock->id)->lockForUpdate()->first();
            }

            $before = $this->weightCalc->roundWeight((float) $stock->qty);
            $after = $this->weightCalc->roundWeight($before + $qty);

            $stock->qty = $after;
            $stock->save();

            $this->syncProductQty($productId);

            if ($unitCost !== null && $unitCost > 0) {
                Product::where('id', $productId)->update(['cost_price' => $unitCost]);
            }

            $product = Product::find($productId);

            return StockMovement::create(array_merge([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => 'in',
                'reason' => $reason,
                'qty' => $qty,
                'qty_before' => $before,
                'qty_after' => $after,
                'base_quantity' => $qty,
                'unit' => $meta['unit'] ?? ($product?->unit_type === 'kg' ? 'kg' : 'pcs'),
                'weight_variant_id' => $meta['weight_variant_id'] ?? null,
                'variant_name' => $meta['variant_name'] ?? null,
                'unit_weight_kg' => $meta['unit_weight_kg'] ?? null,
                'reference_no' => $referenceNo,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'supplier_id' => $supplierId,
                'unit_cost' => $unitCost,
                'note' => $note,
                'admin_id' => $adminId,
            ], []));
        });
    }

    public function stockOut(
        int $productId,
        int $warehouseId,
        $qty,
        string $reason = 'stock_out',
        ?string $note = null,
        ?string $referenceNo = null,
        ?int $adminId = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?int $supplierId = null,
        array $meta = []
    ): StockMovement {
        $qty = $this->weightCalc->roundWeight($qty);
        if ($qty <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero.');
        }

        return DB::transaction(function () use (
            $productId, $warehouseId, $qty, $reason, $note, $referenceNo, $adminId,
            $referenceType, $referenceId, $supplierId, $meta
        ) {
            $stock = WarehouseStock::where('warehouse_id', $warehouseId)
                ->where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            if (! $stock) {
                $stock = $this->ensureWarehouseStock($productId, $warehouseId);
                $stock = WarehouseStock::where('id', $stock->id)->lockForUpdate()->first();
            }

            $before = $this->weightCalc->roundWeight((float) $stock->qty);

            if ($before + 0.0001 < $qty) {
                $product = Product::find($productId);
                throw new InvalidArgumentException(
                    $product
                        ? $this->weightCalc->availableStockMessage($product)
                        : 'Insufficient stock in warehouse.'
                );
            }

            $after = $this->weightCalc->roundWeight($before - $qty);
            $stock->qty = $after;
            $stock->save();

            $this->syncProductQty($productId);

            $product = Product::find($productId);

            return StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => 'out',
                'reason' => $reason,
                'qty' => $qty,
                'qty_before' => $before,
                'qty_after' => $after,
                'base_quantity' => $qty,
                'unit' => $meta['unit'] ?? ($product?->unit_type === 'kg' ? 'kg' : 'pcs'),
                'weight_variant_id' => $meta['weight_variant_id'] ?? null,
                'variant_name' => $meta['variant_name'] ?? null,
                'unit_weight_kg' => $meta['unit_weight_kg'] ?? null,
                'reference_no' => $referenceNo,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'supplier_id' => $supplierId,
                'note' => $note,
                'admin_id' => $adminId,
            ]);
        });
    }

    public function addStock(int $productId, float $baseQty, ?int $warehouseId = null, array $options = []): StockMovement
    {
        $warehouseId = $warehouseId ?: $this->getDefaultWarehouse()->id;

        return $this->stockIn(
            $productId,
            $warehouseId,
            $baseQty,
            $options['note'] ?? null,
            $options['reference_no'] ?? null,
            $options['admin_id'] ?? null,
            $options['reason'] ?? 'stock_in',
            $options['reference_type'] ?? null,
            $options['reference_id'] ?? null,
            $options['supplier_id'] ?? null,
            $options['unit_cost'] ?? null,
            $options['meta'] ?? []
        );
    }

    public function removeStock(int $productId, float $baseQty, ?int $warehouseId = null, array $options = []): void
    {
        $this->deductForSale(
            $productId,
            $baseQty,
            $options['reference_no'] ?? null,
            $options['admin_id'] ?? null,
            $options['reference_type'] ?? 'order',
            $options['reference_id'] ?? null,
            $options['meta'] ?? []
        );
    }

    public function adjust(int $productId, int $warehouseId, $newQty, ?string $note = null, ?int $adminId = null): StockMovement
    {
        $newQty = $this->weightCalc->roundWeight($newQty);
        if ($newQty < 0) {
            throw new InvalidArgumentException('Quantity cannot be negative.');
        }

        return DB::transaction(function () use ($productId, $warehouseId, $newQty, $note, $adminId) {
            $stock = WarehouseStock::where('warehouse_id', $warehouseId)
                ->where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            if (! $stock) {
                $stock = $this->ensureWarehouseStock($productId, $warehouseId);
                $stock = WarehouseStock::where('id', $stock->id)->lockForUpdate()->first();
            }

            $before = $this->weightCalc->roundWeight((float) $stock->qty);
            $diff = $this->weightCalc->roundWeight($newQty - $before);

            $stock->qty = $newQty;
            $stock->save();

            $this->syncProductQty($productId);

            $product = Product::find($productId);

            return StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => 'adjustment',
                'reason' => $diff >= 0 ? 'adjustment_in' : 'adjustment_out',
                'qty' => abs($diff),
                'qty_before' => $before,
                'qty_after' => $newQty,
                'base_quantity' => abs($diff),
                'unit' => $product?->unit_type === 'kg' ? 'kg' : 'pcs',
                'note' => $note,
                'admin_id' => $adminId,
            ]);
        });
    }

    public function transfer(int $productId, int $fromWarehouseId, int $toWarehouseId, $qty, ?string $note = null, ?int $adminId = null): array
    {
        if ($fromWarehouseId === $toWarehouseId) {
            throw new InvalidArgumentException('Source and destination warehouses must be different.');
        }

        $qty = $this->weightCalc->roundWeight($qty);
        if ($qty <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($productId, $fromWarehouseId, $toWarehouseId, $qty, $note, $adminId) {
            $out = $this->stockOut($productId, $fromWarehouseId, $qty, 'transfer_out', $note, null, $adminId);
            $in = $this->stockIn($productId, $toWarehouseId, $qty, $note, null, $adminId, 'transfer_in');

            $out->update(['related_id' => $in->id]);
            $in->update(['related_id' => $out->id]);

            return ['out' => $out, 'in' => $in];
        });
    }

    public function openingStock(int $productId, int $warehouseId, $qty, ?float $unitCost = null, ?int $adminId = null): StockMovement
    {
        return $this->stockIn(
            $productId,
            $warehouseId,
            $qty,
            'Opening stock',
            'OPEN-'.$productId,
            $adminId,
            'opening_stock',
            'product',
            $productId,
            null,
            $unitCost
        );
    }

    public function deductForSale(
        int $productId,
        $qty,
        ?string $referenceNo = null,
        ?int $adminId = null,
        string $referenceType = 'order',
        ?int $referenceId = null,
        array $meta = []
    ): void {
        $qty = $this->weightCalc->roundWeight($qty);
        if ($qty <= 0) {
            return;
        }

        DB::transaction(function () use ($productId, $qty, $referenceNo, $adminId, $referenceType, $referenceId, $meta) {
            $this->alignWarehouseToProductQty($productId);

            $product = Product::lockForUpdate()->find($productId);
            if (! $product) {
                throw new InvalidArgumentException('Product not found.');
            }

            $available = $this->weightCalc->roundWeight((float) $product->qty);
            if ($available + 0.0001 < $qty) {
                throw new InvalidArgumentException($this->weightCalc->availableStockMessage($product));
            }

            $remaining = $qty;
            $defaultId = $this->getDefaultWarehouse()->id;
            $stocks = WarehouseStock::where('product_id', $productId)
                ->where('qty', '>', 0)
                ->lockForUpdate()
                ->get()
                ->sortByDesc(fn ($stock) => $stock->warehouse_id === $defaultId ? PHP_INT_MAX : (float) $stock->qty)
                ->values();

            foreach ($stocks as $stock) {
                if ($remaining <= 0) {
                    break;
                }

                $take = min($remaining, $this->weightCalc->roundWeight((float) $stock->qty));
                $this->stockOut(
                    $productId,
                    (int) $stock->warehouse_id,
                    $take,
                    'sale',
                    'Order sale',
                    $referenceNo,
                    $adminId,
                    $referenceType,
                    $referenceId,
                    null,
                    $meta
                );
                $remaining = $this->weightCalc->roundWeight($remaining - $take);
            }

            if ($remaining > 0.0001) {
                throw new InvalidArgumentException($this->weightCalc->availableStockMessage($product->fresh()));
            }
        });
    }

    /**
     * Put stock back when an order is cancelled (uses base_quantity / KG).
     */
    public function restoreForSale(
        int $productId,
        $qty,
        ?string $referenceNo = null,
        ?int $adminId = null,
        string $referenceType = 'order',
        ?int $referenceId = null,
        array $meta = []
    ): void {
        $qty = $this->weightCalc->roundWeight($qty);
        if ($qty <= 0) {
            return;
        }

        $warehouseId = $this->getDefaultWarehouse()->id;

        $this->stockIn(
            $productId,
            $warehouseId,
            $qty,
            'Order cancelled — stock restored',
            $referenceNo,
            $adminId,
            'order_cancel',
            $referenceType,
            $referenceId,
            null,
            null,
            $meta
        );
    }

    /**
     * Resolve KG base qty for an order line.
     * Pack sales: packs × unit_weight_kg (e.g. 2 × 0.25 = 0.5 KG).
     */
    public function resolveOrderItemBaseQty(OrderProduct $item): float
    {
        $qty = (float) ($item->qty ?? 0);
        $unitWeight = (float) ($item->unit_weight_kg ?? 0);

        if ($qty > 0 && $unitWeight > 0) {
            return $this->weightCalc->roundWeight($qty * $unitWeight);
        }

        if ($qty > 0 && $item->weight_variant_id) {
            $variant = WeightVariant::find($item->weight_variant_id);
            if ($variant && (float) $variant->weight_in_kg > 0) {
                return $this->weightCalc->roundWeight($qty * (float) $variant->weight_in_kg);
            }
        }

        if ($item->base_quantity !== null && $item->base_quantity !== '') {
            return $this->weightCalc->roundWeight((float) $item->base_quantity);
        }

        return $this->weightCalc->roundWeight($qty);
    }

    /**
     * Deduct stock when order moves to Processing (once per sale cycle).
     */
    public function deductStockFromOrder(Order $order, ?int $adminId = null): void
    {
        DB::transaction(function () use ($order, $adminId) {
            $locked = Order::lockForUpdate()->find($order->id);
            if (! $locked) {
                return;
            }

            // Already deducted and not restored after a cancel
            if ($locked->stock_deducted_at && ! $locked->stock_restored_at) {
                return;
            }

            $items = OrderProduct::where('order_id', $locked->id)->get();
            foreach ($items as $item) {
                $baseQty = $this->resolveOrderItemBaseQty($item);
                if ($baseQty <= 0 || ! $item->product_id) {
                    continue;
                }

                // Keep stored base_quantity aligned with weight packs
                if ((float) ($item->base_quantity ?? 0) !== $baseQty) {
                    $item->base_quantity = $baseQty;
                    $item->save();
                }

                $this->deductForSale(
                    (int) $item->product_id,
                    $baseQty,
                    $locked->order_id,
                    $adminId,
                    'order',
                    (int) $locked->id,
                    [
                        'weight_variant_id' => $item->weight_variant_id,
                        'variant_name' => $item->variant_name_snapshot,
                        'unit_weight_kg' => $item->unit_weight_kg,
                    ]
                );

                $product = Product::find($item->product_id);
                if ($product && isset($product->sold_qty)) {
                    $product->sold_qty = (float) $product->sold_qty + (float) $item->qty;
                    $product->save();
                }
            }

            $locked->stock_deducted_at = now();
            $locked->stock_restored_at = null;
            $locked->save();
        });
    }

    /**
     * Put stock back when an order is cancelled (only if previously deducted).
     */
    public function restoreStockFromOrder(Order $order, ?int $adminId = null): bool
    {
        if (! $order->stock_deducted_at || $order->stock_restored_at) {
            return false;
        }

        return (bool) DB::transaction(function () use ($order, $adminId) {
            $locked = Order::lockForUpdate()->find($order->id);
            if (! $locked || ! $locked->stock_deducted_at || $locked->stock_restored_at) {
                return false;
            }

            $items = OrderProduct::where('order_id', $locked->id)->get();
            foreach ($items as $item) {
                $baseQty = $this->resolveOrderItemBaseQty($item);
                if ($baseQty <= 0 || ! $item->product_id) {
                    continue;
                }

                $this->restoreForSale(
                    (int) $item->product_id,
                    $baseQty,
                    $locked->order_id,
                    $adminId,
                    'order',
                    (int) $locked->id,
                    [
                        'weight_variant_id' => $item->weight_variant_id,
                        'variant_name' => $item->variant_name_snapshot,
                        'unit_weight_kg' => $item->unit_weight_kg,
                    ]
                );

                $product = Product::find($item->product_id);
                if ($product && isset($product->sold_qty) && (float) $product->sold_qty > 0) {
                    $product->sold_qty = max(0, (float) $product->sold_qty - (float) $item->qty);
                    $product->save();
                }
            }

            $locked->stock_restored_at = now();
            $locked->save();

            return true;
        });
    }

    public function alignWarehouseToProductQty(int $productId): void
    {
        $product = Product::find($productId);
        if (! $product) {
            return;
        }

        $productQty = $this->weightCalc->roundWeight((float) $product->qty);
        $default = $this->ensureWarehouseStock($productId);
        $stocks = WarehouseStock::where('product_id', $productId)->get();
        $warehouseTotal = $this->weightCalc->roundWeight((float) $stocks->sum('qty'));

        if (abs($productQty - $warehouseTotal) < 0.0001) {
            return;
        }

        if ($productQty > $warehouseTotal) {
            $default->qty = $this->weightCalc->roundWeight((float) $default->qty + ($productQty - $warehouseTotal));
            $default->save();

            return;
        }

        $need = $this->weightCalc->roundWeight($warehouseTotal - $productQty);
        $ordered = $stocks->sortByDesc(fn ($stock) => $stock->warehouse_id === $default->warehouse_id ? 1 : 0);

        foreach ($ordered as $stock) {
            if ($need <= 0) {
                break;
            }

            $take = min($need, $this->weightCalc->roundWeight((float) $stock->qty));
            $stock->qty = $this->weightCalc->roundWeight((float) $stock->qty - $take);
            $stock->save();
            $need = $this->weightCalc->roundWeight($need - $take);
        }
    }

    public function reconcileAllProducts(): int
    {
        $count = 0;
        Product::query()->select('id')->orderBy('id')->chunkById(100, function ($products) use (&$count) {
            foreach ($products as $product) {
                $this->alignWarehouseToProductQty((int) $product->id);
                $count++;
            }
        });

        return $count;
    }
}
