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
use App\Helpers\GuestModeHelper;

class CartController extends Controller
{
    public function __construct()
    {
        // No middleware - support both authenticated and guest users
    }

    public function addToCart(Request $request)
    {
        try {
            if (! GuestModeHelper::guestCartAllowed()) {
                return GuestModeHelper::loginRequiredResponse('Please login to add products to cart.');
            }

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

            $weightCalc = app(\App\Services\Inventory\WeightCalculationService::class);
            $weightVariantId = $request->filled('weight_variant_id') ? (int) $request->weight_variant_id : null;
            $weightVariant = null;
            $pivot = null;
            $unitPrice = null;
            $baseQuantity = null;
            $variantNameSnapshot = null;
            $unitWeightKg = null;
            $qtyRequested = (float) $request->quantity;

            if ($product->isKg() && $weightVariantId) {
                $pivot = \App\Models\ProductWeightVariant::where('product_id', $product->id)
                    ->where('weight_variant_id', $weightVariantId)
                    ->first();
                if (! $pivot) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid weight variant for this product',
                    ], 400);
                }
                $weightVariant = \App\Models\WeightVariant::where('id', $weightVariantId)->where('status', 1)->first();
                if (! $weightVariant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Weight variant is not available',
                    ], 400);
                }
                $unitPrice = $weightCalc->variantSellingPrice($product, $weightVariant, $pivot);
                $baseQuantity = $weightCalc->calculateBaseQuantity($product, $qtyRequested, $weightVariant);
                $variantNameSnapshot = $weightVariant->name;
                $unitWeightKg = (float) $weightVariant->weight_in_kg;
            } elseif ($product->isKg()) {
                $unitPrice = (float) (($product->offer_price !== null && $product->offer_price !== '') ? $product->offer_price : $product->price);
                $baseQuantity = $weightCalc->roundWeight($qtyRequested);
            }

            // Check stock availability (base unit)
            $availableStock = $weightCalc->roundWeight((float) $product->qty);
            if ($availableStock <= 0) {
                session()->flash('error', 'Product is out of stock');
                return response()->json([
                    'success' => false,
                    'message' => 'Product is out of stock'
                ], 400);
            }

            $requiredBase = $baseQuantity ?? $qtyRequested;
            if (! $weightCalc->hasEnoughStock($product, $requiredBase)) {
                $msg = $weightCalc->availableStockMessage($product);
                session()->flash('error', $msg);
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ], 400);
            }

            // If user is authenticated, save to database
            if (Auth::check()) {
                $user = Auth::user();
                $incomingVariants = $weightVariantId ? [] : $this->normalizeRequestVariants($request);
                $incomingItemIds = $this->variantItemIds($incomingVariants);

                $existingCartItem = ShoppingCart::with('variants')
                    ->where('user_id', $user->id)
                    ->where('product_id', $request->product_id)
                    ->when($weightVariantId, fn ($q) => $q->where('weight_variant_id', $weightVariantId))
                    ->when(! $weightVariantId, fn ($q) => $q->whereNull('weight_variant_id'))
                    ->get()
                    ->first(function ($item) use ($incomingItemIds, $weightVariantId) {
                        if ($weightVariantId) {
                            return (int) $item->weight_variant_id === (int) $weightVariantId;
                        }

                        return $this->variantItemIds($item->variants) === $incomingItemIds;
                    });

                if ($existingCartItem) {
                    $newQuantity = (float) $existingCartItem->qty + $qtyRequested;
                    $newBase = $weightVariant
                        ? $weightCalc->calculateBaseQuantity($product, $newQuantity, $weightVariant)
                        : ($product->isKg() ? $weightCalc->roundWeight($newQuantity) : $newQuantity);
                    if (! $weightCalc->hasEnoughStock($product, $newBase)) {
                        $msg = $weightCalc->availableStockMessage($product);
                        session()->flash('error', $msg);
                        return response()->json([
                            'success' => false,
                            'message' => $msg
                        ], 400);
                    }
                    $existingCartItem->qty = $newQuantity;
                    $existingCartItem->base_quantity = $newBase;
                    if ($unitPrice !== null) {
                        $existingCartItem->unit_price = $unitPrice;
                    }
                    $existingCartItem->save();
                } else {
                    $cartItem = new ShoppingCart();
                    $cartItem->user_id = $user->id;
                    $cartItem->product_id = $request->product_id;
                    $cartItem->qty = $qtyRequested;
                    $cartItem->coupon_name = '';
                    $cartItem->offer_type = 0;
                    $cartItem->weight_variant_id = $weightVariantId;
                    $cartItem->variant_name_snapshot = $variantNameSnapshot;
                    $cartItem->unit_weight_kg = $unitWeightKg;
                    $cartItem->base_quantity = $baseQuantity ?? $qtyRequested;
                    $cartItem->unit_price = $unitPrice;
                    $cartItem->save();

                    foreach ($incomingVariants as $variant) {
                        $cartVariant = new ShoppingCartVariant();
                        $cartVariant->shopping_cart_id = $cartItem->id;
                        $cartVariant->variant_id = $variant['variant_id'];
                        $cartVariant->variant_item_id = $variant['variant_item_id'];
                        $cartVariant->save();
                    }
                }
            } else {
                // Guest session cart — separate line per product + variant combo
                $cart = Session::get('guest_cart', []);
                $incomingVariants = $weightVariantId ? [] : $this->normalizeRequestVariants($request);
                $productKey = $weightVariantId
                    ? $request->product_id.'_wv_'.$weightVariantId
                    : $this->guestCartKey($request->product_id, $incomingVariants);

                if (isset($cart[$productKey])) {
                    $newQuantity = (float) $cart[$productKey]['quantity'] + $qtyRequested;
                    $newBase = $weightVariant
                        ? $weightCalc->calculateBaseQuantity($product, $newQuantity, $weightVariant)
                        : ($product->isKg() ? $weightCalc->roundWeight($newQuantity) : $newQuantity);
                    if (! $weightCalc->hasEnoughStock($product, $newBase)) {
                        $msg = $weightCalc->availableStockMessage($product);
                        session()->flash('error', $msg);
                        return response()->json([
                            'success' => false,
                            'message' => $msg
                        ], 400);
                    }
                    $cart[$productKey]['quantity'] = $newQuantity;
                    $cart[$productKey]['base_quantity'] = $newBase;
                    if ($unitPrice !== null) {
                        $cart[$productKey]['unit_price'] = $unitPrice;
                    }
                } else {
                    $cart[$productKey] = [
                        'product_id' => (int) $request->product_id,
                        'quantity' => $qtyRequested,
                        'variants' => $incomingVariants,
                        'weight_variant_id' => $weightVariantId,
                        'variant_name_snapshot' => $variantNameSnapshot,
                        'unit_weight_kg' => $unitWeightKg,
                        'base_quantity' => $baseQuantity ?? $qtyRequested,
                        'unit_price' => $unitPrice,
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

    /**
     * Normalize variant payload from request into [[variant_id, variant_item_id], ...]
     */
    private function normalizeRequestVariants(Request $request): array
    {
        $normalized = [];

        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $index => $variant) {
                if (is_array($variant) && isset($variant['variant_id'], $variant['variant_item_id'])) {
                    $normalized[] = [
                        'variant_id' => (int) $variant['variant_id'],
                        'variant_item_id' => (int) $variant['variant_item_id'],
                    ];
                    continue;
                }

                // Legacy: variants[i]=variant_id, items[i]=variant_item_id
                $variantId = is_numeric($variant) ? (int) $variant : null;
                $itemId = $request->input("items.$index");
                if ($variantId && $itemId) {
                    $normalized[] = [
                        'variant_id' => $variantId,
                        'variant_item_id' => (int) $itemId,
                    ];
                }
            }
        }

        usort($normalized, function ($a, $b) {
            return [$a['variant_id'], $a['variant_item_id']] <=> [$b['variant_id'], $b['variant_item_id']];
        });

        return $normalized;
    }

    private function variantItemIds($variants): array
    {
        $ids = [];

        foreach ($variants ?? [] as $variant) {
            if (is_object($variant)) {
                $ids[] = (int) ($variant->variant_item_id ?? 0);
            } elseif (is_array($variant)) {
                $ids[] = (int) ($variant['variant_item_id'] ?? 0);
            }
        }

        $ids = array_values(array_filter($ids));
        sort($ids);

        return $ids;
    }

    private function guestCartKey($productId, array $variants): string
    {
        $ids = $this->variantItemIds($variants);

        return empty($ids)
            ? (string) $productId
            : $productId . '_' . implode('-', $ids);
    }

    private function formatCartVariants($variants): array
    {
        $formatted = [];

        foreach ($variants ?? [] as $variant) {
            if (is_object($variant)) {
                $item = $variant->variantItem ?? null;
                $formatted[] = [
                    'variant_id' => (int) ($variant->variant_id ?? 0),
                    'variant_item_id' => (int) ($variant->variant_item_id ?? 0),
                    'variant_name' => $item->product_variant_name ?? '',
                    'variant_value' => $item->name ?? '',
                    'name' => trim(($item->product_variant_name ?? '') . ': ' . ($item->name ?? ''), ': '),
                    'price' => (float) ($item->price ?? 0),
                    'variant_price' => (float) ($item->price ?? 0),
                ];
                continue;
            }

            if (is_array($variant)) {
                $item = null;
                if (! empty($variant['variant_item_id'])) {
                    $item = ProductVariantItem::find($variant['variant_item_id']);
                }
                $formatted[] = [
                    'variant_id' => (int) ($variant['variant_id'] ?? 0),
                    'variant_item_id' => (int) ($variant['variant_item_id'] ?? 0),
                    'variant_name' => $item->product_variant_name ?? ($variant['variant_name'] ?? ''),
                    'variant_value' => $item->name ?? ($variant['variant_value'] ?? ''),
                    'name' => $item
                        ? trim($item->product_variant_name . ': ' . $item->name, ': ')
                        : ($variant['name'] ?? ''),
                    'price' => (float) ($item->price ?? ($variant['price'] ?? 0)),
                    'variant_price' => (float) ($item->price ?? ($variant['variant_price'] ?? $variant['price'] ?? 0)),
                ];
            }
        }

        return $formatted;
    }

    public function getCartItems()
    {
        try {
            if (! GuestModeHelper::guestCartAllowed()) {
                return response()->json([
                    'success' => true,
                    'cart_items' => [],
                    'cart_count' => 0,
                    'cart_total' => 0,
                    'login_required' => true,
                ]);
            }

            if (Auth::check()) {
                $user = Auth::user();
                $cartItems = ShoppingCart::with(['product', 'variants.variantItem', 'weightVariant'])
                    ->where('user_id', $user->id)
                    ->get()
                    ->map(function ($item) {
                        $variants = $this->formatCartVariants($item->variants);
                        if ($item->variant_name_snapshot) {
                            $variants = [[
                                'name' => $item->variant_name_snapshot,
                                'variant_name' => 'Weight',
                                'variant_value' => $item->variant_name_snapshot,
                                'price' => (float) ($item->unit_price ?? 0),
                            ]];
                        }
                        $unitPrice = $item->unit_price !== null
                            ? (float) $item->unit_price
                            : product_unit_price($item->product, $item->variants);

                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'qty' => $item->qty,
                            'quantity' => $item->qty,
                            'product' => $item->product,
                            'variants' => $variants,
                            'weight_variant_id' => $item->weight_variant_id,
                            'unit_weight_kg' => $item->unit_weight_kg,
                            'base_quantity' => $item->base_quantity,
                            'unit_price' => $unitPrice,
                            'line_total' => $unitPrice * $item->qty,
                        ];
                    })
                    ->values();
            } else {
                $cart = Session::get('guest_cart', []);
                $cartItems = collect();

                foreach ($cart as $itemId => $item) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $variants = $this->formatCartVariants($item['variants'] ?? []);
                        if (! empty($item['variant_name_snapshot'])) {
                            $variants = [[
                                'name' => $item['variant_name_snapshot'],
                                'variant_name' => 'Weight',
                                'variant_value' => $item['variant_name_snapshot'],
                                'price' => (float) ($item['unit_price'] ?? 0),
                            ]];
                        }
                        $unitPrice = isset($item['unit_price']) && $item['unit_price'] !== null
                            ? (float) $item['unit_price']
                            : product_unit_price($product, $item['variants'] ?? []);
                        $qty = (float) ($item['quantity'] ?? 1);
                        $cartItems->push([
                            'id' => (string) $itemId,
                            'product_id' => $product->id,
                            'product' => $product,
                            'qty' => $qty,
                            'quantity' => $qty,
                            'variants' => $variants,
                            'weight_variant_id' => $item['weight_variant_id'] ?? null,
                            'unit_weight_kg' => $item['unit_weight_kg'] ?? null,
                            'base_quantity' => $item['base_quantity'] ?? $qty,
                            'unit_price' => $unitPrice,
                            'line_total' => $unitPrice * $qty,
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
            if (! GuestModeHelper::guestCartAllowed()) {
                return GuestModeHelper::loginRequiredResponse('Please login to update your cart.');
            }

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
            if (! GuestModeHelper::guestCartAllowed()) {
                return GuestModeHelper::loginRequiredResponse('Please login to update your cart.');
            }

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
            $product = Product::find($request->product_id);
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            if ($request->filled('weight_variant_id') && $product->isKg()) {
                $weightCalc = app(\App\Services\Inventory\WeightCalculationService::class);
                $wv = \App\Models\WeightVariant::find($request->weight_variant_id);
                $pivot = \App\Models\ProductWeightVariant::where('product_id', $product->id)
                    ->where('weight_variant_id', $request->weight_variant_id)
                    ->first();
                if ($wv) {
                    return response()->json([
                        'productPrice' => $weightCalc->variantSellingPrice($product, $wv, $pivot),
                    ]);
                }
            }

            $basePrice = $product->offer_price === null
                ? (float) $product->price
                : (float) $product->offer_price;

            $variantTotal = 0.0;
            $hasVariantPrice = false;

            if ($request->items && is_array($request->items)) {
                foreach ($request->items as $itemId) {
                    $item = ProductVariantItem::find($itemId);
                    if ($item && (float) $item->price > 0) {
                        $variantTotal += (float) $item->price;
                        $hasVariantPrice = true;
                    }
                }
            } elseif ($request->variants && is_array($request->variants)) {
                foreach ($request->variants as $variant) {
                    $itemId = is_array($variant)
                        ? ($variant['variant_item_id'] ?? null)
                        : null;
                    if (! $itemId) {
                        continue;
                    }
                    $item = ProductVariantItem::find($itemId);
                    if ($item && (float) $item->price > 0) {
                        $variantTotal += (float) $item->price;
                        $hasVariantPrice = true;
                    }
                }
            }

            // Variant prices are full selling prices for the option (not added to base)
            $productPrice = $hasVariantPrice ? $variantTotal : $basePrice;

            return response()->json([
                'productPrice' => round($productPrice, 2),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in calculateProductPrice: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
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

                if ($item->unit_price !== null) {
                    $total += (float) $item->unit_price * (float) $item->qty;
                } else {
                    $total += $this->calculateLineTotal($item->product, (float) $item->qty, $item->variants);
                }
            }

            return round($total, 2);
        }

        $cart = Session::get('guest_cart', []);

        foreach ($cart as $item) {
            $product = Product::find($item['product_id'] ?? null);
            if (! $product) {
                continue;
            }

            if (isset($item['unit_price']) && $item['unit_price'] !== null) {
                $total += (float) $item['unit_price'] * (float) ($item['quantity'] ?? 1);
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

            $total += $this->calculateLineTotal($product, (float) ($item['quantity'] ?? 1), $variantItems);
        }

        return round($total, 2);
    }

    private function calculateLineTotal(Product $product, $quantity, $variants = null): float
    {
        return product_unit_price($product, $variants) * max(0.001, (float) $quantity);
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