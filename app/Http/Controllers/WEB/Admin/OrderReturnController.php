<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use App\Services\PurchaseService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $returns = OrderReturn::with('order', 'items.product')->latest()->paginate(20);

        return view('admin.order_returns.index', compact('returns'));
    }

    public function create(Request $request)
    {
        $order = null;
        if ($request->filled('order_id')) {
            $order = Order::with('orderProducts.product')->where('order_id', $request->order_id)->orWhere('id', $request->order_id)->first();
        }

        return view('admin.order_returns.create', compact('order'));
    }

    public function store(Request $request, StockService $stockService, PurchaseService $purchaseService)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.order_product_id' => 'required|exists:order_products,id',
            'items.*.qty' => 'required|numeric|min:0.001',
        ]);

        $order = Order::findOrFail($data['order_id']);

        DB::transaction(function () use ($data, $order, $stockService, $purchaseService) {
            $return = OrderReturn::create([
                'return_number' => $purchaseService->generateNumber('SR'),
                'order_id' => $order->id,
                'status' => 'posted',
                'return_date' => now()->toDateString(),
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::guard('admin')->id(),
            ]);

            $warehouseId = $stockService->getDefaultWarehouse()->id;

            foreach ($data['items'] as $line) {
                $op = OrderProduct::findOrFail($line['order_product_id']);
                if ((int) $op->order_id !== (int) $order->id) {
                    continue;
                }

                $qty = (float) $line['qty'];
                if ($qty <= 0 || $qty > (float) $op->qty) {
                    continue;
                }

                $ratio = $qty / max(0.001, (float) $op->qty);
                $baseQty = $op->base_quantity !== null
                    ? round((float) $op->base_quantity * $ratio, 3)
                    : $qty;

                OrderReturnItem::create([
                    'order_return_id' => $return->id,
                    'order_product_id' => $op->id,
                    'product_id' => $op->product_id,
                    'weight_variant_id' => $op->weight_variant_id,
                    'variant_name_snapshot' => $op->variant_name_snapshot,
                    'unit_weight_kg' => $op->unit_weight_kg,
                    'qty' => $qty,
                    'base_quantity' => $baseQty,
                    'unit_price' => $op->unit_price,
                ]);

                $stockService->stockIn(
                    (int) $op->product_id,
                    $warehouseId,
                    $baseQty,
                    'Sale return',
                    $return->return_number,
                    Auth::guard('admin')->id(),
                    'sale_return',
                    'order_return',
                    $return->id,
                    null,
                    null,
                    [
                        'weight_variant_id' => $op->weight_variant_id,
                        'variant_name' => $op->variant_name_snapshot,
                        'unit_weight_kg' => $op->unit_weight_kg,
                        'unit' => $op->unit_weight_kg ? 'kg' : 'pcs',
                    ]
                );
            }
        });

        $notification = ['messege' => 'Sale return posted successfully', 'alert-type' => 'success'];

        return redirect()->route('admin.order-return.index')->with($notification);
    }
}
