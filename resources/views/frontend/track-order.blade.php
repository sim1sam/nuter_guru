@extends('frontend.layouts.app')

@section('title', __('Track Order'))
@section('meta_description', __('Track your order status by entering your order ID'))
@section('meta_keywords', __('track order, order status, shipping, delivery'))

@push('styles')
<style>
.track-hero {
    background: linear-gradient(135deg, var(--bg-elegant, #f8f9fa) 0%, var(--bg-light, #ffffff) 50%, rgba(var(--primary-rgb, 139, 123, 168), 0.12) 100%);
    padding: 64px 0 48px;
    text-align: center;
}
.track-section { padding: 40px 0 64px; }
.track-card {
    background: #fff;
    border: 1px solid rgba(var(--primary-rgb, 139, 123, 168), 0.15);
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    padding: 1.75rem;
}
.track-search-row {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.track-search-row .form-control {
    flex: 1 1 220px;
    min-height: 48px;
    border-radius: 10px;
    border: 2px solid #e9ecef;
}
.track-search-row .btn {
    min-height: 48px;
    border-radius: 10px;
    padding-inline: 1.25rem;
}
.track-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.95rem;
    font-weight: 600;
    padding: 0.45rem 0.9rem;
    border-radius: 999px;
}
.track-timeline {
    list-style: none;
    margin: 1.5rem 0 0;
    padding: 0;
    position: relative;
}
.track-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: #e9ecef;
}
.track-timeline li {
    position: relative;
    padding: 0 0 1.25rem 3rem;
}
.track-timeline li:last-child { padding-bottom: 0; }
.track-timeline .dot {
    position: absolute;
    left: 8px;
    top: 4px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #dee2e6;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}
.track-timeline li.is-done .dot {
    background: var(--primary-color, #8B7BA8);
    box-shadow: 0 0 0 2px var(--primary-color, #8B7BA8);
}
.track-timeline li.is-current .dot {
    background: var(--primary-color, #8B7BA8);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 139, 123, 168), 0.35);
}
.track-timeline .label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.15rem;
}
.track-timeline .meta {
    font-size: 0.85rem;
    color: #6c757d;
}
.track-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 1rem;
    margin-top: 1.25rem;
}
.track-meta-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 0.9rem 1rem;
}
.track-meta-item .k {
    display: block;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #6c757d;
    margin-bottom: 0.25rem;
}
.track-meta-item .v {
    font-weight: 600;
    color: #222;
    word-break: break-word;
}
.track-empty {
    text-align: center;
    padding: 2rem 1rem;
}
@media (max-width: 991.98px) {
    .track-hero { padding: 40px 0 28px; }
    .track-section { padding: 24px 0 40px; }
}
</style>
@endpush

@section('content')
@php
    $currency = $setting->currency_icon ?? '৳';
    $status = $order ? (int) $order->order_status : null;
    $steps = [
        ['key' => 0, 'label' => __('Pending')],
        ['key' => 1, 'label' => __('Processing')],
        ['key' => 5, 'label' => __('Shipment')],
        ['key' => 2, 'label' => __('Delivered')],
    ];
    if ($status === 3) {
        $steps[] = ['key' => 3, 'label' => __('Return')];
    } elseif ($status === 4) {
        $steps[] = ['key' => 4, 'label' => __('Cancel')];
    }
    $progressOrder = [0, 1, 5, 2];
    $currentIndex = array_search($status, $progressOrder, true);
    if ($currentIndex === false) {
        $currentIndex = -1;
    }
@endphp

<section class="track-hero">
    <div class="container">
        <h1 class="mb-2">{{ __('Track Order') }}</h1>
        <p class="lead text-muted mb-0">{{ __('Enter your order ID to see the latest delivery status') }}</p>
    </div>
</section>

<section class="track-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="track-card mb-4">
                    <form method="GET" action="{{ route('tracking.order') }}" class="track-search-row">
                        <input
                            type="text"
                            name="order_id"
                            class="form-control"
                            value="{{ $orderId }}"
                            placeholder="{{ __('Enter Order ID') }}"
                            required
                            autofocus
                            autocomplete="off"
                        >
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>{{ __('Track Now') }}
                        </button>
                    </form>
                </div>

                @if($searched && !$order)
                    <div class="track-card track-empty">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h4 class="mb-2">{{ __('Order not found') }}</h4>
                        <p class="text-muted mb-0">{{ __('Please check your order ID and try again.') }}</p>
                    </div>
                @endif

                @if($order)
                    <div class="track-card">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <div>
                                <div class="text-muted small">{{ __('Order ID') }}</div>
                                <h3 class="mb-0">#{{ $order->order_id }}</h3>
                            </div>
                            <span class="track-status-badge badge bg-{{ $status === 2 ? 'success' : ($status === 4 ? 'danger' : ($status === 3 ? 'warning' : 'primary')) }}">
                                <i class="fas fa-truck"></i>
                                {{ order_status_label($order->order_status) }}
                            </span>
                        </div>

                        <div class="track-meta-grid">
                            <div class="track-meta-item">
                                <span class="k">{{ __('Order Date') }}</span>
                                <span class="v">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '—' }}</span>
                            </div>
                            <div class="track-meta-item">
                                <span class="k">{{ __('Payment Status') }}</span>
                                <span class="v">{{ (int) $order->payment_status === 1 ? __('Paid') : __('Pending') }}</span>
                            </div>
                            <div class="track-meta-item">
                                <span class="k">{{ __('Total') }}</span>
                                <span class="v">{{ $currency }}{{ number_format((float) $order->total_amount, 2) }}</span>
                            </div>
                            @if(!empty($order->steadfast_tracking_code))
                                <div class="track-meta-item">
                                    <span class="k">{{ __('Tracking Number') }}</span>
                                    <span class="v">{{ $order->steadfast_tracking_code }}</span>
                                </div>
                            @endif
                            @if(!empty($order->steadfast_status))
                                <div class="track-meta-item">
                                    <span class="k">{{ __('Courier Status') }}</span>
                                    <span class="v">{{ $order->steadfast_status }}</span>
                                </div>
                            @endif
                            @if(!empty($order->shipping_method))
                                <div class="track-meta-item">
                                    <span class="k">{{ __('Shipping Method') }}</span>
                                    <span class="v">{{ $order->shipping_method }}</span>
                                </div>
                            @endif
                        </div>

                        <ul class="track-timeline" aria-label="{{ __('Order tracking progress') }}">
                            @foreach($steps as $index => $step)
                                @php
                                    $stepKey = $step['key'];
                                    $stepIndex = array_search($stepKey, $progressOrder, true);
                                    $isTerminal = in_array($status, [3, 4], true) && $stepKey === $status;
                                    $isDone = $isTerminal
                                        || ($stepIndex !== false && $currentIndex !== -1 && $stepIndex < $currentIndex)
                                        || ($stepKey === $status);
                                    $isCurrent = $stepKey === $status;
                                @endphp
                                <li class="{{ $isDone ? 'is-done' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
                                    <span class="dot" aria-hidden="true"></span>
                                    <div class="label">{{ $step['label'] }}</div>
                                    <div class="meta">
                                        @if($stepKey === 0 && $order->created_at)
                                            {{ $order->created_at->format('d M Y, h:i A') }}
                                        @elseif($stepKey === 1 && $order->order_approval_date)
                                            {{ \Carbon\Carbon::parse($order->order_approval_date)->format('d M Y, h:i A') }}
                                        @elseif($stepKey === 2 && $order->order_delivered_date)
                                            {{ \Carbon\Carbon::parse($order->order_delivered_date)->format('d M Y, h:i A') }}
                                        @elseif($stepKey === 3 && $order->order_completed_date)
                                            {{ \Carbon\Carbon::parse($order->order_completed_date)->format('d M Y, h:i A') }}
                                        @elseif($stepKey === 4 && $order->order_declined_date)
                                            {{ \Carbon\Carbon::parse($order->order_declined_date)->format('d M Y, h:i A') }}
                                        @elseif($isCurrent)
                                            {{ __('Current status') }}
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @if(isset($order->orderProducts) && $order->orderProducts->count() > 0)
                            <hr class="my-4">
                            <h5 class="mb-3">{{ __('Order Items') }}</h5>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Product') }}</th>
                                            <th class="text-center">{{ __('Qty') }}</th>
                                            <th class="text-end">{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderProducts as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->product_name ?? ($item->product->name ?? __('Product')) }}
                                                    @if(!empty($item->variant_name_snapshot))
                                                        <small class="text-muted d-block">{{ $item->variant_name_snapshot }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item->qty ?? 1 }}</td>
                                                <td class="text-end">{{ $currency }}{{ number_format((float) ($item->unit_price ?? 0) * (float) ($item->qty ?? 1), 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
