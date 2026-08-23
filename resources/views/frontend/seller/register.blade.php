@extends('frontend.layouts.app')
@section('title')
<title>{{__('Seller Registration')}} || {{ $setting->sidebar_lg_header ?? config('app.name', 'Nuter Guru') }}</title>
@endsection
@section('meta')
<meta name="description" content="{{__('Join our marketplace as a seller')}}">
@endsection

@section('content')
<!-- Seller Registration Section Start -->
<section class="login-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="login-card">
                    <div class="login-header text-center mb-4">
                        <h2 class="login-title">{{__('Seller Registration')}}</h2>
                        <p class="login-subtitle">{{__('Join our marketplace as a seller')}}</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form id="sellerRegisterForm" method="POST" action="{{ route('seller.store-register') }}">
                        @csrf

                        <!-- Personal Information Section -->
                        <div class="registration-section mb-4">
                            <h5 class="section-title mb-3">{{__('Personal Information')}}</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">{{__('Full Name')}} <span class="text-danger">*</span></label>
                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">{{__('Email Address')}} <span class="text-danger">*</span></label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">{{__('Password')}} <span class="text-danger">*</span></label>
                                        <input id="password" type="password" class="form-control" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password_confirmation" class="form-label">{{__('Confirm Password')}} <span class="text-danger">*</span></label>
                                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">{{__('Phone Number')}} <span class="text-danger">*</span></label>
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div class="registration-section mb-4">
                            <h5 class="section-title mb-3">{{__('Address Information')}}</h5>
                            
                            <div class="form-group mb-3">
                                <label for="address" class="form-label">{{__('Address')}} <span class="text-danger">*</span></label>
                                    <textarea id="address" class="form-control" name="address" tabindex="6" rows="3">{{ old('address') }}</textarea>
                                </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="country_id" class="form-label">{{__('Country')}} <span class="text-danger">*</span></label>
                                        <select id="country_id" class="form-control" name="country_id" required>
                                            <option value="">{{__('Select Country')}}</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state_id" class="form-label">{{__('State')}} <span class="text-danger">*</span></label>
                                        <select id="state_id" class="form-control" name="state_id" required>
                                            <option value="">{{__('Select State')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="city_id" class="form-label">{{__('City')}} <span class="text-danger">*</span></label>
                                        <select id="city_id" class="form-control" name="city_id" required>
                                            <option value="">{{__('Select City')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shop Information Section -->
                        <div class="registration-section mb-4">
                            <h5 class="section-title mb-3">{{__('Shop Information')}}</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="shop_name" class="form-label">{{__('Shop Name')}} <span class="text-danger">*</span></label>
                                        <input id="shop_name" type="text" class="form-control" name="shop_name" value="{{ old('shop_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="shop_email" class="form-label">{{__('Shop Email')}} <span class="text-danger">*</span></label>
                                        <input id="shop_email" type="email" class="form-control" name="shop_email" value="{{ old('shop_email') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="shop_phone" class="form-label">{{__('Shop Phone')}} <span class="text-danger">*</span></label>
                                <input id="shop_phone" type="text" class="form-control" name="shop_phone" value="{{ old('shop_phone') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="shop_address" class="form-label">{{__('Shop Address')}} <span class="text-danger">*</span></label>
                                <textarea id="shop_address" class="form-control" name="shop_address" rows="3" required>{{ old('shop_address') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="open_at" class="form-label">{{__('Opening Time')}} <span class="text-danger">*</span></label>
                                        <input id="open_at" type="time" class="form-control" name="open_at" value="{{ old('open_at', '09:00') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="closed_at" class="form-label">{{__('Closing Time')}} <span class="text-danger">*</span></label>
                                        <input id="closed_at" type="time" class="form-control" name="closed_at" value="{{ old('closed_at', '18:00') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="form-label">{{__('Shop Description')}}</label>
                                <textarea id="description" class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="banner_image" class="form-label">{{__('Banner Image')}} <span class="text-danger">*</span></label>
                                <input id="banner_image" type="file" class="form-control" name="banner_image" accept="image/*" required>
                                <small class="form-text text-muted">{{__('Upload a banner image for your shop (JPG, PNG, GIF)')}}</small>
                            </div>
                        </div>

                        <!-- Terms and Submit -->
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="agree_terms_condition" name="agree_terms_condition" {{ old('agree_terms_condition') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="agree_terms_condition">
                                    {{__('I agree to the')}} <a href="{{ route('terms.conditions') }}" target="_blank">{{__('Terms and Conditions')}}</a> <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <button id="sellerRegisterBtn" type="submit" class="btn btn-primary btn-lg w-100">
                                <span class="btn-text">{{__('Register as Seller')}}</span>
                                <span class="btn-loading d-none">
                                    <i class="fas fa-spinner fa-spin"></i> {{__('Processing...')}}
                                </span>
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">{{__('Already have an account?')}} <a href="{{ route('seller.login') }}" class="text-primary">{{__('Login here')}}</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Seller Registration Section End -->

@endsection

@push('scripts')
<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            // Country, State, City dropdown functionality
            $("#country_id").on("change", function(e) {
                var country_id = e.target.value;
                if(country_id) {
                    $.get("{{ route('seller.state-by-country', ':id') }}".replace(':id', country_id), function(data) {
                        $("#state_id").empty();
                        $("#state_id").append('<option value="">{{__("Select State")}}</option>');
                        $.each(data, function(index, state) {
                            $("#state_id").append('<option value="' + state.id + '">' + state.name + '</option>');
                        });
                        // Clear city dropdown
                        $("#city_id").empty();
                        $("#city_id").append('<option value="">{{__("Select City")}}</option>');
                    });
                } else {
                    $("#state_id").empty().append('<option value="">{{__("Select State")}}</option>');
                    $("#city_id").empty().append('<option value="">{{__("Select City")}}</option>');
                }
            });

            $("#state_id").on("change", function(e) {
                var state_id = e.target.value;
                if(state_id) {
                    $.get("{{ route('seller.city-by-state', ':id') }}".replace(':id', state_id), function(data) {
                        $("#city_id").empty();
                        $("#city_id").append('<option value="">{{__("Select City")}}</option>');
                        $.each(data, function(index, city) {
                            $("#city_id").append('<option value="' + city.id + '">' + city.name + '</option>');
                        });
                    });
                } else {
                    $("#city_id").empty().append('<option value="">{{__("Select City")}}</option>');
                }
            });

            // Form submission
            $("#sellerRegisterForm").on('submit', function(e) {
                // Demo mode check
                var isDemo = "{{ env('APP_MODE') }}";
                if(isDemo == 'DEMO') {
                    e.preventDefault();
                    toastr.error('{{__("This Is Demo Version. You Can Not Change Anything")}}');
                    return;
                }

                // Disable submit button and show loading
                var $submitBtn = $("#sellerRegisterBtn");
                var $btnText = $submitBtn.find('.btn-text');
                var $btnLoading = $submitBtn.find('.btn-loading');
                
                $submitBtn.prop('disabled', true);
                $btnText.addClass('d-none');
                $btnLoading.removeClass('d-none');
                
                // Allow form to submit normally (no preventDefault)
                // The form will submit to the web route and handle redirect
            });
        });
    })(jQuery);
</script>
@endpush