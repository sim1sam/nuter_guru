<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WEB\Admin\DashboardController;
use App\Http\Controllers\WEB\Admin\Auth\AdminLoginController;
use App\Http\Controllers\WEB\Admin\Auth\AdminForgotPasswordController;
use App\Http\Controllers\WEB\Admin\AdminProfileController;
use App\Http\Controllers\WEB\Admin\ProductCategoryController;
use App\Http\Controllers\WEB\Admin\ProductSubCategoryController;
use App\Http\Controllers\WEB\Admin\ProductChildCategoryController;
use App\Http\Controllers\WEB\Admin\ProductBrandController;
use App\Http\Controllers\WEB\Admin\SpecificationKeyController;
use App\Http\Controllers\WEB\Admin\TestimonialController;
use App\Http\Controllers\WEB\Admin\ProductController;
use App\Http\Controllers\WEB\Admin\ProductGalleryController;
use App\Http\Controllers\WEB\Admin\ServiceController;
use App\Http\Controllers\WEB\Admin\AboutUsController;
use App\Http\Controllers\WEB\Admin\ContactPageController;
use App\Http\Controllers\WEB\Admin\CustomPageController;
use App\Http\Controllers\WEB\Admin\TermsAndConditionController;
use App\Http\Controllers\WEB\Admin\PrivacyPolicyController;
use App\Http\Controllers\WEB\Admin\BlogCategoryController;
use App\Http\Controllers\WEB\Admin\BlogController;
use App\Http\Controllers\WEB\Admin\PopularBlogController;
use App\Http\Controllers\WEB\Admin\BlogCommentController;
use App\Http\Controllers\WEB\Admin\ProductVariantController;
use App\Http\Controllers\WEB\Admin\ProductVariantItemController;
use App\Http\Controllers\WEB\Admin\SettingController;
use App\Http\Controllers\WEB\Admin\SubscriberController;
use App\Http\Controllers\WEB\Admin\ContactMessageController;
use App\Http\Controllers\WEB\Admin\EmailConfigurationController;
use App\Http\Controllers\WEB\Admin\EmailTemplateController;
use App\Http\Controllers\WEB\Admin\AdminController;
use App\Http\Controllers\WEB\Admin\FaqController;
use App\Http\Controllers\WEB\Admin\ProductReviewController;
use App\Http\Controllers\WEB\Admin\CustomerController;
use App\Http\Controllers\WEB\Admin\ErrorPageController;
use App\Http\Controllers\WEB\Admin\ContentController;
use App\Http\Controllers\WEB\Admin\CountryController;
use App\Http\Controllers\WEB\Admin\CountryStateController;
use App\Http\Controllers\WEB\Admin\CityController;
use App\Http\Controllers\WEB\Admin\PaymentMethodController;
use App\Http\Controllers\WEB\Admin\SellerController;
use App\Http\Controllers\WEB\Admin\MegaMenuController;
use App\Http\Controllers\WEB\Admin\MegaMenuSubCategoryController;
use App\Http\Controllers\WEB\Admin\SliderController;
use App\Http\Controllers\WEB\Admin\HomePageController;
use App\Http\Controllers\WEB\Admin\ShippingMethodController;
use App\Http\Controllers\WEB\Admin\WithdrawMethodController;
use App\Http\Controllers\WEB\Admin\SellerWithdrawController;
use App\Http\Controllers\WEB\Admin\ProductReportController;
use App\Http\Controllers\WEB\Admin\OrderController;
use App\Http\Controllers\WEB\Admin\CouponController;
use App\Http\Controllers\WEB\Admin\BreadcrumbController;
use App\Http\Controllers\WEB\Admin\FooterController;
use App\Http\Controllers\WEB\Admin\FooterSocialLinkController;
use App\Http\Controllers\WEB\Admin\FooterLinkController;
use App\Http\Controllers\WEB\Admin\HomepageVisibilityController;
use App\Http\Controllers\WEB\Admin\MenuVisibilityController;
use App\Http\Controllers\WEB\Admin\LanguageController;
use App\Http\Controllers\WEB\Admin\AdvertisementController;
use App\Http\Controllers\WEB\Admin\FlashSaleController;
use App\Http\Controllers\WEB\Admin\InventoryController;
use App\Http\Controllers\WEB\Admin\WarehouseController;
use App\Http\Controllers\WEB\Admin\UnitController;
use App\Http\Controllers\WEB\Admin\SupplierController;
use App\Http\Controllers\WEB\Admin\PurchaseOrderController;
use App\Http\Controllers\WEB\Admin\PurchaseReceiptController;
use App\Http\Controllers\WEB\Admin\PurchaseReturnController;
use App\Http\Controllers\WEB\Admin\ExpenseController;
use App\Http\Controllers\WEB\Admin\ExpenseCategoryController;
use App\Http\Controllers\WEB\Admin\ReportController;
use App\Http\Controllers\WEB\Admin\NotificationController;
use App\Http\Controllers\WEB\Admin\PosController;

use App\Http\Controllers\WEB\Seller\SellerDashboardController;
use App\Http\Controllers\WEB\Seller\SellerProfileController;
use App\Http\Controllers\WEB\Seller\SellerProductController;
use App\Http\Controllers\WEB\Seller\SellerProductGalleryController;
use App\Http\Controllers\WEB\Seller\SellerProductVariantController;
use App\Http\Controllers\WEB\Seller\SellerProductVariantItemController;
use App\Http\Controllers\WEB\Seller\SellerProductReviewController;
use App\Http\Controllers\WEB\Seller\WithdrawController;
use App\Http\Controllers\WEB\Seller\SellerProductReportControler;
use App\Http\Controllers\WEB\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerMessageContoller;
use App\Http\Controllers\WEB\Seller\InventoryController as SellerInventoryController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\PaypalController;
use App\Http\Controllers\User\MessageController;
use App\Http\Controllers\User\AddressCotroller;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\WEB\Seller\Auth\SellerLoginController;
use App\Http\Controllers\WEB\Seller\Auth\SellerForgotPasswordController;
use App\Http\Controllers\WEB\Seller\Auth\SellerRegisterController;

Route::group(['as'=> 'user.', 'prefix' => 'user'],function (){

    Route::group(['as'=> 'checkout.', 'prefix' => 'checkout'],function (){

        Route::get('/paypal-web-view', [PaypalController::class, 'paypalWebView'])->name('paypal-web-view');
        Route::get('/pay-with-paypal', [PaypalController::class, 'payWithPaypal'])->name('pay-with-paypal');
        Route::get('/paypal-payment-success', [PaypalController::class, 'paypalPaymentSuccess'])->name('paypal-payment-success');
        Route::get('/paypal-payment-cancled', [PaypalController::class, 'paypalPaymentCancled'])->name('paypal-payment-cancled');

        Route::get('/paypal-react-web-view', [PaypalController::class, 'paypalReactWebView'])->name('paypal-react-web-view');
        Route::get('/pay-with-paypal-from-react', [PaypalController::class, 'payWithPaypalForReactJs'])->name('pay-with-paypal-from-react');
        Route::get('/paypal-payment-success-from-react', [PaypalController::class, 'paypalPaymentSuccessFromReact'])->name('paypal-payment-success-from-react');
        Route::get('/paypal-payment-cancled-from-react', [PaypalController::class, 'paypalPaymentCancledFromReact'])->name('paypal-payment-cancled-from-react');

        Route::get('/razorpay-order', [PaymentController::class, 'razorpayOrder'])->name('razorpay-order');
        Route::get('/razorpay-web-view', [PaymentController::class, 'razorpayWebView'])->name('razorpay-web-view');
        Route::post('razorpay/pay/verify', [PaymentController::class, 'razorpayVerify'])->name('razorpay-pay-verify');

        Route::get('/flutterwave-web-view', [PaymentController::class, 'flutterwaveWebView'])->name('flutterwave-web-view');
        Route::post('/pay-with-flutterwave', [PaymentController::class, 'payWithFlutterwave'])->name('pay-with-flutterwave');

        Route::get('/pay-with-mollie', [PaymentController::class, 'payWithMollie'])->name('pay-with-mollie');
        Route::get('/mollie-payment-success', [PaymentController::class, 'molliePaymentSuccess'])->name('mollie-payment-success');

        Route::get('/pay-with-instamojo', [PaymentController::class, 'payWithInstamojo'])->name('pay-with-instamojo');
        Route::get('/instamojo-response', [PaymentController::class, 'instamojoResponse'])->name('instamojo-response');

        Route::get('/paystack-web-view', [PaymentController::class, 'paystackWebView'])->name('paystack-web-view');
        Route::post('/pay-with-paystack', [PaymentController::class, 'payWithPayStack'])->name('pay-with-paystack');

        Route::get('/sslcommerz-web-view', [PaymentController::class,   'sslcommerzWebView'])->name('sslcommerz-web-view');
        Route::post('/sslcommerz-pay',     [PaymentController::class,   'sslcommerz'])->name('sslcommerz-pay');
        Route::post('/sslcommerz-success', [PaymentController::class,   'sslcommerz_success'])->name('sslcommerz-success');

        Route::post('/sslcommerz-failed', [PaymentController::class,   'sslcommerz_failed'])->name('sslcommerz-failed');
        Route::post('/sslcommerz-cancel', [PaymentController::class,   'sslcommerz_failed'])->name('sslcommerz-cancel');

        Route::get('order-success-url-for-mobile-app', function(){
            return response()->json(['message' => 'order success']);
        })->name('order-success-url-for-mobile-app');

        Route::get('order-fail-url-for-mobile-app', function(){
            return response()->json(['message' => 'order faild']);
        })->name('order-fail-url-for-mobile-app');

    });
});

