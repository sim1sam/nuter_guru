@php
    $setting = App\Models\Setting::first();
    $theme = \App\Helpers\ThemeHelper::variables($setting);
    $adminPrimary = $theme['button_color']; // Accent green — buttons / active menu
    $adminPrimaryDark = $theme['button_hover'];
    $adminPrimaryRgb = $theme['button_color_rgb'];
    $adminHighlight = $theme['primary']; // Orange — accents
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="shortcut icon"  href="{{ asset($setting->favicon) }}"  type="image/x-icon">
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  @yield('title')
  <title>{{__('admin.Login')}}</title>


  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
  <link href="{{ asset('backend/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-social.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/components.css') }}">
  @if ($setting->text_direction == 'rtl')
    <link rel="stylesheet" href="{{ asset('backend/css/rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/dev_rtl.css') }}">
    @endif
  <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap4-toggle.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/dev.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/tagify.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-tagsinput.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/fontawesome-iconpicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/datetimepicker/jquery.datetimepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/iziToast.min.css') }}">

  <script src="{{ asset('backend/js/jquery-3.7.0.min.js') }}"></script>
  @yield('style')
<style>
    .fade.in { opacity: 1 !important; }
    .tox .tox-promotion, .tox-statusbar__branding { display: none !important; }

    /* Admin theme — Website Settings → Theme Color */
    :root {
        --primary: {{ $adminPrimary }};
        --primary-dark: {{ $adminPrimaryDark }};
        --primary-rgb: {{ $adminPrimaryRgb }};
        --admin-highlight: {{ $adminHighlight }};
        --admin-soft: rgba({{ $adminPrimaryRgb }}, 0.12);
        --pos-primary: {{ $adminPrimary }};
        --pos-primary-dark: {{ $adminPrimaryDark }};
        --pos-primary-soft: rgba({{ $adminPrimaryRgb }}, 0.12);
    }

    a { color: var(--primary); }
    a:hover { color: var(--primary-dark); }

    .bg-primary { background-color: var(--primary) !important; }
    .text-primary,
    .text-primary-all *,
    .text-primary-all *:before,
    .text-primary-all *:after { color: var(--primary) !important; }

    .btn-primary,
    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active,
    .btn-primary:not(:disabled):not(.disabled):active,
    .btn-primary:not(:disabled):not(.disabled).active {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        box-shadow: 0 2px 6px rgba(var(--primary-rgb), 0.45) !important;
    }
    .btn-primary:hover,
    .btn-primary:focus {
        background-color: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
    }

    .btn-outline-primary {
        color: var(--primary) !important;
        border-color: var(--primary) !important;
    }
    .btn-outline-primary:hover,
    .btn-outline-primary:focus {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        color: #fff !important;
    }

    .card.card-primary { border-top: 2px solid var(--primary) !important; }
    .card.card-hero .card-header {
        background-image: none !important;
        background-color: var(--primary) !important;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: var(--primary) !important;
        color: #fff !important;
    }

    .sidebar-menu .active > a,
    .sidebar-menu li.active > a,
    .sidebar-menu .dropdown-menu li.active > a,
    .sidebar-menu li ul.dropdown-menu li.active > a {
        color: var(--primary) !important;
    }

    .navbar-bg { background-color: var(--primary) !important; }

    .page-item.active .page-link {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
    }
    .page-link { color: var(--primary); }

    .custom-switch-input:checked ~ .custom-switch-indicator,
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show > .nav-link {
        background-color: var(--primary) !important;
    }

    .badge-primary { background-color: var(--primary) !important; }

    .form-control:focus,
    .custom-select:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.2) !important;
    }

    body {
        background-color: {{ $theme['bg_elegant'] }} !important;
    }
</style>

</head>
