<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ulagym | The Best Car Marketplace</title>

    <!-- 1. Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- 2. Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap-icons.min.css') }}">

    <!-- 3. Local Bootstrap -->
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d6efd;
            --dark-color: #1a1d20;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            /* Light Gray Background */
            color: #333;
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
            color: #555 !important;
            transition: color 0.2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-color) !important;
        }

        /* Buttons in Nav */
        .btn-nav-login {
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            font-weight: 600;
        }

        .btn-nav-login:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-nav-register {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border: 1px solid var(--primary-color);
        }

        .btn-nav-register:hover {
            background-color: #0b5ed7;
        }

        /* Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 10px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 8px 15px;
        }

        .dropdown-item:hover {
            background-color: #f0f7ff;
            color: var(--primary-color);
        }

        /* Alert Styling */
        .custom-alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top shadow-sm py-3">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-car-front-fill"></i> Ulagym
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Links -->
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto ms-lg-4">
                    <li class="nav-item me-2">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            {{ __('site.home') }}
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link {{ request()->routeIs('cars.create') ? 'active' : '' }}" href="{{ route('cars.create') }}">
                            {{ __('site.sell_car') }}
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            {{ __('site.about') }}
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">

                    <!-- Language Dropdown -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle text-uppercase fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-globe2 me-1"></i> {{ app()->getLocale() }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('lang', 'en') }}">🇺🇸 English</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang', 'ru') }}">🇷🇺 Russian</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang', 'tk') }}">🇹🇲 Turkmen</a></li>
                        </ul>
                    </li>

                    <div class="vr mx-2 d-none d-lg-block text-secondary"></div>

                    <!-- Auth Links -->
                    @auth
                    <!-- Admin Link -->
                    @if(Auth::user()->role === 'admin')
                    <li class="nav-item me-2">
                        <a class="nav-link text-danger fw-bold" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-shield-lock"></i> {{ __('site.ADMIN PANEL') }}
                        </a>
                    </li>
                    @endif

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('site.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <!-- Login / Register Buttons -->
                    <li class="nav-item ms-2">
                        <a class="btn btn-nav-login px-4 rounded-pill btn-sm" href="{{ route('login') }}">{{ __('site.login') }}</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-nav-register px-4 rounded-pill btn-sm shadow-sm" href="{{ route('register') }}">{{ __('site.register') }}</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="container mt-4">
            <div class="alert alert-success custom-alert d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                <div>{{ session('success') }}</div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container mt-4">
            <div class="alert alert-danger custom-alert d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                <div>{{ session('error') }}</div>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Scripts -->
    <script src="{{ asset('asset/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>