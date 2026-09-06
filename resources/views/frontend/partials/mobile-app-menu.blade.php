<div class="offcanvas offcanvas-bottom mobile-app-menu d-lg-none"
     tabindex="-1"
     id="mobileMenu"
     aria-labelledby="mobileMenuLabel"
     data-bs-backdrop="true"
     data-bs-scroll="false">
    <div class="mobile-app-menu__handle" aria-hidden="true"></div>
    <div class="offcanvas-header mobile-app-menu__header">
        <div>
            <h5 class="offcanvas-title" id="mobileMenuLabel">{{ __('Menu') }}</h5>
            <p class="mobile-app-menu__subtitle">{{ __('Browse the store') }}</p>
        </div>
        <button type="button" class="btn-close" data-dismiss="offcanvas" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}"></button>
    </div>
    <div class="offcanvas-body mobile-app-menu__body">
        <form action="{{ route('products') }}" method="GET" class="mobile-app-menu__search">
            <i class="fas fa-search"></i>
            <input type="text"
                   name="search"
                   class="js-product-search"
                   placeholder="{{ __('Search products...') }}"
                   value="{{ request('search') }}"
                   autocomplete="off"
                   aria-autocomplete="list">
            <button type="submit" class="visually-hidden">{{ __('Search') }}</button>
            <div class="search-suggestions js-search-suggestions search-suggestions--menu" hidden></div>
        </form>

        <div class="mobile-app-menu__grid">
            <a href="{{ route('home') }}" class="mobile-app-menu__tile {{ request()->routeIs('home') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-home"></i></span>
                <span>{{ __('Home') }}</span>
            </a>
            <a href="{{ route('products') }}" class="mobile-app-menu__tile {{ request()->routeIs('products') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-store"></i></span>
                <span>{{ __('Shop') }}</span>
            </a>
            <a href="{{ route('cart') }}" class="mobile-app-menu__tile js-open-cart-drawer {{ request()->routeIs('cart') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-shopping-bag"></i></span>
                <span>{{ __('Cart') }}</span>
            </a>
            @auth
            <a href="{{ route('dashboard') }}" class="mobile-app-menu__tile {{ \App\Helpers\AccountNavHelper::isDashboard() ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-user"></i></span>
                <span>{{ __('Account') }}</span>
            </a>
            @else
            <a href="{{ route('login') }}" class="mobile-app-menu__tile">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-user"></i></span>
                <span>{{ __('Login') }}</span>
            </a>
            @endauth
            <a href="{{ route('our-story') }}" class="mobile-app-menu__tile {{ request()->routeIs('our-story') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-book-open"></i></span>
                <span>{{ __('Story') }}</span>
            </a>
            @auth
            <a href="{{ route('orders') }}" class="mobile-app-menu__tile {{ request()->routeIs('orders', 'orders.show', 'user.orders', 'user.orders.show') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-box"></i></span>
                <span>{{ __('Orders') }}</span>
            </a>
            @else
            <a href="{{ route('register') }}" class="mobile-app-menu__tile">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-user-plus"></i></span>
                <span>{{ __('Register') }}</span>
            </a>
            @endauth
        </div>

        <div class="mobile-app-menu__section">
            @php $currentLocale = app()->getLocale(); @endphp
            <div class="organic-lang organic-lang--mobile" role="group" aria-label="{{ __('Language') }}">
                <a href="{{ route('locale.switch', 'bn') }}"
                   class="organic-lang__btn {{ $currentLocale === 'bn' ? 'is-active' : '' }}">বাংলা</a>
                <a href="{{ route('locale.switch', 'en') }}"
                   class="organic-lang__btn {{ $currentLocale === 'en' ? 'is-active' : '' }}">EN</a>
            </div>
        </div>

        <div class="mobile-app-menu__section">
            <button class="mobile-app-menu__section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileCategories" aria-expanded="false" aria-controls="mobileCategories">
                <span><i class="fas fa-th-large me-2"></i>{{ __('Categories') }}</span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="collapse" id="mobileCategories">
                <div class="mobile-app-menu__category-list">
                    @foreach($categories as $category)
                    <a href="{{ route('category', $category->slug) }}">{{ category_name($category) }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        @auth
        <div class="mobile-app-menu__links">
            <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile', 'user.profile') ? 'is-active' : '' }}"><i class="fas fa-id-card me-2"></i>{{ __('Profile') }}</a>
            <a href="{{ route('wishlist') }}" class="{{ request()->routeIs('wishlist', 'user.wishlist') ? 'is-active' : '' }}"><i class="fas fa-heart me-2"></i>{{ __('Wishlist') }}</a>
            <a href="{{ route('addresses.index') }}" class="{{ request()->routeIs('addresses.*') ? 'is-active' : '' }}"><i class="fas fa-map-marker-alt me-2"></i>{{ __('Addresses') }}</a>
        </div>
        @endauth
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const offcanvasEl = document.getElementById('mobileMenu');
    const toggleBtns = document.querySelectorAll('[data-bs-target="#mobileMenu"]');
    if (!offcanvasEl) {
        return;
    }

    offcanvasEl.addEventListener('show.bs.offcanvas', function () {
        document.body.classList.add('mobile-menu-open');
    });

    offcanvasEl.addEventListener('shown.bs.offcanvas', function () {
        toggleBtns.forEach(function (toggleBtn) {
            toggleBtn.setAttribute('aria-expanded', 'true');
            const hamburger = toggleBtn.querySelector('.navbar-toggler-icon');
            const closeIcon = toggleBtn.querySelector('.close-icon');
            if (hamburger) hamburger.classList.add('d-none');
            if (closeIcon) closeIcon.classList.remove('d-none');
        });
    });

    offcanvasEl.addEventListener('hidden.bs.offcanvas', function () {
        document.body.classList.remove('mobile-menu-open');
        toggleBtns.forEach(function (toggleBtn) {
            toggleBtn.setAttribute('aria-expanded', 'false');
            const hamburger = toggleBtn.querySelector('.navbar-toggler-icon');
            const closeIcon = toggleBtn.querySelector('.close-icon');
            if (hamburger) hamburger.classList.remove('d-none');
            if (closeIcon) closeIcon.classList.add('d-none');
        });
    });
});
</script>
