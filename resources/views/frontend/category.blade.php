@extends('frontend.layouts.app')

@section('title', $category->name . ' - ' . config('app.name', 'Nuter Guru'))

@push('styles')
<style>
body.category-page .main-content {
    padding-left: 0;
    padding-right: 0;
    max-width: 100%;
}
</style>
@endpush

@section('content')
<style>
/* Simple dropdown solution - Completely rewritten */
.sort-dropdown-container {
    position: relative;
    display: inline-block;
    z-index: 1000;
    isolation: isolate;
}

.sort-btn {
    position: relative;
    z-index: 1001;
    cursor: pointer;
    background: white;
    border: 1px solid #dee2e6;
}

.sort-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1002;
    min-width: 12rem;
    padding: 0.5rem 0;
    margin: 0;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    list-style: none;
    margin-top: 2px;
}

.sort-item {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem;
    color: #212529;
    text-decoration: none;
    border: none;
    background: none;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.sort-item:hover {
    background-color: #f8f9fa;
    color: #212529;
    text-decoration: none;
}

.sort-dropdown .dropdown-item {
    display: block;
    width: 100%;
    padding: 0.375rem 1rem;
    clear: both;
    font-weight: 400;
    color: #212529;
    text-align: inherit;
    text-decoration: none;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
    cursor: pointer;
}

.sort-dropdown .dropdown-item:hover {
    background-color: #f8f9fa;
}

.custom-dropdown-menu .dropdown-item:hover,
.custom-dropdown-menu .dropdown-item:focus {
    background-color: #f8f9fa !important;
    color: #16181b !important;
}

/* Ensure all other elements stay below */
.product-grid, .product-item, .product-card, .card {
    z-index: 1 !important;
    position: relative !important;
}

.container, .container-fluid, main, section, .row, .col {
    z-index: 0 !important;
}
</style>

@if($category->image)
<section class="category-hero-banner" style="--banner-img: url('{{ asset($category->image) }}');">
    <div class="category-hero-banner__overlay">
        <div class="container">
            <h1 class="category-hero-banner__title">{{ $category->name }}</h1>
            @if($category->description)
            <p class="category-hero-banner__desc">{{ $category->description }}</p>
            @endif
            <span class="category-hero-banner__badge">{{ $products->total() }} Products</span>
        </div>
    </div>
</section>
@else
<section class="category-hero-banner category-hero-banner--plain">
    <div class="container text-center">
        <h1 class="category-hero-banner__title">{{ $category->name }}</h1>
        @if($category->description)
        <p class="category-hero-banner__desc">{{ $category->description }}</p>
        @endif
        <span class="category-hero-banner__badge">{{ $products->total() }} Products</span>
    </div>
</section>
@endif

<div class="container my-5">
    <!-- Sub-categories -->
    @if($category->subCategories->count() > 0)
    <div class="sub-categories mb-5">
        <h3 class="mb-4">Shop by Sub-Category</h3>
        <div class="row">
            @foreach($category->subCategories->where('status', 1) as $subCategory)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="sub-category-card">
                    <a href="{{ route('products', ['category' => $subCategory->slug]) }}" class="text-decoration-none">
                        <div class="sub-category-image">
                            @if($subCategory->image)
                                <img src="{{ asset($subCategory->image) }}" alt="{{ $subCategory->name }}" class="img-fluid">
                            @else
                                <div class="placeholder-image d-flex align-items-center justify-content-center">
                                    <i class="fas fa-gem fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="sub-category-info text-center p-3">
                            <h5 class="sub-category-name mb-2">{{ $subCategory->name }}</h5>
                            <p class="text-muted small mb-0">{{ \App\Models\Product::where('sub_category_id', $subCategory->id)->where('status', 1)->where('approve_by_admin', 1)->count() }} items</p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="row category-shop-row">
        <div class="col-lg-3 d-none d-lg-block mb-4 mb-lg-0">
            <div class="filters-sidebar" id="categoryFiltersSidebar">
                <div class="filters-sidebar__head">
                    <h5 class="mb-0">{{ __('Filters') }}</h5>
                </div>
                @include('frontend.partials.category-filters-content', ['filterContext' => 'desktop'])
            </div>
        </div>

        <div class="col-lg-9 col-md-12">
            <div class="mobile-shop-bar d-lg-none">
                <button type="button" class="mobile-shop-bar__chip" data-bs-toggle="offcanvas" data-bs-target="#categoryFilterSheet" aria-controls="categoryFilterSheet">
                    <i class="fas fa-sliders-h"></i>
                    <span>{{ __('Filters') }}</span>
                </button>
                <div class="mobile-shop-bar__sort">
                    <select class="form-select form-select-sm" id="categorySortMobile" aria-label="{{ __('Sort products') }}">
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>{{ __('Name (A-Z)') }}</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>{{ __('Name (Z-A)') }}</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>{{ __('Price (Low to High)') }}</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>{{ __('Price (High to Low)') }}</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>{{ __('Highest Rated') }}</option>
                    </select>
                </div>
            </div>

            <div class="products-toolbar mb-4 d-none d-md-block">
                <div class="row align-items-center g-3">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted small">
                            {{ __('Showing') }} {{ $products->count() }} {{ __('of') }} {{ $products->total() }} {{ __('products') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end gap-3 flex-wrap">
                            <div class="view-toggle">
                                <button class="btn btn-outline-secondary btn-sm view-btn active" data-view="grid">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm view-btn" data-view="list">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>

                            <div class="sort-dropdown-container">
                                <button class="btn btn-outline-secondary sort-btn" type="button" onclick="toggleSortDropdown(event)">
                                    <i class="fas fa-sort me-2"></i>{{ __('Sort by') }} <i class="fas fa-chevron-down ms-2"></i>
                                </button>
                                <div class="sort-menu" id="sortMenu" style="display: none;">
                                    <a href="#" class="sort-item" onclick="sortProducts('name_asc', event); return false;">{{ __('Name (A-Z)') }}</a>
                                    <a href="#" class="sort-item" onclick="sortProducts('name_desc', event); return false;">{{ __('Name (Z-A)') }}</a>
                                    <a href="#" class="sort-item" onclick="sortProducts('price_asc', event); return false;">{{ __('Price (Low to High)') }}</a>
                                    <a href="#" class="sort-item" onclick="sortProducts('price_desc', event); return false;">{{ __('Price (High to Low)') }}</a>
                                    <a href="#" class="sort-item" onclick="sortProducts('newest', event); return false;">{{ __('Newest First') }}</a>
                                    <a href="#" class="sort-item" onclick="sortProducts('rating', event); return false;">{{ __('Highest Rated') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="products-container">
        @if($products->count() > 0)
            <div class="row" id="productsGrid">
                @foreach($products as $product)
                <div class="col-6 col-lg-3 col-md-4 col-sm-6 mb-4 product-item">
                    @include('frontend.partials.product-card', [
                        'product' => $product,
                        'showBrand' => true,
                    ])
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-gem fa-4x text-muted mb-4"></i>
                <h4 class="text-muted mb-3">No products found in this category</h4>
                <p class="text-muted mb-4">Try browsing other categories or check back later for new arrivals.</p>
                <a href="{{ route('products') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Browse All Products
                </a>
            </div>
        @endif
    </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-bottom mobile-filter-sheet d-lg-none" tabindex="-1" id="categoryFilterSheet" aria-labelledby="categoryFilterSheetLabel">
    <div class="mobile-filter-sheet__handle" aria-hidden="true"></div>
    <div class="offcanvas-header mobile-filter-sheet__header">
        <h5 class="offcanvas-title" id="categoryFilterSheetLabel">{{ __('Filters') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}"></button>
    </div>
    <div class="offcanvas-body mobile-filter-sheet__body">
        <div class="filters-sidebar filters-sidebar--sheet" id="categoryFiltersMobile">
            @include('frontend.partials.category-filters-content', ['filterContext' => 'mobile'])
        </div>
    </div>
    <div class="mobile-filter-sheet__footer">
        <button type="button" class="btn btn-outline-secondary" id="clearCategoryFiltersMobile">{{ __('Clear') }}</button>
        <button type="button" class="btn btn-primary" id="applyCategoryFiltersMobile">{{ __('Apply Filters') }}</button>
    </div>
</div>

<style>
.category-hero-banner {
    position: relative;
    width: 100%;
    overflow: hidden;
    min-height: 340px;
    max-height: 520px;
    background-color: #1a1520;
    margin-bottom: 0;
    isolation: isolate;
}

.category-hero-banner::before {
    content: '';
    position: absolute;
    inset: -8%;
    background-image: var(--banner-img);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    transform: scale(1.08);
    z-index: 0;
}

.category-hero-banner__overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 48px 16px;
    background: linear-gradient(180deg, rgba(20, 18, 28, 0.15) 0%, rgba(20, 18, 28, 0.55) 100%);
    color: #fff;
}

.category-hero-banner--plain {
    width: 100%;
    min-height: auto;
    max-height: none;
    padding: 56px 0;
    background: linear-gradient(135deg, #f8f6fc 0%, #efeaf6 100%);
    color: #2d2a3a;
}

.category-hero-banner--plain .category-hero-banner__title {
    color: #2d2a3a;
    text-shadow: none;
}

.category-hero-banner--plain .category-hero-banner__desc {
    color: #6b6580;
}

.category-hero-banner--plain .category-hero-banner__badge {
    background: rgba(var(--primary-rgb), 0.14);
    color: #6f5f8c;
}

.category-hero-banner__title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    margin-bottom: 12px;
    color: #fff;
    text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35);
}

.category-hero-banner__desc {
    max-width: 680px;
    margin: 0 auto 16px;
    font-size: 1.05rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.92);
}

.category-hero-banner__badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(4px);
    font-size: 0.95rem;
    font-weight: 600;
}

.category-header {
    padding: 40px 0;
}

/* Sort Dropdown Styles */
.sort-dropdown {
    position: relative;
}

.sort-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    min-width: 200px;
    padding: 0.5rem 0;
    margin: 0.125rem 0 0;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
}

.sort-dropdown-menu.show {
    display: block;
}

.sort-dropdown-menu .dropdown-item {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem;
    clear: both;
    font-weight: 400;
    color: #212529;
    text-align: inherit;
    text-decoration: none;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
    cursor: pointer;
}

.sort-dropdown-menu .dropdown-item:hover {
    background-color: #f8f9fa;
    color: #1e2125;
}

.sort-dropdown-menu .dropdown-item:active {
    background-color: #0d6efd;
    color: #fff;
}

#sortDropdownBtn .fa-chevron-down {
    transition: transform 0.2s ease;
}

