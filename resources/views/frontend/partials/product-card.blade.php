@php
    $setting = $setting ?? App\Models\Setting::first();
    $showCategory = $showCategory ?? true;
    $showBrand = $showBrand ?? false;
    $tagBadge = $tagBadge ?? null;
    $extraClass = $extraClass ?? '';
    $cartStyle = $cartStyle ?? 'full';

    $rating = (float) ($product->averageRating ?? ($product->relationLoaded('reviews') ? ($product->reviews->avg('rating') ?? 0) : 0));
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $reviewCount = $product->reviews_count ?? ($product->relationLoaded('reviews') ? $product->reviews->count() : 0);

    $hasSale = $product->offer_price && $product->offer_price < $product->price;
    $discountPct = $hasSale ? round((($product->price - $product->offer_price) / $product->price) * 100) : 0;

    if (!$tagBadge && !empty($product->is_featured)) {
        $tagBadge = 'featured';
    }
@endphp

<div class="product-card h-100 {{ $extraClass }}"
     data-category="{{ $product->category->slug ?? '' }}"
     data-price="{{ $product->offer_price ?? $product->price }}"
     data-name="{{ $product->name }}">
    <div class="product-image-container">
        <a href="{{ route('product-detail', ['slug' => $product->slug]) }}" class="product-image-link">
            <img src="{{ $product->thumb_image ? asset($product->thumb_image) : asset('frontend/images/default-product.svg') }}"
                 alt="{{ $product->name }}"
                 class="product-image"
                 loading="lazy"
                 onerror="this.src='{{ asset('frontend/images/default-product.svg') }}'">
        </a>

        @if($hasSale)
            <span class="product-badge product-badge--sale">-{{ $discountPct }}%</span>
        @endif

        @if($tagBadge === 'new')
            <span class="product-badge product-badge--tag product-badge--new">New</span>
        @elseif($tagBadge === 'featured')
            <span class="product-badge product-badge--tag product-badge--featured">Featured</span>
        @elseif($tagBadge === 'best')
            <span class="product-badge product-badge--tag product-badge--best">Best</span>
        @elseif($tagBadge === 'flash')
            <span class="product-badge product-badge--tag product-badge--flash">Flash</span>
        @endif

        <div class="product-quick-actions">
            <button type="button"
                    class="product-quick-btn wishlist-btn add-to-wishlist"
                    data-product-id="{{ $product->id }}"
                    title="{{ __('Add to Wishlist') }}">
                <i class="far fa-heart"></i>
            </button>
            <a href="{{ route('product-detail', ['slug' => $product->slug]) }}"
               class="product-quick-btn"
               title="{{ __('View Details') }}">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </div>

    <div class="product-info">
        @if($showCategory)
            <div class="product-meta">
                {{ $product->category->name ?? __('Uncategorized') }}
                @if($showBrand && $product->brand)
                    <span class="product-meta-sep">&bull;</span>{{ $product->brand->name }}
                @endif
            </div>
        @endif

        <h3 class="product-title">
            <a href="{{ route('product-detail', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
        </h3>

        <div class="product-rating">
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
            <span class="product-rating-count">({{ $reviewCount }})</span>
        </div>

        <div class="product-footer">
            <div class="product-price">
                @if($hasSale)
                    <span class="current-price">{{ $setting->currency_icon }}{{ number_format($product->offer_price, 2) }}</span>
                    <span class="original-price">{{ $setting->currency_icon }}{{ number_format($product->price, 2) }}</span>
                @else
                    <span class="current-price">{{ $setting->currency_icon }}{{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <div class="product-cart-wrap">
                @include('frontend.partials.product-add-to-cart', [
                    'product' => $product,
                    'style' => $cartStyle,
                ])
            </div>
        </div>
    </div>
</div>
