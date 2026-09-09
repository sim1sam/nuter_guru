@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Stock History')}}</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{__('admin.Stock History')}} - {{ $product->short_name }}</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p><strong>{{__('admin.SKU')}}:</strong> {{ $product->sku }}</p>
                            <p><strong>{{__('admin.Barcode')}}:</strong> {{ $product->barcode ?: '-' }}</p>
                            <p><strong>{{__('admin.Stock')}}:</strong> {{ format_stock_qty($product->qty, $product) }}</p>
                            <p><strong>{{__('admin.Product Unit Type')}}:</strong> {{ product_unit_label($product) }}</p>
                            @if($product->barcode)
                            <form action="{{ route('admin.inventory.barcode.print') }}" method="POST" target="_blank" class="mt-3">
                                @csrf
                                <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                <div class="form-group">
                                    <label>{{__('admin.Printer')}}</label>
                                    <select name="printer_type" class="form-control form-control-sm">
                                        <option value="label">{{__('admin.Label Printer')}}</option>
                                        <option value="a4">{{__('admin.A4 Printer')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Label Size')}}</label>
                                    <select name="label_size" class="form-control form-control-sm">
                                        <option value="50x30">50mm × 30mm ({{__('admin.Standard')}})</option>
                                        <option value="40x30">40mm × 30mm</option>
                                        <option value="50x25">50mm × 25mm</option>
                                        <option value="38x25">38mm × 25mm</option>
                                        <option value="58x40">58mm × 40mm</option>
                                        <option value="60x40">60mm × 40mm</option>
                                        <option value="100x50">100mm × 50mm</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Copies')}}</label>
                                    <input type="number" name="copies" class="form-control form-control-sm" value="1" min="1" max="100">
                                </div>
                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-print"></i> {{__('admin.Print Barcode')}}</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"><h4>{{__('admin.Warehouse Stock')}}</h4></div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead><tr><th>{{__('admin.Warehouse')}}</th><th>{{__('admin.Stock')}}</th></tr></thead>
                                <tbody>
                                    @forelse($warehouseStocks as $ws)
                                    <tr><td>{{ $ws->warehouse->name }}</td><td>{{ format_stock_qty($ws->qty, $product) }}</td></tr>
                                    @empty
                                    <tr><td colspan="2">{{__('admin.No data found')}}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.add-stock') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>{{__('admin.Product')}}</label>
                                        <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('admin.Warehouse')}}</label>
                                        <select name="warehouse_id" class="form-control">
                                            @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ $warehouse->is_default ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>{{__('admin.Stock In Quantity')}} ({{ product_unit_label($product) }})</label>
                                        <div class="input-group">
                                            <input type="number" name="stock_in" class="form-control" min="{{ $product->isKg() ? '0.001' : '1' }}" step="{{ $product->isKg() ? '0.001' : '1' }}" required>
                                            <div class="input-group-append"><span class="input-group-text">{{ product_unit_label($product) }}</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('admin.Note')}}</label>
                                        <input type="text" name="note" class="form-control">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">{{__('admin.Add Stock')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>{{__('admin.Stock Movements')}}</h4></div>
                        <div class="card-body">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>{{__('admin.SN')}}</th>
                                        <th>{{__('admin.Date')}}</th>
                                        <th>{{__('admin.Warehouse')}}</th>
                                        <th>{{__('admin.Type')}}</th>
                                        <th>{{__('admin.Stock')}}</th>
                                        <th>{{__('admin.Before')}}</th>
                                        <th>{{__('admin.After')}}</th>
                                        <th>{{__('admin.Note')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movements as $index => $movement)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $movement->warehouse->name ?? '-' }}</td>
                                        <td>{{ strtoupper($movement->type) }}</td>
                                        <td>{{ format_stock_qty($movement->qty, $product) }}</td>
                                        <td>{{ format_stock_qty($movement->qty_before, $product) }}</td>
                                        <td>{{ format_stock_qty($movement->qty_after, $product) }}</td>
                                        <td>{{ $movement->note }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if($histories->count())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>{{__('admin.Legacy Stock History')}}</h4></div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{__('admin.SN')}}</th>
                                        <th>{{__('admin.Stock')}}</th>
                                        <th>{{__('admin.Date')}}</th>
                                        <th>{{__('admin.Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($histories as $index => $history)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $history->stock_in }}</td>
                                        <td>{{ $history->created_at->format('H:ia d F, Y') }}</td>
                                        <td>
                                            <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $history->id }})"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
<script>
function deleteData(id){
    $("#deleteForm").attr("action",'{{ url("admin/delete-stock/") }}'+"/"+id)
}
</script>
@endsection