#sortDropdownBtn[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}


.category-title {
    font-size: 2.5rem;
    font-weight: 600;
    color: #333;
}

.category-description {
    max-width: 600px;
    margin: 0 auto;
}

.sub-category-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
    height: 100%;
}

.sub-category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: var(--primary-color);
}

.sub-category-image {
    height: 200px;
    overflow: hidden;
}

.sub-category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.sub-category-card:hover .sub-category-image img {
    transform: scale(1.05);
}

.placeholder-image {
    height: 200px;
    background: #f8f9fa;
}

.sub-category-name {
    color: #333;
    font-weight: 600;
}

.products-toolbar {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.filters-sidebar {
    background: #fff;
    padding: 18px;
    border-radius: 12px;
    border: 1px solid #e8e4ef;
    box-shadow: 0 8px 20px rgba(74, 74, 92, 0.06);
}

.filters-sidebar__head {
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e8e4ef;
}

.filter-section {
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f0edf4;
}

.filter-section:last-of-type {
    border-bottom: 0;
    margin-bottom: 14px;
}

.filter-title {
    font-size: 14px;
    font-weight: 600;
    color: #2d2a3a;
    margin-bottom: 10px;
}

.filter-options {
    max-height: 180px;
    overflow-y: auto;
}

@media (max-width: 991.98px) {
    .mobile-filter-sheet .filter-options {
        max-height: none;
        overflow: visible;
    }
}

.filter-options .form-check {
    margin-bottom: 8px;
}

.filter-options .form-check-label {
    font-size: 14px;
    color: #6b6580;
    cursor: pointer;
}

.category-filter-toggle {
    border-radius: 10px;
    font-weight: 600;
}

.view-toggle .view-btn {
    border-radius: 0;
}

.view-toggle .view-btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.view-toggle .view-btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
    border-left: none;
}

