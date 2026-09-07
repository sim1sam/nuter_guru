<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReceipt;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class PurchaseReceiptController extends Controller
{
    public function __construct(protected PurchaseService $purchaseService)
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $receipts = PurchaseReceipt::with(['purchaseOrder.supplier', 'warehouse'])->latest()->get();
        return view('admin.purchase.receipts', compact('receipts'));
    }

    public function create(Request $request)
    {
        $orders = PurchaseOrder::with(['supplier', 'items.product'])
            ->whereIn('status', ['submitted', 'partial'])
            ->latest()
            ->get();
        $selectedOrder = $request->purchase_order_id
            ? PurchaseOrder::with(['supplier', 'warehouse', 'items.product', 'items.weightVariant'])->find($request->purchase_order_id)
            : null;

        return view('admin.purchase.create_receipt', compact('orders', 'selectedOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'item_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        $order = PurchaseOrder::findOrFail($request->purchase_order_id);
        $lines = [];
        foreach ($request->item_id as $i => $itemId) {
            $lines[] = ['item_id' => $itemId, 'qty' => (float) ($request->qty[$i] ?? 0)];
        }

        try {
            $receipt = $this->purchaseService->receivePurchaseOrder(
                $order,
                $lines,
                $request->notes,
                Auth::guard('admin')->id()
            );
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with(['messege' => $e->getMessage(), 'alert-type' => 'error']);
        }

        return redirect()->route('admin.purchase-receipt.show', $receipt->id)->with([
            'messege' => trans('admin.Purchase received and stock updated'),
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        $receipt = PurchaseReceipt::with(['purchaseOrder.supplier', 'warehouse', 'items.product', 'items.orderItem'])->findOrFail($id);
        return view('admin.purchase.show_receipt', compact('receipt'));
    }
}
