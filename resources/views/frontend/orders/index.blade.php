@extends('frontend.layouts.account')

@section('title', __('My Orders'))

@section('account')
    <div class="account-page-header">
        <div>
            <h2>{{ __('My Orders') }}</h2>
            <p class="account-page-header__subtitle">{{ __('Track and manage your order history') }}</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-auto-sm">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Continue Shopping') }}
        </a>
    </div>

    @if($orders && $orders->count() > 0)
        <div class="account-card">
            <div class="account-card__body p-0">
                <div class="account-table-wrap d-none d-md-block">
                    <table class="table account-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Order ID') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Payment') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                @php
                                    $statusClass = 'secondary';
                                    $statusText = __('Unknown');
                                    if(isset($order->order_status)) {
                                        switch($order->order_status) {
                                            case 0: $statusClass = 'warning'; $statusText = __('Pending'); break;
                                            case 1: $statusClass = 'info'; $statusText = __('In Progress'); break;
                                            case 2: $statusClass = 'primary'; $statusText = __('Delivered'); break;
                                            case 3: $statusClass = 'success'; $statusText = __('Completed'); break;
                                            case 4: $statusClass = 'danger'; $statusText = __('Declined'); break;
                                        }
                                    } elseif(isset($order->status)) {
                                        $statusText = ucfirst($order->status);
                                        $statusClass = $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info');
                                    }

                                    $paymentStatusClass = 'secondary';
                                    $paymentStatusText = __('Unknown');
                                    if(isset($order->payment_status)) {
                                        switch($order->payment_status) {
                                            case 0: $paymentStatusClass = 'warning'; $paymentStatusText = __('Pending'); break;
                                            case 1: $paymentStatusClass = 'success'; $paymentStatusText = __('Paid'); break;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td><strong class="text-primary">#{{ $order->order_id ?? $order->id }}</strong></td>
                                    <td>
                                        <small class="text-muted d-block">{{ $order->created_at->format('M d, Y') }}</small>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td><span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span></td>
                                    <td><strong class="text-success">{{ format_currency($order->total_amount ?? $order->amount_real_currency ?? 0) }}</strong></td>
                                    <td><span class="badge bg-{{ $paymentStatusClass }}">{{ $paymentStatusText }}</span></td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>{{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="account-order-cards d-md-none p-3">
                    @foreach($orders as $order)
                        @include('frontend.partials.account-order-card', ['order' => $order, 'showPayment' => true])
                    @endforeach
                </div>
            </div>
        </div>

        @if(method_exists($orders, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <div class="account-card">
            <div class="account-card__body">
                <div class="account-empty">
                    <div class="account-empty__icon"><i class="fas fa-shopping-bag"></i></div>
                    <h4>{{ __('No Orders Found') }}</h4>
                    <p>{{ __('You haven\'t placed any orders yet. Start shopping to see your orders here!') }}</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>{{ __('Start Shopping') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
