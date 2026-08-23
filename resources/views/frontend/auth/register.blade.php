@extends('frontend.layouts.app')
@section('title', 'Register')

@section('meta')
<meta name="description" content="{{ __('Register') }}">
@endsection

@section('content')
<section class="organic-auth-page">
    <div class="container">
        <div class="organic-auth-card">
            <div class="organic-auth-card__brand">
                @if($setting && $setting->logo)
                    <img src="{{ asset($setting->logo) }}" alt="{{ config('app.name', 'Nuter Guru') }}">
                @endif
                <h1 class="organic-auth-card__title">{{ __('Create Account') }}</h1>
                <p class="organic-auth-card__subtitle">{{ __('Join us and start shopping fresh organic foods') }}</p>
            </div>

            <form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">
                        {{ __('Phone Number') }}
                        @if($setting->phone_number_required == 1)<span class="text-danger">*</span>@endif
                    </label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" @if($setting->phone_number_required == 1) required @endif>
                    @error('phone')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                    <div class="password-input-wrapper">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'passwordIcon')" aria-label="{{ __('Toggle password visibility') }}">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                    <div class="password-input-wrapper">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'confirmPasswordIcon')" aria-label="{{ __('Toggle password visibility') }}">
                            <i class="fas fa-eye" id="confirmPasswordIcon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                @if($googleRecaptcha->status == 1)
                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="{{ $googleRecaptcha->site_key }}"></div>
                    @error('g-recaptcha-response')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
                        <label class="form-check-label" for="agree">
                            {{ __('I agree to the') }}
                            <a href="{{ route('terms.conditions') }}" target="_blank">{{ __('Terms & Conditions') }}</a>
                            {{ __('and') }}
                            <a href="{{ route('privacy.policy') }}" target="_blank">{{ __('Privacy Policy') }}</a>
                            <span class="text-danger">*</span>
                        </label>
                    </div>
                    @error('agree')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-auth-submit register-btn">
                    <span class="btn-text">{{ __('Create Account') }}</span>
                    <span class="btn-loader d-none">
                        <i class="fas fa-spinner fa-spin"></i> {{ __('Creating Account...') }}
                    </span>
                </button>

                @if($setting->enable_google_login == 1 || $setting->enable_facebook_login == 1)
                <div class="organic-auth-divider"><span>{{ __('Or sign up with') }}</span></div>
                <div class="organic-auth-social">
                    @if($setting->enable_google_login == 1)
                    <a href="{{ route('login-google') }}" class="btn-social btn-social--google">
                        <i class="fab fa-google"></i> {{ __('Google') }}
                    </a>
                    @endif
                    @if($setting->enable_facebook_login == 1)
                    <a href="{{ route('login-facebook') }}" class="btn-social btn-social--facebook">
                        <i class="fab fa-facebook-f"></i> {{ __('Facebook') }}
                    </a>
                    @endif
                </div>
                @endif

                <p class="auth-switch">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('login') }}">{{ __('Sign In') }}</a>
                </p>
            </form>
        </div>
    </div>
</section>

@if($googleRecaptcha->status == 1)
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const passwordIcon = document.getElementById(iconId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

document.getElementById('registerForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('.register-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');

    btnText.classList.add('d-none');
    btnLoader.classList.remove('d-none');
    submitBtn.disabled = true;
});
</script>
@endsection
