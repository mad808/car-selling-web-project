@extends('layout')

@section('content')

<!-- 1. Local Swiper Slider CSS -->
<link rel="stylesheet" href="{{ asset('asset/css/swiper-bundle.min.css') }}">

<style>
    /* =========================================================
       🚀 HERO SLIDER (LOCAL SWIPER ENGINE)
       ========================================================= */
    .hero-slider-container {
        position: relative;
        height: 560px;
        overflow: hidden;
        background-color: #0d0f12;
    }

    .swiper-slide {
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .hero-slide-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1);
        transition: transform 6s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .swiper-slide-active .hero-slide-img {
        transform: scale(1.08);
    }

    .hero-slide-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(13, 15, 18, 0.4) 0%, rgba(13, 15, 18, 0.78) 100%);
        z-index: 2;
    }

    .hero-slide-content {
        position: absolute;
        top: 45%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 3;
        text-align: center;
        width: 92%;
        max-width: 860px;
    }

    .hero-badge-ai {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(13, 110, 253, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(13, 110, 253, 0.5);
        color: #ffffff;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }

    /* Swiper Navigation Buttons */
    .hero-swiper-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff !important;
        transition: all 0.25s ease;
    }

    .hero-swiper-btn::after {
        font-size: 1.1rem !important;
        font-weight: bold;
    }

    .hero-swiper-btn:hover {
        background: #0d6efd;
        border-color: #0d6efd;
        transform: scale(1.08);
    }

    .swiper-pagination-bullet {
        background: rgba(255, 255, 255, 0.5) !important;
        opacity: 0.6;
        width: 10px;
        height: 10px;
        transition: all 0.3s ease;
    }

    .swiper-pagination-bullet-active {
        background: #0d6efd !important;
        opacity: 1;
        width: 26px;
        border-radius: 6px;
    }

    /* =========================================================
       🔍 FLOATING SEARCH BAR
       ========================================================= */
    .search-container {
        position: relative;
        z-index: 15;
        margin-top: -55px;
    }

    .search-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.14);
        border-radius: 100px;
        padding: 10px 16px;
    }

    @media (max-width: 992px) {
        .search-card {
            border-radius: 20px;
            padding: 20px;
            margin-top: -85px;
        }
        .search-divider { display: none; }
    }

    .search-input {
        border: none;
        background: transparent;
        box-shadow: none !important;
        font-weight: 500;
        padding-left: 10px;
    }

    .search-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        margin-bottom: 0;
        padding-left: 10px;
    }

    .search-divider {
        border-right: 1px solid #e9ecef;
        height: 38px;
        margin: auto 0;
    }

    /* =========================================================
       🤖 AI PROMO BANNER (AUTOR.GIF INTEGRATION)
       ========================================================= */
    .ai-showcase-card {
        background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 60%, #0d6efd 150%);
        border: 1px solid rgba(13, 110, 253, 0.35);
        border-radius: 22px;
        padding: 24px 30px;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        box-shadow: 0 12px 35px rgba(13, 110, 253, 0.18);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .ai-showcase-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 45px rgba(13, 110, 253, 0.28);
    }

    .ai-gif-avatar {
        width: 110px;
        height: 110px;
        border-radius: 24px;
        object-fit: cover;
        border: 2px solid rgba(13, 110, 253, 0.6);
        box-shadow: 0 0 25px rgba(13, 110, 253, 0.45);
        background: #000;
        flex-shrink: 0;
    }

    .ai-smart-chip {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #e2e8f0;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .ai-smart-chip:hover {
        background: #0d6efd;
        color: #ffffff;
        border-color: #0d6efd;
        transform: translateY(-2px);
    }

    /* =========================================================
       🚗 CAR LISTING CARDS
       ========================================================= */
    .listing-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        height: 100%;
        overflow: hidden;
    }

    .listing-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.09);
    }

    .img-hover-zoom {
        height: 220px;
        overflow: hidden;
        position: relative;
        background-color: #f1f3f5;
    }

    .img-hover-zoom img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }

    .listing-card:hover .img-hover-zoom img {
        transform: scale(1.07);
    }

    /* Badges */
    .badge-year {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255, 255, 255, 0.95);
        color: #111;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 0.8rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        z-index: 2;
    }

    .badge-ai {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(13, 110, 253, 0.92);
        color: #ffffff;
        font-weight: 700;
        padding: 4px 11px;
        border-radius: 30px;
        font-size: 0.73rem;
        letter-spacing: 0.4px;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.35);
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-price {
        color: #0d6efd;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .attr-grid {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.82rem;
        color: #6c757d;
        margin-top: 8px;
        border-top: 1px solid #f1f3f5;
        padding-top: 10px;
    }
