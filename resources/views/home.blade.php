@extends('layout')

@section('content')

<style>
    /* --- HERO SECTION --- */
    .hero-wrapper {
        position: relative;
        height: 560px;
        overflow: hidden;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.35) 0%, rgba(0, 0, 0, 0.72) 100%);
        z-index: 2;
    }

    .hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1);
        transition: transform 8s ease;
    }

    .active .hero-img {
        transform: scale(1.08);
    }

    .hero-content {
        position: absolute;
        top: 45%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 3;
        text-align: center;
        width: 90%;
        max-width: 850px;
    }

    /* --- GLASSMORPHISM SEARCH BAR --- */
    .search-container {
        position: relative;
        z-index: 10;
        margin-top: -55px;
    }

    .search-card {
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        border-radius: 100px;
        padding: 10px 14px;
    }

    @media (max-width: 992px) {
        .search-card {
            border-radius: 20px;
            padding: 20px;
            margin-top: -85px;
        }

        .search-divider {
            display: none;
        }
    }

    .search-input {
        border: none;
        background: transparent;
        box-shadow: none !important;
        font-weight: 500;
        padding-left: 10px;
    }

    .search-label {
        font-size: 0.73rem;
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

    /* --- LISTING CARDS --- */
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
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
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
        transform: scale(1.06);
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
        background: rgba(13, 110, 253, 0.9);
        color: #ffffff;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 0.72rem;
        letter-spacing: 0.4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        z-index: 2;
    }

    .badge-price {
        color: #0d6efd;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    /* Attributes Grid */
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

<!-- 1. CINEMATIC HERO SECTION -->
@if($banners->count() > 0)
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner hero-wrapper">
        @foreach($banners as $key => $banner)
        <div class="carousel-item {{ $key == 0 ? 'active' : '' }} h-100">
            <div class="hero-overlay"></div>
            
            @php
                $fallbackBanner = asset('asset/img/cars/2.png');
                $bannerImg = !empty($banner->image_path) ? asset('storage/' . $banner->image_path) : $fallbackBanner;
            @endphp
            <img src="{{ $bannerImg }}" class="hero-img" alt="Hero Banner" onerror="this.onerror=null; this.src='{{ $fallbackBanner }}';">

            @if($banner->title)
            <div class="hero-content">
                <h1 class="display-4 fw-bolder text-white mb-2" style="text-shadow: 0 4px 16px rgba(0,0,0,0.5);">
                    {{ $banner->title }}
                </h1>
                <p class="lead text-white-50 fw-normal">{{ __('site.Discover your next vehicle with confidence.') }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@else
<!-- Fallback Hero if no banners -->
<div class="hero-wrapper bg-dark d-flex align-items-center justify-content-center">
    <div class="text-center text-white px-3">
        <h1 class="display-4 fw-bold mb-2">{{ __('site.Find Your Dream Car') }}</h1>
        <p class="lead text-white-50">{{ __('site.Simple. Fast. Reliable.') }}</p>
    </div>
</div>
@endif

<!-- 2. FLOATING SEARCH BAR -->
<div class="container search-container mb-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <form action="{{ route('home') }}" method="GET">
                <div class="search-card">
                    <div class="row g-2 align-items-center">

                        <!-- Keywords -->
                        <div class="col-lg-3 position-relative">
                            <label class="search-label"><i class="bi bi-search me-1"></i> {{ __('site.Keywords') }}</label>
                            <input type="text" name="search" class="form-control search-input" placeholder="Camry, BMW..." value="{{ request('search') }}">
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

                        <!-- Price Max -->
                        <div class="col-lg-3 position-relative">
                            <label class="search-label"><i class="bi bi-cash me-1"></i> {{ __('site.Max Price') }}</label>
                            <input type="number" name="max_price" class="form-control search-input" placeholder="e.g. 100000" value="{{ request('max_price') }}">
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

    <!-- 3. SECTION HEADER -->
    <div class="d-flex justify-content-between align-items-end mb-4 px-2">
        <div>
            <h6 class="text-primary fw-bold text-uppercase letter-spacing-2 mb-1">{{ __('site.Inventory') }}</h6>
            <h2 class="fw-bold mb-0 text-dark">{{ __('site.Latest Arrivals') }}</h2>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-pill px-4 btn-sm">{{ __('site.View All') }}</a>
    </div>

    <!-- 4. CARS GRID -->
    <div class="row g-4">
        @forelse($cars as $car)
        @php
            // Safe Local Asset Fallback for Image
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
                    @if(!empty($car->ai_verdict))
                        <span class="badge-ai">
                            <i class="bi bi-robot me-1"></i>{{ $car->ai_verdict }}
                        </span>
                    @endif

                    <img src="{{ $carImg }}" alt="{{ $car->title }}" loading="lazy" onerror="this.onerror=null; this.src='{{ $fallbackImg }}';">
                    <span class="badge-year">{{ $car->year }}</span>
                </div>

                <!-- Content Area -->
                <div class="card-body p-3 pt-3">
                    <!-- Brand -->
                    <div class="text-uppercase text-muted fw-bold" style="font-size: 0.72rem; letter-spacing: 0.8px;">
                        {{ $car->brand->name ?? 'Car' }}
                    </div>

                    <!-- Title -->
                    <h5 class="card-title fw-bold text-dark mt-1 mb-2 text-truncate" title="{{ $car->title }}">
                        {{ $car->model }} <span class="fw-normal text-secondary">{{ \Illuminate\Support\Str::limit($car->title, 16) }}</span>
                    </h5>

                    <!-- Price -->
                    <div class="badge-price mb-2">
                        {{ number_format($car->price) }} <small class="fs-6 fw-normal text-dark">TMT</small>
                    </div>

                    <!-- Attributes Grid -->
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

    <!-- 5. PAGINATION -->
    <div class="d-flex justify-content-center mt-5">
        {{ $cars->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

</div>

<!-- 6. TRUST BANNER -->
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

@endsection