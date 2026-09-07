@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Sale Returns')}}</title>
@endsection
@section('admin-content')
<div class="main-content">
<section class="section">
<div class="section-header">
    <h1>{{__('admin.Sale Returns')}}</h1>
    <div class="section-header-breadcrumb">
        <a href="{{ route('admin.order-return.create') }}" class="btn btn-primary">{{__('admin.Add New')}}</a>
    </div>
</div>
<div class="section-body">
<div class="card"><div class="card-body table-responsive">
<table class="table table-striped">
<thead>
<tr>
    <th>#</th>
    <th>{{__('admin.Return Number') ?? 'Return #'}}</th>
    <th>{{__('admin.Order')}}</th>
    <th>{{__('admin.Date')}}</th>
    <th>{{__('admin.Status')}}</th>
</tr>
</thead>
<tbody>
@forelse($returns as $return)
<tr>
    <td>{{ $return->id }}</td>
    <td>{{ $return->return_number }}</td>
    <td>{{ $return->order->order_id ?? $return->order_id }}</td>
    <td>{{ $return->return_date }}</td>
    <td>{{ $return->status }}</td>
</tr>
@empty
<tr><td colspan="5" class="text-center">{{__('admin.No data found') ?? 'No returns yet'}}</td></tr>
@endforelse
</tbody>
</table>
{{ $returns->links() }}
</div></div>
</div>
</section>
</div>
@endsection
