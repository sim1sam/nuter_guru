@extends('admin.master_layout')
@section('title')<title>{{__('admin.Receive Purchase')}}</title>@endsection
@section('admin-content')
<div class="main-content"><section class="section">
<div class="section-header"><h1>{{__('admin.Receive Purchase')}}</h1></div>
<div class="section-body"><div class="card"><div class="card-body">
<form action="{{ route('admin.purchase-receipt.store') }}" method="POST">@csrf
<div class="form-group"><label>{{ __('admin.Purchase Order') }} <span class="text-danger">*</span></label>
<select name="purchase_order_id" class="form-control select2" required onchange="location.href='?purchase_order_id='+this.value">
<option value="">{{__('admin.Select Purchase Order')}}</option>
@foreach($orders as $o)<option value="{{ $o->id }}" {{ optional($selectedOrder)->id==$o->id?'selected':'' }}>{{ $o->po_number }} - {{ $o->supplier->name }}</option>@endforeach
</select></div>
@if($selectedOrder)
<p><strong>{{__('admin.Warehouse')}}:</strong> {{ $selectedOrder->warehouse->name }}</p>
<table class="table"><thead><tr>
<th>{{__('admin.Product')}}</th>
<th>Type</th>
<th>{{__('admin.Unit')}}</th>
<th>{{__('admin.Pending')}}</th>
<th>{{__('admin.Receive Qty')}}</th>
</tr></thead><tbody>
@foreach($selectedOrder->items as $item)
@if($item->pendingQty() > 0)
@php
    $isKg = $item->product && $item->product->isKg();
    $baseLabel = $isKg ? 'KG' : __('admin.Pcs');
    $pending = $item->pendingQty();
@endphp
<tr>
<td>{{ $item->product->name }}<input type="hidden" name="item_id[]" value="{{ $item->id }}">
<small class="text-muted d-block">{{__('admin.Will add')}} {{ $isKg ? number_format($item->toBaseQty($pending), 3) : (int) $item->toBaseQty($pending) }} {{ $baseLabel }} {{__('admin.to stock')}}</small></td>
<td>
    @if($isKg)
        <span class="badge badge-info">KG</span>
    @else
        <span class="badge badge-secondary">PCS</span>
    @endif
</td>
<td>
    {{ $isKg ? 'KG' : $item->unitLabel() }}
    @if(! $isKg && \App\Models\Product::isPackUnit($item->unit))
        <small class="text-muted d-block">1 {{ $item->unitLabel() }} = {{ $item->pcs_per_box }} {{__('admin.Pcs')}}</small>
    @endif
</td>
<td>{{ $isKg ? rtrim(rtrim(number_format($pending, 3, '.', ''), '0'), '.') : $pending }} {{ $isKg ? 'KG' : $item->unitLabel() }}</td>
<td><input type="number" name="qty[]" class="form-control" min="0" step="{{ $isKg ? '0.001' : '1' }}" max="{{ $pending }}" value="{{ $pending }}"></td>
</tr>
@endif
@endforeach
</tbody></table>
<div class="form-group"><label>{{__('admin.Note')}}</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
<button type="submit" class="btn btn-success">{{__('admin.Post Receive & Add Stock')}}</button>
@endif
</form></div></div></div></section></div>
@endsection