</style>

<!-- =========================================================
     1. HERO SWIPER SLIDER (LOCAL JS SLIDING)
     ========================================================= -->
<div class="hero-slider-container">
    <div class="swiper heroSwiper h-100">
        <div class="swiper-wrapper">

            @php
                $fallbackBanners = [
                    asset('asset/img/banner1.jpg'),
                    asset('asset/img/banner2.jpg'),
                    asset('asset/img/banner3.jpg'),
                ];
            @endphp

            @if(isset($banners) && $banners->count() > 0)
                @foreach($banners as $key => $banner)
                    @php
                        $bImg = !empty($banner->image_path) ? asset('storage/' . $banner->image_path) : ($fallbackBanners[$key % count($fallbackBanners)]);
                    @endphp
                    <div class="swiper-slide">
                        <div class="hero-slide-overlay"></div>
                        <img src="{{ $bImg }}" class="hero-slide-img" alt="Banner" onerror="this.onerror=null; this.src='{{ $fallbackBanners[0] }}';">

                        <div class="hero-slide-content">
                            <div class="hero-badge-ai">
                                <img src="{{ asset('asset/img/Autor.gif') }}" width="20" height="20" class="rounded-circle" alt="AI">
                                <span>{{ __('site.AI Smart Appraisal') }} • Ulagym</span>
                            </div>

                            <h1 class="display-4 fw-bolder text-white mb-2" style="text-shadow: 0 4px 20px rgba(0,0,0,0.65);">
                                {{ $banner->title ?: __('site.Find Your Dream Car') }}
                            </h1>
                            <p class="lead text-white-50 fw-normal">
                                {{ __('site.Discover your next vehicle with confidence.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Fallback Local Sliding Banners -->
                @foreach($fallbackBanners as $idx => $fImg)
                    <div class="swiper-slide">
                        <div class="hero-slide-overlay"></div>
                        <img src="{{ $fImg }}" class="hero-slide-img" alt="Hero Banner" onerror="this.onerror=null; this.src='{{ asset('asset/img/cars/2.png') }}';">

                        <div class="hero-slide-content">
                            <div class="hero-badge-ai">
                                <img src="{{ asset('asset/img/Autor.gif') }}" width="20" height="20" class="rounded-circle" alt="AI">
                                <span>{{ __('site.AI Smart Appraisal') }} • Ulagym</span>
                            </div>
                            <h1 class="display-4 fw-bolder text-white mb-2" style="text-shadow: 0 4px 20px rgba(0,0,0,0.65);">
                                {{ __('site.Find Your Dream Car') }}
                            </h1>
                            <p class="lead text-white-50 fw-normal">
                                {{ __('site.Simple. Fast. Reliable.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

        <!-- Slider Controls -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next hero-swiper-btn"></div>
        <div class="swiper-button-prev hero-swiper-btn"></div>
    </div>
</div>

<!-- =========================================================
     2. FLOATING SEARCH BAR
     ========================================================= -->
<div class="container search-container mb-4">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <form action="{{ route('home') }}" method="GET">
                <div class="search-card">
                    <div class="row g-2 align-items-center">

                        <!-- Keywords -->
                        <div class="col-lg-3 position-relative">
                            <label class="search-label"><i class="bi bi-search me-1"></i> {{ __('site.Keywords') }}</label>
                            <input type="text" name="search" class="form-control search-input" placeholder="Camry, BMW, Elantra..." value="{{ request('search') }}">
                        </div>

                        <div class="col-auto search-divider d-none d-lg-block"></div>

                        <!-- Brand -->
                        <div class="col-lg-3 position-relative">
                            <label class="search-label"><i class="bi bi-tag me-1"></i> {{ __('site.Brand') }}</label>
                            <select name="brand_id" class="form-select search-input" style="cursor: pointer;">
                                <option value="">{{ __('site.All Brands') }}</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto search-divider d-none d-lg-block"></div>

                        <!-- Max Price -->
                        <div class="col-lg-3 position-relative">
                            <label class="search-label"><i class="bi bi-cash me-1"></i> {{ __('site.Max Price') }}</label>
                            <input type="number" name="max_price" class="form-control search-input" placeholder="e.g. 50000" value="{{ request('max_price') }}">
                        </div>

                        <!-- Search Button -->
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm">
                                {{ __('site.Search') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container mb-5">

    <!-- =========================================================
         🤖 3. AI POWERED SPOTLIGHT (AUTOR.GIF INTEGRATION)
         ========================================================= -->
    <div class="row justify-content-center mb-5">
        <div class="col-xl-10">
            <div class="ai-showcase-card d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
                
                <!-- Left: AI Avatar GIF & Info -->
                <div class="d-flex align-items-center gap-3 text-center text-md-start">
                    <img src="{{ asset('asset/img/Autor.gif') }}" class="ai-gif-avatar" alt="Ulagym AI Assistant">
                    <div>
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                            <span class="badge bg-primary px-2.5 py-1 rounded-pill small">AI Online</span>
                            <span class="text-white-50 small">{{ __('site.AI Smart Appraisal') }}</span>
                        </div>
                        <h4 class="fw-bold mb-1 text-white">Ulagym AI Assistant</h4>
                        <p class="text-light opacity-75 small mb-0" style="max-width: 440px; line-height: 1.5;">
                            {{ __('site.Discover your next vehicle with confidence.') }}
                        </p>
                    </div>
                </div>

                <!-- Right: Quick Questions & Open Chat -->
                <div class="d-flex flex-column align-items-center align-items-md-end gap-2">
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2">
                        <a href="{{ route('ai.index') }}" class="ai-smart-chip">
                            <i class="bi bi-currency-dollar"></i> $15,000 býujet
                        </a>
                        <a href="{{ route('ai.index') }}" class="ai-smart-chip">
                            <i class="bi bi-shield-check"></i> Ulag barlagy
                        </a>
                        <a href="{{ route('ai.index') }}" class="ai-smart-chip">
                            <i class="bi bi-lightning-charge"></i> Gibrid vs Benzin
                        </a>
                    </div>
                    <a href="{{ route('ai.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2 mt-1">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span>AI Assistant</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- =========================================================
         4. CARS SECTION HEADER
         ========================================================= -->
    <div class="d-flex justify-content-between align-items-end mb-4 px-2">
        <div>
            <h6 class="text-primary fw-bold text-uppercase letter-spacing-2 mb-1">{{ __('site.Inventory') }}</h6>
            <h2 class="fw-bold mb-0 text-dark">{{ __('site.Latest Arrivals') }}</h2>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-pill px-4 btn-sm">{{ __('site.View All') }}</a>
    </div>

    <!-- =========================================================
         5. CARS GRID (AI VALUATION BADGES & LOCAL FALLBACK)
         ========================================================= -->
    <div class="row g-4">
        @forelse($cars as $car)
        @php
            $fallbackImg = asset('asset/img/cars/2.png');
            $carImg = $fallbackImg;
            
            if (!empty($car->image)) {
                if (str_starts_with($car->image, 'http')) {
                    $carImg = $car->image;
                } elseif (file_exists(public_path($car->image))) {
                    $carImg = asset($car->image);
                } elseif (file_exists(public_path('asset/img/' . $car->image))) {
                    $carImg = asset('asset/img/' . $car->image);
                } elseif (file_exists(public_path('storage/' . $car->image))) {
                    $carImg = asset('storage/' . $car->image);
                }
            }
        @endphp

        <div class="col-md-6 col-lg-3">
            <div class="card listing-card position-relative">

                <!-- Image Area -->
                <div class="img-hover-zoom">
                    <!-- AI Verdict Badge if exists -->
                    <!-- @if(!empty($car->ai_verdict))
                        <span class="badge-ai" title="{{ $car->ai_review }}">
                            <img src="{{ asset('asset/img/Autor.gif') }}" width="15" height="15" class="rounded-circle" alt="AI">
                            <span>{{ __('site.' . $car->ai_verdict) ?? $car->ai_verdict }}</span>
                        </span>
                    @endif -->

                    <img src="{{ $carImg }}" alt="{{ $car->title }}" loading="lazy" onerror="this.onerror=null; this.src='{{ $fallbackImg }}';">
                    <span class="badge-year">{{ $car->year }}</span>
                </div>

                <!-- Content Area -->
                <div class="card-body p-3 pt-3">
                    <div class="text-uppercase text-muted fw-bold" style="font-size: 0.72rem; letter-spacing: 0.8px;">
                        {{ $car->brand->name ?? 'Car' }}
                    </div>

                    <h5 class="card-title fw-bold text-dark mt-1 mb-2 text-truncate" title="{{ $car->title }}">
                        {{ $car->model }} <span class="fw-normal text-secondary">{{ \Illuminate\Support\Str::limit($car->title, 16) }}</span>
                    </h5>

                    <div class="badge-price mb-2">
                        {{ number_format($car->price) }} <small class="fs-6 fw-normal text-dark">TMT</small>
                    </div>

                    <div class="attr-grid">
                        <span><i class="bi bi-calendar3 me-1"></i> {{ $car->created_at->format('M d') }}</span>
                        <span><i class="bi bi-person-circle me-1"></i> {{ __('site.Seller') }}</span>
                    </div>
                </div>

                <!-- Full Card Click -->
                <a href="{{ route('cars.show', $car->id) }}" class="stretched-link"></a>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="col-12 py-5 text-center">
            <div class="bg-light rounded-4 p-5 d-inline-block">
                <i class="bi bi-search display-1 text-muted mb-3 d-block"></i>
                <h3 class="fw-bold text-dark">{{ __('site.No Listings Found') }}</h3>
                <p class="text-muted">{{ __('site.Adjust your filters to find what you are looking for.') }}</p>
                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4">{{ __('site.Clear Filters') }}</a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $cars->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

</div>

<!-- =========================================================
     6. TRUST BANNER
     ========================================================= -->
<div class="bg-light py-5 mt-5 border-top">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="p-3">
                    <i class="bi bi-shield-check display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">{{ __('site.Verified Sellers') }}</h5>
                    <p class="text-muted small">{{ __('site.We check our users to ensure a safe environment.') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <i class="bi bi-lightning-charge display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">{{ __('site.Fast Selling') }}</h5>
                    <p class="text-muted small">{{ __('site.List your car and connect with buyers in minutes.') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <i class="bi bi-headset display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">{{ __('site.24/7 Support') }}</h5>
                    <p class="text-muted small">{{ __('site.Our team is always here to help you.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     7. LOCAL SWIPER JS SCRIPT
     ========================================================= -->
<script src="{{ asset('asset/js/swiper-bundle.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const heroSwiper = new Swiper('.heroSwiper', {
            loop: true,
            speed: 900,
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>

@endsection