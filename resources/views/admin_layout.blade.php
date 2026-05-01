<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ulagym</title>

    <!-- 1. Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- 2. Bootstrap Icons -->

    <link href="{{ asset('asset/css/bootstrap-icons.min.css') }}" rel="stylesheet">

    <!-- 3. Local Bootstrap -->
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            /* Light Gray Background */
            overflow-x: hidden;
        }

        /* --- SIDEBAR STYLES --- */
        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            width: 260px;
            margin-left: -260px;
            /* Hidden by default on mobile */
            background-color: #1e293b;
            /* Dark Slate */
            color: #94a3b8;
            transition: all 0.3s ease;
            position: fixed;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        /* Sidebar Visible on Desktop */
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
                position: relative;
            }
        }

        /* Toggled State */
        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
            /* Show on mobile when toggled */
        }

        @media (min-width: 768px) {
            #wrapper.toggled #sidebar-wrapper {
                margin-left: -260px;
                /* Hide on desktop when toggled */
            }
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: #0f172a;
        }

        .list-group-item {
            background-color: transparent;
            color: #94a3b8;
            border: none;
            padding: 12px 25px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .list-group-item i {
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .list-group-item.active {
            background-color: #0d6efd;
            color: #fff;
            border-right: 4px solid #fff;
            /* Accent border */
        }

        /* --- CONTENT STYLES --- */
        #page-content-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .navbar-admin {
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 15px 30px;
        }

        .admin-avatar {
            width: 35px;
            height: 35px;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div id="wrapper">

        <!-- SIDEBAR -->
        <div id="sidebar-wrapper">
            <div class="sidebar-brand">
                <i class="bi bi-speedometer2 text-primary me-2"></i> Ulagym
            </div>

            <div class="list-group list-group-flush mt-3 flex-grow-1">
                <small class="text-uppercase fw-bold px-4 mb-2" style="font-size: 0.7rem; color: #64748b;">{{ __('site.Main Menu') }}</small>

                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> {{ __('site.Dashboard') }}
                </a>

                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> {{ __('site.Users') }}
                </a>

                <a href="{{ route('admin.banners.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i> {{ __('site.Banners') }}
                </a>

                <a href="{{ route('admin.brands.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill"></i> {{ __('site.Brands') }}
                </a>

                <!-- Spacer -->
                <div class="mt-4"></div>
                <small class="text-uppercase fw-bold px-4 mb-2" style="font-size: 0.7rem; color: #64748b;">{{ __('site.System') }}</small>

                <a href="{{ route('home') }}" target="_blank" class="list-group-item list-group-item-action">
                    <i class="bi bi-box-arrow-up-right"></i> {{ __('site.Visit Website') }}
                </a>
            </div>

            <!-- Logout Area -->
            <div class="p-3 border-top border-secondary border-opacity-25">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-box-arrow-left me-2"></i> {{ __('site.logout') }}
                    </button>
                </form>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- PAGE CONTENT -->
        <div id="page-content-wrapper">

            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-admin sticky-top">
                <div class="container-fluid">
                    <button class="btn btn-light text-secondary" id="menu-toggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        <div class="d-none d-md-block text-end me-3">
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ __('site.Administrator') }}</div>
                        </div>
                        <div class="admin-avatar">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content Container -->
            <div class="container-fluid p-4">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('asset/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function() {
            el.classList.toggle("toggled");
        };
    </script>
</body>

</html>