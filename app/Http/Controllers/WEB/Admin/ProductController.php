<?php

namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\ProductGallery;
use App\Models\Brand;
use App\Models\ProductSpecificationKey;
use App\Models\ProductSpecification;
use App\Models\OrderProduct;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\OrderProductVariant;
use App\Models\ProductReport;
use App\Models\ProductReview;
use App\Models\Wishlist;
use App\Models\Setting;
use App\Models\FlashSaleProduct;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartVariant;
use App\Models\CompareProduct;
use App\Models\Unit;
use Intervention\Image\Laravel\Facades\Image;
use File;
use Str;

use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $products = Product::with('category','seller','brand')->where(['vendor_id' => 0])->orderBy('id','desc')->get();
        $orderProducts = OrderProduct::all();
        $setting = Setting::first();
        $frontend_url = $setting->frontend_url;
        $frontend_view = $frontend_url.'single-product?slug=';

        return view('admin.product',compact('products','orderProducts','setting','frontend_view'));
    }

    public function sellerProduct(){
        $products = Product::with('category','seller','brand')->where('vendor_id','!=',0)->where('status',1)->get();
        $orderProducts = OrderProduct::all();
        $setting = Setting::first();
        $frontend_url = $setting->frontend_url;
        $frontend_view = $frontend_url.'single-product?slug=';
        return view('admin.product',compact('products','orderProducts','setting','frontend_view'));
    }

    public function sellerPendingProduct(){
        $products = Product::with('category','seller','brand')->where('vendor_id','!=',0)->where('approve_by_admin',0)->get();
        $orderProducts = OrderProduct::all();
        $setting = Setting::first();

        return view('admin.pending_product',compact('products','orderProducts','setting'));

    }

    public function stockoutProduct(){
        $products = Product::with('category','seller','brand')->where('vendor_id',0)->where('qty',0)->get();
        $orderProducts = OrderProduct::all();
        $setting = Setting::first();

        $frontend_url = $setting->frontend_url;
        $frontend_view = $frontend_url.'single-product?slug=';

        return view('admin.stockout_product',compact('products','orderProducts','setting','frontend_view'));

    }



    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $specificationKeys = ProductSpecificationKey::all();
        $warehouses = \App\Models\Warehouse::where('status', 1)->get();
        $suppliers = \App\Models\Supplier::where('status', 1)->orderBy('name')->get();
        $units = \App\Models\Unit::activeUnits();
        $weightVariants = \App\Models\WeightVariant::active()->ordered()->get();

        return view('admin.create_product',compact('categories','brands','specificationKeys','warehouses','suppliers','units','weightVariants'));
    }

    public function store(Request $request)
    {
        $rules = [
            'short_name' => 'required',
            'name' => 'required',
            'slug' => 'required|unique:products',
            'thumb_image' => 'required',
            'category' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
            'weight' => 'nullable|numeric',
            'opening_stock' => 'nullable|numeric|min:0',
            'unit_type' => 'required|in:pcs,kg',
            'selling_price_mode' => 'nullable|in:automatic,custom',
            'weight_variant_ids' => 'nullable|array',
            'weight_variant_ids.*' => 'integer|exists:weight_variants,id',
            'weight_variant_prices' => 'nullable|array',
        ];
        $customMessages = [
            'short_name.required' => trans('admin_validation.Short name is required'),
            'short_name.unique' => trans('admin_validation.Short name is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
            'thumb_image.required' => trans('admin_validation.thumbnail is required'),
            'short_description.required' => trans('admin_validation.Short description is required'),
            'long_description.required' => trans('admin_validation.Long description is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $openingQty = (float) ($request->opening_stock ?? 0);
        if ($openingQty > 0 && (! $request->filled('cost_price') || (float) $request->cost_price <= 0)) {
            return redirect()->back()->withInput()->with([
                'messege' => trans('admin.Purchase price is required when opening stock is added'),
                'alert-type' => 'error',
            ]);
        }

        $product = new Product();
        if($request->thumb_image){
            $extention = $request->thumb_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::read($request->thumb_image)
                ->save(public_path().'/'.$image_name);
            $product->thumb_image=$image_name;
        }

        $product->short_name = $request->short_name;
        $product->short_name_bn = $request->short_name_bn;
        $product->name = $request->name;
        $product->name_bn = $request->name_bn;
        $product->slug = $request->slug;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category ? $request->sub_category : 0;
        $product->child_category_id = $request->child_category ? $request->child_category : 0;
        $product->brand_id = $request->brand ? $request->brand : 0;
        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->low_stock_threshold = $request->low_stock_threshold ?? 5;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        $product->cost_price = ($request->filled('cost_price') && (float) $request->cost_price > 0)
            ? (float) $request->cost_price
            : 0;
        $product->default_supplier_id = $request->default_supplier_id;
        $product->qty = 0;
        $product->unit_type = $request->unit_type === 'kg' ? 'kg' : 'pcs';
        $product->selling_price_mode = $request->selling_price_mode === 'custom' ? 'custom' : 'automatic';
        $product->pcs_per_box = max(1, (int) ($request->pcs_per_box ?? 1));
        $product->purchase_unit = $product->unit_type === 'kg' ? 'kg' : Product::resolvePurchaseUnit($request->purchase_unit);
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->status = $request->status;
        $product->weight = $request->weight ?? 0;
        $product->tags = $request->tags;
        $product->is_undefine = 1;
        $product->is_specification = $request->is_specification ? 1 : 0;
        $product->seo_title = $request->seo_title ? $request->seo_title : $request->name;
        $product->seo_description = $request->seo_description ? $request->seo_description : $request->name;
        $product->is_top = $request->top_product ? 1 : 0;
        $product->new_product = $request->new_arrival ? 1 : 0;
        $product->is_best = $request->best_product ? 1 : 0;
        $product->is_featured = $request->is_featured ? 1 : 0;
        $product->approve_by_admin = 1;
        $product->save();

        if (empty($product->barcode)) {
            $product->barcode = 'P'.str_pad((string) $product->id, 10, '0', STR_PAD_LEFT);
            $product->save();
        }

        $stockService = app(\App\Services\StockService::class);
        $warehouseId = $request->opening_warehouse_id ?: $stockService->getDefaultWarehouse()->id;
        $openingQty = (float) ($request->opening_stock ?? 0);
        $openingCost = ($request->filled('cost_price') && (float) $request->cost_price > 0)
            ? (float) $request->cost_price
            : null;
        if ($openingQty > 0) {
            $stockService->openingStock($product->id, $warehouseId, $openingQty, $openingCost, auth('admin')->id());
        } else {
            $stockService->ensureWarehouseStock($product->id, $warehouseId);
        }

        $this->syncProductWeightVariants($product, $request);

        if($request->is_specification){
            $exist_specifications=[];
            if($request->keys){
                foreach($request->keys as $index => $key){
                    if($key){
                        if($request->specifications[$index]){
                            if(!in_array($key, $exist_specifications)){
                                $productSpecification= new ProductSpecification();
                                $productSpecification->product_id = $product->id;
                                $productSpecification->product_specification_key_id = $key;
                                $productSpecification->specification = $request->specifications[$index];
                                $productSpecification->save();
                            }
                            $exist_specifications[] = $key;
                        }
                    }
                }
            }
        }
        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product.index')->with($notification);
    }

    public function show($id)
    {
        $product = Product::with('category','brand','gallery','specifications','reviews','variants','variantItems')->find($id);
        if($product->vendor_id == 0){
            $notification = 'Something went wrong';
            return response()->json(['error'=>$notification],403);
        }

        return response()->json(['product' => $product], 200);
    }


    public function edit($id)
    {
        $product = Product::with('category','brand','gallery','variants','variantItems','productWeightVariants')->find($id);
        $categories = Category::all();
        $subCategories = SubCategory::where('category_id',$product->category_id)->get();
        $childCategories = ChildCategory::where('sub_category_id', $product->sub_category_id)->get();
        $brands = Brand::all();
        $specificationKeys = ProductSpecificationKey::all();
        $productSpecifications = ProductSpecification::where('product_id',$product->id)->get();
        $units = \App\Models\Unit::activeUnits();
        $weightVariants = \App\Models\WeightVariant::active()->ordered()->get();
        $selectedWeightVariantIds = $product->productWeightVariants->pluck('weight_variant_id')->all();
        $customWeightPrices = $product->productWeightVariants->pluck('selling_price', 'weight_variant_id')->all();
        $suppliers = \App\Models\Supplier::where('status', 1)->orderBy('name')->get();

        return view('admin.edit_product',compact('categories','brands','specificationKeys','product','subCategories','childCategories','productSpecifications','units','weightVariants','selectedWeightVariantIds','customWeightPrices','suppliers'));

    }


    public function update(Request $request, $id)
    {

        $product = Product::find($id);
        $rules = [
            'short_name' => 'required',
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id,
            'category' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
            'weight' => 'nullable|numeric',
            'unit_type' => 'required|in:pcs,kg',
            'selling_price_mode' => 'nullable|in:automatic,custom',
            'weight_variant_ids' => 'nullable|array',
            'weight_variant_ids.*' => 'integer|exists:weight_variants,id',
            'weight_variant_prices' => 'nullable|array',
        ];
        $customMessages = [
            'short_name.required' => trans('admin_validation.Short name is required'),
            'short_name.unique' => trans('admin_validation.Short name is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
            'thumb_image.required' => trans('admin_validation.thumbnail is required'),
            'banner_image.required' => trans('admin_validation.Banner is required'),
            'short_description.required' => trans('admin_validation.Short description is required'),
            'long_description.required' => trans('admin_validation.Long description is required'),
            'brand.required' => trans('admin_validation.Brand is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'quantity.required' => trans('admin_validation.Quantity is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        if($request->thumb_image){
            $old_thumbnail = $product->thumb_image;
            $extention = $request->thumb_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::read($request->thumb_image)
                ->save(public_path().'/'.$image_name);
            $product->thumb_image=$image_name;
            $product->save();
            if($old_thumbnail){
                if(File::exists(public_path().'/'.$old_thumbnail))unlink(public_path().'/'.$old_thumbnail);
            }
        }


        $product->short_name = $request->short_name;
        $product->short_name_bn = $request->short_name_bn;
        $product->name = $request->name;
        $product->name_bn = $request->name_bn;
        $product->slug = $request->slug;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category ? $request->sub_category : 0;
        $product->child_category_id = $request->child_category ? $request->child_category : 0;
        $product->brand_id = $request->brand ? $request->brand : 0;
        $product->sold_qty = 0;
        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->low_stock_threshold = $request->low_stock_threshold ?? 5;
        $product->unit_type = $request->unit_type === 'kg' ? 'kg' : 'pcs';
        $product->selling_price_mode = $request->selling_price_mode === 'custom' ? 'custom' : 'automatic';
        $product->pcs_per_box = max(1, (int) ($request->pcs_per_box ?? 1));
        $product->purchase_unit = $product->unit_type === 'kg' ? 'kg' : Product::resolvePurchaseUnit($request->purchase_unit);
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        if ($request->filled('cost_price') && (float) $request->cost_price > 0) {
            $product->cost_price = (float) $request->cost_price;
        }
        $product->default_supplier_id = $request->default_supplier_id ?: null;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->tags = $request->tags;
        $product->status = $request->status;
        $product->weight = $request->weight ?? 0;
        $product->is_specification = $request->is_specification ? 1 : 0;
        $product->seo_title = $request->seo_title ? $request->seo_title : $request->name;
        $product->seo_description = $request->seo_description ? $request->seo_description : $request->name;
        $product->is_top = $request->top_product ? 1 : 0;
        $product->new_product = $request->new_arrival ? 1 : 0;
        $product->is_best = $request->best_product ? 1 : 0;
        $product->is_featured = $request->is_featured ? 1 : 0;
        if($product->vendor_id != 0){
            $product->approve_by_admin = $request->approve_by_admin;
        }
        $product->save();

        $this->syncProductWeightVariants($product, $request);

        $exist_specifications=[];
        if($request->is_specification && $request->keys){
            foreach($request->keys as $index => $key){
                if($key){
                    if($request->specifications[$index]){
                        if(!in_array($key, $exist_specifications)){
                            $existSroductSpecification = ProductSpecification::where(['product_id' => $product->id,'product_specification_key_id' => $key])->first();
                            if($existSroductSpecification){
                                $existSroductSpecification->specification = $request->specifications[$index];
                                $existSroductSpecification->save();
                            }else{
                                $productSpecification = new ProductSpecification();
                                $productSpecification->product_id = $product->id;
                                $productSpecification->product_specification_key_id = $key;
                                $productSpecification->specification = $request->specifications[$index];
                                $productSpecification->save();
                            }
                        }
                        $exist_specifications[] = $key;
                    }
                }
            }
        }
        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product.index')->with($notification);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $gallery = $product->gallery;
        $old_thumbnail = $product->thumb_image;
        $product->delete();
        if($old_thumbnail){
            if(File::exists(public_path().'/'.$old_thumbnail))unlink(public_path().'/'.$old_thumbnail);
        }
        foreach($gallery as $image){
            $old_image = $image->image;
            $image->delete();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }
        ProductVariant::where('product_id',$id)->delete();
        ProductVariantItem::where('product_id',$id)->delete();
        FlashSaleProduct::where('product_id',$id)->delete();
        ProductReport::where('product_id',$id)->delete();
        ProductReview::where('product_id',$id)->delete();
        ProductSpecification::where('product_id',$id)->delete();
        Wishlist::where('product_id',$id)->delete();
        $cartProducts = ShoppingCart::where('product_id',$id)->get();
        foreach($cartProducts as $cartProduct){
            ShoppingCartVariant::where('shopping_cart_id', $cartProduct->id)->delete();
            $cartProduct->delete();
        }
        CompareProduct::where('product_id',$id)->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product.index')->with($notification);
    }

    public function changeStatus($id){
        $product = Product::find($id);
        if($product->status == 1){
            $product->status = 0;
            $product->save();
            $message = trans('admin_validation.InActive Successfully');
        }else{
            $product->status = 1;
            $product->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function productApproved($id){
        $product = Product::find($id);
        if($product->approve_by_admin == 1){
            $product->approve_by_admin = 0;
            $product->save();
            $message = trans('admin_validation.Reject Successfully');
        }else{
            $product->approve_by_admin = 1;
            $product->save();
            $message = trans('admin_validation.Approved Successfully');
        }
        return response()->json($message);
    }



    public function removedProductExistSpecification($id){
        $productSpecification = ProductSpecification::find($id);
        $productSpecification->delete();
        $message = trans('admin_validation.Removed Successfully');
        return response()->json($message);
    }


    public function product_import(){

        return view('admin.product_import');
    }

    public function product_export(){

        $is_dummy = false;

        return Excel::download(new ProductExport($is_dummy), 'products.xlsx');
    }

    public function product_demo_export(){

        $is_dummy = true;

        return Excel::download(new ProductExport($is_dummy), 'products.xlsx');
    }


    public function store_product_import(Request $request)
    {
        try{
            Excel::import(new ProductImport, $request->file('import_file'));

            $notification=trans('Uploaded Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }catch(Exception $ex){
            $notification=trans('Please follow the instruction and input the value carefully');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }


    }

    protected function syncProductWeightVariants(Product $product, Request $request): void
    {
        if ($product->unit_type !== 'kg') {
            \App\Models\ProductWeightVariant::where('product_id', $product->id)->delete();

            return;
        }

        $selected = collect($request->input('weight_variant_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        \App\Models\ProductWeightVariant::where('product_id', $product->id)
            ->whereNotIn('weight_variant_id', $selected->all())
            ->delete();

        $prices = $request->input('weight_variant_prices', []);
        $mode = $product->selling_price_mode;

        foreach ($selected as $variantId) {
            $customPrice = null;
            if ($mode === 'custom' && isset($prices[$variantId]) && $prices[$variantId] !== '') {
                $customPrice = round((float) $prices[$variantId], 2);
            }

            \App\Models\ProductWeightVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'weight_variant_id' => $variantId,
                ],
                [
                    'selling_price' => $customPrice,
                ]
            );
        }
    }
}
