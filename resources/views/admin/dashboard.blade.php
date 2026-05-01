@extends('admin_layout')

@section('content')

<style>
    /* Stats Card Icon Box */
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .bg-light-success {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    /* Table Image */
    .table-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">{{ __('site.Dashboard Overview') }}</h4>
        <p class="text-muted small mb-0">{{ __('site.Welcome back') }}, {{ Auth::user()->name }}. {{ __('site.Here what is happening today.') }} </p>
    </div>
    <div>
        <span class="badge bg-white text-dark shadow-sm border px-3 py-2">
            <i class="bi bi-calendar3 me-2"></i> {{ date('d M, Y') }}
        </span>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row g-4 mb-5">

    <!-- Users Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="stats-icon bg-light-primary me-3">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase fw-bold small mb-1">{{ __('site.Total Users') }}</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ number_format($totalUsers) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Cars Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="stats-icon bg-light-success me-3">
                    <i class="bi bi-car-front-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase fw-bold small mb-1">{{ __('site.Total Listings') }}</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ number_format($totalCars) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Banners Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="stats-icon bg-light-warning me-3">
                    <i class="bi bi-images"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase fw-bold small mb-1">{{ __('site.Active Banners') }}</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ $totalBanners }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">{{ __('site.🚗 Recently Added Cars') }} </h6>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-light text-primary fw-bold">{{ __('site.View Website') }} &rarr;</a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small text-uppercase">
                        <th class="ps-4">{{ __('site.Car Details') }} </th>
                        <th>{{ __('site.Price') }}</th>
                        <th>{{ __('site.Seller') }}</th>
                        <th>{{ __('site.Status') }}</th>
                        <th>{{ __('site.Date Added') }}</th>
                        <th class="text-end pe-4">{{ __('site.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestCars as $car)
                    <tr>
                        <!-- Car Info -->
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" class="table-thumb me-3" alt="Car">
                                @else
                                <div class="table-thumb bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-3 text-muted">
                                    <i class="bi bi-camera"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark">{{ $car->brand->name ?? 'Unknown' }} {{ $car->model }}</div>
                                    <div class="small text-muted">{{ $car->year }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Price -->
                        <td class="fw-bold text-primary">
                            {{ number_format($car->price) }} TMT
                        </td>

                        <!-- Seller -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 25px; font-size: 0.7rem;">
                                    <i class="bi bi-person-fill text-muted"></i>
                                </div>
                                <span class="small">{{ $car->user->name }}</span>
                            </div>
                        </td>

                        <!-- Status -->
                        <td>
                            @if($car->is_sold)
                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">{{ __('site.Sold') }} </span>
                            @else
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">{{ __('site.Active') }} </span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="text-muted small">
                            {{ $car->created_at->diffForHumans() }}
                        </td>

                        <!-- Action -->
                        <td class="text-end pe-4">
                            <a href="{{ route('cars.show', $car->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            {{ __('site.No recent activity found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection