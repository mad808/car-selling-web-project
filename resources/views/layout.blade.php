<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ulagym | The Best Car Marketplace</title>

    <!-- 1. Local Bootstrap Icons (No CDN) -->
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap-icons.min.css') }}">

    <!-- 2. Local Bootstrap CSS -->
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #f0f3f7;
            --primary-hover: #b2bfd3;
            --dark-color: #1a1d20;
        }

        /* 100% Local Fast System Font Stack */
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        /* Navbar Styling */
        .navbar-custom {
            background: linear-gradient(180deg, #0954a0 0%, #62a2e2 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--primary-color) !important;
            font-size: 1.45rem;
        }

        .nav-link {
            font-weight: 600;
            font-size: 0.92rem;
            color: #b0b0b1 !important;
            transition: color 0.15s ease;
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
            transition: all 0.2s ease;
        }

        .btn-nav-login:hover {
            background-color: var(--primary-color);
            color: #ffffff;
        }

        .btn-nav-register {
            background-color: var(--primary-color);
            color: #ffffff;
            font-weight: 600;
            border: 1px solid var(--primary-color);
            transition: all 0.2s ease;
        }

        .btn-nav-register:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
        }

        /* Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            padding: 8px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background-color: #f0f7ff;
            color: var(--primary-color);
        }

        /* Alerts */
        .custom-alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        /* =========================================================
           🚀 FLOATING BOTTOM-RIGHT BUTTONS (AI & GO TO TOP)
           ========================================================= */
        .floating-container {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        /* AI Floating Icon Button */
        .floating-ai-btn {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            color: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.45rem;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }

        .floating-ai-btn:hover {
            transform: scale(1.08);
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(102, 16, 242, 0.45);
        }

        /* AI Pulse Glow */
        .ai-pulse-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid rgba(13, 110, 253, 0.6);
            animation: aiPulse 2s infinite ease-out;
            pointer-events: none;
        }

        @keyframes aiPulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }
            100% {
                transform: scale(1.45);
                opacity: 0;
            }
        }

        /* Go To Top Button */
        .floating-top-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #ffffff;
            color: #1a1d20;
            border: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.25s ease;
        }

        .floating-top-btn.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .floating-top-btn:hover {
            background-color: var(--primary-color);
            color: #ffffff;
            border-color: var(--primary-color);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top py-3">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="bi bi-car-front-fill"></i>
                <span>Ulagym</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
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
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle text-uppercase fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-globe2 me-1"></i> {{ app()->getLocale() }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('lang', 'en') }}">🇺🇸 English</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang', 'ru') }}">🇷🇺 Russian</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang', 'tk') }}">🇹🇲 Turkmen</a></li>
                        </ul>
                    </li>

                    <div class="vr mx-2 d-none d-lg-block text-secondary opacity-25"></div>

                    <!-- Auth Links -->
                    @auth
                        @if(Auth::user()->role === 'admin')
                        <li class="nav-item me-2">
                            <a class="nav-link text-danger fw-bold" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-shield-lock me-1"></i> {{ __('site.ADMIN PANEL') }}
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
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="btn btn-nav-login px-3 py-1.5 rounded-pill btn-sm" href="{{ route('login') }}">{{ __('site.login') }}</a>
                        </li>
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="btn btn-nav-register px-3 py-1.5 rounded-pill btn-sm shadow-sm" href="{{ route('register') }}">{{ __('site.register') }}</a>
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

    <!-- =========================================================
         🚀 FLOATING AI & GO-TO-TOP BUTTONS (RIGHT BOTTOM)
         ========================================================= -->
    <div class="floating-container">
        <!-- AI Assistant Floating Button -->
        <a href="{{ route('ai.index') }}" class="floating-ai-btn" id="btnFloatingAi" title="AI Assistant">
            <i class="bi bi-robot"></i>
            <span class="ai-pulse-ring"></span>
        </a>

        <!-- Go To Top Button -->
        <button type="button" class="floating-top-btn" id="btnBackToTop" title="Top" onclick="scrollToTop()">
            <i class="bi bi-arrow-up"></i>
        </button>
    </div>

    <!-- Scripts (100% Local Bootstrap) -->
    <script src="{{ asset('asset/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Go to Top Visibility & Action
        const topBtn = document.getElementById('btnBackToTop');

        window.addEventListener('scroll', function () {
            if (window.scrollY > 280) {
                topBtn.classList.add('show');
            } else {
                topBtn.classList.remove('show');
            }
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function scrollToSearch() {
            const searchBar = document.querySelector('.search-container') || document.querySelector('form');
            if (searchBar) {
                searchBar.scrollIntoView({ behavior: 'smooth', block: 'center' });
                const searchInput = searchBar.querySelector('input');
                if (searchInput) searchInput.focus();
            } else {
                scrollToTop();
            }
        }
    </script>
</body>

</html>