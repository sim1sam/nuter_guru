@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Bulk Order Change')}}</title>
@endsection

@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Bulk Order Change') }}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Status Change')}}</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{route ('admin.pos.bulk.order.serch')}}" method="get">

                            <div class="row">
                                <div class="form-group col-6">
                                    <label>{{ __('admin.Form') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datetimepicker_mask" name="form" value="2025-09-14 14:57:00" autocomplete="off" required>
                                </div>
                                <div class="form-group col-6">
                                    <label>{{ __('admin.To') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datetimepicker_mask" name="to" value="2025-09-14 14:57:00" autocomplete="off" required>
                                </div>
                                <div class="form-group col-6">
                                    <label for="">{{ __('admin.Payment') }}</label>
                                    <select name="payment_status" id="" class="form-control">
                                        <option value="" disabled selected>{{ __('admin.Select a Payment Status') }}</option>
                                        <option value="0">{{ __('admin.Pending') }}</option>
                                        <option selected="" value="1">{{ __('admin.Success') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="">{{ __('admin.Order') }}</label>
                                    <select name="order_status" id="" class="form-control">
                                      <option value="" disabled selected>{{ __('admin.Select a Order Status') }}</option>
                                      <option value="0">{{ __('admin.Pending') }}</option>
                                      <option value="1">{{ __('admin.Processing') }}</option>
                                      <option value="5">{{ __('admin.Shipment') }}</option>
                                      <option value="2">{{ __('admin.Delivered') }}</option>
                                      <option value="3">{{ __('admin.Return') }}</option>
                                      <option value="4">{{ __('admin.Cancel') }}</option>
                                    </select>
                                  </div>
                            </div>
                            <button type="submit" class="btn btn-success">{{__('admin.Search Order')}}</button>
                        </form>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <form action="{{ route('admin.pos.bulk.order.status.change') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-10">
                                    <select name="newStatus" id="" class="form-control">
                                        <option value="" disabled selected>{{ __('admin.Select a Order Status') }}</option>
                                        <option value="0">{{ __('admin.Pending') }}</option>
                                        <option value="1">{{ __('admin.Processing') }}</option>
                                        <option value="5">{{ __('admin.Shipment') }}</option>
                                        <option value="2">{{ __('admin.Delivered') }}</option>
                                        <option value="3">{{ __('admin.Return') }}</option>
                                        <option value="4">{{ __('admin.Cancel') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-2">
                                    <button type="submit" class="btn btn-success">{{ __('admin.Update Order Status') }}</button>
                                </div>
                            </div>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%"><input type="checkbox" id="select-all-checkbox"></th>
                                        <th width="5%">{{__('admin.SN')}}</th>
                                        <th width="10%">{{__('admin.Customer')}}</th>
                                        <th width="10%">{{__('admin.Order Id')}}</th>
                                        <th width="10%">{{__('admin.Date')}}</th>
                                        <th width="10%">{{__('admin.Quantity')}}</th>
                                        <th width="10%">{{__('admin.Amount')}}</th>
                                        <th width="10%">{{__('admin.Order Status')}}</th>
                                        <th width="10%">{{__('admin.Payment')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($filteredOrders as $index => $order)
                                        <tr>
                                            <td><input type="checkbox" class="select-checkbox" name="orderIds[]" value="{{ $order->id }}"></td>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->order_id }}</td>
                                            <td>{{ $order->created_at->format('d F, Y') }}</td>
                                            <td>{{ $order->product_qty }}</td>
                                            <td>{{ $setting->currency_icon }}{{ round($order->total_amount) }}</td>
                                            <td>
                                                @if ($order->order_status == 1)
                                                <span class="badge badge-info">{{ order_status_label(1) }}</span>
                                                @elseif ($order->order_status == 5)
                                                <span class="badge badge-primary">{{ order_status_label(5) }}</span>
                                                @elseif ($order->order_status == 2)
                                                <span class="badge badge-success">{{ order_status_label(2) }}</span>
                                                @elseif ($order->order_status == 3)
                                                <span class="badge badge-danger">{{ order_status_label(3) }}</span>
                                                @elseif ($order->order_status == 4)
                                                <span class="badge badge-dark">{{ order_status_label(4) }}</span>
                                                @else
                                                <span class="badge badge-warning">{{ order_status_label(0) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->payment_status == 1)
                                                <span class="badge badge-success">{{__('admin.success')}} </span>
                                                @else
                                                <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </form>
                      </div>

                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

<script>
        $(document).ready(function () {
            // Handle "Select All" checkbox
            $('#select-all-checkbox').change(function () {
                var isChecked = $(this).prop('checked');
                $('input[type="checkbox"]').prop('checked', isChecked);
            });
        });
</script>



<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/delete-order/") }}'+"/"+id)
    }
</script>
@endsection
