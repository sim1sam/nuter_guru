@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Low Stock Alerts')}}</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header"><h1>{{__('admin.Low Stock Alerts')}}</h1></div>
        <div class="section-body">
            @include('admin.inventory.partials.barcode_print_toolbar', ['products' => $products])

            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="dataTable">
                        <thead>
                            <tr>
                                <th width="40"></th>
                                <th>{{__('admin.SN')}}</th>
                                <th>{{__('admin.Name')}}</th>
                                <th>{{__('admin.SKU')}}</th>
                                <th>{{__('admin.Barcode')}}</th>
                                <th>{{__('admin.Stock')}}</th>
                                <th>{{__('admin.Low Stock Threshold')}}</th>
                                <th>{{__('admin.Status')}}</th>
                                <th>{{__('admin.Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $product)
                            <tr>
                                <td>
                                    <input type="checkbox" class="barcode-product-check" value="{{ $product->id }}" {{ $product->barcode ? '' : 'disabled' }}>
                                </td>
                                <td>{{ ++$index }}</td>
                                <td>{{ $product->short_name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->barcode ?: '-' }}</td>
                                <td>
                                    @if($product->qty <= 0)
                                        <span class="badge badge-danger">{{ format_stock_qty($product->qty, $product) }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ format_stock_qty($product->qty, $product) }}</span>
                                    @endif
                                </td>
                                <td>{{ $product->low_stock_threshold ?? 5 }}</td>
                                <td>
                                    @if($product->qty <= 0)
                                        {{__('admin.Out of Stock')}}
                                    @else
                                        {{__('admin.Low Stock')}}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.inventory.stock-in') }}?product_id={{ $product->id }}" class="btn btn-primary btn-sm">{{__('admin.Stock In')}}</a>
                                    <a href="{{ route('admin.stock-history', $product->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@include('admin.inventory.partials.barcode_selection_script')
@endsection
