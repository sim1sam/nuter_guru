<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.purchase.suppliers', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:suppliers,code',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'status' => 'required|in:0,1',
        ]);

        Supplier::create($request->only('code', 'name', 'contact_person', 'email', 'phone', 'address', 'tax_no', 'status', 'notes'));

        return redirect()->back()->with(['messege' => trans('admin.Created Successfully'), 'alert-type' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:suppliers,code,'.$supplier->id,
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'status' => 'required|in:0,1',
        ]);

        $supplier->update($request->only('code', 'name', 'contact_person', 'email', 'phone', 'address', 'tax_no', 'status', 'notes'));

        return redirect()->back()->with(['messege' => trans('admin.Update Successfully'), 'alert-type' => 'success']);
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        if (PurchaseOrder::where('supplier_id', $supplier->id)->exists()) {
            return redirect()->back()->with([
                'messege' => 'Cannot delete. This supplier is used in purchase orders.',
                'alert-type' => 'error',
            ]);
        }

        if (PurchaseReturn::where('supplier_id', $supplier->id)->exists()) {
            return redirect()->back()->with([
                'messege' => 'Cannot delete. This supplier is used in purchase returns.',
                'alert-type' => 'error',
            ]);
        }

        if (Product::where('default_supplier_id', $supplier->id)->exists()) {
            return redirect()->back()->with([
                'messege' => 'Cannot delete. This supplier is set as default on one or more products. Clear it first, or set Status = Inactive.',
                'alert-type' => 'error',
            ]);
        }

        $supplier->delete();

        return redirect()->back()->with(['messege' => trans('Deleted Successfully'), 'alert-type' => 'success']);
    }
}
