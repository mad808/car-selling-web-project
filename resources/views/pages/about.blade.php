@extends('layout')

@section('content')

<style>
    /* Hero Gradient & Wave */
    .about-hero {
        background: linear-gradient(135deg, #111315 0%, #1a1d20 100%);
        color: #ffffff;
        padding: 70px 0 85px 0;
        border-radius: 0 0 35px 35px;
        position: relative;
        overflow: hidden;
    }

    .hero-glow-circle {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(13, 110, 253, 0.25) 0%, rgba(13, 110, 253, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Soft Color Palette */
    .bg-soft-primary {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .bg-soft-success {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .bg-soft-warning {
        background-color: rgba(255, 193, 7, 0.15);
        color: #b78103;
    }

    .bg-soft-info {
        background-color: rgba(13, 202, 240, 0.12);
        color: #0aa2c0;
    }

    /* Feature Cards */
    .feature-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 28px 22px;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        height: 100%;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.08) !important;
        border-color: rgba(13, 110, 253, 0.25);
    }

    .feature-icon-box {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px auto;
        font-size: 1.7rem;
    }

    /* Stat Cards */
    .stat-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 24px 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    /* Floating Image Badge */
    .floating-img-badge {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    /* Checklist Badge */
    .check-pill {
        background-color: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        padding: 10px 14px;
    }
</style>

@php
    // Lokal surat ýoly we howpsuz fallback
    $fallbackImg = asset('asset/img/cars/2.png');
    $aboutImg = $fallbackImg;
    
    if (file_exists(public_path('asset/img/tmcars.jpg'))) {
        $aboutImg = asset('asset/img/tmcars.jpg');
    } elseif (file_exists(public_path('assets/img/sliders/tmcars.jpg'))) {
        $aboutImg = asset('assets/img/sliders/tmcars.jpg');
    }
@endphp

<!-- 1. HERO SECTION -->
<div class="about-hero text-center mb-5">
    <div class="hero-glow-circle"></div>

    <div class="container position-relative z-1">
        <span class="badge bg-primary bg-opacity-25 text-white border border-primary px-3 py-2 rounded-pill mb-3 fs-7">
            Est. {{ date('Y') }} • Ulagym
        </span>
        <h1 class="display-4 fw-bolder mb-3">{{ __('site.Driving the Future') }}</h1>
        <p class="lead text-white-50 mx-auto" style="max-width: 650px; font-size: 1.05rem; line-height: 1.7;">
            {{ __('site.The most trusted digital marketplace to buy and sell cars in Turkmenistan. Simple, secure, and built for you.') }}
        </p>
    </div>
</div>

<div class="container">

    <!-- 2. STORY SECTION -->
    <div class="row align-items-center mb-5 pb-4 g-4">
        <!-- Surat Bölümi -->
        <div class="col-lg-6">
            <div class="position-relative">
                <img src="{{ $aboutImg }}"
                     class="img-fluid rounded-4 shadow border w-100"
                     style="max-height: 420px; object-fit: cover;"
                     alt="About Ulagym"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='{{ $fallbackImg }}';">

                <!-- Floating Badge -->
                <div class="floating-img-badge p-3 rounded-4 position-absolute bottom-0 end-0 m-3 m-md-4 d-flex align-items-center gap-3">
                    <div class="bg-soft-warning p-2 rounded-3 text-warning fs-3 leading-none d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ __('site.#1 Choice') }}</h6>
                        <small class="text-muted">{{ __('site.In Turkmenistan') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tekst Bölümi -->
        <div class="col-lg-6 ps-lg-5">
            <h6 class="text-primary fw-bold text-uppercase letter-spacing-1 mb-2">{{ __('site.Who We Are') }}</h6>
            <h2 class="fw-bold mb-3 text-dark">{{ __('site.We connect buyers & sellers instantly.') }}</h2>
            
            <p class="text-muted" style="line-height: 1.75; font-size: 0.98rem;">
                {{ __('site.Welcome to') }} <strong class="text-dark">Ulagym</strong>. {{ __('site.We are dedicated to giving you the best car trading experience, focusing on reliability, customer service, and speed.') }}
            </p>
            <p class="text-muted mb-4" style="line-height: 1.75; font-size: 0.93rem;">
                {{ __('site.Founded in') }} {{ date('Y') }}, Ulagym {{ __('site.started with a simple idea: make car buying easy. Today, we serve customers all over the country. Whether you are looking for a rugged SUV or a city commuter, we have the platform to help you find it.') }}
            </p>

            <!-- Barlanan Aýratynlyklar (Checklist) -->
            <div class="row g-2">
                <div class="col-6">
                    <div class="check-pill d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="fw-semibold text-dark small">{{ __('site.Verified Dealers') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="check-pill d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="fw-semibold text-dark small">{{ __('site.Secure Data') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="check-pill d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="fw-semibold text-dark small">{{ __('site.Easy Listings') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="check-pill d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="fw-semibold text-dark small">{{ __('site.Free Support') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. STATISTIKA KARTLARY -->
    <div class="row text-center mb-5 pb-3 g-3">
        <div class="col-md-4">
            <div class="stat-card">
                <h2 class="fw-bold text-dark display-5 mb-1">10k+</h2>
                <span class="text-muted fw-bold small text-uppercase">{{ __('site.Active Listings') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card border-primary border-opacity-25">
                <h2 class="fw-bold text-primary display-5 mb-1">5k+</h2>
                <span class="text-muted fw-bold small text-uppercase">{{ __('site.Happy Sellers') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h2 class="fw-bold text-dark display-5 mb-1">24/7</h2>
                <span class="text-muted fw-bold small text-uppercase">{{ __('site.Live Support') }}</span>
            </div>
        </div>
    </div>

    <!-- 4. AÝRATYNLYKLAR (FEATURES) -->
    <div class="text-center mb-4">
        <h6 class="text-primary fw-bold text-uppercase letter-spacing-1 mb-1">{{ __('site.Inventory') }}</h6>
        <h2 class="fw-bold text-dark">{{ __('site.Why Choose Us?') }}</h2>
        <p class="text-muted mx-auto" style="max-width: 500px;">{{ __('site.We provide the best tools to help you buy and sell.') }}</p>
    </div>

    <div class="row g-4 mb-5">
        <!-- 1. Çalt Satmak -->
        <div class="col-md-6 col-lg-3">
            <div class="feature-card">
                <div class="feature-icon-box bg-soft-primary">
                    <i class="bi bi-rocket-takeoff-fill"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">{{ __('site.Fast Selling') }}</h5>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    {{ __('site.List your car in under 2 minutes and reach thousands of potential buyers instantly.') }}
                </p>
            </div>
        </div>

        <!-- 2. Howpsuzlyk -->
        <div class="col-md-6 col-lg-3">
            <div class="feature-card">
                <div class="feature-icon-box bg-soft-success">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">{{ __('site.Secure & Safe') }}</h5>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    {{ __('site.We manually review listings and verify users to ensure a safe trading environment.') }}
                </p>
            </div>
        </div>

        <!-- 3. Lokal AI Maslahatçy (Öňki açar söz ulandy) -->
        <div class="col-md-6 col-lg-3">
            <div class="feature-card border-primary border-opacity-25">
                <div class="feature-icon-box bg-primary text-white shadow-sm">
                    <i class="bi bi-robot"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">{{ __('site.AI Smart Appraisal') }}</h5>
                <p class="text-muted small mb-3" style="line-height: 1.6;">
                    {{ __('site.Discover your next vehicle with confidence.') }}
                </p>
                <a href="{{ route('ai.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                    <i class="bi bi-chat-dots me-1"></i> AI Chat
                </a>
            </div>
        </div>

        <!-- 4. Goldaw -->
        <div class="col-md-6 col-lg-3">
            <div class="feature-card">
                <div class="feature-icon-box bg-soft-warning">
                    <i class="bi bi-headset"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">{{ __('site.Premium Support') }}</h5>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    {{ __('site.Our local support team in Ashgabat is ready to help you via phone or email.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- 5. ARAGATNAŞYK BLOKY (CTA SECTION) -->
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-5" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
        <div class="card-body p-4 p-md-5 text-center text-white position-relative">
            <div class="position-relative z-1 py-2">
                <h2 class="fw-bold mb-2">{{ __('site.Have Questions?') }}</h2>
                <p class="lead mb-4 text-white-50 mx-auto" style="max-width: 580px; font-size: 1.05rem;">
                    {{ __('site.We are here to help you grow your business or find your dream car.') }}
                </p>
                
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="mailto:eziz5505@gmail.com" class="btn btn-light btn-lg rounded-pill fw-bold text-primary px-4 shadow-sm fs-6">
                        <i class="bi bi-envelope-fill me-2"></i> {{ __('site.Email Support') }}
                    </a>
                    <a href="tel:+99362240774" class="btn btn-outline-light btn-lg rounded-pill fw-bold px-4 fs-6">
                        <i class="bi bi-telephone-fill me-2"></i> {{ __('site.Call Us') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection