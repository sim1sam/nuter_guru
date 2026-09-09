@php
    $grandTotal = 0;
    $taxRate = floatval($setting->tax);
    $cupon = 0;
    $tax = 0;
    $discount = 0;
    $subTotal = 0;
@endphp

<div class="pos-cart-head">
    <span>{{ __('Item') }}</span>
    <span>{{ __('QTY') }}</span>
    <span>{{ __('Price') }}</span>
    <span>{{ __('Action') }}</span>
</div>

<div class="pos-cart-items-wrap" id="posCartItems">
    @forelse ($cart_products as $index => $product)
        <div class="pos-cart-item delivery-information-top-item-two">
            <div class="pos-cart-item__info delivery-information-top-item-two-img">
                <img src="{{ asset($product->card_product->thumb_image) }}" alt="">
                <p class="pos-cart-item__name">
                    {{ $product->card_product->name }}
                    @if (!empty($product->variant_name_snapshot))
                        <small>({{ $product->variant_name_snapshot }})</small>
                    @endif
                </p>
            </div>

            <div class="count">
                <div class="mainas">
                    <p>
                        <a href="{{ route('admin.pos.cart.decrement.product', $product->id) }}" class="pos-cart-action" data-action="decrement">
                            <span>
                                <svg width="14" height="2" viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13 1L1 1" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </a>
                    </p>
                </div>
                <div class="count-text">
                    <input type="number" class="pos-qty-input" min="1" data-cart-id="{{ $product->id }}" value="{{ $product->qty }}">
                </div>
                <div class="plus">
                    <p>
                        <a href="{{ route('admin.pos.cart.increment.product', $product->id) }}" class="pos-cart-action" data-action="increment">
                            <span>
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 1V13M13 7L1 7" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </a>
                    </p>
                </div>
            </div>

            <div class="price">
                @php
                    if ($product->unit_price !== null && $product->unit_price !== '') {
                        $price = (float) $product->unit_price;
                    } elseif ($product->card_product->offer_price == '') {
                        $price = (float) $product->card_product->price;
                    } else {
                        $price = (float) $product->card_product->offer_price;
                    }
                    $total = $product->qty * $price;
                    $grandTotal += $total;
                    $tax = ($grandTotal * ($taxRate / 100));
                    if ($coupon) {
                        $cupon = $coupon->discount;
                    }
                    $discount = ($grandTotal * ($cupon / 100));
                    $discountedTotal = $grandTotal - $discount;
                    $subTotal = ($discountedTotal + $tax);
                @endphp
                <p>{{ $setting->currency_icon }}{{ $price }}</p>
            </div>

            <div class="action">
                <a href="{{ route('admin.pos.destroy.product', $product->id) }}" class="pos-cart-action" data-action="destroy">
                    <span>
                        <svg width="19" height="24" viewBox="0 0 19 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.7842 6.29297C16.5061 6.29297 16.2393 6.40348 16.0426 6.60018C15.8459 6.79689 15.7354 7.06368 15.7354 7.34187V19.0801C15.7053 19.6105 15.4668 20.1075 15.0719 20.4629C14.677 20.8183 14.1577 21.0033 13.6271 20.9775H5.25686C4.72622 21.0033 4.2069 20.8183 3.81201 20.4629C3.41712 20.1075 3.17867 19.6105 3.14858 19.0801V7.34187C3.14858 7.06368 3.03807 6.79689 2.84136 6.60018C2.64466 6.40348 2.37786 6.29297 2.09968 6.29297C1.82149 6.29297 1.5547 6.40348 1.358 6.60018C1.16129 6.79689 1.05078 7.06368 1.05078 7.34187V19.0801C1.08072 20.167 1.54018 21.1977 2.32853 21.9466C3.11688 22.6954 4.16986 23.1013 5.25686 23.0753H13.6271C14.7141 23.1013 15.7671 22.6954 16.5554 21.9466C17.3438 21.1977 17.8032 20.167 17.8331 19.0801V7.34187C17.8331 7.06368 17.7226 6.79689 17.5259 6.60018C17.3292 6.40348 17.0624 6.29297 16.7842 6.29297Z" />
                            <path d="M17.8313 3.14669H13.6357V1.0489C13.6357 0.770713 13.5252 0.503921 13.3285 0.307215C13.1317 0.110509 12.865 0 12.5868 0H6.29339C6.0152 0 5.74841 0.110509 5.5517 0.307215C5.355 0.503921 5.24449 0.770713 5.24449 1.0489V3.14669H1.0489C0.770713 3.14669 0.503921 3.2572 0.307215 3.45391C0.110509 3.65061 0 3.91741 0 4.19559C0 4.47378 0.110509 4.74057 0.307215 4.93727C0.503921 5.13398 0.770713 5.24449 1.0489 5.24449H17.8313C18.1094 5.24449 18.3762 5.13398 18.5729 4.93727C18.7697 4.74057 18.8802 4.47378 18.8802 4.19559C18.8802 3.91741 18.7697 3.65061 18.5729 3.45391C18.3762 3.2572 18.1094 3.14669 17.8313 3.14669ZM7.34228 3.14669V2.0978H11.5379V3.14669H7.34228Z" />
                            <path d="M8.39272 16.7813V9.43903C8.39272 9.16085 8.28221 8.89406 8.0855 8.69735C7.8888 8.50065 7.622 8.39014 7.34382 8.39014C7.06563 8.39014 6.79884 8.50065 6.60214 8.69735C6.40543 8.89406 6.29492 9.16085 6.29492 9.43903V16.7813C6.29492 17.0595 6.40543 17.3263 6.60214 17.523C6.79884 17.7197 7.06563 17.8302 7.34382 17.8302C7.622 17.8302 7.8888 17.7197 8.0855 17.523C8.28221 17.3263 8.39272 17.0595 8.39272 16.7813Z" />
                            <path d="M12.588 16.7813V9.43903C12.588 9.16085 12.4775 8.89406 12.2808 8.69735C12.0841 8.50065 11.8173 8.39014 11.5391 8.39014C11.2609 8.39014 10.9942 8.50065 10.7974 8.69735C10.6007 8.89406 10.4902 9.16085 10.4902 9.43903V16.7813C10.4902 17.0595 10.6007 17.3263 10.7974 17.523C10.9942 17.7197 11.2609 17.8302 11.5391 17.8302C11.8173 17.8302 12.0841 17.7197 12.2808 17.523C12.4775 17.3263 12.588 17.0595 12.588 16.7813Z" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    @empty
        <div class="pos-cart-empty">{{ __('admin.No items in cart') }}</div>
    @endforelse
