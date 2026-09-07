@extends('admin.master_layout')
@section('title')
<title>Steadfast Courier</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Steadfast Courier API</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                <div class="breadcrumb-item">Steadfast</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>API Settings</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                When an order is set to <strong>Processing</strong>, it is automatically created on Steadfast.
                                Get keys from your Steadfast merchant portal (API Settings).
                            </p>
                            <form action="{{ route('admin.steadfast.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label>Status</label>
                                    <div>
                                        @if ($setting->status == 1)
                                            <input type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Enable')}}" data-off="{{__('admin.Disable')}}" data-onstyle="success" data-offstyle="danger" name="status">
                                        @else
                                            <input type="checkbox" data-toggle="toggle" data-on="{{__('admin.Enable')}}" data-off="{{__('admin.Disable')}}" data-onstyle="success" data-offstyle="danger" name="status">
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>API Key <span class="text-danger">*</span></label>
                                    <input type="text" name="api_key" class="form-control" value="{{ old('api_key', $setting->api_key) }}" placeholder="Api-Key">
                                </div>
                                <div class="form-group">
                                    <label>Secret Key <span class="text-danger">*</span></label>
                                    <input type="text" name="secret_key" class="form-control" value="{{ old('secret_key', $setting->secret_key) }}" placeholder="Secret-Key">
                                </div>
                                <div class="form-group">
                                    <label>Base URL</label>
                                    <input type="text" name="base_url" class="form-control" value="{{ old('base_url', $setting->base_url) }}">
                                    <small class="text-muted">Default: https://portal.packzy.com/api/v1</small>
                                </div>
                                <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
