@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Sale Returns')}}</title>
@endsection
@section('admin-content')
<div class="main-content">
<section class="section">
<div class="section-header"><h1>{{__('admin.Sale Returns')}}</h1></div>
<div class="section-body">
<form method="GET" class="card mb-3"><div class="card-body row">
    <div class="col-md-4">
        <label>Order ID</label>
        <input type="text" name="order_id" class="form-control" value="{{ request('order_id') }}" placeholder="Order id / number">
    </div>
    <div class="col-md-2 align-self-end">
        <button class="btn btn-primary">Load</button>
    </div>
</div></form>

@if($order)
<form action="{{ route('admin.order-return.store') }}" method="POST" class="card">
@csrf
<input type="hidden" name="order_id" value="{{ $order->id }}">
<div class="card-body">
<p><strong>Order:</strong> {{ $order->order_id }}</p>
<div class="form-group">
    <label>Reason</label>
    <input type="text" name="reason" class="form-control">
</div>
<table class="table">
<thead><tr><th>Product</th><th>Variant</th><th>Sold Qty</th><th>Return Qty</th><th>Base Qty</th></tr></thead>
<tbody>
@foreach($order->orderProducts as $i => $op)
<tr>
    <td>{{ $op->product_name }}
        <input type="hidden" name="items[{{ $i }}][order_product_id]" value="{{ $op->id }}">
    </td>
    <td>{{ $op->variant_name_snapshot ?: '-' }}</td>
    <td>{{ $op->qty }}</td>
    <td><input type="number" step="0.001" min="0" max="{{ $op->qty }}" name="items[{{ $i }}][qty]" class="form-control" value="0"></td>
    <td>{{ $op->base_quantity ?? $op->qty }}</td>
</tr>
@endforeach
</tbody>
</table>
<button class="btn btn-primary">Post Return</button>
</div>
</form>
@endif
</div>
</section>
</div>
@endsection
