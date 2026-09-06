<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Slider;
use App\Helpers\GuestModeHelper;
use App\Models\Setting;
use App\Models\Order;
use App\Models\BannerImage;
use App\Models\HomePageOneVisibility;
use App\Helpers\ProductWatcherHelper;
use App\Models\PopularCategory;
use App\Models\FeaturedCategory;
use App\Models\FlashSale;
use App\Models\FlashSaleProduct;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\StripePayment;
use App\Models\PaypalPayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;
use App\Models\SslcommerzPayment;
use App\Models\BankPayment;
use App\Models\Blog;
use App\Models\SeoSetting;
use App\Models\ContactPage;
use App\Models\AboutUs;
use App\Models\Faq;
use App\Models\CustomPage;
use App\Models\TermsAndCondition;

use Cart;
use Session;

class FrontendController extends Controller
{
    public function index()
    {
        // Fetch homepage data dynamically from admin panel
        $setting = Setting::first();
        $homePageVisibility = HomePageOneVisibility::first();
        
        // SEO settings for homepage
        $seoSetting = SeoSetting::find(1);
        
        // Sliders
        $sliders = Slider::where('status', 1)->orderBy('serial', 'asc')->get();
        
        // Banner Images - Fetch specific four banners from admin advertisement
        $bannerImages = BannerImage::whereIn('id', [16, 17, 18, 19])
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();
        
        // Categories — show 6 active categories on homepage (below slider)
        $categories = Category::withCount(['products' => function ($query) {
                $query->where('status', 1)->where('approve_by_admin', 1);
            }])
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->take(6)
            ->get();
        
        // Popular Categories
        $popularCategories = PopularCategory::with('category')
            ->get();
            
        // Featured Categories
        $featuredCategories = FeaturedCategory::with(['category' => function ($query) {
            $query->withCount(['products' => function ($productQuery) {
                $productQuery->where('status', 1)->where('approve_by_admin', 1);
            }]);
        }])->get();
            
        // Top / Best Selling Products
        $products = Product::with(['category', 'brand', 'reviews', 'activeVariants'])
            ->where('status', 1)
            ->where('approve_by_admin', 1)
            ->where(function ($q) {
                $q->where('is_top', 1)->orWhere('is_best', 1);
            })
            ->latest()
            ->take(10)
            ->get();

        if ($products->isEmpty()) {
            $products = Product::with(['category', 'brand', 'reviews', 'activeVariants'])
                ->where('status', 1)
                ->where('approve_by_admin', 1)
                ->latest()
                ->take(10)
                ->get();
        }
            
        // Featured Products
        $featuredProducts = Product::with(['category', 'brand', 'reviews', 'activeVariants'])
            ->where('status', 1)
            ->where('is_featured', 1)
            ->where('approve_by_admin', 1)
            ->latest()
            ->take(10)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::with(['category', 'brand', 'reviews', 'activeVariants'])
                ->where('status', 1)
                ->where('approve_by_admin', 1)
                ->latest()
                ->take(10)
                ->get();
        }
            
        // New Arrival Products
        $newArrivalProducts = Product::with(['category', 'brand', 'reviews', 'activeVariants'])
            ->where('status', 1)
            ->where('approve_by_admin', 1)
            ->latest()
            ->take(12)
            ->get();
            
        // Best Products
        $bestProducts = Product::with(['category', 'brand', 'reviews', 'activeVariants'])
            ->where('status', 1)
            ->where('is_best', 1)
            ->where('approve_by_admin', 1)
            ->latest()
            ->take(10)
            ->get();

        if ($bestProducts->isEmpty()) {
            $bestProducts = $products;
        }
        
        // Flash Sale
        $flashSale = FlashSale::where('status', 1)
            ->where('end_time', '>=', now())
            ->first();
        $flashSaleProducts = collect();
        if ($flashSale) {
            $flashSaleProducts = FlashSaleProduct::with(['product.category', 'product.brand', 'product.activeVariants'])
                ->where('status', 1)
                ->whereHas('product', function($query) {
                    $query->where('status', 1)->where('approve_by_admin', 1);
                })
                ->get();
        }
            
