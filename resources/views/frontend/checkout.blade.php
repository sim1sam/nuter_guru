@extends('frontend.layouts.app')

@section('title', __('Checkout'))

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            @if ($errors->any())
                {{-- validation toasts are shown from layout --}}
            @endif
            <form id="checkout-form" action="{{ route('checkout.place-order') }}" method="POST" novalidate>
                @csrf
                
                <!-- Billing Information -->
                @php
                    $hasSavedAddresses = auth()->check() && isset($addresses) && $addresses->count() > 0;
                    $defaultIdx = $defaultAddressIndex ?? 0;
                    $bdCountryId = $bangladeshCountryId ?? '';
                    $defaultAddress = $hasSavedAddresses ? ($addresses[$defaultIdx] ?? $addresses->first()) : null;
                    $defaultNameParts = $defaultAddress ? preg_split('/\s+/', trim($defaultAddress->name ?? ''), 2) : ['', ''];
                @endphp
                <div class="card checkout-section-card mb-4">
                    <div class="card-header checkout-section-header">
                        <div>
                            <h5 class="mb-0">{{ __('Billing Address') }}</h5>
                            <small class="text-muted">{{ __('Select a delivery address or add a new one') }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        @auth
                        @if($hasSavedAddresses)
                        <select class="d-none" id="saved_address" name="saved_address" aria-hidden="true">
                            @foreach($addresses as $index => $address)
                                <option value="{{ $index }}"
                                        {{ (string)$index === (string)$defaultIdx ? 'selected' : '' }}
                                        data-name="{{ $address->name ?? '' }}"
                                        data-email="{{ $address->email ?? '' }}"
                                        data-phone="{{ $address->phone ?? '' }}"
                                        data-address="{{ $address->address ?? '' }}"
                                        data-country="{{ $address->country_id ?? $bdCountryId }}"
                                        data-delivery-area="{{ $address->delivery_area ?? 'inside' }}"
                                        data-default-billing="{{ $address->default_billing ? '1' : '0' }}"
                                        data-default-shipping="{{ $address->default_shipping ? '1' : '0' }}">
                                </option>
                            @endforeach
                        </select>

                        <div class="checkout-address-grid" id="checkout-address-grid">
                            @foreach($addresses as $index => $address)
                                @php
                                    $isSelected = (string)$index === (string)$defaultIdx;
                                    $areaLabel = ($address->delivery_area ?? 'inside') === 'outside' ? __('Outside') : __('Inside');
                                @endphp
                                <button type="button"
                                        class="checkout-address-card {{ $isSelected ? 'is-selected' : '' }}"
                                        data-address-index="{{ $index }}"
                                        aria-pressed="{{ $isSelected ? 'true' : 'false' }}">
                                    <div class="checkout-address-card__top">
                                        <span class="checkout-address-card__icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <span class="checkout-address-card__check">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </div>
                                    <div class="checkout-address-card__body">
                                        <div class="checkout-address-card__name">{{ $address->name ?? __('Address') }}</div>
                                        <div class="checkout-address-card__badges">
                                            @if($address->default_billing)
                                                <span class="checkout-chip checkout-chip--primary">{{ __('Default') }}</span>
                                            @endif
                                            <span class="checkout-chip">{{ $areaLabel }}</span>
                                        </div>
                                        <p class="checkout-address-card__text">{{ $address->address }}</p>
                                        <div class="checkout-address-card__meta">
                                            @if($address->phone)
                                                <span><i class="fas fa-phone-alt"></i> {{ $address->phone }}</span>
                                            @endif
                                            <span><i class="fas fa-flag"></i> {{ __('Bangladesh') }}</span>
                                        </div>
                                    </div>
                                </button>
                            @endforeach

                            <button type="button" class="checkout-address-card checkout-address-card--add" id="btn-add-new-address">
                                <div class="checkout-address-card__add-inner">
                                    <span class="checkout-address-card__add-icon"><i class="fas fa-plus"></i></span>
                                    <strong>{{ __('Add new address') }}</strong>
                                    <small>{{ __('Use a different delivery location') }}</small>
                                </div>
                            </button>
                        </div>
                        @endif
                        @endauth

                        <div id="billing-form-fields" class="checkout-address-form @if($hasSavedAddresses) d-none @endif">
                            <div class="checkout-form-title">
                                <h6 class="mb-1">{{ $hasSavedAddresses ? __('New address details') : __('Enter billing address') }}</h6>
                                <small class="text-muted">{{ __('Country is fixed to Bangladesh') }}</small>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">{{ __('First Name') }} *</label>
                                    <input type="text" class="form-control" id="first_name" name="billing_first_name" value="{{ old('billing_first_name', $defaultNameParts[0] ?? '') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">{{ __('Last Name') }} *</label>
                                    <input type="text" class="form-control" id="last_name" name="billing_last_name" value="{{ old('billing_last_name', $defaultNameParts[1] ?? '') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }} *</label>
                                    <input type="email" class="form-control" id="email" name="billing_email" value="{{ old('billing_email', $defaultAddress->email ?? (auth()->user()->email ?? '')) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone Number') }} *</label>
                                    <input type="tel" class="form-control" id="phone" name="billing_phone" value="{{ old('billing_phone', $defaultAddress->phone ?? '') }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('Full Address') }} *</label>
                                <textarea class="form-control" id="address" name="billing_address" rows="3" placeholder="{{ __('House, road, area, landmark') }}" required>{{ old('billing_address', $defaultAddress->address ?? '') }}</textarea>
                            </div>
                            <div class="row align-items-end">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Country') }}</label>
                                    <div class="checkout-readonly-field">
                                        <i class="fas fa-globe-asia me-2"></i> {{ __('Bangladesh') }}
                                    </div>
                                    <input type="hidden" id="country" name="billing_country" value="{{ $bdCountryId }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-block">{{ __('Delivery Area') }} *</label>
                                    @php $billingArea = old('billing_delivery_area', $defaultAddress->delivery_area ?? 'inside'); @endphp
                                    <div class="checkout-area-toggle">
                                        <input type="radio" class="btn-check" name="billing_delivery_area" id="billing_area_inside" value="inside" {{ $billingArea === 'inside' ? 'checked' : '' }}>
                                        <label class="checkout-area-btn" for="billing_area_inside">{{ __('Inside') }}</label>
                                        <input type="radio" class="btn-check" name="billing_delivery_area" id="billing_area_outside" value="outside" {{ $billingArea === 'outside' ? 'checked' : '' }}>
                                        <label class="checkout-area-btn" for="billing_area_outside">{{ __('Outside') }}</label>
                                    </div>
                                </div>
                            </div>
                            @if($hasSavedAddresses)
                            <div class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-cancel-new-address">{{ __('Cancel') }}</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="card checkout-section-card mb-4">
                    <div class="card-header checkout-section-header">
                        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">
                            <div>
                                <h5 class="mb-0">{{ __('Shipping Information') }}</h5>
                                <small class="text-muted">{{ __('Deliver to a different address if needed') }}</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="same-as-billing" checked>
                                <label class="form-check-label" for="same-as-billing">
                                    {{ __('Same as billing') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="shipping-form" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ship_first_name" class="form-label">{{ __('First Name') }} *</label>
                                <input type="text" class="form-control" id="ship_first_name" name="shipping_first_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ship_last_name" class="form-label">{{ __('Last Name') }} *</label>
                                <input type="text" class="form-control" id="ship_last_name" name="shipping_last_name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ship_email" class="form-label">{{ __('Email Address') }} *</label>
                                <input type="email" class="form-control" id="ship_email" name="shipping_email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ship_phone" class="form-label">{{ __('Phone Number') }} *</label>
                                <input type="tel" class="form-control" id="ship_phone" name="shipping_phone">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ship_address" class="form-label">{{ __('Full Address') }} *</label>
                            <textarea class="form-control" id="ship_address" name="shipping_address" rows="3" placeholder="{{ __('House, road, area, landmark') }}"></textarea>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Country') }}</label>
                                <div class="checkout-readonly-field">
                                    <i class="fas fa-globe-asia me-2"></i> {{ __('Bangladesh') }}
                                </div>
                                <input type="hidden" id="ship_country" name="shipping_country" value="{{ $bdCountryId }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">{{ __('Delivery Area') }} *</label>
                                <div class="checkout-area-toggle">
                                    <input type="radio" class="btn-check" name="shipping_delivery_area" id="ship_area_inside" value="inside" checked>
                                    <label class="checkout-area-btn" for="ship_area_inside">{{ __('Inside') }}</label>
                                    <input type="radio" class="btn-check" name="shipping_delivery_area" id="ship_area_outside" value="outside">
                                    <label class="checkout-area-btn" for="ship_area_outside">{{ __('Outside') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Shipping Method') }}</h5>
                    </div>
                    <div class="card-body shipping-methods">
                        @if($shippingMethods && $shippingMethods->count() > 0)
                            @foreach($shippingMethods as $index => $shipping)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="shipping_method" id="shipping_{{ $shipping->id }}" 
                                       value="{{ $shipping->id }}" {{ $index == 0 ? 'checked' : '' }}
                                       data-cost="{{ $shipping->shipping_fee }}">
                                <label class="form-check-label w-100" for="shipping_{{ $shipping->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $shipping->shipping_rule }}</strong>
                                            <small class="d-block text-muted">{{ $shipping->shipping_fee > 0 ? __('Delivery time: 3-5 business days') : __('Free shipping') }}</small>
                                        </div>
                                        <div class="text-end">
                                            <strong class="text-success">
                                                @if($shipping->shipping_fee > 0)
                                                    {{ format_currency($shipping->shipping_fee) }}
                                                @else
                                                    {{ __('Free') }}
                                                @endif
                                            </strong>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ __('No shipping methods available. Please contact support.') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Payment Method') }}</h5>
                    </div>
                    <div class="card-body">
                        <div id="payment-methods-container">
                            {{-- Cash on Delivery --}}
                            @if($bank_payment_setting && $bank_payment_setting->cash_on_delivery_status == 1)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery" checked>
                                <label class="form-check-label w-100" for="cash_on_delivery">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-money-bill-wave me-3 text-success" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>{{ __('Cash on Delivery') }}</strong>
                                            <small class="d-block text-muted">{{ __('Pay when you receive your order') }}</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endif

                            {{-- Stripe --}}
                            @if(isset($stripe_setting) && $stripe_setting->status == 1)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe">
                                <label class="form-check-label w-100" for="stripe">
                                    <div class="d-flex align-items-center">
                                        <i class="fab fa-stripe me-3 text-primary" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>{{ __('Credit/Debit Card (Stripe)') }}</strong>
                                            <small class="d-block text-muted">{{ __('Pay securely with your credit or debit card') }}</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endif

                            {{-- PayPal --}}
                            @if(isset($paypal_setting) && $paypal_setting->status == 1)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                <label class="form-check-label w-100" for="paypal">
                                    <div class="d-flex align-items-center">
                                        <i class="fab fa-paypal me-3 text-primary" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>PayPal</strong>
                                            <small class="d-block text-muted">{{ __('Pay with your PayPal account') }}</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endif

                            {{-- Bank Payment --}}
                            @if(isset($bank_payment_setting) && $bank_payment_setting->status == 1)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank_payment" value="bank_payment">
                                <label class="form-check-label w-100" for="bank_payment">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-university me-3 text-secondary" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>{{ __('Bank Transfer') }}</strong>
                                            <small class="d-block text-muted">{{ __('Transfer directly to our bank account') }}</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endif

                            {{-- SSLCommerz --}}
                            @if(isset($sslcommerz_setting) && $sslcommerz_setting->status == 1)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="sslcommerz" value="sslcommerz">
                                <label class="form-check-label w-100" for="sslcommerz">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-credit-card me-3 text-primary" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>SSLCommerz</strong>
                                            <small class="d-block text-muted">{{ __('Pay with cards, mobile banking & internet banking') }}</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endif
                        </div>
                        

                        
                        <!-- Bank Payment Info (hidden by default) -->
                        <div id="bank-payment-info" style="display: none;">
                            <div class="alert alert-info">
                                <h6>{{ __('Bank Transfer Details:') }}</h6>
                                <div id="bank-account-details">
                                    <!-- Bank account information will be loaded dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Order Notes (Optional)') }}</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="order_notes" name="order_notes" rows="3" placeholder="{{ __('Notes about your order, e.g. special notes for delivery.') }}"></textarea>
                    </div>
                </div>
                
                <!-- Hidden field for same_as_billing -->
                <input type="hidden" name="same_as_billing" id="same_as_billing_hidden" value="1">
            </form>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Order Summary') }}</h5>
                </div>
                <div class="card-body">
                    <div id="order-items">
                        <!-- Order items will be loaded here -->
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Subtotal:') }}</span>
                        <span id="subtotal">{{ currency_icon() }}0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Shipping:') }}</span>
                        <span id="shipping-cost">{{ currency_icon() }}0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Tax:') }}</span>
                        <span id="tax">{{ currency_icon() }}0.00</span>
                    </div>
                    
                    <!-- Coupon Section -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="coupon-code" placeholder="{{ __('Enter coupon code') }}">
                            <button class="btn btn-outline-secondary" type="button" id="apply-coupon-btn">
                                {{ __('Apply Coupon') }}
                            </button>
                        </div>
                        <div id="coupon-info" class="mt-2" style="display: none;">
                            <div class="alert alert-success py-2 mb-0">
                                <small id="coupon-info-text"></small>
                                <button type="button" class="btn-close btn-sm float-end" id="remove-coupon" aria-label="{{ __('Remove coupon') }}"></button>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-2" id="coupon-discount" style="display: none;">
                        <span>{{ __('Coupon Discount:') }}</span>
                        <span id="coupon-discount-amount" class="text-success">-{{ currency_icon() }}0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>{{ __('Total:') }}</strong>
                        <strong id="total">{{ currency_icon() }}0.00</strong>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="place-order-btn" form="checkout-form">
                            <i class="fas fa-lock me-2"></i>{{ __('Place Order') }}
                        </button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            {{ __('Your payment information is secure and encrypted') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Security Badges -->
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="mb-3">{{ __('Secure Checkout') }}</h6>
                    <div class="d-flex justify-content-center gap-3">
                        <i class="fab fa-cc-visa fa-2x text-muted"></i>
                        <i class="fab fa-cc-mastercard fa-2x text-muted"></i>
                        <i class="fab fa-cc-amex fa-2x text-muted"></i>
                        <i class="fab fa-paypal fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-section-card {
    border: 1px solid #ebe7f2;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(74, 74, 92, 0.06);
}

.checkout-section-header {
    background: linear-gradient(180deg, #fbfafc 0%, #f5f3f8 100%);
    border-bottom: 1px solid #ebe7f2;
    padding: 1rem 1.25rem;
}

.checkout-section-header h5 {
    color: var(--text-dark, #4A4A5C);
    font-weight: 650;
}

.checkout-address-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 14px;
    margin-bottom: 0.5rem;
}

.checkout-address-card {
    position: relative;
    text-align: left;
    background: #fff;
    border: 1.5px solid #e8e4ef;
    border-radius: 12px;
    padding: 16px;
    transition: border-color .2s ease, box-shadow .2s ease, transform .15s ease;
    cursor: pointer;
    min-height: 180px;
    color: inherit;
}

.checkout-address-card:hover {
    border-color: var(--accent-color, #A594C4);
    box-shadow: 0 10px 22px rgba(107, 78, 157, 0.08);
    transform: translateY(-1px);
}

.checkout-address-card.is-selected {
    border-color: var(--primary-color, #8B7BA8);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.15);
    background: linear-gradient(180deg, #ffffff 0%, #f8f6fb 100%);
}

.checkout-address-card__top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.checkout-address-card__icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--primary-rgb), 0.12);
    color: var(--primary-color, #8B7BA8);
}

.checkout-address-card__check {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 1.5px solid #d9d4e3;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    font-size: 11px;
    transition: all .2s ease;
}

.checkout-address-card.is-selected .checkout-address-card__check {
    background: var(--primary-color, #8B7BA8);
    border-color: var(--primary-color, #8B7BA8);
    color: #fff;
}

.checkout-address-card__name {
    font-weight: 650;
    font-size: 1rem;
    color: var(--text-dark, #4A4A5C);
    margin-bottom: 8px;
}

.checkout-address-card__badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 10px;
}

.checkout-chip {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    background: #f1eef6;
    color: #6b6580;
}

.checkout-chip--primary {
    background: rgba(var(--primary-rgb), 0.16);
    color: var(--primary-color, #8B7BA8);
}

.checkout-address-card__text {
    font-size: 0.9rem;
    color: #5f5a70;
    margin: 0 0 12px;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3.9em;
}

.checkout-address-card__meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 0.8rem;
    color: #7a748c;
}

.checkout-address-card__meta i {
    width: 14px;
    color: var(--primary-color, #8B7BA8);
}

.checkout-address-card--add {
    border-style: dashed;
    background: #fcfbfd;
    display: flex;
    align-items: center;
    justify-content: center;
}

.checkout-address-card--add.is-selected,
.checkout-address-card--add.is-active {
    border-style: solid;
    border-color: var(--primary-color, #8B7BA8);
    background: #f8f6fb;
}

.checkout-address-card__add-inner {
    text-align: center;
}

.checkout-address-card__add-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    background: rgba(var(--primary-rgb), 0.12);
    color: var(--primary-color, #8B7BA8);
    font-size: 1rem;
}

.checkout-address-card__add-inner strong {
    display: block;
    color: var(--text-dark, #4A4A5C);
    margin-bottom: 4px;
}

.checkout-address-card__add-inner small {
    color: #8a8499;
}

.checkout-address-form {
    margin-top: 1.25rem;
    padding: 1.1rem 1.15rem;
    border: 1px solid #ebe7f2;
    border-radius: 12px;
    background: #faf9fc;
}

.checkout-form-title {
    margin-bottom: 1rem;
}

.checkout-readonly-field {
    display: flex;
    align-items: center;
    min-height: 38px;
    padding: 0.45rem 0.75rem;
    border: 1px solid #e4e0ea;
    border-radius: 0.375rem;
    background: #fff;
    color: #5f5a70;
}

.checkout-area-toggle {
    display: inline-flex;
    gap: 8px;
    width: 100%;
}

.checkout-area-btn {
    flex: 1;
    text-align: center;
    border: 1.5px solid #e4e0ea;
    border-radius: 8px;
    padding: 0.45rem 0.75rem;
    cursor: pointer;
    background: #fff;
    color: #5f5a70;
    font-weight: 600;
    font-size: 0.9rem;
    margin: 0;
    transition: all .15s ease;
}

.btn-check:checked + .checkout-area-btn {
    border-color: var(--primary-color, #8B7BA8);
    background: rgba(var(--primary-rgb), 0.12);
    color: var(--primary-color, #8B7BA8);
}

.order-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.order-item-info {
    flex: 1;
}

.order-item-name {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 2px;
}

.order-item-details {
    font-size: 12px;
    color: #666;
}

.order-item-price {
    font-weight: 600;
    color: var(--primary-color, #8B7BA8);
}

.form-check-label {
    width: auto;
}

.payment-icons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.payment-icons i {
    font-size: 24px;
    color: #666;
}

@media (max-width: 575.98px) {
    .checkout-address-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
const BD_COUNTRY_ID = @json($bangladeshCountryId ?? null);
const STORE_CURRENCY = @json(currency_icon());

function setDeliveryArea(name, value) {
    const target = value === 'outside' ? 'outside' : 'inside';
    document.querySelectorAll('input[name="' + name + '"]').forEach(function (radio) {
        radio.checked = radio.value === target;
    });
}

function setSelectedAddressCard(index) {
    document.querySelectorAll('.checkout-address-card[data-address-index]').forEach(function (card) {
        const selected = String(card.dataset.addressIndex) === String(index);
        card.classList.toggle('is-selected', selected);
        card.setAttribute('aria-pressed', selected ? 'true' : 'false');
    });
    const addCard = document.getElementById('btn-add-new-address');
    if (addCard) {
        addCard.classList.toggle('is-active', index === 'new');
    }
}

function showBillingFormFields(show) {
    const wrap = document.getElementById('billing-form-fields');
    if (!wrap) return;
    wrap.classList.toggle('d-none', !show);

    // Hidden required fields block native submit ("not focusable")
    wrap.querySelectorAll('input, textarea, select').forEach(function (field) {
        if (!field.hasAttribute('name')) return;
        if (field.type === 'hidden' || field.type === 'radio' || field.type === 'checkbox') return;
        if (show) {
            if (field.dataset.wasRequired === '1') {
                field.setAttribute('required', 'required');
            }
        } else {
            if (field.hasAttribute('required')) {
                field.dataset.wasRequired = '1';
                field.removeAttribute('required');
            }
        }
    });
}

function ensureBillingFieldsFromSelection() {
    const savedAddressSelect = document.getElementById('saved_address');
    const formWrap = document.getElementById('billing-form-fields');
    const usingSaved = savedAddressSelect
        && savedAddressSelect.value !== ''
        && formWrap
        && formWrap.classList.contains('d-none');

    if (usingSaved) {
        populateAddress(savedAddressSelect.value);
        showBillingFormFields(false);
    }

    const lastName = document.getElementById('last_name');
    if (lastName && !String(lastName.value || '').trim()) {
        lastName.value = '-';
    }
}

function populateAddress(index) {
    const savedAddressSelect = document.getElementById('saved_address');
    if (!savedAddressSelect) return;

    if (index === '' || index === null || index === undefined || index === 'new') {
        setSelectedAddressCard('new');
        clearAddressForm();
        showBillingFormFields(true);
        return;
    }

    const option = document.querySelector('#saved_address option[value="' + index + '"]');
    if (!option) return;

    savedAddressSelect.value = String(index);
    setSelectedAddressCard(index);

    const name = option.dataset.name || '';
    const nameParts = name.trim().split(/\s+/);
    const firstNameField = document.getElementById('first_name');
    const lastNameField = document.getElementById('last_name');
    const emailField = document.getElementById('email');
    const phoneField = document.getElementById('phone');
    const addressField = document.getElementById('address');
    const countryField = document.getElementById('country');

    if (firstNameField) firstNameField.value = nameParts[0] || '';
    if (lastNameField) lastNameField.value = nameParts.slice(1).join(' ') || '';
    if (emailField) emailField.value = option.dataset.email || '';
    if (phoneField) phoneField.value = option.dataset.phone || '';
    if (addressField) addressField.value = option.dataset.address || '';
    if (countryField) countryField.value = option.dataset.country || BD_COUNTRY_ID || '';
    setDeliveryArea('billing_delivery_area', option.dataset.deliveryArea || 'inside');

    showBillingFormFields(false);

    const sameAsBillingCheckbox = document.getElementById('same-as-billing');
    if (sameAsBillingCheckbox && !sameAsBillingCheckbox.checked) {
        populateShippingAddress(option);
    }
}

function populateShippingAddress(option) {
    const name = option.dataset.name || '';
    const nameParts = name.trim().split(/\s+/);

    const shipFirstNameField = document.getElementById('ship_first_name');
    const shipLastNameField = document.getElementById('ship_last_name');
    const shipEmailField = document.getElementById('ship_email');
    const shipPhoneField = document.getElementById('ship_phone');
    const shipAddressField = document.getElementById('ship_address');
    const shipCountryField = document.getElementById('ship_country');

    if (shipFirstNameField) shipFirstNameField.value = nameParts[0] || '';
    if (shipLastNameField) shipLastNameField.value = nameParts.slice(1).join(' ') || '';
    if (shipEmailField) shipEmailField.value = option.dataset.email || '';
    if (shipPhoneField) shipPhoneField.value = option.dataset.phone || '';
    if (shipAddressField) shipAddressField.value = option.dataset.address || '';
    if (shipCountryField) shipCountryField.value = option.dataset.country || BD_COUNTRY_ID || '';
    setDeliveryArea('shipping_delivery_area', option.dataset.deliveryArea || 'inside');
}

function clearAddressForm() {
    ['first_name', 'last_name', 'email', 'phone', 'address'].forEach(function (fieldId) {
        const field = document.getElementById(fieldId);
        if (field) field.value = '';
    });
    const countryField = document.getElementById('country');
    if (countryField) countryField.value = BD_COUNTRY_ID || '';
    setDeliveryArea('billing_delivery_area', 'inside');

    const sameAsBillingCheckbox = document.getElementById('same-as-billing');
    if (sameAsBillingCheckbox && !sameAsBillingCheckbox.checked) {
        clearShippingForm();
    }
}

function clearShippingForm() {
    ['ship_first_name', 'ship_last_name', 'ship_email', 'ship_phone', 'ship_address'].forEach(function (fieldId) {
        const field = document.getElementById(fieldId);
        if (field) field.value = '';
    });
    const shipCountryField = document.getElementById('ship_country');
    if (shipCountryField) shipCountryField.value = BD_COUNTRY_ID || '';
    setDeliveryArea('shipping_delivery_area', 'inside');
}

class Checkout {
    constructor() {
        this.cart = [];
        this.shippingMethods = [];
        this.appliedCoupon = null;
        this.userData = null; // Store user data without auto-populating
        this.currencyIcon = STORE_CURRENCY;
        this.init();
    }

    formatMoney(amount) {
        const value = parseFloat(amount);
        return this.currencyIcon + (isNaN(value) ? '0.00' : value.toFixed(2));
    }

    async init() {
        await this.loadCheckoutData();
        this.bindEvents();
        this.formatCardInputs();
    }

    bindEvents() {
        // Address card selection
        const savedAddressSelect = document.getElementById('saved_address');
        document.querySelectorAll('.checkout-address-card[data-address-index]').forEach(function (card) {
            card.addEventListener('click', function () {
                populateAddress(card.dataset.addressIndex);
            });
        });

        if (savedAddressSelect && savedAddressSelect.value !== '') {
            populateAddress(savedAddressSelect.value);
        }

        const addNewBtn = document.getElementById('btn-add-new-address');
        if (addNewBtn) {
            addNewBtn.addEventListener('click', () => {
                if (savedAddressSelect) {
                    savedAddressSelect.selectedIndex = -1;
                }
                populateAddress('new');
            });
        }

        const cancelNewBtn = document.getElementById('btn-cancel-new-address');
        if (cancelNewBtn) {
            cancelNewBtn.addEventListener('click', () => {
                const fallback = @json((string)($defaultIdx ?? 0));
                populateAddress(fallback);
            });
        }

        // Same as billing checkbox
        document.getElementById('same-as-billing').addEventListener('change', (e) => {
            const shippingForm = document.getElementById('shipping-form');
            const hiddenField = document.getElementById('same_as_billing_hidden');
            
            shippingForm.style.display = e.target.checked ? 'none' : 'block';
            hiddenField.value = e.target.checked ? '1' : '0';
            
            // If unchecking "same as billing" and there's a selected address, auto-fill shipping
            if (!e.target.checked) {
                const select = document.getElementById('saved_address');
                if (select && select.value !== '' && select.selectedIndex >= 0) {
                    const selectedOption = select.options[select.selectedIndex];
                    if (selectedOption) {
                        populateShippingAddress(selectedOption);
                    }
                }
            }
        });

        const checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', (e) => {
                ensureBillingFieldsFromSelection();

                const payment = document.querySelector('input[name="payment_method"]:checked');
                const shipping = document.querySelector('input[name="shipping_method"]:checked');
                const firstName = document.getElementById('first_name');
                const email = document.getElementById('email');
                const phone = document.getElementById('phone');
                const address = document.getElementById('address');

                const missing = [];
                if (!firstName || !String(firstName.value || '').trim()) missing.push('name');
                if (!email || !String(email.value || '').trim()) missing.push('email');
                if (!phone || !String(phone.value || '').trim()) missing.push('phone');
                if (!address || !String(address.value || '').trim()) missing.push('address');
                if (!shipping) missing.push('shipping method');
                if (!payment) missing.push('payment method');

                if (missing.length) {
                    e.preventDefault();
                    const formWrap = document.getElementById('billing-form-fields');
                    if (formWrap && formWrap.classList.contains('d-none') && (missing.includes('name') || missing.includes('email') || missing.includes('phone') || missing.includes('address'))) {
                        showBillingFormFields(true);
                    }
                    this.showNotification(@json(__('Please fill: ')) + missing.join(', '), 'error');
                    return false;
                }

                const btn = document.getElementById('place-order-btn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' + @json(__('Placing Order...'));
                }
            });
        }

        const placeOrderBtn = document.getElementById('place-order-btn');
        if (placeOrderBtn && checkoutForm) {
            placeOrderBtn.addEventListener('click', (e) => {
                e.preventDefault();
                ensureBillingFieldsFromSelection();
                if (typeof checkoutForm.requestSubmit === 'function') {
                    checkoutForm.requestSubmit();
                    return;
                }
                // Legacy fallback
                const submitEvent = new Event('submit', { cancelable: true, bubbles: true });
                if (checkoutForm.dispatchEvent(submitEvent)) {
                    checkoutForm.submit();
                }
            });
        }

        // Shipping method change events are now bound in loadShippingMethods()

        // Payment method change
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', () => {
                this.togglePaymentForms();
            });
        });

        // Coupon application (if coupon form exists)
        const applyCouponBtn = document.getElementById('apply-coupon-btn');
        if (applyCouponBtn) {
            applyCouponBtn.addEventListener('click', () => {
                this.applyCoupon();
            });
        }
        
        const removeCouponBtn = document.getElementById('remove-coupon');
        if (removeCouponBtn) {
            removeCouponBtn.addEventListener('click', () => {
                this.removeCoupon();
            });
        }
    }

    async loadCheckoutData() {
        try {
            const response = await fetch('{{ route("checkout.data") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            console.log('Checkout data received:', data);
            
            if (data.success) {
                this.cart = data.cart_items;
                this.shippingMethods = data.shipping_methods;
                this.paymentGateways = {
                    cash_on_delivery: { status: (data.bankPaymentInfo && data.bankPaymentInfo.cash_on_delivery_status) || 0 },
                    stripe: data.stripePaymentInfo,
                    paypal: data.paypalPaymentInfo,
                    razorpay: data.razorpayPaymentInfo,
                    flutterwave: data.flutterwavePaymentInfo,
                    mollie: data.molliePaymentInfo,
                    instamojo: data.instamojo,
                    paystack: data.paystack,
                    sslcommerz: data.sslcommerz,
                    bank_payment: data.bankPaymentInfo
                };
                console.log('Payment gateways:', this.paymentGateways);
                this.loadOrderSummary();
                this.loadShippingMethods();
                this.loadPaymentMethods();
                this.loadBankAccountDetails(data.bankPaymentInfo);
                
                // Store user data for potential use but don't auto-populate
                if (data.user) {
                    this.userData = data.user;
                }
                
                // Keep blade-rendered address list; only refresh from API if select is empty
                const savedAddressSelect = document.getElementById('saved_address');
                if (data.addresses && data.addresses.length > 0) {
                    if (savedAddressSelect && savedAddressSelect.options.length === 0) {
                        this.populateAddressDropdown(data.addresses);
                    } else if (savedAddressSelect && savedAddressSelect.value !== '') {
                        populateAddress(savedAddressSelect.value);
                    }
                }
            } else {
                this.showNotification(@json(__('Failed to load checkout data')), 'error');
            }
        } catch (error) {
            console.error('Error loading checkout data:', error);
            this.showNotification(@json(__('Failed to load checkout data')), 'error');
        }
    }
    
    populateUserData(user) {
        // Split name into first and last name if full name is provided
        if (user.name && !user.first_name && !user.last_name) {
            const nameParts = user.name.split(' ');
            user.first_name = nameParts[0] || '';
            user.last_name = nameParts.slice(1).join(' ') || '';
        }
        
        // Populate billing information
        if (user.first_name) document.getElementById('first_name').value = user.first_name;
        if (user.last_name) document.getElementById('last_name').value = user.last_name;
        if (user.email) document.getElementById('email').value = user.email;
        if (user.phone) document.getElementById('phone').value = user.phone;
    }
    
    populateAddressDropdown(addresses) {
        const savedAddressSelect = document.getElementById('saved_address');
        if (!savedAddressSelect) return;

        savedAddressSelect.innerHTML = '';

        let defaultIndex = 0;
        const billingDefault = addresses.findIndex(a => Number(a.default_billing) === 1);
        const shippingDefault = addresses.findIndex(a => Number(a.default_shipping) === 1);
        if (billingDefault >= 0) defaultIndex = billingDefault;
        else if (shippingDefault >= 0) defaultIndex = shippingDefault;

        addresses.forEach((address, index) => {
            const option = document.createElement('option');
            option.value = index;
            const labelBits = [address.name || @json(__('Address'))];
            if (Number(address.default_billing) === 1) labelBits.push(@json(__('(Default)')));
            option.textContent = labelBits.join(' ') + ' — ' + (address.address || '');
            option.dataset.name = address.name || '';
            option.dataset.email = address.email || '';
            option.dataset.phone = address.phone || '';
            option.dataset.address = address.address || '';
            option.dataset.country = address.country_id || BD_COUNTRY_ID || '';
            option.dataset.deliveryArea = address.delivery_area || 'inside';
            option.dataset.defaultBilling = Number(address.default_billing) === 1 ? '1' : '0';
            option.dataset.defaultShipping = Number(address.default_shipping) === 1 ? '1' : '0';
            if (index === defaultIndex) option.selected = true;
            savedAddressSelect.appendChild(option);
        });

        this.userAddresses = addresses;
        populateAddress(String(defaultIndex));
    }
    
    populateAddressData(address) {
        if (address.name) {
            const nameParts = address.name.trim().split(/\s+/);
            document.getElementById('first_name').value = nameParts[0] || '';
            document.getElementById('last_name').value = nameParts.slice(1).join(' ') || '';
        }
        if (address.email) document.getElementById('email').value = address.email;
        if (address.phone) document.getElementById('phone').value = address.phone;
        if (address.address) document.getElementById('address').value = address.address;
        const countryField = document.getElementById('country');
        if (countryField) countryField.value = address.country_id || BD_COUNTRY_ID || '';
        setDeliveryArea('billing_delivery_area', address.delivery_area || 'inside');
    }
    
    loadOrderSummary() {
        const orderItemsContainer = document.getElementById('order-items');
        
        if (this.cart.length === 0) {
            orderItemsContainer.innerHTML = '<p class="text-muted">' + @json(__('No items in cart')) + '</p>';
            return;
        }

        orderItemsContainer.innerHTML = this.cart.map(item => {
            const variants = item.variants || [];
            let itemPrice = parseFloat(item.product_price || (item.product && (item.product.offer_price || item.product.price)) || 0);
            const variantPrices = variants
                .map(function (v) { return parseFloat(v.variant_price || v.price || 0); })
                .filter(function (p) { return p > 0; });
            if (variantPrices.length) {
                itemPrice = variantPrices.reduce(function (sum, p) { return sum + p; }, 0);
            }
            const totalItemPrice = itemPrice * item.quantity;
            
            return '<div class="order-item">' +
                    '<img src="' + (item.product_image || (item.product && item.product.thumb_image)) + '" alt="' + (item.product_name || (item.product && item.product.name)) + '">' +
                    '<div class="order-item-info">' +
                        '<div class="order-item-name">' + (item.product_name || (item.product && item.product.name)) + '</div>' +
                        '<div class="order-item-details">' + @json(__('Qty:')) + ' ' + item.quantity + '</div>' +
                        (variants.length > 0 ? 
                            '<div class="order-item-variants">' + 
                            variants.map(function(v) { return (v.variant_name || '') + ': ' + (v.variant_value || v.name || ''); }).join(', ') + 
                            '</div>' : '') +
                    '</div>' +
                    '<div class="order-item-price">' + this.formatMoney(totalItemPrice) + '</div>' +
                '</div>';
        }).join('');

        this.updateOrderSummary();
    }
    
    loadShippingMethods() {
        const shippingContainer = document.querySelector('.shipping-methods');
        if (shippingContainer && this.shippingMethods.length > 0) {
            shippingContainer.innerHTML = this.shippingMethods.map((method, index) => {
                return '<div class="form-check mb-2">' +
                    '<input class="form-check-input" type="radio" name="shipping_method" id="shipping_' + method.id + '" value="' + method.id + '" ' + (index === 0 ? 'checked' : '') + '>' +
                    '<label class="form-check-label d-flex justify-content-between" for="shipping_' + method.id + '">' +
                        '<span>' + method.shipping_rule + '</span>' +
                        '<span class="fw-bold">' + this.formatMoney(parseFloat(method.shipping_fee || 0)) + '</span>' +
                    '</label>' +
                '</div>';
            }).join('');
            
            // Bind event listeners after shipping methods are loaded
            document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    this.updateShippingCost();
                });
            });
            
            // Update shipping cost for the initially selected method
            this.updateShippingCost();
        } else if (shippingContainer) {
            // Show message if no shipping methods available
            shippingContainer.innerHTML = `
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${@json(__('No shipping methods available. Please contact support.'))}
                </div>
            `;
        }
    }
    
    loadPaymentMethods() {
        console.log('Loading payment methods...');
        const paymentContainer = document.getElementById('payment-methods-container');
        if (!paymentContainer) {
            console.error('Payment container not found!');
            return;
        }
        
        let paymentMethodsHtml = '';
        let firstActiveMethod = null;
        console.log('Checking payment gateways:', this.paymentGateways);
        
        // Cash on Delivery
        if (this.paymentGateways.cash_on_delivery && this.paymentGateways.cash_on_delivery.status == 1) {
            if (!firstActiveMethod) firstActiveMethod = 'cash_on_delivery';
            paymentMethodsHtml += '<div class="form-check mb-3">' +
                '<input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery" ' + (!firstActiveMethod || firstActiveMethod === 'cash_on_delivery' ? 'checked' : '') + '>' +
                '<label class="form-check-label" for="cash_on_delivery">' +
                    '<i class="fas fa-money-bill-wave me-2"></i>' + @json(__('Cash on Delivery')) +
                    '<small class="d-block text-muted mt-1">' + @json(__('Pay when you receive your order')) + '</small>' +
                '</label>' +
            '</div>';
        }
        
        // Stripe
        if (this.paymentGateways.stripe && this.paymentGateways.stripe.status == 1) {
            if (!firstActiveMethod) firstActiveMethod = 'stripe';
            paymentMethodsHtml += '<div class="form-check mb-3">' +
                '<input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" ' + (firstActiveMethod === 'stripe' ? 'checked' : '') + '>' +
                '<label class="form-check-label" for="stripe">' +
                    '<i class="fab fa-stripe me-2"></i>' + @json(__('Credit/Debit Card (Stripe)')) +
                    '<small class="d-block text-muted mt-1">' + @json(__('Pay securely with your credit or debit card')) + '</small>' +
                '</label>' +
            '</div>';
        }
        
        // PayPal
        if (this.paymentGateways.paypal && this.paymentGateways.paypal.status == 1) {
            if (!firstActiveMethod) firstActiveMethod = 'paypal';
            paymentMethodsHtml += '<div class="form-check mb-3">' +
                '<input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" ' + (firstActiveMethod === 'paypal' ? 'checked' : '') + '>' +
                '<label class="form-check-label" for="paypal">' +
                    '<i class="fab fa-paypal me-2"></i>PayPal' +
                    '<small class="d-block text-muted mt-1">' + @json(__('Pay with your PayPal account')) + '</small>' +
                '</label>' +
            '</div>';
        }
            
            // Razorpay
            if (this.paymentGateways.razorpay && this.paymentGateways.razorpay.status == 1) {
                if (!firstActiveMethod) firstActiveMethod = 'razorpay';
                paymentMethodsHtml += '<div class="form-check mb-3">' +
                    '<input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay" ' + (firstActiveMethod === 'razorpay' ? 'checked' : '') + '>' +
                    '<label class="form-check-label" for="razorpay">' +
                        '<i class="fas fa-credit-card me-2"></i>Razorpay' +
                        '<small class="d-block text-muted mt-1">Pay with cards, UPI, wallets & more</small>' +
                    '</label>' +
                '</div>';
            }
            
            // Flutterwave
            if (this.paymentGateways.flutterwave && this.paymentGateways.flutterwave.status == 1) {
                if (!firstActiveMethod) firstActiveMethod = 'flutterwave';
                paymentMethodsHtml += '<div class="form-check mb-3">' +
                    '<input class="form-check-input" type="radio" name="payment_method" id="flutterwave" value="flutterwave" ' + (firstActiveMethod === 'flutterwave' ? 'checked' : '') + '>' +
                    '<label class="form-check-label" for="flutterwave">' +
                        '<i class="fas fa-credit-card me-2"></i>Flutterwave' +
                        '<small class="d-block text-muted mt-1">Pay with cards, mobile money & bank transfers</small>' +
                    '</label>' +
                '</div>';
            }
            
            // Mollie
            if (this.paymentGateways.mollie && this.paymentGateways.mollie.status == 1) {
                if (!firstActiveMethod) firstActiveMethod = 'mollie';
                paymentMethodsHtml += '<div class="form-check mb-3">' +
                    '<input class="form-check-input" type="radio" name="payment_method" id="mollie" value="mollie" ' + (firstActiveMethod === 'mollie' ? 'checked' : '') + '>' +
                    '<label class="form-check-label" for="mollie">' +
                        '<i class="fas fa-credit-card me-2"></i>Mollie' +
                        '<small class="d-block text-muted mt-1">Pay with iDEAL, Bancontact, and more</small>' +
                    '</label>' +
                '</div>';
            }
            
            // Instamojo
            if (this.paymentGateways.instamojo && this.paymentGateways.instamojo.status == 1) {
                if (!firstActiveMethod) firstActiveMethod = 'instamojo';
                paymentMethodsHtml += '<div class="form-check mb-3">' +
                    '<input class="form-check-input" type="radio" name="payment_method" id="instamojo" value="instamojo" ' + (firstActiveMethod === 'instamojo' ? 'checked' : '') + '>' +
                    '<label class="form-check-label" for="instamojo">' +
                        '<i class="fas fa-credit-card me-2"></i>Instamojo' +
                        '<small class="d-block text-muted mt-1">Pay with cards, net banking & wallets</small>' +
                    '</label>' +
                '</div>';
            }
            
            // Paystack
            if (this.paymentGateways.paystack_and_mollie && this.paymentGateways.paystack_and_mollie.paystack_status == 1) {
                if (!firstActiveMethod) firstActiveMethod = 'paystack';
                paymentMethodsHtml += '<div class="form-check mb-3">' +
                    '<input class="form-check-input" type="radio" name="payment_method" id="paystack" value="paystack" ' + (firstActiveMethod === 'paystack' ? 'checked' : '') + '>' +
                    '<label class="form-check-label" for="paystack">' +
                        '<i class="fas fa-credit-card me-2"></i>Paystack' +
                        '<small class="d-block text-muted mt-1">Pay with cards, bank transfers & USSD</small>' +
                    '</label>' +
                '</div>';
            }
            
            // SSLCommerz
            if (this.paymentGateways.sslcommerz && this.paymentGateways.sslcommerz.status == 1) {
                if (!firstActiveMethod) firstActiveMethod = 'sslcommerz';
                paymentMethodsHtml += '<div class="form-check mb-3">' +
                    '<input class="form-check-input" type="radio" name="payment_method" id="sslcommerz" value="sslcommerz" ' + (firstActiveMethod === 'sslcommerz' ? 'checked' : '') + '>' +
                    '<label class="form-check-label" for="sslcommerz">' +
                        '<i class="fas fa-credit-card me-2"></i>SSLCommerz' +
                        '<small class="d-block text-muted mt-1">' + @json(__('Pay with cards, mobile banking & internet banking')) + '</small>' +
                    '</label>' +
                '</div>';
            }
            
            // Bank Payment
            if (this.paymentGateways.bank_payment && this.paymentGateways.bank_payment.status == 1) {
                if (!firstActiveMethod) firstActiveMethod = 'bank_payment';
                paymentMethodsHtml += '<div class="form-check mb-3">' +
                    '<input class="form-check-input" type="radio" name="payment_method" id="bank_payment" value="bank_payment" ' + (firstActiveMethod === 'bank_payment' ? 'checked' : '') + '>' +
                    '<label class="form-check-label" for="bank_payment">' +
                        '<i class="fas fa-university me-2"></i>' + @json(__('Bank Transfer')) +
                        '<small class="d-block text-muted mt-1">' + @json(__('Transfer directly to our bank account')) + '</small>' +
                    '</label>' +
                '</div>';
            }
            
            paymentMethodsHtml += '</div>';
        
        paymentContainer.innerHTML = paymentMethodsHtml;
        
        // Add event listener for "Pay Now" button
        const showOtherPaymentsBtn = document.getElementById('show-other-payments');
        if (showOtherPaymentsBtn) {
            showOtherPaymentsBtn.addEventListener('click', () => {
                const otherPaymentMethods = document.getElementById('other-payment-methods');
                if (otherPaymentMethods.style.display === 'none') {
                    otherPaymentMethods.style.display = 'block';
                    showOtherPaymentsBtn.innerHTML = '<i class="fas fa-eye-slash me-2"></i>' + @json(__('Hide Other Payment Methods'));
                } else {
                    otherPaymentMethods.style.display = 'none';
                    showOtherPaymentsBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>' + @json(__('Pay Now (Other Payment Methods)'));
                    // Reset to Cash on Delivery when hiding other methods
                    const codRadio = document.getElementById('cash_on_delivery');
                    if (codRadio) {
                        codRadio.checked = true;
                        this.togglePaymentForms();
                    }
                }
            });
        }
        
        // Select Cash on Delivery by default if available
        if (firstActiveMethod) {
            const firstRadio = document.getElementById(firstActiveMethod);
            if (firstRadio) {
                firstRadio.checked = true;
                this.togglePaymentForms();
            }
        }
        
        // Re-bind payment method change events
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', () => {
                this.togglePaymentForms();
            });
        });
    }

    updateShippingCost() {
        const selectedShippingElement = document.querySelector('input[name="shipping_method"]:checked');
        const selectedShippingId = selectedShippingElement ? selectedShippingElement.value : null;
        if (selectedShippingId) {
            const selectedShipping = this.shippingMethods.find(function(method) { return method.id == selectedShippingId; });
            const shippingCost = selectedShipping ? parseFloat(selectedShipping.shipping_fee) : 0;
            
            document.getElementById('shipping-cost').textContent = this.formatMoney(shippingCost);
            this.updateOrderSummary();
        }
    }

    updateOrderSummary() {
        // Handle empty cart scenario
        if (!this.cart || this.cart.length === 0) {
            document.getElementById('subtotal').textContent = this.formatMoney(0);
            document.getElementById('shipping-cost').textContent = this.formatMoney(0);
            document.getElementById('tax').textContent = this.formatMoney(0);
            document.getElementById('total').textContent = this.formatMoney(0);
            
            const couponDiscountElement = document.getElementById('coupon-discount-amount');
            if (couponDiscountElement) {
                couponDiscountElement.textContent = '-' + this.formatMoney(0);
            }
            return;
        }
        
        const subtotal = this.cart.reduce(function(sum, item) {
            const variants = item.variants || [];
            let itemPrice = parseFloat(item.product_price || (item.product && (item.product.offer_price || item.product.price)) || 0);
            const variantPrices = variants
                .map(function (v) { return parseFloat(v.variant_price || v.price || 0); })
                .filter(function (p) { return p > 0; });
            if (variantPrices.length) {
                itemPrice = variantPrices.reduce(function (s, p) { return s + p; }, 0);
            }
            const quantity = parseInt(item.quantity || 0);
            const itemTotal = itemPrice * quantity;
            return sum + (isNaN(itemTotal) ? 0 : itemTotal);
        }, 0);
        
        const selectedShippingElement = document.querySelector('input[name="shipping_method"]:checked');
        const selectedShippingId = selectedShippingElement ? selectedShippingElement.value : null;
        const selectedShipping = this.shippingMethods.find(method => method.id == selectedShippingId);
        const shipping = selectedShipping ? parseFloat(selectedShipping.shipping_fee) || 0 : 0;
        
        const couponDiscount = this.appliedCoupon ? this.calculateCouponDiscount(subtotal) : 0;
        const tax = 0; // No tax calculation
        const total = subtotal + shipping - couponDiscount;

        // Ensure all values are valid numbers before displaying
        const safeSubtotal = isNaN(subtotal) ? 0 : subtotal;
        const safeShipping = isNaN(shipping) ? 0 : shipping;
        const safeTax = isNaN(tax) ? 0 : tax;
        const safeTotal = isNaN(total) ? 0 : total;
        const safeCouponDiscount = isNaN(couponDiscount) ? 0 : couponDiscount;

        document.getElementById('subtotal').textContent = this.formatMoney(safeSubtotal);
        document.getElementById('shipping-cost').textContent = this.formatMoney(safeShipping);
        document.getElementById('tax').textContent = this.formatMoney(safeTax);
        document.getElementById('total').textContent = this.formatMoney(safeTotal);
        
        // Update coupon discount display if exists
        const couponDiscountElement = document.getElementById('coupon-discount-amount');
        if (couponDiscountElement) {
            couponDiscountElement.textContent = '-' + this.formatMoney(safeCouponDiscount);
        }
    }
    
    calculateCouponDiscount(subtotal) {
        if (!this.appliedCoupon) return 0;
        
        if (this.appliedCoupon.discount_type === 'percentage') {
            return (subtotal * this.appliedCoupon.discount) / 100;
        } else {
            return this.appliedCoupon.discount;
        }
    }
    
    async applyCoupon() {
        const couponInput = document.getElementById('coupon-code');
        if (!couponInput) return;
        
        const couponCode = couponInput.value.trim();
        if (!couponCode) {
            this.showNotification(@json(__('Please enter a coupon code')), 'error');
            return;
        }
        
        const applyCouponBtn = document.getElementById('apply-coupon-btn');
        const originalText = applyCouponBtn.innerHTML;
        applyCouponBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>' + @json(__('Applying...'));
        applyCouponBtn.disabled = true;
        
        try {
            const response = await fetch('{{ route("checkout.apply-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    coupon_code: couponCode
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.appliedCoupon = data.coupon;
                this.showNotification(data.message, 'success');
                this.updateOrderSummary();
                
                // Show applied coupon info
                const couponInfo = document.getElementById('coupon-info');
                const couponInfoText = document.getElementById('coupon-info-text');
                if (couponInfo && couponInfoText) {
                    const discountText = data.coupon.discount_type === 'percentage' 
                    ? data.coupon.discount + '% ' + @json(__('off'))
                    : this.formatMoney(data.coupon.discount) + ' ' + @json(__('off'));
                couponInfoText.textContent = @json(__('Coupon')) + ' "' + data.coupon.code + '" ' + @json(__('applied')) + ' - ' + discountText;
                    couponInfo.style.display = 'block';
                }
                
                couponInput.disabled = true;
                applyCouponBtn.style.display = 'none';
                
                // Show coupon discount in order summary
                const couponDiscountRow = document.getElementById('coupon-discount');
                if (couponDiscountRow) {
                    couponDiscountRow.style.display = 'flex';
                }
            } else {
                this.showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error applying coupon:', error);
            this.showNotification(@json(__('Failed to apply coupon')), 'error');
        } finally {
            applyCouponBtn.innerHTML = originalText;
            applyCouponBtn.disabled = false;
        }
    }
    
    removeCoupon() {
        this.appliedCoupon = null;
        this.updateOrderSummary();
        
        const couponInput = document.getElementById('coupon-code');
        const applyCouponBtn = document.getElementById('apply-coupon-btn');
        const couponInfo = document.getElementById('coupon-info');
        const couponDiscountRow = document.getElementById('coupon-discount');
        
        if (couponInput) {
            couponInput.value = '';
            couponInput.disabled = false;
        }
        
        if (applyCouponBtn) {
            applyCouponBtn.style.display = 'inline-block';
        }
        
        if (couponInfo) {
            couponInfo.style.display = 'none';
        }
        
        if (couponDiscountRow) {
            couponDiscountRow.style.display = 'none';
        }
        
        this.showNotification(@json(__('Coupon removed')), 'info');
    }
    
    loadBankAccountDetails(bankPaymentInfo) {
         const bankAccountDetails = document.getElementById('bank-account-details');
         if (bankAccountDetails && bankPaymentInfo && bankPaymentInfo.status == 1) {
             // Parse the account_info which contains formatted bank details
             const accountInfo = bankPaymentInfo.account_info || '';
             const formattedInfo = accountInfo.replace(/\n/g, '<br>');
             
             bankAccountDetails.innerHTML = '<div class="bank-details">' +
                formattedInfo +
            '</div>' +
            '<p class="text-muted mt-3 mb-0"><small>' + @json(__('Please use the order number as reference when making the transfer.')) + '</small></p>';
         }
     }

    togglePaymentForms() {
        const selected = document.querySelector('input[name="payment_method"]:checked');
        if (!selected) return;
        const selectedMethod = selected.value;
        const bankPaymentInfo = document.getElementById('bank-payment-info');
        
        // Hide all forms first
        if (bankPaymentInfo) bankPaymentInfo.style.display = 'none';
        
        // Show relevant form based on selected method
        if (selectedMethod === 'bank_payment') {
            if (bankPaymentInfo) bankPaymentInfo.style.display = 'block';
        }
        // Note: Stripe payment will redirect to Stripe's hosted payment page
    }

    formatCardInputs() {
        // Note: Stripe card input formatting is no longer needed 
        // as payment processing is handled by Stripe's hosted page
    }

    validateForm() {
        const form = document.getElementById('checkout-form');
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Validate payment method specific fields
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
        if (selectedPayment === 'credit_card') {
            const cardFields = ['card_number', 'expiry', 'cvv', 'card_name'];
            cardFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
        }

        return isValid;
    }

    // placeOrder function removed - now using traditional form submission

    showNotification(message, type = 'success') {
        // Use the existing notification system from app.js
        if (window.showNotification) {
            window.showNotification(message, type);
        } else {
            alert(message);
        }
    }
}

// Initialize checkout
const checkout = new Checkout();
</script>

@push('scripts')
@endpush

@endsection