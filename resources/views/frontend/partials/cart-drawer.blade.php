@php
    $currencyIcon = $setting->currency_icon ?? '৳';
@endphp

<div class="offcanvas offcanvas-end cart-drawer"
     tabindex="-1"
     id="cartDrawer"
     aria-labelledby="cartDrawerLabel"
     data-currency="{{ $currencyIcon }}">
    <div class="cart-drawer__header">
        <div class="cart-drawer__title-wrap">
            <h2 class="cart-drawer__title" id="cartDrawerLabel">
                <i class="fas fa-shopping-bag"></i>
                {{ __('Your Cart') }}
            </h2>
            <span class="cart-drawer__count"><span id="cartDrawerCount">0</span> {{ __('items') }}</span>
        </div>
        <button type="button"
                class="cart-drawer__close"
                data-bs-dismiss="offcanvas"
                aria-label="{{ __('Close cart') }}">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="cart-drawer__body">
        <div id="cartDrawerLoading" class="cart-drawer__loading">
            <i class="fas fa-spinner fa-spin"></i>
            <span>{{ __('Loading cart...') }}</span>
        </div>

        <div id="cartDrawerEmpty" class="cart-drawer__empty" hidden>
            <div class="cart-drawer__empty-icon"><i class="fas fa-shopping-basket"></i></div>
            <p>{{ __('Your cart is empty') }}</p>
            <a href="{{ route('products') }}" class="btn btn-outline-primary btn-sm" data-bs-dismiss="offcanvas">
                {{ __('Continue Shopping') }}
            </a>
        </div>

        <div id="cartDrawerItems" class="cart-drawer__items" hidden></div>
    </div>

    <div class="cart-drawer__footer" id="cartDrawerFooter" hidden>
        <div class="cart-drawer__subtotal">
            <span>{{ __('Subtotal') }}</span>
            <strong id="cartDrawerSubtotal">{{ $currencyIcon }}0.00</strong>
        </div>
        <div class="cart-drawer__actions">
            <a href="{{ route('cart') }}" class="btn btn-outline-secondary cart-drawer__btn-view">
                {{ __('View Cart') }}
            </a>
            <a href="{{ route('checkout') }}" class="btn btn-primary btn-checkout cart-drawer__btn-checkout">
                <i class="fas fa-lock"></i> {{ __('Checkout') }}
            </a>
        </div>
    </div>
</div>
