@extends('frontend.layouts.app')

@section('title', product_name($product) . ' - ' . config('app.name', 'Nuter Guru'))

@section('content')
@php
    $mainImage = $product->thumb_image ? asset($product->thumb_image) : asset('frontend/images/default-product.svg');
    $hasSale = $product->offer_price && $product->offer_price < $product->price;
    $discountPct = $hasSale ? round((($product->price - $product->offer_price) / $product->price) * 100) : 0;
    $availableStock = max(0, ($product->qty ?? 0) - ($product->sold_qty ?? 0));
    $rating = (float) ($product->averageRating ?? 0);
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $reviewCount = $product->reviews->where('status', 1)->count();
    $basePrice = $product->offer_price ?? $product->price;

    $tags = [];
    if ($product->tags) {
        try {
            $decodedTags = json_decode($product->tags, true);
            if (is_array($decodedTags)) {
                foreach ($decodedTags as $tag) {
                    if (is_array($tag) && isset($tag['value'])) {
                        $tags[] = $tag['value'];
                    } elseif (is_string($tag)) {
                        $tags[] = $tag;
                    }
                }
            } else {
                $tags = array_map('trim', explode(',', $product->tags));
            }
        } catch (Exception $e) {
            $tags = array_map('trim', explode(',', $product->tags));
        }
    }
@endphp

