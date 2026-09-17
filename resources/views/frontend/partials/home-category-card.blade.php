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
    } elseif (method_exists($category, 'relationLoaded') && $category->relationLoaded('products')) {
        $productCount = $category->products->count();
    } elseif (isset($category->products) && (is_countable($category->products) || $category->products instanceof \Countable)) {
        $productCount = count($category->products);
    }
@endphp

<a href="{{ route('category', $category->slug) }}" class="home-category-card fade-in">
    <div class="home-category-card__media">
        <img src="{{ $categoryImage }}" alt="{{ category_name($category) }}" class="home-category-card__img" loading="lazy">
    </div>
    <div class="home-category-card__body">
        <h3 class="home-category-card__title">{{ category_name($category) }}</h3>
        <span class="home-category-card__action">
            @if($productCount !== null)
                {{ $productCount }} {{ $productCount === 1 ? __('product') : __('products') }}
            @else
                {{ __('Shop now') }}
            @endif
        </span>
    </div>
</a>
