@extends('admin.master_layout')
@section('title')<title>{{__('admin.Purchase Order')}} {{ $order->po_number }}</title>@endsection
@section('admin-content')
<div class="main-content"><section class="section">
<div class="section-header"><h1>{{__('admin.Purchase Order')}}: {{ $order->po_number }}</h1></div>
<div class="section-body">
<div class="card mb-3"><div class="card-body">
<p><strong>{{__('admin.Supplier')}}:</strong> {{ $order->supplier->name }}</p>
<p><strong>{{__('admin.Warehouse')}}:</strong> {{ $order->warehouse->name }}</p>
<p><strong>{{__('admin.Status')}}:</strong> {{ strtoupper($order->status) }}</p>
<p><strong>{{__('admin.Total')}}:</strong> {{ number_format($order->total, 2) }}</p>
@if($order->status==='draft')<a href="{{ route('admin.purchase-order.submit', $order->id) }}" class="btn btn-primary">{{__('admin.Submit Order')}}</a>@endif
@if(in_array($order->status,['submitted','partial']))<a href="{{ route('admin.purchase-receipt.create', ['purchase_order_id'=>$order->id]) }}" class="btn btn-success">{{__('admin.Receive Stock')}}</a>@endif
<a href="{{ route('admin.purchase-return.create', ['purchase_order_id'=>$order->id]) }}" class="btn btn-warning">{{__('admin.Create RTV')}}</a>
</div></div>
<div class="card"><div class="card-header"><h4>{{__('admin.Order Items')}}</h4></div><div class="card-body"><table class="table table-striped"><thead><tr>
<th>{{__('admin.Product')}}</th>
<th>Type</th>
<th>{{__('admin.Unit')}} / Variant</th>
<th>{{__('admin.Ordered')}}</th>
<th>Base Qty</th>
<th>{{__('admin.Received')}}</th>
<th>{{__('admin.Returned')}}</th>
<th>{{__('admin.Pending')}}</th>
<th>{{__('admin.Purchase Price')}}</th>
</tr></thead><tbody>@foreach($order->items as $item)
@php
    $isKg = $item->product && $item->product->isKg();
    $baseLabel = $isKg ? 'KG' : __('admin.Pcs');
@endphp
<tr>
<td>{{ $item->product->name ?? '-' }}</td>
<td>
    @if($isKg)
        <span class="badge badge-info">KG</span>
    @else
        <span class="badge badge-secondary">PCS</span>
    @endif
</td>
<td>
    {{ $item->unitLabel() }}
    @if($item->variant_name)
        <small class="text-muted d-block">{{ $item->variant_name }}@if($item->unit_weight_kg) ({{ rtrim(rtrim(number_format((float)$item->unit_weight_kg, 3, '.', ''), '0'), '.') }} KG)@endif</small>
    @elseif(\App\Models\Product::isPackUnit($item->unit))
        <small class="text-muted d-block">1 {{ $item->unitLabel() }} = {{ $item->pcs_per_box }} {{__('admin.Pcs')}}</small>
    @endif
</td>
<td>{{ $isKg ? rtrim(rtrim(number_format((float)$item->ordered_qty, 3, '.', ''), '0'), '.') : (float) $item->ordered_qty }}</td>
<td>{{ $isKg ? number_format($item->toBaseQty($item->ordered_qty), 3) : (int) $item->toBaseQty($item->ordered_qty) }} {{ $baseLabel }}</td>
<td>{{ $isKg ? rtrim(rtrim(number_format((float)$item->received_qty, 3, '.', ''), '0'), '.') : (float) $item->received_qty }}</td>
<td>{{ $isKg ? rtrim(rtrim(number_format((float)$item->returned_qty, 3, '.', ''), '0'), '.') : (float) $item->returned_qty }}</td>
<td>{{ $isKg ? rtrim(rtrim(number_format($item->pendingQty(), 3, '.', ''), '0'), '.') : $item->pendingQty() }}</td>
<td>
    {{ number_format($item->unit_cost, 2) }} / {{ $item->unitLabel() }}
    @if($isKg && $item->unit_weight_kg)
        <br><small class="text-muted">{{ __('admin.Pc cost') }} / KG: {{ number_format($item->costPerPc(), 2) }}</small>
    @elseif(\App\Models\Product::isPackUnit($item->unit))
        <br><small class="text-muted">{{ __('admin.Pc cost') }}: {{ number_format($item->costPerPc(), 2) }}</small>
    @endif
</td>
</tr>@endforeach</tbody></table></div></div>
</div></section></div>
@endsection
