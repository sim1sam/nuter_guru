@php
    $filterContext = $filterContext ?? 'desktop';
    $idPrefix = $filterContext === 'mobile' ? 'm' : '';
@endphp

<div class="filter-section">
    <h6 class="filter-title">{{ __('Categories') }}</h6>
    <div class="filter-options">
        @foreach($categories as $category)
        <div class="form-check">
            <input class="form-check-input category-filter" type="checkbox"
                   value="{{ $category->id }}" id="cat{{ $idPrefix }}{{ $category->id }}"
                   {{ request('category') == $category->id ? 'checked' : '' }}>
            <label class="form-check-label" for="cat{{ $idPrefix }}{{ $category->id }}">
                {{ category_name($category) }}
            </label>
        </div>
        @endforeach
    </div>
</div>

@include('frontend.partials.price-range-filter', [
    'title' => __('Price Range'),
    'filterContext' => $filterContext,
    'sliderId' => $filterContext === 'mobile' ? 'priceRangeSliderMobile' : 'priceRangeSlider',
    'applyButtonId' => $filterContext === 'mobile' ? 'applyProductsPriceFilterMobile' : 'applyPriceFilter',
])

<div class="filter-section">
    <h6 class="filter-title">{{ __('Rating') }}</h6>
    <div class="filter-options">
        @for($i = 5; $i >= 1; $i--)
        <div class="form-check">
            <input class="form-check-input rating-filter" type="radio"
                   name="rating{{ $idPrefix }}" value="{{ $i }}" id="productsRating{{ $idPrefix }}{{ $i }}"
                   {{ request('rating') == $i ? 'checked' : '' }}>
            <label class="form-check-label" for="productsRating{{ $idPrefix }}{{ $i }}">
                @for($j = 1; $j <= $i; $j++)
                    <i class="fas fa-star text-warning"></i>
                @endfor
                @for($j = $i + 1; $j <= 5; $j++)
                    <i class="far fa-star text-muted"></i>
                @endfor
                & Up
            </label>
        </div>
        @endfor
    </div>
</div>

@if($filterContext === 'desktop')
<button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
    {{ __('Clear All Filters') }}
</button>
@endif
