@extends('frontend.layouts.account')

@section('title', 'Dashboard')

@section('account')
    {{-- Welcome --}}
    <div class="account-card account-welcome mb-3 mb-lg-4">
        <div class="account-card__body">
            <h1 class="account-welcome__title">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="account-welcome__text">Manage your account and track your orders from your dashboard.</p>
        </div>
    </div>

    {{-- Mobile quick links (dashboard home only — no duplicate tab bar) --}}
    <div class="account-quick-links d-lg-none mb-3">
        <a href="{{ route('profile') }}" class="account-quick-link">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <a href="{{ route('orders') }}" class="account-quick-link">
            <i class="fas fa-shopping-bag"></i>
            <span>Orders</span>
        </a>
        <a href="{{ route('wishlist') }}" class="account-quick-link">
            <i class="fas fa-heart"></i>
            <span>Wishlist</span>
        </a>
        <a href="{{ route('addresses.index') }}" class="account-quick-link">
            <i class="fas fa-map-marker-alt"></i>
            <span>Addresses</span>
        </a>
    </div>

    {{-- Stats --}}
    <div class="account-stats">
        <div class="account-stat-card">
            <div class="account-stat-card__icon text-primary"><i class="fas fa-shopping-bag"></i></div>
            <p class="account-stat-card__value">{{ $totalOrders ?? 0 }}</p>
            <p class="account-stat-card__label">Total Orders</p>
            <a href="{{ route('orders') }}" class="btn btn-outline-primary btn-sm">View Orders</a>
        </div>
        <div class="account-stat-card">
            <div class="account-stat-card__icon text-success"><i class="fas fa-check-circle"></i></div>
            <p class="account-stat-card__value">{{ $completedOrders ?? 0 }}</p>
            <p class="account-stat-card__label">Completed Orders</p>
            <a href="{{ route('orders') }}?status=3" class="btn btn-outline-success btn-sm">View Completed</a>
        </div>
        <div class="account-stat-card">
            <div class="account-stat-card__icon text-danger"><i class="fas fa-heart"></i></div>
            <p class="account-stat-card__value">{{ $wishlistCount ?? 0 }}</p>
            <p class="account-stat-card__label">Wishlist Items</p>
            <a href="{{ route('wishlist') }}" class="btn btn-outline-danger btn-sm">View Wishlist</a>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="account-card">
        <div class="account-card__header">
            <h2>Recent Orders</h2>
            <a href="{{ route('orders') }}" class="btn btn-primary btn-sm btn-auto-sm">View All</a>
        </div>
        <div class="account-card__body">
            @if(isset($recentOrders) && $recentOrders->count() > 0)
                <div class="account-table-wrap d-none d-md-block">
                    <table class="table account-table mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                @php
                                    $statusClass = 'secondary';
                                    $statusText = 'Unknown';
                                    switch($order->order_status) {
                                        case 0: $statusClass = 'warning'; $statusText = 'Pending'; break;
                                        case 1: $statusClass = 'info'; $statusText = 'In Progress'; break;
                                        case 2: $statusClass = 'primary'; $statusText = 'Delivered'; break;
                                        case 3: $statusClass = 'success'; $statusText = 'Completed'; break;
                                        case 4: $statusClass = 'danger'; $statusText = 'Declined'; break;
                                    }
                                @endphp
                                <tr>
                                    <td><strong>#{{ $order->order_id ?? $order->id }}</strong></td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td><span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span></td>
                                    <td>${{ number_format($order->total_amount ?? 0, 2) }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="account-order-cards d-md-none">
                    @foreach($recentOrders as $order)
                        @include('frontend.partials.account-order-card', ['order' => $order])
                    @endforeach
                </div>
            @else
                <div class="account-empty">
                    <div class="account-empty__icon"><i class="fas fa-shopping-bag"></i></div>
                    <h5>No orders yet</h5>
                    <p>Start shopping to see your orders here.</p>
                    <a href="{{ route('products') }}" class="btn btn-primary">Start Shopping</a>
                </div>
            @endif
        </div>
    </div>
@endsection