        // Brands
        $brands = Brand::where('status', 1)
            ->take(6)
            ->get();
            
        // Services
        $services = Service::where('status', 1)->get();
        
        // Testimonials
        $testimonials = Testimonial::where('status', 1)->get();
            
        // Blogs
        $blogs = Blog::where('status', 1)
            ->latest()
            ->take(3)
            ->get();
        
        return view('frontend.home', compact(
            'categories', 
            'products',
            'featuredProducts',
            'newArrivalProducts',
            'bestProducts',
            'brands',
            'blogs',
            'sliders',
            'bannerImages',
            'popularCategories',
            'featuredCategories',
            'flashSale',
            'flashSaleProducts',
            'services',
            'testimonials',
            'setting',
            'homePageVisibility',
            'seoSetting'
        ));
    }
    
    public function products(Request $request)
    {
        $query = Product::with(['category', 'brand', 'reviews', 'activeVariants'])
            ->where('status', 1)
            ->where('approve_by_admin', 1);
            
        // Category filter - handle both slug and ID for backward compatibility
        if ($request->has('category') && $request->category) {
            $categories = explode(',', $request->category);
            
            // Check if categories are slugs or IDs
            $categoryIds = [];
            $subCategoryIds = [];
            
            foreach ($categories as $category) {
                if (is_numeric($category)) {
                    // It's an ID - check if it's a category or subcategory
                    $categoryModel = Category::find($category);
                    if ($categoryModel) {
                        $categoryIds[] = $category;
                    } else {
                        // Check if it's a subcategory ID
                        $subCategoryModel = SubCategory::find($category);
                        if ($subCategoryModel) {
                            $subCategoryIds[] = $category;
                        }
                    }
                } else {
                    // It's a slug - check if it's a category or subcategory slug
                    $categoryModel = Category::where('slug', $category)->first();
                    if ($categoryModel) {
                        $categoryIds[] = $categoryModel->id;
                    } else {
                        // Check if it's a subcategory slug
                        $subCategoryModel = SubCategory::where('slug', $category)->first();
                        if ($subCategoryModel) {
                            $subCategoryIds[] = $subCategoryModel->id;
                        }
                    }
                }
            }
            
            // Apply filters based on what we found
            if (!empty($categoryIds) && !empty($subCategoryIds)) {
                // Both categories and subcategories selected
                $query->where(function($q) use ($categoryIds, $subCategoryIds) {
                    $q->whereIn('category_id', $categoryIds)
                      ->orWhereIn('sub_category_id', $subCategoryIds);
                });
            } elseif (!empty($categoryIds)) {
                // Only categories selected
                $query->whereIn('category_id', $categoryIds);
            } elseif (!empty($subCategoryIds)) {
                // Only subcategories selected
                $query->whereIn('sub_category_id', $subCategoryIds);
            }
        }
        
        // Brand filter
        if ($request->has('brand') && $request->brand) {
            $brands = explode(',', $request->brand);
            $query->whereIn('brand_id', $brands);
        }
        
        // Price range filter
        $this->applyEffectivePriceFilter(
            $query,
            $request->input('min_price'),
            $request->input('max_price')
        );
        
        // Rating filter
        if ($request->has('rating') && $request->rating) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->selectRaw('product_id, AVG(rating) as avg_rating')
                  ->groupBy('product_id')
                  ->havingRaw('AVG(rating) >= ?', [$request->rating]);
            });
        }
        
        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhere('tags', 'LIKE', "%{$search}%");
            });
        }
        
        // Product type filter (featured, new, top)
        if ($request->has('filter') && $request->filter) {
            switch ($request->filter) {
                case 'featured':
                    $query->where('is_featured', 1)->where('show_homepage', 1);
                    break;
                case 'new':
                    // For new arrivals, we can either use a specific flag or just show latest products
                    // Since there's no specific 'new' flag, we'll show latest products
                    $query->latest();
                    break;
                case 'top':
                    $query->where('is_top', 1);
                    break;
                case 'best':
                    $query->where('is_best', 1);
                    break;
                case 'flash_sale':
                    // Filter products that are part of active flash sales
                    // Use FlashSaleProduct directly to fetch product IDs of active items
                    $flashSaleProductIds = FlashSaleProduct::where('status', 1)
                        ->whereHas('product', function($q) {
                            $q->where('status', 1)->where('approve_by_admin', 1);
                        })
                        ->pluck('product_id');

                    if ($flashSaleProductIds->isNotEmpty()) {
                        $query->whereIn('id', $flashSaleProductIds);
                    } else {
                        // If no active flash sale items, return empty result
                        $query->whereRaw('1 = 0');
                    }
                    break;
            }
        }
        
        // Sorting
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price':
                $query->orderByRaw('COALESCE(offer_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(offer_price, price) DESC');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }
        
        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();
        $setting = Setting::first();

        $priceRangeMeta = $this->resolvePriceRangeMeta(
            Product::where('status', 1)->where('approve_by_admin', 1)
        );

        return view('frontend.products', compact(
            'products',
            'categories',
            'brands',
            'setting'
        ) + $priceRangeMeta);
    }
    
    public function productDetail(Request $request, $slug)
    {
        if (!$slug) {
            abort(404, 'Product not found');
        }
        
        // Get product by slug with relationships
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->where('approve_by_admin', 1)
            ->with([
                'category', 
                'brand', 
                'gallery', 
                'specifications.key', 
                'reviews' => function($query) {
                    $query->with('user')->latest();
                },
                'variants' => function ($query) {
                    $query->where('status', 1)
                        ->with(['variantItems' => function ($itemQuery) {
                            $itemQuery->where('status', 1)->orderBy('id');
                        }]);
                }
            ])
            ->firstOrFail();
        
        // Average rating from approved reviews only
        $product->averageRating = $product->reviews->where('status', 1)->avg('rating') ?? 0;
        
        // Get related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->where('approve_by_admin', 1)
            ->with(['category', 'brand', 'reviews', 'activeVariants'])
            ->limit(4)
            ->get()
            ->each(function ($relatedProduct) {
                $relatedProduct->averageRating = $relatedProduct->reviews->avg('rating') ?? 0;
            });
        
        // Get SEO settings
        $seoSetting = SeoSetting::first();
        
        // Get setting for currency
        $setting = Setting::first();

        $watcherId = ProductWatcherHelper::register($product->id);
        $watchingCount = ProductWatcherHelper::count($product->id);

        return view('frontend.product-detail', compact('product', 'relatedProducts', 'seoSetting', 'setting', 'watchingCount', 'watcherId'));
    }

    public function productWatcherHeartbeat(Request $request, $id)
    {
        $productId = (int) $id;
        $product = Product::where('id', $productId)
            ->where('status', 1)
            ->where('approve_by_admin', 1)
            ->first();

        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $visitorId = ProductWatcherHelper::register(
            $productId,
            $request->input('visitor_id')
        );

        return response()->json([
            'success' => true,
            'count' => ProductWatcherHelper::count($productId),
            'visitor_id' => $visitorId,
        ]);
    }
    
    public function getRecommendedProducts()
    {
        // Get random featured or popular products for cart recommendations
        $recommendedProducts = Product::where('status', 1)
            ->where('approve_by_admin', 1)
            ->where(function($query) {
                $query->where('is_featured', 1)
                      ->orWhere('is_top', 1)
                      ->orWhere('is_best', 1);
            })
            ->with(['category', 'brand'])
            ->inRandomOrder()
            ->limit(3)
            ->get();
            
        return response()->json([
            'success' => true,
            'products' => $recommendedProducts
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'products' => [],
            ]);
        }

        $setting = Setting::select('currency_icon')->first();
        $currency = $setting->currency_icon ?? '৳';
        $like = '%' . $query . '%';
        $prefix = $query . '%';

        $products = Product::query()
            ->where('status', 1)
            ->where('approve_by_admin', 1)
            ->where(function ($q) use ($like) {
                $q->where('name', 'LIKE', $like)
                    ->orWhere('short_description', 'LIKE', $like)
                    ->orWhere('tags', 'LIKE', $like)
                    ->orWhere('sku', 'LIKE', $like);
            })
            ->select('id', 'name', 'slug', 'thumb_image', 'price', 'offer_price')
            ->orderByRaw('CASE WHEN name LIKE ? THEN 0 ELSE 1 END', [$prefix])
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->map(function (Product $product) use ($currency) {
                $hasSale = $product->offer_price && $product->offer_price < $product->price;
                $displayPrice = $hasSale ? $product->offer_price : $product->price;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'url' => route('product-detail', ['slug' => $product->slug]),
                    'image' => $product->thumb_image
                        ? asset($product->thumb_image)
                        : asset('frontend/images/default-product.svg'),
                    'price' => number_format((float) $displayPrice, 2),
                    'currency' => $currency,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }
    
    public function category($slug)
    {
        $category = Category::with(['subCategories' => function ($query) {
            $query->where('status', 1)->with('products');
        }])->where('slug', $slug)->firstOrFail();

        $query = Product::where('category_id', $category->id)
            ->where('status', 1)
            ->where('approve_by_admin', 1)
            ->with(['category', 'brand', 'reviews', 'activeVariants']);

        if (request()->filled('sub_category')) {
            $subIds = array_map('intval', array_filter(explode(',', request('sub_category'))));
            $validSubIds = $category->subCategories->whereIn('id', $subIds)->pluck('id')->all();
            if (!empty($validSubIds)) {
                $query->whereIn('sub_category_id', $validSubIds);
            }
        }

        if (request()->filled('brand')) {
            $brandIds = array_map('intval', array_filter(explode(',', request('brand'))));
            if (!empty($brandIds)) {
                $query->whereIn('brand_id', $brandIds);
            }
        }

        $this->applyEffectivePriceFilter(
            $query,
            request('min_price'),
            request('max_price')
        );

        if (request()->filled('rating')) {
            $rating = request('rating');
            $query->whereHas('reviews', function ($q) use ($rating) {
                $q->selectRaw('product_id, AVG(rating) as avg_rating')
                    ->groupBy('product_id')
                    ->havingRaw('AVG(rating) >= ?', [$rating]);
            });
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('short_description', 'LIKE', "%{$search}%")
                    ->orWhere('tags', 'LIKE', "%{$search}%");
            });
        }

        $sort = request('sort', 'newest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(NULLIF(offer_price, ""), price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(NULLIF(offer_price, ""), price) DESC');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $setting = Setting::first();

        $brands = Brand::where('status', 1)
            ->whereHas('products', function ($q) use ($category) {
                $q->where('category_id', $category->id)
                    ->where('status', 1)
                    ->where('approve_by_admin', 1);
            })
            ->orderBy('name')
            ->get();

        $activeSubCategories = collect(explode(',', (string) request('sub_category', '')))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->all();

        $activeBrands = collect(explode(',', (string) request('brand', '')))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->all();

        $priceRangeMeta = $this->resolvePriceRangeMeta(
            Product::where('category_id', $category->id)
                ->where('status', 1)
                ->where('approve_by_admin', 1)
        );

        return view('frontend.category', compact(
            'category',
            'products',
            'setting',
            'brands',
            'activeSubCategories',
            'activeBrands'
        ) + $priceRangeMeta);
    }
    
    public function brand($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        
        // Get categories that have products from this brand
        $brandCategories = Category::whereHas('products', function($query) use ($brand) {
            $query->where('brand_id', $brand->id)->where('status', 1);
        })->with('products')->get();
        
        // Build query for products
        $query = Product::where('brand_id', $brand->id)
            ->where('status', 1)
            ->where('approve_by_admin', 1)
            ->with(['category', 'brand', 'reviews', 'activeVariants']);
        
        $this->applyEffectivePriceFilter(
            $query,
            request('min_price'),
            request('max_price')
        );

        // Apply category filter
        if (request('category')) {
            $query->where('category_id', request('category'));
        }
        
        // Apply sorting
        $sort = request('sort', 'newest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(NULLIF(offer_price, ""), price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(NULLIF(offer_price, ""), price) DESC');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(12)->withQueryString();
        $setting = Setting::first();

        $priceRangeMeta = $this->resolvePriceRangeMeta(
            Product::where('brand_id', $brand->id)
                ->where('status', 1)
                ->where('approve_by_admin', 1)
        );

        return view('frontend.brand', compact(
            'brand',
            'products',
            'brandCategories',
            'setting'
        ) + $priceRangeMeta);
    }
    
    public function about()
    {
        $aboutUs = AboutUs::first();
        return view('frontend.about', compact('aboutUs'));
    }
    
    public function contact()
    {
        $contactPage = ContactPage::first();
        return view('frontend.contact', compact('contactPage'));
    }
    
    public function cart()
    {
        $setting = Setting::first();

        if (! GuestModeHelper::guestCartAllowed()) {
            return redirect()->route('login')->with('error', 'Please login to view your cart.');
        }

        return view('frontend.cart', compact('setting'));
    }

    public function checkout()
    {
        if (! GuestModeHelper::guestCartAllowed()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        $shippingMethods = \App\Models\Shipping::all();
        $bangladeshCountryId = \App\Models\Country::where('name', 'like', 'Bangladesh%')->value('id');

        // Get user addresses if authenticated (default billing/shipping first)
        $addresses = collect();
        $defaultAddressIndex = null;
        if (auth()->check()) {
            $addresses = \App\Models\Address::with('country')
                ->where('user_id', auth()->id())
                ->orderByDesc('default_billing')
                ->orderByDesc('default_shipping')
                ->get();

            if ($addresses->isNotEmpty()) {
                $default = $addresses->firstWhere('default_billing', 1)
                    ?? $addresses->firstWhere('default_shipping', 1)
                    ?? $addresses->first();
                $defaultAddressIndex = $addresses->search(fn ($a) => $a->id === $default->id);
            }
        }

        // Get payment gateway settings
        $stripe_setting = \App\Models\StripePayment::first();
        $paypal_setting = \App\Models\PaypalPayment::first();
        $razorpay_setting = \App\Models\RazorpayPayment::first();
        $flutterwave_setting = \App\Models\Flutterwave::first();
        $mollie_setting = \App\Models\PaystackAndMollie::first();
        $instamojo_setting = \App\Models\InstamojoPayment::first();
        $paystack_setting = \App\Models\PaystackAndMollie::first();
        $sslcommerz_setting = \App\Models\SslcommerzPayment::first();
        $bank_payment_setting = \App\Models\BankPayment::first();

        return view('frontend.checkout', compact(
            'shippingMethods',
            'addresses',
            'defaultAddressIndex',
            'bangladeshCountryId',
            'stripe_setting',
            'paypal_setting',
            'razorpay_setting',
            'flutterwave_setting',
            'mollie_setting',
            'instamojo_setting',
            'paystack_setting',
            'sslcommerz_setting',
            'bank_payment_setting'
        ));
    }

    public function orderSuccess(Request $request)
    {
        $encodedOrderId = $request->get('order');
        
        if (!$encodedOrderId) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }
        
        // Decode the order ID
        $orderNumber = decodeOrderId($encodedOrderId);
        
        if (!$orderNumber) {
            return redirect()->route('home')->with('error', 'Invalid order reference.');
        }
        
        // Find the order by order_id
        $order = Order::with(['orderProducts.product.category', 'user', 'orderAddress'])
            ->where('order_id', $orderNumber)
            ->first();
            
        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }
        
        // Get settings for currency and other configurations
        $setting = Setting::first();
        
        // Clear guest cart after successful order
        if (!Auth::check()) {
            Session::forget('guest_cart');
        }
        
        // Display the order success page with order details
        return view('frontend.order-success', compact('order', 'setting'));
    }
    
    public function blog()
    {
        $blogs = Blog::where('status', 1)->orderBy('id', 'desc')->paginate(9);
        return view('frontend.blog', compact('blogs'));
    }
    
    public function blogDetail($slug)
    {
        $blog = Blog::where(['slug' => $slug, 'status' => 1])->firstOrFail();
        $recentBlogs = Blog::where('status', 1)->where('id', '!=', $blog->id)
            ->orderBy('id', 'desc')->take(5)->get();
            
        return view('frontend.blog-detail', compact('blog', 'recentBlogs'));
    }
    
    public function faq()
    {
        $faqs = Faq::where('status', 1)->get();
        return view('frontend.faq', compact('faqs'));
    }
    
    public function customPage($slug)
    {
        $page = CustomPage::where(['slug' => $slug, 'status' => 1])->firstOrFail();
        return view('frontend.custom-page', compact('page'));
    }
    
    public function termsConditions()
    {
        $termsCondition = TermsAndCondition::first();
        return view('frontend.terms-conditions', compact('termsCondition'));
    }
    
    public function privacyPolicy()
    {
        $privacyPolicy = TermsAndCondition::first();
        return view('frontend.privacy-policy', compact('privacyPolicy'));
    }
    
    public function orderDetails(Request $request, $order_id)
    {
        // Find the order by order_id and ensure it belongs to the authenticated user
        $order = Order::with(['orderProducts.product.category', 'user', 'orderAddress'])
            ->where('order_id', $order_id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found or you do not have permission to view this order.');
        }
        
        $setting = Setting::first();
        
        // Get payment gateway settings for the payment options
        $stripe_setting = StripePayment::first();
        $paypal_setting = PaypalPayment::first();
        $razorpay_setting = RazorpayPayment::first();
        $flutterwave_setting = Flutterwave::first();
        $mollie_setting = PaystackAndMollie::first(); // This model handles both Paystack and Mollie
        $instamojo_setting = InstamojoPayment::first();
        $paystack_setting = PaystackAndMollie::first(); // Same model as mollie
        $sslcommerz_setting = SslcommerzPayment::first();
        $bank_payment_setting = BankPayment::first();
        
        return view('frontend.order-details', compact(
            'order', 
            'setting',
            'stripe_setting',
            'paypal_setting', 
            'razorpay_setting',
            'flutterwave_setting',
            'mollie_setting',
            'instamojo_setting',
            'paystack_setting',
            'sslcommerz_setting',
            'bank_payment_setting'
        ));
    }

    private function applyEffectivePriceFilter($query, $minPrice = null, $maxPrice = null): void
    {
        if ($minPrice !== null && $minPrice !== '') {
            $query->whereRaw(
                'CAST(COALESCE(NULLIF(offer_price, ""), price) AS DECIMAL(12,2)) >= ?',
                [(float) $minPrice]
            );
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->whereRaw(
                'CAST(COALESCE(NULLIF(offer_price, ""), price) AS DECIMAL(12,2)) <= ?',
                [(float) $maxPrice]
            );
        }
    }

    private function resolvePriceRangeMeta($baseQuery): array
    {
        $priceBounds = (clone $baseQuery)
            ->selectRaw('MIN(CAST(COALESCE(NULLIF(offer_price, ""), price) AS DECIMAL(12,2))) as floor_price, MAX(CAST(COALESCE(NULLIF(offer_price, ""), price) AS DECIMAL(12,2))) as ceil_price')
            ->first();

        $priceFloor = (int) floor($priceBounds->floor_price ?? 0);
        $priceCeil = (int) ceil($priceBounds->ceil_price ?? 0);

        if ($priceCeil <= $priceFloor) {
            $priceCeil = $priceFloor + 1000;
        }

        $priceStep = max(1, (int) round(($priceCeil - $priceFloor) / 50));

        $selectedMinPrice = request()->filled('min_price')
            ? (int) request('min_price')
            : $priceFloor;
        $selectedMaxPrice = request()->filled('max_price')
            ? (int) request('max_price')
            : $priceCeil;

        $selectedMinPrice = max($priceFloor, min($selectedMinPrice, $priceCeil));
        $selectedMaxPrice = max($selectedMinPrice, min($selectedMaxPrice, $priceCeil));

        return compact('priceFloor', 'priceCeil', 'priceStep', 'selectedMinPrice', 'selectedMaxPrice');
    }
}