.view-toggle .view-btn.active {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 50px;
}

.pagination {
    display: flex;
    gap: 10px;
    align-items: center;
}

.pagination .page-link {
    padding: 10px 15px;
    border: 1px solid #dee2e6;
    color: #6c757d;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover,
.pagination .page-item.active .page-link {
    background-color: var(--primary-color, #d4af37);
    border-color: var(--primary-color, #d4af37);
    color: white;
}

@media (max-width: 768px) {
    .category-hero-banner {
        min-height: 260px;
        max-height: 360px;
    }

    .category-hero-banner__overlay {
        padding: 32px 16px;
    }

    .category-title {
        font-size: 2rem;
    }
    
    .products-toolbar {
        padding: 15px;
    }
    
    .products-toolbar .row {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<script>
// Immediately execute to avoid conflicts
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }
    
    ready(function() {
    const categoryBaseUrl = @json(route('category', $category->slug));

    function buildCategoryFilterParams(extra) {
        const params = new URLSearchParams(window.location.search);
        const next = new URLSearchParams();

        ['sub_category', 'brand', 'min_price', 'max_price', 'rating', 'sort'].forEach(function (key) {
            if (params.has(key)) {
                next.set(key, params.get(key));
            }
        });

        if (extra) {
            Object.keys(extra).forEach(function (key) {
                if (extra[key] === null || extra[key] === '') {
                    next.delete(key);
                } else {
                    next.set(key, extra[key]);
                }
            });
        }

        const query = next.toString();
        return query ? (categoryBaseUrl + '?' + query) : categoryBaseUrl;
    }

    function applyCategoryFilters(extra) {
        window.location.href = buildCategoryFilterParams(extra);
    }

    function getCategoryFilterRoot() {
        return window.innerWidth >= 992
            ? document.getElementById('categoryFiltersSidebar')
            : document.getElementById('categoryFiltersMobile');
    }

    function collectCategoryFilterParams() {
        const root = getCategoryFilterRoot() || document;
        const params = {};

        const selectedSubCategories = Array.from(root.querySelectorAll('.subcategory-filter:checked')).map(function (el) {
            return el.value;
        });
        if (selectedSubCategories.length) {
            params.sub_category = selectedSubCategories.join(',');
        } else {
            params.sub_category = null;
        }

        const selectedBrands = Array.from(root.querySelectorAll('.brand-filter:checked')).map(function (el) {
            return el.value;
        });
        if (selectedBrands.length) {
            params.brand = selectedBrands.join(',');
        } else {
            params.brand = null;
        }

        const selectedRating = root.querySelector('.rating-filter:checked');
        params.rating = selectedRating ? selectedRating.value : null;

        const priceWrap = root.querySelector('[data-price-range-slider]');
        if (priceWrap && window.PriceRangeFilter) {
            Object.assign(params, window.PriceRangeFilter.getParams(priceWrap));
        }

        return params;
    }

    function shouldAutoApplyCategoryFilters() {
        return window.innerWidth >= 992;
    }

    document.querySelectorAll('#categoryFiltersSidebar .subcategory-filter, #categoryFiltersSidebar .brand-filter').forEach(function (el) {
        el.addEventListener('change', function () {
            if (shouldAutoApplyCategoryFilters()) {
                applyCategoryFilters(collectCategoryFilterParams());
            }
        });
    });

    document.querySelectorAll('#categoryFiltersSidebar .rating-filter').forEach(function (el) {
        el.addEventListener('change', function () {
            if (shouldAutoApplyCategoryFilters()) {
                applyCategoryFilters(collectCategoryFilterParams());
            }
        });
    });

    const applyPriceBtn = document.getElementById('applyPriceFilter');
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', function () {
            applyCategoryFilters(collectCategoryFilterParams());
        });
    }

    const clearFiltersBtn = document.getElementById('clearCategoryFilters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function () {
            window.location.href = categoryBaseUrl;
        });
    }

    const applyMobileBtn = document.getElementById('applyCategoryFiltersMobile');
    if (applyMobileBtn) {
        applyMobileBtn.addEventListener('click', function () {
            applyCategoryFilters(collectCategoryFilterParams());
        });
    }

    const clearMobileBtn = document.getElementById('clearCategoryFiltersMobile');
    if (clearMobileBtn) {
        clearMobileBtn.addEventListener('click', function () {
            window.location.href = categoryBaseUrl;
        });
    }

    const categoryFilterSheet = document.getElementById('categoryFilterSheet');
    if (categoryFilterSheet) {
        categoryFilterSheet.addEventListener('shown.bs.offcanvas', function () {
            if (window.PriceRangeFilter) {
                const mobileWrap = categoryFilterSheet.querySelector('[data-price-range-slider]');
                if (mobileWrap) {
                    window.PriceRangeFilter.init(mobileWrap);
                    window.PriceRangeFilter.sync(mobileWrap);
                }
            }
        });
    }

    const categorySortMobile = document.getElementById('categorySortMobile');
    if (categorySortMobile) {
        categorySortMobile.addEventListener('change', function () {
            window.sortProducts(this.value);
        });
    }

    // View toggle functionality
    const viewButtons = document.querySelectorAll('.view-btn');
    const productsGrid = document.getElementById('productsGrid');
    
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            viewButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.dataset.view;
            if (view === 'list') {
                productsGrid.classList.add('list-view');
            } else {
                productsGrid.classList.remove('list-view');
            }
        });
    });
    
    // Simple global functions for dropdown - no conflicts
    window.toggleSortDropdown = function(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }
        
        const menu = document.getElementById('sortMenu');
        if (menu) {
            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
            } else {
                menu.style.display = 'none';
            }
        }
    };
    
    window.sortProducts = function(sortBy, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }
        
        // Close dropdown
        const menu = document.getElementById('sortMenu');
        if (menu) {
            menu.style.display = 'none';
        }
        
        // Keep existing filter query params, only change sort
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortBy);
        window.location.href = url.toString();
    };
    
    // Add to cart functionality removed - only available on product details page
    
    // Add to wishlist functionality
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    
    wishlistButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.classList.add('text-danger');
                showNotification('Added to wishlist!', 'success');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.classList.remove('text-danger');
                showNotification('Removed from wishlist!', 'info');
            }
        });
    });
    
    // addToCart function removed - only available on product details page
    
    function showNotification(message, type) {
        if (typeof window.showNotification === 'function') {
            return window.showNotification(message, type);
        }
        alert(message);
    }
    });
    
})(); // End IIFE
</script>
@push('scripts')
<script>document.body.classList.add('category-page');</script>
@endpush
@endsection