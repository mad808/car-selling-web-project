@extends('layout')

@section('content')

<style>
    /* Feature Card Hover Effect */
    .feature-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        border-color: transparent;
    }

    .icon-box {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px auto;
        font-size: 2rem;
    }

    /* Hero Gradient Background */
    .about-hero {
        background: linear-gradient(135deg, #1a1d20 0%, #212529 100%);
        color: white;
        padding: 80px 0;
        border-radius: 0 0 50px 50px;
        /* Modern curve at bottom */
        margin-bottom: 60px;
        margin-top: -20px;
        /* Pull up to touch navbar if needed */
    }
</style>

<!-- 1. HERO SECTION -->
<div class="about-hero text-center position-relative overflow-hidden">
    <!-- Decorative Circle (Background) -->
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(13, 110, 253, 0.1); border-radius: 50%;"></div>

    <div class="container position-relative z-1">
        <span class="badge bg-primary bg-opacity-25 text-primary-emphasis border border-primary px-3 py-2 rounded-pill mb-3">Est. {{ date('Y') }}</span>
        <h1 class="display-3 fw-bold mb-3">{{ __('site.Driving the Future') }}</h1>
        <p class="lead text-secondary mx-auto" style="max-width: 600px;">
            {{ __('site.The most trusted digital marketplace to buy and sell cars in Turkmenistan. Simple, secure, and built for you.') }}
        </p>
    </div>
</div>

<div class="container">

    <!-- 2. STORY SECTION -->
    <div class="row align-items-center mb-5 pb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="position-relative">
                <!-- Main Image -->
                <img src="{{ asset('asset/img/tmcars.jpg') }}"
                    onerror="this.src='https://images.unsplash.com/photo-1560179707-f14e90ef3623?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'"
                    class="img-fluid rounded-4 shadow-lg"
                    alt="About TM Cars">

                <!-- Floating Badge -->
                <div class="bg-white p-3 rounded-4 shadow position-absolute bottom-0 end-0 m-4 d-none d-md-block" style="max-width: 200px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-star-fill text-warning fs-3 me-2"></i>
                        <div>
                            <h6 class="fw-bold mb-0">{{ __('site.#1 Choice') }}</h6>
                            <small class="text-muted">{{ __('site.In Turkmenistan') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 ps-lg-5">
            <h5 class="text-primary fw-bold text-uppercase ls-2">{{ __('site.Who We Are') }}</h5>
            <h2 class="fw-bold display-6 mb-4">{{ __('site.We connect buyers & sellers instantly.') }}</h2>
            <p class="text-muted lead">
                {{ __('site.Welcome to') }} <strong>Ulagym</strong>.{{ __('site.We are dedicated to giving you the best car trading experience, focusing on reliability, customer service, and speed.') }} 
            </p>
            <p class="text-muted mb-4">
                {{ __('site.Founded in') }} {{ date('Y') }}, Ulagym {{ __('site.started with a simple idea: make car buying easy. Today, we serve customers all over the country. Whether you are looking for a rugged SUV or a city commuter, we have the platform to help you find it.') }} 
            </p>

            <div class="row g-3">
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span class="fw-bold">{{ __('site.Verified Dealers') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span class="fw-bold">{{ __('site.Secure Data') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span class="fw-bold">{{ __('site.Easy Listings') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span class="fw-bold">{{ __('site.Free Support') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. STATS (Trust Builders) -->
    <div class="row text-center mb-5 pb-5">
        <div class="col-md-4 mb-3">
            <h2 class="fw-bold text-dark display-4">10k+</h2>
            <p class="text-uppercase text-muted fw-bold small">{{ __('site.Active Listings') }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <h2 class="fw-bold text-primary display-4">5k+</h2>
            <p class="text-uppercase text-muted fw-bold small">{{ __('site.Happy Sellers') }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <h2 class="fw-bold text-dark display-4">24/7</h2>
            <p class="text-uppercase text-muted fw-bold small">{{ __('site.Live Support') }}</p>
        </div>
    </div>

    <!-- 4. FEATURES -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">{{ __('site.Why Choose Us?') }}</h2>
        <p class="text-muted">{{ __('site.We provide the best tools to help you buy and sell.') }}</p>
    </div>

    <div class="row g-4 mb-5">
        <!-- Feature 1 -->
        <div class="col-md-4">
            <div class="card h-100 feature-card shadow-sm rounded-4 p-4 text-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-rocket-takeoff"></i>
                </div>
                <h5 class="fw-bold">{{ __('site.Fast Selling') }}</h5>
                <p class="text-muted">{{ __('site.List your car in under 2 minutes and reach thousands of potential buyers instantly.') }}</p>
            </div>
        </div>

        <!-- Feature 2 -->
        <div class="col-md-4">
            <div class="card h-100 feature-card shadow-sm rounded-4 p-4 text-center">
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h5 class="fw-bold">{{ __('site.Secure & Safe') }}</h5>
                <p class="text-muted">{{ __('site.We manually review listings and verify users to ensure a safe trading environment.') }}</p>
            </div>
        </div>

        <!-- Feature 3 -->
        <div class="col-md-4">
            <div class="card h-100 feature-card shadow-sm rounded-4 p-4 text-center">
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-headset"></i>
                </div>
                <h5 class="fw-bold">{{ __('site.Premium Support') }}</h5>
                <p class="text-muted">{{ __('site.Our local support team in Ashgabat is ready to help you via phone or email.') }}</p>
            </div>
        </div>
    </div>

    <!-- 5. CTA SECTION -->
    <div class="card bg-primary text-white border-0 rounded-4 shadow-lg overflow-hidden mb-5">
        <div class="card-body p-5 text-center position-relative">
            <!-- Background Decoration -->
            <i class="bi bi-envelope-paper position-absolute" style="font-size: 15rem; opacity: 0.1; right: -50px; top: -50px; transform: rotate(15deg);"></i>

            <div class="position-relative z-1">
                <h2 class="fw-bold mb-3">{{ __('site.Have Questions?') }}</h2>
                <p class="lead mb-4 opacity-75">{{ __('site.We are here to help you grow your business or find your dream car.') }}</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="mailto:support@tmcars.com" class="btn btn-light btn-lg rounded-pill fw-bold text-primary">
                        <i class="bi bi-envelope-fill me-2"></i> {{ __('site.Email Support') }}
                    </a>
                    <a href="tel:+99361000000" class="btn btn-outline-light btn-lg rounded-pill fw-bold">
                        <i class="bi bi-telephone-fill me-2"></i> {{ __('site.Call Us') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection