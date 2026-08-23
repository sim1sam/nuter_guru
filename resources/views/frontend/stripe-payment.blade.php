<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fab fa-stripe me-2"></i>Stripe Payment</h4>
                    </div>
                    <div class="card-body">
                        <!-- Order Summary -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Order Summary</h6>
                            <p class="mb-1"><strong>Order ID:</strong> {{ $order->order_id ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>Total Amount:</strong> {{ format_currency($order->total_amount ?? 0) }}</p>
                        </div>

                        <!-- Billing Address (from order) -->
                        @if($order->orderAddress)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-address-card me-2"></i>Billing Address</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>{{ $order->orderAddress->billing_name ?? 'N/A' }}</strong></p>
                                <p class="mb-1">{{ $order->orderAddress->billing_address ?? 'N/A' }}</p>
                                <p class="mb-1">{{ $order->orderAddress->billing_city ?? '' }}, {{ $order->orderAddress->billing_state ?? '' }}</p>
                                <p class="mb-1">{{ $order->orderAddress->billing_country ?? '' }}</p>
                                @if($order->orderAddress->billing_phone)
                                <p class="mb-0"><i class="fas fa-phone me-1"></i> {{ $order->orderAddress->billing_phone }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Payment Form -->
                        <form action="{{ route('pay-with-stripe') }}" method="POST" id="stripe-form">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="card_number" class="form-label">
                                        <i class="fas fa-credit-card me-2"></i>Card Number *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('card_number') is-invalid @enderror" 
                                           id="card_number" 
                                           name="card_number" 
                                           value="{{ old('card_number') }}"
                                           placeholder="1234 5678 9012 3456"
                                           maxlength="19"
                                           required>
                                    @error('card_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="month" class="form-label">
                                        <i class="fas fa-calendar me-2"></i>Month *
                                    </label>
                                    <select class="form-control @error('month') is-invalid @enderror" 
                                            id="month" 
                                            name="month" 
                                            required>
                                        <option value="">Select Month</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}" {{ old('month') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                {{ sprintf('%02d', $i) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="year" class="form-label">
                                        <i class="fas fa-calendar me-2"></i>Year *
                                    </label>
                                    <select class="form-control @error('year') is-invalid @enderror" 
                                            id="year" 
                                            name="year" 
                                            required>
                                        <option value="">Select Year</option>
                                        @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                            <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="cvv" class="form-label">
                                        <i class="fas fa-lock me-2"></i>CVV *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('cvv') is-invalid @enderror" 
                                           id="cvv" 
                                           name="cvv" 
                                           value="{{ old('cvv') }}"
                                           placeholder="123"
                                           maxlength="4"
                                           required>
                                    @error('cvv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-shield-alt me-2"></i>
                                Your payment information is secure and encrypted. We use Stripe's secure payment processing.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('orders.show', $order->id ?? 1) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Order
                                </a>
                                <button type="submit" class="btn btn-success" id="pay-btn">
                                    <i class="fas fa-credit-card me-2"></i>Pay Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format card number input
        const cardNumberInput = document.getElementById('card_number');
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            if (formattedValue !== e.target.value) {
                e.target.value = formattedValue;
            }
        });

        // CVV input validation
        const cvvInput = document.getElementById('cvv');
        cvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // Form submission
        const form = document.getElementById('stripe-form');
        const payBtn = document.getElementById('pay-btn');
        
        form.addEventListener('submit', function(e) {
            payBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            payBtn.disabled = true;
        });
    });
    </script>
</body>
</html>