<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="mask-icon" href="{{ asset('images/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <link rel="shortcut icon" href="{{ asset('images/favicon/favicon.ico') }}">
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
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            font-size: 15px; /* Increased from 13px for better readability */
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
            height: 72px; /* Increased from 64px to match larger font */
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
            font-size: 1.2rem; /* Reduced from 1.25rem */
            color: var(--accent-color) !important;
            margin-right: 2rem;
            text-decoration: none !important;
        }

        .nav-link-custom {
            font-weight: 500;
            color: var(--text-muted) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 1.05rem;
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
            margin-top: 72px; /* Matches new navbar height */
            background-color: var(--primary-bg) !important;
            min-height: calc(100vh - 72px) !important;
            padding: 24px 0 !important; /* Increased padding */
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
            padding: 0.75rem 1.25rem !important; /* More compact */
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

        .btn-indigo { background-color: #10b981 !important; color: white !important; border: none !important; }
        .btn-indigo:hover { background-color: #059669 !important; transform: translateY(-1px); }
        
        .btn-soft-primary { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; border: none !important; }
        .btn-soft-primary:hover { background-color: rgba(16, 185, 129, 0.2) !important; }
        
        .btn-soft-success { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; border: none !important; }
        .btn-soft-success:hover { background-color: rgba(16, 185, 129, 0.2) !important; }
        
        .btn-soft-danger { background-color: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; border: none !important; }
        .btn-soft-danger:hover { background-color: rgba(239, 68, 68, 0.2) !important; }
        
        .btn-soft-warning { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; border: none !important; }
        .btn-soft-warning:hover { background-color: rgba(245, 158, 11, 0.2) !important; }
        
        .btn-soft-info { background-color: rgba(6, 182, 212, 0.1) !important; color: #0891b2 !important; border: none !important; }
        .btn-soft-info:hover { background-color: rgba(6, 182, 212, 0.2) !important; }

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
                padding: 0 1rem !important;
            }
            .navbar-brand-custom span {
                display: none;
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
        .content > .container-fluid { 
            padding: 0 10px !important; 
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        @endif

        /* Popover & Tooltip Coverage Fix */
        .popover { 
            z-index: 1061 !important; /* Above navbar (1040) */
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
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mr-2" style="height: 32px; width: auto; object-fit: contain;">
                <span>HERBATECH</span>
            </a>

            <!-- Mobile toggler -->
            <button class="navbar-toggler border-0 shadow-none px-0" type="button" data-toggle="collapse" data-target="#mainNavbar">
                <i class="fas fa-bars text-dark"></i>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item mr-1">
                        <a href="{{ route('dashboard') }}" class="nav-link nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-th-large mr-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mr-1">
                        <a href="{{ route('meetings.index') }}" class="nav-link nav-link-custom {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                            <i class="far fa-calendar-alt mr-1"></i> Meeting
                        </a>
                    </li>
                    <li class="nav-item mr-1">
                        <a href="{{ route('action-items.index') }}" class="nav-link nav-link-custom {{ request()->routeIs('action-items.*') ? 'active' : '' }}">
                            <i class="fas fa-tasks mr-1"></i> Tugas
                        </a>
                    </li>
                    <li class="nav-item mr-1">
                        <a href="{{ route('room-bookings.index') }}" class="nav-link nav-link-custom {{ request()->routeIs('room-bookings.*') ? 'active' : '' }}">
                            <i class="fas fa-door-open mr-1"></i> Pinjam Ruang
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <li class="nav-item mr-1">
                        <a href="{{ route('trash.index') }}" class="nav-link nav-link-custom {{ request()->routeIs('trash.*') ? 'active' : '' }}">
                            <i class="far fa-trash-alt mr-1"></i> Tempat Sampah
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->isAdmin())
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="adminDropdown" data-toggle="dropdown">
                            <i class="fas fa-cog mr-1"></i> Admin
                        </a>
                        <div class="dropdown-menu">
                            <a href="{{ route('meeting-types.index') }}" class="dropdown-item">
                                <i class="fas fa-list-ul mr-2"></i> Jenis Meeting
                            </a>
                            <a href="{{ route('departments.index') }}" class="dropdown-item">
                                <i class="far fa-building mr-2"></i> Departemen
                            </a>
                            <a href="{{ route('users.index') }}" class="dropdown-item">
                                <i class="far fa-user mr-2"></i> Manajemen Pengguna
                            </a>
                        </div>
                    </li>
                    @endif
                </ul>

                <!-- Right side: Notifications & Profile -->
                <ul class="navbar-nav ml-auto align-items-center">
                    <!-- Notifications -->
                    <li class="nav-item dropdown mr-3">
                        <a class="nav-link p-0 position-relative" data-toggle="dropdown" href="#">
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 40px; height: 40px;">
                                <i class="far fa-bell text-muted"></i>
                            </div>
                            @if(auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute bg-danger border border-white rounded-circle" style="top: 0; right: 0; width: 12px; height: 12px;"></span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            @if(auth()->user() && auth()->user()->notifications->count() > 0)
                                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-sm">Notifikasi</span>
                                    <div>
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline mr-2">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-link p-0 text-muted" title="Tandai semua dibaca">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <span class="badge badge-light text-primary">{{ auth()->user()->unreadNotifications->count() }} Baru</span>
                                    </div>
                                </div>
                                
                                <div style="max-height: 300px; overflow-y: auto;">
                                    @foreach(auth()->user()->notifications->take(5) as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}" class="dropdown-item py-3 {{ $notification->read_at ? 'opacity-75' : 'bg-light font-weight-bold' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="bg-primary-light p-2 rounded-circle mr-3" style="background: rgba(16, 185, 129, 0.1)">
                                                    <i class="fas {{ $notification->data['icon'] ?? 'fa-bell' }} text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="text-sm mb-1">{{ $notification->data['title'] ?? 'Notice' }}</div>
                                                    <div class="text-xs text-muted">{{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 50) }}</div>
                                                    <div class="text-xs text-muted mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="d-flex border-top">
                                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary text-xs font-weight-bold py-2 border-right" style="flex: 1;">Lihat Semua</a>
                                    <a href="{{ route('notifications.settings') }}" class="dropdown-item text-center text-muted text-xs font-weight-bold py-2" style="flex: 1;" title="Pengaturan">
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
                            <div class="bg-indigo text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-2" style="width: 40px; height: 40px; background-color: var(--accent-color)">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="d-none d-md-block">
                                <span class="d-block text-dark font-weight-bold text-sm leading-tight">{{ Auth::user()->name }}</span>
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
        <footer class="main-footer bg-white border-top d-flex align-items-center" style="margin-left: 0; box-shadow: 0 -1px 3px 0 rgba(0,0,0,0.05)">
            <div class="container-fluid">
                <div class="row align-items-center text-sm">
                    <div class="col-sm-6 text-muted">
                        &copy; {{ date('Y') }} <span class="font-weight-bold" style="color: var(--accent-color)">HERBATECH</span>. Hak cipta dilindungi undang-undang.
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
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        // Setup AJAX headers for CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Keep session alive every 15 minutes
            setInterval(function() {
                if (navigator.onLine) {
                    $.get("{{ route('keep-alive') }}").fail(function() {
                        console.log('Failed to keep session alive');
                    });
                }
            }, 15 * 60 * 1000);

            // Auto-hide alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(300);
            
            // Active link handling for dropdowns
            $('.dropdown-item.active').parents('.nav-item.dropdown').find('.nav-link').addClass('active');
        });
    </script>
    
    @stack('scripts')
</body>
</html>