<div class="pd-page">
    <div class="container my-5">
        <div class="row pd-layout g-4 align-items-start">
            <div class="col-12 col-lg-6 pd-layout__media">
                <div class="pd-gallery-card">
                    <div class="pd-main-image-wrap">
                        <img src="{{ $mainImage }}"
                             alt="{{ product_name($product) }}"
                             class="pd-main-image"
                             id="mainProductImage"
                             onerror="this.src='{{ asset('frontend/images/default-product.svg') }}'">

                        @if($hasSale)
                            <span class="product-badge product-badge--sale">-{{ $discountPct }}%</span>
                        @endif
                    </div>

                    <div class="pd-thumbs">
                        <button type="button" class="pd-thumb active" data-image="{{ $mainImage }}">
                            <img src="{{ $mainImage }}" alt="{{ product_name($product) }}" onerror="this.src='{{ asset('frontend/images/default-product.svg') }}'">
                        </button>
                        @foreach($product->gallery as $gallery)
                        <button type="button" class="pd-thumb" data-image="{{ asset($gallery->image) }}">
                            <img src="{{ asset($gallery->image) }}" alt="{{ product_name($product) }}">
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 pd-layout__info">
                <div class="pd-info-card">
                    <div class="product-meta">
                        {{ $product->category->name ?? 'Uncategorized' }}
                        @if($product->brand)
                            <span class="product-meta-sep">&bull;</span>{{ $product->brand->name }}
                        @endif
                    </div>

                    <h1 class="pd-title">{{ product_name($product) }}</h1>

                    <div class="pd-rating-row">
                        <span class="product-stars" aria-label="{{ number_format($rating, 1) }} out of 5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <i class="fas fa-star"></i>
                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="pd-rating-text">{{ number_format($rating, 1) }} ({{ $reviewCount }} {{ $reviewCount === 1 ? 'review' : 'reviews' }})</span>
                    </div>

                    <div class="pd-price-block">
                        <span class="current-price" id="detailCurrentPrice">{{ $setting->currency_icon }}{{ number_format($basePrice, 2) }}</span>
                        @if($hasSale)
                            <span class="original-price" id="detailOriginalPrice">{{ $setting->currency_icon }}{{ number_format($product->price, 2) }}</span>
                            <span class="pd-savings">You save {{ $setting->currency_icon }}{{ number_format($product->price - $product->offer_price, 2) }}</span>
                        @endif
                    </div>

                    @if($product->short_description)
                        <p class="pd-short-desc">{{ $product->short_description }}</p>
                    @endif

                    @php
                        $productVariants = $product->variants
                            ->where('status', 1)
                            ->filter(fn ($variant) => $variant->variantItems->where('status', 1)->isNotEmpty())
                            ->values();
                    @endphp

                    @if($productVariants->count() > 0)
                    <div class="pd-variants mb-3" id="pdVariants">
                        <div class="pd-variants__head">
                            <span class="pd-section-label mb-0">{{ __('Select Options') }}</span>
                            <small class="pd-variants__hint text-muted">{{ __('Please choose your preferred options') }}</small>
                        </div>
                        @foreach($productVariants as $variant)
                        @php
                            $activeItems = $variant->variantItems->where('status', 1)->values();
                            $defaultItem = $activeItems->firstWhere('is_default', 1) ?: $activeItems->first();
                        @endphp
                        <div class="pd-variant-group variant-group" data-variant-id="{{ $variant->id }}">
                            <span class="pd-section-label">{{ $variant->name }} <span class="text-danger">*</span></span>
                            <div class="pd-variant-options" role="radiogroup" aria-label="{{ $variant->name }}">
                                @foreach($activeItems as $item)
                                @php
                                    $variantDisplayPrice = (float) $item->price > 0
                                        ? (float) $item->price
                                        : (float) $basePrice;
                                @endphp
                                <label class="pd-variant-chip">
                                    <input class="variant-option"
                                           type="radio"
                                           name="variant_{{ $variant->id }}"
                                           id="variant_{{ $item->id }}"
                                           value="{{ $item->id }}"
                                           data-name="{{ $item->name }}"
                                           data-price="{{ $item->price }}"
                                           {{ $defaultItem && $defaultItem->id === $item->id ? 'checked' : '' }}
                                           required>
                                    <span class="pd-variant-chip__label">
                                        <span class="pd-variant-chip__name">{{ $item->name }}</span>
                                        <span class="pd-variant-chip__price">{{ $setting->currency_icon }}{{ number_format($variantDisplayPrice, 2) }}</span>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($availableStock > 0)
                    <div class="pd-buy-box">
                        <div class="pd-qty-wrap">
                            <span class="pd-section-label">{{ __('Quantity') }}</span>
                            <div class="pd-qty-control">
                                <button type="button" id="decreaseQty" aria-label="{{ __('Decrease quantity') }}"><i class="fas fa-minus"></i></button>
                                <input type="number" id="productQuantity" value="1" min="1" max="{{ $availableStock }}" aria-label="{{ __('Quantity') }}">
                                <button type="button" id="increaseQty" aria-label="{{ __('Increase quantity') }}"><i class="fas fa-plus"></i></button>
                            </div>
                            <small class="pd-stock-note">{{ $availableStock }} {{ __('in stock') }}</small>
                        </div>

                        @php
                            $whatsappNumber = preg_replace('/\D+/', '', $setting->topbar_phone ?? '');
                            $waText = rawurlencode(__('Hello, I want to order:') . ' ' . product_name($product));
                        @endphp

                        <div class="pd-actions">
                            <button type="button" class="pd-btn pd-btn--cart" id="addToCart" data-product-id="{{ $product->id }}">
                                {{ __('ADD TO CART') }}
                            </button>
                            @if($whatsappNumber)
                                <a class="pd-btn btn-whatsapp-order"
                                   href="https://wa.me/{{ $whatsappNumber }}?text={{ $waText }}"
                                   target="_blank"
                                   rel="noopener">
                                    <i class="fab fa-whatsapp me-1"></i> {{ __('Order on WhatsApp') }}
                                </a>
                            @endif
                            <button type="button" class="pd-btn pd-btn--wishlist" id="addToWishlist" data-product-id="{{ $product->id }}" title="{{ __('Add to Wishlist') }}">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>

                        <div class="pd-direct-order">
                            <button type="button" class="pd-btn pd-btn--buy w-100" id="buyNow" data-product-id="{{ $product->id }}">
                                {{ __('BUY NOW') }}
                            </button>
                        </div>

                        <div class="pd-watching"
                             id="pdWatching"
                             data-product-id="{{ $product->id }}"
                             data-visitor-id="{{ $watcherId }}"
                             data-heartbeat-url="{{ route('product.watching', $product->id) }}">
                            <i class="fas fa-eye"></i>
                            <span>
                                <strong id="pdWatchingCount">{{ $watchingCount }}</strong>
                                <span id="pdWatchingLabel">{{ $watchingCount === 1 ? __('Person watching this product now!') : __('People watching this product now!') }}</span>
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="pd-out-of-stock">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>{{ __('Out of Stock') }}</h5>
                        <p>{{ __('This product is currently unavailable.') }}</p>
                    </div>
                    <div class="pd-actions">
                        <button type="button" class="pd-btn pd-btn--wishlist" id="addToWishlist" data-product-id="{{ $product->id }}" title="{{ __('Add to Wishlist') }}">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    @endif

                    <div class="pd-meta-grid">
                        <div class="pd-meta-item">
                            <strong>{{ __('SKU') }}</strong>
                            <span>{{ $product->sku ?? 'N/A' }}</span>
                        </div>
                        <div class="pd-meta-item">
                            <strong>{{ __('Weight') }}</strong>
                            <span>{{ $product->weight ? $product->weight . 'g' : 'N/A' }}</span>
                        </div>
                        @if(count($tags))
                        <div class="pd-meta-item pd-tags">
                            <strong>{{ __('Tags') }}</strong>
                            <span class="pd-tags-list">
                                @foreach($tags as $tag)
                                    @if(!empty($tag))
                                        <span class="pd-tag">{{ $tag }}</span>
                                    @endif
                                @endforeach
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="pd-tabs-section">
            <ul class="nav pd-tabs-nav" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                            data-bs-target="#description" type="button" role="tab">{{ __('DESCRIPTION') }}</button>
                </li>
                @if($product->specifications->count() > 0)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab"
                            data-bs-target="#specifications" type="button" role="tab">{{ __('SPECIFICATIONS') }}</button>
                </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                            data-bs-target="#reviews" type="button" role="tab">{{ __('REVIEWS') }} ({{ $reviewCount }})</button>
                </li>
            </ul>

            <div class="tab-content" id="productTabsContent">
                <div class="tab-pane fade show active pd-tab-panel" id="description" role="tabpanel">
                    @if($product->long_description)
                        {!! $product->long_description !!}
                    @else
                        <p class="mb-0">{{ $product->short_description ?? 'No description available.' }}</p>
                    @endif

                    @if($product->video_link)
                    <div class="mt-4">
                        <h5 class="mb-3">Product Video</h5>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                            <iframe src="{{ $product->video_link }}" allowfullscreen></iframe>
                        </div>
                    </div>
                    @endif
                </div>

                @if($product->specifications->count() > 0)
                <div class="tab-pane fade pd-tab-panel" id="specifications" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table pd-spec-table mb-0">
                            <tbody>
                                @foreach($product->specifications as $spec)
                                <tr>
                                    <td>{{ $spec->key->key ?? 'N/A' }}</td>
                                    <td>{{ $spec->specification }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div class="tab-pane fade pd-tab-panel" id="reviews" role="tabpanel">
                    @php
                        $approvedReviews = $product->reviews->where('status', 1);
                        $userReview = auth()->check()
                            ? $product->reviews->where('user_id', auth()->id())->first()
                            : null;
                        $canReview = false;
                        if (auth()->check() && ! $userReview) {
                            $canReview = \App\Models\OrderProduct::where('product_id', $product->id)
                                ->whereHas('order', function ($q) {
                                    $q->where('user_id', auth()->id());
                                })
                                ->exists();
                        }
                    @endphp

                    @auth
                        @if($userReview)
                            <div class="alert alert-info mb-4">
                                You already submitted a review for this product
                                @if((int) $userReview->status !== 1)
                                    (pending approval)
                                @endif.
                            </div>
                        @elseif($canReview)
                            <div class="pd-review-form card mb-4">
                                <div class="card-body">
                                    <h5 class="mb-3">Write a Review</h5>
                                    <form action="{{ route('product-review.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div class="mb-3">
                                            <label class="form-label d-block">Your Rating <span class="text-danger">*</span></label>
                                            <div class="pd-rating-input">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" id="rating_{{ $i }}" value="{{ $i }}" {{ (int) old('rating', 5) === $i ? 'checked' : '' }} required>
                                                    <label for="rating_{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"><i class="fas fa-star"></i></label>
                                                @endfor
                                            </div>
                                            @error('rating')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="review" class="form-label">Your Review <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('review') is-invalid @enderror"
                                                      id="review"
                                                      name="review"
                                                      rows="4"
                                                      minlength="10"
                                                      maxlength="2000"
                                                      placeholder="Share your experience with this product..."
                                                      required>{{ old('review') }}</textarea>
                                            @error('review')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-1"></i> Submit Review
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-light border mb-4">
                                Purchase this product to leave a review.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light border mb-4">
                            Please <a href="{{ route('login', ['redirect' => url()->current()]) }}">login</a> to write a review.
                        </div>
                    @endauth

                    @if($approvedReviews->count() > 0)
                        @foreach($approvedReviews as $review)
                        <article class="pd-review-card">
                            <div class="pd-review-head">
                                <div>
                                    <h6 class="pd-review-name">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                    <div class="pd-review-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <time class="pd-review-date">{{ $review->created_at->format('M d, Y') }}</time>
                            </div>
                            <p class="mb-0">{{ $review->review }}</p>
                        </article>
                        @endforeach
                    @else
                        <div class="pd-empty-reviews">
                            <i class="fas fa-star fa-2x mb-3 d-block"></i>
                            <h5>No reviews yet</h5>
                            <p class="mb-0">Be the first to review this product.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($relatedProducts->count() > 0)
        <section class="pd-related">
            <div class="home-section-head">
                <h2 class="section-title">{{ __('RELATED PRODUCTS') }}</h2>
            </div>
            <div class="row products-grid-row" id="relatedProductsGrid">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-6 col-lg-3 col-md-4 col-sm-6 mb-4 product-item">
                    @include('frontend.partials.product-card', [
                        'product' => $relatedProduct,
                        'showBrand' => true,
                    ])
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>

@if($availableStock > 0)
<div class="pd-mobile-bar d-lg-none" id="pdMobileBar">
    <div class="pd-mobile-bar__price">
        <span class="pd-mobile-bar__label">Price</span>
        <strong id="pdMobilePrice">{{ $setting->currency_icon }}{{ number_format($basePrice, 2) }}</strong>
    </div>
    <div class="pd-mobile-bar__actions">
        <button type="button" class="pd-mobile-bar__cart" id="addToCartMobile" data-product-id="{{ $product->id }}" aria-label="Add to cart">
            <i class="fas fa-shopping-cart"></i>
        </button>
        <button type="button" class="pd-mobile-bar__buy" id="buyNowMobile" data-product-id="{{ $product->id }}">
            Buy Now
        </button>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currencyIcon = @json($setting->currency_icon);
    const watchingEl = document.getElementById('pdWatching');
    const watchingCountEl = document.getElementById('pdWatchingCount');
    const watchingLabelEl = document.getElementById('pdWatchingLabel');

    function updateWatchingLabel(count) {
        if (!watchingLabelEl) {
            return;
        }

        watchingLabelEl.textContent = count === 1
            ? @json(__('Person watching this product now!'))
            : @json(__('People watching this product now!'));
    }

    function refreshWatchingCount() {
        if (!watchingEl || !watchingCountEl) {
            return;
        }

        const heartbeatUrl = watchingEl.dataset.heartbeatUrl;
        const visitorId = watchingEl.dataset.visitorId;

        if (!heartbeatUrl) {
            return;
        }

        fetch(heartbeatUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ visitor_id: visitorId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && typeof data.count !== 'undefined') {
                watchingCountEl.textContent = data.count;
                updateWatchingLabel(parseInt(data.count, 10));

                if (data.visitor_id) {
                    watchingEl.dataset.visitorId = data.visitor_id;
                }
            }
        })
        .catch(() => {});
    }

    if (watchingEl) {
        refreshWatchingCount();
        setInterval(refreshWatchingCount, 45000);

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                refreshWatchingCount();
            }
        });
    }

    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.pd-thumb');
    const detailCurrentPrice = document.getElementById('detailCurrentPrice');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const quantityInput = document.getElementById('productQuantity');
    const addToCartBtn = document.getElementById('addToCart');
    const buyNowBtn = document.getElementById('buyNow');
    const wishlistButtons = document.querySelectorAll('#addToWishlist');
    const variantOptions = document.querySelectorAll('.variant-option');
    const basePrice = {{ $basePrice }};

    thumbnails.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            thumbnails.forEach(function (t) { t.classList.remove('active'); });
            this.classList.add('active');
            if (mainImage) {
                mainImage.src = this.dataset.image;
            }
        });
    });

    if (decreaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', function () {
            const currentValue = parseInt(quantityInput.value, 10);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
    }

    if (increaseBtn && quantityInput) {
        increaseBtn.addEventListener('click', function () {
            const currentValue = parseInt(quantityInput.value, 10);
            const maxValue = parseInt(quantityInput.max, 10);
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });
    }

    // Variant selection does not change product price
    variantOptions.forEach(function (option) {
        option.addEventListener('change', updatePrice);
    });

    updatePrice();

    function getSelectedVariantPrice() {
        let variantTotal = 0;
        let hasVariantPrice = false;

        document.querySelectorAll('.variant-option:checked').forEach(function (variant) {
            const price = parseFloat(variant.dataset.price || 0);
            if (price > 0) {
                variantTotal += price;
                hasVariantPrice = true;
            }
        });

        // Variant item price is the selling price for that option (not added on top of main price)
        return hasVariantPrice ? variantTotal : Number(basePrice);
    }

    function updatePrice() {
        const totalPrice = getSelectedVariantPrice();
        const formatted = currencyIcon + Number(totalPrice).toFixed(2);
        if (detailCurrentPrice) {
            detailCurrentPrice.textContent = formatted;
        }
        const mobilePrice = document.getElementById('pdMobilePrice');
        if (mobilePrice) {
            mobilePrice.textContent = formatted;
        }
    }

    const addToCartMobile = document.getElementById('addToCartMobile');
    if (addToCartMobile && addToCartBtn) {
        addToCartMobile.addEventListener('click', function () {
            addToCartBtn.click();
        });
    }

    const buyNowMobile = document.getElementById('buyNowMobile');
    if (buyNowMobile && buyNowBtn) {
        buyNowMobile.addEventListener('click', function () {
            buyNowBtn.click();
        });
    }

    function validateVariantSelection() {
        const variantGroups = document.querySelectorAll('.variant-group');
        if (variantGroups.length === 0) {
            return true;
        }

        const selectedVariants = document.querySelectorAll('.variant-option:checked');
        if (selectedVariants.length < variantGroups.length) {
            showNotification(@json(__('Please select all required product options before proceeding.')), 'danger');
            const box = document.getElementById('pdVariants');
            if (box) {
                box.scrollIntoView({ behavior: 'smooth', block: 'center' });
                box.classList.add('pd-variants--attention');
                setTimeout(function () { box.classList.remove('pd-variants--attention'); }, 1200);
            }
            return false;
        }

        return true;
    }

    function collectSelectedVariants() {
        const selectedVariants = [];
        document.querySelectorAll('.variant-option:checked').forEach(function (variant) {
            selectedVariants.push({
                variant_id: variant.name.replace('variant_', ''),
                variant_item_id: variant.value
            });
        });
        return selectedVariants;
    }

    if (addToCartBtn && quantityInput) {
        addToCartBtn.addEventListener('click', function () {
            if (!validateVariantSelection()) {
                return;
            }

            pdAddToCart(this.dataset.productId, quantityInput.value, collectSelectedVariants());
        });
    }

    if (buyNowBtn && quantityInput) {
        buyNowBtn.addEventListener('click', function () {
            if (!validateVariantSelection()) {
                return;
            }

            pdBuyNow(this.dataset.productId, quantityInput.value, collectSelectedVariants());
        });
    }

    wishlistButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            addToWishlist(this.dataset.productId);
        });
    });
    function pdAddToCart(productId, quantity, variants) {
        if (!addToCartBtn) {
            return;
        }

        const originalText = addToCartBtn.innerHTML;
        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        addToCartBtn.disabled = true;

        if (typeof requiresCartLogin === 'function' && requiresCartLogin()) {
            addToCartBtn.innerHTML = originalText;
            addToCartBtn.disabled = false;
            redirectToLogin('Please login to add products to cart.');
            return;
        }

        // Prepare data
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        
        if (variants && variants.length > 0) {
            variants.forEach((variant, index) => {
                formData.append(`variants[${index}][variant_id]`, variant.variant_id);
                formData.append(`variants[${index}][variant_item_id]`, variant.variant_item_id);
            });
        }

        // Add CSRF token
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Make AJAX request
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (typeof handleCartLoginRequired === 'function' && handleCartLoginRequired(data)) {
                return;
            }
            if (data.success) {
                showNotification(data.message, 'success');
                // Update cart count across all header badges
                const cartCountElements = document.querySelectorAll('.cart-count');
                if (data.cart_count !== undefined && cartCountElements.length) {
                    cartCountElements.forEach(el => {
                        el.textContent = data.cart_count;
                    });
                }
                // Re-sync via global updater if available
                if (typeof updateCartCount === 'function') {
                    try { updateCartCount(); } catch (e) { /* no-op */ }
                }
                if (typeof window.openCartDrawer === 'function') {
                    window.openCartDrawer();
                }
            } else {
                showNotification(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        })
        .finally(() => {
            // Restore button state
            addToCartBtn.innerHTML = originalText;
            addToCartBtn.disabled = false;
        });
    }
    
    function pdBuyNow(productId, quantity, variants) {
        if (!buyNowBtn) {
            return;
        }

        const originalText = buyNowBtn.innerHTML;
        buyNowBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        buyNowBtn.disabled = true;

        // Build query parameters for price calculation
        let priceParams = new URLSearchParams();
        priceParams.append('product_id', productId);
        
        if (variants && variants.length > 0) {
            variants.forEach((variant, index) => {
                priceParams.append(`variants[${index}]`, variant.variant_id);
                priceParams.append(`items[${index}]`, variant.variant_item_id);
            });
        }
        
        // Calculate price first
        console.log('Making web call to:', `/cart/calculate-product-price?${priceParams.toString()}`);
                fetch(`/cart/calculate-product-price?${priceParams.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('API Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(priceData => {
            console.log('Price API Response:', priceData);
            if (priceData.productPrice === undefined || priceData.productPrice === null) {
                console.error('Product price is undefined or null:', priceData);
                throw new Error('Product price not found in API response');
            }
            const totalPrice = (priceData.productPrice * quantity).toFixed(2);
            console.log('Calculated total price:', totalPrice);
            const currencyIcon = '{{ $setting->currency_icon }}';
            
            // Now add to cart
            const cartFormData = new FormData();
            cartFormData.append('product_id', productId);
            cartFormData.append('quantity', quantity);
            
            if (variants && variants.length > 0) {
                variants.forEach((variant, index) => {
                    cartFormData.append(`variants[${index}][variant_id]`, variant.variant_id);
                    cartFormData.append(`variants[${index}][variant_item_id]`, variant.variant_item_id);
                });
            }
            
            cartFormData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            return fetch('/cart/add', {
                method: 'POST',
                body: cartFormData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .then(data => {
                if (data.message) {
                    showNotification(`Total: ${currencyIcon}${totalPrice} - ${data.message} - Redirecting to checkout...`, 'success');
                    // Redirect to checkout page after a brief delay
                    setTimeout(() => {
                        window.location.href = '/checkout';
                    }, 1500);
                } else {
                    showNotification(data.message || 'An error occurred', 'danger');
                    // Restore button state on error
                    buyNowBtn.innerHTML = originalText;
                    buyNowBtn.disabled = false;
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
            // Restore button state on error
            buyNowBtn.innerHTML = originalText;
            buyNowBtn.disabled = false;
        });
    }
    
    function addToWishlist(productId) {
        // Implement your add to wishlist logic here
        showNotification('Product added to wishlist!', 'success');
    }
    
    function showNotification(message, type) {
        if (typeof window.showNotification === 'function') {
            return window.showNotification(message, type);
        }
        alert(message);
    }
});
</script>
@endpush