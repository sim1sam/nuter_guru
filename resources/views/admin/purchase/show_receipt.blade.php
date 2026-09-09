@extends('admin.master_layout')
@section('title')<title>{{__('admin.Purchase Receipt')}}</title>@endsection
@section('admin-content')
<div class="main-content"><section class="section">
<div class="section-header"><h1>{{__('admin.Purchase Receipt')}}: {{ $receipt->receipt_number }}</h1></div>
<div class="section-body"><div class="card"><div class="card-body">
<p><strong>{{__('admin.PO Number')}}:</strong> {{ $receipt->purchaseOrder->po_number }}</p>
<p><strong>{{__('admin.Supplier')}}:</strong> {{ $receipt->purchaseOrder->supplier->name }}</p>
<p><strong>{{__('admin.Warehouse')}}:</strong> {{ $receipt->warehouse->name }}</p>
<table class="table table-striped mt-3"><thead><tr>
<th>{{__('admin.Product')}}</th>
<th>Type</th>
<th>{{__('admin.Unit')}}</th>
<th>{{__('admin.Quantity')}}</th>
<th>{{__('admin.Base Qty')}}</th>
<th>{{__('admin.Unit Cost')}}</th>
</tr></thead>
<tbody>@foreach($receipt->items as $item)
@php
    $orderItem = $item->orderItem;
    $product = $item->product;
    $isKg = $product && $product->isKg();
    $baseLabel = $isKg ? 'KG' : 'PCS';
    $baseQty = $orderItem ? $orderItem->toBaseQty($item->received_qty) : $item->received_qty;
@endphp
<tr>
<td>{{ $product->name ?? '-' }}</td>
<td>
    @if($isKg)
        <span class="badge badge-info">KG</span>
    @else
        <span class="badge badge-secondary">PCS</span>
    @endif
</td>
<td>{{ optional($orderItem)->unitLabel() ?? ($isKg ? 'KG' : 'Pc') }}</td>
<td>{{ $isKg ? rtrim(rtrim(number_format((float)$item->received_qty, 3, '.', ''), '0'), '.') : (float) $item->received_qty }}</td>
<td>{{ $isKg ? number_format((float)$baseQty, 3) : (int) $baseQty }} {{ $baseLabel }}</td>
<td>{{ number_format($item->unit_cost,2) }}</td>
</tr>
@endforeach</tbody></table>
</div></div></div></section></div>
@endsection
