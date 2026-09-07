@php $icon = $setting->currency_icon ?? ''; @endphp
<div class="row">
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.all-order') }}">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary"><i class="fas fa-shopping-cart"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>{{__('admin.Total Order')}}</h4></div>
                <div class="card-body">{{ $stats['total'] }}</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.pending-order') }}">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning"><i class="fas fa-clock"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>{{__('admin.Pending Orders')}}</h4></div>
                <div class="card-body">{{ $stats['pending'] }}</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.pregress-order') }}">
        <div class="card card-statistic-1">
            <div class="card-icon bg-info"><i class="fas fa-spinner"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>{{__('admin.Processing Orders')}}</h4></div>
                <div class="card-body">{{ $stats['progress'] }}</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.shipment-order') }}">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary"><i class="fas fa-shipping-fast"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>{{__('admin.Shipment Orders')}}</h4></div>
                <div class="card-body">{{ $stats['shipment'] ?? 0 }}</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.delivered-order') }}">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success"><i class="fas fa-truck"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>{{__('admin.Delivered Orders')}}</h4></div>
                <div class="card-body">{{ $stats['delivered'] }}</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.declined-order') }}">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger"><i class="fas fa-undo"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>{{__('admin.Return Orders')}}</h4></div>
                <div class="card-body">{{ $stats['declined'] }}</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.cancelled-order') }}">
        <div class="card card-statistic-1">
            <div class="card-icon bg-dark"><i class="fas fa-ban"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>{{__('admin.Cancelled Orders')}}</h4></div>
                <div class="card-body">{{ $stats['cancelled'] ?? 0 }}</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary"><i class="fas fa-coins"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>{{__('admin.Total Earning')}}</h4></div>
                <div class="card-body">{{ $icon }}{{ number_format($stats['sales'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning"><i class="fas fa-money-bill"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>{{__('admin.Unpaid')}}</h4></div>
                <div class="card-body">{{ $stats['unpaid'] }} / {{ $icon }}{{ number_format($stats['unpaid_amount'], 2) }}</div>
            </div>
        </div>
    </div>
</div>
