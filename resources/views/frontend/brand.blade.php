@extends('frontend.layouts.app')

@section('title', $brand->name . ' - ' . config('app.name', 'Nuter Guru'))

@section('content')
<div class="container my-5">
    <!-- Brand Header -->
    <div class="brand-header text-center mb-5">
        @if($brand->logo)
        <div class="brand-logo mb-4">
            <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid">
        </div>
        @endif
        
        <h1 class="brand-title mb-3">{{ $brand->name }}</h1>
        
        @if($brand->description)
        <p class="brand-description text-muted lead">{{ $brand->description }}</p>
        @endif
        
        <div class="brand-stats">
            <span class="badge bg-primary fs-6">{{ $products->total() }} Products</span>
            @if($brand->founded_year)
            <span class="badge bg-secondary fs-6 ms-2">Est. {{ $brand->founded_year }}</span>
            @endif
        </div>
    </div>

    <!-- Brand Story Section -->
    @if($brand->story)
    <div class="brand-story mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto">
                <div class="story-content bg-light p-4 rounded">
                    <h3 class="mb-3">Our Story</h3>
                    <div class="story-text">
                        {!! $brand->story !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Featured Categories for this Brand -->
    @if($brandCategories->count() > 0)
    <div class="brand-categories mb-5">
        <h3 class="mb-4">Shop {{ $brand->name }} by Category</h3>
        <div class="row">
            @foreach($brandCategories as $category)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="category-card">
                    <a href="{{ route('products', ['brand' => $brand->id, 'category' => $category->slug]) }}" class="text-decoration-none">
                        <div class="category-image">
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="img-fluid">
                            @else
                                <div class="placeholder-image d-flex align-items-center justify-content-center">
                                    <i class="fas fa-gem fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="category-info text-center p-3">
                            <h5 class="category-name mb-2">{{ $category->name }}</h5>
                            <p class="text-muted small mb-0">{{ $category->products->where('brand_id', $brand->id)->count() }} items</p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filters and Products -->
    <div class="row g-4">
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="filters-sidebar">
                @include('frontend.partials.price-range-filter')
                <button type="button" class="btn btn-outline-secondary w-100 mt-2" id="clearBrandFilters">
                    {{ __('Clear Price Filter') }}
                </button>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
    <div class="products-toolbar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted small">
                    {{ __('Showing') }} {{ $products->count() }} {{ __('of') }} {{ $products->total() }} {{ __('products') }}
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end gap-3 flex-wrap">
                    <!-- View Toggle -->
                    <div class="view-toggle">
                        <button class="btn btn-outline-secondary btn-sm view-btn active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm view-btn" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                    
                    <!-- Sort Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-2"></i>Sort by
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item sort-option" href="#" data-sort="name_asc">Name (A-Z)</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="name_desc">Name (Z-A)</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="price_asc">Price (Low to High)</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="price_desc">Price (High to Low)</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="newest">Newest First</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="rating">Highest Rated</a></li>
                        </ul>
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
                        'showBrand' => false,
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
                <h4 class="text-muted mb-3">No products found for {{ $brand->name }}</h4>
                <p class="text-muted mb-4">Check back later for new arrivals from this brand.</p>
                <a href="{{ route('products') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Browse All Products
                </a>
            </div>
        @endif
    </div>
        </div>
    </div>
</div>

<style>
.brand-header {
    padding: 40px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    margin-bottom: 40px;
}

.brand-logo img {
    max-height: 120px;
    max-width: 300px;
    object-fit: contain;
}

.brand-title {
    font-size: 2.5rem;
    font-weight: 600;
    color: #333;
}

.brand-description {
    max-width: 600px;
    margin: 0 auto;
}

.brand-story {
    margin: 50px 0;
}

.story-content {
    border-left: 4px solid var(--primary-color);
}

.story-text {
    line-height: 1.8;
    color: #666;
}

.category-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: var(--primary-color);
}

.category-image {
    height: 180px;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.05);
}

.placeholder-image {
    height: 180px;
    background: #f8f9fa;
}

.category-name {
    color: #333;
    font-weight: 600;
}

.products-toolbar {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
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
    .brand-title {
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
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Price filter
    function applyBrandFilters(extra) {
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);

        ['min_price', 'max_price', 'sort', 'category'].forEach(function (key) {
            if (extra && Object.prototype.hasOwnProperty.call(extra, key)) {
                if (extra[key] === null || extra[key] === '') {
                    params.delete(key);
                } else {
                    params.set(key, extra[key]);
                }
            }
        });

        url.search = params.toString();
        window.location.href = url.toString();
    }

    const applyPriceBtn = document.getElementById('applyPriceFilter');
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', function () {
            const priceWrap = document.querySelector('[data-price-range-slider]');
            const priceParams = window.PriceRangeFilter
                ? window.PriceRangeFilter.getParams(priceWrap)
                : {};
            applyBrandFilters(priceParams);
        });
    }

    const clearBrandFiltersBtn = document.getElementById('clearBrandFilters');
    if (clearBrandFiltersBtn) {
        clearBrandFiltersBtn.addEventListener('click', function () {
            applyBrandFilters({ min_price: null, max_price: null });
        });
    }
    
    // Sort functionality
    const sortOptions = document.querySelectorAll('.sort-option');
    
    sortOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const sortBy = this.dataset.sort;
            
            const url = new URL(window.location);
            url.searchParams.set('sort', sortBy);
            window.location.href = url.toString();
        });
    });
    
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
    
    function addToCart(productId) {
        // Implement your add to cart logic here
        showNotification('Product added to cart!', 'success');
    }
    
    function showNotification(message, type) {
        if (typeof window.showNotification === 'function') {
            return window.showNotification(message, type);
        }
        alert(message);
    }
});
</script>
@endsection