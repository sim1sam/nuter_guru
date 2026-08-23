@extends('frontend.layouts.app')

@section('title', 'Our Story')
@section('meta_description', 'Discover Nuter Guru — premium organic dry fruits, nuts, spices and healthy foods.')
@section('meta_keywords', 'our story, organic food, dry fruits, nuts, Nuter Guru')

@push('styles')
<style>
.our-story-hero {
    background: linear-gradient(135deg, var(--bg-elegant, #f8f9fa) 0%, var(--bg-light, #ffffff) 50%, rgba(var(--primary-rgb), 0.12) 100%);
    padding: 80px 0;
    text-align: center;
}

.our-story-section { padding: 60px 0; }

/* Single large feature image */
.story-image {
    width: 100%;
    height: 420px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}
@media (min-width: 992px) {
    .story-image { height: 540px; }
}

.story-text {
    background: transparent;
    padding: 32px;
    border-radius: 10px;
    border: none;
    box-shadow: none;
}
.story-text p { line-height: 1.6; color: #555; margin-bottom: 0.9rem; text-align: left; word-spacing: normal; }
.story-text h2 { color: var(--primary-color, #d4af37); margin-bottom: 1rem; font-weight: 300; }

@media (max-width: 576px) {
    .story-text p { line-height: 1.55; }
}
</style>
@endpush

@section('content')
<!-- Hero -->
<section class="our-story-hero">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4 mb-3">Our Story</h1>
                {{-- <p class="lead">Honouring heritage with modern craftsmanship</p> --}}
            </div>
        </div>
    </div>
</section>

<!-- Two-column layout: single large image + text -->
<section class="our-story-section">
    <div class="container">
        <div class="row g-4 align-items-start">
            {{-- Image removed per request --}}

            <!-- Text Content -->
            <div class="col-lg-12">
                <div class="story-text">
                    <h2>{{ __('Rooted in Nature, Packed with Care') }}</h2>
                    <p>{{ __('Nuter Guru began with a simple belief: everyday food should be pure, nutritious, and trustworthy. We source premium dry fruits, nuts, spices and organic essentials so your family can enjoy healthier living.') }}</p>
                    <p>{{ __('From farm-fresh selections to hygienic packaging, every product is chosen for quality and taste. Whether you need almonds for breakfast, mixed dry fruits for guests, or authentic spices for cooking — we make it easy to shop with confidence.') }}</p>
                    <p>{{ __('Our mission is more than selling groceries. We want to bring wholesome organic foods to your doorstep with fair prices, fast delivery, and caring support.') }}</p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('home') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-2"></i>Back to Home</a>
            </div>
        </div>
    </div>
</section>
@endsection