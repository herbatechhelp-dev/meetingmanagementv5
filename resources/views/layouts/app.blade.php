<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    @if(isset($globalSetting) && $globalSetting->favicon_path)
        <link rel="icon" type="{{ Str::endsWith($globalSetting->favicon_path, '.ico') ? 'image/x-icon' : 'image/png' }}"
            href="{{ Storage::url($globalSetting->favicon_path) }}">
        <link rel="apple-touch-icon" href="{{ Storage::url($globalSetting->favicon_path) }}">
    @else
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
        <link rel="mask-icon" href="{{ asset('images/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
        <link rel="shortcut icon" href="{{ asset('images/favicon/favicon.ico') }}">
    @endif
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="{{ asset('images/favicon/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Fallback favicon jika folder favicon tidak ada -->
    @if(!file_exists(public_path('images/favicon')))
        <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    @endif

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_green.css">

    <style>
        :root {
            --primary-bg: #f8fafc;
            --nav-bg: rgba(255, 255, 255, 0.85);
            --accent-color: #10b981;
            --accent-hover: #059669;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --surface-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --premium-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --glass-border: rgba(255, 255, 255, 0.4);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-main);
            font-size: 15px;
            /* Increased from 13px for better readability */
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Top Navigation Bar */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            min-height: 72px;
            /* Changed from fixed height */
            background-color: var(--nav-bg) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border) !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            margin-left: 0 !important;
            display: flex;
            align-items: center;
            padding: 0 32px !important;
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            font-weight: 800;
            font-size: 1.2rem;
            /* Reduced from 1.25rem */
            color: var(--accent-color) !important;
            margin-right: 2rem;
            text-decoration: none !important;
        }

        .nav-link-custom {
            font-weight: 500;
            color: var(--text-muted) !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            display: flex !important;
            align-items: center;
            white-space: nowrap;
            gap: 0.35rem;
            /* Better spacing between icon and text */
        }

        .nav-link-custom i {
            margin-right: 0 !important;
            /* Handled by gap */
            font-size: 1.1rem;
        }

        .nav-link-custom:hover {
            color: var(--accent-color) !important;
            background-color: rgba(16, 185, 129, 0.05);
        }

        .nav-link-custom.active {
            color: var(--accent-color) !important;
            font-weight: 600;
        }

        /* Content Area */
        .content-wrapper {
            margin-left: 0 !important;
            margin-top: 72px;
            /* Matches new navbar height */
            background-color: var(--primary-bg) !important;
            min-height: calc(100vh - 72px) !important;
            padding: 24px 0 !important;
            /* Increased padding */
        }

        /* Card System */
        .card {
            border: none !important;
            border-radius: 20px !important;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02) !important;
            transform: translateY(-4px);
        }

        .transition-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 0.75rem 1.25rem !important;
            /* More compact */
        }

        .card-title {
            font-weight: 700 !important;
            color: var(--text-main);
            font-size: 1.1rem !important;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            border-radius: 10px !important;
            padding: 0.625rem 1.5rem !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3) !important;
            transition: all 0.2s ease !important;
            font-size: 0.95rem;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover) !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4) !important;
        }

        .btn-indigo {
            background-color: #10b981 !important;
            color: white !important;
            border: none !important;
        }

        .btn-indigo:hover {
            background-color: #059669 !important;
            transform: translateY(-1px);
        }

        .btn-soft-primary {
            background-color: rgba(16, 185, 129, 0.1) !important;
            color: #10b981 !important;
            border: none !important;
        }

        .btn-soft-primary:hover {
            background-color: rgba(16, 185, 129, 0.2) !important;
        }

        .btn-soft-success {
            background-color: rgba(16, 185, 129, 0.1) !important;
            color: #10b981 !important;
            border: none !important;
        }

        .btn-soft-success:hover {
            background-color: rgba(16, 185, 129, 0.2) !important;
        }

        .btn-soft-danger {
            background-color: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
            border: none !important;
        }

        .btn-soft-danger:hover {
            background-color: rgba(239, 68, 68, 0.2) !important;
        }

        .btn-soft-warning {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #f59e0b !important;
            border: none !important;
        }

        .btn-soft-warning:hover {
            background-color: rgba(245, 158, 11, 0.2) !important;
        }

        .btn-soft-info {
            background-color: rgba(6, 182, 212, 0.1) !important;
            color: #0891b2 !important;
            border: none !important;
        }

        .btn-soft-info:hover {
            background-color: rgba(6, 182, 212, 0.2) !important;
        }

        /* Custom Dropdown */
        .dropdown-menu {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: var(--premium-shadow) !important;
            padding: 0.75rem !important;
            margin-top: 0.5rem !important;
        }

        .dropdown-item {
            border-radius: 8px !important;
            padding: 0.625rem 1rem !important;
            font-weight: 500 !important;
            color: var(--text-muted) !important;
            transition: all 0.2s ease !important;
        }

        .dropdown-item:hover {
            background-color: #f8fafc !important;
            color: var(--accent-color) !important;
        }

        /* Tables */
        .table-header-custom {
            color: #ffffff !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 0.8rem !important;
            letter-spacing: 0.05em !important;
            border-bottom: none !important;
            text-align: center !important;
        }

        /* Responsive */

        @media (max-width: 991.98px) {
            .main-header {
                padding: 0.5rem 1rem !important;
                height: auto !important;
                flex-wrap: wrap;
                /* Fix content clipping when navbar expands */
            }

            .navbar-brand-custom {
                margin-right: auto;
            }

            .navbar-brand-custom span {
                display: none;
                /* Keep hidden on mobile to save space */
            }

            /* Create a card-like dropdown for mobile menu */
            .navbar-collapse {
                background: #ffffff;
                border-radius: 12px;
                padding: 1rem;
                margin-top: 0.75rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                border: 1px solid rgba(0, 0, 0, 0.05);
                width: 100%;
                max-height: calc(100vh - 80px);
                overflow-y: auto;
            }

            .nav-link-custom {
                padding: 0.75rem 1rem !important;
                border-bottom: 1px solid #f1f5f9;
            }

            .nav-link-custom:last-child {
                border-bottom: none;
            }

            .navbar-nav.ml-auto {
                flex-direction: row;
                justify-content: flex-end;
                width: auto;
            }

            .content-wrapper {
                padding: 16px 12px !important;
                margin-top: 80px !important;
                /* Fixed clipping issue */
            }
        }

        @media (max-width: 767.98px) {

            /* General card and padding fixes for mobile */
            .card-body {
                padding: 1.25rem 1rem !important;
            }

            .card-header {
                padding: 1rem !important;
            }

            /* Fix page titles and layout on mobile */
            .content-header h1 {
                font-size: 1.5rem;
                margin-bottom: 0.5rem !important;
            }

            .content-header .breadcrumb {
                float: none;
                background-color: transparent !important;
                padding: 0;
            }

            .content-header {
                padding-bottom: 0.5rem !important;
            }

            /* Buttons should wrap gracefully */
            .btn {
                white-space: normal;
                word-wrap: break-word;
            }

            /* Adjust data tables padding */
            .table th,
            .table td {
                padding: 0.75rem 0.5rem !important;
            }

            /* Make popovers look better on mobile */
            .popover {
                max-width: 90vw !important;
            }
        }

        /* Extreme Full-Width Overrides */
        @hasSection('hide_header')
            .content-wrapper {
                padding-left: 20px !important;
                padding-right: 20px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .content {
                padding: 0 !important;
                margin: 0 !important;
            }

            .content>.container-fluid {
                padding: 0 10px !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }

        @endif

        /* Popover & Tooltip Coverage Fix */
        .popover {
            z-index: 1061 !important;
            /* Above navbar (1040) */
        }
    </style>

    <style>
        /* Global UI refresh layer to modernize all pages without changing each individual view */
        :root {
            --fresh-bg-start: #f7fbff;
            --fresh-bg-mid: #eefcf6;
            --fresh-bg-end: #ffffff;
            --fresh-surface: #ffffff;
            --fresh-border: #e6edf4;
            --fresh-text: #0f172a;
            --fresh-muted: #64748b;
            --fresh-accent: #0ea673;
            --fresh-accent-strong: #07835a;
            --fresh-ring: rgba(14, 166, 115, 0.2);
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at 15% 10%, #e3f7ff 0%, rgba(227, 247, 255, 0) 42%),
                radial-gradient(circle at 85% 0%, #e8fff2 0%, rgba(232, 255, 242, 0) 48%),
                linear-gradient(160deg, var(--fresh-bg-start) 0%, var(--fresh-bg-mid) 45%, var(--fresh-bg-end) 100%);
            color: var(--fresh-text);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .card-title,
        .navbar-brand-custom {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            letter-spacing: 0.01em;
        }

        .main-header {
            background: rgba(255, 255, 255, 0.82) !important;
            border-bottom: 1px solid var(--fresh-border) !important;
            box-shadow: 0 10px 32px rgba(15, 23, 42, 0.05);
        }

        .content-wrapper {
            padding-top: 26px !important;
            padding-bottom: 26px !important;
        }

        .content>.container-fluid,
        .content-header .container-fluid {
            max-width: 1360px;
            margin-left: auto;
            margin-right: auto;
        }

        .card {
            border: 1px solid var(--fresh-border) !important;
            border-radius: 18px !important;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06) !important;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.1) !important;
        }

        .card-header {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
            border-bottom: 1px solid var(--fresh-border) !important;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(247, 252, 249, 0.9) 100%) !important;
        }

        .btn,
        .form-control,
        .custom-select,
        .input-group-text,
        .badge,
        .dropdown-item {
            border-radius: 10px !important;
        }

        .form-control,
        .custom-select {
            border: 1px solid #dbe5ef;
            min-height: 42px;
            box-shadow: none !important;
        }

        .form-control:focus,
        .custom-select:focus {
            border-color: var(--fresh-accent) !important;
            box-shadow: 0 0 0 0.2rem var(--fresh-ring) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--fresh-accent) 0%, #17b67f 100%) !important;
            border-color: var(--fresh-accent) !important;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, var(--fresh-accent-strong) 0%, #0ea673 100%) !important;
            border-color: var(--fresh-accent-strong) !important;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f6fafc;
            color: #334155;
            border-bottom: 1px solid #e6edf4 !important;
            font-size: 0.76rem;
            letter-spacing: 0.06em;
        }

        .table td,
        .table th {
            vertical-align: middle;
            border-color: #eef3f7;
        }

        .table-responsive {
            border-radius: 12px;
            border: 1px solid #edf2f7;
            background: #fff;
        }

        .alert {
            border: 1px solid transparent;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
        }

        .dropdown-menu {
            border: 1px solid #e6edf4 !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .main-footer {
            border-top: 1px solid #e8eef4 !important;
            background: linear-gradient(180deg, #ffffff 0%, #f9fcff 100%) !important;
        }

        .mobile-empty-state {
            border-radius: 12px;
            background: linear-gradient(180deg, #fbfdff 0%, #f5fbf8 100%);
        }

        .mobile-pagination-wrap {
            margin-top: 1rem;
        }

        .mobile-pagination-wrap .pagination {
            margin-bottom: 0;
            gap: 0.3rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .mobile-pagination-wrap .page-link {
            border-radius: 10px !important;
            border: 1px solid #dbe6f1;
            color: #334155;
            min-width: 38px;
            text-align: center;
            font-weight: 600;
        }

        .mobile-pagination-wrap .page-item.active .page-link {
            background: linear-gradient(135deg, #0ea673 0%, #15b981 100%);
            border-color: #0ea673;
            color: #fff;
        }

        @media (max-width: 1199.98px) {
            .content>.container-fluid,
            .content-header .container-fluid {
                max-width: 100%;
            }
        }

        @media (max-width: 991.98px) {
            .main-header {
                padding: 0.7rem 0.8rem !important;
                min-height: 74px;
                flex-wrap: nowrap;
                overflow: visible;
            }

            .d-flex.align-items-center.order-lg-3.ml-auto {
                margin-left: auto !important;
                width: auto;
                justify-content: flex-end;
                margin-top: 0;
                gap: 0.4rem;
            }

            .navbar-collapse {
                border: 1px solid #e8eef4;
                background: rgba(255, 255, 255, 0.96);
                position: absolute;
                top: calc(100% + 0.4rem);
                left: 0.8rem;
                right: 0.8rem;
                width: auto;
                z-index: 1045;
            }

            .main-header .navbar-nav .dropdown-menu {
                margin-top: 0 !important;
                z-index: 1062 !important;
            }

            .main-header .navbar-nav .dropdown-menu.dropdown-menu-lg {
                position: fixed !important;
                top: 78px !important;
                left: 0.75rem !important;
                right: 0.75rem !important;
                width: auto !important;
                min-width: 0 !important;
                max-height: min(70vh, 520px);
                overflow-y: auto;
            }

            .main-header .navbar-nav .dropdown-menu.dropdown-menu-right:not(.dropdown-menu-lg) {
                position: fixed !important;
                top: 78px !important;
                right: 0.75rem !important;
                left: auto !important;
                width: min(260px, calc(100vw - 1.5rem)) !important;
                min-width: 0 !important;
            }

            .main-header .navbar-nav .dropdown-menu::before {
                display: none;
            }

            .content-wrapper {
                margin-top: 88px !important;
                min-height: calc(100vh - 88px) !important;
                padding: 14px 10px !important;
            }

            .content-header h1 {
                font-size: 1.35rem;
            }

            .main-footer .row {
                row-gap: 0.65rem;
            }

            .main-footer .col-sm-6,
            .main-footer .col-sm-6.text-right {
                text-align: center !important;
            }
        }

        @media (max-width: 575.98px) {
            .card {
                border-radius: 14px !important;
            }

            .navbar-brand-custom {
                margin-right: auto;
            }

            .navbar-brand-custom img {
                height: 28px !important;
            }

            .navbar-nav.flex-row.align-items-center {
                margin-right: 0.1rem;
            }

            .navbar-toggler {
                margin-left: 0 !important;
            }

            .card-body {
                padding: 0.95rem !important;
            }

            .card-header {
                padding: 0.85rem 0.95rem !important;
            }

            .breadcrumb {
                font-size: 0.78rem;
            }

            .btn {
                font-size: 0.88rem;
                padding: 0.5rem 0.9rem !important;
            }

            .table td,
            .table th {
                padding: 0.6rem 0.5rem !important;
            }

            .mobile-pagination-wrap .pagination {
                justify-content: flex-start;
            }

            .mobile-pagination-wrap {
                overflow-x: auto;
                padding-bottom: 0.2rem;
            }
        }

        @media (max-width: 767.98px) {
            .card-header {
                display: flex;
                flex-wrap: wrap;
                gap: 0.6rem;
                align-items: flex-start !important;
            }

            .card-header .card-title {
                width: 100%;
                margin-bottom: 0 !important;
            }

            .card-tools,
            .btn-group,
            .btn-group-sm {
                flex-wrap: wrap;
                gap: 0.35rem;
            }

            .table-mobile-stack thead {
                display: none;
            }

            .table-mobile-stack,
            .table-mobile-stack tbody,
            .table-mobile-stack tr,
            .table-mobile-stack td {
                display: block;
                width: 100%;
            }

            .table-mobile-stack tr {
                border: 1px solid #e8eff5;
                border-radius: 12px;
                padding: 0.4rem 0.2rem;
                margin-bottom: 0.75rem;
                background: #ffffff;
                box-shadow: 0 6px 14px rgba(15, 23, 42, 0.05);
            }

            .table-mobile-stack td {
                border: 0 !important;
                border-bottom: 1px dashed #edf2f7 !important;
                position: relative;
                padding: 0.55rem 0.7rem 0.55rem 48% !important;
                text-align: left !important;
                min-height: 38px;
            }

            .table-mobile-stack td:last-child {
                border-bottom: 0 !important;
            }

            .table-mobile-stack td::before {
                content: attr(data-label);
                position: absolute;
                top: 0.55rem;
                left: 0.7rem;
                width: 43%;
                font-size: 0.73rem;
                font-weight: 700;
                letter-spacing: 0.03em;
                text-transform: uppercase;
                color: #64748b;
                white-space: normal;
                line-height: 1.2;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Unified Top Navigation Header -->
        <header class="main-header navbar navbar-expand-lg">
            <!-- Logo area -->
            <a href="{{ route('dashboard') }}" class="navbar-brand-custom">
                @if(isset($globalSetting) && $globalSetting->logo_path)
                    <img src="{{ Storage::url($globalSetting->logo_path) }}" alt="Logo" class="mr-2"
                        style="height: 32px; width: auto; object-fit: contain;">
                @else
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mr-2"
                        style="height: 32px; width: auto; object-fit: contain;">
                @endif
                <span>{{ $globalSetting->app_name ?? 'HERBATECH' }}</span>
            </a>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse order-lg-2" id="mainNavbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item mr-1">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-th-large mr-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mr-1">
                        <a href="{{ route('meetings.index') }}"
                            class="nav-link nav-link-custom {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                            <i class="far fa-calendar-alt mr-1"></i> Meeting
                        </a>
                    </li>
                    <li class="nav-item mr-1">
                        <a href="{{ route('action-items.index') }}"
                            class="nav-link nav-link-custom {{ request()->routeIs('action-items.*') ? 'active' : '' }}">
                            <i class="fas fa-tasks mr-1"></i> Tugas
                        </a>
                    </li>
                    <li class="nav-item mr-1">
                        <a href="{{ route('room-bookings.index') }}"
                            class="nav-link nav-link-custom {{ request()->routeIs('room-bookings.*') ? 'active' : '' }}">
                            <i class="fas fa-door-open mr-1"></i> Pinjam Ruang
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        <li class="nav-item mr-1">
                            <a href="{{ route('trash.index') }}"
                                class="nav-link nav-link-custom {{ request()->routeIs('trash.*') ? 'active' : '' }}">
                                <i class="far fa-trash-alt mr-1"></i> Tempat Sampah
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="adminDropdown"
                                data-toggle="dropdown">
                                <i class="fas fa-cog mr-1"></i> Admin
                            </a>
                            <div class="dropdown-menu">
                                <a href="{{ route('meeting-types.index') }}" class="dropdown-item">
                                    <i class="fas fa-list-ul mr-2"></i> Jenis Meeting
                                </a>
                                <a href="{{ route('rooms.index') }}"
                                    class="dropdown-item {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                                    <i class="fas fa-door-open mr-2"></i> Manajemen Ruangan
                                </a>
                                <a href="{{ route('departments.index') }}" class="dropdown-item">
                                    <i class="far fa-building mr-2"></i> Departemen
                                </a>
                                <a href="{{ route('users.index') }}" class="dropdown-item">
                                    <i class="far fa-user mr-2"></i> Manajemen Pengguna
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('settings.branding') }}" class="dropdown-item">
                                    <i class="fas fa-paint-brush mr-2 text-primary"></i> Pengaturan Branding
                                </a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Right side: Notifications & Profile (Always visible) -->
            <div class="d-flex align-items-center order-lg-3 ml-auto">
                <ul class="navbar-nav flex-row align-items-center">
                    <!-- Notifications -->
                    <li class="nav-item dropdown mr-3">
                        <a class="nav-link p-0 position-relative" data-toggle="dropdown" href="#">
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle"
                                style="width: 40px; height: 40px;">
                                <i class="far fa-bell text-muted"></i>
                            </div>
                            @if(auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute bg-danger border border-white rounded-circle"
                                    style="top: 0; right: 0; width: 12px; height: 12px;"></span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            @if(auth()->user() && auth()->user()->notifications->count() > 0)
                                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-sm">Notifikasi</span>
                                    <div>
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <form action="{{ route('notifications.mark-all-read') }}" method="POST"
                                                class="d-inline mr-2">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-link p-0 text-muted"
                                                    title="Tandai semua dibaca">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <span
                                            class="badge badge-light text-primary">{{ auth()->user()->unreadNotifications->count() }}
                                            Baru</span>
                                    </div>
                                </div>

                                <div style="max-height: 300px; overflow-y: auto;">
                                    @foreach(auth()->user()->notifications->take(5) as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                            class="dropdown-item py-3 {{ $notification->read_at ? 'opacity-75' : 'bg-light font-weight-bold' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="bg-primary-light p-2 rounded-circle mr-3"
                                                    style="background: rgba(16, 185, 129, 0.1)">
                                                    <i
                                                        class="fas {{ $notification->data['icon'] ?? 'fa-bell' }} text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="text-sm mb-1">{{ $notification->data['title'] ?? 'Notice' }}
                                                    </div>
                                                    <div class="text-xs text-muted">
                                                        {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 50) }}
                                                    </div>
                                                    <div class="text-xs text-muted mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="d-flex border-top">
                                    <a href="{{ route('notifications.index') }}"
                                        class="dropdown-item text-center text-primary text-xs font-weight-bold py-2 border-right"
                                        style="flex: 1;">Lihat Semua</a>
                                    <a href="{{ route('notifications.settings') }}"
                                        class="dropdown-item text-center text-muted text-xs font-weight-bold py-2"
                                        style="flex: 1;" title="Pengaturan">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                </div>
                            @else
                                <div class="p-4 text-center text-muted">
                                    <i class="far fa-bell-slash mb-2 fa-2x"></i>
                                    <div class="text-sm">Belum ada notifikasi</div>
                                </div>
                            @endif
                        </div>
                    </li>

                    <!-- Profile -->
                    <li class="nav-item dropdown">
                        <a class="nav-link p-0 d-flex align-items-center" data-toggle="dropdown" href="#">
                            <div class="bg-indigo text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-2"
                                style="width: 40px; height: 40px; background-color: var(--accent-color)">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="d-none d-md-block">
                                <span
                                    class="d-block text-dark font-weight-bold text-sm leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-muted">{{ ucfirst(Auth::user()->role) }}</span>
                            </div>
                            <i class="fas fa-chevron-down ml-2 text-muted x-small" style="font-size: 0.6rem;"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item border-bottom">
                                <i class="far fa-user-circle mr-2 opacity-50"></i> Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt mr-2 opacity-50"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>

                <!-- Mobile toggler -->
                <button class="navbar-toggler border-0 shadow-none px-2 ml-1" type="button" data-toggle="collapse"
                    data-target="#mainNavbar">
                    <i class="fas fa-bars text-dark" style="font-size: 1.4rem;"></i>
                </button>
            </div>
        </header>

        <!-- Content Wrapper - Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @unless(isset($__env->getSections()['hide_header']))
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">@yield('title')</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    @yield('breadcrumb')
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            @endunless

            <!-- Main content -->
            <section class="content">
                <div class="@hasSection('hide_header') container-fluid px-0 @else container-fluid @endif">
                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Page Content -->
                    <div class="content">
                        @yield('content')
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer - Fixed Position -->
        <footer class="main-footer bg-white border-top d-flex align-items-center"
            style="margin-left: 0; box-shadow: 0 -1px 3px 0 rgba(0,0,0,0.05)">
            <div class="container-fluid">
                <div class="row align-items-center text-sm">
                    <div class="col-sm-6 text-muted">
                        &copy; {{ date('Y') }} <span class="font-weight-bold"
                            style="color: var(--accent-color)">{{ $globalSetting->app_name ?? 'HERBATECH' }}</span>. Hak
                        cipta dilindungi undang-undang.
                    </div>
                    <div class="col-sm-6 text-right text-muted">
                        <span class="px-2 py-1 rounded bg-light">v1.0.0</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
    <!-- Flatpickr & Tippy -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Setup AJAX headers for CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            const enhanceResponsiveTables = function () {
                $('table.table').each(function () {
                    const $table = $(this);
                    const $headers = $table.find('thead th');

                    if (!$headers.length) {
                        return;
                    }

                    $table.find('tbody tr').each(function () {
                        $(this).find('td').each(function (index) {
                            const headerText = $headers.eq(index).text().trim().replace(/\s+/g, ' ');
                            if (headerText) {
                                $(this).attr('data-label', headerText);
                            }
                        });
                    });

                    if (window.matchMedia('(max-width: 767.98px)').matches) {
                        $table.addClass('table-mobile-stack');
                    } else {
                        $table.removeClass('table-mobile-stack');
                    }
                });
            };

            // Keep session alive every 15 minutes
            setInterval(function () {
                if (navigator.onLine) {
                    $.get("{{ route('keep-alive') }}").fail(function () {
                        console.log('Failed to keep session alive');
                    });
                }
            }, 15 * 60 * 1000);

            // Auto-hide alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(300);

            // Active link handling for dropdowns
            $('.dropdown-item.active').parents('.nav-item.dropdown').find('.nav-link').addClass('active');

            // Responsive table wrapper
            $('table.table').each(function () {
                if (!$(this).parent().hasClass('table-responsive')) {
                    $(this).wrap('<div class="table-responsive"></div>');
                }
            });

            enhanceResponsiveTables();

            $(window).on('resize', function () {
                enhanceResponsiveTables();
            });

            // Mobile header overlap guard: never show menu collapse and account dropdowns at the same time
            const mobileHeaderQuery = window.matchMedia('(max-width: 991.98px)');

            $('.main-header .dropdown').on('show.bs.dropdown', function () {
                if (mobileHeaderQuery.matches) {
                    $('#mainNavbar').collapse('hide');
                }
            });

            $('.navbar-toggler').on('click', function () {
                if (!mobileHeaderQuery.matches) {
                    return;
                }

                $('.main-header .dropdown.show .dropdown-toggle').each(function () {
                    $(this).dropdown('toggle');
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>