// Public location endpoints for checkout (outside middleware groups)
Route::get('/public/states/{country}', [App\Http\Controllers\Frontend\PublicLocationController::class, 'getStatesByCountry'])->name('public.states');
Route::get('/public/cities/{state}', [App\Http\Controllers\Frontend\PublicLocationController::class, 'getCitiesByState'])->name('public.cities');
Route::get('/manifest.webmanifest', [App\Http\Controllers\Frontend\PwaController::class, 'manifest'])->name('pwa.manifest');

Route::group(['middleware' => ['demo','XSS']], function () {
Route::group(['middleware' => ['maintainance']], function () {

    // Frontend Routes
    Route::get('/', [App\Http\Controllers\FrontendController::class, 'index'])->name('home');
    Route::get('/products', [App\Http\Controllers\FrontendController::class, 'products'])->name('products');
    Route::get('/product/{slug}', [App\Http\Controllers\FrontendController::class, 'productDetail'])->name('product-detail');
    Route::post('/product/{id}/watching', [App\Http\Controllers\FrontendController::class, 'productWatcherHeartbeat'])->name('product.watching');
    Route::get('/category/{slug}', [App\Http\Controllers\FrontendController::class, 'category'])->name('category');
    Route::get('/brand/{slug}', [App\Http\Controllers\FrontendController::class, 'brand'])->name('brand');
    Route::get('/about', [App\Http\Controllers\FrontendController::class, 'about'])->name('about');
    Route::get('/contact', [App\Http\Controllers\FrontendController::class, 'contact'])->name('contact');
    Route::post('/send-contact-message', [HomeController::class, 'sendContactMessage'])->name('send-contact-message');
    Route::get('/blog', [App\Http\Controllers\FrontendController::class, 'blog'])->name('blog');
    Route::get('/blog/{slug}', [App\Http\Controllers\FrontendController::class, 'blogDetail'])->name('blog.detail');
    Route::get('/faq', [App\Http\Controllers\FrontendController::class, 'faq'])->name('faq');
    Route::get('/page/{slug}', [App\Http\Controllers\FrontendController::class, 'customPage'])->name('custom.page');
    Route::get('/terms-conditions', [App\Http\Controllers\FrontendController::class, 'termsConditions'])->name('terms.conditions');
    Route::get('/privacy-policy', [App\Http\Controllers\FrontendController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::view('/our-story', 'frontend.our-story')->name('our-story');

// Frontend Customer Authentication Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [App\Http\Controllers\Frontend\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Frontend\LoginController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Frontend\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Frontend\RegisterController::class, 'register']);
    Route::get('/forgot-password', [App\Http\Controllers\Frontend\LoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Frontend\LoginController::class, 'sendForgotPassword'])->name('password.email');
    Route::post('/forgot-password-ajax', [App\Http\Controllers\Frontend\LoginController::class, 'sendForgotPasswordAjax'])->name('password.email.ajax');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Frontend\LoginController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password/{token}', [App\Http\Controllers\Frontend\LoginController::class, 'storeResetPassword'])->name('password.update');
});

// Email Verification Routes
Route::get('/email/verify', [App\Http\Controllers\Frontend\RegisterController::class, 'showVerifyEmailForm'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Frontend\RegisterController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/email/verification-notification', [App\Http\Controllers\Frontend\RegisterController::class, 'resendVerificationEmail'])->name('verification.send');

// Social Login Routes
Route::get('/auth/{provider}/redirect', [App\Http\Controllers\Frontend\LoginController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [App\Http\Controllers\Frontend\LoginController::class, 'handleProviderCallback'])->name('social.callback');

// Logout Route
Route::post('/logout', [App\Http\Controllers\Frontend\LoginController::class, 'logout'])->name('logout');

// Frontend Cart and Checkout Routes (accessible to both authenticated and guest users)
Route::get('/cart', [App\Http\Controllers\FrontendController::class, 'cart'])->name('cart');
Route::get('/api/recommended-products', [App\Http\Controllers\FrontendController::class, 'getRecommendedProducts'])->name('recommended.products');
Route::get('/checkout', [App\Http\Controllers\FrontendController::class, 'checkout'])->name('checkout');
Route::get('/order-success', [App\Http\Controllers\FrontendController::class, 'orderSuccess'])->name('order.success');
Route::get('/order-details/{order_id}', [App\Http\Controllers\FrontendController::class, 'orderDetails'])->name('order.details')->middleware('auth');

// Frontend Cart Routes
Route::post('/cart/add', [App\Http\Controllers\Frontend\CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/items', [App\Http\Controllers\Frontend\CartController::class, 'getCartItems'])->name('cart.items');
Route::get('/cart/count', [App\Http\Controllers\Frontend\CartController::class, 'getCartCountApi'])->name('cart.count');
Route::post('/cart/update', [App\Http\Controllers\Frontend\CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/remove', [App\Http\Controllers\Frontend\CartController::class, 'removeItem'])->name('cart.remove');
Route::post('/cart/clear', [App\Http\Controllers\Frontend\CartController::class, 'clearCart'])->name('cart.clear');
Route::post('/cart/apply-coupon', [App\Http\Controllers\Frontend\CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::get('/cart/calculate-product-price', [App\Http\Controllers\Frontend\CartController::class, 'calculateProductPrice'])->name('cart.calculate-product-price');

// Frontend Checkout Routes
Route::get('/checkout/data', [App\Http\Controllers\Frontend\CheckoutController::class, 'getCheckoutData'])->name('checkout.data');
Route::post('/checkout/apply-coupon', [App\Http\Controllers\Frontend\CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
Route::post('/checkout/place-order', [App\Http\Controllers\Frontend\CheckoutController::class, 'placeOrder'])->name('checkout.place-order');

// Frontend Payment Routes
Route::post('/cash-on-delivery', [App\Http\Controllers\Frontend\CheckoutController::class, 'cashOnDelivery'])->name('cash-on-delivery');
Route::match(['GET', 'POST'], '/pay-with-stripe', [App\Http\Controllers\Frontend\CheckoutController::class, 'payWithStripe'])->name('pay-with-stripe');
Route::get('/stripe-success', [App\Http\Controllers\Frontend\CheckoutController::class, 'stripeSuccess'])->name('stripe.success');
Route::get('/stripe-cancel', [App\Http\Controllers\Frontend\CheckoutController::class, 'stripeCancel'])->name('stripe.cancel');
Route::post('/razorpay-order', [App\Http\Controllers\Frontend\CheckoutController::class, 'razorpayOrder'])->name('razorpay-order');
Route::get('/razorpay-webview', [App\Http\Controllers\Frontend\CheckoutController::class, 'razorpayWebView'])->name('razorpay-webview');
Route::post('/razorpay-verify', [App\Http\Controllers\Frontend\CheckoutController::class, 'razorpayVerify'])->name('razorpay-verify');
Route::get('/flutterwave-webview', [App\Http\Controllers\Frontend\CheckoutController::class, 'flutterwaveWebView'])->name('flutterwave-webview');
Route::post('/pay-with-flutterwave', [App\Http\Controllers\Frontend\CheckoutController::class, 'payWithFlutterwave'])->name('pay-with-flutterwave');
Route::post('/pay-with-paypal', [App\Http\Controllers\Frontend\CheckoutController::class, 'payWithPaypal'])->name('pay-with-paypal');
Route::get('/paypal-success', [App\Http\Controllers\Frontend\CheckoutController::class, 'paypalSuccess'])->name('paypal-success');
Route::get('/paypal-cancel', [App\Http\Controllers\Frontend\CheckoutController::class, 'paypalCancel'])->name('paypal-cancel');
Route::post('/pay-with-mollie', [App\Http\Controllers\Frontend\CheckoutController::class, 'payWithMollie'])->name('pay-with-mollie');
Route::get('/mollie-success', [App\Http\Controllers\Frontend\CheckoutController::class, 'mollieSuccess'])->name('mollie-success');
Route::post('/pay-with-instamojo', [App\Http\Controllers\Frontend\CheckoutController::class, 'payWithInstamojo'])->name('pay-with-instamojo');
Route::get('/instamojo-success', [App\Http\Controllers\Frontend\CheckoutController::class, 'instamojoSuccess'])->name('instamojo-success');
Route::post('/pay-with-paystack', [App\Http\Controllers\Frontend\CheckoutController::class, 'payWithPaystack'])->name('pay-with-paystack');
Route::get('/paystack-success', [App\Http\Controllers\Frontend\CheckoutController::class, 'paystackSuccess'])->name('paystack-success');
Route::match(['GET', 'POST'], '/pay-with-sslcommerz', [App\Http\Controllers\Frontend\CheckoutController::class, 'payWithSslcommerz'])->name('pay-with-sslcommerz');
Route::get('/sslcommerz-success', [App\Http\Controllers\Frontend\CheckoutController::class, 'sslcommerzSuccess'])->name('sslcommerz-success');
Route::get('/sslcommerz-fail', [App\Http\Controllers\Frontend\CheckoutController::class, 'sslcommerzFail'])->name('sslcommerz-fail');
Route::get('/sslcommerz-cancel', [App\Http\Controllers\Frontend\CheckoutController::class, 'sslcommerzCancel'])->name('sslcommerz-cancel');
Route::match(['GET', 'POST'], '/bank-payment', [App\Http\Controllers\Frontend\CheckoutController::class, 'bankPayment'])->name('bank-payment');

// Payment Processing Route for existing orders
Route::post('/payment/process', [App\Http\Controllers\Frontend\CheckoutController::class, 'processPayment'])->name('payment.process')->middleware('auth');

// Debug route for checking order status (remove in production)
Route::get('/debug/order/{id}', function($id) {
    $order = \App\Models\Order::find($id);
    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }
    
    // Test encoding/decoding
    $encoded = base64_encode(encrypt($order->id));
    $decoded = null;
    try {
        $decoded = decrypt(base64_decode($encoded));
    } catch (Exception $e) {
        $decoded = 'Decoding failed: ' . $e->getMessage();
    }
    
    return response()->json([
        'id' => $order->id,
        'order_id' => $order->order_id,
        'user_id' => $order->user_id,
        'payment_method' => $order->payment_method,
        'payment_status' => $order->payment_status,
        'order_status' => $order->order_status,
        'total_amount' => $order->total_amount,
        'created_at' => $order->created_at,
        'updated_at' => $order->updated_at,
        'encoded_id' => $encoded,
        'decoded_id' => $decoded
    ]);
})->middleware('auth');

// Debug route for testing Stripe connection (remove in production)
Route::get('/debug/stripe-test', function() {
    $stripe = \App\Models\StripePayment::first();
    
    if (!$stripe) {
        return response()->json(['error' => 'Stripe not configured']);
    }
    
    try {
        \Stripe\Stripe::setApiKey($stripe->stripe_secret);
        
        // Test creating a token with test card
        $token = \Stripe\Token::create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 12,
                'exp_year' => 2025,
                'cvc' => '123',
            ],
        ]);
        
        return response()->json([
            'success' => true,
            'stripe_status' => $stripe->status,
            'currency' => $stripe->currency_code,
            'rate' => $stripe->currency_rate,
            'token_created' => $token->id,
            'message' => 'Stripe connection successful'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Stripe test failed: ' . $e->getMessage(),
            'stripe_status' => $stripe->status
        ]);
    }
})->middleware('auth');

// Debug route for checking user addresses (remove in production)
Route::get('/debug/addresses', function() {
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    $addresses = \App\Models\Address::with('country','countryState','city')
        ->where('user_id', $user->id)
        ->get();
    
    return response()->json([
        'user_id' => $user->id,
        'addresses_count' => $addresses->count(),
        'addresses' => $addresses->toArray()
    ]);
})->middleware('auth');

// Test encoding/decoding helper functions (remove in production)
Route::get('/debug/encode-test/{id}', function($id) {
    $encoded = encodeOrderId($id);
    $decoded = decodeOrderId($encoded);
    
    return response()->json([
        'original_id' => $id,
        'encoded' => $encoded,
        'decoded' => $decoded,
        'match' => ($id == $decoded)
    ]);
})->middleware('auth');

// Debug route for checking payment gateway status (remove in production)
Route::get('/debug/payment-gateways', function() {
    $gateways = [
        'stripe' => \App\Models\StripePayment::first(),
        'paypal' => \App\Models\PaypalPayment::first(),
        'razorpay' => \App\Models\RazorpayPayment::first(),
        'flutterwave' => \App\Models\Flutterwave::first(),
        'paystackAndMollie' => \App\Models\PaystackAndMollie::first(),
        'instamojo' => \App\Models\InstamojoPayment::first(),
        'sslcommerz' => \App\Models\SslcommerzPayment::first(),
        'bank_payment' => \App\Models\BankPayment::first(),
    ];
    
    return response()->json($gateways);
})->middleware('auth');

// Debug route for checking order after payment (remove in production)
Route::get('/debug/order-status/{id}', function($id) {
    $order = \App\Models\Order::find($id);
    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }
    
    return response()->json([
        'id' => $order->id,
        'order_id' => $order->order_id,
        'payment_method' => $order->payment_method,
        'payment_status' => $order->payment_status,
        'transection_id' => $order->transection_id,
        'payment_approval_date' => $order->payment_approval_date,
        'total_amount' => $order->total_amount,
        'updated_at' => $order->updated_at
    ]);
})->middleware('auth');

// Frontend Routes Requiring Authentication
Route::group(['middleware' => 'auth'], function () {

    // Customer Dashboard Routes
    Route::get('/dashboard', [App\Http\Controllers\Frontend\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/dashboard', [App\Http\Controllers\Frontend\DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [App\Http\Controllers\Frontend\ProfileController::class, 'index'])->name('profile');
    Route::get('/user/profile', [App\Http\Controllers\Frontend\ProfileController::class, 'index'])->name('user.profile');
    Route::put('/profile', [App\Http\Controllers\Frontend\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/user/profile', [App\Http\Controllers\Frontend\ProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/orders', [App\Http\Controllers\Frontend\OrderController::class, 'index'])->name('orders');
    Route::get('/user/orders', [App\Http\Controllers\Frontend\OrderController::class, 'index'])->name('user.orders');
    Route::get('/orders/{id}', [App\Http\Controllers\Frontend\OrderController::class, 'show'])->name('orders.show');
    Route::get('/user/orders/{id}', [App\Http\Controllers\Frontend\OrderController::class, 'show'])->name('user.orders.show');
    Route::get('/wishlist', [App\Http\Controllers\Frontend\WishlistController::class, 'index'])->name('wishlist');
    Route::get('/user/wishlist', [App\Http\Controllers\Frontend\WishlistController::class, 'index'])->name('user.wishlist');
    Route::post('/wishlist/add', [App\Http\Controllers\Frontend\WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/user/wishlist/add', [App\Http\Controllers\Frontend\WishlistController::class, 'add'])->name('user.wishlist.add');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\Frontend\WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::delete('/user/wishlist/{id}', [App\Http\Controllers\Frontend\WishlistController::class, 'remove'])->name('user.wishlist.remove');
    Route::get('/add-to-wishlist/{id}', [App\Http\Controllers\Frontend\WishlistController::class, 'add'])->name('add-to-wishlist');
    Route::get('/user/add-to-wishlist/{id}', [App\Http\Controllers\Frontend\WishlistController::class, 'add'])->name('user.add-to-wishlist');
    
    // Address management routes
    Route::resource('addresses', App\Http\Controllers\Frontend\AddressController::class);
    Route::get('/addresses/{country}/states', [App\Http\Controllers\Frontend\AddressController::class, 'getStatesByCountry'])->name('addresses.states');
    Route::get('/addresses/{state}/cities', [App\Http\Controllers\Frontend\AddressController::class, 'getCitiesByState'])->name('addresses.cities');
    Route::post('/addresses/{id}/set-default-shipping', [App\Http\Controllers\Frontend\AddressController::class, 'setDefaultShipping'])->name('addresses.set-default-shipping');
    Route::post('/addresses/{id}/set-default-billing', [App\Http\Controllers\Frontend\AddressController::class, 'setDefaultBilling'])->name('addresses.set-default-billing');
});
    
    // Admin access route
    Route::get('/admin-panel', function(){
        return redirect()->route('admin.login');
    })->name('admin.panel');

    // Seller Registration Routes
    Route::get('seller/register', [SellerRegisterController::class,'showRegistrationForm'])->name('seller.register');
    Route::post('seller/register', [SellerRegisterController::class,'register'])->name('seller.register');
    Route::post('seller/store-register', [SellerRegisterController::class,'register'])->name('seller.store-register');
    Route::get('seller/state-by-country/{id}', [App\Http\Controllers\Frontend\LocationController::class,'getStatesByCountry'])->name('seller.state-by-country');
    Route::get('seller/city-by-state/{id}', [App\Http\Controllers\Frontend\LocationController::class,'getCitiesByState'])->name('seller.city-by-state');
    
    // Checkout Location Routes
    Route::get('get-states-by-country/{id}', [App\Http\Controllers\Frontend\LocationController::class,'getStatesByCountry'])->name('get-states-by-country');
    Route::get('get-cities-by-state/{id}', [App\Http\Controllers\Frontend\LocationController::class,'getCitiesByState'])->name('get-cities-by-state');
    
    // Seller Login Routes
    Route::get('seller/login', [SellerLoginController::class,'sellerLoginPage'])->name('seller.login');
    Route::post('seller/login', [SellerLoginController::class,'storeLogin'])->name('seller.login');
    
    // Seller Forgot Password Routes
    Route::get('seller/forget-password', [App\Http\Controllers\WEB\Seller\Auth\SellerForgotPasswordController::class,'forgetPassword'])->name('seller.forget.password');
    Route::post('seller/send-forget-password-mail', [App\Http\Controllers\WEB\Seller\Auth\SellerForgotPasswordController::class,'sendForgetEmail'])->name('seller.send.forget.password.mail');
    Route::get('seller/reset-password/{token}', [App\Http\Controllers\WEB\Seller\Auth\SellerForgotPasswordController::class,'resetPassword'])->name('seller.reset.password');
    Route::post('seller/password-store/{token}', [App\Http\Controllers\WEB\Seller\Auth\SellerForgotPasswordController::class,'storeResetData'])->name('seller.password.store');
    Route::get('seller/logout', [SellerLoginController::class,'adminLogout'])->name('seller.logout');

    Route::group(['as'=> 'seller.', 'prefix' => 'seller'],function (){
        Route::get('dashboard',[SellerDashboardController::class,'index'])->name('dashboard');
        Route::get('my-profile',[SellerProfileController::class,'index'])->name('my-profile');
        // State and city routes moved outside group for public access
        Route::put('update-seller-profile',[SellerProfileController::class,'updateSellerProfile'])->name('update-seller-profile');
        Route::get('change-password',[SellerProfileController::class,'changePassword'])->name('change-password');
        Route::put('password-update',[SellerProfileController::class,'updatePassword'])->name('password-update');
        Route::get('shop-profile',[SellerProfileController::class,'myShop'])->name('shop-profile');
        Route::put('update-seller-shop',[SellerProfileController::class,'updateSellerSop'])->name('update-seller-shop');
        Route::put('remove-seller-social-link/{id}',[SellerProfileController::class,'removeSellerSocialLink'])->name('remove-seller-social-link');
        Route::get('email-history',[SellerProfileController::class,'emailHistory'])->name('email-history');

        Route::resource('product', SellerProductController::class);
        Route::get('stockout-product', [SellerProductController::class,'stockoutProduct'])->name('stockout-product');
        Route::put('product-status/{id}', [SellerProductController::class,'changeStatus'])->name('product-status');
        Route::put('removed-product-exist-specification/{id}', [SellerProductController::class,'removedProductExistSpecification'])->name('removed-product-exist-specification');
        Route::get('pending-product', [SellerProductController::class,'pendingProduct'])->name('pending-product');
        Route::get('product-highlight/{id}', [SellerProductController::class,'productHighlight'])->name('product-highlight');
        Route::put('update-product-highlight/{id}', [SellerProductController::class,'productHighlightUpdate'])->name('update-product-highlight');

        Route::get('subcategory-by-category/{id}', [SellerProductController::class,'getSubcategoryByCategory'])->name('subcategory-by-category');
        Route::get('childcategory-by-subcategory/{id}', [SellerProductController::class,'getChildcategoryBySubCategory'])->name('childcategory-by-subcategory');

        Route::get('product-import', [SellerProductController::class, 'product_import'])->name('product-import');
        Route::get('product-export', [SellerProductController::class, 'product_export'])->name('product-export');
        Route::get('product-demo-export', [SellerProductController::class, 'product_demo_export'])->name('product-demo-export');
        Route::post('store-product-import', [SellerProductController::class, 'store_product_import'])->name('store-product-import');

        Route::get('product-variant/{id}', [SellerProductVariantController::class,'index'])->name('product-variant');
        Route::get('create-product-variant/{id}', [SellerProductVariantController::class,'create'])->name('create-product-variant');
        Route::post('store-product-variant', [SellerProductVariantController::class,'store'])->name('store-product-variant');
        Route::get('get-product-variant/{id}', [SellerProductVariantController::class,'show'])->name('get-product-variant');
        Route::get('edit-product-variant/{id}', [SellerProductVariantController::class,'edit'])->name('edit-product-variant');
        Route::put('update-product-variant/{id}', [SellerProductVariantController::class,'update'])->name('update-product-variant');
        Route::delete('delete-product-variant/{id}', [SellerProductVariantController::class,'destroy'])->name('delete-product-variant');
        Route::put('product-variant-status/{id}', [SellerProductVariantController::class,'changeStatus'])->name('product-variant.status');

        Route::get('product-variant-item', [SellerProductVariantItemController::class,'index'])->name('product-variant-item');
        Route::get('create-product-variant-item/{id}', [SellerProductVariantItemController::class,'create'])->name('create-product-variant-item');
        Route::post('store-product-variant-item', [SellerProductVariantItemController::class,'store'])->name('store-product-variant-item');
        Route::get('edit-product-variant-item/{id}', [SellerProductVariantItemController::class,'edit'])->name('edit-product-variant-item');
        Route::get('get-product-variant-item/{id}', [SellerProductVariantItemController::class,'show'])->name('egetdit-product-variant-item');
        Route::put('update-product-variant-item/{id}', [SellerProductVariantItemController::class,'update'])->name('update-product-variant-item');
        Route::delete('delete-product-variant-item/{id}', [SellerProductVariantItemController::class,'destroy'])->name('delete-product-variant-item');
        Route::put('product-variant-item-status/{id}', [SellerProductVariantItemController::class,'changeStatus'])->name('product-variant-item.status');

        Route::get('product-gallery/{id}', [SellerProductGalleryController::class,'index'])->name('product-gallery');
        Route::post('store-product-gallery', [SellerProductGalleryController::class,'store'])->name('store-product-gallery');
        Route::delete('delete-product-image/{id}', [SellerProductGalleryController::class,'destroy'])->name('delete-product-image');
        Route::put('product-gallery-status/{id}', [SellerProductGalleryController::class,'changeStatus'])->name('product-gallery.status');

        Route::get('product-review',[SellerProductReviewController::class,'index'])->name('product-review');
        Route::put('product-review-status/{id}',[SellerProductReviewController::class,'changeStatus'])->name('product-review-status');
        Route::get('show-product-review/{id}',[SellerProductReviewController::class,'show'])->name('show-product-review');

        Route::get('product-report',[SellerProductReportControler::class, 'index'])->name('product-report');
        Route::get('show-product-report/{id}',[SellerProductReportControler::class, 'show'])->name('show-product-report');

        Route::resource('my-withdraw', WithdrawController::class);
        Route::get('get-withdraw-account-info/{id}', [WithdrawController::class, 'getWithDrawAccountInfo'])->name('get-withdraw-account-info');

        Route::get('all-order', [SellerOrderController::class, 'index'])->name('all-order');
        Route::get('pending-order', [SellerOrderController::class, 'pendingOrder'])->name('pending-order');
        Route::get('pregress-order', [SellerOrderController::class, 'pregressOrder'])->name('pregress-order');
        Route::get('delivered-order', [SellerOrderController::class, 'deliveredOrder'])->name('delivered-order');
        Route::get('completed-order', [SellerOrderController::class, 'completedOrder'])->name('completed-order');
        Route::get('declined-order', [SellerOrderController::class, 'declinedOrder'])->name('declined-order');
        Route::get('cash-on-delivery', [SellerOrderController::class, 'cashOnDelivery'])->name('cash-on-delivery');
        Route::get('order-show/{id}', [SellerOrderController::class, 'show'])->name('order-show');

        Route::get('message', [SellerMessageContoller::class, 'index'])->name('message');
        Route::get('load-chat-box/{id}', [SellerMessageContoller::class, 'loadChatBox'])->name('load-chat-box');
        Route::get('load-new-message/{id}', [SellerMessageContoller::class, 'loadNewMessage'])->name('load-new-message');
        Route::get('send-message', [SellerMessageContoller::class, 'sendMessage'])->name('send-message');

        Route::get('inventory', [SellerInventoryController::class, 'index'])->name('inventory');
        Route::get('stock-history/{id}', [SellerInventoryController::class, 'show_inventory'])->name('stock-history');
        Route::post('add-stock', [SellerInventoryController::class, 'add_stock'])->name('add-stock');
        Route::delete('delete-stock/{id}', [SellerInventoryController::class, 'delete_stock'])->name('delete-stock');

    });
});



// start admin routes

Route::group(['as'=> 'admin.', 'prefix' => 'admin'],function (){

    // start auth route
    Route::get('login', [AdminLoginController::class,'adminLoginPage'])->name('login');
    Route::post('login', [AdminLoginController::class,'storeLogin'])->name('login');
    Route::post('logout', [AdminLoginController::class,'adminLogout'])->name('logout');
    Route::get('forget-password', [AdminForgotPasswordController::class,'forgetPassword'])->name('forget-password');
    Route::post('send-forget-password', [AdminForgotPasswordController::class,'sendForgetEmail'])->name('send.forget.password');
    Route::get('reset-password/{token}', [AdminForgotPasswordController::class,'resetPassword'])->name('reset.password');
    Route::post('password-store/{token}', [AdminForgotPasswordController::class,'storeResetData'])->name('store.reset.password');
    // end auth route

    Route::get('/', [DashboardController::class,'dashobard'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class,'dashobard'])->name('dashboard');
    Route::get('profile', [AdminProfileController::class,'index'])->name('profile');
    Route::put('profile-update', [AdminProfileController::class,'update'])->name('profile.update');

    Route::resource('product-category', ProductCategoryController::class);
    Route::put('product-category-status/{id}', [ProductCategoryController::class,'changeStatus'])->name('product.category.status');

    Route::resource('product-sub-category', ProductSubCategoryController::class);
    Route::put('product-sub-category-status/{id}', [ProductSubCategoryController::class,'changeStatus'])->name('product.sub.category.status');

    Route::resource('product-child-category', ProductChildCategoryController::class);
    Route::put('product-child-category-status/{id}', [ProductChildCategoryController::class,'changeStatus'])->name('product.child.category.status');
    Route::get('subcategory-by-category/{id}', [ProductChildCategoryController::class,'getSubcategoryByCategory'])->name('subcategory-by-category');
    Route::get('childcategory-by-subcategory/{id}', [ProductChildCategoryController::class,'getChildcategoryBySubCategory'])->name('childcategory-by-subcategory');

    Route::resource('product-brand', ProductBrandController::class);
    Route::put('product-brand-status/{id}', [ProductBrandController::class,'changeStatus'])->name('product.brand.status');

    Route::resource('specification-key', SpecificationKeyController::class);
    Route::put('specification-key-status/{id}', [SpecificationKeyController::class,'changeStatus'])->name('specification-key.status');

    Route::resource('testimonial', TestimonialController::class);
    Route::put('testimonial-status/{id}', [TestimonialController::class,'changeStatus'])->name('testimonial.status');

    Route::resource('product', ProductController::class);
    Route::get('create-product-info', [ProductController::class,'create'])->name('create-product-info');
    Route::put('product-status/{id}', [ProductController::class,'changeStatus'])->name('product.status');
    Route::put('product-approved/{id}', [ProductController::class,'productApproved'])->name('product-approved');
    Route::put('removed-product-exist-specification/{id}', [ProductController::class,'removedProductExistSpecification'])->name('removed-product-exist-specification');
    Route::get('seller-product', [ProductController::class,'sellerProduct'])->name('seller-product');
    Route::get('seller-pending-product', [ProductController::class,'sellerPendingProduct'])->name('seller-pending-product');
    Route::get('stockout-product', [ProductController::class,'stockoutProduct'])->name('stockout-product');

    Route::get('product-import', [ProductController::class, 'product_import'])->name('product-import');
    Route::get('product-export', [ProductController::class, 'product_export'])->name('product-export');
    Route::get('product-demo-export', [ProductController::class, 'product_demo_export'])->name('product-demo-export');
    Route::post('store-product-import', [ProductController::class, 'store_product_import'])->name('store-product-import');

    Route::get('product-variant/{id}', [ProductVariantController::class,'index'])->name('product-variant');
    Route::get('create-product-variant/{id}', [ProductVariantController::class,'create'])->name('create-product-variant');
    Route::post('store-product-variant', [ProductVariantController::class,'store'])->name('store-product-variant');
    Route::get('get-product-variant/{id}', [ProductVariantController::class,'show'])->name('get-product-variant');
    Route::put('update-product-variant/{id}', [ProductVariantController::class,'update'])->name('update-product-variant');
    Route::delete('delete-product-variant/{id}', [ProductVariantController::class,'destroy'])->name('delete-product-variant');
    Route::put('product-variant-status/{id}', [ProductVariantController::class,'changeStatus'])->name('product-variant.status');

    Route::get('product-variant-item', [ProductVariantItemController::class,'index'])->name('product-variant-item');
    Route::get('create-product-variant-item/{id}', [ProductVariantItemController::class,'create'])->name('create-product-variant-item');
    Route::post('store-product-variant-item', [ProductVariantItemController::class,'store'])->name('store-product-variant-item');
    Route::get('edit-product-variant-item/{id}', [ProductVariantItemController::class,'edit'])->name('edit-product-variant-item');
    Route::get('get-product-variant-item/{id}', [ProductVariantItemController::class,'show'])->name('egetdit-product-variant-item');
    Route::put('update-product-variant-item/{id}', [ProductVariantItemController::class,'update'])->name('update-product-variant-item');
    Route::delete('delete-product-variant-item/{id}', [ProductVariantItemController::class,'destroy'])->name('delete-product-variant-item');
    Route::put('product-variant-item-status/{id}', [ProductVariantItemController::class,'changeStatus'])->name('product-variant-item.status');

    Route::get('product-gallery/{id}', [ProductGalleryController::class,'index'])->name('product-gallery');
    Route::post('store-product-gallery', [ProductGalleryController::class,'store'])->name('store-product-gallery');
    Route::delete('delete-product-image/{id}', [ProductGalleryController::class,'destroy'])->name('delete-product-image');
    Route::put('product-gallery-status/{id}', [ProductGalleryController::class,'changeStatus'])->name('product-gallery.status');

    Route::resource('service', ServiceController::class);
    Route::put('service-status/{id}', [ServiceController::class,'changeStatus'])->name('service.status');

    Route::resource('about-us', AboutUsController::class);

    Route::resource('contact-us', ContactPageController::class);

    Route::resource('custom-page', CustomPageController::class);

    Route::put('custom-page-status/{id}', [CustomPageController::class,'changeStatus'])->name('custom-page.status');

    Route::resource('terms-and-condition', TermsAndConditionController::class);

    Route::resource('privacy-policy', PrivacyPolicyController::class);

    Route::resource('blog-category', BlogCategoryController::class);
    Route::put('blog-category-status/{id}', [BlogCategoryController::class,'changeStatus'])->name('blog.category.status');

    Route::resource('blog', BlogController::class);
    Route::put('blog-status/{id}', [BlogController::class,'changeStatus'])->name('blog.status');

    Route::resource('popular-blog', PopularBlogController::class);
    Route::put('popular-blog-status/{id}', [PopularBlogController::class,'changeStatus'])->name('popular-blog.status');

    Route::resource('blog-comment', BlogCommentController::class);
    Route::put('blog-comment-status/{id}', [BlogCommentController::class,'changeStatus'])->name('blog-comment.status');



    



    Route::get('subscriber',[SubscriberController::class,'index'])->name('subscriber');
    Route::delete('delete-subscriber/{id}',[SubscriberController::class,'destroy'])->name('delete-subscriber');
    Route::post('specification-subscriber-email/{id}',[SubscriberController::class,'specificationSubscriberEmail'])->name('specification-subscriber-email');
    Route::post('each-subscriber-email',[SubscriberController::class,'eachSubscriberEmail'])->name('each-subscriber-email');

    Route::get('contact-message',[ContactMessageController::class,'index'])->name('contact-message');
    Route::get('show-contact-message/{id}',[ContactMessageController::class,'show'])->name('show-contact-message');
    Route::delete('delete-contact-message/{id}',[ContactMessageController::class,'destroy'])->name('delete-contact-message');
    Route::put('enable-save-contact-message',[ContactMessageController::class,'handleSaveContactMessage'])->name('enable-save-contact-message');

    Route::get('email-configuration',[EmailConfigurationController::class,'index'])->name('email-configuration');
    Route::put('update-email-configuraion',[EmailConfigurationController::class,'update'])->name('update-email-configuraion');

    Route::get('email-template',[EmailTemplateController::class,'index'])->name('email-template');
    Route::get('edit-email-template/{id}',[EmailTemplateController::class,'edit'])->name('edit-email-template');
    Route::put('update-email-template/{id}',[EmailTemplateController::class,'update'])->name('update-email-template');

    Route::get('general-setting',[SettingController::class,'index'])->name('general-setting');
    Route::put('update-general-setting',[SettingController::class,'updateGeneralSetting'])->name('update-general-setting');
    Route::put('update-theme-color',[SettingController::class,'updateThemeColor'])->name('update-theme-color');
    Route::put('update-statistics-color',[SettingController::class,'updateStatisticsColor'])->name('update-statistics-color');
    Route::put('update-statistics-font-color',[SettingController::class,'updateStatisticsFontColor'])->name('update-statistics-font-color');
    Route::put('update-logo-favicon',[SettingController::class,'updateLogoFavicon'])->name('update-logo-favicon');
    Route::put('update-cookie-consent',[SettingController::class,'updateCookieConset'])->name('update-cookie-consent');
    Route::put('update-google-recaptcha',[SettingController::class,'updateGoogleRecaptcha'])->name('update-google-recaptcha');
    Route::put('update-facebook-comment',[SettingController::class,'updateFacebookComment'])->name('update-facebook-comment');
    Route::put('update-tawk-chat',[SettingController::class,'updateTawkChat'])->name('update-tawk-chat');
    Route::put('update-google-analytic',[SettingController::class,'updateGoogleAnalytic'])->name('update-google-analytic');
    Route::put('update-google-tag-manager',[SettingController::class,'updateGoogleTagManager'])->name('update-google-tag-manager');
    Route::put('update-custom-pagination',[SettingController::class,'updateCustomPagination'])->name('update-custom-pagination');
    Route::put('update-social-login',[SettingController::class,'updateSocialLogin'])->name('update-social-login');
    Route::put('update-facebook-pixel',[SettingController::class,'updateFacebookPixel'])->name('update-facebook-pixel');
    Route::put('update-pusher',[SettingController::class,'updatePusher'])->name('update-pusher');

    Route::resource('admin', AdminController::class);
    Route::put('admin-status/{id}', [AdminController::class,'changeStatus'])->name('admin-status');

    Route::resource('faq', FaqController::class);
    Route::put('faq-status/{id}', [FaqController::class,'changeStatus'])->name('faq-status');

    Route::get('product-review',[ProductReviewController::class,'index'])->name('product-review');
    Route::put('product-review-status/{id}',[ProductReviewController::class,'changeStatus'])->name('product-review-status');
    Route::get('show-product-review/{id}',[ProductReviewController::class,'show'])->name('show-product-review');
    Route::delete('delete-product-review/{id}',[ProductReviewController::class,'destroy'])->name('delete-product-review');

    Route::get('product-report',[ProductReportController::class, 'index'])->name('product-report');
    Route::get('show-product-report/{id}',[ProductReportController::class, 'show'])->name('show-product-report');
    Route::delete('delete-product-report/{id}',[ProductReportController::class, 'destroy'])->name('delete-product-report');
    Route::put('de-active-product/{id}',[ProductReportController::class, 'deactiveProduct'])->name('de-active-product');

    Route::get('customer-list',[CustomerController::class,'index'])->name('customer-list');
    Route::get('customer-show/{id}',[CustomerController::class,'show'])->name('customer-show');
    Route::put('customer-status/{id}',[CustomerController::class,'changeStatus'])->name('customer-status');
    Route::delete('customer-delete/{id}',[CustomerController::class,'destroy'])->name('customer-delete');
    Route::get('pending-customer-list',[CustomerController::class,'pendingCustomerList'])->name('pending-customer-list');
    Route::get('send-email-to-all-customer',[CustomerController::class,'sendEmailToAllUser'])->name('send-email-to-all-customer');
    Route::post('send-mail-to-all-user',[CustomerController::class,'sendMailToAllUser'])->name('send-mail-to-all-user');
    Route::post('send-mail-to-single-user/{id}',[CustomerController::class,'sendMailToSingleUser'])->name('send-mail-to-single-user');

    Route::get('seller-list',[SellerController::class,'index'])->name('seller-list');
    Route::get('seller-show/{id}',[SellerController::class,'show'])->name('seller-show');
    Route::put('seller-status/{id}',[SellerController::class,'changeStatus'])->name('seller-status');
    Route::delete('seller-delete/{id}',[SellerController::class,'destroy'])->name('seller-delete');
    Route::get('pending-seller-list',[SellerController::class,'pendingSellerList'])->name('pending-seller-list');
    Route::put('seller-update/{id}',[SellerController::class,'updateSeller'])->name('seller-update');
    Route::get('seller-shop-detail/{id}',[SellerController::class,'sellerShopDetail'])->name('seller-shop-detail');
    Route::put('remove-seller-social-link/{id}',[SellerController::class,'removeSellerSocialLink'])->name('remove-seller-social-link');

    Route::put('update-seller-shop/{id}',[SellerController::class,'updateSellerSop'])->name('update-seller-shop');
    Route::get('seller-reviews/{id}',[SellerController::class,'sellerReview'])->name('seller-reviews');
    Route::get('show-seller-review-details/{id}',[SellerController::class,'showSellerReviewDetails'])->name('show-seller-review-details');
    Route::get('send-email-to-seller/{id}',[SellerController::class,'sendEmailToSeller'])->name('send-email-to-seller');
    Route::post('send-mail-to-single-seller/{id}',[SellerController::class,'sendMailtoSingleSeller'])->name('send-mail-to-single-seller');
    Route::get('email-history/{id}',[SellerController::class,'emailHistory'])->name('email-history');
    Route::get('product-by-seller/{id}',[SellerController::class,'productBySaller'])->name('product-by-seller');
    Route::get('send-email-to-all-seller',[SellerController::class,'sendEmailToAllSeller'])->name('send-email-to-all-seller');
    Route::post('send-mail-to-all-seller',[SellerController::class,'sendMailToAllSeller'])->name('send-mail-to-all-seller');
    Route::get('withdraw-list/{id}',[SellerController::class,'sellerWithdrawList'])->name('withdraw-list');

    Route::get('state-by-country/{id}',[SellerController::class,'stateByCountry'])->name('state-by-country');
    Route::get('city-by-state/{id}',[SellerController::class,'cityByState'])->name('city-by-state');

    Route::resource('error-page', ErrorPageController::class);

    Route::get('maintainance-mode',[ContentController::class,'maintainanceMode'])->name('maintainance-mode');
    Route::put('maintainance-mode-update',[ContentController::class,'maintainanceModeUpdate'])->name('maintainance-mode-update');
    Route::get('announcement',[ContentController::class,'announcementModal'])->name('announcement');
    Route::post('announcement-update',[ContentController::class,'announcementModalUpdate'])->name('announcement-update');

    Route::get('topbar-contact', [ContentController::class, 'headerPhoneNumber'])->name('topbar-contact');
    Route::put('update-topbar-contact', [ContentController::class, 'updateHeaderPhoneNumber'])->name('update-topbar-contact');

    Route::get('product-quantity-progressbar', [ContentController::class, 'productProgressbar'])->name('product-quantity-progressbar');
    Route::put('update-product-quantity-progressbar', [ContentController::class, 'updateProductProgressbar'])->name('update-product-quantity-progressbar');

    Route::get('default-avatar', [ContentController::class, 'defaultAvatar'])->name('default-avatar');
    Route::post('update-default-avatar', [ContentController::class, 'updateDefaultAvatar'])->name('update-default-avatar');

    Route::get('seller-conditions', [ContentController::class, 'sellerCondition'])->name('seller-conditions');
    Route::put('update-seller-conditions', [ContentController::class, 'updatesellerCondition'])->name('update-seller-conditions');

    Route::get('subscription-banner', [ContentController::class, 'subscriptionBanner'])->name('subscription-banner');
    Route::post('update-subscription-banner', [ContentController::class, 'updatesubscriptionBanner'])->name('update-subscription-banner');

    Route::get('flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale');
    Route::put('update-flash-sale', [FlashSaleController::class, 'update'])->name('update-flash-sale');
    Route::get('flash-sale-product', [FlashSaleController::class, 'flash_sale_product'])->name('flash-sale-product');
    Route::post('store-flash-sale-product', [FlashSaleController::class, 'store'])->name('store-flash-sale-product');
    Route::put('flash-sale-product-status/{id}', [FlashSaleController::class, 'changeStatus'])->name('flash-sale-product-status');
    Route::delete('delete-flash-sale-product/{id}', [FlashSaleController::class,'destroy'])->name('delete-flash-sale-product');

    Route::get('advertisement',[AdvertisementController::class, 'index'])->name('advertisement');
    Route::post('mega-menu-banner-update', [AdvertisementController::class, 'megaMenuBannerUpdate'])->name('mega-menu-banner-update');
    Route::post('slider-banner-one', [AdvertisementController::class, 'updateSliderBannerOne'])->name('slider-banner-one');
    Route::post('slider-banner-two', [AdvertisementController::class, 'updateSliderBannerTwo'])->name('slider-banner-two');
    Route::post('slider-banner-third', [AdvertisementController::class, 'updateSliderBannerThird'])->name('slider-banner-third');
    Route::post('popular-category-sidebar', [AdvertisementController::class, 'updatePopularCategorySidebar'])->name('popular-category-sidebar');
    Route::post('homepage-two-col-first-banner', [AdvertisementController::class, 'homepageTwoColFirstBanner'])->name('homepage-two-col-first-banner');
    Route::post('homepage-two-col-second-banner', [AdvertisementController::class, 'homepageTwoColSecondBanner'])->name('homepage-two-col-second-banner');
    Route::post('homepage-single-first-banner', [AdvertisementController::class, 'homepageSinleFirstBanner'])->name('homepage-single-first-banner');
    Route::post('homepage-single-second-banner', [AdvertisementController::class, 'homepageSinleSecondBanner'])->name('homepage-single-second-banner');
    Route::post('homepage-flash-sale-sidebar-banner', [AdvertisementController::class, 'homepageFlashSaleSidebarBanner'])->name('homepage-flash-sale-sidebar-banner');
    Route::post('shop-page-center-banner', [AdvertisementController::class, 'shopPageCenterBanner'])->name('shop-page-center-banner');
    Route::post('shop-page-sidebar-banner', [AdvertisementController::class, 'shopPageSidebarBanner'])->name('shop-page-sidebar-banner');

    Route::get('login-page', [ContentController::class, 'loginPage'])->name('login-page');
    Route::post('update-login-page', [ContentController::class, 'updateloginPage'])->name('update-login-page');
    Route::get('image-content', [ContentController::class, 'image_content'])->name('image-content');
    Route::post('update-image-content', [ContentController::class, 'updateImageContent'])->name('update-image-content');
    Route::get('shop-page',[ContentController::Class, 'shopPage'])->name('shop-page');
    Route::put('update-filter-price',[ContentController::Class, 'updateFilterPrice'])->name('update-filter-price');

    Route::get('seo-setup',[ContentController::Class, 'seoSetup'])->name('seo-setup');
    Route::put('update-seo-setup/{id}',[ContentController::Class, 'updateSeoSetup'])->name('update-seo-setup');
    Route::get('get-seo-setup/{id}',[ContentController::Class, 'getSeoSetup'])->name('get-seo-setup');

    Route::resource('country', CountryController::class);
    Route::put('country-status/{id}',[CountryController::class,'changeStatus'])->name('country-status');

    Route::get('country-import-page',[CountryController::class,'country_import_page'])->name('country-import-page');
    Route::get('country-export',[CountryController::class,'country_export'])->name('country-export');
    Route::get('country-demo-export',[CountryController::class,'demo_country_export'])->name('country-demo-export');
    Route::post('country-import',[CountryController::class,'country_import'])->name('country-import');

    Route::resource('state', CountryStateController::class);
    Route::put('state-status/{id}',[CountryStateController::class,'changeStatus'])->name('state-status');

    Route::get('state-import-page',[CountryStateController::class,'state_import_page'])->name('state-import-page');
    Route::get('state-export',[CountryStateController::class,'state_export'])->name('state-export');
    Route::get('state-demo-export',[CountryStateController::class,'demo_state_export'])->name('state-demo-export');
    Route::post('state-import',[CountryStateController::class,'state_import'])->name('state-import');


    Route::resource('city', CityController::class);
    Route::put('city-status/{id}',[CityController::class,'changeStatus'])->name('city-status');

    Route::get('city-import-page',[CityController::class,'city_import_page'])->name('city-import-page');
    Route::get('city-export',[CityController::class,'city_export'])->name('city-export');
    Route::get('city-demo-export',[CityController::class,'demo_city_export'])->name('city-demo-export');
    Route::post('city-import',[CityController::class,'city_import'])->name('city-import');


    Route::get('payment-method',[PaymentMethodController::class,'index'])->name('payment-method');
    Route::put('update-paypal',[PaymentMethodController::class,'updatePaypal'])->name('update-paypal');
    Route::put('update-stripe',[PaymentMethodController::class,'updateStripe'])->name('update-stripe');
    Route::put('update-razorpay',[PaymentMethodController::class,'updateRazorpay'])->name('update-razorpay');
    Route::put('update-bank',[PaymentMethodController::class,'updateBank'])->name('update-bank');
    Route::put('update-mollie',[PaymentMethodController::class,'updateMollie'])->name('update-mollie');
    Route::put('update-paystack',[PaymentMethodController::class,'updatePayStack'])->name('update-paystack');
    Route::put('update-flutterwave',[PaymentMethodController::class,'updateflutterwave'])->name('update-flutterwave');
    Route::put('update-instamojo',[PaymentMethodController::class,'updateInstamojo'])->name('update-instamojo');
    Route::put('update-cash-on-delivery',[PaymentMethodController::class,'updateCashOnDelivery'])->name('update-cash-on-delivery');
    Route::put('update-sslcommerz',[PaymentMethodController::class,'updateSslcommerz'])->name('update-sslcommerz');

    Route::resource('mega-menu-category', MegaMenuController::class);
    Route::put('mega-menu-category-status/{id}',[MegaMenuController::class,'changeStatus'])->name('mega-menu-category-status');

    Route::get('mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'index'])->name('mega-menu-sub-category');
    Route::get('create-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'create'])->name('create-mega-menu-sub-category');
    Route::get('get-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'show'])->name('get-mega-menu-sub-category');
    Route::post('store-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'store'])->name('store-mega-menu-sub-category');
    Route::get('edit-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'edit'])->name('edit-mega-menu-sub-category');
    Route::put('update-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'update'])->name('update-mega-menu-sub-category');
    Route::delete('delete-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'destroy'])->name('delete-mega-menu-sub-category');
    Route::put('mega-menu-sub-category-status/{id}',[MegaMenuSubCategoryController::class,'changeStatus'])->name('mega-menu-sub-category-status');

    Route::resource('slider', SliderController::class);
    Route::put('slider-status/{id}',[SliderController::class,'changeStatus'])->name('slider-status');

    Route::get('popular-category', [HomePageController::class, 'popularCategory'])->name('popular-category');
    Route::post('store-popular-category', [HomePageController::class, 'storePopularCategory'])->name('store-popular-category');
    Route::delete('destroy-popular-category/{id}', [HomePageController::class, 'destroyPopularCategory'])->name('destroy-popular-category');

    Route::put('popular-category-banner', [HomePageController::class, 'bannerPopularCategory'])->name('popular-category-banner');
    Route::put('featured-category-banner', [HomePageController::class, 'bannerFeaturedCategory'])->name('featured-category-banner');

    Route::get('featured-category', [HomePageController::class, 'featuredCategory'])->name('featured-category');
    Route::post('store-featured-category', [HomePageController::class, 'storeFeaturedCategory'])->name('store-featured-category');
    Route::delete('destroy-featured-category/{id}', [HomePageController::class, 'destroyFeaturedCategory'])->name('destroy-featured-category');

    Route::get('homepage-section-title', [HomePageController::class, 'homepage_section_content'])->name('homepage-section-title');
    Route::post('update-homepage-section-title', [HomePageController::class, 'update_homepage_section_content'])->name('update-homepage-section-title');

    Route::get('homepage-visibility', [HomepageVisibilityController::class, 'index'])->name('homepage-visibility');
    Route::put('update-homepage-visibility', [HomepageVisibilityController::class, 'update'])->name('update-homepage-visibility');

    Route::get('menu-visibility', [MenuVisibilityController::class, 'index'])->name('menu-visibility');
    Route::put('update-menu-visibility/{id}', [MenuVisibilityController::class, 'update'])->name('update-menu-visibility');

    Route::resource('shipping', ShippingMethodController::class);
    Route::get('city-wise-shipping/{city_id}', [ShippingMethodController::class , 'cityWiseShipping'])->name('city-wise-shipping');

    Route::get('shipping-import-page',[ShippingMethodController::class,'shipping_import_page'])->name('shipping-import-page');
    Route::get('shipping-export',[ShippingMethodController::class,'shipping_export'])->name('shipping-export');
    Route::get('shipping-demo-export',[ShippingMethodController::class,'demo_shipping_export'])->name('shipping-demo-export');
    Route::post('shipping-import',[ShippingMethodController::class,'shipping_import'])->name('shipping-import');

    Route::resource('withdraw-method', WithdrawMethodController::class);
    Route::put('withdraw-method-status/{id}',[WithdrawMethodController::class,'changeStatus'])->name('withdraw-method-status');

    Route::get('seller-withdraw', [SellerWithdrawController::class, 'index'])->name('seller-withdraw');
    Route::get('pending-seller-withdraw', [SellerWithdrawController::class, 'pendingSellerWithdraw'])->name('pending-seller-withdraw');

    Route::get('show-seller-withdraw/{id}', [SellerWithdrawController::class, 'show'])->name('show-seller-withdraw');
    Route::delete('delete-seller-withdraw/{id}', [SellerWithdrawController::class, 'destroy'])->name('delete-seller-withdraw');
    Route::put('approved-seller-withdraw/{id}', [SellerWithdrawController::class, 'approvedWithdraw'])->name('approved-seller-withdraw');

    Route::get('order-dashboard', [OrderController::class, 'dashboard'])->name('order-dashboard');
    Route::get('all-order', [OrderController::class, 'index'])->name('all-order');
    Route::get('pending-order', [OrderController::class, 'pendingOrder'])->name('pending-order');
    Route::get('pregress-order', [OrderController::class, 'pregressOrder'])->name('pregress-order');
    Route::get('delivered-order', [OrderController::class, 'deliveredOrder'])->name('delivered-order');
    Route::get('completed-order', [OrderController::class, 'completedOrder'])->name('completed-order');
    Route::get('declined-order', [OrderController::class, 'declinedOrder'])->name('declined-order');
    Route::get('cash-on-delivery', [OrderController::class, 'cashOnDelivery'])->name('cash-on-delivery');
    Route::get('order-show/{id}', [OrderController::class, 'show'])->name('order-show');
    Route::put('order-address/{id}', [OrderController::class, 'updateOrderAddress'])->name('order-address.update');
    Route::delete('delete-order/{id}', [OrderController::class, 'destroy'])->name('delete-order');
    Route::put('update-order-status/{id}', [OrderController::class, 'updateOrderStatus'])->name('update-order-status');

    Route::resource('coupon', CouponController::class);

    Route::put('coupon-status/{id}',[CouponController::class,'changeStatus'])->name('coupon-status');

    Route::resource('banner-image', BreadcrumbController::class);

    Route::resource('footer', FooterController::class);

    Route::resource('social-link', FooterSocialLinkController::class);

    Route::resource('footer-link', FooterLinkController::class);
    Route::get('second-col-footer-link', [FooterLinkController::class, 'secondColFooterLink'])->name('second-col-footer-link');
    Route::get('third-col-footer-link', [FooterLinkController::class, 'thirdColFooterLink'])->name('third-col-footer-link');
    Route::put('update-col-title/{id}', [FooterLinkController::class, 'updateColTitle'])->name('update-col-title');



    Route::get('inventory/dashboard', [InventoryController::class, 'dashboard'])->name('inventory.dashboard');
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::get('stock-history/{id}', [InventoryController::class, 'show_inventory'])->name('stock-history');
    Route::post('add-stock', [InventoryController::class, 'add_stock'])->name('add-stock');
    Route::delete('delete-stock/{id}', [InventoryController::class, 'delete_stock'])->name('delete-stock');
    Route::get('inventory/stock-in', [InventoryController::class, 'stockInForm'])->name('inventory.stock-in');
    Route::post('inventory/stock-in', [InventoryController::class, 'storeStockIn'])->name('inventory.stock-in.store');
    Route::get('inventory/stock-out', [InventoryController::class, 'stockOutForm'])->name('inventory.stock-out');
    Route::post('inventory/stock-out', [InventoryController::class, 'storeStockOut'])->name('inventory.stock-out.store');
    Route::get('inventory/adjustment', [InventoryController::class, 'adjustmentForm'])->name('inventory.adjustment');
    Route::post('inventory/adjustment', [InventoryController::class, 'storeAdjustment'])->name('inventory.adjustment.store');
    Route::get('inventory/transfer', [InventoryController::class, 'transferForm'])->name('inventory.transfer');
    Route::post('inventory/transfer', [InventoryController::class, 'storeTransfer'])->name('inventory.transfer.store');
    Route::get('inventory/movements', [InventoryController::class, 'movements'])->name('inventory.movements');
    Route::get('inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('inventory/barcode', [InventoryController::class, 'barcodeIndex'])->name('inventory.barcode');
    Route::post('inventory/barcode/generate', [InventoryController::class, 'generateBarcode'])->name('inventory.barcode.generate');
    Route::post('inventory/barcode/generate-all', [InventoryController::class, 'generateAllBarcodes'])->name('inventory.barcode.generate-all');
    Route::post('inventory/barcode/print', [InventoryController::class, 'printBarcodes'])->name('inventory.barcode.print');
    Route::get('inventory/barcode/search', [InventoryController::class, 'searchBarcode'])->name('inventory.barcode.search');
    Route::resource('warehouse', WarehouseController::class)->except(['show']);
    Route::get('unit', [UnitController::class, 'index'])->name('unit.index');
    Route::post('unit', [UnitController::class, 'store'])->name('unit.store');
    Route::put('unit/{id}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('unit/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');

    Route::get('supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::post('supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::put('supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    Route::get('purchase-order', [PurchaseOrderController::class, 'index'])->name('purchase-order.index');
    Route::get('purchase-order/create', [PurchaseOrderController::class, 'create'])->name('purchase-order.create');
    Route::post('purchase-order', [PurchaseOrderController::class, 'store'])->name('purchase-order.store');
    Route::get('purchase-order/{id}', [PurchaseOrderController::class, 'show'])->name('purchase-order.show');
    Route::get('purchase-order/{id}/submit', [PurchaseOrderController::class, 'submit'])->name('purchase-order.submit');

    Route::get('purchase-receipt', [PurchaseReceiptController::class, 'index'])->name('purchase-receipt.index');
    Route::get('purchase-receipt/create', [PurchaseReceiptController::class, 'create'])->name('purchase-receipt.create');
    Route::post('purchase-receipt', [PurchaseReceiptController::class, 'store'])->name('purchase-receipt.store');
    Route::get('purchase-receipt/{id}', [PurchaseReceiptController::class, 'show'])->name('purchase-receipt.show');

    Route::get('expense', [ExpenseController::class, 'index'])->name('expense.index');
    Route::get('expense/create', [ExpenseController::class, 'create'])->name('expense.create');
    Route::post('expense', [ExpenseController::class, 'store'])->name('expense.store');
    Route::get('expense/{id}/edit', [ExpenseController::class, 'edit'])->name('expense.edit');
    Route::put('expense/{id}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('expense/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');

    Route::get('expense-category', [ExpenseCategoryController::class, 'index'])->name('expense-category.index');
    Route::post('expense-category', [ExpenseCategoryController::class, 'store'])->name('expense-category.store');
    Route::put('expense-category/{id}', [ExpenseCategoryController::class, 'update'])->name('expense-category.update');
    Route::delete('expense-category/{id}', [ExpenseCategoryController::class, 'destroy'])->name('expense-category.destroy');

    Route::get('report', [ReportController::class, 'dashboard'])->name('report.dashboard');
    Route::get('report/inventory', [ReportController::class, 'inventory'])->name('report.inventory');
    Route::get('report/expense', [ReportController::class, 'expense'])->name('report.expense');
    Route::get('report/purchase-order', [ReportController::class, 'purchaseOrder'])->name('report.purchase-order');
    Route::get('report/receive', [ReportController::class, 'receive'])->name('report.receive');
    Route::get('report/return', [ReportController::class, 'returns'])->name('report.returns');
    Route::get('report/sales', [ReportController::class, 'sales'])->name('report.sales');
    Route::get('report/{report}/export/{format}', [ReportController::class, 'export'])
        ->where('report', 'dashboard|inventory|expense|purchase-order|receive|returns|sales|profit')
        ->where('format', 'excel|csv|pdf')
        ->name('report.export');
    Route::get('report/profit', [ReportController::class, 'profit'])->name('report.profit');

    Route::get('purchase-return', [PurchaseReturnController::class, 'index'])->name('purchase-return.index');
    Route::get('purchase-return/create', [PurchaseReturnController::class, 'create'])->name('purchase-return.create');
    Route::post('purchase-return', [PurchaseReturnController::class, 'store'])->name('purchase-return.store');
    Route::get('purchase-return/{id}', [PurchaseReturnController::class, 'show'])->name('purchase-return.show');

    Route::get('sms-notification', [NotificationController::class, 'twilio_sms'])->name('sms-notification');
    Route::put('update-twilio-configuration', [NotificationController::class, 'update_twilio_sms'])->name('update-twilio-configuration');
    Route::put('update-biztech-configuration', [NotificationController::class, 'update_biztech_sms'])->name('update-biztech-configuration');

    Route::get('sms-template', [NotificationController::class, 'sms_template'])->name('sms-template');
    Route::get('edit-sms-template/{id}', [NotificationController::class, 'edit_sms_template'])->name('edit-sms-template');
    Route::put('update-sms-template/{id}', [NotificationController::class, 'update_sms_template'])->name('update-sms-template');
    // Pos Routes........
    Route::get('/pos', [PosController::class, 'Index'])->name('pos.index');
    Route::get('/pos/category/{id}', [PosController::class, 'categoryIndex'])->name('pos.category.index');
    Route::get('/products/search', [PosController::class, 'search'])->name('pos.product.search');
    Route::get('/pos/add/product/{id}', [PosController::class, 'AddProduct'])->name('pos.add.product');
    Route::get('/pos/product/delete/{id}', [PosController::class, 'Destroy'])->name('pos.destroy.product');
    Route::get('/pos/product/cart/increment/{id}', [PosController::class, 'cartIncremet'])->name('pos.cart.increment.product');
    Route::get('/pos/product/cart/decrement/delete/{id}', [PosController::class, 'cartDecrement'])->name('pos.cart.decrement.product');
    Route::get('/pos/product/cart/clear', [PosController::class, 'clearCart'])->name('pos.cart.clear.product');
    Route::post('/pos/add/customer', [PosController::class, 'addCustomer'])->name('pos.add.customer');
    Route::get('/pos/customers/search', [PosController::class, 'searchCustomers'])->name('pos.customer.search');
    Route::post('/pos/customer/select', [PosController::class, 'selectCustomer'])->name('pos.customer.select');
    Route::get('/pos/customer/{id}/address', [PosController::class, 'customerAddress'])->name('pos.customer.address');
    Route::get('/pos/apply/cupon', [PosController::class, 'applyCupon'])->name('pos.apply.cupon');
    Route::post('/pos/order/submit', [PosController::class, 'orderSubmit'])->name('pos.order.submit');
    Route::get('/pos/bulk/order', [PosController::class, 'bulkOrder'])->name('pos.bulk.order');
    Route::get('/pos/bulk/order/serch', [PosController::class, 'bulkOrderSerch'])->name('pos.bulk.order.serch');
    Route::put('/pos/bulk/order/status/change', [PosController::class, 'updateOrderStatus'])->name('pos.bulk.order.status.change');
    Route::put('/pos/update/cart/product', [PosController::class, 'updatePosCart'])->name('pos.update.cart.order');
    Route::post('/pos/add/product/with/detils/{id}', [PosController::class, 'AddProductWithDetils'])->name('pos.cart.order.detils');

    Route::post('/add-new-product-in-order/{id}', [OrderController::class, 'addNewProduct'])->name('add-new-product-in-order');
    Route::get('/increment-order-quantity/{id}/{order_id}', [OrderController::class, 'incrementOrderQuantity'])->name('order-quantity-increment');
    Route::get('/decrement-order-quantity/{id}/{order_id}', [OrderController::class, 'decrementOrderQuantity'])->name('order-quantity-decrement');
    Route::delete('/delete-order-product/{id}/{order_id}', [OrderController::class, 'deleteOrderProduct'])->name('delete-order-product');

    // Language Management Routes
    Route::get('admin-language', [LanguageController::class, 'adminLanguage'])->name('admin-language');
    Route::post('update-admin-language', [LanguageController::class, 'updateAdminLanguage'])->name('update-admin-language');

    Route::get('admin-validation-language', [LanguageController::class, 'adminValidationLanguage'])->name('admin-validation-language');
    Route::post('update-admin-validation-language', [LanguageController::class, 'updateAdminValidationLanguage'])->name('update-admin-validation-language');

    Route::get('website-language', [LanguageController::class, 'websiteLanguage'])->name('website-language');
    Route::post('update-language', [LanguageController::class, 'updateLanguage'])->name('update-language');

    Route::get('website-validation-language', [LanguageController::class, 'websiteValidationLanguage'])->name('website-validation-language');
    Route::post('update-validation-language', [LanguageController::class, 'updateValidationLanguage'])->name('update-validation-language');
});

});



















