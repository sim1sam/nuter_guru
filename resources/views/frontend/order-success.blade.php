@extends('frontend.layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="container my-5 order-success-page">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5 order-success-hero no-print">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                <h1 class="mb-3">Order Placed Successfully!</h1>
                <p class="lead text-muted">Thank you for your purchase. Your order has been received and is being processed.</p>
            </div>

            <div class="order-receipt" id="orderReceipt">
                <div class="print-only print-receipt-header">
                    <h2>{{ config('app.name', 'Nuter Guru') }}</h2>
                    <p>Order Receipt</p>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Order Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <strong>Order Number:</strong>
                                <span>{{ $order->order_id ?? '#ORDER-NOT-FOUND' }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Order Date:</strong>
                                <span>{{ $order ? $order->created_at->format('F j, Y') : date('F j, Y') }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Payment Method:</strong>
                                <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Estimated Delivery:</strong>
                                <span>{{ $order ? $order->created_at->copy()->addDays(7)->format('F j, Y') : date('F j, Y', strtotime('+7 days')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div id="order-items">
                            @if($order && $order->orderProducts->count() > 0)
                                @foreach($order->orderProducts as $orderProduct)
                                    <div class="order-item">
                                        <img src="{{ $orderProduct->product->thumb_image ? asset($orderProduct->product->thumb_image) : asset('frontend/images/default-product.svg') }}"
                                             alt="{{ $orderProduct->product_name }}"
                                             onerror="this.src='{{ asset('frontend/images/default-product.svg') }}'">
                                        <div class="order-item-info">
                                            <div class="order-item-name">{{ $orderProduct->product_name }}</div>
                                            <div class="order-item-details">Category: {{ $orderProduct->product->category->name ?? 'N/A' }}</div>
                                            <div class="order-item-details">Quantity: {{ $orderProduct->qty }}</div>
                                            <div class="order-item-details">Unit Price: {{ format_currency($orderProduct->unit_price) }}</div>
                                        </div>
                                        <div class="order-item-price">{{ format_currency($orderProduct->unit_price * $orderProduct->qty) }}</div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted">No order items found.</p>
                                </div>
                            @endif
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                @php
                                    $subtotal = $order ? $order->orderProducts->sum(function ($item) {
                                        return $item->unit_price * $item->qty;
                                    }) : 0;
                                    $shipping = $order->shipping_cost ?? 0;
                                    $couponDiscount = $order->coupon_coast ?? 0;
                                    $tax = 0;
                                    $total = $order->total_amount ?? 0;
                                @endphp
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>{{ format_currency($subtotal) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>{{ format_currency($shipping) }}</span>
                                </div>
                                @if($couponDiscount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Coupon Discount:</span>
                                    <span class="text-success">-{{ format_currency($couponDiscount) }}</span>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span>{{ format_currency($tax) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong>{{ format_currency($total) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0">Billing Address</h6>
                            </div>
                            <div class="card-body">
                                @if($order && $order->orderAddress)
                                    <p class="mb-1">{{ $order->orderAddress->billing_name }}</p>
                                    <p class="mb-1">{{ $order->orderAddress->billing_address }}</p>
                                    @if(trim(($order->orderAddress->billing_city ?? '') . ($order->orderAddress->billing_state ?? '')) !== '')
                                        <p class="mb-1">{{ trim(($order->orderAddress->billing_city ?? '') . ', ' . ($order->orderAddress->billing_state ?? ''), ' ,') }}</p>
                                    @endif
                                    <p class="mb-0">{{ $order->orderAddress->billing_country }}</p>
                                    @if($order->orderAddress->billing_phone)
                                        <p class="mb-0 mt-2"><strong>Phone:</strong> {{ $order->orderAddress->billing_phone }}</p>
                                    @endif
                                    @if($order->orderAddress->billing_email)
                                        <p class="mb-0"><strong>Email:</strong> {{ $order->orderAddress->billing_email }}</p>
                                    @endif
                                @else
                                    <p class="text-muted mb-0">Billing address not available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0">Shipping Address</h6>
                            </div>
                            <div class="card-body">
                                @if($order && $order->orderAddress)
                                    <p class="mb-1">{{ $order->orderAddress->shipping_name }}</p>
                                    <p class="mb-1">{{ $order->orderAddress->shipping_address }}</p>
                                    @if(trim(($order->orderAddress->shipping_city ?? '') . ($order->orderAddress->shipping_state ?? '')) !== '')
                                        <p class="mb-1">{{ trim(($order->orderAddress->shipping_city ?? '') . ', ' . ($order->orderAddress->shipping_state ?? ''), ' ,') }}</p>
                                    @endif
                                    <p class="mb-0">{{ $order->orderAddress->shipping_country }}</p>
                                    @if($order->orderAddress->shipping_phone)
                                        <p class="mb-0 mt-2"><strong>Phone:</strong> {{ $order->orderAddress->shipping_phone }}</p>
                                    @endif
                                    @if($order->orderAddress->shipping_email)
                                        <p class="mb-0"><strong>Email:</strong> {{ $order->orderAddress->shipping_email }}</p>
                                    @endif
                                @else
                                    <p class="text-muted mb-0">Shipping address not available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 no-print">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>What's Next?</strong><br>
                    You will receive an email confirmation shortly with your order details and tracking information.
                </div>

                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Continue Shopping
                    </a>
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: bounceIn 1s ease-in-out;
}

@keyframes bounceIn {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
}

.order-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
}

.order-item-info {
    flex: 1;
    min-width: 0;
}

.order-item-name {
    font-weight: 600;
    margin-bottom: 5px;
}

.order-item-details {
    font-size: 14px;
    color: #666;
    margin-bottom: 3px;
}

.order-item-price {
    font-weight: 600;
    color: var(--primary-color, #f6913b);
    font-size: 16px;
    white-space: nowrap;
}

.print-only {
    display: none;
}

@media print {
    @page {
        size: A4;
        margin: 12mm;
    }

    html, body {
        background: #fff !important;
        color: #000 !important;
        font-size: 12pt !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .header,
    .footer,
    .organic-header,
    .mobile-bottom-nav,
    .mobile-app-menu,
    .cart-drawer,
    .offcanvas,
    .offcanvas-backdrop,
    .whatsapp-chat-btn,
    .pwa-install,
    .app-download-sheet,
    .modal,
    .no-print,
    .btn,
    .alert,
    .success-icon {
        display: none !important;
    }

    body.mobile-app-mode {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .main-content,
    .order-success-page,
    .order-success-page .container,
    .order-success-page .row,
    .order-success-page .col-lg-8 {
        margin: 0 !important;
        padding: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
    }

    .print-only {
        display: block !important;
    }

    .print-receipt-header {
        text-align: center;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid #000;
    }

    .print-receipt-header h2 {
        margin: 0 0 4px;
        font-size: 20pt;
        color: #000 !important;
    }

    .print-receipt-header p {
        margin: 0;
        font-size: 12pt;
        color: #333 !important;
    }

    .order-receipt .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        break-inside: avoid;
        page-break-inside: avoid;
        margin-bottom: 14px !important;
        background: #fff !important;
    }

    .order-receipt .card-header {
        background: #f0f0f0 !important;
        color: #000 !important;
        border-bottom: 1px solid #000 !important;
        padding: 8px 12px !important;
    }

    .order-receipt .card-header.bg-success,
    .order-receipt .card-header.text-white {
        background: #f0f0f0 !important;
        color: #000 !important;
    }

    .order-receipt .card-body {
        padding: 12px !important;
    }

    .order-item {
        break-inside: avoid;
        page-break-inside: avoid;
        border-bottom: 1px solid #ccc !important;
        padding: 10px 0 !important;
    }

    .order-item img {
        width: 48px !important;
        height: 48px !important;
    }

    .order-item-price,
    .order-item-details,
    .text-success,
    .text-muted {
        color: #000 !important;
    }

    .order-receipt .row {
        display: flex !important;
        flex-wrap: wrap !important;
    }

    .order-receipt .col-md-6 {
        width: 50% !important;
        max-width: 50% !important;
        flex: 0 0 50% !important;
    }

    .order-receipt .col-md-6.offset-md-6 {
        margin-left: 50% !important;
        width: 50% !important;
    }

    a {
        color: #000 !important;
        text-decoration: none !important;
    }
}
</style>
@endsection
