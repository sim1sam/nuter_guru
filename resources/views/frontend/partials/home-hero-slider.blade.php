@php
    $heroSliders = $sliders ?? collect();
@endphp

<section class="home-hero">
    @if($heroSliders->count() > 0)
        <div id="homeHeroCarousel" class="carousel slide home-hero__carousel" data-bs-ride="carousel" data-bs-interval="6000">
            @if($heroSliders->count() > 1)
                <div class="carousel-indicators home-hero__indicators">
                    @foreach($heroSliders as $index => $slider)
                        <button type="button"
                                data-bs-target="#homeHeroCarousel"
                                data-bs-slide-to="{{ $index }}"
                                class="{{ $index === 0 ? 'active' : '' }}"
                                aria-label="{{ __('Go to slide') }} {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif

            <div class="carousel-inner">
                @foreach($heroSliders as $index => $slider)
                    @php
                        $shopUrl = route('products');
                        if (!empty($slider->product_slug)) {
                            $shopUrl = route('product-detail', ['slug' => $slider->product_slug]);
                        } elseif (!empty($slider->link)) {
                            $shopUrl = url($slider->link);
                        }
                        $alignRight = ($slider->text_position ?? 'left') === 'right';
                    @endphp
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="home-hero__slide {{ $alignRight ? 'home-hero__slide--align-right' : '' }}">
                            <img src="{{ asset($slider->image) }}"
                                 alt="{{ $slider->title_one ?? __('Hero slide') }}"
                                 class="home-hero__img"
                                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            <div class="home-hero__overlay"></div>
                            <div class="container home-hero__container">
                                <div class="home-hero__content {{ $alignRight ? 'home-hero__content--right' : '' }}">
                                    @if($slider->title_one)
                                        <span class="home-hero__eyebrow">{{ __('Featured') }}</span>
                                    @endif
                                    <h1 class="home-hero__title">{{ $slider->title_one ?? config('app.name', 'Nuter Guru') }}</h1>
                                    <p class="home-hero__desc">{{ $slider->title_two ?? __('Discover our exquisite collection of handcrafted jewellery.') }}</p>
                                    <div class="home-hero__actions">
                                        <a href="{{ $shopUrl }}" class="home-hero__btn home-hero__btn--primary">{{ __('Shop Now') }}</a>
                                        <a href="{{ route('our-story') }}" class="home-hero__btn home-hero__btn--ghost">{{ __('Learn More') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($heroSliders->count() > 1)
                <button class="carousel-control-prev home-hero__nav" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="prev" aria-label="{{ __('Previous slide') }}">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next home-hero__nav" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="next" aria-label="{{ __('Next slide') }}">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            @endif
        </div>
    @else
        <div class="home-hero__slide home-hero__slide--static">
            <div class="home-hero__img home-hero__img--fallback" aria-hidden="true"></div>
            <div class="home-hero__overlay"></div>
            <div class="container home-hero__container">
                <div class="home-hero__content">
                    <span class="home-hero__eyebrow">{{ __('New Collection') }}</span>
                    <h1 class="home-hero__title">{{ config('app.name', 'Nuter Guru') }}</h1>
                    <p class="home-hero__desc">{{ __('Discover our exquisite collection of handcrafted diamond jewellery.') }}</p>
                    <div class="home-hero__actions">
                        <a href="{{ route('products') }}" class="home-hero__btn home-hero__btn--primary">{{ __('Shop Now') }}</a>
                        <a href="{{ route('our-story') }}" class="home-hero__btn home-hero__btn--ghost">{{ __('Learn More') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
