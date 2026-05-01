@extends('layout')

@section('content')

<!-- Custom CSS for this page -->
<style>
    /* Main Car Styling */
    .car-main-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 12px;
    }

    .spec-box {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        transition: transform 0.2s;
    }

    .spec-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .spec-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .spec-value {
        font-weight: 700;
        font-size: 1.1rem;
        color: #212529;
    }

    .seller-avatar {
        width: 50px;
        height: 50px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #adb5bd;
    }

    .sticky-sidebar {
        position: sticky;
        top: 90px;
    }

    /* --- RELATED CARS STYLING --- */
    .related-card {
        border: none;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        height: 100%;
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .related-img-wrapper {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .related-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .related-card:hover .related-img {
        transform: scale(1.05);
    }

    .related-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 4px;
    }
</style>

<div class="container mt-4 mb-5">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('site.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $car->brand->name ?? 'Car' }} {{ $car->model }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- LEFT SIDE: Image & Description -->
        <div class="col-lg-8">

            <!-- Main Image -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-2">
                    @if($car->image)
                    <img src="{{ asset('storage/' . $car->image) }}" class="car-main-image shadow-sm" alt="{{ $car->title }}">
                    @else
                    <img src="https://via.placeholder.com/800x500?text=No+Image+Available" class="car-main-image shadow-sm" alt="No Image">
                    @endif
                </div>
            </div>

            <!-- Quick Specs Grid -->
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="spec-box">
                        <div class="spec-label">{{ __('site.Brand') }}</div>
                        <div class="spec-value">{{ $car->brand->name ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="spec-box">
                        <div class="spec-label">{{ __('site.Model') }}</div>
                        <div class="spec-value">{{ $car->model }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="spec-box">
                        <div class="spec-label">{{ __('site.Year') }}</div>
                        <div class="spec-value">{{ $car->year }}</div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">{{ __('site.Vehicle Description') }}</h4>
                    <p class="text-muted" style="line-height: 1.8; white-space: pre-line;">
                        {{ $car->description }}
                    </p>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE: Sticky Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-sidebar">

                <!-- Price Card -->
                <div class="card border-0 shadow mb-3">
                    <div class="card-body p-4">
                        <h5 class="text-muted mb-1">{{ $car->title }}</h5>
                        <h2 class="text-primary fw-bold mb-0">{{ number_format($car->price) }} TMT</h2>
                        <hr>
                        <div class="d-flex align-items-center mb-3">
                            <div class="seller-avatar me-3">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('site.Seller') }}</small>
                                <span class="fw-bold">{{ $car->user->name }}</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            @if($car->user->phone)
                            <a href="tel:{{ $car->user->phone }}" class="btn btn-success btn-lg fw-bold shadow-sm">
                                <i class="bi bi-telephone-fill me-2"></i> Call {{ $car->user->phone }}
                            </a>
                            @else
                            <button class="btn btn-secondary btn-lg" disabled>{{ __('site.No Phone Provided') }}</button>
                            @endif
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">{{ __('site.Posted:') }} {{ $car->created_at->format('d M Y') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Admin / Owner Actions -->
                @auth
                @if(Auth::id() == $car->user_id || Auth::user()->role == 'admin')
                <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
                    <div class="card-body">
                        <h6 class="text-danger fw-bold"><i class="bi bi-gear-fill me-1"></i> {{ __('site.Manage Listing') }}</h6>
                        <p class="small text-muted mb-2">{{ __('site.You are the owner or admin.') }}</p>
                        <form action="{{ route('cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this listing?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger w-100 btn-sm"> {{ __('site.Delete Listing') }}</button>
                        </form>
                    </div>
                </div>
                @endif
                @endauth

            </div>
        </div>
    </div>

    <!-- ================= RELATED CARS SECTION ================= -->
    @if($relatedCars->count() > 0)
    <div class="mt-4 pt-4 border-top">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">{{ __('site.Similar listings you might like') }}</h3>
            <a href="{{ route('home', ['brand_id' => $car->brand_id]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">{{ __('site.View All') }} {{ $car->brand->name }}</a>
        </div>

        <div class="row g-4">
            @foreach($relatedCars as $related)
            <div class="col-md-6 col-lg-3">
                <div class="card related-card shadow-sm">
                    <div class="position-relative related-img-wrapper">
                        @if($related->image)
                        <img src="{{ asset('storage/' . $related->image) }}" class="related-img" alt="{{ $related->title }}">
                        @else
                        <img src="https://via.placeholder.com/400x300?text=No+Photo" class="related-img">
                        @endif
                        <span class="related-badge">{{ $related->year }}</span>
                        <!-- Clickable Link Overlay -->
                        <a href="{{ route('cars.show', $related->id) }}" class="stretched-link"></a>
                    </div>

                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold text-truncate mb-1">{{ $related->brand->name }} {{ $related->model }}</h6>
                        <p class="text-primary fw-bold mb-0">{{ number_format($related->price) }} TMT</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <!-- ================= END RELATED SECTION ================= -->

</div>
@endsection