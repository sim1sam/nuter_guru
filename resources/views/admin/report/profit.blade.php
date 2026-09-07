@extends('admin.master_layout')
@section('title')<title>{{__('admin.Profit Report')}}</title>@endsection
@section('admin-content')
@include('admin.report.partials.filters')
@php $icon = $setting->currency_icon ?? ''; @endphp
<div class="main-content">
    <section class="section">
        <div class="section-header"><h1>{{__('admin.Profit Report')}}</h1></div>
        <div class="section-body">
            <form method="GET" class="form-inline mb-3 no-print">
                <input type="date" name="from_date" class="form-control mr-2 mb-2" value="{{ $from }}">
                <input type="date" name="to_date" class="form-control mr-2 mb-2" value="{{ $to }}">
                <button class="btn btn-primary mb-2 mr-2">{{__('admin.Filter')}}</button>
                <a href="{{ route('admin.report.profit') }}" class="btn btn-secondary mb-2 mr-2">{{__('admin.Reset')}}</a>
                <button type="button" class="btn btn-info mb-2 mr-2" onclick="window.print()"><i class="fa fa-print"></i> {{__('admin.Print')}}</button>
                @include('admin.report.partials.export_buttons', ['report' => 'profit'])
            </form>
            <div class="row report-summary">
                <div class="col-md-3"><div class="card card-statistic-1"><div class="card-icon bg-primary"><i class="fas fa-shopping-cart"></i></div>
                    <div class="card-wrap"><div class="card-header"><h4>{{__('admin.Sales')}}</h4></div><div class="card-body">{{ $icon }}{{ number_format($salesTotal, 2) }}</div></div></div></div>
                <div class="col-md-3"><div class="card card-statistic-1"><div class="card-icon bg-warning"><i class="fas fa-boxes"></i></div>
                    <div class="card-wrap"><div class="card-header"><h4>{{__('admin.Cost of Goods')}}</h4></div><div class="card-body">{{ $icon }}{{ number_format($cogs, 2) }}</div></div></div></div>
                <div class="col-md-3"><div class="card card-statistic-1"><div class="card-icon bg-info"><i class="fas fa-wallet"></i></div>
                    <div class="card-wrap"><div class="card-header"><h4>{{__('admin.Expense')}}</h4></div><div class="card-body">{{ $icon }}{{ number_format($expenseTotal, 2) }}</div></div></div></div>
                <div class="col-md-3"><div class="card card-statistic-1"><div class="card-icon bg-success"><i class="fas fa-chart-line"></i></div>
                    <div class="card-wrap"><div class="card-header"><h4>{{__('admin.Net Profit')}}</h4></div><div class="card-body">{{ $icon }}{{ number_format($netProfit, 2) }}</div></div></div></div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm mb-4" style="max-width:420px">
                        <tr><td>{{__('admin.Sales')}}</td><td class="text-right">{{ $icon }}{{ number_format($salesTotal, 2) }}</td></tr>
                        <tr><td>(-) {{__('admin.Cost of Goods')}}</td><td class="text-right">{{ $icon }}{{ number_format($cogs, 2) }}</td></tr>
                        <tr class="font-weight-bold"><td>{{__('admin.Gross Profit')}}</td><td class="text-right">{{ $icon }}{{ number_format($grossProfit, 2) }}</td></tr>
                        <tr><td>(-) {{__('admin.Expense')}}</td><td class="text-right">{{ $icon }}{{ number_format($expenseTotal, 2) }}</td></tr>
                        <tr class="font-weight-bold table-success"><td>{{__('admin.Net Profit')}}</td><td class="text-right">{{ $icon }}{{ number_format($netProfit, 2) }}</td></tr>
                    </table>
                    <h6>{{__('admin.Profit by Product')}}</h6>
                    <table class="table table-striped" id="dataTable">
                        <thead>
                            <tr>
                                <th>{{__('admin.Product')}}</th>
                                <th>{{__('admin.Sold')}}</th>
                                <th class="text-right">{{__('admin.Sales')}}</th>
                                <th class="text-right">{{__('admin.Cost of Goods')}}</th>
                                <th class="text-right">{{__('admin.Profit')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $p)
                        <tr>
                            <td>{{ $p->product_name }}@if(!empty($p->variant_name_snapshot)) <small class="text-muted">({{ $p->variant_name_snapshot }})</small>@endif</td>
                            <td>{{ $p->sold_qty }} @if(isset($p->base_qty)) <small class="text-muted">/ {{ number_format((float)$p->base_qty, 3) }} base</small>@endif</td>
                            <td class="text-right">{{ number_format($p->sale_amount, 2) }}</td>
                            <td class="text-right">{{ number_format($p->cost_amount, 2) }}</td>
                            <td class="text-right {{ $p->profit < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($p->profit, 2) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
