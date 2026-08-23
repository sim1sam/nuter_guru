@extends('frontend.layouts.app')
@section('title', 'Login')

@section('meta')
<meta name="description" content="{{ __('Login') }}">
@endsection

@section('content')
<section class="organic-auth-page">
    <div class="container">
        <div class="organic-auth-card">
            <div class="organic-auth-card__brand">
                @if($setting && $setting->logo)
                    <img src="{{ asset($setting->logo) }}" alt="{{ config('app.name', 'Nuter Guru') }}">
                @endif
                <h1 class="organic-auth-card__title">{{ __('Welcome Back') }}</h1>
                <p class="organic-auth-card__subtitle">{{ __('Sign in to your account') }}</p>
            </div>

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
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

                <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="auth-forgot-link">{{ __('Forgot Password?') }}</a>
                </div>

                @if($googleRecaptcha->status == 1)
                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="{{ $googleRecaptcha->site_key }}"></div>
                    @error('g-recaptcha-response')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <button type="submit" class="btn btn-auth-submit login-btn">
                    <span class="btn-text">{{ __('Sign In') }}</span>
                    <span class="btn-loader d-none">
                        <i class="fas fa-spinner fa-spin"></i> {{ __('Signing In...') }}
                    </span>
                </button>

                @if($setting->enable_google_login == 1 || $setting->enable_facebook_login == 1)
                <div class="organic-auth-divider"><span>{{ __('Or continue with') }}</span></div>
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
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}">{{ __('Register') }}</a>
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

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('.login-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');

    btnText.classList.add('d-none');
    btnLoader.classList.remove('d-none');
    submitBtn.disabled = true;

    document.querySelectorAll('.organic-auth-card .text-danger').forEach(el => el.remove());

    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.showNotification) {
                showNotification(data.success, 'success');
            }
            setTimeout(function () {
                window.location.href = data.redirect;
            }, 700);
        } else if (data.error) {
            showAuthError(data.error);
        }
    })
    .catch(() => showAuthError('{{ __('An error occurred. Please try again.') }}'))
    .finally(() => {
        btnText.classList.remove('d-none');
        btnLoader.classList.add('d-none');
        submitBtn.disabled = false;
    });
});

function showAuthError(message) {
    if (window.showNotification) {
        showNotification(message, 'error');
        return;
    }
    alert(message);
}
</script>
@endsection
