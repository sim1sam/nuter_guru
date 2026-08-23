@php
    $setting = App\Models\Setting::first();
    $footer = App\Models\Footer::first();
    $footerLinks1 = App\Models\FooterLink::where('column', 1)->get();
    $footerLinks2 = App\Models\FooterLink::where('column', 2)->get();
    $footerLinks3 = App\Models\FooterLink::where('column', 3)->get();
    $socialLinks = App\Models\FooterSocialLink::all();
    $categories = App\Models\Category::where('status', 1)->orderBy('name', 'asc')->get();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('frontend.partials.gtm_head')
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="{{ theme_variables($setting)['primary'] }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>@yield('title', isset($seoSetting) ? $seoSetting->seo_title : config('app.name', 'Nuter Guru'))</title>
    <meta name="description" content="@yield('meta_description', isset($seoSetting) ? $seoSetting->seo_description : 'Shop premium organic dry fruits, nuts, spices and healthy foods at Nuter Guru.')">
    <meta name="keywords" content="@yield('meta_keywords', isset($seoSetting) ? $seoSetting->seo_keywords : 'organic food, dry fruits, nuts, spices, healthy snacks, Nuter Guru')">
    
    @if(isset($seoSetting))
        @if($seoSetting->facebook_app_id)
        <meta property="fb:app_id" content="{{ $seoSetting->facebook_app_id }}">
        @endif
        
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="@yield('og_title', $seoSetting->seo_title)">
        <meta property="og:description" content="@yield('og_description', $seoSetting->seo_description)">
        <meta property="og:image" content="@yield('og_image', asset('frontend/images/og-image.jpg'))">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $seoSetting->seo_title }}">
        
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('twitter_title', $seoSetting->seo_title)">
        <meta name="twitter:description" content="@yield('twitter_description', $seoSetting->seo_description)">
        <meta name="twitter:image" content="@yield('twitter_image', asset('frontend/images/twitter-image.jpg'))">
        
        @if($seoSetting->google_analytics_id)
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $seoSetting->google_analytics_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $seoSetting->google_analytics_id }}');
        </script>
        @endif
        
        @if($seoSetting->facebook_pixel_id)
        <!-- Facebook Pixel -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $seoSetting->facebook_pixel_id }}');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $seoSetting->facebook_pixel_id }}&ev=PageView&noscript=1"/></noscript>
        @endif
    @endif
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $setting && $setting->favicon ? asset($setting->favicon) : asset('frontend/images/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ $setting && $setting->logo ? asset($setting->logo) : asset('frontend/pwa/icon-192.png') }}">

    <!-- PWA -->
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts — organic storefront (Bengali + Latin) -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}?v={{ filemtime(public_path('frontend/css/style.css')) }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/organic-theme.css') }}?v={{ filemtime(public_path('frontend/css/organic-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/mobile-app.css') }}?v={{ filemtime(public_path('frontend/css/mobile-app.css')) }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/pwa-install.css') }}?v={{ filemtime(public_path('frontend/css/pwa-install.css')) }}">
    
    <!-- Dynamic Theme Colors from Admin Settings -->
    @include('frontend.partials.theme-variables')

    <style>
        /* Enhanced Background Styling */
        body {
            background: var(--gradient-bg) !important;
            position: relative;
        }
        
        .main-wrapper {
            background: transparent;
        }
        
        /* Global Layout Fixes */
        html, body {
            overflow-x: hidden;
            width: 100%;
        }
        
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
            overflow-x: hidden;
        }

        :root {
            --site-header-height: 88px;
        }
        
        /* Header Navbar Layout Styles - Always Fixed */
        .main-header {
            border-bottom: 1px solid var(--border-color, #E5E3EB);
            width: 100%;
            overflow: visible !important;
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999 !important;
            background: var(--navbar-bg-color, #ffffff);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(var(--navbar-menu-rgb, var(--primary-rgb)), 0.12);
        }
        
        /* Prevent mobile action buttons from moving during menu toggle */
        .mobile-actions-container {
            transition: none !important;
            animation: none !important;
        }
        
        .navbar {
            z-index: 9998 !important;
            position: relative;
            overflow: visible !important;
        }
        
        /* Add padding to body to prevent content being hidden behind fixed navbar */
        body {
            padding-top: var(--site-header-height);
        }
        
        /* Ensure container doesn't restrict dropdown visibility */
        .container {
            overflow: visible !important;
        }
        
        /* Prevent any scrollbars on dropdown parent elements */
        .nav-item.dropdown {
            overflow: visible !important;
        }
        
        .navbar-nav .dropdown {
            position: relative;
            z-index: 9998 !important;
            overflow: visible !important;
        }
        
        .navbar-collapse {
            overflow: visible !important;
        }
        
        .navbar-nav {
            overflow: visible !important;
        }
        
        /* Dropdown Menu Styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            padding: 0.5rem 0;
            z-index: 10000 !important;
            position: absolute !important;
            display: none;
            background: #ffffff !important;
            min-width: 200px;
            overflow: visible !important;
            max-height: none !important;
        }
        

        
        /* Show dropdown when Bootstrap activates it */
        .dropdown.show .dropdown-menu {
            display: block !important;
        }
        
        /* Optional: show Categories dropdown on hover (desktop only) */
        @media (min-width: 992px) {
            .navbar-nav .dropdown:hover .dropdown-menu {
                display: block !important;
            }
        }
        
        /* Dropdown items styling */
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 400;
            color: #333333;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(var(--navbar-menu-rgb, var(--primary-rgb)), 0.1);
            color: var(--navbar-menu-active-color, var(--primary-color));
        }
        
        /* Navbar category dropdown — open below the link */
        .navbar-nav .dropdown .dropdown-menu {
            top: 100% !important;
            bottom: auto !important;
            left: 0 !important;
            right: auto !important;
            margin-top: 0.5rem !important;
            margin-bottom: 0 !important;
            transform: none !important;
            z-index: 10001 !important;
        }
        
        .navbar {
            padding: 0.85rem 0;
            align-items: center;
        }
        
        .navbar-brand {
            flex: 0 0 auto;
            margin-right: 1.5rem;
            max-width: none;
            display: flex;
            align-items: center;
        }
        
        .logo-img {
            width: auto;
            height: auto;
            max-height: 78px;
            max-width: 320px;
            display: block;
            object-fit: contain;
        }
        
        .logo-text {
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .navbar-nav > .nav-item > .nav-link,
        .navbar-nav > .nav-link {
            color: var(--navbar-menu-color, #333) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }
        
        .navbar-nav > .nav-item > .nav-link:hover,
        .navbar-nav > .nav-item > .nav-link.active,
        .navbar-nav > .nav-link:hover,
        .navbar-nav > .nav-link.active {
            color: var(--navbar-menu-active-color, var(--primary-color)) !important;
        }
        
        /* Default dropdown item active state */
        .dropdown-item.active {
            color: var(--navbar-menu-active-color, var(--primary-color)) !important;
            background-color: transparent !important;
        }
        
        /* Blog dropdown item active state uses secondary color */
        .dropdown-item.active[href*="blog"] {
            color: var(--secondary-color) !important;
            background-color: transparent !important;
        }
        
        .search-section {
            max-width: 250px;
            min-width: 180px;
        }
        
        /* Main Content Spacing */
        .main-content {
            margin-top: -20px;
            padding-top: 10px;
        }
        
        /* Responsive Main Content Spacing */
        @media (max-width: 768px) {
            .main-content {
                margin-top: -15px;
                padding-top: 8px;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                margin-top: -10px;
                padding-top: 5px;
            }
        }
        
        .search-section .form-control {
            width: 100%;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }
        
        .search-section .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
        }
        
        .header-actions .btn-link {
            padding: 0.5rem;
            transition: color 0.3s ease;
            text-decoration: none;
        }
        
        .header-actions .btn-link:hover {
            color: var(--primary-color) !important;
        }

        /* Mobile Devices */
        @media (max-width: 767px) {
            .navbar {
                padding: 0.75rem 0; /* slightly tighter */
                justify-content: space-between;
                position: relative; /* allow absolute-positioned mobile actions */
                padding-right: 80px; /* reserve space for cart on right */
                padding-left: 80px;  /* reserve space for menu on left */
                min-height: 54px; /* slimmer header */
            }
            
            .navbar-brand {
                flex: 0 0 auto;
                margin-right: 1rem;
                display: flex !important;
                align-items: center;
                justify-content: center; /* center the logo */
                width: 100%;
                padding: 0 60px; /* add padding to prevent overlap with action buttons */
            }
            
            .logo-img {
                max-width: 200px;
                max-height: 64px;
                height: auto;
                display: block !important;
            }
            
            .logo-text {
                font-size: 1.4rem;
                display: block !important;
                color: #2c3e50 !important;
                font-weight: 700;
            }
            
            .search-section {
                max-width: 180px;
                min-width: 120px;
                margin: 0.5rem 0;
                flex: 1 1 auto;
            }
            
            .header-actions {
                margin-top: 0.5rem;
                flex: 0 0 auto;
            }

            /* Mobile Actions Container - holds both menu and cart in same div */
            .mobile-actions-container {
                position: absolute !important;
                top: 50%;
                left: 0;
                right: 0;
                transform: translateY(-50%);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 0.75rem;
                z-index: 1051; /* higher than navbar */
                pointer-events: none; /* allow clicks to pass through container */
                transition: none !important; /* prevent any transitions */
            }
            
            /* Mobile menu button - positioned on left */
            .mobile-menu-btn {
                pointer-events: auto; /* re-enable clicks on button */
                transition: none !important;
            }
            
            /* Mobile cart button - positioned on right */
            .mobile-cart-btn {
                pointer-events: auto; /* re-enable clicks on button */
                transition: none !important;
            }
            
            /* Icon button styling for mobile actions */
            .mobile-menu-btn,
            .mobile-cart-btn {
                width: 36px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                /* removed circle styles for menu button via override below */
                border-radius: 50%;
                border: 1px solid #dee2e6;
                background: #fff;
                color: #2c3e50;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                transition: all 0.3s ease;
            }
            
            .mobile-menu-btn i,
            .mobile-cart-btn i { color: var(--primary-color); }
            .mobile-menu-btn:hover,
            .mobile-cart-btn:hover {
                color: var(--primary-color);
                border-color: var(--primary-color);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                transform: scale(1.05);
            }

            /* Themed hamburger icon using CSS bars */
            .mobile-actions-container .navbar-toggler-icon {
                width: 24px;
                height: 16px;
                position: relative;
                background: none !important;
                background-image: none !important;
            }
            .mobile-actions-container .navbar-toggler-icon::before,
            .mobile-actions-container .navbar-toggler-icon::after,
            .mobile-actions-container .navbar-toggler-icon > span {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                height: 2px;
                background-color: var(--primary-color);
                border-radius: 2px;
            }
            .mobile-actions-container .navbar-toggler-icon::before { top: 0; }
            .mobile-actions-container .navbar-toggler-icon > span { top: 7px; }
            .mobile-actions-container .navbar-toggler-icon::after { bottom: 0; }

            /* Close icon color to match theme */
            .mobile-actions-container .close-icon i {
                color: var(--primary-color) !important;
            }
            
            .mobile-menu-btn {
                padding: 0;
                border: 0 !important;
                background: transparent !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                width: auto;
                height: auto;
            }
            .mobile-menu-btn:hover {
                border-color: transparent !important;
                box-shadow: none !important;
                transform: none !important;
            }

            /* Remove circle on mobile cart button */
            .mobile-cart-btn {
                padding: 0;
                border: 0 !important;
                background: transparent !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                width: auto;
                height: auto;
            }
            .mobile-cart-btn:hover {
                border-color: transparent !important;
                box-shadow: none !important;
                transform: none !important;
            }

            /* Show badge unless explicitly hidden with d-none */
            .mobile-cart-btn .cart-count.d-none {
                display: none !important;
            }
        }

        /* Tablet and Small Desktop */
        @media (min-width: 768px) and (max-width: 1399px) {
            .navbar {
                padding: 1rem 0;
            }
            
            .navbar-brand {
                margin-right: 1rem;
            }
            
            .logo-img {
                max-width: 200px;
                max-height: 64px;
            }
            
            .logo-text {
                font-size: 1.4rem;
            }
            
            .search-section {
                max-width: 220px;
                min-width: 140px;
            }
        }
        
        /* Medium Desktop */
        @media (min-width: 1200px) and (max-width: 1399px) {
            .navbar {
                padding: 0.5rem 0;
            }
            
            .logo-img {
                /* max-width removed */
            }
            
            .logo-text {
                font-size: 1.8rem;
            }
        }
        
         /* Desktop Layout */
         @media (min-width: 992px) {
             .navbar-collapse {
                 display: flex !important;
                 align-items: center;
                 justify-content: flex-end;
                 flex: 1 1 auto;
                 min-width: 0;
                 gap: 0.75rem;
             }
             
             .navbar-brand {
                 margin-right: 1.25rem;
             }

             .navbar-nav {
                 flex-direction: row;
                 flex: 0 1 auto;
                 justify-content: center;
                 flex-wrap: nowrap;
                 white-space: nowrap;
                 margin: 0;
             }

             .navbar-nav > .nav-item > .nav-link,
             .navbar-nav > .nav-link {
                 padding: 0.5rem 0.75rem;
                 font-size: 0.9rem;
                 white-space: nowrap;
             }
             
             .search-section {
                 flex: 0 1 220px;
                 max-width: 220px;
                 min-width: 150px;
                 width: auto;
                 margin: 0;
             }

             .header-actions {
                 flex: 0 0 auto;
                 margin-left: 0;
                 white-space: nowrap;
             }
         }
         

           
           /* Large Desktop */
           @media (min-width: 1400px) and (max-width: 1659px) {
                .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
                    max-width: 1500px;
                }
                
                .navbar {
                    padding: 0.75rem 0;
                }
                
                .logo-img {
                    /* max-width removed */
                }
                
                .logo-text {
                    font-size: 2.5rem;
                }
            }
            
            /* Ultra Wide Desktop */
            @media (min-width: 1660px) {
                .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
                    max-width: 1550px;
                }
                
                .navbar {
                    padding: 0.75rem 0;
                }
                
                .logo-img {
                    /* max-width removed */
                }
                
                .logo-text {
                    font-size: 3rem;
                }
            }
        
        /* Mobile Layout */
        @media (max-width: 991.98px) {
            :root {
                --site-header-height: 56px;
            }

            body {
                padding-top: var(--site-header-height);
            }

            .navbar-collapse {
                margin-top: 1rem;
                position: relative;
                z-index: 1000; /* below action buttons */
            }
            
            .navbar-nav {
                margin-bottom: 1rem;
            }
            
            .search-section {
                margin-bottom: 1rem;
                width: 100%;
                max-width: none;
            }
            
            .header-actions {
                justify-content: center;
            }
            
            /* Ensure mobile action buttons stay fixed during menu toggle */
            .mobile-actions-container {
                position: absolute !important;
                z-index: 1051 !important;
                transition: none !important;
            }
            
            /* Prevent navbar collapse from affecting action button positions */
            .navbar-collapse.show ~ .mobile-actions-container {
                position: absolute !important;
            }
            
            /* Ensure main header doesn't change height during menu toggle */
            .main-header {
                height: auto !important;
                min-height: 80px !important;
            }
            
            /* Mobile dropdown adjustments - Fix overlapping menus */
            .dropdown-menu {
                position: static !important;
                float: none !important;
                width: 100% !important;
                margin-top: 0 !important;
                margin-bottom: 0.5rem !important;
                border: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                background: transparent !important;
                display: none !important;
                transform: none !important;
                left: auto !important;
                right: auto !important;
                top: auto !important;
                bottom: auto !important;
                z-index: 1000 !important;
            }
            
            /* Show dropdown when toggled - ensure only the clicked dropdown shows */
            .dropdown.show .dropdown-menu {
                display: block !important;
            }
            
            /* Hide all other dropdowns when one is shown */
            .navbar-nav .dropdown:not(.show) .dropdown-menu {
                display: none !important;
            }
            
            /* Ensure proper spacing between mobile dropdown containers */
            .navbar-nav .dropdown {
                margin-bottom: 0.5rem !important;
                position: relative !important;
            }
            
            /* Ensure each dropdown is independent */
            .navbar-nav .dropdown .dropdown-menu {
                position: static !important;
                width: 100% !important;
                box-shadow: none !important;
                border: 0 !important;
                background: transparent !important;
            }
            
            /* Fix navbar collapse spacing on mobile */
            .navbar-collapse {
                margin-top: 1rem;
                overflow: visible !important;
            }
            
            /* Ensure dropdown items are properly styled */
            .dropdown-item {
                padding: 0.75rem 1rem !important;
                color: #333 !important;
                border-bottom: 1px solid #f8f9fa !important;
                display: block !important;
                width: 100% !important;
                text-align: left !important;
            }
            
            .dropdown-item:last-child {
                border-bottom: none !important;
            }
            
            .dropdown-item:hover {
                background-color: #f8f9fa !important;
                color: #007bff !important;
            }
            
            /* Ensure dropdown toggles work properly on mobile */
            .navbar-nav .nav-link.dropdown-toggle {
                position: relative !important;
                display: block !important;
                width: 100% !important;
                text-align: left !important;
                padding: 0.75rem 1rem !important;
                border: none !important;
                background: transparent !important;
            }
            
            .navbar-nav .nav-link.dropdown-toggle::after {
                float: right !important;
                margin-top: 0.375rem !important;
            }

            /* Keep menu + cart on the same line on tablet/mobile */
            .navbar {
                position: relative;
                padding-right: 80px; /* reserve space for cart on right */
                padding-left: 80px;  /* reserve space for menu on left */
                min-height: 54px; /* match slimmer mobile header */
            }
            
            .navbar-brand {
                justify-content: center;
                width: 100%;
                padding: 0 60px;
            }
            
            /* Mobile Actions Container - tablet/mobile */
            .mobile-actions-container {
                position: absolute !important;
                top: 50%;
                left: 0;
                right: 0;
                transform: translateY(-50%);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 1rem;
                z-index: 1051; /* higher than navbar */
                pointer-events: none; /* allow clicks to pass through container */
                transition: none !important; /* prevent any transitions */
            }
            
            .mobile-menu-btn,
            .mobile-cart-btn {
                width: auto;
                height: auto;
                border: 0 !important;
                background: transparent !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                transition: none !important;
            }
            .mobile-cart-btn:hover {
                border-color: transparent !important;
                box-shadow: none !important;
                transform: none !important;
            }

            /* Show badge unless explicitly hidden with d-none */
            .mobile-cart-btn .cart-count.d-none {
                display: none !important;
            }

            /* Flat mobile dropdown – remove box visuals */
            .navbar .dropdown-menu {
                border: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                background: transparent !important;
                padding: 0 !important;
            }
            .navbar .dropdown-menu.show { display: block !important; }
            .navbar .dropdown-menu .dropdown-item { padding: 0.5rem 0 !important; }

            /* Stronger override: ensure no box on any mobile dropdown */
            .main-header .dropdown-menu,
            .navbar .dropdown-menu,
            .nav-item .dropdown-menu,
            .dropdown-menu {
                border: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                background: transparent !important;
                padding: 0 !important;
            }
            .dropdown-menu.show { display: block !important; }
            .dropdown-menu .dropdown-item {
                padding: 0.5rem 0 !important;
                background: transparent !important;
            }

        }
        
        @media (max-width: 576px) {
            .navbar-brand h4 {
                font-size: 1.2rem;
            }
            
            .header-actions .btn-sm {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
        }
        
        /* Hide top bar with email and social links on mobile and tablet */
        @media (max-width: 991.98px) {
            .top-bar.corano-topbar {
                display: none !important;
            }
        }

        /* Hide top bar globally as requested */
        .top-bar.corano-topbar {
            display: none !important;
        }
        
    </style>
    
    @stack('styles')
    <style>
        /* WhatsApp Chat Button (global) */
        .whatsapp-chat-btn {
            position: fixed;
            right: 20px;
            bottom: 20px;
            width: 56px;
            height: 56px;
            background: #25D366;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 8px 20px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            text-decoration: none;
        }
        .whatsapp-chat-btn:hover {
            background: #1ebe5d;
            color: #fff;
            transform: translateY(-1px);
        }
        .whatsapp-chat-btn i {
            font-size: 1.6rem;
        }
    </style>
</head>
<body>
    @include('frontend.partials.gtm_body')
    <!-- Header -->
    <header class="header">
        <div class="organic-header" id="organicHeader">
            <div class="organic-header__top">
                <div class="container">
                    <div class="row align-items-center g-3">
                        <div class="col col-lg-3">
                            <a class="organic-logo" href="{{ route('home') }}">
                                @if($setting && $setting->logo)
                                    <img src="{{ asset($setting->logo) }}" alt="{{ config('app.name', 'Nuter Guru') }}" class="img-fluid logo-img">
                                @else
                                    <span class="organic-logo__mark">Nuter</span>
                                    <span class="organic-logo__name">Guru</span>
                                @endif
                            </a>
                        </div>

                        <div class="col-12 col-lg-6 organic-search-col">
                            <div class="organic-search" id="organicSearchBar">
                                <form action="{{ route('products') }}" method="GET" class="organic-search__form">
                                    <input type="text"
                                           name="search"
                                           class="form-control organic-search__input js-product-search"
                                           placeholder="{{ __('Search products...') }}"
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-autocomplete="list"
                                           aria-controls="organicSearchSuggestions">
                                    <button type="submit" class="organic-search__btn" aria-label="{{ __('Search') }}">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                                <div class="search-suggestions js-search-suggestions" id="organicSearchSuggestions" hidden></div>
                            </div>
                        </div>

                        <div class="col-auto col-lg-3 ms-auto ms-lg-0">
                            <div class="d-flex align-items-center justify-content-end gap-2 gap-md-3 organic-header__actions">
                                <button type="button"
                                        class="organic-search-toggle d-lg-none"
                                        id="organicSearchToggle"
                                        aria-label="{{ __('Search') }}"
                                        aria-expanded="false"
                                        aria-controls="organicSearchBar">
                                    <i class="fas fa-search"></i>
                                </button>

                                <a href="{{ route('cart') }}" class="organic-cart wsus__cart_icon d-none d-lg-inline-flex" aria-label="{{ __('Cart') }}">
                                    <span class="organic-cart__icon">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span class="cart-count badge d-none">0</span>
                                    </span>
                                    <span class="organic-cart__total d-none d-md-inline">
                                        <span class="cart-total-amount">0</span>{{ $setting->currency_icon ?? '৳' }}
                                    </span>
                                </a>

                                <button class="navbar-toggler d-lg-none border-0 p-0"
                                        type="button"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#mobileMenu"
                                        aria-controls="mobileMenu"
                                        aria-label="{{ __('Toggle navigation') }}">
                                    <span class="navbar-toggler-icon"><span></span></span>
                                </button>

                                <div class="organic-auth d-none d-lg-flex align-items-center">
                                    @auth
                                        <div class="dropdown">
                                            <a href="#" class="organic-auth__user dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-user-circle"></i>
                                                <span>{{ __('Account') }}</span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>{{ __('Dashboard') }}</a></li>
                                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>{{ __('Profile') }}</a></li>
                                                <li><a class="dropdown-item" href="{{ route('orders') }}"><i class="fas fa-shopping-bag me-2"></i>{{ __('Orders') }}</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ route('login') }}" class="organic-auth__link {{ request()->routeIs('login') ? 'is-active' : '' }}">{{ __('Login') }}</a>
                                        <a href="{{ route('register') }}" class="organic-auth__btn {{ request()->routeIs('register') ? 'is-active' : '' }}">{{ __('Register') }}</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="organic-nav d-none d-lg-block">
                <div class="container">
                    <ul class="organic-nav__list">
                        <li>
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
                        </li>
                        <li>
                            <a class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}" href="{{ route('products') }}">{{ __('Shop') }}</a>
                        </li>
                        @foreach($categories->take(7) as $category)
                            <li>
                                <a class="nav-link {{ request()->routeIs('category') && request()->route('slug') === $category->slug ? 'active' : '' }}"
                                   href="{{ route('category', $category->slug) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                        @if($categories->count() > 7)
                            <li class="dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ __('More') }}</a>
                                <ul class="dropdown-menu">
                                    @foreach($categories->slice(7) as $category)
                                        <li><a class="dropdown-item" href="{{ route('category', $category->slug) }}">{{ $category->name }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                        <li>
                            <a class="nav-link {{ request()->routeIs('our-story') ? 'active' : '' }}" href="{{ route('our-story') }}">{{ __('About Us') }}</a>
                        </li>
                        @if(\Illuminate\Support\Facades\Route::has('contact'))
                            <li>
                                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('Contact') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    
    <!-- Flash messages are shown as toast popups (see script below) -->
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer text-white mt-5" style="background-color: var(--footer-color, {{ $footer->footer_color ?? '#343a40' }});">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-widget">
                        <a href="{{ route('home') }}" class="d-inline-block">
                            <img src="{{ asset($setting->logo) }}" alt="{{ $seoSetting->seo_title ?? 'Logo' }}" class="img-fluid" style="max-height: 72px;">
                        </a>
                        
                        <p class="text-muted">{{ $footer->description ?? 'Premium organic dry fruits, nuts, spices and healthy foods — fresh, pure, and delivered to your door.' }}</p>
                        <div class="social-links mt-3">
                            @if($socialLinks->count() > 0)
                                @foreach($socialLinks as $socialLink)
                                    <a href="{{ $socialLink->link }}" target="_blank" class="text-white me-3"><i class="{{ $socialLink->icon }}"></i></a>
                                @endforeach
                            @else
                                <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-white"><i class="fab fa-pinterest"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h6 class="mb-3">{{ $footer->column_two_title ?? 'Quick Links' }}</h6>
                        <ul class="list-unstyled">
                            @if($footerLinks1->count() > 0)
                                @foreach($footerLinks1 as $link)
                                    <li><a href="{{ $link->link }}" class="text-muted text-decoration-none">{{ $link->title }}</a></li>
                                @endforeach
                            @else
                                {{-- <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                                <li><a href="{{ route('products') }}" class="text-muted text-decoration-none">Products</a></li>
                                <li><a href="{{ route('about') }}" class="text-muted text-decoration-none">About Us</a></li>
                                <li><a href="{{ route('contact') }}" class="text-muted text-decoration-none">Contact</a></li> --}}
                               
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h6 class="mb-3">{{ $footer->column_three_title ?? 'Customer Service' }}</h6>
                        <ul class="list-unstyled">
                            @if($footerLinks2->count() > 0)
                                @foreach($footerLinks2 as $link)
                                    <li><a href="{{ $link->link }}" class="text-muted text-decoration-none">{{ $link->title }}</a></li>
                                @endforeach
                            @else
                                <li><a href="{{ route('faq') }}" class="text-muted text-decoration-none">FAQ</a></li>
                                <li><a href="#" class="text-muted text-decoration-none">Shipping Info</a></li>
                                <li><a href="#" class="text-muted text-decoration-none">Returns</a></li>
                                <li><a href="{{ route('terms.conditions') }}" class="text-muted text-decoration-none">Terms & Conditions</a></li>
                                <li><a href="{{ route('privacy.policy') }}" class="text-muted text-decoration-none">Privacy Policy</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h6 class="mb-3">{{ $footer->column_four_title ?? 'Contact Info' }}</h6>
                        <div class="contact-info">
                            @if($footer && $footer->address)
                                <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i> {{ $footer->address }}</p>
                            @else
                                <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i> Dhaka, Bangladesh</p>
                            @endif
                            @if($footer && $footer->phone)
                                <p class="text-muted mb-2"><i class="fas fa-phone me-2"></i> {{ $footer->phone }}</p>
                            @else
                                <p class="text-muted mb-2"><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</p>
                            @endif
                            @if($footer && $footer->email)
                                <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i> {{ $footer->email }}</p>
                            @else
                                <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i> {{ $setting->contact_email ?? 'info@nuterguru.com' }}</p>
                            @endif
                            @if($footer && $footer->working_hours)
                                <p class="text-muted"><i class="fas fa-clock me-2"></i> {{ $footer->working_hours }}</p>
                            @else
                                <p class="text-muted"><i class="fas fa-clock me-2"></i> Mon - Sat: 9:00 AM - 8:00 PM</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom border-top border-secondary py-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">&copy; {{ date('Y') }} {{ $footer->copyright ?? config('app.name', 'Nuter Guru') . '. All rights reserved.' }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="payment-methods">
                            <i class="fab fa-cc-visa text-muted me-2"></i>
                            <i class="fab fa-cc-mastercard text-muted me-2"></i>
                            <i class="fab fa-cc-paypal text-muted me-2"></i>
                            <i class="fab fa-cc-stripe text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @include('frontend.partials.mobile-app-menu')
    @include('frontend.partials.mobile-app-shell')
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>window.__searchProductsUrl = @json(route('products.search'));</script>
    <script src="{{ asset('frontend/js/app.js') }}?v={{ filemtime(public_path('frontend/js/app.js')) }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                showNotification(@json(session('success')), 'success');
            @endif

            @if(session('error'))
                showNotification(@json(session('error')), 'error');
            @endif

            @if(session('warning'))
                showNotification(@json(session('warning')), 'warning');
            @endif

            @if(session('info'))
                showNotification(@json(session('info')), 'info');
            @endif

            @if(session('messege'))
                showNotification(@json(session('messege')), @json(session('alert-type', 'info')));
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    showNotification(@json($error), 'error');
                @endforeach
            @endif
        });
    </script>
    
    <!-- Fix dropdown conflicts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile dropdown fallback: ensure navbar dropdowns open on tap
        // This helps in case Bootstrap's dropdown is blocked by other scripts/CSS on small screens
        const dropdownToggles = document.querySelectorAll('.navbar-nav .dropdown-toggle');
        dropdownToggles.forEach(function(toggle){
            toggle.addEventListener('click', function(e){
                // Only apply manual toggle on tablet/mobile widths
                if (window.innerWidth <= 992) {
                    e.preventDefault();
                    e.stopPropagation();

                    const parent = this.closest('.dropdown');
                    if (!parent) return;
                    const menu = parent.querySelector('.dropdown-menu');

                    const isShown = parent.classList.contains('show');

                    // Close other open dropdowns in the same nav
                    const siblings = parent.parentElement ? parent.parentElement.querySelectorAll('.dropdown.show') : [];
                    siblings.forEach(function(d){
                        if (d !== parent) {
                            d.classList.remove('show');
                            const dm = d.querySelector('.dropdown-menu');
                            if (dm) dm.classList.remove('show');
                        }
                    });

                    // Toggle current dropdown
                    parent.classList.toggle('show', !isShown);
                    if (menu) menu.classList.toggle('show', !isShown);
                }
            });
        });

        // Close any open sort dropdowns when clicking anywhere
        document.addEventListener('click', function(e) {
            // Close sort dropdown if clicking outside
            const sortMenu = document.getElementById('sortMenu');
            const sortContainer = document.querySelector('.sort-dropdown-container');
            
            if (sortMenu && sortContainer && !sortContainer.contains(e.target)) {
                sortMenu.style.display = 'none';
            }
            
            // Close navbar dropdowns when clicking outside (mobile/tablet collapse menu only)
            if (window.innerWidth <= 991.98) {
                const navbarDropdowns = document.querySelectorAll('.navbar-nav .dropdown');
                navbarDropdowns.forEach(function(dropdown) {
                    if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('show');
                        const menu = dropdown.querySelector('.dropdown-menu');
                        if (menu) {
                            menu.classList.remove('show');
                        }
                    }
                });
            }
        });
        
        // Prevent sort dropdown from interfering with navbar dropdowns
        const sortContainer = document.querySelector('.sort-dropdown-container');
        if (sortContainer) {
            sortContainer.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
    </script>
    
    @stack('scripts')
    @php($footer = App\Models\Footer::first())
      @if($footer && $footer->phone)
          @php($waPhone = preg_replace('/[^0-9]/', '', $footer->phone))
          @php($waText = urlencode('I am searching for'))
          <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" class="whatsapp-chat-btn" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
              <i class="fab fa-whatsapp"></i>
          </a>
      @endif

    @include('frontend.partials.pwa-install')
    <script src="{{ asset('frontend/js/pwa-install.js') }}?v={{ filemtime(public_path('frontend/js/pwa-install.js')) }}" defer></script>
</body>
</html>