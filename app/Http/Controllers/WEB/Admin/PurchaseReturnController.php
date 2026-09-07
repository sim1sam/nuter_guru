<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class PurchaseReturnController extends Controller
{
    public function __construct(protected PurchaseService $purchaseService)
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $returns = PurchaseReturn::with(['supplier', 'warehouse'])->latest()->get();
        return view('admin.purchase.returns', compact('returns'));
    }

    public function create(Request $request)
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
        $orders = PurchaseOrder::with('supplier')->whereIn('status', ['partial', 'received'])->latest()->get();
        $selectedOrder = $request->purchase_order_id
            ? PurchaseOrder::with(['supplier', 'items.product', 'items.weightVariant'])->find($request->purchase_order_id)
            : null;

        return view('admin.purchase.create_return', compact('suppliers', 'warehouses', 'products', 'categories', 'units', 'orders', 'selectedOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'return_date' => 'required|date',
            'product_id' => 'required|array|min:1',
            'qty' => 'required|array',
            'weight_variant_id' => 'nullable|array',
            'weight_variant_id.*' => 'nullable|integer|exists:weight_variants,id',
        ]);

        $lines = [];
        foreach ($request->product_id as $i => $productId) {
            $qty = (float) ($request->qty[$i] ?? 0);
            if ($qty <= 0) {
                continue;
            }
            $lines[] = [
                'product_id' => $productId,
                'qty' => $qty,
                'unit' => Product::resolvePurchaseUnit($request->unit[$i] ?? 'pc'),
                'pcs_per_box' => $request->pcs_per_box[$i] ?? 1,
                'unit_cost' => (float) ($request->unit_cost[$i] ?? 0),
                'weight_variant_id' => ! empty($request->weight_variant_id[$i])
                    ? (int) $request->weight_variant_id[$i]
                    : null,
                'purchase_order_item_id' => $request->purchase_order_item_id[$i] ?? null,
            ];
        }

        if (empty($lines)) {
            return redirect()->back()->with(['messege' => trans('admin.Please add at least one item'), 'alert-type' => 'error']);
        }

        try {
            $return = $this->purchaseService->createRtv([
                'supplier_id' => $request->supplier_id,
                'warehouse_id' => $request->warehouse_id,
                'purchase_order_id' => $request->purchase_order_id,
                'return_date' => $request->return_date,
                'reason' => $request->reason,
                'notes' => $request->notes,
            ], $lines, Auth::guard('admin')->id());
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with(['messege' => $e->getMessage(), 'alert-type' => 'error']);
        }

        return redirect()->route('admin.purchase-return.show', $return->id)->with([
            'messege' => trans('admin.RTV posted and stock reduced'),
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        $return = PurchaseReturn::with(['supplier', 'warehouse', 'items.product', 'purchaseOrder'])->findOrFail($id);
        return view('admin.purchase.show_return', compact('return'));
    }
}
