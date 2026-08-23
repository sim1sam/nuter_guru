@extends('frontend.layouts.app')

@section('content')
<div class="account-page">
    <div class="container account-page__container py-3 py-lg-5">
        <div class="account-page__grid">
            @include('frontend.partials.account-nav')
            <main class="account-page__main">
                @yield('account')
            </main>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/account-dashboard.css') }}?v={{ filemtime(public_path('frontend/css/account-dashboard.css')) }}">
@stack('account-styles')
@endpush

@push('scripts')
@stack('account-scripts')
@endpush
