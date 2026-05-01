@extends('layout')

@section('content')

<style>
    /* --- HERO SECTION --- */
    .hero-wrapper {
        position: relative;
        height: 600px;
        overflow: hidden;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
        z-index: 2;
    }

    .hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1);
        transition: transform 10s ease;
        /* Subtle Zoom Effect */
    }

    .active .hero-img {
        transform: scale(1.1);
    }

    .hero-content {
        position: absolute;
        top: 45%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 3;
        text-align: center;
        width: 100%;
    }

    /* --- GLASSMOPHISM SEARCH BAR --- */
    .search-container {
        position: relative;
        z-index: 10;
        margin-top: -60px;
        /* Pulls it up into the hero */
    }

    .search-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        border-radius: 100px;
        /* Pill shape on desktop */
        padding: 10px;
    }

    /* Mobile Search Adjustments */
    @media (max-width: 992px) {
        .search-card {
            border-radius: 20px;
            /* Box shape on mobile */
            padding: 20px;
            margin-top: -100px;
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
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        margin-bottom: 0;
        padding-left: 10px;
    }

    .search-divider {
        border-right: 1px solid #ddd;
        height: 40px;
        margin: auto 0;
    }

    /* --- LISTING CARDS --- */
    .listing-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .listing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .img-hover-zoom {
        height: 240px;
        overflow: hidden;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
        position: relative;
    }

    .img-hover-zoom img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .listing-card:hover .img-hover-zoom img {
        transform: scale(1.08);
    }

    /* Badges */
    .badge-year {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        color: #000;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.85rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .badge-price {
        color: #0d6efd;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    /* Attributes (Icons) */
    .attr-grid {
        display: flex;
        gap: 15px;
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 10px;
        border-top: 1px solid #f0f0f0;
        padding-top: 12px;
    }
</style>

<!-- 1. CINEMATIC HERO SECTION -->
@if($banners->count() > 0)
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner hero-wrapper">
        @foreach($banners as $key => $banner)
        <div class="carousel-item {{ $key == 0 ? 'active' : '' }} h-100">
            <div class="hero-overlay"></div>
            <img src="{{ asset('storage/' . $banner->image_path) }}" class="hero-img" alt="Hero Banner">

            @if($banner->title)
            <div class="hero-content">
                <h1 class="display-3 fw-bolder text-white mb-2" style="text-shadow: 0 4px 20px rgba(0,0,0,0.5);">
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
    <div class="text-center text-white">
        <h1 class="display-4 fw-bold">{{ __('site.Find Your Dream Car') }}</h1>
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
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
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
        <div class="col-md-6 col-lg-3">
            <div class="card listing-card position-relative">

                <!-- Image Area -->
                <div class="img-hover-zoom">
                    @if($car->image)
                    <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->title }}">
                    @else
                    <img src="https://via.placeholder.com/400x300?text=No+Photo" alt="Placeholder">
                    @endif
                    <span class="badge-year">{{ $car->year }}</span>
                </div>

                <!-- Content Area -->
                <div class="card-body p-3 pt-4">
                    <!-- Brand -->
                    <div class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                        {{ $car->brand->name ?? 'Car' }}
                    </div>

                    <!-- Title -->
                    <h5 class="card-title fw-bold text-dark mt-1 mb-2 text-truncate">
                        {{ $car->model }} <span class="fw-normal text-secondary">{{ \Illuminate\Support\Str::limit($car->title, 15) }}</span>
                    </h5>

                    <!-- Price -->
                    <div class="badge-price mb-3">
                        {{ number_format($car->price) }} <small class="fs-6 fw-normal text-dark">TMT</small>
                    </div>

                    <!-- Attributes Grid -->
                    <div class="attr-grid">
                        <div><i class="bi bi-calendar3 me-1"></i> {{ $car->created_at->format('M d') }}</div>
                        <div><i class="bi bi-person-circle me-1"></i> {{ __('site.Seller') }} </div>
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

<!-- 6. TRUST BANNER (Static) -->
<div class="bg-light py-5 mt-5">
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