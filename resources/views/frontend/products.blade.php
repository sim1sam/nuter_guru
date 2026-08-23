@extends('frontend.layouts.app')

@section('title', 'Products - ' . config('app.name', 'Nuter Guru'))

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title mb-0">All Products</h1>
                </div>
                <div class="col-md-6 text-md-end">
                    <!-- Removed duplicate product count -->
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-3 d-none d-lg-block mb-4">
                <div class="filters-sidebar" id="productsFiltersSidebar">
                    <div class="filters-sidebar__head mb-3">
                        <h5 class="filter-title mb-0">Filters</h5>
                    </div>
                    @include('frontend.partials.products-filters-content', ['filterContext' => 'desktop'])
                </div>
            </div>

            <div class="col-lg-9 col-md-12">
                <div class="mobile-shop-bar d-lg-none">
                    <button type="button" class="mobile-shop-bar__chip" data-bs-toggle="offcanvas" data-bs-target="#productsFilterSheet" aria-controls="productsFilterSheet">
                        <i class="fas fa-sliders-h"></i>
                        <span>Filters</span>
                    </button>
                    <div class="mobile-shop-bar__sort">
                        <select class="form-select form-select-sm" id="sortProductsMobile">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price High to Low</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </div>
                </div>

                <div class="products-toolbar d-none d-lg-flex justify-content-between align-items-center mb-4">
                    <div class="view-options">
                        <button class="btn btn-outline-secondary btn-sm view-grid active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm view-list" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                    
                    <div class="sort-options">
                        <select class="form-select" id="sortProducts">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price High to Low</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid" id="productsContainer">
                    <div class="row" id="productsList">
                        @forelse($products as $product)
                        <div class="col-6 col-lg-4 col-md-6 mb-4 product-item">
                            @include('frontend.partials.product-card', ['product' => $product])
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No products found</h4>
                                <p class="text-muted">Try adjusting your filters or search criteria.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="pagination-wrapper">
                    {{ $products->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-bottom mobile-filter-sheet d-lg-none" tabindex="-1" id="productsFilterSheet" aria-labelledby="productsFilterSheetLabel">
    <div class="mobile-filter-sheet__handle" aria-hidden="true"></div>
    <div class="offcanvas-header mobile-filter-sheet__header">
        <h5 class="offcanvas-title" id="productsFilterSheetLabel">Filters</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mobile-filter-sheet__body">
        <div class="filters-sidebar filters-sidebar--sheet" id="productsFiltersMobile">
            @include('frontend.partials.products-filters-content', ['filterContext' => 'mobile'])
        </div>
    </div>
    <div class="mobile-filter-sheet__footer">
        <button type="button" class="btn btn-outline-secondary" id="clearFiltersMobile">Clear</button>
        <button type="button" class="btn btn-primary" id="applyProductsFiltersMobile">Apply Filters</button>
    </div>
</div>

<style>
.filters-sidebar {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.filter-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e9ecef;
}

.filter-options {
    max-height: 200px;
    overflow-y: auto;
}

@media (max-width: 991.98px) {
    .mobile-filter-sheet .filter-options {
        max-height: none;
        overflow: visible;
    }
}

.form-check {
    margin-bottom: 8px;
}

.form-check-label {
    font-size: 14px;
    color: #666;
    cursor: pointer;
}

.products-toolbar {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.view-options .btn {
    margin-right: 5px;
}

.view-options .btn.active {
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
    .filters-sidebar {
        margin-bottom: 20px;
    }
    
    .products-toolbar {
        flex-direction: column;
        gap: 15px;
    }
    
    .view-options {
        order: 2;
    }
    
    .sort-options {
        order: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function getProductsFilterRoot() {
        return window.innerWidth >= 992
            ? document.getElementById('productsFiltersSidebar')
            : document.getElementById('productsFiltersMobile');
    }

    function shouldAutoApplyProductsFilters() {
        return window.innerWidth >= 992;
    }

    const sortSelect = document.getElementById('sortProducts');
    const sortSelectMobile = document.getElementById('sortProductsMobile');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const applyPriceBtn = document.getElementById('applyPriceFilter');

    function applyFilters() {
        const root = getProductsFilterRoot() || document;
        const url = new URL(window.location.href);
        const params = new URLSearchParams();

        const selectedCategories = Array.from(root.querySelectorAll('.category-filter:checked'))
            .map(cb => cb.value);
        if (selectedCategories.length > 0) {
            params.set('category', selectedCategories.join(','));
        }

        const selectedRating = root.querySelector('.rating-filter:checked');
        if (selectedRating) {
            params.set('rating', selectedRating.value);
        }

        const priceWrap = root.querySelector('[data-price-range-slider]');
        const priceParams = window.PriceRangeFilter
            ? window.PriceRangeFilter.getParams(priceWrap)
            : { min_price: null, max_price: null };
        if (priceParams.min_price) {
            params.set('min_price', priceParams.min_price);
        }
        if (priceParams.max_price) {
            params.set('max_price', priceParams.max_price);
        }

        const activeSort = (window.innerWidth >= 992 ? sortSelect : sortSelectMobile);
        if (activeSort && activeSort.value) {
            params.set('sort', activeSort.value);
        }

        url.search = params.toString();
        window.location.href = url.toString();
    }

    const desktopRoot = document.getElementById('productsFiltersSidebar');
    if (desktopRoot) {
        desktopRoot.querySelectorAll('.category-filter, .rating-filter').forEach(filter => {
            filter.addEventListener('change', function () {
                if (shouldAutoApplyProductsFilters()) {
                    applyFilters();
                }
            });
        });
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', applyFilters);
    }

    if (sortSelectMobile) {
        sortSelectMobile.addEventListener('change', applyFilters);
    }

    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', applyFilters);
    }

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            window.location.href = '{{ route("products") }}';
        });
    }

    const applyMobileBtn = document.getElementById('applyProductsFiltersMobile');
    if (applyMobileBtn) {
        applyMobileBtn.addEventListener('click', applyFilters);
    }

    const clearMobileBtn = document.getElementById('clearFiltersMobile');
    if (clearMobileBtn) {
        clearMobileBtn.addEventListener('click', function() {
            window.location.href = '{{ route("products") }}';
        });
    }

    const productsFilterSheet = document.getElementById('productsFilterSheet');
    if (productsFilterSheet) {
        productsFilterSheet.addEventListener('shown.bs.offcanvas', function () {
            if (window.PriceRangeFilter) {
                const mobileWrap = productsFilterSheet.querySelector('[data-price-range-slider]');
                if (mobileWrap) {
                    window.PriceRangeFilter.init(mobileWrap);
                    window.PriceRangeFilter.sync(mobileWrap);
                }
            }
        });
    }
    
    // View toggle
    const viewButtons = document.querySelectorAll('[data-view]');
    const productsList = document.getElementById('productsList');
    
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            viewButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            if (this.dataset.view === 'list') {
                productsList.classList.remove('row');
                productsList.classList.add('list-view');
            } else {
                productsList.classList.add('row');
                productsList.classList.remove('list-view');
            }
        });
    });
});
</script>
@endsection