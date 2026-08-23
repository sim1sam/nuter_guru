<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-university me-2"></i>Bank Payment</h4>
                    </div>
                    <div class="card-body">
                        <!-- Order Summary -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Order Summary</h6>
                            <p class="mb-1"><strong>Order ID:</strong> {{ $order->order_id ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>Total Amount:</strong> {{ format_currency($order->total_amount ?? 0) }}</p>
                        </div>

                        <!-- Bank Information -->
                        @if($bankPayment && $bankPayment->account_info)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-bank me-2"></i>Bank Account Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="bank-info">
                                    {!! nl2br(e($bankPayment->account_info)) !!}
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Bank Information</h6>
                            <p class="mb-0">Bank account information is not configured. Please contact support.</p>
                        </div>
                        @endif

                        <!-- Payment Instructions -->
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Payment Instructions</h6>
                            <ol class="mb-0">
                                <li>Transfer the exact amount to the bank account details provided above</li>
                                <li>Enter your transaction ID or reference number below</li>
                                <li>Submit the form to complete your order</li>
                                <li>Your order will be processed after payment verification</li>
                            </ol>
                        </div>

                        <!-- Payment Form -->
                        <form action="{{ route('bank-payment') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="tnx_info" class="form-label">
                                    <i class="fas fa-receipt me-2"></i>Transaction ID / Reference Number *
                                </label>
                                <input type="text" 
                                       class="form-control @error('tnx_info') is-invalid @enderror" 
                                       id="tnx_info" 
                                       name="tnx_info" 
                                       value="{{ old('tnx_info') }}"
                                       placeholder="Enter your transaction ID or reference number"
                                       required>
                                @error('tnx_info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Please enter the transaction ID or reference number you received after making the bank transfer.
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('orders.show', $order->id ?? 1) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Order
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Submit Payment Information
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .bank-info {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        border-left: 4px solid #007bff;
        font-family: 'Courier New', monospace;
        white-space: pre-line;
    }
    </style>
</body>
</html>