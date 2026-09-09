@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Products Inventory')}}</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{__('admin.Products Inventory')}}</h1>
        </div>
        <div class="section-body">
            @include('admin.inventory.partials.barcode_print_toolbar', ['products' => $products])

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.inventory.barcode.search') }}" method="GET" class="form-inline mb-3">
                        <input type="text" name="barcode" class="form-control mr-2" placeholder="{{__('admin.Scan or enter barcode/SKU')}}" autofocus>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-barcode"></i> {{__('admin.Search')}}</button>
                    </form>
                    <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th width="40"></th>
                                    <th>{{__('admin.SN')}}</th>
                                    <th>{{__('admin.Name')}}</th>
                                    <th>{{__('admin.SKU')}}</th>
                                    <th>{{__('admin.Barcode')}}</th>
                                    <th>{{__('admin.Stock')}}</th>
                                    <th>{{__('admin.Pcs Per Box')}}</th>
                                    <th>{{__('admin.Low Stock Threshold')}}</th>
                                    <th>{{__('admin.Sold')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="barcode-product-check" value="{{ $product->id }}" {{ $product->barcode ? '' : 'disabled' }}>
                                    </td>
                                    <td>{{ ++$index }}</td>
                                    <td><a href="{{ route('admin.product.edit', $product->id) }}">{{ $product->short_name }}</a></td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->barcode ?: '-' }}</td>
                                    <td>
                                        @if($product->qty <= 0)
                                            <span class="badge badge-danger">{{ format_stock_qty($product->qty, $product) }}</span>
                                        @elseif($product->qty <= ($product->low_stock_threshold ?? 5))
                                            <span class="badge badge-warning">{{ format_stock_qty($product->qty, $product) }}</span>
                                        @else
                                            {{ format_stock_qty($product->qty, $product) }}
                                        @endif
                                    </td>
                                    <td>{{ max(1, (int) ($product->pcs_per_box ?? 1)) }}</td>
                                    <td>{{ $product->low_stock_threshold ?? 5 }}</td>
                                    <td>
                                        @php
                                            $sold = (float) ($product->total_sold ?? $product->sold_qty ?? 0);
                                        @endphp
                                        {{ abs($sold - round($sold)) < 0.0001 ? number_format($sold, 0) : number_format($sold, 2) }}
                                    </td>
                                    <td>
                                        <a class="btn btn-success btn-sm" href="{{ route('admin.stock-history', $product->id) }}"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('admin.inventory.partials.barcode_selection_script')
@endsection
