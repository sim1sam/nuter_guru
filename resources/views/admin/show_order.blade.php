@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Invoice')}} #{{ $order->order_id }}</title>
@endsection

@section('style')
<style>
  .inv-wrap { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:28px 32px; color:#111827; }
  .inv-top { display:flex; justify-content:space-between; gap:24px; align-items:flex-start; margin-bottom:22px; }
  .inv-brand img { max-height:58px; width:auto; }
  .inv-brand h3 { margin:10px 0 4px; font-size:20px; font-weight:700; }
  .inv-brand p { margin:0; color:#6b7280; font-size:13px; line-height:1.5; }
  .inv-meta { text-align:right; }
  .inv-meta .inv-label { font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#6b7280; margin-bottom:4px; }
  .inv-meta .inv-no { font-size:24px; font-weight:700; color:#111827; margin-bottom:8px; }
  .inv-meta .inv-date { font-size:13px; color:#4b5563; }
  .inv-badge { display:inline-block; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:600; }
  .inv-badge-success { background:#dcfce7; color:#166534; }
  .inv-badge-danger { background:#fee2e2; color:#991b1b; }
  .inv-badge-warning { background:#fef3c7; color:#92400e; }
  .inv-badge-info { background:#dbeafe; color:#1e40af; }
  .inv-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px; margin:18px 0 8px; }
  .inv-card { background:#f9fafb; border:1px solid #eef2f7; border-radius:8px; padding:14px 16px; min-height:120px; }
  .inv-card h5 { margin:0 0 8px; font-size:12px; letter-spacing:.06em; text-transform:uppercase; color:#6b7280; font-weight:700; }
  .inv-card p { margin:0; font-size:14px; line-height:1.55; color:#111827; }
  .inv-card .muted { color:#6b7280; }
  .inv-table { width:100%; border-collapse:collapse; margin-top:18px; }
  .inv-table thead th { background:#111827; color:#fff; font-size:12px; text-transform:uppercase; letter-spacing:.04em; padding:12px 10px; font-weight:600; border:0; }
  .inv-table tbody td { padding:12px 10px; border-bottom:1px solid #e5e7eb; font-size:14px; vertical-align:middle; }
  .inv-table tbody tr:nth-child(even) { background:#fafafa; }
  .inv-totals { width:320px; margin-left:auto; margin-top:18px; }
  .inv-totals .row-line { display:flex; justify-content:space-between; padding:6px 0; font-size:14px; color:#374151; }
  .inv-totals .grand { border-top:2px solid #111827; margin-top:8px; padding-top:10px; font-size:18px; font-weight:700; color:#111827; }
  .inv-footer { margin-top:28px; padding-top:14px; border-top:1px dashed #d1d5db; display:flex; justify-content:space-between; gap:16px; font-size:12px; color:#6b7280; }
  .inv-actions { display:flex; flex-wrap:wrap; gap:8px; justify-content:flex-end; margin-bottom:14px; }
  .address-missing { color:#b45309; background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:10px 12px; margin-bottom:12px; }
  .qty-edit { display:inline-flex; }
  .qty-print { display:none; }
  @media print {
    @page { size: A4; margin: 12mm; }
    body { background:#fff !important; }
    .main-sidebar, .navbar, .section-header, .main-footer, .inv-actions,
    .order-status, .print-area, .additional_info, .modal, .qty-edit, .action-btn,
    .delete-icon, .custom_click, #sidebar-wrapper { display:none !important; }
    .qty-print { display:inline !important; }
    .main-content, .section, .section-body, .invoice { padding:0 !important; margin:0 !important; width:100% !important; max-width:100% !important; }
    .inv-wrap { border:0; border-radius:0; padding:0; box-shadow:none; }
    .inv-table thead th { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    a { color:#111 !important; text-decoration:none !important; }
  }
</style>
@endsection

@section('admin-content')
@php
  $orderAddress = $order->orderAddress;
  $hasAddress = $orderAddress && ($orderAddress->billing_name || $orderAddress->shipping_name);
  $sub_total = ($order->total_amount - $order->shipping_cost) + $order->coupon_coast;
  $companyName = $setting->sidebar_lg_header ?: 'Invoice';
@endphp
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>{{__('admin.Invoice')}}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.all-order') }}">{{__('admin.All Orders')}}</a></div>
        <div class="breadcrumb-item">#{{ $order->order_id }}</div>
      </div>
    </div>

    <div class="section-body">
      <div class="inv-actions print-area">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editAddressModal">
          <i class="fas fa-map-marker-alt"></i> {{ $hasAddress ? __('admin.Edit Order Address') : __('admin.Add Order Address') }}
        </button>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModalLong-3">
          {{__('Add Item') }}
        </button>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalLong-2">
          {{__('Add New Product') }}
        </button>
        <button class="btn btn-dark print_btn"><i class="fas fa-print"></i> {{__('admin.Print')}}</button>
        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" onclick="deleteOrder({{ $order->id }})">
          <i class="fas fa-trash"></i> {{__('admin.Delete')}}
        </button>
      </div>

      @if(!$hasAddress)
        <div class="address-missing print-area">
          <strong>{{ __('admin.No address found') }}.</strong>
          {{ __('admin.Please add billing and shipping address for this order.') }}
          <button type="button" class="btn btn-sm btn-warning ml-2" data-toggle="modal" data-target="#editAddressModal">{{ $hasAddress ? __('admin.Edit Order Address') : __('admin.Add Order Address') }}</button>
        </div>
      @endif

      <div class="invoice">
        <div class="invoice-print inv-wrap">
          <div class="inv-top">
            <div class="inv-brand">
              @if($setting->logo)
                <img src="{{ asset($setting->logo) }}" alt="logo">
              @endif
              <h3>{{ $companyName }}</h3>
              <p>
                @if(!empty($footer?->address)){{ $footer->address }}<br>@endif
                @if($setting->topbar_phone || !empty($footer?->phone)){{ __('admin.Phone') }}: {{ $setting->topbar_phone ?: $footer->phone }}<br>@endif
                @if($setting->topbar_email || $setting->contact_email || !empty($footer?->email)){{ __('admin.Email') }}: {{ $setting->topbar_email ?: ($setting->contact_email ?: $footer->email) }}@endif
              </p>
            </div>
            <div class="inv-meta">
              <div class="inv-label">{{__('admin.Invoice')}}</div>
              <div class="inv-no">#{{ $order->order_id }}</div>
              <div class="inv-date">{{__('admin.Date')}}: {{ $order->created_at->format('d M Y, h:i A') }}</div>
              <div class="mt-2">
                @if ($order->order_status == 1)
                  <span class="inv-badge inv-badge-info">{{ order_status_label(1) }}</span>
                @elseif ($order->order_status == 5)
                  <span class="inv-badge inv-badge-primary">{{ order_status_label(5) }}</span>
                @elseif ($order->order_status == 2)
                  <span class="inv-badge inv-badge-success">{{ order_status_label(2) }}</span>
                @elseif ($order->order_status == 3)
                  <span class="inv-badge inv-badge-danger">{{ order_status_label(3) }}</span>
                @elseif ($order->order_status == 4)
                  <span class="inv-badge inv-badge-dark">{{ order_status_label(4) }}</span>
                @else
                  <span class="inv-badge inv-badge-warning">{{ order_status_label(0) }}</span>
                @endif
                @if ($order->payment_status == 1)
                  <span class="inv-badge inv-badge-success">{{__('admin.Paid')}}</span>
                @else
                  <span class="inv-badge inv-badge-danger">{{__('admin.Unpaid')}}</span>
                @endif
              </div>
            </div>
          </div>

          <div class="inv-grid">
            <div class="inv-card">
              <h5>{{__('admin.Billing Information')}}</h5>
              @if($hasAddress)
                <p>
                  <strong>{{ $orderAddress->billing_name }}</strong><br>
                  @if($orderAddress->billing_email)<span class="muted">{{ $orderAddress->billing_email }}</span><br>@endif
                  @if($orderAddress->billing_phone)<span class="muted">{{ $orderAddress->billing_phone }}</span><br>@endif
                  {{ $orderAddress->billing_address }}<br>
                  <span class="muted">Bangladesh
                    @if($orderAddress->delivery_area)
                      · {{ $orderAddress->delivery_area === 'outside' ? __('admin.Outside') : __('admin.Inside') }}
                    @endif
                  </span>
                </p>
              @else
                <p class="muted">{{ __('admin.No address found') }}</p>
              @endif
            </div>
            <div class="inv-card">
              <h5>{{__('admin.Shipping Information')}}</h5>
              @if($hasAddress)
                <p>
                  <strong>{{ $orderAddress->shipping_name }}</strong><br>
                  @if($orderAddress->shipping_email)<span class="muted">{{ $orderAddress->shipping_email }}</span><br>@endif
                  @if($orderAddress->shipping_phone)<span class="muted">{{ $orderAddress->shipping_phone }}</span><br>@endif
                  {{ $orderAddress->shipping_address }}<br>
                  <span class="muted">Bangladesh
                    @if($orderAddress->delivery_area)
                      · {{ $orderAddress->delivery_area === 'outside' ? __('admin.Outside') : __('admin.Inside') }}
                    @endif
                  </span>
                </p>
              @else
                <p class="muted">{{ __('admin.No address found') }}</p>
              @endif
            </div>
            <div class="inv-card">
              <h5>{{__('admin.Payment Information')}}</h5>
              <p>
                {{__('admin.Method')}}: {{ $order->payment_method }}<br>
                {{__('admin.Status')}}:
                @if ($order->payment_status == 1)
                  <span class="inv-badge inv-badge-success">{{__('admin.Success')}}</span>
                @else
                  <span class="inv-badge inv-badge-danger">{{__('admin.Pending')}}</span>
                @endif<br>
                @if($order->transection_id){{__('admin.Transaction')}}: {{ $order->transection_id }}<br>@endif
                @if($order->payment_screenshot)
                  Payment Screenshot:<br>
                  <a href="{{ asset($order->payment_screenshot) }}" target="_blank">
                    <img src="{{ asset($order->payment_screenshot) }}" alt="Payment proof" style="max-width:220px;max-height:220px;border:1px solid #ddd;border-radius:6px;margin-top:6px;">
                  </a>
                  @if($order->payment_status == 0)
                    <br><small class="text-warning">Review screenshot, then set Payment = Success to approve.</small>
                  @endif
                @endif
              </p>
            </div>
            <div class="inv-card">
              <h5>{{__('admin.Order Information')}}</h5>
              <p>
                {{__('admin.Customer')}}: {{ optional($order->user)->name ?: ($orderAddress->billing_name ?? '-') }}<br>
                {{__('admin.Shipping')}}: {{ $order->shipping_method }}<br>
                {{__('admin.Items')}}: {{ $order->product_qty }}
                @if($order->steadfast_tracking_code || $order->steadfast_consignment_id)
                  <br><strong>Steadfast:</strong>
                  @if($order->steadfast_tracking_code) Tracking: {{ $order->steadfast_tracking_code }}@endif
                  @if($order->steadfast_consignment_id) · CID: {{ $order->steadfast_consignment_id }}@endif
                  @if($order->steadfast_status) · {{ $order->steadfast_status }}@endif
                @endif
              </p>
            </div>
          </div>

          <table class="inv-table">
            <thead>
              <tr>
                <th width="5%">#</th>
                <th>{{__('admin.Product')}}</th>
                <th>{{__('admin.Variant')}}</th>
                @if ($setting->enable_multivendor == 1)
                <th>{{__('admin.Shop Name')}}</th>
                @endif
                <th class="text-center">{{__('admin.Unit Price')}}</th>
                <th class="text-center">{{__('admin.Quantity')}}</th>
                <th class="text-right">{{__('admin.Total')}}</th>
                <th class="text-right action-btn print-area">{{__('admin.Action')}}</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($order->orderProducts as $index => $orderProduct)
                @php $totalVariant = $orderProduct->orderProductVariants->count(); @endphp
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $orderProduct->product_name }}</td>
                  <td>
                    @forelse ($orderProduct->orderProductVariants as $indx => $variant)
                      {{ $variant->variant_name.' : '.$variant->variant_value }}{{ $totalVariant == ($indx + 1) ? '' : ',' }}
                      @if(!$loop->last)<br>@endif
                    @empty
                      -
                    @endforelse
                  </td>
                  @if ($setting->enable_multivendor == 1)
                  <td>{{ optional($orderProduct->seller)->shop_name ?: '-' }}</td>
                  @endif
                  <td class="text-center">{{ $setting->currency_icon }}{{ number_format((float)$orderProduct->unit_price, 2) }}</td>
                  <td class="text-center">
                    <div class="input-group qty-edit justify-content-center print-area" style="max-width:140px;margin:0 auto;">
                      <div class="input-group-prepend">
                        <a href="{{ route('admin.order-quantity-decrement',[$orderProduct->id,$order->id]) }}" class="btn btn-outline-secondary">-</a>
                      </div>
                      <input type="text" class="form-control text-center" value="{{ $orderProduct->qty }}" readonly>
                      <div class="input-group-append">
                        <a href="{{ route('admin.order-quantity-increment',[$orderProduct->id,$order->id]) }}" class="btn btn-outline-secondary">+</a>
                      </div>
                    </div>
                    <span class="qty-print">{{ $orderProduct->qty }}</span>
                  </td>
                  <td class="text-right">{{ $setting->currency_icon }}{{ number_format((float)$orderProduct->unit_price * (int)$orderProduct->qty, 2) }}</td>
                  <td class="text-right delete-icon print-area">
                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $orderProduct->id}},{{$order->id }})"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center">{{ __('admin.No products found') }}</td></tr>
              @endforelse
            </tbody>
          </table>

          @if ($order->additional_info)
          <div class="mt-3 additional_info print-area">
            <h5>{{__('admin.Additional Information')}}</h5>
            <p>{!! clean(nl2br($order->additional_info)) !!}</p>
          </div>
          @endif

          <div class="row mt-3">
            <div class="col-lg-6 order-status print-area">
              <div class="section-title">{{__('admin.Order Status')}}</div>
              <form action="{{ route('admin.update-order-status',$order->id) }}" method="POST">
                @csrf
                @method("PUT")
                <div class="form-group">
                  <label>{{__('admin.Payment')}}</label>
                  <select name="payment_status" class="form-control">
                    <option {{ $order->payment_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                    <option {{ $order->payment_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Success')}}</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>{{__('admin.Order')}}</label>
                  <select name="order_status" class="form-control">
                    <option {{ $order->order_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                    <option {{ $order->order_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Processing')}}</option>
                    <option {{ (int)$order->order_status === 5 ? 'selected' : '' }} value="5">{{__('admin.Shipment')}}</option>
                    <option {{ $order->order_status == 2 ? 'selected' : '' }} value="2">{{__('admin.Delivered')}}</option>
                    <option {{ (int)$order->order_status === 3 ? 'selected' : '' }} value="3">{{__('admin.Return')}}</option>
                    <option {{ (int)$order->order_status === 4 ? 'selected' : '' }} value="4">{{__('admin.Cancel')}}</option>
                  </select>
                </div>
                <button class="btn btn-primary" type="submit">{{__('admin.Update Status')}}</button>
              </form>
            </div>
            <div class="col-lg-6">
              <div class="inv-totals">
                <div class="row-line"><span>{{__('admin.Subtotal')}}</span><span>{{ $setting->currency_icon }}{{ number_format((float)$sub_total, 2) }}</span></div>
                <div class="row-line"><span>{{__('admin.Discount')}} (-)</span><span>{{ $setting->currency_icon }}{{ number_format((float)$order->coupon_coast, 2) }}</span></div>
                <div class="row-line"><span>{{__('admin.Shipping')}}</span><span>{{ $setting->currency_icon }}{{ number_format((float)$order->shipping_cost, 2) }}</span></div>
                <div class="row-line grand"><span>{{__('admin.Total')}}</span><span>{{ $setting->currency_icon }}{{ number_format((float)$order->total_amount, 2) }}</span></div>
              </div>
            </div>
          </div>

          <div class="inv-footer">
            <div>{{ __('admin.Thank you for your order') }}</div>
            <div>{{ $companyName }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

{{-- Edit Address Modal --}}
@php
  $def = $customerDefaultAddress ?? null;
  $billAddress = old('billing_address', $orderAddress->billing_address ?? ($def->address ?? ''));
  $deliveryArea = old('delivery_area', $orderAddress->delivery_area ?? ($def->delivery_area ?? 'inside'));
@endphp
<div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.order-address.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">{{ $hasAddress ? __('admin.Edit Order Address') : __('admin.Add Order Address') }}</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small mb-2">{{ __('admin.This address is saved only for this order') }}</p>
          @if($def && !$hasAddress)
            <div class="alert alert-info py-2">{{ __('admin.Default address loaded') }}. {{ __('admin.You can edit address for this order only.') }}</div>
          @elseif(!$def && !$hasAddress)
            <div class="alert alert-warning py-2">{{ __('admin.No address found') }}. {{ __('admin.Please enter full address for this order.') }}</div>
          @endif
          <div class="form-group">
            <label>{{ __('admin.Country') }}</label>
            <input type="text" class="form-control" value="Bangladesh" readonly>
          </div>
          <div class="form-group">
            <label>{{ __('admin.Address') }} *</label>
            <textarea name="billing_address" class="form-control" rows="4" placeholder="{{ __('admin.House, road, area, landmark') }}" required>{{ $billAddress }}</textarea>
          </div>
          <div class="form-group mb-0">
            <label class="d-block">{{ __('admin.Delivery Area') }} *</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="delivery_area" id="orderAreaInside" value="inside" {{ $deliveryArea === 'outside' ? '' : 'checked' }}>
              <label class="form-check-label" for="orderAreaInside">{{ __('admin.Inside') }}</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="delivery_area" id="orderAreaOutside" value="outside" {{ $deliveryArea === 'outside' ? 'checked' : '' }}>
              <label class="form-check-label" for="orderAreaOutside">{{ __('admin.Outside') }}</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.Close') }}</button>
          <button type="submit" class="btn btn-primary">{{ __('admin.Save') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Add Product Modal --}}
<div class="modal fade" id="exampleModalLong-2" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-two modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{__('admin.Add New Product') }}</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="form-group col-12">
              <label>{{__('admin.Thumbnail Image Preview')}}</label>
              <div><img id="preview-img" class="admin-img" src="{{ asset('uploads/website-images/preview.png') }}" alt=""></div>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Thumnail Image')}} <span class="text-danger">*</span></label>
              <input type="file" class="form-control-file" name="thumb_image" onchange="previewThumnailImage(event)" required>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Short Name')}} <span class="text-danger">*</span></label>
              <input type="text" id="short_name" class="form-control" name="short_name" required>
            </div>
            <div class="form-group col-12">
              <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
              <input type="text" id="name" class="form-control" name="name" required>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
              <input type="text" id="slug" class="form-control" name="slug">
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
              <select name="category" class="form-control select2" id="category" required>
                <option value="">{{__('admin.Select Category')}}</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Sub Category')}}</label>
              <select name="sub_category" class="form-control select2" id="sub_category">
                <option value="">{{__('admin.Select Sub Category')}}</option>
              </select>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Child Category')}}</label>
              <select name="child_category" class="form-control select2" id="child_category">
                <option value="">{{__('admin.Select Child Category')}}</option>
              </select>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Brand')}}</label>
              <select name="brand" class="form-control select2" id="brand">
                <option value="">{{__('admin.Select Brand')}}</option>
                @foreach ($brands as $brand)
                  <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.SKU')}}</label>
              <input type="text" class="form-control" name="sku">
            </div>
            <div class="form-group col-6">
              <label>{{__('Price')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="price" required>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Offer Price')}}</label>
              <input type="text" class="form-control" name="offer_price">
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Stock Quantity')}} <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="quantity" required>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Weight')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="weight" required>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Short Description')}} <span class="text-danger">*</span></label>
              <textarea name="short_description" rows="5" class="form-control"></textarea>
            </div>
            <div class="form-group col-6">
              <label>{{__('admin.Long Description')}} <span class="text-danger">*</span></label>
              <textarea name="long_description" rows="5" class="form-control"></textarea>
            </div>
            <div class="form-group col-12">
              <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
              <select name="status" class="form-control" required>
                <option value="1">{{__('admin.Active')}}</option>
                <option value="0">{{__('admin.Inactive')}}</option>
              </select>
            </div>
          </div>
          <button class="btn btn-primary" type="submit">{{__('admin.Save') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Add Item to Order Modal --}}
<div class="modal fade" id="exampleModalLong-3" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{__('Add New Product in Order') }}</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.add-new-product-in-order',$order->id)}}" method="POST">
          @csrf
          <div class="form-group">
            <label>{{__('admin.Product')}} <span class="text-danger">*</span></label>
            <select name="product_id" class="form-control select2" required>
              <option value="">{{__('admin.Select Product')}}</option>
              @foreach($products as $product)
                <option value="{{$product->id}}">{{$product->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>{{__('admin.Quantity')}} <span class="text-danger">*</span></label>
            <input type="number" min="1" class="form-control" name="quantity" required>
          </div>
          <button class="btn btn-primary" type="submit">{{__('admin.Add Product') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function deleteOrder(id){
    $("#deleteForm").attr("action",'{{ url("admin/delete-order/") }}'+"/"+id)
  }
  function deleteData(id, order_id){
    $("#deleteForm").attr("action",'{{ url("admin/delete-order-product/") }}'+"/"+id+"/"+order_id)
  }
  function previewThumnailImage(event) {
    var reader = new FileReader();
    reader.onload = function(){ document.getElementById('preview-img').src = reader.result; }
    reader.readAsDataURL(event.target.files[0]);
  }
  function convertToSlug(Text){
    return Text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
  }
  (function($){
    "use strict";
    $(document).ready(function(){
      $(".print_btn").on("click", function(){
        window.print();
      });

      $("#name").on("focusout", function(){ $("#slug").val(convertToSlug($(this).val())); });
      $("#category").on("change", function(){
        var categoryId = $("#category").val();
        if(categoryId){
          $.get("{{url('/admin/subcategory-by-category/')}}/"+categoryId, function(response){
            $("#sub_category").html(response.subCategories);
            $("#child_category").html("<option value=''>{{__('admin.Select Child Category')}}</option>");
          });
        }
      });
      $("#sub_category").on("change", function(){
        var SubCategoryId = $("#sub_category").val();
        if(SubCategoryId){
          $.get("{{url('/admin/childcategory-by-subcategory/')}}/"+SubCategoryId, function(response){
            $("#child_category").html(response.childCategories);
          });
        }
      });
    });
  })(jQuery);
</script>
@endsection
