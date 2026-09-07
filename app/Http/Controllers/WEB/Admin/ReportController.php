<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Exports\ReportArrayExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseReceiptItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboard(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $setting = Setting::first();

        $salesQuery = Order::whereBetween('created_at', $this->dateTimeRange($from, $to))
            ->where('order_status', '!=', 4);
        $salesTotal = (float) (clone $salesQuery)->sum('total_amount');
        $salesCount = (clone $salesQuery)->count();
        $salesQty = (int) (clone $salesQuery)->sum('product_qty');

        $cogs = $this->cogsTotal($from, $to);
        $expenseTotal = (float) Expense::whereBetween('expense_date', [$from, $to])->sum('amount');
        $poTotal = (float) PurchaseOrder::whereBetween('order_date', [$from, $to])
            ->where('status', '!=', 'cancelled')
            ->sum('total');
        $poCount = PurchaseOrder::whereBetween('order_date', [$from, $to])->count();
        $receiveCount = PurchaseReceipt::whereBetween('receipt_date', [$from, $to])->count();
        $receiveValue = $this->receiveValue($from, $to);
        $returnCount = PurchaseReturn::whereBetween('return_date', [$from, $to])->count();
        $returnValue = $this->returnValue($from, $to);

        $stockQty = (int) Product::where('vendor_id', 0)->sum('qty');
        $stockValue = (float) Product::where('vendor_id', 0)->selectRaw('SUM(qty * COALESCE(cost_price, 0)) as v')->value('v');
        $lowStock = Product::where('vendor_id', 0)->where('qty', '>', 0)->whereColumn('qty', '<=', 'low_stock_threshold')->count();
        $stockOut = Product::where('vendor_id', 0)->where('qty', '<=', 0)->count();

        $grossProfit = $salesTotal - $cogs;
        $netProfit = $grossProfit - $expenseTotal;

        $monthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth()->toDateString();
            $end = now()->subMonths($i)->endOfMonth()->toDateString();
            $monthSales = (float) Order::whereBetween('created_at', $this->dateTimeRange($start, $end))
                ->where('order_status', '!=', 4)
                ->sum('total_amount');
            $monthCogs = $this->cogsTotal($start, $end);
            $monthExpense = (float) Expense::whereBetween('expense_date', [$start, $end])->sum('amount');
            $monthly[] = [
                'label' => now()->subMonths($i)->format('M Y'),
                'sales' => $monthSales,
                'cogs' => $monthCogs,
                'expense' => $monthExpense,
                'profit' => $monthSales - $monthCogs - $monthExpense,
            ];
        }

        $expenseByCategory = Expense::select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('expense_date', [$from, $to])
            ->groupBy('expense_category_id')
            ->with('category')
            ->orderByDesc('total')
            ->get();

        return view('admin.report.dashboard', compact(
            'from', 'to', 'setting', 'salesTotal', 'salesCount', 'salesQty', 'cogs',
            'expenseTotal', 'poTotal', 'poCount', 'receiveCount', 'receiveValue',
            'returnCount', 'returnValue', 'stockQty', 'stockValue', 'lowStock', 'stockOut',
            'grossProfit', 'netProfit', 'monthly', 'expenseByCategory'
        ));
    }

    public function inventory(Request $request)
    {
        $setting = Setting::first();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get(['id', 'name']);

        $query = Product::where('vendor_id', 0)->orderBy('name');
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();
        $warehouseId = $request->warehouse_id;
        $rows = $products->map(function ($product) use ($warehouseId) {
            $qty = (int) $product->qty;
            if ($warehouseId) {
                $qty = (int) WarehouseStock::where('product_id', $product->id)
                    ->where('warehouse_id', $warehouseId)
                    ->value('qty');
            }
            $cost = (float) ($product->cost_price ?? 0);

            return [
                'product' => $product,
                'qty' => $qty,
                'cost' => $cost,
                'value' => $qty * $cost,
            ];
        });

        if ($request->stock === 'low') {
            $rows = $rows->filter(fn ($row) => $row['qty'] > 0 && $row['qty'] <= ($row['product']->low_stock_threshold ?? 5));
        } elseif ($request->stock === 'out') {
            $rows = $rows->filter(fn ($row) => $row['qty'] <= 0);
        }

        $totalQty = $rows->sum('qty');
        $totalValue = $rows->sum('value');

        return view('admin.report.inventory', compact(
            'rows', 'warehouses', 'categories', 'setting', 'totalQty', 'totalValue'
        ));
    }

    public function expense(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $setting = Setting::first();
        $categories = ExpenseCategory::orderBy('name')->get();

        $query = Expense::with('category')->whereBetween('expense_date', [$from, $to])->latest('expense_date');
        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }
        $expenses = $query->get();
        $total = $expenses->sum('amount');

        $byCategory = Expense::select('expense_category_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as qty'))
            ->whereBetween('expense_date', [$from, $to])
            ->when($request->filled('category_id'), fn ($q) => $q->where('expense_category_id', $request->category_id))
            ->groupBy('expense_category_id')
            ->with('category')
            ->orderByDesc('total')
            ->get();

        return view('admin.report.expense', compact('from', 'to', 'expenses', 'categories', 'setting', 'total', 'byCategory'));
    }

    public function purchaseOrder(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $setting = Setting::first();
        $suppliers = Supplier::orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();

        $query = PurchaseOrder::with(['supplier', 'warehouse'])
            ->whereBetween('order_date', [$from, $to])
            ->latest('order_date');
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $orders = $query->get();
        $total = $orders->sum('total');

        return view('admin.report.purchase_order', compact('from', 'to', 'orders', 'suppliers', 'warehouses', 'setting', 'total'));
    }

    public function receive(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $setting = Setting::first();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        $query = PurchaseReceipt::with(['warehouse', 'purchaseOrder.supplier', 'items.product', 'items.orderItem'])
            ->whereBetween('receipt_date', [$from, $to])
            ->latest('receipt_date');
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('supplier_id')) {
            $query->whereHas('purchaseOrder', fn ($q) => $q->where('supplier_id', $request->supplier_id));
        }
        $receipts = $query->get();

        $rows = collect();
        foreach ($receipts as $receipt) {
            foreach ($receipt->items as $item) {
                $pcs = $item->orderItem
                    ? $item->orderItem->toBaseQty((int) $item->received_qty)
                    : (int) $item->received_qty;
                $value = (float) $item->received_qty * (float) $item->unit_cost;
                $rows->push([
                    'receipt' => $receipt,
                    'item' => $item,
                    'pcs' => $pcs,
                    'value' => $value,
                ]);
            }
        }

        $totalQty = $rows->sum(fn ($r) => (int) $r['item']->received_qty);
        $totalPcs = $rows->sum('pcs');
        $totalValue = $rows->sum('value');

        return view('admin.report.receive', compact(
            'from', 'to', 'rows', 'warehouses', 'suppliers', 'setting', 'totalQty', 'totalPcs', 'totalValue'
        ));
    }

    public function returns(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $setting = Setting::first();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        $query = PurchaseReturn::with(['supplier', 'warehouse', 'items.product'])
            ->whereBetween('return_date', [$from, $to])
            ->latest('return_date');
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }
        $returns = $query->get();

        $rows = collect();
        foreach ($returns as $return) {
            foreach ($return->items as $item) {
                $pcs = Product::convertToPcs((int) $item->qty, $item->unit, (int) ($item->pcs_per_box ?: 1));
                $rows->push([
                    'return' => $return,
                    'item' => $item,
                    'pcs' => $pcs,
                    'value' => (float) $item->qty * (float) $item->unit_cost,
                ]);
            }
        }

        $totalQty = $rows->sum(fn ($r) => (int) $r['item']->qty);
        $totalPcs = $rows->sum('pcs');
        $totalValue = $rows->sum('value');

        return view('admin.report.returns', compact(
            'from', 'to', 'rows', 'warehouses', 'suppliers', 'setting', 'totalQty', 'totalPcs', 'totalValue'
        ));
    }

    public function sales(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $setting = Setting::first();
        $statuses = $this->orderStatuses();

        $query = Order::with('user')
            ->whereBetween('created_at', $this->dateTimeRange($from, $to))
            ->latest();
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        $orders = $query->get();
        $total = $orders->sum('total_amount');
        $qty = $orders->sum('product_qty');

        return view('admin.report.sales', compact('from', 'to', 'orders', 'setting', 'statuses', 'total', 'qty'));
    }

    public function profit(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $setting = Setting::first();

        $salesTotal = (float) Order::whereBetween('created_at', $this->dateTimeRange($from, $to))
            ->where('order_status', '!=', 4)
            ->sum('total_amount');
        $cogs = $this->cogsTotal($from, $to);
        $expenseTotal = (float) Expense::whereBetween('expense_date', [$from, $to])->sum('amount');
        $grossProfit = $salesTotal - $cogs;
        $netProfit = $grossProfit - $expenseTotal;

        $products = OrderProduct::query()
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_products.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', $this->dateTimeRange($from, $to))
            ->where('orders.order_status', '!=', 4)
            ->groupBy('order_products.product_id', 'order_products.product_name', 'order_products.variant_name_snapshot')
            ->selectRaw('order_products.product_id, order_products.product_name, order_products.variant_name_snapshot, SUM(order_products.qty) as sold_qty, SUM(COALESCE(order_products.base_quantity, order_products.qty)) as base_qty, SUM(order_products.unit_price * order_products.qty) as sale_amount, SUM(COALESCE(order_products.base_quantity, order_products.qty) * COALESCE(order_products.unit_cost, products.cost_price, 0)) as cost_amount')
            ->orderByDesc('sale_amount')
            ->get()
            ->map(function ($row) {
                $row->profit = (float) $row->sale_amount - (float) $row->cost_amount;
                return $row;
            });

        return view('admin.report.profit', compact(
            'from', 'to', 'setting', 'salesTotal', 'cogs', 'expenseTotal', 'grossProfit', 'netProfit', 'products'
        ));
    }

    public function export(Request $request, string $report, string $format)
    {
        $table = $this->exportTable($request, $report);
        $filename = Str::slug($table['title']).'-'.now()->format('Y-m-d');
        $export = new ReportArrayExport($table['headings'], $table['rows'], $table['title']);

        if ($format === 'excel') {
            return Excel::download($export, $filename.'.xlsx');
        }
        if ($format === 'csv') {
            return Excel::download($export, $filename.'.csv', ExcelFormat::CSV);
        }
        if ($format === 'pdf') {
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');
            $pdf = new Dompdf($options);
            $pdf->loadHtml(view('admin.report.pdf', $table)->render());
            $pdf->setPaper('A4', 'landscape');
            $pdf->render();

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.pdf"',
            ]);
        }

        abort(404);
    }

    protected function exportTable(Request $request, string $report): array
    {
        [$from, $to] = $this->dateRange($request);

        if ($report === 'dashboard') {
            $salesTotal = (float) Order::whereBetween('created_at', $this->dateTimeRange($from, $to))->where('order_status', '!=', 4)->sum('total_amount');
            $cogs = $this->cogsTotal($from, $to);
            $expenseTotal = (float) Expense::whereBetween('expense_date', [$from, $to])->sum('amount');
            $poTotal = (float) PurchaseOrder::whereBetween('order_date', [$from, $to])->where('status', '!=', 'cancelled')->sum('total');
            $rows = [
                ['Sales', number_format($salesTotal, 2)],
                ['Cost of Goods', number_format($cogs, 2)],
                ['Gross Profit', number_format($salesTotal - $cogs, 2)],
                ['Expense', number_format($expenseTotal, 2)],
                ['Net Profit', number_format($salesTotal - $cogs - $expenseTotal, 2)],
                ['Purchase Orders', number_format($poTotal, 2)],
                ['Purchase Received', number_format($this->receiveValue($from, $to), 2)],
                ['Returns', number_format($this->returnValue($from, $to), 2)],
            ];
            foreach ($this->monthlyRows() as $month) {
                $rows[] = [$month['label'].' Sales', number_format($month['sales'], 2)];
                $rows[] = [$month['label'].' Profit', number_format($month['profit'], 2)];
            }

            return $this->tablePayload(trans('admin.Report Dashboard'), ['Item', 'Amount'], $rows, $from, $to);
        }

        if ($report === 'inventory') {
            $request->merge(['from_date' => $from, 'to_date' => $to]);
            $view = $this->inventory($request);
            $data = $view->getData();
            $rows = collect($data['rows'])->map(fn ($row) => [
                $row['product']->name,
                $row['product']->sku,
                $row['qty'],
                number_format($row['cost'], 2, '.', ''),
                number_format($row['value'], 2, '.', ''),
            ])->values()->all();

            return $this->tablePayload(trans('admin.Inventory Report'), [
                trans('admin.Product'), trans('admin.SKU'), trans('admin.Stock'), trans('admin.Cost Price'), trans('admin.Stock Value'),
            ], $rows, null, null, [
                trans('admin.Total Stock') => $data['totalQty'],
                trans('admin.Stock Value') => number_format($data['totalValue'], 2),
            ]);
        }

        if ($report === 'expense') {
            $data = $this->expense($request)->getData();
            $rows = collect($data['expenses'])->map(fn ($e) => [
                $e->expense_number,
                optional($e->expense_date)->format('Y-m-d'),
                $e->category->name ?? '-',
                $e->title,
                $e->paymentMethodLabel(),
                number_format($e->amount, 2, '.', ''),
            ])->values()->all();

            return $this->tablePayload(trans('admin.Expense Report'), [
                trans('admin.Expense No'), trans('admin.Date'), trans('admin.Category'), trans('admin.Title'), trans('admin.Payment Method'), trans('admin.Amount'),
            ], $rows, $from, $to, [trans('admin.Total') => number_format($data['total'], 2)]);
        }

        if ($report === 'purchase-order') {
            $data = $this->purchaseOrder($request)->getData();
            $rows = collect($data['orders'])->map(fn ($o) => [
                $o->po_number,
                optional($o->order_date)->format('Y-m-d'),
                $o->supplier->name ?? '-',
                $o->warehouse->name ?? '-',
                strtoupper($o->status),
                number_format($o->total, 2, '.', ''),
            ])->values()->all();

            return $this->tablePayload(trans('admin.Purchase Order Report'), [
                trans('admin.PO Number'), trans('admin.Date'), trans('admin.Supplier'), trans('admin.Warehouse'), trans('admin.Status'), trans('admin.Total'),
            ], $rows, $from, $to, [trans('admin.Total') => number_format($data['total'], 2)]);
        }

        if ($report === 'receive') {
            $data = $this->receive($request)->getData();
            $rows = collect($data['rows'])->map(fn ($row) => [
                $row['receipt']->receipt_number,
                optional($row['receipt']->receipt_date)->format('Y-m-d'),
                $row['receipt']->purchaseOrder->po_number ?? '-',
                $row['receipt']->purchaseOrder->supplier->name ?? '-',
                $row['receipt']->warehouse->name ?? '-',
                $row['item']->product->name ?? '-',
                $row['item']->received_qty,
                $row['pcs'],
                number_format($row['value'], 2, '.', ''),
            ])->values()->all();

            return $this->tablePayload(trans('admin.Purchase Receive Report'), [
                trans('admin.Receipt No'), trans('admin.Date'), trans('admin.PO Number'), trans('admin.Supplier'), trans('admin.Warehouse'), trans('admin.Product'), trans('admin.Quantity'), trans('admin.Total Pcs'), trans('admin.Amount'),
            ], $rows, $from, $to, [
                trans('admin.Total Pcs') => $data['totalPcs'],
                trans('admin.Total') => number_format($data['totalValue'], 2),
            ]);
        }

        if ($report === 'returns') {
            $data = $this->returns($request)->getData();
            $rows = collect($data['rows'])->map(fn ($row) => [
                $row['return']->return_number,
                optional($row['return']->return_date)->format('Y-m-d'),
                $row['return']->supplier->name ?? '-',
                $row['return']->warehouse->name ?? '-',
                $row['return']->reason,
                $row['item']->product->name ?? '-',
                $row['item']->qty.' '.$row['item']->unit,
                $row['pcs'],
                number_format($row['value'], 2, '.', ''),
            ])->values()->all();

            return $this->tablePayload(trans('admin.Return Report'), [
                trans('admin.Return No'), trans('admin.Date'), trans('admin.Supplier'), trans('admin.Warehouse'), trans('admin.Reason'), trans('admin.Product'), trans('admin.Quantity'), trans('admin.Total Pcs'), trans('admin.Amount'),
            ], $rows, $from, $to, [
                trans('admin.Total Pcs') => $data['totalPcs'],
                trans('admin.Total') => number_format($data['totalValue'], 2),
            ]);
        }

        if ($report === 'sales') {
            $data = $this->sales($request)->getData();
            $statuses = $data['statuses'];
            $rows = collect($data['orders'])->map(fn ($order) => [
                $order->order_id,
                $order->created_at->format('Y-m-d'),
                $order->user->name ?? '-',
                $order->product_qty,
                $statuses[$order->order_status] ?? $order->order_status,
                $order->payment_status == 1 ? trans('admin.success') : trans('admin.Pending'),
                number_format($order->total_amount, 2, '.', ''),
            ])->values()->all();

            return $this->tablePayload(trans('admin.Sales Report'), [
                trans('admin.Order Id'), trans('admin.Date'), trans('admin.Customer'), trans('admin.Quantity'), trans('admin.Status'), trans('admin.Payment'), trans('admin.Amount'),
            ], $rows, $from, $to, [
                trans('admin.Quantity') => $data['qty'],
                trans('admin.Total') => number_format($data['total'], 2),
            ]);
        }

        if ($report === 'profit') {
            $data = $this->profit($request)->getData();
            $rows = collect($data['products'])->map(fn ($p) => [
                $p->product_name,
                (int) $p->sold_qty,
                number_format($p->sale_amount, 2, '.', ''),
                number_format($p->cost_amount, 2, '.', ''),
                number_format($p->profit, 2, '.', ''),
            ])->values()->all();

            return $this->tablePayload(trans('admin.Profit Report'), [
                trans('admin.Product'), trans('admin.Sold'), trans('admin.Sales'), trans('admin.Cost of Goods'), trans('admin.Profit'),
            ], $rows, $from, $to, [
                trans('admin.Sales') => number_format($data['salesTotal'], 2),
                trans('admin.Cost of Goods') => number_format($data['cogs'], 2),
                trans('admin.Gross Profit') => number_format($data['grossProfit'], 2),
                trans('admin.Expense') => number_format($data['expenseTotal'], 2),
                trans('admin.Net Profit') => number_format($data['netProfit'], 2),
            ]);
        }

        abort(404);
    }

    protected function tablePayload(string $title, array $headings, array $rows, ?string $from, ?string $to, array $summary = []): array
    {
        return compact('title', 'headings', 'rows', 'from', 'to', 'summary');
    }

    protected function monthlyRows(): array
    {
        $monthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth()->toDateString();
            $end = now()->subMonths($i)->endOfMonth()->toDateString();
            $monthSales = (float) Order::whereBetween('created_at', $this->dateTimeRange($start, $end))->where('order_status', '!=', 4)->sum('total_amount');
            $monthCogs = $this->cogsTotal($start, $end);
            $monthExpense = (float) Expense::whereBetween('expense_date', [$start, $end])->sum('amount');
            $monthly[] = [
                'label' => now()->subMonths($i)->format('M Y'),
                'sales' => $monthSales,
                'profit' => $monthSales - $monthCogs - $monthExpense,
            ];
        }

        return $monthly;
    }

    protected function dateRange(Request $request): array
    {
        $from = $request->from_date ?: now()->startOfMonth()->toDateString();
        $to = $request->to_date ?: now()->toDateString();

        return [$from, $to];
    }

    protected function dateTimeRange(string $from, string $to): array
    {
        return [$from.' 00:00:00', $to.' 23:59:59'];
    }

    protected function cogsTotal(string $from, string $to): float
    {
        return (float) (OrderProduct::query()
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_products.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', $this->dateTimeRange($from, $to))
            ->where('orders.order_status', '!=', 4)
            ->selectRaw('SUM(COALESCE(order_products.base_quantity, order_products.qty) * COALESCE(order_products.unit_cost, products.cost_price, 0)) as cogs')
            ->value('cogs') ?? 0);
    }

    protected function receiveValue(string $from, string $to): float
    {
        return (float) (PurchaseReceiptItem::query()
            ->join('purchase_receipts', 'purchase_receipt_items.purchase_receipt_id', '=', 'purchase_receipts.id')
            ->whereBetween('purchase_receipts.receipt_date', [$from, $to])
            ->selectRaw('SUM(purchase_receipt_items.received_qty * purchase_receipt_items.unit_cost) as total')
            ->value('total') ?? 0);
    }

    protected function returnValue(string $from, string $to): float
    {
        return (float) (PurchaseReturnItem::query()
            ->join('purchase_returns', 'purchase_return_items.purchase_return_id', '=', 'purchase_returns.id')
            ->whereBetween('purchase_returns.return_date', [$from, $to])
            ->selectRaw('SUM(purchase_return_items.qty * purchase_return_items.unit_cost) as total')
            ->value('total') ?? 0);
    }

    protected function orderStatuses(): array
    {
        return [
            0 => trans('admin.Pending'),
            1 => trans('admin.Processing'),
            5 => trans('admin.Shipment'),
            2 => trans('admin.Delivered'),
            3 => trans('admin.Return'),
            4 => trans('admin.Cancel'),
        ];
    }
}
