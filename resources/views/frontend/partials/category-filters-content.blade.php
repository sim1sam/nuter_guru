@php
    $filterContext = $filterContext ?? 'desktop';
    $idPrefix = $filterContext === 'mobile' ? 'm' : '';
@endphp

@if($category->subCategories->where('status', 1)->count() > 0)
<div class="filter-section">
    <h6 class="filter-title">{{ __('Sub-Category') }}</h6>
    <div class="filter-options">
        @foreach($category->subCategories->where('status', 1) as $subCategory)
        <div class="form-check">
            <input class="form-check-input subcategory-filter" type="checkbox"
                   value="{{ $subCategory->id }}" id="subCat{{ $idPrefix }}{{ $subCategory->id }}"
                   {{ in_array($subCategory->id, $activeSubCategories ?? []) ? 'checked' : '' }}>
            <label class="form-check-label" for="subCat{{ $idPrefix }}{{ $subCategory->id }}">
                {{ category_name($subCategory) }}
            </label>
        </div>
        @endforeach
    </div>
</div>
@endif

@if(($brands ?? collect())->count() > 0)
<div class="filter-section">
    <h6 class="filter-title">{{ __('Brand') }}</h6>
    <div class="filter-options">
        @foreach($brands as $brand)
        <div class="form-check">
            <input class="form-check-input brand-filter" type="checkbox"
                   value="{{ $brand->id }}" id="brand{{ $idPrefix }}{{ $brand->id }}"
                   {{ in_array($brand->id, $activeBrands ?? []) ? 'checked' : '' }}>
            <label class="form-check-label" for="brand{{ $idPrefix }}{{ $brand->id }}">
                {{ $brand->name }}
            </label>
        </div>
        @endforeach
    </div>
</div>
@endif

@include('frontend.partials.price-range-filter', [
    'filterContext' => $filterContext,
    'sliderId' => $filterContext === 'mobile' ? 'priceRangeSliderMobileCat' : 'priceRangeSlider',
    'applyButtonId' => $filterContext === 'mobile' ? 'applyPriceFilterMobile' : 'applyPriceFilter',
])

<div class="filter-section">
    <h6 class="filter-title">{{ __('Rating') }}</h6>
    <div class="filter-options">
        @for($i = 5; $i >= 1; $i--)
        <div class="form-check">
            <input class="form-check-input rating-filter" type="radio"
                   name="rating{{ $idPrefix }}" value="{{ $i }}" id="rating{{ $idPrefix }}{{ $i }}"
                   {{ (int) request('rating') === $i ? 'checked' : '' }}>
            <label class="form-check-label" for="rating{{ $idPrefix }}{{ $i }}">
                @for($j = 1; $j <= $i; $j++)
                    <i class="fas fa-star text-warning small"></i>
                @endfor
                & Up
            </label>
        </div>
        @endfor
    </div>
</div>

@if($filterContext === 'desktop')
<button type="button" class="btn btn-outline-secondary w-100" id="clearCategoryFilters">
    {{ __('Clear All Filters') }}
</button>
@endif