</div>

<div class="apply-promo-code-btn-main" id="posPromoWrap">
    <form action="{{ route('admin.pos.apply.cupon') }}" method="get">
        <input type="text" class="form-control" name="coupon" id="exampleFormControlInput-3"
               placeholder="{{ __('admin.Apply Promo Code') }}" value="{{ $couponValue ?? '' }}">
        <button type="submit" class="promo-code-btn">{{ __('admin.Apply') }}</button>
    </form>
</div>

<div class="pos-totals">
    <div class="sub-total" id="posSubTotalBlock">
        <div class="sub-total-item">
            <h6>{{ __('admin.Sub total :') }}</h6>
            <h6>{{ __('admin.Discount :') }}</h6>
            <h6>{{ __('admin.Tax :') }}</h6>
        </div>
        <div class="sub-total-inner">
            <h6>{{ $setting->currency_icon }}{{ $grandTotal == 0 ? 0 : $grandTotal }}</h6>
            <h6>{{ $setting->currency_icon }}{{ $grandTotal == 0 ? 0 : $discount }}</h6>
            <h6>{{ $setting->currency_icon }}{{ $grandTotal == 0 ? 0 : $tax }}</h6>
        </div>
    </div>

    <div class="sub-total-btm" id="posGrandTotalBlock">
        <div class="sub-total-btm-item">
            <h6>{{ __('admin.Sub total :') }}</h6>
        </div>
        <div class="sub-total-btm-inner">
            <h6>{{ $setting->currency_icon }}{{ $grandTotal == 0 ? 0 : $subTotal }}</h6>
        </div>
    </div>
</div>
