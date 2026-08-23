@php
    use App\Helpers\ThemeHelper;

    $theme = ThemeHelper::variables($setting ?? null);
    $footerColor = $footer->footer_color ?? '#343a40';
@endphp
<style>
    :root {
        --primary-color: {{ $theme['primary'] }};
        --secondary-color: {{ $theme['secondary'] }};
        --accent-color: {{ $theme['accent'] }};
        --background-color: {{ $theme['background'] }};
        --text-dark: {{ $theme['text_dark'] }};
        --text-light: {{ $theme['text_light'] }};
        --bg-light: {{ $theme['bg_light'] }};
        --bg-elegant: {{ $theme['bg_elegant'] }};
        --border-color: {{ $theme['border_color'] }};
        --shadow: {{ $theme['shadow'] }};
        --gradient-bg: {{ $theme['gradient_bg'] }};
        --dark-purple: {{ $theme['dark_purple'] }};
        --soft-shadow: {{ $theme['soft_shadow'] }};
        --pearl-white: #ffffff;
        --light-purple: {{ $theme['light_purple'] }};
        --primary-rgb: {{ $theme['primary_rgb'] }};
        --statistics-color: {{ $theme['statistics_color'] }};
        --statistics-font-color: {{ $theme['statistics_font_color'] }};
        --navbar-menu-color: {{ $theme['navbar_menu_color'] }};
        --navbar-menu-active-color: {{ $theme['navbar_menu_active_color'] }};
        --navbar-bg-color: {{ $theme['navbar_bg_color'] }};
        --navbar-menu-rgb: {{ $theme['navbar_menu_rgb'] }};
        --footer-color: {{ $footerColor }};
        --brand-brown: {{ $theme['brand_brown'] }};
        --accent-green: {{ $theme['accent_green'] }};
        --accent-green-rgb: {{ $theme['accent_green_rgb'] }};
        --button-color: {{ $theme['button_color'] }};
        --button-hover-color: {{ $theme['button_hover'] }};
        --button-color-rgb: {{ $theme['button_color_rgb'] }};
        --newsletter-bg: {{ $theme['newsletter_bg'] }};
        --newsletter-bg-end: {{ $theme['newsletter_bg_end'] }};
        --newsletter-text: {{ $theme['newsletter_text'] }};
        --newsletter-btn: {{ $theme['newsletter_btn'] }};
        --link-color: {{ $theme['button_color'] }};
        --section-accent: {{ $theme['accent_green'] }};
        --organic-green: {{ $theme['accent_green'] }};
        --organic-green-soft: {{ $theme['green_soft'] }};
        --organic-terracotta: {{ $theme['primary'] }};
        --organic-orange: {{ $theme['accent'] }};
        --organic-brown: {{ $theme['brand_brown'] }};
        --selection-color: {{ $theme['primary'] }};
        --selection-color-rgb: {{ $theme['primary_rgb'] }};
        --bs-primary: {{ $theme['primary'] }};
        --bs-primary-rgb: {{ $theme['primary_rgb'] }};
        --bs-form-check-input-checked-bg-color: {{ $theme['primary'] }};
        --bs-form-check-input-checked-border-color: {{ $theme['primary'] }};
        --bs-form-check-input-focus-border: {{ $theme['primary'] }};
        --bs-form-check-input-focus-box-shadow: 0 0 0 0.25rem rgba({{ $theme['primary_rgb'] }}, 0.25);
        --bs-form-switch-bg: {{ $theme['primary'] }};
        --transition: all 0.3s ease;
    }

    .form-check-input:checked {
        background-color: var(--selection-color, var(--primary-color)) !important;
        border-color: var(--selection-color, var(--primary-color)) !important;
    }

    .form-check-input:focus {
        border-color: var(--selection-color, var(--primary-color)) !important;
        box-shadow: 0 0 0 0.25rem rgba(var(--selection-color-rgb, var(--primary-rgb)), 0.25) !important;
    }

    .form-switch .form-check-input:checked {
        background-color: var(--selection-color, var(--primary-color)) !important;
        border-color: var(--selection-color, var(--primary-color)) !important;
    }
</style>
