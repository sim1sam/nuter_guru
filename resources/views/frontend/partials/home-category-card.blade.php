@php
    $categoryImage = asset('frontend/images/category-placeholder.jpg');
    if (!empty($category->image)) {
        $categoryImage = str_starts_with($category->image, 'http')
            ? $category->image
            : asset($category->image);
    }
    $productCount = null;
    if (isset($category->products_count)) {
        $productCount = (int) $category->products_count;
    } elseif ($category->relationLoaded('products')) {
        $productCount = $category->products->count();
    }
@endphp

<a href="{{ route('category', $category->slug) }}" class="home-category-card fade-in">
    <div class="home-category-card__media">
        <img src="{{ $categoryImage }}" alt="{{ $category->name }}" class="home-category-card__img" loading="lazy">
    </div>
    <div class="home-category-card__body">
        <h3 class="home-category-card__title">{{ $category->name }}</h3>
        <span class="home-category-card__action">
            @if($productCount !== null)
                {{ $productCount }} {{ $productCount === 1 ? __('product') : __('products') }}
            @else
                {{ __('Shop now') }}
            @endif
        </span>
    </div>
</a>
