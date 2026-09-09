<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseService $purchaseService)
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $orders = PurchaseOrder::with(['supplier', 'warehouse'])->latest()->get();
        return view('admin.purchase.orders', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->get();
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $products = Product::with(['weightVariants' => function ($q) {
            $q->where('weight_variants.status', 1);
        }])->orderBy('name')->get([
            'id', 'name', 'short_name', 'sku', 'barcode', 'cost_price', 'qty',
            'category_id', 'pcs_per_box', 'purchase_unit', 'unit_type',
        ]);
        $units = Unit::activeUnits();
        return view('admin.purchase.create_order', compact('suppliers', 'warehouses', 'products', 'categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'order_date' => 'required|date',
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'exists:products,id',
            'ordered_qty' => 'required|array',
            'unit_cost' => 'nullable|array',
            'unit' => 'nullable|array',
            'pcs_per_box' => 'nullable|array',
            'weight_variant_id' => 'nullable|array',
            'weight_variant_id.*' => 'nullable|integer|exists:weight_variants,id',
        ]);

        DB::transaction(function () use ($request) {
            $order = PurchaseOrder::create([
                'po_number' => $this->purchaseService->generateNumber('PO'),
                'supplier_id' => $request->supplier_id,
                'warehouse_id' => $request->warehouse_id,
                'status' => $request->input('submit') == 1 ? 'submitted' : 'draft',
                'order_date' => $request->order_date,
                'expected_date' => $request->expected_date,
                'tax' => $request->tax ?? 0,
                'discount' => $request->discount ?? 0,
                'notes' => $request->notes,
                'created_by' => Auth::guard('admin')->id(),
            ]);

            foreach ($request->product_id as $i => $productId) {
                $qty = (float) ($request->ordered_qty[$i] ?? 0);
                if ($qty <= 0) {
                    continue;
                }

                $product = Product::findOrFail($productId);
                $rawCost = $request->unit_cost[$i] ?? null;
                $unitCost = ($rawCost === null || $rawCost === '') ? null : (float) $rawCost;

                if ($product->isKg()) {
                    // PO for KG: quantity in KG, cost per KG (no weight variant)
                    $payload = $this->purchaseService->buildLinePayload(
                        $product,
                        $qty,
                        null,
                        $unitCost
                    );
                } else {
                    $unit = Product::resolvePurchaseUnit($request->unit[$i] ?? 'pc');
                    $pcsPerBox = max(1, (int) ($request->pcs_per_box[$i] ?? 1));
                    $payload = $this->purchaseService->buildLinePayload(
                        $product,
                        $qty,
                        null,
                        $unitCost,
                        $unit,
                        $pcsPerBox
                    );
                }

                PurchaseOrderItem::create(array_merge(
                    ['purchase_order_id' => $order->id],
                    $payload
                ));
            }

            $this->purchaseService->recalculateOrder($order);
        });

        return redirect()->route('admin.purchase-order.index')->with([
            'messege' => trans('admin.Purchase order created').' — '.trans('admin.Stock updates only after Receive Stock'),
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        $order = PurchaseOrder::with(['supplier', 'warehouse', 'items.product', 'items.weightVariant', 'receipts.items'])->findOrFail($id);
        return view('admin.purchase.show_order', compact('order'));
    }

    public function submit($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        if ($order->status !== 'draft') {
            return redirect()->back()->with(['messege' => trans('admin.Invalid status'), 'alert-type' => 'error']);
        }
        $order->update(['status' => 'submitted']);
        return redirect()->back()->with(['messege' => trans('admin.Purchase order submitted'), 'alert-type' => 'success']);
    }
}
