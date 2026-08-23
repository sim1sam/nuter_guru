<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariantItem;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function __construct()
    {
        // No middleware - support both authenticated and guest users
    }

    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($request->product_id);
            
            // Check if product is active and approved
            if ($product->status != 1 || $product->approve_by_admin != 1) {
                session()->flash('error', 'Product is not available');
                return response()->json([
                    'success' => false,
                    'message' => 'Product is not available'
                ], 400);
            }

            // Check stock availability
            $availableStock = $product->qty - $product->sold_qty;
            if ($availableStock <= 0) {
                session()->flash('error', 'Product is out of stock');
                return response()->json([
                    'success' => false,
                    'message' => 'Product is out of stock'
                ], 400);
            }

            if ($request->quantity > $availableStock) {
                session()->flash('error', "Only {$availableStock} items available in stock");
                return response()->json([
                    'success' => false,
                    'message' => "Only {$availableStock} items available in stock"
                ], 400);
            }

            // If user is authenticated, save to database
            if (Auth::check()) {
                $user = Auth::user();
            
            // Check if product already exists in cart
            $existingCartItem = ShoppingCart::where([
                'user_id' => $user->id,
                'product_id' => $request->product_id
            ])->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->qty + $request->quantity;
                if ($newQuantity > $availableStock) {
                    session()->flash('error', "Cannot add more items. Only {$availableStock} items available in stock");
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot add more items. Only {$availableStock} items available in stock"
                    ], 400);
                }
                $existingCartItem->qty = $newQuantity;
                $existingCartItem->save();
                $cartItemId = $existingCartItem->id;
            } else {
                $cartItem = new ShoppingCart();
                $cartItem->user_id = $user->id;
                $cartItem->product_id = $request->product_id;
                $cartItem->qty = $request->quantity;
                $cartItem->coupon_name = '';
                $cartItem->offer_type = 0;
                $cartItem->save();
                $cartItemId = $cartItem->id;
            }

            // Handle product variants if provided
            if ($request->has('variants') && is_array($request->variants)) {
                // Remove existing variants for this cart item
                ShoppingCartVariant::where('shopping_cart_id', $cartItemId)->delete();
                
                foreach ($request->variants as $variant) {
                    if (isset($variant['variant_id']) && isset($variant['variant_item_id'])) {
                        $cartVariant = new ShoppingCartVariant();
                        $cartVariant->shopping_cart_id = $cartItemId;
                        $cartVariant->variant_id = $variant['variant_id'];
                        $cartVariant->variant_item_id = $variant['variant_item_id'];
                        $cartVariant->save();
                    }
                }
            }
        } else {
            // For guest users, use session-based cart
            $cart = Session::get('guest_cart', []);
            $productKey = $request->product_id;
            
            if (isset($cart[$productKey])) {
                $newQuantity = $cart[$productKey]['quantity'] + $request->quantity;
                if ($newQuantity > $availableStock) {
                    session()->flash('error', "Cannot add more items. Only {$availableStock} items available in stock");
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot add more items. Only {$availableStock} items available in stock"
                    ], 400);
                }
                $cart[$productKey]['quantity'] = $newQuantity;
            } else {
                $cart[$productKey] = [
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'variants' => $request->variants ?? []
                ];
            }
            
            Session::put('guest_cart', $cart);
        }

        // Get updated cart count
        $cartCount = $this->getCartCount();
        $cartTotal = $this->getCartTotal();

        return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
            ]);
        } catch (\Exception $e) {
            \Log::error('Add to cart failed: ' . $e->getMessage());
            
            // Set session flash message for blade template display
            session()->flash('error', 'Failed to add product to cart. Please try again.');
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart. Please try again.'
            ], 500);
        }
    }

    public function getCartItems()
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $cartItems = ShoppingCart::with(['product', 'variants.variantItem'])
                    ->where('user_id', $user->id)
                    ->get();
            } else {
                $cart = Session::get('guest_cart', []);
                $cartItems = collect();
                
                foreach ($cart as $itemId => $item) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $cartItems->push([
                            'id' => $itemId, // Include the cart item ID for updates
                            'product' => $product,
                            'qty' => $item['quantity'],
                            'variants' => $item['variants'] ?? []
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'cart_items' => $cartItems,
                'cart_count' => $this->getCartCount(),
                'cart_total' => $this->getCartTotal(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getCartItems: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching cart items'
            ], 500);
        }
    }

    public function updateQuantity(Request $request)
    {
        try {
            $request->validate([
                'cart_item_id' => 'required',
                'quantity' => 'required|integer|min:1'
            ]);

            if (Auth::check()) {
                $cartItem = ShoppingCart::where([
                    'id' => $request->cart_item_id,
                    'user_id' => Auth::id()
                ])->first();

                if (!$cartItem) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cart item not found'
                    ], 404);
                }

                $product = Product::find($cartItem->product_id);
                $availableStock = $product->qty - $product->sold_qty;

                if ($request->quantity > $availableStock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Only {$availableStock} items available in stock"
                    ], 400);
                }

                $cartItem->qty = $request->quantity;
                $cartItem->save();
            } else {
                $cart = Session::get('guest_cart', []);
                \Log::info('Guest cart update - Current cart:', ['cart' => $cart]);
                \Log::info('Guest cart update - Item ID: ' . $request->cart_item_id);
                
                if (isset($cart[$request->cart_item_id])) {
                    $product = Product::find($cart[$request->cart_item_id]['product_id']);
                    if (!$product) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Product not found'
                        ], 404);
                    }
                    
                    $availableStock = $product->qty - $product->sold_qty;

                    if ($request->quantity > $availableStock) {
                        return response()->json([
                            'success' => false,
                            'message' => "Only {$availableStock} items available in stock"
                        ], 400);
                    }

                    $cart[$request->cart_item_id]['quantity'] = $request->quantity;
                    Session::put('guest_cart', $cart);
                    \Log::info('Guest cart updated:', ['cart' => $cart]);
                } else {
                    \Log::error('Cart item not found for guest user. Item ID: ' . $request->cart_item_id);
                    return response()->json([
                        'success' => false,
                        'message' => 'Cart item not found'
                    ], 404);
                }
            }

            // Get updated cart count
            $cartCount = $this->getCartCount();
            $cartTotal = $this->getCartTotal();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in updateQuantity: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating cart'
            ], 500);
        }
    }

    public function removeItem(Request $request)
    {
        try {
            $request->validate([
            'cart_item_id' => 'required'
        ]);

        if (Auth::check()) {
            $cartItem = ShoppingCart::where([
                'id' => $request->cart_item_id,
                'user_id' => Auth::id()
            ])->first();

            if ($cartItem) {
                ShoppingCartVariant::where('shopping_cart_id', $cartItem->id)->delete();
                $cartItem->delete();
            }
        } else {
            $cart = Session::get('guest_cart', []);
            unset($cart[$request->cart_item_id]);
            Session::put('guest_cart', $cart);
        }

        $cartCount = $this->getCartCount();
        $cartTotal = $this->getCartTotal();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in removeItem: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing item'
            ], 500);
        }
    }

    public function clearCart()
    {
        try {
            if (Auth::check()) {
            $user = Auth::user();
            $cartItems = ShoppingCart::where('user_id', $user->id)->get();
            
            foreach ($cartItems as $cartItem) {
                ShoppingCartVariant::where('shopping_cart_id', $cartItem->id)->delete();
                $cartItem->delete();
            }
        } else {
            Session::forget('guest_cart');
        }

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'cart_count' => 0,
                'cart_total' => 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in clearCart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while clearing cart'
            ], 500);
        }
    }

    public function calculateProductPrice(Request $request)
    {
        try {
            \Log::info('Frontend calculateProductPrice called with:', $request->all());
            
            $prices = [];
            $variantPrice = 0;
            if($request->variants){
                foreach($request->variants as $index => $varr){
                    if (!isset($request->items[$index])) {
                        \Log::error('Missing item for variant index: ' . $index);
                        continue;
                    }
                    $item = ProductVariantItem::where(['id' => $request->items[$index]])->first();
                    if ($item) {
                        $prices[] = $item->price;
                    } else {
                        \Log::error('ProductVariantItem not found for id: ' . $request->items[$index]);
                    }
                }
                $variantPrice = $variantPrice + array_sum($prices);
            }

            $product = Product::find($request->product_id);
            if (!$product) {
                \Log::error('Product not found for id: ' . $request->product_id);
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Simplified price calculation without campaign logic
            $productPrice = 0;
            if ($product->offer_price == null) {
                $productPrice = $product->price + $variantPrice;
            } else {
                $productPrice = $product->offer_price + $variantPrice;
            }

            $productPrice = round($productPrice, 2);
            \Log::info('Calculated product price: ' . $productPrice);
            return response()->json(['productPrice' => $productPrice]);
        } catch (\Exception $e) {
            \Log::error('Error in calculateProductPrice: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function getCartCountApi()
    {
        try {
            return response()->json([
                'success' => true,
                'cart_count' => $this->getCartCount(),
                'cart_total' => $this->getCartTotal(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting cart count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'cart_count' => 0,
                'cart_total' => 0,
            ], 500);
        }
    }

    private function getCartCount()
    {
        if (Auth::check()) {
            return ShoppingCart::where('user_id', Auth::id())->sum('qty');
        }

        $cart = Session::get('guest_cart', []);

        return array_sum(array_column($cart, 'quantity'));
    }

    private function getCartTotal(): float
    {
        $total = 0.0;

        if (Auth::check()) {
            $cartItems = ShoppingCart::with(['product', 'variants.variantItem'])
                ->where('user_id', Auth::id())
                ->get();

            foreach ($cartItems as $item) {
                if (! $item->product) {
                    continue;
                }

                $total += $this->calculateLineTotal($item->product, $item->qty, $item->variants) ;
            }

            return round($total, 2);
        }

        $cart = Session::get('guest_cart', []);

        foreach ($cart as $item) {
            $product = Product::find($item['product_id'] ?? null);
            if (! $product) {
                continue;
            }

            $variantItems = collect($item['variants'] ?? [])
                ->map(function ($variant) {
                    if (! isset($variant['variant_item_id'])) {
                        return null;
                    }

                    return ProductVariantItem::find($variant['variant_item_id']);
                })
                ->filter();

            $total += $this->calculateLineTotal($product, $item['quantity'] ?? 1, $variantItems);
        }

        return round($total, 2);
    }

    private function calculateLineTotal(Product $product, int $quantity, $variants = null): float
    {
        $variantPrice = 0.0;

        if ($variants) {
            foreach ($variants as $variant) {
                $variantItem = $variant->variantItem ?? $variant;
                if ($variantItem) {
                    $variantPrice += (float) $variantItem->price;
                }
            }
        }

        $basePrice = $product->offer_price === null
            ? (float) $product->price
            : (float) $product->offer_price;

        return ($basePrice + $variantPrice) * max(1, $quantity);
    }

    public function applyCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string'
            ]);

            // Check if cart has items
            if (Auth::check()) {
                $cartCount = ShoppingCart::where('user_id', Auth::id())->count();
            } else {
                $cart = Session::get('guest_cart', []);
                $cartCount = count($cart);
            }

            if ($cartCount == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your shopping cart is empty'
                ]);
            }

            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)
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

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'coupon' => [
                    'code' => $coupon->code,
                    'discount' => $coupon->discount,
                    'offer_type' => $coupon->offer_type
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
}