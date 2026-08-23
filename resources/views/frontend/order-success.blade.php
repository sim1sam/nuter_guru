@extends('frontend.layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                <h1 class="mb-3">Order Placed Successfully!</h1>
                <p class="lead text-muted">Thank you for your purchase. Your order has been received and is being processed.</p>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Order Number:</strong>
                            <span>{{ $order->order_id ?? '#ORDER-NOT-FOUND' }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Order Date:</strong>
                            <span>{{ $order ? $order->created_at->format('F j, Y') : date('F j, Y') }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Payment Method:</strong><br>
                            <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Estimated Delivery:</strong>
                            <span>{{ $order ? $order->created_at->addDays(7)->format('F j, Y') : date('F j, Y', strtotime('+7 days')) }}</span>
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
                                    <img src="{{ $orderProduct->product->thumb_image ?? '/frontend/images/products/default.jpg' }}" alt="{{ $orderProduct->product_name }}">
                                    <div class="order-item-info">
                                        <div class="order-item-name">{{ $orderProduct->product_name }}</div>
                                        <div class="order-item-details">Category: {{ $orderProduct->product->category->name ?? 'N/A' }}</div>
                                        <div class="order-item-details">Quantity: {{ $orderProduct->qty }}</div>
                                        <div class="order-item-details">Unit Price: {{ currency_icon() }}{{ number_format($orderProduct->unit_price, 2) }}</div>
                    </div>
                    <div class="order-item-price">{{ currency_icon() }}{{ number_format($orderProduct->unit_price * $orderProduct->qty, 2) }}</div>
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
                                $subtotal = $order ? $order->orderProducts->sum(function($item) { return $item->unit_price * $item->qty; }) : 0;
                                $shipping = $order->shipping_cost ?? 0;
                                $couponDiscount = $order->coupon_coast ?? 0;
                                $tax = 0; // No tax calculation
                                $total = $order->total_amount ?? 0;
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>{{ currency_icon() }}{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>{{ currency_icon() }}{{ number_format($shipping, 2) }}</span>
                            </div>
                            @if($couponDiscount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Coupon Discount:</span>
                                <span class="text-success">-{{ currency_icon() }}{{ number_format($couponDiscount, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>{{ currency_icon() }}{{ number_format($tax, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong>{{ currency_icon() }}{{ number_format($total, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Billing Address</h6>
                        </div>
                        <div class="card-body">
                            <div id="billing-address">
                                @if($order && $order->orderAddress)
                                    <p class="mb-1">{{ $order->orderAddress->billing_name }}</p>
                                    <p class="mb-1">{{ $order->orderAddress->billing_address }}</p>
                                    <p class="mb-1">{{ $order->orderAddress->billing_city }}, {{ $order->orderAddress->billing_state }}</p>
                                    <p class="mb-0">{{ $order->orderAddress->billing_country }}</p>
                                    @if($order->orderAddress->billing_phone)
                                        <p class="mb-0"><strong>Phone:</strong> {{ $order->orderAddress->billing_phone }}</p>
                                    @endif
                                    @if($order->orderAddress->billing_email)
                                        <p class="mb-0"><strong>Email:</strong> {{ $order->orderAddress->billing_email }}</p>
                                    @endif
                                @else
                                    <p class="text-muted">Billing address not available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Shipping Address</h6>
                        </div>
                        <div class="card-body">
                            <div id="shipping-address">
                                @if($order && $order->orderAddress)
                                    <p class="mb-1">{{ $order->orderAddress->shipping_name }}</p>
                                    <p class="mb-1">{{ $order->orderAddress->shipping_address }}</p>
                                    <p class="mb-1">{{ $order->orderAddress->shipping_city }}, {{ $order->orderAddress->shipping_state }}</p>
                                    <p class="mb-0">{{ $order->orderAddress->shipping_country }}</p>
                                    @if($order->orderAddress->shipping_phone)
                                        <p class="mb-0"><strong>Phone:</strong> {{ $order->orderAddress->shipping_phone }}</p>
                                    @endif
                                    @if($order->orderAddress->shipping_email)
                                        <p class="mb-0"><strong>Email:</strong> {{ $order->orderAddress->shipping_email }}</p>
                                    @endif
                                @else
                                    <p class="text-muted">Shipping address not available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>What's Next?</strong><br>
                    You will receive an email confirmation shortly with your order details and tracking information.
                </div>
                
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Continue Shopping
                    </a>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                </div>
            </div>
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
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
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
}

.order-item-info {
    flex: 1;
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
    color: #d4af37;
    font-size: 16px;
}

@media print {
    .btn, .alert {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    
    .success-icon {
        display: none;
    }
}
</style>


@endsection