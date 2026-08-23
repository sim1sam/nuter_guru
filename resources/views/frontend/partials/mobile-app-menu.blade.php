<div class="offcanvas offcanvas-bottom mobile-app-menu d-lg-none"
     tabindex="-1"
     id="mobileMenu"
     aria-labelledby="mobileMenuLabel"
     data-bs-backdrop="true"
     data-bs-scroll="false">
    <div class="mobile-app-menu__handle" aria-hidden="true"></div>
    <div class="offcanvas-header mobile-app-menu__header">
        <div>
            <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
            <p class="mobile-app-menu__subtitle">Browse the store</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mobile-app-menu__body">
        <form action="{{ route('products') }}" method="GET" class="mobile-app-menu__search">
            <i class="fas fa-search"></i>
            <input type="text"
                   name="search"
                   class="js-product-search"
                   placeholder="Search products..."
                   value="{{ request('search') }}"
                   autocomplete="off"
                   aria-autocomplete="list">
            <button type="submit" class="visually-hidden">Search</button>
            <div class="search-suggestions js-search-suggestions search-suggestions--menu" hidden></div>
        </form>

        <div class="mobile-app-menu__grid">
            <a href="{{ route('home') }}" class="mobile-app-menu__tile {{ request()->routeIs('home') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-home"></i></span>
                <span>Home</span>
            </a>
            <a href="{{ route('products') }}" class="mobile-app-menu__tile {{ request()->routeIs('products') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-store"></i></span>
                <span>Shop</span>
            </a>
            <a href="{{ route('cart') }}" class="mobile-app-menu__tile js-open-cart-drawer {{ request()->routeIs('cart') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-shopping-bag"></i></span>
                <span>Cart</span>
            </a>
            @auth
            <a href="{{ route('dashboard') }}" class="mobile-app-menu__tile {{ \App\Helpers\AccountNavHelper::isDashboard() ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-user"></i></span>
                <span>Account</span>
            </a>
            @else
            <a href="{{ route('login') }}" class="mobile-app-menu__tile">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-user"></i></span>
                <span>Login</span>
            </a>
            @endauth
            <a href="{{ route('our-story') }}" class="mobile-app-menu__tile {{ request()->routeIs('our-story') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-book-open"></i></span>
                <span>Story</span>
            </a>
            @auth
            <a href="{{ route('orders') }}" class="mobile-app-menu__tile {{ request()->routeIs('orders', 'orders.show', 'user.orders', 'user.orders.show') ? 'is-active' : '' }}">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-box"></i></span>
                <span>Orders</span>
            </a>
            @else
            <a href="{{ route('register') }}" class="mobile-app-menu__tile">
                <span class="mobile-app-menu__tile-icon"><i class="fas fa-user-plus"></i></span>
                <span>Register</span>
            </a>
            @endauth
        </div>

        <div class="mobile-app-menu__section">
            <button class="mobile-app-menu__section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileCategories" aria-expanded="false" aria-controls="mobileCategories">
                <span><i class="fas fa-th-large me-2"></i>Categories</span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="collapse" id="mobileCategories">
                <div class="mobile-app-menu__category-list">
                    @foreach($categories as $category)
                    <a href="{{ route('category', $category->slug) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        @auth
        <div class="mobile-app-menu__links">
            <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile', 'user.profile') ? 'is-active' : '' }}"><i class="fas fa-id-card me-2"></i>Profile</a>
            <a href="{{ route('wishlist') }}" class="{{ request()->routeIs('wishlist', 'user.wishlist') ? 'is-active' : '' }}"><i class="fas fa-heart me-2"></i>Wishlist</a>
            <a href="{{ route('addresses.index') }}" class="{{ request()->routeIs('addresses.*') ? 'is-active' : '' }}"><i class="fas fa-map-marker-alt me-2"></i>Addresses</a>
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
