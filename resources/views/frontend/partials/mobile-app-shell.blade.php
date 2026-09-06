@php
    $appBanner = App\Models\BannerImage::find(24);
    $playStoreUrl = ($appBanner && !empty($appBanner->link) && $appBanner->link !== '#')
        ? $appBanner->link
        : 'https://play.google.com/store';
    $appStoreUrl = ($appBanner && !empty($appBanner->title) && $appBanner->title !== '#')
        ? $appBanner->title
        : 'https://apps.apple.com';
    $appVersion = $setting->app_version ?? 'Mobile App';
@endphp

<nav class="mobile-bottom-nav d-lg-none" aria-label="{{ __('Toggle navigation') }}">
    <div class="mobile-bottom-nav__inner">
        <a href="{{ route('home') }}" class="mobile-tab {{ request()->routeIs('home') ? 'is-active' : '' }}">
            <i class="fas fa-home"></i>
            <span>{{ __('Home') }}</span>
        </a>
        <a href="{{ route('products') }}" class="mobile-tab {{ request()->routeIs('products') || request()->routeIs('category') || request()->routeIs('product-detail') ? 'is-active' : '' }}">
            <i class="fas fa-store"></i>
            <span>{{ __('Shop') }}</span>
        </a>
        <button type="button" class="mobile-tab" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="{{ __('Open menu') }}">
            <i class="fas fa-th-large"></i>
            <span>{{ __('Menu') }}</span>
        </button>
        <a href="{{ route('cart') }}" class="mobile-tab js-open-cart-drawer {{ request()->routeIs('cart') ? 'is-active' : '' }}">
            <i class="fas fa-shopping-bag"></i>
            <span>{{ __('Cart') }}</span>
            <span class="mobile-tab__badge cart-count d-none">0</span>
        </a>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="mobile-tab {{ \App\Helpers\AccountNavHelper::isAccountPage() || request()->routeIs('login') ? 'is-active' : '' }}">
            <i class="fas fa-user"></i>
            <span>{{ auth()->check() ? __('Account') : __('Login') }}</span>
        </a>
    </div>
</nav>

<div class="modal fade app-download-sheet" id="appDownloadSheet" tabindex="-1" aria-labelledby="appDownloadSheetLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="app-sheet-handle"></div>
                <div class="app-sheet-icon"><i class="fas fa-mobile-alt"></i></div>
                <h2 class="app-sheet-title" id="appDownloadSheetLabel">{{ __('Get Our Mobile App') }}</h2>
                <p class="app-sheet-desc">{{ __('Shop faster with our app — exclusive deals, easy checkout, and order tracking on the go.') }}</p>

                <a href="{{ $appStoreUrl }}" class="app-store-btn" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-apple"></i>
                    <span class="app-store-btn__text">
                        <small>{{ __('Download on the') }}</small>
                        <strong>{{ __('App Store') }}</strong>
                    </span>
                </a>
                <a href="{{ $playStoreUrl }}" class="app-store-btn" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-google-play"></i>
                    <span class="app-store-btn__text">
                        <small>{{ __('Get it on') }}</small>
                        <strong>{{ __('Google Play') }}</strong>
                    </span>
                </a>

                <p class="app-sheet-hint">
                    <i class="fas fa-share-square me-1"></i>
                    {{ __('Tip: Add this site to your home screen for an app-like experience.') }}
                    @if($appVersion)
                        <br><span class="text-muted">{{ $appVersion }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.matchMedia('(max-width: 991.98px)').matches) {
        document.body.classList.add('mobile-app-mode');
    }

    const appSheet = document.getElementById('appDownloadSheet');
    if (appSheet) {
        appSheet.addEventListener('show.bs.modal', function () {
            document.body.classList.add('app-sheet-open');
        });
        appSheet.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('app-sheet-open');
        });
    }

    document.querySelectorAll('[data-open-app-sheet]').forEach(function (el) {
        el.addEventListener('click', function () {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('appDownloadSheet'));
            modal.show();
        });
    });
});
</script>
