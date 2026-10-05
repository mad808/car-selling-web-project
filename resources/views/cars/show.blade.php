@extends('layout')

@section('content')

<style>
    /* Main Car Image Box */
    .car-main-wrapper {
        position: relative;
        background-color: #f1f3f5;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .car-main-image {
        width: 100%;
        max-height: 480px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    /* Soft Colors */
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

    .bg-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .bg-soft-info {
        background-color: rgba(13, 202, 240, 0.12);
        color: #0aa2c0;
    }

    /* Spec Box */
    .spec-card-item {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        height: 100%;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .spec-card-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
        border-color: #dee2e6;
    }

    .spec-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    /* AI Card */
    .ai-review-card {
        background: linear-gradient(145deg, #ffffff, #f7faff);
        border: 1px solid rgba(13, 110, 253, 0.2) !important;
        border-radius: 16px;
    }

    /* Seller Card */
    .seller-avatar {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    }

    .sticky-sidebar {
        position: sticky;
        top: 90px;
    }

    /* Related Cars */
    .related-card {
        border: none;
        border-radius: 14px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        height: 100%;
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }

    .related-img-wrapper {
        height: 190px;
        overflow: hidden;
        position: relative;
        background-color: #f1f3f5;
    }

    .related-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .related-card:hover .related-img {
        transform: scale(1.05);
    }
</style>

@php
    // Esasy ulagyň suratyny howpsuz anyklamak
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

<div class="container mt-4 mb-5">

    <!-- Breadcrumb Nav -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">{{ __('site.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home', ['brand_id' => $car->brand_id]) }}" class="text-decoration-none text-muted">{{ $car->brand->name ?? 'Car' }}</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $car->model }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- ÇEP TARAP: Surat, Aýratynlyklar, AI Synçy we Düşündiriş -->
        <div class="col-lg-8">

            <!-- 1. Esasy Ulag Suraty -->
            <div class="car-main-wrapper shadow-sm mb-4">
                <img src="{{ $carImg }}" 
                     class="car-main-image" 
                     alt="{{ $car->title }}"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='{{ $fallbackImg }}';">

                <!-- Ýyl we AI Badgelary -->
                <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                    <span class="badge bg-dark bg-opacity-75 backdrop-blur px-3 py-2 rounded-pill fs-6 shadow-sm">
                        {{ $car->year }}
                    </span>
                </div>

                @if($car->is_sold)
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-secondary px-3 py-2 rounded-pill fs-6 shadow-sm">
                            <i class="bi bi-bag-check-fill me-1"></i> {{ __('site.Sold') }}
                        </span>
                    </div>
                @elseif(!empty($car->ai_verdict))
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-primary px-3 py-2 rounded-pill fs-6 shadow-sm">
                            <i class="bi bi-robot me-1"></i> {{ __('site.' . $car->ai_verdict) ?? $car->ai_verdict }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- 2. Tehniki Aýratynlyklar (Specs Grid) -->
            <div class="row g-3 mb-4">
                <!-- Marka -->
                <div class="col-6 col-md-4">
                    <div class="spec-card-item">
                        <div class="spec-icon-box bg-soft-primary">
                            <i class="bi bi-tag-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block text-uppercase" style="font-size: 11px;">{{ __('site.Brand') }}</small>
                            <span class="fw-bold text-dark">{{ $car->brand->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Model -->
                <div class="col-6 col-md-4">
                    <div class="spec-card-item">
                        <div class="spec-icon-box bg-soft-primary">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block text-uppercase" style="font-size: 11px;">{{ __('site.Model') }}</small>
                            <span class="fw-bold text-dark">{{ $car->model }}</span>
                        </div>
                    </div>
                </div>

                <!-- Ýyly -->
                <div class="col-6 col-md-4">
                    <div class="spec-card-item">
                        <div class="spec-icon-box bg-soft-info">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block text-uppercase" style="font-size: 11px;">{{ __('site.Year') }}</small>
                            <span class="fw-bold text-dark">{{ $car->year }}</span>
                        </div>
                    </div>
                </div>

                <!-- Probeg (Mileage) -->
                <div class="col-6 col-md-4">
                    <div class="spec-card-item">
                        <div class="spec-icon-box bg-soft-warning">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block text-uppercase" style="font-size: 11px;">{{ __('site.Mileage') }}</small>
                            <span class="fw-bold text-dark">{{ number_format($car->mileage ?? 0) }} km</span>
                        </div>
                    </div>
                </div>

                <!-- Ýangyç Görnüşi -->
                <div class="col-6 col-md-4">
                    <div class="spec-card-item">
                        <div class="spec-icon-box bg-soft-success">
                            <i class="bi bi-fuel-pump-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block text-uppercase" style="font-size: 11px;">{{ __('site.Fuel Type') }}</small>
                            <span class="fw-bold text-dark">{{ $car->fuel_type ?: 'Petrol' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Geçiriji Guty -->
                <div class="col-6 col-md-4">
                    <div class="spec-card-item">
                        <div class="spec-icon-box bg-soft-secondary">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block text-uppercase" style="font-size: 11px;">{{ __('site.Transmission') }}</small>
                            <span class="fw-bold text-dark">{{ $car->transmission ?: 'Automatic' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. 🤖 Lokal AI Synçy Kerti (AI Smart Appraisal) -->
            @if(!empty($car->ai_review))
            <div class="card border-0 shadow-sm ai-review-card p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="spec-icon-box bg-primary text-white">
                            <i class="bi bi-robot"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">{{ __('site.AI Smart Appraisal') }}</h6>
                            <small class="text-muted" style="font-size: 11px;">Local Diagnostic & Price Evaluation</small>
                        </div>
                    </div>

                    @if(!empty($car->ai_verdict))
                        <span class="badge bg-primary px-3 py-2 rounded-pill">
                            {{ __('site.' . $car->ai_verdict) ?? $car->ai_verdict }}
                        </span>
                    @endif
                </div>

                <div class="p-3 bg-white border rounded-3 text-secondary" style="font-size: 0.93rem; line-height: 1.65;">
                    {{ $car->ai_review }}
                </div>
            </div>
            @endif

            <!-- 4. Düşündiriş (Description) -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">{{ __('site.Description') }}</h5>
                    <div class="text-secondary" style="line-height: 1.75; white-space: pre-line; font-size: 0.95rem;">
                        {{ $car->description ?: __('site.No description provided.') }}
                    </div>
                </div>
            </div>

        </div>

        <!-- SAG TARAP: Baha, Satyjynyň Maglumatlary we AI Çat Linki -->
        <div class="col-lg-4">
            <div class="sticky-sidebar">

                <!-- Baha Kerti -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body p-4">
                        <h6 class="text-muted mb-1 text-truncate" title="{{ $car->title }}">{{ $car->title }}</h6>
                        <h2 class="text-primary fw-bold mb-3">
                            {{ number_format($car->price) }} <small class="fs-6 fw-normal text-muted">TMT</small>
                        </h2>

                        <hr class="my-3 opacity-25">

                        <!-- Satyjynyň maglumatlary -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="seller-avatar me-3">
                                {{ strtoupper(substr($car->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('site.Seller') }}</small>
                                <span class="fw-bold text-dark">{{ $car->user->name ?? 'User' }}</span>
                            </div>
                        </div>

                        <!-- Jaň etmek düwmesi -->
                        <div class="d-grid gap-2">
                            @if(!empty($car->user->phone))
                                <a href="tel:{{ $car->user->phone }}" class="btn btn-success btn-lg fw-bold shadow-sm rounded-pill py-2.5">
                                    <i class="bi bi-telephone-fill me-2"></i> {{ $car->user->phone }}
                                </a>
                            @else
                                <button class="btn btn-secondary btn-lg rounded-pill" disabled>{{ __('site.Seller') }}</button>
                            @endif

                            <!-- AI Maslahatçydan bu ulag barada soramak -->
                            <a href="{{ route('ai.index') }}" class="btn btn-outline-primary rounded-pill py-2 fw-semibold">
                                <i class="bi bi-robot me-1"></i> AI Assistant
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i> {{ __('site.Date Added') }}: {{ $car->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Admin / Ulag Eýesiniň Dolandyryş Kerti -->
                @auth
                @if(Auth::id() == $car->user_id || Auth::user()->role == 'admin')
                <div class="card border-0 shadow-sm rounded-3 bg-danger bg-opacity-10 mb-3">
                    <div class="card-body p-3">
                        <h6 class="text-danger fw-bold mb-1">
                            <i class="bi bi-gear-fill me-1"></i> {{ __('site.Manage Listing') }}
                        </h6>
                        <p class="small text-muted mb-3">{{ __('site.Danger Zone') }}</p>

                        <form action="{{ route('cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('{{ __('site.Are you sure you want to permanently delete this car?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 btn-sm rounded-pill py-2 fw-bold">
                                <i class="bi bi-trash me-1"></i> {{ __('site.Delete Listing') }}
                            </button>
                        </form>
                    </div>
                </div>
                @endif
                @endauth

            </div>
        </div>
    </div>

    <!-- ================= MEŇZEŞ ULAĞLAR (RELATED CARS) ================= -->
    @if(isset($relatedCars) && $relatedCars->count() > 0)
    <div class="mt-5 pt-4 border-top">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">{{ __('site.Similar listings you might like') }}</h4>
            </div>
            <a href="{{ route('home', ['brand_id' => $car->brand_id]) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                {{ __('site.View All') }} {{ $car->brand->name ?? '' }}
            </a>
        </div>

        <div class="row g-4">
            @foreach($relatedCars as $related)
            @php
                $relImg = $fallbackImg;
                if (!empty($related->image)) {
                    if (str_starts_with($related->image, 'http')) {
                        $relImg = $related->image;
                    } elseif (file_exists(public_path($related->image))) {
                        $relImg = asset($related->image);
                    } elseif (file_exists(public_path('asset/img/' . $related->image))) {
                        $relImg = asset('asset/img/' . $related->image);
                    } elseif (file_exists(public_path('storage/' . $related->image))) {
                        $relImg = asset('storage/' . $related->image);
                    }
                }
            @endphp
            <div class="col-md-6 col-lg-3">
                <div class="card related-card">
                    <div class="position-relative related-img-wrapper">
                        <img src="{{ $relImg }}" 
                             class="related-img" 
                             alt="{{ $related->title }}" 
                             loading="lazy"
                             onerror="this.onerror=null; this.src='{{ $fallbackImg }}';">
                        
                        <span class="badge bg-dark bg-opacity-75 rounded-pill position-absolute top-0 end-0 m-2 px-2.5 py-1 small">
                            {{ $related->year }}
                        </span>
                        
                        <a href="{{ route('cars.show', $related->id) }}" class="stretched-link"></a>
                    </div>

                    <div class="card-body p-3">
                        <div class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.8px;">
                            {{ $related->brand->name ?? 'Car' }}
                        </div>
                        <h6 class="card-title fw-bold text-dark text-truncate mb-1" title="{{ $related->title }}">
                            {{ $related->model }}
                        </h6>
                        <div class="text-primary fw-bold">
                            {{ number_format($related->price) }} <small class="text-muted fw-normal">TMT</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <!-- ================= END RELATED CARS ================= -->

</div>
@endsection