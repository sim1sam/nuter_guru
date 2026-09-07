<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\ShoppingCart;
use App\Models\User;
use App\Models\Coupon;
use App\Models\DeliveryMan;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderProduct;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\Country;
use App\Models\City;
use App\Models\CountryState;
use App\Models\ProductVariantItem;
use App\Models\ShoppingCartVariant;
use App\Models\Address;
use App\Models\OrderProductVariant;
use App\Models\FlashSaleProduct;
use App\Models\FlashSale;
use App\Models\Brand;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Mail\OrderSuccessfully;
use App\Mail\UserRegistrationFromAdmin;
use App\Services\ShippingCalculationService;
use DB;


use Illuminate\Support\Facades\Session;
use Auth;
use Hash;
use Mail;

class PosController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }

    public function Index()
    {
        Paginator::useBootstrap();
        $data['brands'] = Brand::all();
        $data['products'] = Product::with('activeVariants')->where(['vendor_id' => 0])->where(['status' => 1])->orderBy('id','desc')->paginate(18);
        $data['setting'] = Setting::first();
        $data['categories'] = Category::with('subCategories','products')->get();
        $cartData = $this->posCartViewData();
        $totals = $this->calculatePosTotals($cartData);
        $data['cart_products'] = $cartData['cart_products'];
        $data['coupon'] = $cartData['coupon'];
        $data['couponValue'] = $cartData['couponValue'];
        $data['grandTotal'] = $totals['grandTotal'];
        $data['tax'] = $totals['tax'];
        $data['discount'] = $totals['discount'];
        $data['subTotal'] = $totals['subTotal'];
        $data['shippings'] = Shipping::all();
        $data['countries'] = Country::all();
        $data['city'] = City::all();
        $data['state'] = CountryState::all();
        return view('admin.pos.index', $this->withPosSharedData($data));
    }

    public function categoryIndex($id)
    {
        Paginator::useBootstrap();
        $data['brands'] = Brand::all();
        $data['products'] = Product::where(['vendor_id' => 0])->where(['status' => 1])->where(['category_id' => $id])->orderBy('id','desc')->paginate(18);
        $data['setting'] = Setting::first();
        $data['categories'] = Category::with('subCategories','products')->get();
        $data['cart_products'] = ShoppingCart::where('user_id',Auth::guard('admin')->user()->id)->orderBy('id','desc')->get();
        $data['coupon'] = Coupon::where(['code' => 'fdfgdfg', 'status' => 1])->first();
        $data['shippings'] = Shipping::all();
        $data['countries'] = Country::all();
        $data['city'] = City::all();
        $data['state'] = CountryState::all();
        $data['couponValue'] = 'dfgdfg';
        return view('admin.pos.index', $this->withPosSharedData($data));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        Paginator::useBootstrap();
        $productsQuery = Product::where('vendor_id', 0)
        ->where('status', 1);
            if (!empty($query)) {
            $productsQuery->where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'like', '%' . $query . '%')
                ->orWhere('short_name', 'like', '%' . $query . '%');
            });
            }
        $data['products'] = $productsQuery->paginate(18);
        $data['brands'] = Brand::all();
        $data['setting'] = Setting::first();
        $data['categories'] = Category::with('subCategories','products')->get();
        $data['cart_products'] = ShoppingCart::where('user_id',Auth::guard('admin')->user()->id)->orderBy('id','desc')->get();
        $data['coupon'] = Coupon::where(['code' => 'fdfgdfg', 'status' => 1])->first();
        $data['shippings'] = Shipping::all();
        $data['countries'] = Country::all();
        $data['city'] = City::all();
        $data['state'] = CountryState::all();
        $data['couponValue'] = 'dfgdfg';
        return view('admin.pos.index', $this->withPosSharedData($data));
    }

    protected function withPosSharedData(array $data): array
    {
        $data['customers'] = User::where('status', 1)->select('id', 'name', 'email', 'phone')->orderBy('name')->get();
        $data['selected_customer'] = User::where('status', 1)->select('id', 'name', 'email', 'phone')->find(session('pos_customer_id'));
        $data['states'] = $data['state'] ?? CountryState::all();
        $data['cities'] = $data['city'] ?? City::all();

        return $data;
    }

    protected function isAjaxRequest(?Request $request = null): bool
    {
        $request = $request ?: request();

        return $request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';
    }

    protected function posCartViewData(): array
    {
        $couponCode = session('pos_coupon_code');
        $coupon = $couponCode
            ? Coupon::where(['code' => $couponCode, 'status' => 1])->first()
            : null;

        return [
            'setting' => Setting::first(),
            'cart_products' => ShoppingCart::with('card_product')
                ->where('user_id', Auth::guard('admin')->user()->id)
                ->orderBy('id', 'desc')
                ->get(),
            'coupon' => $coupon,
            'couponValue' => $couponCode ?: '',
        ];
    }

    protected function calculatePosTotals(array $viewData): array
    {
        $setting = $viewData['setting'];
        $cartProducts = $viewData['cart_products'];
        $coupon = $viewData['coupon'] ?? null;
        $grandTotal = 0;
        $taxRate = floatval($setting->tax ?? 0);
        $cuponPct = $coupon ? floatval($coupon->discount) : 0;

        foreach ($cartProducts as $product) {
            if (!$product->card_product) {
                continue;
            }
            $unit = $product->card_product->offer_price === '' || $product->card_product->offer_price === null
                ? floatval($product->card_product->price)
                : floatval($product->card_product->offer_price);
            $grandTotal += $unit * intval($product->qty);
        }

        $tax = $grandTotal * ($taxRate / 100);
        $discount = $grandTotal * ($cuponPct / 100);
        $subTotal = ($grandTotal - $discount) + $tax;

        return [
            'grandTotal' => round($grandTotal, 2),
            'discount' => round($discount, 2),
            'tax' => round($tax, 2),
            'subTotal' => round($subTotal, 2),
            'currency' => $setting->currency_icon ?? '',
            'couponValue' => $viewData['couponValue'] ?? '',
        ];
    }

    protected function posCartJsonResponse(string $message, string $type = 'success', int $status = 200)
    {
        $viewData = $this->posCartViewData();
        $totals = $this->calculatePosTotals($viewData);

        return response()->json([
            'ok' => $type === 'success',
            'message' => $message,
            'alert' => $type,
            'html' => view('admin.pos.partials.cart_refresh', $viewData)->render(),
            'totals' => $totals,
        ], $status);
    }

    protected function posCartResult(string $message, string $type = 'success')
    {
        if ($this->isAjaxRequest()) {
            return $this->posCartJsonResponse($message, $type, $type === 'success' ? 200 : 422);
        }

        return redirect()->back()->with(['messege' => $message, 'alert-type' => $type]);
    }

    public function searchCustomers(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $customers = User::where('status', 1)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', '%'.$q.'%')
                        ->orWhere('email', 'like', '%'.$q.'%')
                        ->orWhere('phone', 'like', '%'.$q.'%');
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json($customers);
    }

    public function selectCustomer(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
        ]);

        session(['pos_customer_id' => (int) $request->customer_id]);

        return response()->json(['ok' => true]);
    }

    public function customerAddress($id)
    {
        $customer = User::where('status', 1)->findOrFail($id);
        $address = Address::where('user_id', $customer->id)
            ->orderByDesc('default_shipping')
            ->orderByDesc('default_billing')
            ->first();

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
            ],
            'has_address' => (bool) ($address && $address->address),
            'address' => $address ? [
                'name' => $address->name,
                'email' => $address->email,
                'phone' => $address->phone,
                'address' => $address->address,
                'delivery_area' => $address->delivery_area ?: 'inside',
                'type' => $address->type,
                'is_default' => (bool) ($address->default_billing || $address->default_shipping),
            ] : null,
        ]);
    }

    public function AddProduct($id)
    {
        if(Auth::guard('admin')->user()){
            $check_stock = Product::where('id',$id)->select('qty')->first();
            $qty = $check_stock->qty;
            if ($qty > 0) {
                if(ShoppingCart::where('product_id', $id)->where('user_id',Auth::guard('admin')->user()->id)->exists())
                {
                    $cart = ShoppingCart::where('product_id', $id)->where('user_id',Auth::guard('admin')->user()->id)->first();
                    if($qty >= $cart->qty + 1){
                        $qty = $cart->qty + 1;
                        $datacode= array();
                        $datacode['qty'] = $qty;
                        ShoppingCart::where('product_id',$id)->where('user_id',Auth::guard('admin')->user()->id)
                         ->update($datacode);

                        return $this->posCartResult(trans('admin_validation.Product Quantity Updated'), 'success');
                    }

                    return $this->posCartResult(trans('admin_validation.This Product Are Out of Stock'), 'error');
                }

                $cart = new ShoppingCart();
                $cart->user_id = Auth::guard('admin')->user()->id;
                $cart->product_id = $id;
                $cart->qty = 1;
                $cart->save();

                return $this->posCartResult(trans('admin_validation.Product Added'), 'success');
            }

            return $this->posCartResult(trans('admin_validation.This Product Are Out of Stock'), 'error');
        }

        return $this->posCartResult(trans('admin_validation.Sry First You Need To Login'), 'error');
    }

    public function AddProductWithDetils(Request $request, $id)
    {
        $selectedValues = $request->input('selectedValues');
        if(Auth::guard('admin')->user()){
            $check_stock = Product::where('id',$id)->select('qty')->first();
            $qty = $check_stock->qty;
            if ($qty >= $request->quantity) {
                if(ShoppingCart::where('product_id', $id)->where('user_id',Auth::guard('admin')->user()->id)->exists())
                {
                    $cart = ShoppingCart::where('product_id', $id)->where('user_id',Auth::guard('admin')->user()->id)->first();
                    if($qty >= $cart->qty + $request->quantity){
                        $qty = $cart->qty + $request->quantity;
                        $datacode= array();
                        $datacode['qty'] = $qty;
                        $code_reg = ShoppingCart::where('product_id',$id)->where('user_id',Auth::guard('admin')->user()->id)
                         ->update($datacode);
                         if ($selectedValues) {
                            foreach ($selectedValues as $variantName => $selectedValue) {
                                $cart_variation = new ShoppingCartVariant();
                                $cart_variation->shopping_cart_id = $cart->id;
                                $cart_variation->variant_id = $variantName;
                                $cart_variation->variant_item_id = $selectedValue;
                                $cart_variation->save();
                            }
                        }
                    }else{
                        $notification = trans('admin_validation.This Product Are Out of Stock');
                        $notification=array('messege'=>$notification,'alert-type'=>'error');
                        return redirect()->back()->with($notification);
                    }
                   

                    $notification = trans('admin_validation.Product Quantity Updated');
                    $notification=array('messege'=>$notification,'alert-type'=>'success');
                    return redirect()->back()->with($notification);

                }else{
                    $cart = new ShoppingCart();
                    $cart->user_id = Auth::guard('admin')->user()->id;
                    $cart->product_id = $id;
                    $cart->qty = $request->quantity;
                    $cart->save();
                    if ($selectedValues) {
                        foreach ($selectedValues as $variantName => $selectedValue) {
                            $cart_variation = new ShoppingCartVariant();
                            $cart_variation->shopping_cart_id = $cart->id;
                            $cart_variation->variant_id = $variantName;
                            $cart_variation->variant_item_id = $selectedValue;
                            $cart_variation->save();
                        }
                    }
                    $notification = trans('admin_validation.Product Added');
                    $notification=array('messege'=>$notification,'alert-type'=>'success');
                    return redirect()->back()->with($notification);
                }
            }else{
                $notification = trans('admin_validation.This Product Are Out of Stock');
                $notification=array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->back()->with($notification);
            }
        }else{
            $notification = trans('admin_validation.Sry First You Need To Login');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

    }

    public function cartIncremet($id) {
        $check = ShoppingCart::where('id', $id)->first();
        if (!$check) {
            return $this->posCartResult(trans('admin_validation.Sry Somthin Went To Wrong'), 'error');
        }

        $check_stock = Product::where('id', $check->product_id)->select('qty')->first();
        $qty = $check_stock->qty ?? 0;
        if ($check->user_id == Auth::guard('admin')->user()->id) {
            if ($qty >= $check->qty + 1) {
                ShoppingCart::where('id', $id)->update(['qty' => $check->qty + 1]);
                return $this->posCartResult(trans('admin_validation.Product Quantity Updated'), 'success');
            }

            return $this->posCartResult(trans('admin_validation.This Product Are Out of Stock'), 'error');
        }

        return $this->posCartResult(trans('admin_validation.Sry Somthin Went To Wrong'), 'error');
    }

    public function cartDecrement($id) {
        $check = ShoppingCart::where('id', $id)->first();
        if (!$check) {
            return $this->posCartResult(trans('admin_validation.Sry Somthin Went To Wrong'), 'error');
        }

        if ($check->user_id == Auth::guard('admin')->user()->id && $check->qty > 1) {
            ShoppingCart::where('id', $id)->update(['qty' => $check->qty - 1]);
            return $this->posCartResult(trans('admin_validation.Product Quantity Updated'), 'success');
        }

        return $this->posCartResult(trans('admin_validation.Sry Somthin Went To Wrong'), 'error');
    }

    public function Destroy($id) {
        $check = ShoppingCart::where('id', $id)->first();
        if ($check && $check->user_id == Auth::guard('admin')->user()->id) {
            ShoppingCartVariant::where('shopping_cart_id', $id)->delete();
            ShoppingCart::where('id', $id)->delete();
            return $this->posCartResult(trans('admin_validation.Product Removed Successfully'), 'success');
        }

        return $this->posCartResult(trans('admin_validation.Sry Somthin Went To Wrong'), 'error');
    }

    public function clearCart() {
        ShoppingCart::where('user_id', Auth::guard('admin')->user()->id)->delete();
        session()->forget('pos_coupon_code');
        return $this->posCartResult(trans('admin_validation.Product Cart Clear Successfully'), 'success');
    }

    public function addCustomer(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required',
            'address' => 'required|string|max:1000',
            'delivery_area' => 'required|in:inside,outside',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'email.unique' => trans('admin_validation.Email already exist'),
            'phone.required' => trans('admin_validation.Phone Number is required'),
            'address.required' => trans('admin.Address is required'),
            'delivery_area.required' => trans('admin.Delivery area is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = new User();
        $user->name =$request->name;
        $user->email =$request->email;
        $user->phone =$request->phone;
        $user->country_id = null;
        $user->state_id = null;
        $user->city_id = null;
        $user->address =$request->address;
        $user->status = 1;
        $user->password =Hash::make(1234);
        if($user->save()){
            $address = new Address();
            $address->user_id =$user->id;
            $address->name =$request->name;
            $address->email =$request->email;
            $address->phone =$request->phone;
            $address->country_id = null;
            $address->state_id = null;
            $address->city_id = null;
            $address->address =$request->address;
            $address->type = $request->location ?: 'Home';
            $address->delivery_area = $request->delivery_area;
            $address->default_shipping = 1;
            $address->default_billing = 1;
            $address->save();

            try {
                MailHelper::setMailConfig();
                $template=EmailTemplate::where('id',8)->first();
                if ($template) {
                    $subject=$template->subject;
                    $message=$template->description;
                    $message = str_replace('{{user_name}}',$request->name,$message);
                    Mail::to($user->email)->send(new UserRegistrationFromAdmin($message,$subject,$user));
                }
            } catch (\Throwable $e) {
                // ignore mail errors
            }

            session(['pos_customer_id' => $user->id]);
            $notification = trans('admin_validation.Customer Create Successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
        }else{
            $notification = trans('admin_validation.Customer Create Not Successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }


    }

    public function applyCupon(Request $request)
    {
        if ($request->coupon == null) {
            return $this->posCartResult(trans('admin_validation.Coupon Field is required'), 'error');
        }

        $coupon = Coupon::where(['code' => $request->coupon, 'status' => 1])->first();

        if (!$coupon) {
            return $this->posCartResult(trans('admin_validation.Invalid Coupon'), 'error');
        }

        if ($coupon->expired_date < date('Y-m-d')) {
            return $this->posCartResult(trans('admin_validation.Coupon already expired'), 'error');
        }

        if ($coupon->apply_qty >= $coupon->max_quantity) {
            return $this->posCartResult(trans('admin_validation.Sorry! You can not apply this coupon'), 'error');
        }

        session(['pos_coupon_code' => $request->coupon]);

        return $this->posCartResult(trans('user.Coupon Applied'), 'success');
    }

    public function orderSubmit(Request $request){

        $admin_id = Auth::guard('admin')->user()->id;
        $total_price = 0;
        $sub_total = $request->sub_total;
        $discount = $request->discount;
        $cupon = $request->cupon;
        $tax = $request->tax;
        $customer_id = $request->customer_id;
        $shipping_id = $request->shipping_id;
        $payment_method = $request->payment_method;
        $order_status = $request->order_status;
        if($request->payment_method == 'Cash'){
            $paymetn_status = 1;
        }else{
            $paymetn_status = 0;
        }

        $cartProducts = ShoppingCart::with("product", "variants.variantItem", "weightVariant")
        ->where("user_id", $admin_id)
        ->get();

    if ($cartProducts->count() == 0) {
        $notification = trans('admin_validation.Your shopping cart is empty');
        $notification = array('messege'=>$notification,'alert-type'=>'error');
        return redirect()->back()->with($notification);
    }

    $check_cupon = Coupon::where(['code' => $cupon, 'status' => 1])->first();
        if($check_cupon){

            $datacode= array();
            $datacode['apply_qty'] = $check_cupon->apply_qty + 1;
            $code_reg = Coupon::where(['code' => $cupon, 'status' => 1])
            ->update($datacode);
        }

    $shipping = Shipping::find($shipping_id);
        if(!$shipping){
            $notification = trans('admin_validation.Shipping method not found');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        $shippingService = app(ShippingCalculationService::class);
        $resolved = $shippingService->resolveFee($shipping, $cartProducts);
        $shipping_fee = $resolved !== null ? $resolved : 0;

        $total_price = ($sub_total - $discount) + $shipping_fee + $tax;

        $totalProduct = ShoppingCart::where('user_id', $admin_id)->sum('qty');

        $order = new Order();

        $orderId = substr(rand(0, time()), 0, 10);
        $order->order_id = $orderId;
        $order->user_id = $customer_id;
        $order->total_amount = $total_price;
        $order->product_qty = $totalProduct;
        $order->payment_method = $payment_method;
        $order->transection_id = $payment_method;
        $order->payment_status = $paymetn_status;
        $order->shipping_method = $shipping->shipping_rule;
        $order->shipping_cost = $shipping_fee;
        $order->coupon_coast = $discount;
        $order->order_status = $order_status;
        $order->cash_on_delivery = $order_status;
        $order->save();
        $order_details = "";

        $setting = Setting::first();
        foreach ($cartProducts as $key => $cartProduct) {
            $variantPrice = 0;
            if ($cartProduct->variants) {
                foreach ($cartProduct->variants as $item_index => $var_item) {
                    $item = ProductVariantItem::find(
                        $var_item->variant_item_id
                    );
                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            }

            // calculate product price

            $product = Product::select(
                "id",
                "price",
                "offer_price",
                "weight",
                "vendor_id",
                "qty",
                "name"
            )->find($cartProduct->product_id);

            $price = $product->offer_price
                ? $product->offer_price
                : $product->price;

            $price = $price + $variantPrice;
            $isFlashSale = FlashSaleProduct::where([
                "product_id" => $product->id,
                "status" => 1,
            ])->first();

            $today = date("Y-m-d H:i:s");
            if ($isFlashSale) {
                $flashSale = FlashSale::first();
                if ($flashSale->status == 1) {
                    if ($today <= $flashSale->end_time) {
                        $offerPrice = ($flashSale->offer / 100) * $price;

                        $price = $price - $offerPrice;
                    }
                }
            }

            // store ordre product

            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $cartProduct->product_id;
            $orderProduct->seller_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->unit_price = $cartProduct->unit_price ?? $price;
            $orderProduct->qty = $cartProduct->qty;
            $orderProduct->weight_variant_id = $cartProduct->weight_variant_id;
            $orderProduct->variant_name_snapshot = $cartProduct->variant_name_snapshot;
            $orderProduct->unit_weight_kg = $cartProduct->unit_weight_kg;
            $orderProduct->base_quantity = $cartProduct->base_quantity ?? $cartProduct->qty;
            $orderProduct->unit_cost = $product->cost_price;
            $orderProduct->save();

            $baseQty = (float) ($cartProduct->base_quantity ?? $cartProduct->qty);
            try {
                app(\App\Services\StockService::class)->deductForSale(
                    (int) $product->id,
                    $baseQty,
                    $order->order_id,
                    Auth::guard('admin')->id(),
                    'order',
                    (int) $order->id,
                    [
                        'weight_variant_id' => $cartProduct->weight_variant_id,
                        'variant_name' => $cartProduct->variant_name_snapshot,
                        'unit_weight_kg' => $cartProduct->unit_weight_kg,
                        'unit' => ($product->unit_type ?? 'pcs') === 'kg' ? 'kg' : 'pcs',
                    ]
                );
            } catch (\InvalidArgumentException $e) {
                throw $e;
            }

            // store prouct variant

            // return $cartProduct->variants;
            foreach ($cartProduct->variants as $index => $variant) {
                $item = ProductVariantItem::find($variant->variant_item_id);
                $productVariant = new OrderProductVariant();
                $productVariant->order_product_id = $orderProduct->id;
                $productVariant->product_id = $cartProduct->product_id;
                $productVariant->variant_name = $item->product_variant_name;
                $productVariant->variant_value = $item->name;
                $productVariant->save();
            }

            $order_details .= "Product: " . $product->name . "<br>";
            $order_details .= "Quantity: " . $cartProduct->qty . "<br>";
            $order_details .=
                "Price: " .
                $setting->currency_icon .
                $cartProduct->qty * $price .
                "<br>";
        }

         // Order address only (does not change customer's saved address)
         $customer = User::find($request->customer_id);
         $saved = Address::where('user_id', $request->customer_id)
             ->orderByDesc('default_billing')
             ->first();

         $name = $customer->name ?? 'Walk-in Customer';
         $email = $customer->email ?? null;
         $phone = $customer->phone ?? null;
         $line = $request->filled('address_line')
             ? $request->address_line
             : ($saved->address ?? '');
         $deliveryArea = $request->filled('delivery_area')
             ? $request->delivery_area
             : ($saved->delivery_area ?? 'inside');

         if (! $line) {
             $notification = array('messege' => trans('admin.Address is required'), 'alert-type' => 'error');
             return redirect()->back()->with($notification);
         }

         // If customer has no saved address, save to profile for next time
         if (! $saved) {
             $saved = new Address();
             $saved->user_id = $request->customer_id;
             $saved->name = $name;
             $saved->email = $email;
             $saved->phone = $phone;
             $saved->address = $line;
             $saved->delivery_area = $deliveryArea;
             $saved->type = 'Home';
             $saved->default_shipping = 1;
             $saved->default_billing = 1;
             $saved->save();
             if ($customer) {
                 $customer->address = $line;
                 $customer->save();
             }
         }

         $orderAddress = new OrderAddress();
         $orderAddress->order_id = $order->id;
         $orderAddress->billing_name = $name;
         $orderAddress->billing_email = $email;
         $orderAddress->billing_phone = $phone;
         $orderAddress->billing_address = $line;
         $orderAddress->billing_country = 'Bangladesh';
         $orderAddress->billing_state = null;
         $orderAddress->billing_city = null;
         $orderAddress->billing_address_type = $deliveryArea;
         $orderAddress->shipping_name = $name;
         $orderAddress->shipping_email = $email;
         $orderAddress->shipping_phone = $phone;
         $orderAddress->shipping_address = $line;
         $orderAddress->shipping_country = 'Bangladesh';
         $orderAddress->shipping_state = null;
         $orderAddress->shipping_city = null;
         $orderAddress->shipping_address_type = $deliveryArea;
         $orderAddress->delivery_area = $deliveryArea;
         $orderAddress->save();

         foreach ($cartProducts as $cartProduct) {
             ShoppingCartVariant::where(
                 "shopping_cart_id",
                 $cartProduct->id
             )->delete();

             $cartProduct->delete();
         }
         
            $setting = Setting::first();
            $mailName = $orderAddress->billing_name;
            $mailEmail = $orderAddress->shipping_email ?: $orderAddress->billing_email;
            if ($mailEmail) {
                try {
                    MailHelper::setMailConfig();
                    $template = EmailTemplate::where("id", 6)->first();
                    if ($template) {
                        $subject = $template->subject;
                        $message = $template->description;
                        $message = str_replace("{{user_name}}", $mailName, $message);
                        $message = str_replace(
                            "{{total_amount}}",
                            $setting->currency_icon . $total_price,
                            $message
                        );
                        $message = str_replace("{{payment_method}}",$payment_method, $message);
                        $message = str_replace("{{payment_status}}", $paymetn_status, $message);
                        $message = str_replace("{{order_status}}", $order_status, $message);
                        $message = str_replace(
                            "{{order_date}}",
                            $order->created_at->format("d F, Y"),
                            $message
                        );
                        $message = str_replace("{{order_detail}}", $order_details, $message);
                        Mail::to($mailEmail)->send(new OrderSuccessfully($message, $subject));
                    }
                } catch (\Throwable $e) {
                    // Keep order success even if mail fails
                }
            }

        session()->forget('pos_customer_id');
        $notification = trans('admin_validation.Order Created SuccesFully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.order-show', $order->id)->with($notification);







    }


    public function bulkOrder(){
        return view('admin.pos.bulk_order');
    }

    public function bulkOrderSerch(Request $request){
        // Define the form input parameters
        $from = $request->form;
        $to = $request->to;
        $payment_status = $request->payment_status;
        $order_status = $request->order_status;

        // Start building the query
        $query = Order::query();

        // Apply filters based on form inputs
        if (!empty($from) && !empty($to)) {
            // If both "from" and "to" dates are provided, filter by date range
            $query->whereBetween('created_at', [$from, $to]);
        }

        if (!empty($payment_status)) {
            // Filter by payment_status if provided
            $query->where('payment_status', $payment_status);
        }

        if (!empty($order_status)) {
            // Filter by order_status if provided
            $query->where('order_status', $order_status);
        }

        // Execute the query
        $filteredOrders = $query->get();
        $setting = Setting::first();

        return view('admin.pos.bulk_order_change',compact('filteredOrders','setting'));
    }

    public function updateOrderStatus(Request $request)
    {
        if($request->orderIds == ''){
            $notification = trans('admin_validation.You Not Select Any Order');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        if($request->newStatus == ''){
            $notification = trans('admin_validation.You Not Select Any status');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $validatedData = $request->validate([
            'newStatus' => 'required',
            'orderIds' => 'required|array',
        ]);

        $newStatus = $validatedData['newStatus'];
        $orderIds = $validatedData['orderIds'];

        try {
            Order::whereIn('id', $orderIds)->update(['order_status' => $newStatus]);

            $notification = trans('admin_validation.Order statuses updated successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('admin.pos.bulk.order')->with($notification);
        } catch (\Exception $e) {
            $notification = trans('admin_validation.Error updating order statuses');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.pos.bulk.order')->with($notification);
        }
    }

    public function updatePosCart(Request $request)
    {
        $isAjax = $request->ajax() || $request->wantsJson();
        $rules = [
            'qty_update' => ['required', 'array'],
            'qty_update.*' => ['required', 'integer', 'min:1'],
        ];

        $messages = [
            'qty_update.required' => trans('admin_validation.Quantity is required for all products.'),
            'qty_update.*.required' => trans('admin_validation.Quantity is required for all products.'),
            'qty_update.*.integer' => trans('admin_validation.Quantity must be an integer.'),
            'qty_update.*.min' => trans('admin_validation.Quantity cannot be negative.'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $qtyUpdate = $request->input('qty_update');

        foreach ($qtyUpdate as $productId => $quantity) {
            $cartProduct = ShoppingCart::find($productId);
            if (!$cartProduct) {
                continue;
            }
            $check_stock = Product::where('id', $cartProduct->product_id)->select('qty')->first();
            $qty = $check_stock->qty ?? 0;

            if ($qty >= $quantity) {
                $cartProduct->qty = $quantity;
                $cartProduct->save();
            } else {
                $notification = trans('admin_validation.This Product Are Out of Stock');
                if ($isAjax) {
                    return response()->json(['message' => $notification], 422);
                }
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->back()->with($notification);
            }
        }

        $notification = trans('admin_validation.Update Order Quantity');
        if ($isAjax) {
            return $this->posCartJsonResponse($notification, 'success');
        }
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }



}
