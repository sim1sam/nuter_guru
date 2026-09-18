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
            <svg class="mobile-tab__icon" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 10.5L12 3l9 7.5"></path>
                <path d="M5 9.5V21h14V9.5"></path>
            </svg>
            <span>{{ __('Home') }}</span>
        </a>
        <a href="{{ route('products') }}" class="mobile-tab {{ request()->routeIs('products') || request()->routeIs('category') || request()->routeIs('product-detail') ? 'is-active' : '' }}">
            <svg class="mobile-tab__icon" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l1.5-4.5h15L21 9"></path>
                <path d="M4 9h16v11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9z"></path>
                <path d="M9 21v-6h6v6"></path>
            </svg>
            <span>{{ __('Shop') }}</span>
        </a>
        <button type="button" class="mobile-tab" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="{{ __('Open menu') }}">
            <svg class="mobile-tab__icon" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
            </svg>
            <span>{{ __('Menu') }}</span>
        </button>
        <a href="{{ route('cart') }}" class="mobile-tab js-open-cart-drawer {{ request()->routeIs('cart') ? 'is-active' : '' }}">
            <svg class="mobile-tab__icon" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 7h15l-1.5 9h-12z"></path>
                <path d="M6 7L5 3H2"></path>
                <circle cx="9" cy="20" r="1.2" fill="currentColor" stroke="none"></circle>
                <circle cx="17" cy="20" r="1.2" fill="currentColor" stroke="none"></circle>
            </svg>
            <span>{{ __('Cart') }}</span>
            <span class="mobile-tab__badge cart-count d-none">0</span>
        </a>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="mobile-tab {{ \App\Helpers\AccountNavHelper::isAccountPage() || request()->routeIs('login') ? 'is-active' : '' }}">
            <svg class="mobile-tab__icon" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="4"></circle>
                <path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6"></path>
            </svg>
            <span>{{ auth()->check() ? __('Account') : __('Login') }}</span>
        </a>
    </div>
</nav>

<div class="modal fade app-download-sheet" id="appDownloadSheet" tabindex="-1" aria-labelledby="appDownloadSheetLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="app-sheet-handle"></div>
                <div class="app-sheet-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="7" y="2" width="10" height="20" rx="2"></rect>
                        <path d="M11 18h2"></path>
                    </svg>
                </div>
                <h2 class="app-sheet-title" id="appDownloadSheetLabel">{{ __('Get Our Mobile App') }}</h2>
                <p class="app-sheet-desc">{{ __('Shop faster with our app — exclusive deals, easy checkout, and order tracking on the go.') }}</p>

                <a href="{{ $appStoreUrl }}" class="app-store-btn" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-apple"></i>
                    <span class="app-store-btn__text">
                        <small>{{ __('Download on the') }}</small>
                        <strong>App Store</strong>
                    </span>
                </a>

                <a href="{{ $playStoreUrl }}" class="app-store-btn" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-google-play"></i>
                    <span class="app-store-btn__text">
                        <small>{{ __('Get it on') }}</small>
                        <strong>Google Play</strong>
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
