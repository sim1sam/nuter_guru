@extends('frontend.layouts.account')

@section('title', 'Order Details - #' . ($order->order_id ?? $order->id))

@section('account')
    <div class="account-page-header">
        <div>
            <h2>Order Details</h2>
            <p class="account-page-header__subtitle">Order #{{ $order->order_id ?? $order->id }}</p>
        </div>
        <a href="{{ route('orders') }}" class="btn btn-outline-secondary btn-auto-sm">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>

    <div class="account-detail-grid">
        <div>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Order Date:</strong><br>
                                    <span class="text-muted">{{ $order->created_at->format('F d, Y \\a\\t h:i A') }}</span></p>
                                    
                                    <p><strong>Order Status:</strong><br>
                                    @php
                                        $statusClass = 'secondary';
                                        $statusText = 'Unknown';
                                        
                                        if(isset($order->order_status)) {
                                            switch($order->order_status) {
                                                case 0:
                                                    $statusClass = 'warning';
                                                    $statusText = 'Pending';
                                                    break;
                                                case 1:
                                                    $statusClass = 'info';
                                                    $statusText = 'In Progress';
                                                    break;
                                                case 2:
                                                    $statusClass = 'primary';
                                                    $statusText = 'Delivered';
                                                    break;
                                                case 3:
                                                    $statusClass = 'success';
                                                    $statusText = 'Completed';
                                                    break;
                                                case 4:
                                                    $statusClass = 'danger';
                                                    $statusText = 'Declined';
                                                    break;
                                            }
                                        } elseif(isset($order->status)) {
                                            $statusText = ucfirst($order->status);
                                            $statusClass = $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info');
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} fs-6">{{ $statusText }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Payment Status:</strong><br>
                                    @php
                                        $paymentStatusClass = 'secondary';
                                        $paymentStatusText = 'Unknown';
                                        
                                        if(isset($order->payment_status)) {
                                            switch($order->payment_status) {
                                                case 0:
                                                    $paymentStatusClass = 'warning';
                                                    $paymentStatusText = 'Pending';
                                                    break;
                                                case 1:
                                                    $paymentStatusClass = 'success';
                                                    $paymentStatusText = 'Paid';
                                                    break;
                                            }
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $paymentStatusClass }} fs-6">{{ $paymentStatusText }}</span></p>
                                    
                                    @if(isset($order->payment_method))
                                    <p><strong>Payment Method:</strong><br>
                                    <span class="text-muted">{{ ucfirst($order->payment_method) }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Order Items</h5>
                        </div>
                        <div class="card-body p-0">
                            @if(isset($order->orderProducts) && $order->orderProducts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->orderProducts as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if(isset($item->product) && $item->product->thumb_image)
                                                        <img src="{{ asset($item->product->thumb_image) }}" 
                                                             alt="{{ $item->product->name ?? 'Product' }}" 
                                                             class="me-3" 
                                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->product->name ?? $item->product_name ?? 'Product' }}</h6>
                                                            @if(isset($item->variant_info))
                                                            <small class="text-muted">{{ $item->variant_info }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <strong>{{ format_currency($item->unit_price ?? $item->price ?? 0) }}</strong>
                                                </td>
                                                <td class="align-middle">
                                                    <span class="badge bg-light text-dark">{{ $item->qty ?? $item->quantity ?? 1 }}</span>
                                                </td>
                                                <td class="align-middle">
                                                    <strong class="text-success">
                                                        {{ format_currency(($item->unit_price ?? $item->price ?? 0) * ($item->qty ?? $item->quantity ?? 1)) }}
                                                    </strong>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-4 text-center text-muted">
                                    <i class="fas fa-box-open fa-2x mb-3"></i>
                                    <p>No items found for this order.</p>
                                </div>
                            @endif
                        </div>
                    </div>
        </div>

        <div>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Order Summary</h5>
                        </div>
                        <div class="card-body">
                            @if(isset($order->subtotal))
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>{{ format_currency($order->subtotal) }}</span>
                            </div>
                            @endif
                            
                            @if(isset($order->tax_amount) && $order->tax_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>{{ format_currency($order->tax_amount) }}</span>
                            </div>
                            @endif
                            
                            @if(isset($order->shipping_cost) && $order->shipping_cost > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>{{ format_currency($order->shipping_cost) }}</span>
                            </div>
                            @endif
                            
                            @if(isset($order->discount_amount) && $order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount:</span>
                                <span>-{{ format_currency($order->discount_amount) }}</span>
                            </div>
                            @endif
                            
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong class="text-success">
                                    {{ format_currency($order->total_amount ?? $order->amount_real_currency ?? 0) }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    @if(isset($order->orderAddress))
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $address = $order->orderAddress;
                            @endphp
                            @if($address && !empty($address->shipping_name))
                                <p class="mb-1"><strong>{{ $address->shipping_name }}</strong></p>
                                @if($address->shipping_address)
                                <p class="mb-1">{{ $address->shipping_address }}</p>
                                @endif
                                @if($address->shipping_city || $address->shipping_state || $address->shipping_zip)
                                <p class="mb-1">
                                    {{ $address->shipping_city ?? '' }}{{ ($address->shipping_city && $address->shipping_state) ? ', ' . $address->shipping_state : ($address->shipping_state ?? '') }} {{ $address->shipping_zip ?? '' }}
                                </p>
                                @endif
                                @if($address->shipping_country)
                                <p class="mb-0">{{ $address->shipping_country }}</p>
                                @endif
                                @if($address->shipping_phone)
                                <p class="mb-0"><strong>Phone:</strong> {{ $address->shipping_phone }}</p>
                                @endif
                            @else
                                <p class="text-muted mb-0">No shipping address available.</p>
                            @endif
                        </div>
                    </div>
                    @endif
        </div>
    </div>

            <!-- Pay Now Section -->
            @if($order->payment_status == 0)
                <div class="mt-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>{{ __('Complete Your Payment') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                @if($order->payment_method == 'Cash on Delivery' || $order->payment_method == 'cash_on_delivery')
                                    {{ __('Your order is currently set to Cash on Delivery. You can pay online now for faster processing.') }}
                                @else
                                    {{ __('Complete your payment to process your order faster.') }}
                                @endif
                            </div>

                            <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ base64_encode(encrypt($order->id)) }}">
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="mb-3">{{ __('Select Payment Method') }}</h6>
                                        <div id="payment-methods-container"> 
                                           {{-- Stripe --}}
                                            @if(isset($stripe_setting) && $stripe_setting->status == 1)
                                            <div class="form-check mb-3 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe">
                                                <label class="form-check-label w-100" for="stripe">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fab fa-stripe me-3 text-primary" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>Credit/Debit Card (Stripe)</strong>
                                                            <small class="d-block text-muted">Pay securely with your credit or debit card</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endif

                                            {{-- PayPal --}}
                                            @if(isset($paypal_setting) && $paypal_setting->status == 1)
                                            <div class="form-check mb-3 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                                <label class="form-check-label w-100" for="paypal">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fab fa-paypal me-3 text-primary" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>PayPal</strong>
                                                            <small class="d-block text-muted">Pay with your PayPal account</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endif

                                            {{-- Razorpay --}}
                                            @if(isset($razorpay_setting) && $razorpay_setting->status == 1)
                                            <div class="form-check mb-3 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay">
                                                <label class="form-check-label w-100" for="razorpay">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-credit-card me-3 text-success" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>Razorpay</strong>
                                                            <small class="d-block text-muted">Pay with cards, UPI, wallets & more</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endif

                                            {{-- Flutterwave --}}
                                            @if(isset($flutterwave_setting) && $flutterwave_setting->status == 1)
                                            <div class="form-check mb-3 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="payment_method" id="flutterwave" value="flutterwave">
                                                <label class="form-check-label w-100" for="flutterwave">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-credit-card me-3 text-warning" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>Flutterwave</strong>
                                                            <small class="d-block text-muted">Pay with cards, mobile money & bank transfers</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endif

                                            {{-- Mollie --}}
                                            @if(isset($mollie_setting) && $mollie_setting->mollie_status == 1)
                                            <div class="form-check mb-3 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="payment_method" id="mollie" value="mollie">
                                                <label class="form-check-label w-100" for="mollie">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-university me-3 text-info" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>Mollie</strong>
                                                            <small class="d-block text-muted">Pay with iDEAL, Bancontact, and more</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endif

                                            {{-- Instamojo --}}
                                            @if(isset($instamojo_setting) && $instamojo_setting->status == 1)
                                            <div class="form-check mb-3 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="payment_method" id="instamojo" value="instamojo">
                                                <label class="form-check-label w-100" for="instamojo">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-credit-card me-3 text-danger" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>Instamojo</strong>
                                                            <small class="d-block text-muted">Pay with cards, net banking & wallets</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endif

                                            {{-- Paystack --}}
                                            @if(isset($paystack_setting) && $paystack_setting->paystack_status == 1)
                                            <div class="form-check mb-3 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="payment_method" id="paystack" value="paystack">
                                                <label class="form-check-label w-100" for="paystack">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-credit-card me-3 text-success" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>Paystack</strong>
                                                            <small class="d-block text-muted">Pay with cards, bank transfers & USSD</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endif

                                            {{-- SSLCommerz --}}
                                            @if(isset($sslcommerz_setting) && $sslcommerz_setting->status == 1)
                                            <div class="form-check mb-3 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="payment_method" id="sslcommerz" value="sslcommerz">
                                                <label class="form-check-label w-100" for="sslcommerz">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-credit-card me-3 text-primary" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>SSLCommerz</strong>
                                                            <small class="d-block text-muted">Pay with cards, mobile banking & internet banking</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endif

                                            {{-- Bank Payment --}}
                                            @if(isset($bank_payment_setting) && $bank_payment_setting->status == 1)
                                            <div class="form-check mb-3 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="payment_method" id="bank_payment" value="bank_payment">
                                                <label class="form-check-label w-100" for="bank_payment">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-university me-3 text-secondary" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>Bank Transfer</strong>
                                                            <small class="d-block text-muted">Transfer directly to our bank account</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endif

                                            @php
                                                $hasActivePaymentMethod = (isset($stripe_setting) && $stripe_setting->status == 1) ||
                                                                         (isset($paypal_setting) && $paypal_setting->status == 1) ||
                                                                         (isset($razorpay_setting) && $razorpay_setting->status == 1) ||
                                                                         (isset($flutterwave_setting) && $flutterwave_setting->status == 1) ||
                                                                         (isset($mollie_setting) && $mollie_setting->mollie_status == 1) ||
                                                                         (isset($instamojo_setting) && $instamojo_setting->status == 1) ||
                                                                         (isset($paystack_setting) && $paystack_setting->paystack_status == 1) ||
                                                                         (isset($sslcommerz_setting) && $sslcommerz_setting->status == 1) ||
                                                                         (isset($bank_payment_setting) && $bank_payment_setting->status == 1);
                                            @endphp

                                            @if(!$hasActivePaymentMethod)
                                            <div class="alert alert-warning">
                                                No payment methods are currently available. Please contact support.
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="payment-summary p-3 bg-light rounded">
                                            <h6>{{ __('Payment Summary') }}</h6>
                                            <div class="d-flex justify-content-between">
                                                <span>{{ __('Total Amount') }}:</span>
                                                <strong>{{ format_currency($order->total_amount) }}</strong>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-success w-100" id="pay-now-btn">
                                                <i class="fas fa-credit-card me-2"></i>{{ __('Pay Now') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
@endsection

@push('account-styles')
<style>
.badge.fs-6 {
    font-size: 0.875rem !important;
    padding: 0.5rem 0.75rem;
}

.card {
    border: 1px solid #e3e6f0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fc;
}
</style>
@endpush

@push('account-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add form submission handler
    const paymentForm = document.getElementById('payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedMethod) {
                e.preventDefault();
                alert('Please select a payment method.');
                return false;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('pay-now-btn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endpush