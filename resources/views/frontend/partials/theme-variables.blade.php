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
        --organic-green: {{ $theme['accent_green'] }};
        --organic-green-soft: {{ $theme['green_soft'] }};
        --organic-terracotta: {{ $theme['primary'] }};
        --organic-orange: {{ $theme['accent'] }};
        --organic-brown: {{ $theme['brand_brown'] }};
        --transition: all 0.3s ease;
    }
</style>
