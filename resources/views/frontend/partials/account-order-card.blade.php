@php
    $statusClass = 'secondary';
    $statusText = 'Unknown';

    if (isset($order->order_status)) {
        switch ($order->order_status) {
            case 0: $statusClass = 'warning'; $statusText = 'Pending'; break;
            case 1: $statusClass = 'info'; $statusText = 'In Progress'; break;
            case 2: $statusClass = 'primary'; $statusText = 'Delivered'; break;
            case 3: $statusClass = 'success'; $statusText = 'Completed'; break;
            case 4: $statusClass = 'danger'; $statusText = 'Declined'; break;
        }
    } elseif (isset($order->status)) {
        $statusText = ucfirst($order->status);
        $statusClass = $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info');
    }

    $paymentStatusClass = 'secondary';
    $paymentStatusText = null;

    if (($showPayment ?? false) && isset($order->payment_status)) {
        switch ($order->payment_status) {
            case 0: $paymentStatusClass = 'warning'; $paymentStatusText = 'Pending'; break;
            case 1: $paymentStatusClass = 'success'; $paymentStatusText = 'Paid'; break;
        }
    }

    $total = $order->total_amount ?? $order->amount_real_currency ?? 0;
@endphp

<article class="account-order-card">
    <div class="account-order-card__head">
        <span class="account-order-card__id">#{{ $order->order_id ?? $order->id }}</span>
        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
    </div>
    <div class="account-order-card__row">
        <span>Date</span>
        <strong>{{ $order->created_at->format('M d, Y') }}</strong>
    </div>
    <div class="account-order-card__row">
        <span>Total</span>
        <strong>${{ number_format($total, 2) }}</strong>
    </div>
    @if ($paymentStatusText)
        <div class="account-order-card__row">
            <span>Payment</span>
            <span class="badge bg-{{ $paymentStatusClass }}">{{ $paymentStatusText }}</span>
        </div>
    @endif
    <div class="account-order-card__actions">
        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-eye me-1"></i> View Details
        </a>
    </div>
</article>
