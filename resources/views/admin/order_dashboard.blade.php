@extends('admin.master_layout')
@section('title')<title>{{__('admin.Order Dashboard')}}</title>@endsection
@section('admin-content')
@php $icon = $setting->currency_icon ?? ''; @endphp
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{__('admin.Order Dashboard')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('admin.Order Dashboard')}}</div>
            </div>
        </div>
        <div class="section-body">
            <form method="GET" class="form-inline mb-3">
                <label class="mr-2 mb-2">{{__('admin.From Date')}}</label>
                <input type="date" name="from_date" class="form-control mr-2 mb-2" value="{{ $from }}">
                <label class="mr-2 mb-2">{{__('admin.To Date')}}</label>
                <input type="date" name="to_date" class="form-control mr-2 mb-2" value="{{ $to }}">
                <button class="btn btn-primary mb-2 mr-2">{{__('admin.Filter')}}</button>
                <a href="{{ route('admin.order-dashboard') }}" class="btn btn-secondary mb-2 mr-2">{{__('admin.Reset')}}</a>
                <a href="{{ route('admin.all-order') }}" class="btn btn-info mb-2">{{__('admin.All Orders')}}</a>
            </form>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary"><i class="fas fa-calendar-day"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>{{__('admin.Today Order')}}</h4></div>
                            <div class="card-body">{{ $stats['today'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning"><i class="fas fa-clock"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>{{__('admin.Today Pending Order')}}</h4></div>
                            <div class="card-body">{{ $stats['today_pending'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success"><i class="fas fa-coins"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>{{__('admin.Today Earning')}}</h4></div>
                            <div class="card-body">{{ $icon }}{{ number_format($stats['today_sales'], 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('admin.cash-on-delivery') }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info"><i class="fas fa-hand-holding-usd"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>{{__('admin.Cash On Delivery')}}</h4></div>
                            <div class="card-body">{{ $stats['cod'] }}</div>
                        </div>
                    </div>
                    </a>
                </div>
            </div>

            @include('admin.partials.order_stats')

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{__('admin.Recent Orders')}}</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.all-order') }}" class="btn btn-primary">{{__('admin.All Orders')}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin.Order Id')}}</th>
                                            <th>{{__('admin.Customer')}}</th>
                                            <th>{{__('admin.Date')}}</th>
                                            <th>{{__('admin.Order Status')}}</th>
                                            <th>{{__('admin.Payment')}}</th>
                                            <th class="text-right">{{__('admin.Amount')}}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $order->user->name ?? '-' }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if ($order->order_status == 1)
                                            <span class="badge badge-info">{{ order_status_label(1) }}</span>
                                            @elseif ($order->order_status == 5)
                                            <span class="badge badge-primary">{{ order_status_label(5) }}</span>
                                            @elseif ($order->order_status == 2)
                                            <span class="badge badge-primary">{{ order_status_label(2) }}</span>
                                            @elseif ($order->order_status == 3)
                                            <span class="badge badge-danger">{{ order_status_label(3) }}</span>
                                            @elseif ($order->order_status == 4)
                                            <span class="badge badge-dark">{{ order_status_label(4) }}</span>
                                            @else
                                            <span class="badge badge-warning">{{ order_status_label(0) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->payment_status == 1)
                                            <span class="badge badge-success">{{__('admin.success')}}</span>
                                            @else
                                            <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">{{ $icon }}{{ number_format($order->total_amount, 2) }}</td>
                                        <td><a href="{{ route('admin.order-show', $order->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="7" class="text-center">{{__('admin.No data found')}}</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h4>{{__('admin.Top Selling Products')}}</h4></div>
                        <div class="card-body">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>{{__('admin.Product')}}</th>
                                        <th>{{__('admin.Sold')}}</th>
                                        <th class="text-right">{{__('admin.Amount')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($topProducts as $p)
                                <tr>
                                    <td>{{ $p->product_name }}</td>
                                    <td>{{ (int) $p->sold_qty }}</td>
                                    <td class="text-right">{{ number_format($p->sale_amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center">{{__('admin.No data found')}}</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3 font-weight-bold">
                                {{__('admin.Quantity')}}: {{ number_format($stats['qty']) }}
                                &nbsp;|&nbsp;
                                {{__('admin.Total Earning')}}: {{ $icon }}{{ number_format($stats['sales'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
