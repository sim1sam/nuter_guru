@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Stock Movements')}}</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header"><h1>{{__('admin.Stock Movements')}}</h1></div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="form-inline mb-3">
                        <select name="type" class="form-control mr-2">
                            <option value="">{{__('admin.All Types')}}</option>
                            <option value="in" {{ request('type')=='in'?'selected':'' }}>IN</option>
                            <option value="out" {{ request('type')=='out'?'selected':'' }}>OUT</option>
                            <option value="adjustment" {{ request('type')=='adjustment'?'selected':'' }}>ADJUSTMENT</option>
                        </select>
                        <select name="product_id" class="form-control mr-2 select2">
                            <option value="">{{__('admin.All Products')}}</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id')==$product->id?'selected':'' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">{{__('admin.Filter')}}</button>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('admin.Date')}}</th>
                                    <th>{{__('admin.Product')}}</th>
                                    <th>{{__('admin.Warehouse')}}</th>
                                    <th>{{__('admin.Type')}}</th>
                                    <th>{{__('admin.Reason')}}</th>
                                    <th>{{__('admin.Stock')}}</th>
                                    <th>{{__('admin.Before')}}</th>
                                    <th>{{__('admin.After')}}</th>
                                    <th>{{__('admin.Admin')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $movement->product->short_name ?? '-' }}</td>
                                    <td>{{ $movement->warehouse->name ?? '-' }}</td>
                                    <td>{{ strtoupper($movement->type) }}</td>
                                    <td>{{ $movement->reason }}</td>
                                    <td>{{ format_stock_qty($movement->qty, $movement->product) }}</td>
                                    <td>{{ format_stock_qty($movement->qty_before, $movement->product) }}</td>
                                    <td>{{ format_stock_qty($movement->qty_after, $movement->product) }}</td>
                                    <td>{{ $movement->admin->name ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="9" class="text-center">{{__('admin.No data found')}}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $movements->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
