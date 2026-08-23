@php
    $effectivePrice = ($product->offer_price && $product->offer_price < $product->price)
        ? $product->offer_price
        : $product->price;
    $availableStock = ($product->qty ?? 0) - ($product->sold_qty ?? 0);
    $hasVariants = $product->relationLoaded('activeVariants')
        ? $product->activeVariants->isNotEmpty()
        : $product->activeVariants()->exists();
    $style = $style ?? 'full';
    $btnClass = $btnClass ?? 'btn btn-primary product-add-btn add-to-cart';
@endphp

@if($style === 'compact')
    @if($availableStock <= 0)
        <button type="button" class="product-cart-icon-btn is-disabled" disabled title="{{ __('Out of Stock') }}">
            <i class="fas fa-ban"></i>
        </button>
    @elseif($hasVariants)
        <a href="{{ route('product-detail', ['slug' => $product->slug]) }}"
           class="product-cart-icon-btn product-cart-icon-btn--options"
           title="{{ __('Select Options') }}">
            <i class="fas fa-sliders-h"></i>
        </a>
    @else
        <button type="button"
                class="product-cart-icon-btn add-to-cart"
                data-product-id="{{ $product->id }}"
                data-product-name="{{ $product->name }}"
                data-product-price="{{ $effectivePrice }}"
                data-product-image="{{ $product->thumb_image ? asset($product->thumb_image) : asset('frontend/images/default-product.svg') }}"
                title="{{ __('Add to Cart') }}">
            <i class="fas fa-shopping-cart"></i>
        </button>
    @endif
@else
    @if($availableStock <= 0)
        <button type="button" class="btn btn-secondary product-add-btn w-100" disabled>{{ __('Out of Stock') }}</button>
    @elseif($hasVariants)
        <a href="{{ route('product-detail', ['slug' => $product->slug]) }}" class="btn btn-primary product-add-btn w-100">
            {{ __('Select Options') }}
        </a>
    @else
        <button type="button"
                class="{{ $btnClass }} w-100"
                data-product-id="{{ $product->id }}"
                data-product-name="{{ $product->name }}"
                data-product-price="{{ $effectivePrice }}"
                data-product-image="{{ $product->thumb_image ? asset($product->thumb_image) : asset('frontend/images/default-product.svg') }}">
            {{ __('Add to Cart') }}
        </button>
    @endif
@endif
