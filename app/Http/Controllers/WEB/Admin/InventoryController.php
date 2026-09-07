<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Setting;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class InventoryController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->middleware('auth:admin');
        $this->stockService = $stockService;
    }

    public function dashboard()
    {
        $totalProducts = Product::where('vendor_id', 0)->count();
        $totalStock = Product::where('vendor_id', 0)->sum('qty');
        $stockOut = Product::where('vendor_id', 0)->where('qty', '<=', 0)->count();
        $lowStock = Product::where('vendor_id', 0)
            ->where('qty', '>', 0)
            ->whereColumn('qty', '<=', 'low_stock_threshold')
            ->count();
        $warehouses = Warehouse::where('status', 1)->count();
        $recentMovements = StockMovement::with(['product', 'warehouse', 'admin'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.inventory.dashboard', compact(
            'totalProducts',
            'totalStock',
            'stockOut',
            'lowStock',
            'warehouses',
            'recentMovements'
        ));
    }

    public function index()
    {
        $products = Product::where('vendor_id', 0)->orderBy('id', 'desc')->get();
        $setting = Setting::first();

        return view('admin.inventory.products', compact('products', 'setting'));
    }

    public function show_inventory($id)
    {
        $product = Product::findOrFail($id);
        $histories = Inventory::where('product_id', $id)->orderBy('id', 'desc')->get();
        $movements = StockMovement::with(['warehouse', 'admin'])
            ->where('product_id', $id)
            ->latest()
            ->get();
        $warehouses = Warehouse::where('status', 1)->get();
        $warehouseStocks = WarehouseStock::with('warehouse')
            ->where('product_id', $id)
            ->get();

        return view('admin.inventory.stock_history', compact(
            'product',
            'histories',
            'movements',
            'warehouses',
            'warehouseStocks'
        ));
    }

    public function add_stock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stock_in' => 'required|integer|min:1',
            'warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        $warehouseId = $request->warehouse_id ?: $this->stockService->getDefaultWarehouse()->id;
        $adminId = Auth::guard('admin')->id();

        $inventory = new Inventory();
        $inventory->product_id = $request->product_id;
        $inventory->stock_in = $request->stock_in;
        $inventory->save();

        $this->stockService->stockIn(
            (int) $request->product_id,
            (int) $warehouseId,
            (int) $request->stock_in,
            $request->note,
            null,
            $adminId
        );

        return redirect()->back()->with([
            'messege' => trans('Added Successfully'),
            'alert-type' => 'success',
        ]);
    }

    public function delete_stock($id)
    {
        $inventory = Inventory::findOrFail($id);
        $product = Product::findOrFail($inventory->product_id);
        $warehouseId = $this->stockService->getDefaultWarehouse()->id;
        $adminId = Auth::guard('admin')->id();

        try {
            $this->stockService->stockOut(
                (int) $product->id,
                (int) $warehouseId,
                (int) $inventory->stock_in,
                'stock_in_reversal',
                'Legacy stock entry deleted',
                null,
                $adminId
            );
        } catch (InvalidArgumentException $e) {
            $updateQty = max(0, $product->qty - (int) $inventory->stock_in);
            $product->qty = $updateQty;
            $product->save();
        }

        $inventory->delete();

        return redirect()->back()->with([
            'messege' => trans('Deleted Successfully'),
            'alert-type' => 'success',
        ]);
    }

    public function stockInForm()
    {
        $products = Product::where('vendor_id', 0)->where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->get();

        return view('admin.inventory.stock_in', compact('products', 'warehouses'));
    }

    public function storeStockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.001',
            'reference_no' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
        ]);

        $this->stockService->stockIn(
            (int) $request->product_id,
            (int) $request->warehouse_id,
            (float) $request->qty,
            $request->note,
            $request->reference_no,
            Auth::guard('admin')->id()
        );

        return redirect()->route('admin.inventory.movements')->with([
            'messege' => trans('admin.Stock added successfully'),
            'alert-type' => 'success',
        ]);
    }

    public function stockOutForm()
    {
        $products = Product::where('vendor_id', 0)->where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->get();

        return view('admin.inventory.stock_out', compact('products', 'warehouses'));
    }

    public function storeStockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.001',
            'reason' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            $this->stockService->stockOut(
                (int) $request->product_id,
                (int) $request->warehouse_id,
                (float) $request->qty,
                $request->reason,
                $request->note,
                $request->reference_no,
                Auth::guard('admin')->id()
            );
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with([
                'messege' => $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('admin.inventory.movements')->with([
            'messege' => trans('admin.Stock removed successfully'),
            'alert-type' => 'success',
        ]);
    }

    public function adjustmentForm()
    {
        $products = Product::where('vendor_id', 0)->where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->get();

        return view('admin.inventory.adjustment', compact('products', 'warehouses'));
    }

    public function storeAdjustment(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'new_qty' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        $this->stockService->adjust(
            (int) $request->product_id,
            (int) $request->warehouse_id,
            (float) $request->new_qty,
            $request->note,
            Auth::guard('admin')->id()
        );

        return redirect()->route('admin.inventory.movements')->with([
            'messege' => trans('admin.Stock adjusted successfully'),
            'alert-type' => 'success',
        ]);
    }

    public function transferForm()
    {
        $products = Product::where('vendor_id', 0)->where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->get();

        return view('admin.inventory.transfer', compact('products', 'warehouses'));
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'qty' => 'required|numeric|min:0.001',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            $this->stockService->transfer(
                (int) $request->product_id,
                (int) $request->from_warehouse_id,
                (int) $request->to_warehouse_id,
                (float) $request->qty,
                $request->note,
                Auth::guard('admin')->id()
            );
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with([
                'messege' => $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('admin.inventory.movements')->with([
            'messege' => trans('admin.Stock transferred successfully'),
            'alert-type' => 'success',
        ]);
    }

    public function movements(Request $request)
    {
        $movements = StockMovement::with(['product', 'warehouse', 'admin'])
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->product_id, fn ($q) => $q->where('product_id', $request->product_id))
            ->latest()
            ->paginate(30);
        $products = Product::where('vendor_id', 0)->orderBy('name')->get(['id', 'name']);

        return view('admin.inventory.movements', compact('movements', 'products'));
    }

    public function lowStock()
    {
        $products = Product::where('vendor_id', 0)
            ->where(function ($q) {
                $q->where('qty', '<=', 0)
                    ->orWhereColumn('qty', '<=', 'low_stock_threshold');
            })
            ->orderBy('qty')
            ->get();

        return view('admin.inventory.low_stock', compact('products'));
    }

    public function barcodeIndex()
    {
        $products = Product::where('vendor_id', 0)->orderBy('name')->get();

        return view('admin.inventory.barcode', compact('products'));
    }

    public function generateBarcode(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $product = Product::findOrFail($request->product_id);
        $barcode = $this->stockService->generateBarcode($product);

        return redirect()->back()->with([
            'messege' => trans('admin.Barcode generated').': '.$barcode,
            'alert-type' => 'success',
        ]);
    }

    public function generateAllBarcodes()
    {
        $products = Product::where('vendor_id', 0)->where(function ($q) {
            $q->whereNull('barcode')->orWhere('barcode', '');
        })->get();

        foreach ($products as $product) {
            $this->stockService->generateBarcode($product);
        }

        return redirect()->back()->with([
            'messege' => trans('admin.All barcodes generated successfully'),
            'alert-type' => 'success',
        ]);
    }

    public function printBarcodes(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'printer_type' => 'nullable|in:a4,label',
            'label_size' => 'nullable|in:50x30,40x30,50x25,38x25,100x50,58x40,60x40',
            'copies' => 'nullable|integer|min:1|max:100',
        ]);

        $sizes = [
            '50x30' => ['w' => 50, 'h' => 30, 'cols' => 3],
            '40x30' => ['w' => 40, 'h' => 30, 'cols' => 4],
            '50x25' => ['w' => 50, 'h' => 25, 'cols' => 3],
            '38x25' => ['w' => 38, 'h' => 25, 'cols' => 4],
            '58x40' => ['w' => 58, 'h' => 40, 'cols' => 3],
            '60x40' => ['w' => 60, 'h' => 40, 'cols' => 3],
            '100x50' => ['w' => 100, 'h' => 50, 'cols' => 2],
        ];

        $printerType = $request->printer_type ?: 'label';
        $size = $sizes[$request->label_size ?? '50x30'];
        $copies = (int) ($request->copies ?: 1);
        $products = Product::whereIn('id', $request->product_ids)
            ->whereNotNull('barcode')
            ->where('barcode', '!=', '')
            ->orderBy('name')
            ->get();

        if ($products->isEmpty()) {
            return redirect()->back()->with([
                'messege' => trans('admin.No printable barcodes found'),
                'alert-type' => 'error',
            ]);
        }

        return view('admin.inventory.barcode_print', compact('products', 'size', 'copies', 'printerType'));
    }

    public function searchBarcode(Request $request)
    {
        $request->validate(['barcode' => 'required|string']);

        $product = Product::where('barcode', $request->barcode)
            ->orWhere('sku', $request->barcode)
            ->first();

        if (!$product) {
            return redirect()->back()->with([
                'messege' => trans('admin.Product not found'),
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('admin.stock-history', $product->id);
    }
}

