<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ShoppingCart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\Setting;
use App\Models\Shipping;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Country;
use App\Models\CountryState;
use App\Models\City;
use App\Models\EmailTemplate;
use App\Helpers\GuestModeHelper;
use App\Helpers\MailHelper;
use App\Mail\OrderSuccessfully;
use Illuminate\Support\Facades\Mail;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\PaystackAndMollie;
use App\Models\BankPayment;
use App\Models\InstamojoPayment;
use App\Models\PaypalPayment;
use App\Models\SslcommerzPayment;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;
use App\Models\ProductVariantItem;
use App\Models\ShoppingCartVariant;
use App\Models\FlashSaleProduct;
use App\Models\FlashSale;
use App\Models\SmsTemplate;
use App\Models\TwilioSms;
use Cart;
use Str;
use Stripe;
use Razorpay\Api\Api;
use Exception;
use Mollie\Laravel\Facades\Mollie;
use Twilio\Rest\Client;
// use Omnipay\Omnipay;
// use Srmklive\PayPal\Services\PayPal as PayPalClient;
// // use Library\SslCommerz\SslCommerzNotification;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // No middleware - support both authenticated and guest users
    }

    public function calculateCheckoutTotals(Request $request)
    {
        $user = Auth::user();
        
        // Get cart items and calculate totals
        $cartItems = [];
        $subtotal = 0;
        $totalQty = 0;
        
        if ($user) {
            // Get cart items from database for authenticated users
            $cartItems = ShoppingCart::with(['product'])
                ->where('user_id', $user->id)
                ->get();
                
            foreach ($cartItems as $item) {
                $price = $item->product->price ?? 0;
                $subtotal += $price * $item->qty;
                $totalQty += $item->qty;
            }
        } else {
            // Get cart items from session for guest users
            $sessionCart = Session::get('guest_cart', []);
            foreach ($sessionCart as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $price = $product->price;
                    $quantity = $item['quantity'] ?? 1;
                    $subtotal += $price * $quantity;
                    $totalQty += $quantity;
                }
            }
        }
        
        // Calculate shipping fee
        $shipping_fee = 0;
        if ($request->has('shipping_method')) {
            $shipping = Shipping::find($request->shipping_method);
            $shipping_fee = $shipping ? $shipping->shipping_fee : 0;
        }
        
        // Calculate coupon discount
        $coupon_discount = 0;
        $appliedCoupon = Session::get('applied_coupon');
        if ($appliedCoupon) {
            if ($appliedCoupon->discount_type == 'percentage') {
                $coupon_discount = ($subtotal * $appliedCoupon->discount) / 100;
            } else {
                $coupon_discount = $appliedCoupon->discount;
            }
        }
        
        $total_amount = $subtotal + $shipping_fee - $coupon_discount;
        
        return [
            'subtotal' => $subtotal,
            'total_amount' => $total_amount,
            'total_qty' => $totalQty,
            'shipping_fee' => $shipping_fee,
            'coupon_discount' => $coupon_discount
        ];
    }

    public function getCheckoutData(Request $request)
    {
        try {
            if (! GuestModeHelper::guestCartAllowed()) {
                return GuestModeHelper::loginRequiredResponse('Please login to checkout.');
            }

            $user = Auth::user();
            
            // Get shipping methods
            $shippingMethods = Shipping::all();
            // Add cost property for frontend compatibility
            $shippingMethods->each(function($shipping) {
                $shipping->cost = $shipping->shipping_fee;
            });
            
            // Get countries for address form
            $countries = Country::where('status', 1)->get();
            
            // Get payment gateway configurations
            $stripePaymentInfo = StripePayment::first();
            $razorpayPaymentInfo = RazorpayPayment::first();
            $flutterwavePaymentInfo = Flutterwave::first();
            $paypalPaymentInfo = PaypalPayment::first();
            $bankPaymentInfo = BankPayment::first();
            $paystackAndMollie = PaystackAndMollie::first();
            $instamojo = InstamojoPayment::first();
            $sslcommerz = SslcommerzPayment::first();
            
            // Get cart items
            $cartItems = [];
            if ($user) {
                // Get cart items from database for authenticated users
                $cartItems = ShoppingCart::with(['product'])
                    ->where('user_id', $user->id)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->product->name ?? 'Unknown Product',
                            'product_image' => $item->product->thumb_image ?? '',
                            'product_price' => $item->product->price ?? 0,
                            'quantity' => $item->qty,
                            'variants' => []
                        ];
                    });
            } else {
                // Get cart items from session for guest users
                $sessionCart = Session::get('guest_cart', []);
                foreach ($sessionCart as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $cartItems[] = [
                            'id' => $item['id'] ?? uniqid(),
                            'product_id' => $item['product_id'],
                            'product_name' => $product->name,
                            'product_image' => $product->thumb_image,
                            'product_price' => $product->price,
                            'quantity' => $item['quantity'] ?? 1, // Fixed: session cart uses 'quantity'
                            'variants' => $item['variants'] ?? []
                        ];
                    }
                }
            }
            
            // Get user addresses if authenticated (default first)
            $addresses = [];
            if ($user) {
                $addresses = Address::with('country')
                    ->where('user_id', $user->id)
                    ->orderByDesc('default_billing')
                    ->orderByDesc('default_shipping')
                    ->get();
            }
            
            // Calculate totals - pass default shipping method if none selected
            $defaultShippingMethod = $shippingMethods->first();
            if ($defaultShippingMethod && !$request->has('shipping_method')) {
                $request->merge(['shipping_method' => $defaultShippingMethod->id]);
            }
            $totals = $this->calculateCheckoutTotals($request);
            
            return response()->json([
                'success' => true,
                'cart_items' => $cartItems,
                'shipping_methods' => $shippingMethods,
                'addresses' => $addresses,
                'countries' => $countries,
                'user' => $user ? [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '',
                    'first_name' => $user->first_name ?? '',
                    'last_name' => $user->last_name ?? ''
                ] : null,
                'stripePaymentInfo' => $stripePaymentInfo,
                'razorpayPaymentInfo' => $razorpayPaymentInfo,
                'flutterwavePaymentInfo' => $flutterwavePaymentInfo,
                'paypalPaymentInfo' => $paypalPaymentInfo,
                'bankPaymentInfo' => $bankPaymentInfo,
                'paystackAndMollie' => $paystackAndMollie,
                'instamojo' => $instamojo,
                'sslcommerz' => $sslcommerz,
                'subtotal' => $totals['subtotal'],
                'total_amount' => $totals['total_amount'],
                'total_qty' => $totals['total_qty'],
                'shipping_fee' => $totals['shipping_fee'],
                'coupon_discount' => $totals['coupon_discount']
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getCheckoutData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load checkout data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle successful Stripe payment return
     */
    public function stripeSuccess(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            
            if (!$orderId) {
                return redirect()->route('home')->with('error', trans('Invalid payment session'));
            }

            $user = Auth::user();
            $order = Order::where('id', $orderId)
                          ->where('user_id', $user->id)
                          ->first();

            if (!$order) {
                return redirect()->route('home')->with('error', trans('Order not found'));
            }

            // Verify payment with Stripe
            $stripe = StripePayment::first();
            \Stripe\Stripe::setApiKey($stripe->stripe_secret);

            // Get the session to verify payment
            $sessions = \Stripe\Checkout\Session::all([
                'limit' => 10,
            ]);

            $paymentSuccessful = false;
            $transactionId = null;

            foreach ($sessions->data as $session) {
                if (isset($session->metadata->order_id) && 
                    $session->metadata->order_id == $orderId && 
                    $session->payment_status === 'paid') {
                    $paymentSuccessful = true;
                    $transactionId = $session->payment_intent;
                    break;
                }
            }

            if ($paymentSuccessful) {
                // Update order payment status
                $order->update([
                    'payment_status' => 1,
                    'transaction_id' => $transactionId,
                    'payment_method' => 'Stripe',
                ]);

                // Send order success email
                $this->sendOrderSuccessMail(
                    $user,
                    $order->total_amount,
                    'Stripe',
                    1,
                    $order,
                    $order->orderProducts
                );

                // Clear cart and session
                $this->clearCartAfterOrder($user);
                session()->forget(['temp_order_id', 'coupon_code']);

                return redirect()->route('order.success', ['order' => encodeOrderId($order->order_id)])
                               ->with('success', trans('Payment completed successfully'));
            } else {
                return redirect()->route('checkout')->with('error', trans('Payment verification failed'));
            }

        } catch (\Exception $e) {
            \Log::error('Stripe success callback error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', trans('Payment processing error'));
        }
    }

    /**
     * Handle cancelled Stripe payment return
     */
    public function stripeCancel(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $user = Auth::user();
            $order = Order::where('id', $orderId)
                          ->where('user_id', $user->id)
                          ->where('payment_status', 0)
                          ->first();

            if ($order) {
                // Optionally delete the unpaid order or mark it as cancelled
                // $order->delete(); // or $order->update(['order_status' => 'cancelled']);
            }
        }

        return redirect()->route('checkout')->with('error', trans('Payment was cancelled'));
    }
    
    public function applyCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string'
            ]);
            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('status', 1)
                ->where('expired_date', '>=', now()->format('Y-m-d'))
                ->first();
                
            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired coupon code'
                ]);
            }
            
            if ($coupon->apply_qty >= $coupon->max_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon usage limit exceeded'
                ]);
            }
            
            // Store coupon in session
            Session::put('applied_coupon', $coupon);
            
            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'coupon' => [
                    'code' => $coupon->code,
                    'discount_type' => $coupon->discount_type,
                    'discount' => $coupon->discount
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in applyCoupon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply coupon: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function placeOrder(Request $request)
    {
        if (! GuestModeHelper::guestCartAllowed()) {
            if ($request->expectsJson()) {
                return GuestModeHelper::loginRequiredResponse('Please login to place an order.');
            }

            return redirect()->route('login', ['redirect' => route('checkout')])
                ->with('error', 'Please login to place an order.');
        }

        // Validate request data
        $validatedData = $request->validate([
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'nullable|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string|max:1000',
            'billing_delivery_area' => 'required|in:inside,outside',
            'billing_country' => 'nullable|integer|exists:countries,id',
            'shipping_method' => 'required|exists:shippings,id',
            'payment_method' => 'required|in:cash_on_delivery,credit_card,paypal,stripe,pay_later,razorpay,flutterwave,mollie,instamojo,paystack,sslcommerz,bank_payment',
            'same_as_billing' => 'nullable|boolean',
            'shipping_first_name' => 'nullable|string|max:255',
            'shipping_last_name' => 'nullable|string|max:255',
            'shipping_email' => 'nullable|email|max:255',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_address' => 'nullable|string|max:1000',
            'shipping_delivery_area' => 'nullable|in:inside,outside',
            'shipping_country' => 'nullable|integer|exists:countries,id',
            'order_notes' => 'nullable|string|max:1000'
        ]);
        
        try {
            $user = Auth::user();
            
            // Calculate totals using helper method
            // Create a temporary request with shipping method for calculation
            $tempRequest = clone $request;
            $tempRequest->merge(['shipping_method' => $validatedData['shipping_method']]);
            $checkoutTotals = $this->calculateCheckoutTotals($tempRequest);
            $total_price = $checkoutTotals['total_amount'];
            $totalProduct = $checkoutTotals['total_qty'];
            $shipping_fee = $checkoutTotals['shipping_fee'] ?? 0; // Ensure not null
            $coupon_price = $checkoutTotals['coupon_discount'];
            
            // Debug logging
            \Log::info('Checkout totals debug', [
                'shipping_method_id' => $validatedData['shipping_method'],
                'shipping_fee' => $shipping_fee,
                'checkout_totals' => $checkoutTotals
            ]);
            $shipping = Shipping::find($validatedData['shipping_method']);
            
            if (!$shipping) {
                return redirect()->back()->withErrors(['error' => 'Invalid shipping method'])->withInput();
            }
            
            // Use the webOrderStore method that follows API pattern
            $orderResult = $this->webOrderStore(
                $request,
                $total_price,
                $totalProduct,
                $validatedData['payment_method'],
                null, // transaction_id
                $validatedData['payment_method'] === 'cash_on_delivery' ? 0 : 0, // payment_status
                $shipping,
                $shipping_fee,
                $coupon_price,
                $validatedData['payment_method'] === 'cash_on_delivery' ? 1 : 0, // cash_on_delivery
                null, // billing_address_id - will create from form data
                null  // shipping_address_id - will create from form data
            );
            
            if ($validatedData['payment_method'] === 'cash_on_delivery') {
                try {
                    $this->sendWebOrderSuccessEmail($orderResult['order'], $orderResult['order_details']);
                } catch (\Throwable $emailError) {
                    \Log::warning('Order confirmation email failed: ' . $emailError->getMessage(), [
                        'order_id' => $orderResult['order']->order_id ?? null,
                        'user_id' => Auth::id(),
                    ]);
                }

                return redirect()->route('order.success', ['order' => encodeOrderId($orderResult['order']->order_id)])
                    ->with('success', 'Order placed successfully!');
            } else {
                // For other payment methods, redirect to payment gateway
                return $this->getPaymentRedirectUrl($orderResult['order'], $validatedData['payment_method'], $request);
            }
             
        } catch (\Throwable $e) {
            \Log::error('Order placement failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('checkout')
                ->with('error', 'Failed to place order. Please try again or contact support if the problem persists.')
                ->withInput();
        }
    }

    /**
     * Web-based order store method (similar to API but for session cart)
     */
    public function webOrderStore(
        $request,
        $total_price,
        $totalProduct,
        $payment_method,
        $transaction_id,
        $payment_status,
        $shipping,
        $shipping_fee,
        $coupon_price,
        $cash_on_delivery,
        $billing_address_id,
        $shipping_address_id
    ) {
        $user = Auth::user();
        $cartItems = $this->getCartItems($user);

        if ($cartItems->isEmpty()) {
            throw new \Exception('Your shopping cart is empty');
        }

        // Handle guest user - create or get default guest user
        if (!$user) {
            $user = $this->getOrCreateGuestUser($request);
        }

        $order = new Order();
        $orderId = substr(rand(0, time()), 0, 10);
        $order->order_id = $orderId;
        $order->user_id = $user->id;
        $order->total_amount = $total_price;
        $order->product_qty = $totalProduct;
        $order->payment_method = $payment_method;
        $order->transection_id = $transaction_id;
        $order->payment_status = $payment_status;
        $order->shipping_method = $shipping->shipping_rule ?? 'Standard';
        $order->shipping_cost = $shipping_fee ?? 0;
        $order->coupon_coast = $coupon_price;
        $order->order_status = 0;
        $order->cash_on_delivery = $cash_on_delivery;
        $order->save();

        $order_details = "";
        $setting = Setting::first();

        foreach ($cartItems as $cartItem) {
            $variantPrice = 0;
            
            // Handle both object and array formats
            $variants = is_object($cartItem) ? $cartItem->variants : ($cartItem['variants'] ?? []);
            
            if ($variants && (is_array($variants) || $variants instanceof \Illuminate\Support\Collection)) {
                foreach ($variants as $variant) {
                    $variantItemId = is_object($variant) ? ($variant->variant_item_id ?? null) : ($variant['variant_item_id'] ?? null);
                    $item = ProductVariantItem::find($variantItemId);
                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            }

            $productId = is_object($cartItem) ? $cartItem->product_id : $cartItem['product_id'];
            $product = Product::select('id', 'price', 'offer_price', 'weight', 'vendor_id', 'qty', 'name')
                ->find($productId);

            if (!$product) {
                continue;
            }

            $price = $product->offer_price ? $product->offer_price : $product->price;
            $price = $price + $variantPrice;

            // Check for flash sale
            $isFlashSale = FlashSaleProduct::where([
                'product_id' => $product->id,
                'status' => 1,
            ])->first();

            $today = date('Y-m-d H:i:s');
            if ($isFlashSale) {
                $flashSale = FlashSale::first();
                if ($flashSale && $flashSale->status == 1) {
                    if ($today <= $flashSale->end_time) {
                        $offerPrice = ($flashSale->offer / 100) * $price;
                        $price = $price - $offerPrice;
                    }
                }
            }

            // Store order product
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $productId;
            $orderProduct->seller_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->unit_price = $price;
            $orderProduct->qty = is_object($cartItem) ? $cartItem->qty : $cartItem['qty'];
            $orderProduct->save();

            $cartQty = is_object($cartItem) ? $cartItem->qty : $cartItem['qty'];
            try {
                app(\App\Services\StockService::class)->deductForSale(
                    (int) $product->id,
                    (int) $cartQty,
                    $order->order_id,
                    null,
                    'order',
                    (int) $order->id
                );
            } catch (\InvalidArgumentException $e) {
                $product->qty = max(0, (int) $product->qty - (int) $cartQty);
                $product->save();
            }

            // Store product variants
            if ($variants && (is_array($variants) || $variants instanceof \Illuminate\Support\Collection)) {
                foreach ($variants as $variant) {
                    $variantItemId = is_object($variant) ? ($variant->variant_item_id ?? null) : ($variant['variant_item_id'] ?? null);
                    $item = ProductVariantItem::find($variantItemId);
                    if ($item) {
                        $productVariant = new OrderProductVariant();
                        $productVariant->order_product_id = $orderProduct->id;
                        $productVariant->product_id = $productId;
                        $productVariant->variant_name = $item->product_variant_name;
                        $productVariant->variant_value = $item->name;
                        $productVariant->save();
                    }
                }
            }

            $order_details .= "Product: " . $product->name . "<br>";
            $order_details .= "Quantity: " . $cartQty . "<br>";
            $order_details .= "Price: " . ($setting->currency_icon ?? '$') . ($cartQty * $price) . "<br>";
        }

        // Store shipping and billing address
        if ($billing_address_id && $shipping_address_id) {
            // Use existing addresses
            $billing = Address::find($billing_address_id);
            $shipping_addr = Address::find($shipping_address_id);
            
            if ($billing && $shipping_addr) {
                $orderAddress = new OrderAddress();
                $orderAddress->order_id = $order->id;
                $orderAddress->billing_name = $billing->name;
                $orderAddress->billing_email = $billing->email;
                $orderAddress->billing_phone = $billing->phone;
                $orderAddress->billing_address = $billing->address;
                $orderAddress->billing_country = $billing->country->name ?? 'Bangladesh';
                $orderAddress->billing_state = '';
                $orderAddress->billing_city = '';
                $orderAddress->billing_address_type = $billing->type ?? '';
                $orderAddress->delivery_area = $billing->delivery_area ?? 'inside';
                $orderAddress->shipping_name = $shipping_addr->name;
                $orderAddress->shipping_email = $shipping_addr->email;
                $orderAddress->shipping_phone = $shipping_addr->phone;
                $orderAddress->shipping_address = $shipping_addr->address;
                $orderAddress->shipping_country = $shipping_addr->country->name ?? 'Bangladesh';
                $orderAddress->shipping_state = '';
                $orderAddress->shipping_city = '';
                $orderAddress->shipping_address_type = $shipping_addr->type ?? '';
                $orderAddress->save();
            }
        } else {
            // Create address from form data
            $this->createOrderAddresses($order, $request->all());
        }

        // Clear cart
        if ($user) {
            ShoppingCart::where('user_id', $user->id)->delete();
        } else {
            session()->forget('guest_cart');
        }

        return [
            'order' => $order,
            'order_details' => $order_details
        ];
    }

    /**
     * Get or create a guest user for order placement
     */
    private function getOrCreateGuestUser($request)
    {
        // Create a guest user with minimal required information
        $guestEmail = 'guest_' . time() . '_' . rand(1000, 9999) . '@guest.local';
        
        // Check if we have guest information from the request
        $guestName = 'Guest User';
        $guestPhone = '';
        
        // Try to get guest info from billing address if available
        if (isset($request['billing_name'])) {
            $guestName = $request['billing_name'];
        }
        if (isset($request['billing_phone'])) {
            $guestPhone = $request['billing_phone'];
        }
        if (isset($request['billing_email'])) {
            // Use provided email if it looks valid, otherwise use generated one
            if (filter_var($request['billing_email'], FILTER_VALIDATE_EMAIL)) {
                $guestEmail = $request['billing_email'];
            }
        }
        
        // Check if a guest user with this email already exists
        $existingUser = User::where('email', $guestEmail)->first();
        if ($existingUser) {
            return $existingUser;
        }
        
        // Create new guest user
        $guestUser = new User();
        $guestUser->name = $guestName;
        $guestUser->email = $guestEmail;
        $guestUser->phone = $guestPhone;
        $guestUser->password = Hash::make('guest_password_' . time());
        $guestUser->status = 1;
        $guestUser->email_verified = 1; // Skip email verification for guest users
        $guestUser->save();
        
        return $guestUser;
    }

    private function createSimpleGuestUser()
    {
        // Create a simple guest user for cases where we don't have request data
        $guestUser = new User();
        $guestUser->name = 'Guest User';
        $guestUser->email = 'guest_' . time() . '@example.com';
        $guestUser->phone = '';
        $guestUser->password = Hash::make('guest_password_' . time());
        $guestUser->status = 1;
        $guestUser->email_verified = 1;
        $guestUser->save();
        
        return $guestUser;
    }

    /**
     * Send order success email for web orders
     */
    public function sendWebOrderSuccessEmail($order, $order_details)
    {
        $user = Auth::user();
        if (!$user) {
            return; // Skip email for guest orders for now
        }

        $setting = Setting::first();
        MailHelper::setMailConfig();
        $template = EmailTemplate::where('id', 6)->first();
        
        if (!$template) {
            return;
        }

        $subject = $template->subject;
        $message = $template->description;
        $message = str_replace('{{user_name}}', $user->name, $message);
        $message = str_replace('{{total_amount}}', ($setting->currency_icon ?? '$') . $order->total_amount, $message);
        $message = str_replace('{{payment_method}}', $order->payment_method, $message);
        $message = str_replace('{{payment_status}}', $order->payment_status == 1 ? 'Paid' : 'Pending', $message);
        $message = str_replace('{{order_status}}', 'Pending', $message);
        $message = str_replace('{{order_date}}', $order->created_at->format('d F, Y'), $message);
        $message = str_replace('{{order_detail}}', $order_details, $message);
        
        try {
            Mail::to($user->email)->send(new OrderSuccessfully($message, $subject));
        } catch (\Exception $e) {
            \Log::error('Failed to send order success email: ' . $e->getMessage());
        }
    }

    /**
     * Get payment redirect URL based on payment method
     */
    private function getPaymentRedirectUrl($order, $paymentMethod, $request)
    {
        // Store order ID in session for payment processing
        session(['temp_order_id' => $order->id]);
        
        switch ($paymentMethod) {
            case 'stripe':
                return redirect()->route('pay-with-stripe');
            case 'paypal':
                return redirect()->route('pay-with-paypal');
            case 'razorpay':
                return redirect()->route('razorpay-order');
            case 'flutterwave':
                return redirect()->route('pay-with-flutterwave');
            case 'mollie':
                return redirect()->route('pay-with-mollie');
            case 'instamojo':
                return redirect()->route('pay-with-instamojo');
            case 'paystack':
                return redirect()->route('pay-with-paystack');
            case 'sslcommerz':
                return redirect()->route('pay-with-sslcommerz');
            case 'bank_payment':
                return redirect()->route('bank-payment');
            default:
                return redirect()->back()->with('error', trans('Invalid payment method'));
        }
    }

    /**
     * Get cart items for authenticated or guest users
     */
    private function getCartItems($user)
    {
        if ($user) {
            return ShoppingCart::with(['product', 'variants.variantItem'])
                ->where('user_id', $user->id)
                ->get();
        }
        
        $cartItems = [];
        $sessionCart = Session::get('guest_cart', []);
        
        foreach ($sessionCart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $cartItems[] = (object) [
                    'product_id' => $item['product_id'],
                    'qty' => $item['quantity'], // Fixed: session cart uses 'quantity' not 'qty'
                    'product' => $product,
                    'variants' => collect($item['variants'] ?? [])
                ];
            }
        }
        
        return collect($cartItems);
    }

    /**
     * Calculate order totals including subtotal, shipping, tax, and coupon discount
     */
    private function calculateOrderTotals($cartItems, $shippingMethodId)
    {
        // Calculate subtotal
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $itemPrice = $item->product->price;
            
            // Add variant prices
            if (isset($item->variants)) {
                foreach ($item->variants as $variant) {
                    if (is_object($variant) && isset($variant->variantItem)) {
                        $itemPrice += $variant->variantItem->price;
                    } elseif (is_array($variant) && isset($variant['variant_price'])) {
                        $itemPrice += $variant['variant_price'];
                    }
                }
            }
            
            $subtotal += $itemPrice * $item->qty;
        }
        
        // Get shipping cost
        $shipping = Shipping::find($shippingMethodId);
        $shippingCost = $shipping ? $shipping->cost : 0;
        
        // Apply coupon discount
        $couponDiscount = 0;
        $appliedCoupon = Session::get('applied_coupon');
        if ($appliedCoupon) {
            if ($appliedCoupon->discount_type == 'percentage') {
                $couponDiscount = ($subtotal * $appliedCoupon->discount) / 100;
            } else {
                $couponDiscount = $appliedCoupon->discount;
            }
        }
        
        // No tax calculation
        $tax = 0;
        $total = $subtotal + $shippingCost - $couponDiscount;
        
        return [
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'shipping' => $shipping,
            'coupon_discount' => $couponDiscount,
            'tax' => $tax,
            'total' => $total
        ];
    }

    /**
     * Create order instance with all necessary data
     */
    private function createOrder($user, $cartItems, $orderTotals, $validatedData)
    {
        // Handle guest user - create or get default guest user
        if (!$user) {
            $user = $this->getOrCreateGuestUser($validatedData);
        }
        
        $order = new Order();
        
        // Basic order information
        $order->order_id = 'ORD-' . time() . '-' . rand(1000, 9999);
        $order->user_id = $user->id;
        $order->total_amount = $orderTotals['total'];
        $order->product_qty = $cartItems->sum('qty');
        $order->payment_method = $validatedData['payment_method'];
        
        // Set payment status: 0 for unpaid (cash_on_delivery, pay_later), 1 for paid (others)
        $order->payment_status = in_array($validatedData['payment_method'], ['cash_on_delivery', 'pay_later']) ? 0 : 1;
        
        // Shipping and cost information
        $order->shipping_method = $orderTotals['shipping']->shipping_rule ?? 'Standard';
        $order->shipping_cost = $orderTotals['shipping_cost'] ?? 0;
        $order->coupon_coast = $orderTotals['coupon_discount'];
        
        // Order status and flags
        $order->order_status = 0; // Pending
        $order->cash_on_delivery = $validatedData['payment_method'] == 'cash_on_delivery' ? 1 : 0;
        
        // Transaction ID for pay_later orders
        if ($validatedData['payment_method'] == 'pay_later') {
            $order->transection_id = 'PAY_LATER_' . time();
        }
        
        // Order notes
        $order->order_notes = $validatedData['order_notes'] ?? null;
        
        return $order;
    }

    /**
     * Create order products from cart items
     */
    private function createOrderProducts($order, $cartItems, $validatedData)
    {
        foreach ($cartItems as $item) {
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $item->product_id;
            $orderProduct->seller_id = $item->product->vendor_id ?? 0;
            $orderProduct->product_name = $item->product->name;
            $orderProduct->product_price = $item->product->price;
            $orderProduct->qty = $item->qty;
            $orderProduct->save();
        }
        
        // Create order addresses
        $this->createOrderAddresses($order, $validatedData);
    }
    
    /**
     * Create order addresses
     */
    private function createOrderAddresses($order, $validatedData)
    {
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $order->id;

        $billingCountry = !empty($validatedData['billing_country'])
            ? Country::find($validatedData['billing_country'])
            : Country::where('name', 'like', 'Bangladesh%')->first();
        $billingCountryName = $billingCountry ? $billingCountry->name : 'Bangladesh';
        $billingArea = $validatedData['billing_delivery_area'] ?? 'inside';
        $billingFirst = $validatedData['billing_first_name'] ?? '';
        $billingLast = trim((string) ($validatedData['billing_last_name'] ?? ''));
        $billingName = trim($billingFirst . ($billingLast !== '' && $billingLast !== '-' ? ' ' . $billingLast : ''));

        // Billing information
        $orderAddress->billing_name = $billingName !== '' ? $billingName : $billingFirst;
        $orderAddress->billing_email = $validatedData['billing_email'];
        $orderAddress->billing_phone = $validatedData['billing_phone'];
        $orderAddress->billing_address = $validatedData['billing_address'];
        $orderAddress->billing_country = $billingCountryName;
        $orderAddress->billing_state = '';
        $orderAddress->billing_city = '';
        $orderAddress->billing_address_type = 'billing';
        $orderAddress->delivery_area = $billingArea;

        // Shipping information
        if (!empty($validatedData['same_as_billing'])) {
            $orderAddress->shipping_name = $orderAddress->billing_name;
            $orderAddress->shipping_email = $orderAddress->billing_email;
            $orderAddress->shipping_phone = $orderAddress->billing_phone;
            $orderAddress->shipping_address = $orderAddress->billing_address;
            $orderAddress->shipping_country = $billingCountryName;
            $orderAddress->shipping_state = '';
            $orderAddress->shipping_city = '';
        } else {
            $shippingCountry = !empty($validatedData['shipping_country'])
                ? Country::find($validatedData['shipping_country'])
                : $billingCountry;
            $orderAddress->shipping_name = trim(($validatedData['shipping_first_name'] ?? $validatedData['billing_first_name']) . ' ' . ($validatedData['shipping_last_name'] ?? $validatedData['billing_last_name']));
            $orderAddress->shipping_email = $validatedData['shipping_email'] ?? $validatedData['billing_email'];
            $orderAddress->shipping_phone = $validatedData['shipping_phone'] ?? $validatedData['billing_phone'];
            $orderAddress->shipping_address = $validatedData['shipping_address'] ?? $validatedData['billing_address'];
            $orderAddress->shipping_country = $shippingCountry ? $shippingCountry->name : $billingCountryName;
            $orderAddress->shipping_state = '';
            $orderAddress->shipping_city = '';
            $orderAddress->delivery_area = $validatedData['shipping_delivery_area'] ?? $billingArea;
        }
        $orderAddress->shipping_address_type = 'shipping';

        $orderAddress->save();
    }

    /**
     * Clear cart and session data after successful order
     */
    private function clearCartAndSession($user)
    {
        // Clear cart
        if ($user) {
            ShoppingCart::where('user_id', $user->id)->delete();
        } else {
            Session::forget('guest_cart');
        }
        
        // Clear applied coupon
        Session::forget('applied_coupon');
    }

    /**
     * Send order confirmation email
     */
    private function sendOrderConfirmationEmail($order)
    {
        try {
            $user = $order->user;
            $setting = Setting::first();
            
            // Set mail configuration
            MailHelper::setMailConfig();
            
            // Get email template
            $template = EmailTemplate::where('id', 6)->first();
            $subject = $template->subject;
            $message = $template->description;
            
            // Replace template variables
            $message = str_replace('{{user_name}}', $user->name, $message);
            $message = str_replace('{{total_amount}}', $setting->currency_icon . $order->total_amount, $message);
            $message = str_replace('{{payment_method}}', ucfirst(str_replace('_', ' ', $order->payment_method)), $message);
            $message = str_replace('{{payment_status}}', $order->payment_status == 1 ? 'Paid' : 'Pending', $message);
            $message = str_replace('{{order_status}}', 'Pending', $message);
            $message = str_replace('{{order_date}}', $order->created_at->format('d F, Y'), $message);
            
            // Generate order details
            $order_details = '';
            foreach ($order->orderProducts as $orderProduct) {
                $order_details .= $orderProduct->product_name . ' (Qty: ' . $orderProduct->qty . ') - ' . $setting->currency_icon . $orderProduct->unit_price . "\n";
            }
            
            $message = str_replace('{{order_detail}}', $order_details, $message);
            
            // Send email
            Mail::to($user->email)->send(new OrderSuccessfully($message, $subject));
            
            \Log::info('Order confirmation email sent successfully for order: ' . $order->order_id);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            throw $e;
        }
    }

    public function cashOnDelivery(Request $request)
    {
        $rules = [
            'shipping_address_id' => 'required',
            'billing_address_id' => 'required',
            'shipping_method_id' => 'required',
        ];

        $customMessages = [
            'shipping_address_id.required' => trans('Shipping address is required'),
            'billing_address_id.required' => trans('Billing address is required'),
            'shipping_method_id.required' => trans('Shipping method is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        if (Auth::check()) {
            $user = Auth::user();
            $cartItems = $this->getCartItems($user);
        } else {
            // Handle guest checkout
            $cartItems = $this->getCartItems(null);
            $user = null;
        }

        if($cartItems->count() == 0){
            return response()->json(['message' => trans('Your shopping cart is empty')], 403);
        }

        $total = $this->calculateOrderTotals($request->coupon, $request->shipping_method_id);
        $totalProduct = $cartItems->sum('qty');

        $order_result = $this->orderStore(
            $user,
            $total['total'],
            $totalProduct,
            'Cash on Delivery',
            'cash_on_delivery',
            0,
            $total['shipping'],
            $total['shipping_cost'],
            $total['coupon_discount'],
            1,
            $request->billing_address_id,
            $request->shipping_address_id
        );

        $this->sendOrderSuccessMail(
            $user,
            $total['total'],
            'Cash on Delivery',
            0,
            $order_result['order'],
            $order_result['order_details']
        );

        $notification = trans('Order submitted successfully. Please wait for admin approval');
        $order = $order_result['order'];
        $order_id = $order->order_id;

        return response()->json([
            'message' => $notification,
            'order_id' => $order_id,
            'redirect_url' => route('order.success', ['order' => encodeOrderId($order_id)])
        ], 200);
    }

    public function payWithStripe(Request $request)
    {
        $user = Auth::user();
        $orderId = session('temp_order_id');

        // Check if this is for an existing order
        if ($orderId) {
            return $this->payExistingOrderWithStripe($request, $orderId);
        }

        // For new orders, redirect to Stripe Checkout (hosted payment page)
        return $this->createStripeCheckoutSession($request);
    }

    /**
     * Create Stripe Checkout Session for hosted payment page
     */
    private function createStripeCheckoutSession(Request $request)
    {
        try {
            $user = Auth::user();
            $orderId = session('temp_order_id');
            
            if (!$orderId) {
                return redirect()->back()->with('error', trans('Order session expired. Please try again.'));
            }

            // Find the order
            $order = Order::where('id', $orderId)
                          ->where('user_id', $user->id)
                          ->where('payment_status', 0)
                          ->first();

            if (!$order) {
                return redirect()->back()->with('error', trans('Order not found or already paid'));
            }

            // Validate Stripe configuration
            $stripe = StripePayment::first();
            if (!$stripe || !$stripe->stripe_secret || !$stripe->currency_code) {
                \Log::error('Stripe configuration is incomplete', [
                    'stripe_exists' => $stripe ? true : false,
                    'secret_exists' => $stripe && $stripe->stripe_secret ? true : false,
                    'currency_exists' => $stripe && $stripe->currency_code ? true : false
                ]);
                return redirect()->back()->with('error', trans('Payment service is temporarily unavailable. Please contact support.'));
            }

            $payableAmount = round($order->total_amount * $stripe->currency_rate, 2);
            
            // Validate amount
            if ($payableAmount <= 0) {
                \Log::error('Invalid payment amount calculated', [
                    'order_amount' => $order->total_amount,
                    'currency_rate' => $stripe->currency_rate,
                    'calculated_amount' => $payableAmount
                ]);
                return redirect()->back()->with('error', trans('Invalid payment amount. Please contact support.'));
            }
            
            \Stripe\Stripe::setApiKey($stripe->stripe_secret);

            // Create Stripe Checkout Session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($stripe->currency_code),
                        'product_data' => [
                            'name' => 'Order #' . $order->order_id,
                            'description' => 'Payment for Order #' . $order->order_id,
                        ],
                        'unit_amount' => $payableAmount * 100, // Stripe expects amount in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success', ['order_id' => $order->id]),
                'cancel_url' => route('stripe.cancel', ['order_id' => $order->id]),
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                ],
            ]);

            // Redirect to Stripe Checkout
            return redirect($session->url);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            \Log::error('Stripe Invalid Request: ' . $e->getMessage(), [
                'order_id' => $orderId ?? null,
                'user_id' => $user->id ?? null
            ]);
            return redirect()->back()->with('error', trans('Invalid payment request. Please check your order details and try again.'));
        } catch (\Stripe\Exception\AuthenticationException $e) {
            \Log::error('Stripe Authentication Error: ' . $e->getMessage());
            return redirect()->back()->with('error', trans('Payment service authentication failed. Please contact support.'));
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            \Log::error('Stripe API Connection Error: ' . $e->getMessage());
            return redirect()->back()->with('error', trans('Unable to connect to payment service. Please check your internet connection and try again.'));
        } catch (\Stripe\Exception\RateLimitException $e) {
            \Log::error('Stripe Rate Limit Error: ' . $e->getMessage());
            return redirect()->back()->with('error', trans('Too many payment requests. Please wait a moment and try again.'));
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API Error: ' . $e->getMessage(), [
                'order_id' => $orderId ?? null,
                'user_id' => $user->id ?? null
            ]);
            return redirect()->back()->with('error', trans('Payment service error. Please try again or contact support.'));
        } catch (\Exception $e) {
            \Log::error('Stripe Checkout Session creation failed: ' . $e->getMessage(), [
                'order_id' => $orderId ?? null,
                'user_id' => $user->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', trans('Payment processing failed. Please try again or contact support if the problem persists.'));
        }
    }

     public function razorpayOrder(Request $request)
     {
         $rules = [
             'shipping_method_id' => 'required',
             'billing_address_id' => 'required',
             'shipping_address_id' => 'required',
         ];
         $customMessages = [
             'shipping_method_id.required' => trans('Shipping method is required'),
             'billing_address_id.required' => trans('Billing address is required'),
             'shipping_address_id.required' => trans('Shipping address is required'),
         ];
         $this->validate($request, $rules, $customMessages);

         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $request->shipping_method_id);

         $razorpay = RazorpayPayment::first();
         $payableAmount = round($cartTotals['total_price'] * $razorpay->currency_rate);

         $api = new Api($razorpay->key, $razorpay->secret_key);
         $orderData = [
             'receipt' => 'order_rcptid_' . rand(1000, 9999),
             'amount' => $payableAmount * 100,
             'currency' => $razorpay->currency_name,
         ];
         $razorpayOrder = $api->order->create($orderData);

         Session::put('shipping_method_id', $request->shipping_method_id);
         Session::put('billing_address_id', $request->billing_address_id);
         Session::put('shipping_address_id', $request->shipping_address_id);

         return response()->json([
             'razorpayOrder' => $razorpayOrder,
             'payableAmount' => $payableAmount,
             'razorpay' => $razorpay,
         ]);
     }

     public function razorpayWebView(Request $request)
     {
         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $shipping_method_id = Session::get('shipping_method_id');
         $billing_address_id = Session::get('billing_address_id');
         $shipping_address_id = Session::get('shipping_address_id');

         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $shipping_method_id);
         $razorpay = RazorpayPayment::first();
         $payableAmount = round($cartTotals['total_price'] * $razorpay->currency_rate);

         $api = new Api($razorpay->key, $razorpay->secret_key);
         $orderData = [
             'receipt' => 'order_rcptid_' . rand(1000, 9999),
             'amount' => $payableAmount * 100,
             'currency' => $razorpay->currency_name,
         ];
         $razorpayOrder = $api->order->create($orderData);

         return view('razorpay_webview', compact('razorpayOrder', 'payableAmount', 'razorpay'));
     }

     public function razorpayVerify(Request $request)
     {
         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $shipping_method_id = Session::get('shipping_method_id');
         $billing_address_id = Session::get('billing_address_id');
         $shipping_address_id = Session::get('shipping_address_id');

         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $shipping_method_id);
         $razorpay = RazorpayPayment::first();

         $api = new Api($razorpay->key, $razorpay->secret_key);
         $payment = $api->payment->fetch($request->razorpay_payment_id);

         if ($payment->status == 'captured') {
             $orderResult = $this->orderStore(
                 $user,
                 $cartTotals['total_price'],
                 $cartTotals['productWeight'],
                 'Razorpay',
                 $request->razorpay_payment_id,
                 'success',
                 $cartTotals['shipping'],
                 $cartTotals['shipping_fee'],
                 $cartTotals['coupon_price'],
                 0,
                 $billing_address_id,
                 $shipping_address_id
             );

             $this->sendOrderSuccessMail(
                 $user,
                 $cartTotals['total_price'],
                 'Razorpay',
                 'success',
                 $orderResult['order'],
                 $orderResult['order_details']
             );

             Session::forget(['coupon_code', 'shipping_method_id', 'billing_address_id', 'shipping_address_id']);

             return redirect()->route('order.success', ['order' => encodeOrderId($orderResult['order']->order_id)])->with('success', trans('Order placed successfully'));
         } else {
             return redirect()->route('checkout')->with('error', trans('Payment failed'));
         }
     }

     public function flutterwaveWebView(Request $request)
     {
         $rules = [
             'shipping_method_id' => 'required',
             'billing_address_id' => 'required',
             'shipping_address_id' => 'required',
         ];
         $customMessages = [
             'shipping_method_id.required' => trans('Shipping method is required'),
             'billing_address_id.required' => trans('Billing address is required'),
             'shipping_address_id.required' => trans('Shipping address is required'),
         ];
         $this->validate($request, $rules, $customMessages);

         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $request->shipping_method_id);

         $flutterwave = Flutterwave::first();
         $payableAmount = round($cartTotals['total_price'] * $flutterwave->currency_rate);

         Session::put('shipping_method_id', $request->shipping_method_id);
         Session::put('billing_address_id', $request->billing_address_id);
         Session::put('shipping_address_id', $request->shipping_address_id);

         return view('flutterwave_webview', compact('flutterwave', 'payableAmount', 'user'));
     }

     public function payWithFlutterwave(Request $request)
     {
         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $shipping_method_id = Session::get('shipping_method_id');
         $billing_address_id = Session::get('billing_address_id');
         $shipping_address_id = Session::get('shipping_address_id');

         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $shipping_method_id);
         $flutterwave = Flutterwave::first();

         $curl = curl_init();
         curl_setopt_array($curl, array(
             CURLOPT_URL => 'https://api.flutterwave.com/v3/transactions/' . $request->transaction_id . '/verify',
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'GET',
             CURLOPT_HTTPHEADER => array(
                 'Authorization: Bearer ' . $flutterwave->secret_key
             ),
         ));

         $response = curl_exec($curl);
         curl_close($curl);
         $response = json_decode($response);

         if ($response->status == 'success') {
             $orderResult = $this->orderStore(
                 $user,
                 $cartTotals['total_price'],
                 $cartTotals['productWeight'],
                 'Flutterwave',
                 $request->transaction_id,
                 'success',
                 $cartTotals['shipping'],
                 $cartTotals['shipping_fee'],
                 $cartTotals['coupon_price'],
                 0,
                 $billing_address_id,
                 $shipping_address_id
             );

             $this->sendOrderSuccessMail(
                 $user,
                 $cartTotals['total_price'],
                 'Flutterwave',
                 'success',
                 $orderResult['order'],
                 $orderResult['order_details']
             );

             Session::forget(['coupon_code', 'shipping_method_id', 'billing_address_id', 'shipping_address_id']);

             return response()->json(['status' => 'success', 'redirect_url' => route('order.success', ['order' => encodeOrderId($orderResult['order']->order_id)])]);
         } else {
             return response()->json(['status' => 'failed', 'message' => trans('Payment failed')]);
         }
     }

     public function payWithPaypal(Request $request)
     {
         // PayPal integration temporarily disabled - requires external package
         return redirect()->route('checkout')->with('error', 'PayPal payment is temporarily unavailable');
     }

     public function paypalSuccess(Request $request)
     {
         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $shipping_method_id = Session::get('shipping_method_id');
         $billing_address_id = Session::get('billing_address_id');
         $shipping_address_id = Session::get('shipping_address_id');

         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $shipping_method_id);

         // $provider = new PayPalClient;
         $provider->setApiCredentials(config('paypal'));
         $provider->getAccessToken();
         $response = $provider->capturePaymentOrder($request['token']);

         if (isset($response['status']) && $response['status'] == 'COMPLETED') {
             $orderResult = $this->orderStore(
                 $user,
                 $cartTotals['total_price'],
                 $cartTotals['productWeight'],
                 'PayPal',
                 $response['id'],
                 'success',
                 $cartTotals['shipping'],
                 $cartTotals['shipping_fee'],
                 $cartTotals['coupon_price'],
                 0,
                 $billing_address_id,
                 $shipping_address_id
             );

             $this->sendOrderSuccessMail(
                 $user,
                 $cartTotals['total_price'],
                 'PayPal',
                 'success',
                 $orderResult['order'],
                 $orderResult['order_details']
             );

             Session::forget(['coupon_code', 'shipping_method_id', 'billing_address_id', 'shipping_address_id']);

             return redirect()->route('order.success', ['order' => encodeOrderId($orderResult['order']->order_id)])->with('success', trans('Order placed successfully'));
         } else {
             return redirect()->route('checkout')->with('error', trans('Payment failed'));
         }
     }

     public function paypalCancel()
     {
         return redirect()->route('checkout')->with('error', trans('Payment cancelled'));
     }

     public function payWithMollie(Request $request)
     {
         $rules = [
             'shipping_method_id' => 'required',
             'billing_address_id' => 'required',
             'shipping_address_id' => 'required',
         ];
         $customMessages = [
             'shipping_method_id.required' => trans('Shipping method is required'),
             'billing_address_id.required' => trans('Billing address is required'),
             'shipping_address_id.required' => trans('Shipping address is required'),
         ];
         $this->validate($request, $rules, $customMessages);

         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $request->shipping_method_id);

         $mollie = PaystackAndMollie::first();
         $payableAmount = round($cartTotals['total_price'] * $mollie->mollie_currency_rate, 2);

         Session::put('shipping_method_id', $request->shipping_method_id);
         Session::put('billing_address_id', $request->billing_address_id);
         Session::put('shipping_address_id', $request->shipping_address_id);

         $payment = Mollie::api()->payments->create([
             'amount' => [
                 'currency' => $mollie->mollie_currency_name,
                 'value' => number_format($payableAmount, 2, '.', ''),
             ],
             'description' => 'Order Payment',
             'redirectUrl' => route('mollie.success'),
             'webhookUrl' => route('mollie.webhook'),
         ]);

         Session::put('mollie_payment_id', $payment->id);

         return redirect($payment->getCheckoutUrl(), 303);
     }

     public function mollieSuccess(Request $request)
     {
         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $shipping_method_id = Session::get('shipping_method_id');
         $billing_address_id = Session::get('billing_address_id');
         $shipping_address_id = Session::get('shipping_address_id');
         $paymentId = Session::get('mollie_payment_id');

         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $shipping_method_id);

         $payment = Mollie::api()->payments->get($paymentId);

         if ($payment->isPaid()) {
             $orderResult = $this->orderStore(
                 $user,
                 $cartTotals['total_price'],
                 $cartTotals['productWeight'],
                 'Mollie',
                 $payment->id,
                 'success',
                 $cartTotals['shipping'],
                 $cartTotals['shipping_fee'],
                 $cartTotals['coupon_price'],
                 0,
                 $billing_address_id,
                 $shipping_address_id
             );

             $this->sendOrderSuccessMail(
                 $user,
                 $cartTotals['total_price'],
                 'Mollie',
                 'success',
                 $orderResult['order'],
                 $orderResult['order_details']
             );

             Session::forget(['coupon_code', 'shipping_method_id', 'billing_address_id', 'shipping_address_id', 'mollie_payment_id']);

             return redirect()->route('order.success', ['order' => encodeOrderId($orderResult['order']->order_id)])->with('success', trans('Order placed successfully'));
         } else {
             return redirect()->route('checkout')->with('error', trans('Payment failed'));
         }
     }

     public function payWithInstamojo(Request $request)
     {
         // Instamojo integration temporarily disabled - requires external package
         return redirect()->route('checkout')->with('error', 'Instamojo payment is temporarily unavailable');
     }

     public function instamojoSuccess(Request $request)
     {
         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $shipping_method_id = Session::get('shipping_method_id');
         $billing_address_id = Session::get('billing_address_id');
         $shipping_address_id = Session::get('shipping_address_id');

         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $shipping_method_id);

         $instamojo = InstamojoPayment::first();
         // $gateway = Omnipay::create('Instamojo');
         $gateway->setApiKey($instamojo->api_key);
         $gateway->setAuthToken($instamojo->auth_token);
         $gateway->setTestMode($instamojo->mode == 'sandbox');

         $response = $gateway->completePurchase([
             'transactionReference' => $request->payment_id,
         ])->send();

         if ($response->isSuccessful()) {
             $orderResult = $this->orderStore(
                 $user,
                 $cartTotals['total_price'],
                 $cartTotals['productWeight'],
                 'Instamojo',
                 $request->payment_id,
                 'success',
                 $cartTotals['shipping'],
                 $cartTotals['shipping_fee'],
                 $cartTotals['coupon_price'],
                 0,
                 $billing_address_id,
                 $shipping_address_id
             );

             $this->sendOrderSuccessMail(
                 $user,
                 $cartTotals['total_price'],
                 'Instamojo',
                 'success',
                 $orderResult['order'],
                 $orderResult['order_details']
             );

             Session::forget(['coupon_code', 'shipping_method_id', 'billing_address_id', 'shipping_address_id']);

             return redirect()->route('order.success', ['order' => encodeOrderId($orderResult['order']->order_id)])->with('success', trans('Order placed successfully'));
         } else {
             return redirect()->route('checkout')->with('error', trans('Payment failed'));
         }
     }

     public function payWithPaystack(Request $request)
     {
         // Paystack integration temporarily disabled - requires external package
         return redirect()->route('checkout')->with('error', 'Paystack payment is temporarily unavailable');
     }

     public function paystackSuccess(Request $request)
     {
         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $shipping_method_id = Session::get('shipping_method_id');
         $billing_address_id = Session::get('billing_address_id');
         $shipping_address_id = Session::get('shipping_address_id');

         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $shipping_method_id);

         $paystack = PaystackAndMollie::first();
         // $gateway = Omnipay::create('Paystack');
         $gateway->setSecretKey($paystack->paystack_secret_key);

         $response = $gateway->completePurchase([
             'reference' => $request->reference,
         ])->send();

         if ($response->isSuccessful()) {
             $orderResult = $this->orderStore(
                 $user,
                 $cartTotals['total_price'],
                 $cartTotals['productWeight'],
                 'Paystack',
                 $request->reference,
                 'success',
                 $cartTotals['shipping'],
                 $cartTotals['shipping_fee'],
                 $cartTotals['coupon_price'],
                 0,
                 $billing_address_id,
                 $shipping_address_id
             );

             $this->sendOrderSuccessMail(
                 $user,
                 $cartTotals['total_price'],
                 'Paystack',
                 'success',
                 $orderResult['order'],
                 $orderResult['order_details']
             );

             Session::forget(['coupon_code', 'shipping_method_id', 'billing_address_id', 'shipping_address_id']);

             return redirect()->route('order.success', ['order' => encodeOrderId($orderResult['order']->order_id)])->with('success', trans('Order placed successfully'));
         } else {
             return redirect()->route('checkout')->with('error', trans('Payment failed'));
         }
     }

     public function payWithSslcommerz(Request $request)
     {
         // SSLCommerz integration temporarily disabled - requires external package
         return redirect()->route('checkout')->with('error', 'SSLCommerz payment is temporarily unavailable');
     }

     public function sslcommerzSuccess(Request $request)
     {
         $user = Auth::guard('web')->user();
         $coupon_code = Session::get('coupon_code');
         $shipping_method_id = Session::get('shipping_method_id');
         $billing_address_id = Session::get('billing_address_id');
         $shipping_address_id = Session::get('shipping_address_id');

         $cartTotals = $this->calculateCartTotal($user, $coupon_code, $shipping_method_id);

         // $sslc = new SslCommerzNotification();
         $validation = $sslc->orderValidate($request->all(), $request->tran_id, $cartTotals['total_price']);

         if ($validation) {
             $orderResult = $this->orderStore(
                 $user,
                 $cartTotals['total_price'],
                 $cartTotals['productWeight'],
                 'SSLCommerz',
                 $request->tran_id,
                 'success',
                 $cartTotals['shipping'],
                 $cartTotals['shipping_fee'],
                 $cartTotals['coupon_price'],
                 0,
                 $billing_address_id,
                 $shipping_address_id
             );

             $this->sendOrderSuccessMail(
                 $user,
                 $cartTotals['total_price'],
                 'SSLCommerz',
                 'success',
                 $orderResult['order'],
                 $orderResult['order_details']
             );

             Session::forget(['coupon_code', 'shipping_method_id', 'billing_address_id', 'shipping_address_id']);

             return redirect()->route('order.success', ['order' => encodeOrderId($orderResult['order']->order_id)])->with('success', trans('Order placed successfully'));
         } else {
             return redirect()->route('checkout')->with('error', trans('Payment failed'));
         }
     }

     public function sslcommerzFail()
     {
         return redirect()->route('checkout')->with('error', trans('Payment failed'));
     }

     public function sslcommerzCancel()
     {
         return redirect()->route('checkout')->with('error', trans('Payment cancelled'));
     }

     private function calculateCartTotal($user, $coupon_code, $shipping_method_id)
    {
        if ($user) {
            $cartProducts = ShoppingCart::with('product', 'variants.variantItem')
                ->where('user_id', $user->id)
                ->select('id', 'product_id', 'qty')
                ->get();
        } else {
            // Handle guest cart from session
            $sessionCart = Session::get('guest_cart', []);
            $cartProducts = collect();
            
            foreach ($sessionCart as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $cartItem = (object) [
                        'id' => $item['id'],
                        'product_id' => $item['product_id'],
                        'qty' => $item['quantity'], // Fixed: session cart uses 'quantity' not 'qty'
                        'product' => $product,
                        'variants' => collect($item['variants'] ?? [])
                    ];
                    $cartProducts->push($cartItem);
                }
            }
        }

        $total_price = 0;
        $productWeight = 0;

        foreach ($cartProducts as $cartProduct) {
            $variantPrice = 0;
            if ($cartProduct->variants) {
                foreach ($cartProduct->variants as $variant) {
                    if ($user) {
                        $item = ProductVariantItem::find($variant->variant_item_id);
                    } else {
                        $item = ProductVariantItem::find($variant['variant_item_id']);
                    }
                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            }

            $product = Product::select('id', 'price', 'offer_price', 'weight')
                ->find($cartProduct->product_id);

            $price = $product->offer_price ? $product->offer_price : $product->price;
            $price = $price + $variantPrice;

            // Check flash sale
            $isFlashSale = FlashSaleProduct::where([
                'product_id' => $product->id,
                'status' => 1,
            ])->first();

            $today = date('Y-m-d H:i:s');
            if ($isFlashSale) {
                $flashSale = FlashSale::first();
                if ($flashSale->status == 1) {
                    if ($today <= $flashSale->end_time) {
                        $offerPrice = ($flashSale->offer / 100) * $price;
                        $price = $price - $offerPrice;
                    }
                }
            }

            $total_price += $price * $cartProduct->qty;
            $productWeight += $product->weight * $cartProduct->qty;
        }

        // Apply coupon
        $coupon_price = 0;
        if ($coupon_code) {
            $coupon = Coupon::where(['code' => $coupon_code, 'status' => 1])->first();
            if ($coupon) {
                if ($coupon->expired_date >= date('Y-m-d')) {
                    if ($coupon->apply_qty < $coupon->max_quantity) {
                        if ($coupon->offer_type == 1) {
                            $couponAmount = ($coupon->discount / 100) * $total_price;
                        } else {
                            $couponAmount = $coupon->discount;
                        }
                        $coupon_price = $couponAmount;
                        $qty = $coupon->apply_qty;
                        $qty = $qty + 1;
                        $coupon->apply_qty = $qty;
                        $coupon->save();
                    }
                }
            }
        }

        $shipping = Shipping::find($shipping_method_id);
        if (!$shipping) {
            throw new Exception(trans('Shipping method not found'));
        }

        $shipping_fee = $shipping->shipping_fee == 0 ? 0 : $shipping->shipping_fee;
        $total_price = $total_price - $coupon_price + $shipping_fee;
        $total_price = number_format($total_price, 2, '.', '');

        return [
            'total_price' => $total_price,
            'coupon_price' => $coupon_price,
            'shipping_fee' => $shipping_fee,
            'productWeight' => $productWeight,
            'shipping' => $shipping,
        ];
    }

    private function orderStore(
        $user,
        $total_price,
        $totalProduct,
        $payment_method,
        $transaction_id,
        $payment_status,
        $shipping,
        $shipping_fee,
        $coupon_price,
        $cash_on_delivery,
        $billing_address_id,
        $shipping_address_id
    ) {
        if ($user) {
            $cartProducts = ShoppingCart::with('product', 'variants.variantItem')
                ->where('user_id', $user->id)
                ->select('id', 'product_id', 'qty')
                ->get();
        } else {
            // Handle guest cart
            $sessionCart = Session::get('guest_cart', []);
            $cartProducts = collect();
            
            foreach ($sessionCart as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $cartItem = (object) [
                        'id' => $item['id'],
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'product' => $product,
                        'variants' => collect($item['variants'] ?? [])
                    ];
                    $cartProducts->push($cartItem);
                }
            }
        }

        if ($cartProducts->count() == 0) {
            throw new Exception(trans('Your shopping cart is empty'));
        }

        // Handle guest user - create or get default guest user
        if (!$user) {
            // For orderStore method, we need to create a simple guest user since we don't have request data
            $user = $this->createSimpleGuestUser();
        }

        $order = new Order();
        $orderId = substr(rand(0, time()), 0, 10);
        $order->order_id = $orderId;
        $order->user_id = $user->id;
        $order->total_amount = $total_price;
        $order->product_qty = $totalProduct;
        $order->payment_method = $payment_method;
        $order->transection_id = $transaction_id;
        $order->payment_status = $payment_status;
        $order->shipping_method = $shipping->shipping_rule;
        $order->shipping_cost = $shipping_fee ?? 0;
        $order->coupon_coast = $coupon_price;
        $order->order_status = 0;
        $order->cash_on_delivery = $cash_on_delivery;
        $order->save();

        $order_details = '';
        $setting = Setting::first();

        foreach ($cartProducts as $cartProduct) {
            $variantPrice = 0;
            if ($cartProduct->variants) {
                foreach ($cartProduct->variants as $variant) {
                    if ($user) {
                        $item = ProductVariantItem::find($variant->variant_item_id);
                    } else {
                        $item = ProductVariantItem::find($variant['variant_item_id']);
                    }
                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            }

            $product = Product::select('id', 'price', 'offer_price', 'weight', 'vendor_id', 'qty', 'name')
                ->find($cartProduct->product_id);

            $price = $product->offer_price ? $product->offer_price : $product->price;
            $price = $price + $variantPrice;

            // Check flash sale
            $isFlashSale = FlashSaleProduct::where([
                'product_id' => $product->id,
                'status' => 1,
            ])->first();

            $today = date('Y-m-d H:i:s');
            if ($isFlashSale) {
                $flashSale = FlashSale::first();
                if ($flashSale->status == 1) {
                    if ($today <= $flashSale->end_time) {
                        $offerPrice = ($flashSale->offer / 100) * $price;
                        $price = $price - $offerPrice;
                    }
                }
            }

            // Store order product
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $cartProduct->product_id;
            $orderProduct->seller_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->unit_price = $price;
            $orderProduct->qty = $cartProduct->qty;
            $orderProduct->save();

            try {
                app(\App\Services\StockService::class)->deductForSale(
                    (int) $product->id,
                    (int) $cartProduct->qty,
                    $order->order_id,
                    null,
                    'order',
                    (int) $order->id
                );
            } catch (\InvalidArgumentException $e) {
                $product->qty = max(0, (int) $product->qty - (int) $cartProduct->qty);
                $product->save();
            }

            // Store product variants
            if ($cartProduct->variants) {
                foreach ($cartProduct->variants as $variant) {
                    if ($user) {
                        $item = ProductVariantItem::find($variant->variant_item_id);
                    } else {
                        $item = ProductVariantItem::find($variant['variant_item_id']);
                    }
                    if ($item) {
                        $productVariant = new OrderProductVariant();
                        $productVariant->order_product_id = $orderProduct->id;
                        $productVariant->product_id = $cartProduct->product_id;
                        $productVariant->variant_name = $item->product_variant_name;
                        $productVariant->variant_value = $item->name;
                        $productVariant->save();
                    }
                }
            }

            $order_details .= 'Product: ' . $product->name . '<br>';
            $order_details .= 'Quantity: ' . $cartProduct->qty . '<br>';
            $order_details .= 'Price: ' . $setting->currency_icon . ($cartProduct->qty * $price) . '<br>';
        }

        // Store shipping and billing address
        $billing = Address::find($billing_address_id);
        $shipping_addr = Address::find($shipping_address_id);
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $order->id;
        $orderAddress->billing_name = $billing->name;
        $orderAddress->billing_email = $billing->email;
        $orderAddress->billing_phone = $billing->phone;
        $orderAddress->billing_address = $billing->address;
        $orderAddress->billing_country = $billing->country->name ?? 'Bangladesh';
        $orderAddress->billing_state = '';
        $orderAddress->billing_city = '';
        $orderAddress->billing_address_type = $billing->type;
        $orderAddress->delivery_area = $billing->delivery_area ?? 'inside';
        $orderAddress->shipping_name = $shipping_addr->name;
        $orderAddress->shipping_email = $shipping_addr->email;
        $orderAddress->shipping_phone = $shipping_addr->phone;
        $orderAddress->shipping_address = $shipping_addr->address;
        $orderAddress->shipping_country = $shipping_addr->country->name ?? 'Bangladesh';
        $orderAddress->shipping_state = '';
        $orderAddress->shipping_city = '';
        $orderAddress->shipping_address_type = $shipping_addr->type;
        $orderAddress->save();

        // Clear cart
        if ($user) {
            foreach ($cartProducts as $cartProduct) {
                ShoppingCartVariant::where('shopping_cart_id', $cartProduct->id)->delete();
                $cartProduct->delete();
            }
        } else {
            Session::forget('guest_cart');
        }

        return [
            'order' => $order,
            'order_details' => $order_details,
        ];
    }

    private function sendOrderSuccessMail(
        $user,
        $total_price,
        $payment_method,
        $payment_status,
        $order,
        $order_details
    ) {
        if (!$user) return; // Skip email for guest orders

        $setting = Setting::first();
        MailHelper::setMailConfig();
        $template = EmailTemplate::where('id', 6)->first();
        $subject = $template->subject;
        $message = $template->description;
        $message = str_replace('{{user_name}}', $user->name, $message);
        $message = str_replace('{{total_amount}}', $setting->currency_icon . $total_price, $message);
        $message = str_replace('{{payment_method}}', $payment_method, $message);
        $message = str_replace('{{payment_status}}', $payment_status, $message);
        $message = str_replace('{{order_status}}', 'Pending', $message);
        $message = str_replace('{{order_date}}', $order->created_at->format('d F, Y'), $message);
        $message = str_replace('{{order_detail}}', $order_details, $message);

        Mail::to($user->email)->send(new \App\Mail\OrderSuccessfully($message, $subject));

        $this->sendOrderSuccessSms($user, $order);
    }

    private function sendOrderSuccessSms($user, $order)
    {
        $template = SmsTemplate::where('id', 3)->first();
        $message = $template->description;
        $message = str_replace('{{user_name}}', $user->name, $message);
        $message = str_replace('{{order_id}}', $order->order_id, $message);

        $twilio = TwilioSms::first();
        if ($twilio->enable_order_confirmation_sms == 1) {
            if ($user->phone) {
                try {
                    $account_sid = $twilio->account_sid;
                    $auth_token = $twilio->auth_token;
                    $twilio_number = $twilio->twilio_phone_number;
                    $recipients = $user->phone;
                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create($recipients, [
                        'from' => $twilio_number,
                        'body' => $message
                    ]);
                } catch (Exception $ex) {
                    // Handle SMS error silently
                }
            }
        }
    }

    /**
     * Process payment for existing orders with pending payment status
     */
    public function processPayment(Request $request)
    {
        $rules = [
            'order_id' => 'required|string',
            'payment_method' => 'required|string|in:stripe,paypal,razorpay,flutterwave,mollie,instamojo,paystack,sslcommerz,bank_payment'
        ];

        $customMessages = [
            'order_id.required' => trans('Order ID is required'),
            'payment_method.required' => trans('Payment method is required'),
            'payment_method.in' => trans('Invalid payment method selected')
        ];

        $this->validate($request, $rules, $customMessages);

        try {
            $user = Auth::user();
            
            // Decode the order ID using the same method as encoding
            $orderId = null;
            try {
                $orderId = decrypt(base64_decode($request->order_id));
            } catch (\Exception $decryptException) {
                \Log::error('Order ID decryption failed', [
                    'user_id' => $user->id,
                    'encoded_order_id' => $request->order_id,
                    'error' => $decryptException->getMessage()
                ]);
            }
            
            // Log for debugging
            \Log::info('Payment processing attempt', [
                'user_id' => $user->id,
                'encoded_order_id' => $request->order_id,
                'decoded_order_id' => $orderId,
                'payment_method' => $request->payment_method
            ]);
            
            if (!$orderId) {
                return redirect()->back()->with('error', trans('Invalid order ID format'));
            }
            
            // First check if order exists and belongs to user
            $orderCheck = Order::where('id', $orderId)
                              ->where('user_id', $user->id)
                              ->first();
            
            if (!$orderCheck) {
                return redirect()->back()->with('error', trans('Order not found or does not belong to you'));
            }
            
            // Check payment status
            if ($orderCheck->payment_status == 1) {
                return redirect()->back()->with('error', trans('Payment already completed for this order'));
            }
            
            // Allow payment for any unpaid order (not just Cash on Delivery)
            $order = Order::where('id', $orderId)
                          ->where('user_id', $user->id)
                          ->where('payment_status', 0) // Only pending payments
                          ->first();

            if (!$order) {
                return redirect()->back()->with('error', trans('Order not found or payment already completed'));
            }

            // Store order ID in session for payment processing
            session(['temp_order_id' => $order->id]);

            // Redirect to appropriate payment gateway
            return $this->getPaymentRedirectUrl($order, $request->payment_method, $request);

        } catch (\Exception $e) {
            \Log::error('Payment processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'order_id' => $request->order_id,
                'payment_method' => $request->payment_method,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', trans('Payment processing failed. Please try again.'));
        }
    }

    /**
     * Show bank payment form or process bank payment for existing order
     */
    public function bankPayment(Request $request)
    {
        try {
            $user = Auth::user();
            $orderId = session('temp_order_id');

            // Debug logging
            \Log::info('Bank payment accessed', [
                'user_id' => $user ? $user->id : 'guest',
                'order_id' => $orderId,
                'method' => $request->method()
            ]);

            if (!$orderId) {
                return redirect()->back()->with('error', trans('Order session expired. Please try again.'));
            }

            // Find the order
            $order = Order::where('id', $orderId)
                          ->where('user_id', $user->id)
                          ->where('payment_status', 0)
                          ->first();

            if (!$order) {
                return redirect()->back()->with('error', trans('Order not found or already paid'));
            }

            // If this is a POST request, process the payment
            if ($request->isMethod('post')) {
                $rules = [
                    'tnx_info' => 'required|string',
                ];

                $customMessages = [
                    'tnx_info.required' => trans('Transaction information is required'),
                ];

                $this->validate($request, $rules, $customMessages);

                try {
                    // Update order with bank payment details
                    $order->update([
                        'payment_method' => 'Bank Payment',
                        'transection_id' => $request->tnx_info,
                        'payment_status' => 0, // Keep as pending for admin approval
                    ]);

                    // Clear session
                    session()->forget('temp_order_id');

                    // Send notification email (if method exists)
                    try {
                        $this->sendWebOrderSuccessEmail($order, $order->orderProducts);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to send order success email: ' . $e->getMessage());
                    }

                    $notification = trans('Order submitted successfully. Please wait for admin approval');
                    
                    return redirect()->route('order.success', ['order' => encodeOrderId($order->order_id)])
                                   ->with('success', $notification);

                } catch (\Exception $e) {
                    \Log::error('Bank payment processing failed: ' . $e->getMessage(), [
                        'user_id' => Auth::id(),
                        'order_id' => session('temp_order_id'),
                        'trace' => $e->getTraceAsString()
                    ]);

                    return redirect()->back()->with('error', trans('Payment processing failed. Please try again.'));
                }
            }

            // Show bank payment form
            $bankPayment = BankPayment::first();
            $setting = Setting::first();
            
            \Log::info('Bank payment view data', [
                'order_id' => $order->id,
                'bank_payment_exists' => $bankPayment ? true : false,
                'setting_exists' => $setting ? true : false
            ]);
            
            return view('frontend.bank-payment', compact('order', 'bankPayment', 'setting'));
            
        } catch (\Exception $e) {
            \Log::error('Bank payment method failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Payment page could not be loaded. Please try again.');
        }
    }

    /**
     * Handle Stripe payment for existing orders
     */
    private function payExistingOrderWithStripe(Request $request, $orderId)
    {
        $user = Auth::user();
        
        // Find the existing order
        $order = Order::with(['orderAddress'])
                      ->where('id', $orderId)
                      ->where('user_id', $user->id)
                      ->where('payment_status', 0)
                      ->first();

        if (!$order) {
            return redirect()->back()->with('error', trans('Order not found or already paid'));
        }

        // If GET request, show Stripe payment form
        if ($request->isMethod('get')) {
            $stripe = StripePayment::first();
            $setting = Setting::first();
            
            return view('frontend.stripe-payment', compact('order', 'stripe', 'setting'));
        }

        // If POST request, process the payment
        $rules = [
            'card_number' => 'required',
            'year' => 'required',
            'month' => 'required',
            'cvv' => 'required',
        ];

        $customMessages = [
            'card_number.required' => trans('Card number is required'),
            'year.required' => trans('Year is required'),
            'month.required' => trans('Month is required'),
            'cvv.required' => trans('Cvv is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        try {
            $stripe = StripePayment::first();
            $payableAmount = round($order->total_amount * $stripe->currency_rate, 2);
            
            \Log::info('Stripe payment attempt', [
                'order_id' => $order->id,
                'order_number' => $order->order_id,
                'original_amount' => $order->total_amount,
                'payable_amount' => $payableAmount,
                'currency_rate' => $stripe->currency_rate,
                'currency_code' => $stripe->currency_code,
                'stripe_mode' => $stripe->account_mode ?? 'unknown'
            ]);
            
            Stripe\Stripe::setApiKey($stripe->stripe_secret);
            
            // Create Stripe token
            $token = Stripe\Token::create([
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $request->month,
                    'exp_year' => $request->year,
                    'cvc' => $request->cvv,
                ],
            ]);

            \Log::info('Stripe token created', ['token_id' => $token->id]);

            // Create charge
            $charge = Stripe\Charge::create([
                'amount' => $payableAmount * 100, // Stripe expects amount in cents
                'currency' => strtolower($stripe->currency_code),
                'source' => $token->id,
                'description' => 'Payment for Order #' . $order->order_id,
            ]);

            \Log::info('Stripe charge created', [
                'charge_id' => $charge->id,
                'charge_status' => $charge->status,
                'amount_charged' => $charge->amount,
                'currency' => $charge->currency
            ]);

            if ($charge->status == 'succeeded') {
                \Log::info('Stripe payment succeeded, updating order', [
                    'order_id' => $order->id,
                    'charge_id' => $charge->id
                ]);

                // Update order
                $updateResult = $order->update([
                    'payment_method' => 'Stripe',
                    'payment_status' => 1,
                    'transection_id' => $charge->id,
                    'payment_approval_date' => now(),
                ]);

                \Log::info('Order update result', [
                    'order_id' => $order->id,
                    'update_success' => $updateResult,
                    'new_payment_status' => $order->fresh()->payment_status
                ]);

                // Clear session
                session()->forget('temp_order_id');

                // Send success email
                try {
                    $this->sendWebOrderSuccessEmail($order, $order->orderProducts);
                } catch (\Exception $e) {
                    \Log::warning('Failed to send order success email: ' . $e->getMessage());
                }

                // For debugging, let's also return a simple success message
                if (request()->has('debug')) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Payment completed successfully',
                        'order_id' => $order->id,
                        'payment_status' => $order->fresh()->payment_status,
                        'transaction_id' => $charge->id
                    ]);
                }

                return redirect()->route('order.success', ['order' => encodeOrderId($order->order_id)])
                               ->with('success', trans('Payment completed successfully'));
            } else {
                \Log::warning('Stripe charge failed', [
                    'charge_status' => $charge->status,
                    'charge_id' => $charge->id ?? 'unknown'
                ]);
                return redirect()->back()->with('error', trans('Payment failed. Please try again.'));
            }

        } catch (\Exception $e) {
            \Log::error('Stripe payment failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', trans('Payment processing failed: ') . $e->getMessage());
        }
    }
}