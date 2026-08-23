@extends('frontend.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-receipt me-2"></i>Order Details</h2>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-home me-1"></i>Back to Home
                </a>
            </div>

            <!-- Order Status Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Order Number:</strong><br>
                            <span class="text-primary">{{ $order->order_id }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Order Date:</strong><br>
                            <span>{{ $order->created_at->format('F j, Y g:i A') }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Order Status:</strong><br>
                            @php
                                $statusLabels = [
                                    0 => ['label' => 'Pending', 'class' => 'warning'],
                                    1 => ['label' => 'In Progress', 'class' => 'info'],
                                    2 => ['label' => 'Delivered', 'class' => 'success'],
                                    3 => ['label' => 'Completed', 'class' => 'success'],
                                    4 => ['label' => 'Declined', 'class' => 'danger']
                                ];
                                $status = $statusLabels[$order->order_status] ?? ['label' => 'Unknown', 'class' => 'secondary'];
                            @endphp
                            <span class="badge bg-{{ $status['class'] }}">{{ $status['label'] }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Payment Status:</strong><br>
                            <span class="badge bg-{{ $order->payment_status == 1 ? 'success' : 'warning' }}">
                                {{ $order->payment_status == 1 ? 'Paid' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Payment Method:</strong><br>
                            <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Shipping Method:</strong><br>
                            <span>{{ $order->shipping_method }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Total Amount:</strong><br>
                            <span class="h5 text-success">{{ currency_icon() }}{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                    
                    <!-- Pay Now Button Section -->
                    @if(($order->payment_method == 'cash_on_delivery' || $order->payment_method == 'Cash on Delivery') && $order->payment_status == 0)
                    <hr>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Your order is set to Cash on Delivery. You can pay online now if you prefer.
                            </div>
                            <button type="button" class="btn btn-success btn-lg" id="payNowBtn" onclick="showPaymentMethods()">
                                <i class="fas fa-credit-card me-2"></i>Pay Now (Online Payment)
                            </button>
                            
                            <!-- Payment Methods Section (Initially Hidden) -->
                            <div id="paymentMethodsSection" class="mt-3" style="display: none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-payment me-2"></i>Choose Payment Method</h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="paymentForm" action="{{ route('payment.process') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ base64_encode(encrypt($order->id)) }}">
                                            
                                            <div class="payment-methods">
                                                @if(isset($stripe_setting) && $stripe_setting->status == 1)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe">
                                                    <label class="form-check-label d-flex align-items-center" for="stripe">
                                                        <img src="{{ asset('frontend/images/stripe.png') }}" alt="Stripe" class="me-2" style="height: 30px;">
                                                        <span>Credit/Debit Card (Stripe)</span>
                                                    </label>
                                                </div>
                                                @endif
                                                
                                                @if(isset($paypal_setting) && $paypal_setting->status == 1)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                                    <label class="form-check-label d-flex align-items-center" for="paypal">
                                                        <img src="{{ asset('frontend/images/paypal.png') }}" alt="PayPal" class="me-2" style="height: 30px;">
                                                        <span>PayPal</span>
                                                    </label>
                                                </div>
                                                @endif
                                                
                                                @if(isset($razorpay_setting) && $razorpay_setting->status == 1)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay">
                                                    <label class="form-check-label d-flex align-items-center" for="razorpay">
                                                        <img src="{{ asset('frontend/images/razorpay.png') }}" alt="Razorpay" class="me-2" style="height: 30px;">
                                                        <span>Razorpay</span>
                                                    </label>
                                                </div>
                                                @endif
                                                
                                                @if(isset($flutterwave_setting) && $flutterwave_setting->status == 1)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="flutterwave" value="flutterwave">
                                                    <label class="form-check-label d-flex align-items-center" for="flutterwave">
                                                        <img src="{{ asset('frontend/images/flutterwave.png') }}" alt="Flutterwave" class="me-2" style="height: 30px;">
                                                        <span>Flutterwave</span>
                                                    </label>
                                                </div>
                                                @endif
                                                
                                                @if(isset($mollie_setting) && $mollie_setting->status == 1)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="mollie" value="mollie">
                                                    <label class="form-check-label d-flex align-items-center" for="mollie">
                                                        <img src="{{ asset('frontend/images/mollie.png') }}" alt="Mollie" class="me-2" style="height: 30px;">
                                                        <span>Mollie</span>
                                                    </label>
                                                </div>
                                                @endif
                                                
                                                @if(isset($instamojo_setting) && $instamojo_setting->status == 1)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="instamojo" value="instamojo">
                                                    <label class="form-check-label d-flex align-items-center" for="instamojo">
                                                        <img src="{{ asset('frontend/images/instamojo.png') }}" alt="Instamojo" class="me-2" style="height: 30px;">
                                                        <span>Instamojo</span>
                                                    </label>
                                                </div>
                                                @endif
                                                
                                                @if(isset($paystack_setting) && $paystack_setting->status == 1)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="paystack" value="paystack">
                                                    <label class="form-check-label d-flex align-items-center" for="paystack">
                                                        <img src="{{ asset('frontend/images/paystack.png') }}" alt="Paystack" class="me-2" style="height: 30px;">
                                                        <span>Paystack</span>
                                                    </label>
                                                </div>
                                                @endif
                                                
                                                @if(isset($sslcommerz_setting) && $sslcommerz_setting->status == 1)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="sslcommerz" value="sslcommerz">
                                                    <label class="form-check-label d-flex align-items-center" for="sslcommerz">
                                                        <img src="{{ asset('frontend/images/sslcommerz.png') }}" alt="SSLCommerz" class="me-2" style="height: 30px;">
                                                        <span>SSLCommerz</span>
                                                    </label>
                                                </div>
                                                @endif
                                                
                                                @if(isset($bank_payment_setting) && $bank_payment_setting->status == 1)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="bank_payment" value="bank_payment">
                                                    <label class="form-check-label d-flex align-items-center" for="bank_payment">
                                                        <i class="fas fa-university me-2" style="font-size: 24px; color: #007bff;"></i>
                                                        <span>Bank Payment</span>
                                                    </label>
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-primary me-2" id="proceedPaymentBtn" disabled>
                                                    <i class="fas fa-credit-card me-1"></i>Proceed to Payment
                                                </button>
                                                <button type="button" class="btn btn-secondary" onclick="hidePaymentMethods()">
                                                    <i class="fas fa-times me-1"></i>Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Order Items ({{ $order->orderProducts->count() }} items)</h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderProducts as $orderProduct)
                        <div class="row align-items-center border-bottom py-3">
                            <div class="col-md-2">
                                <img src="{{ $orderProduct->product->thumb_image ?? '/frontend/images/products/default.jpg' }}" 
                                     alt="{{ $orderProduct->product_name }}" 
                                     class="img-fluid rounded" style="max-height: 80px;">
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1">{{ $orderProduct->product_name }}</h6>
                                <small class="text-muted">Category: {{ $orderProduct->product->category->name ?? 'N/A' }}</small>
                                @if($orderProduct->product_size)
                                    <br><small class="text-muted">Size: {{ $orderProduct->product_size }}</small>
                                @endif
                                @if($orderProduct->product_color)
                                    <br><small class="text-muted">Color: {{ $orderProduct->product_color }}</small>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>Qty: {{ $orderProduct->qty }}</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <span>{{ currency_icon() }}{{ number_format($orderProduct->product_price, 2) }}</span>
                                <br><small class="text-muted">per item</small>
                            </div>
                            <div class="col-md-2 text-end">
                                <strong>{{ currency_icon() }}{{ number_format($orderProduct->product_price * $orderProduct->qty, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            @php
                                $subtotal = $order->orderProducts->sum(function($item) { return $item->product_price * $item->qty; });
                                $shipping = $order->shipping_cost ?? 0;
                                $couponDiscount = $order->coupon_coast ?? 0;
                                $tax = 0; // No tax calculation
                                $total = $order->total_amount;
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
                                <strong class="text-success">{{ currency_icon() }}{{ number_format($total, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Addresses -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Billing Address</h6>
                        </div>
                        <div class="card-body">
                            @if($order->orderAddress && $order->orderAddress->billing_name)
                                <p class="mb-1"><strong>{{ $order->orderAddress->billing_name }}</strong></p>
                                <p class="mb-1">{{ $order->orderAddress->billing_address }}</p>
                                <p class="mb-1">{{ $order->orderAddress->billing_city }}, {{ $order->orderAddress->billing_state }}</p>
                                <p class="mb-1">{{ $order->orderAddress->billing_country }}</p>
                                @if($order->orderAddress->billing_phone)
                                    <p class="mb-1"><i class="fas fa-phone me-1"></i> {{ $order->orderAddress->billing_phone }}</p>
                                @endif
                                @if($order->orderAddress->billing_email)
                                    <p class="mb-0"><i class="fas fa-envelope me-1"></i> {{ $order->orderAddress->billing_email }}</p>
                                @endif
                            @else
                                <p class="text-muted">Billing address not available.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Shipping Address</h6>
                        </div>
                        <div class="card-body">
                            @if($order->orderAddress && !empty($order->orderAddress->shipping_name))
                                <p class="mb-1"><strong>{{ $order->orderAddress->shipping_name }}</strong></p>
                                @if($order->orderAddress->shipping_address)
                                    <p class="mb-1">{{ $order->orderAddress->shipping_address }}</p>
                                @endif
                                @if($order->orderAddress->shipping_city || $order->orderAddress->shipping_state)
                                    <p class="mb-1">{{ $order->orderAddress->shipping_city }}@if($order->orderAddress->shipping_city && $order->orderAddress->shipping_state), @endif{{ $order->orderAddress->shipping_state }}</p>
                                @endif
                                @if($order->orderAddress->shipping_country)
                                    <p class="mb-1">{{ $order->orderAddress->shipping_country }}</p>
                                @endif
                                @if($order->orderAddress->shipping_phone)
                                    <p class="mb-0"><i class="fas fa-phone me-1"></i> {{ $order->orderAddress->shipping_phone }}</p>
                                @endif
                            @else
                                <p class="text-muted">Shipping address not available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($order->order_notes)
            <!-- Order Notes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Order Notes</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->order_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary me-2">
                    <i class="fas fa-shopping-bag me-1"></i>Continue Shopping
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="fas fa-print me-1"></i>Print Order
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header {
        display: none !important;
    }
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>

<script>
function showPaymentMethods() {
    document.getElementById('paymentMethodsSection').style.display = 'block';
    document.getElementById('payNowBtn').style.display = 'none';
}

function hidePaymentMethods() {
    document.getElementById('paymentMethodsSection').style.display = 'none';
    document.getElementById('payNowBtn').style.display = 'block';
    // Reset form
    document.getElementById('paymentForm').reset();
    document.getElementById('proceedPaymentBtn').disabled = true;
}

// Enable proceed button when payment method is selected
document.addEventListener('DOMContentLoaded', function() {
    const proceedBtn = document.getElementById('proceedPaymentBtn');
    if (proceedBtn) {
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        paymentMethods.forEach(function(method) {
            method.addEventListener('change', function() {
                proceedBtn.disabled = false;
            });
        });
    }
});
</script>
@endsection