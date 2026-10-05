@extends('admin_layout')

@section('content')

<style>
    /* Car Thumbnail Box */
    .car-thumb {
        width: 60px;
        height: 45px;
        border-radius: 8px;
        object-fit: cover;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        flex-shrink: 0;
    }

    /* Soft Background Colors (Matching Users Page) */
    .bg-soft-primary {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .bg-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
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

    .bg-soft-secondary {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    /* Pill Badges */
    .status-badge {
        padding: 5px 11px;
        border-radius: 30px;
        font-size: 0.73rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Table Styling */
    .table thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        font-weight: 700;
        border-bottom-width: 1px;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .table tbody td {
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
    }

    /* Filter Pill Buttons */
    .filter-btn {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }
</style>

<!-- Header Section -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">{{ __('site.Car Management') }}</h4>
        <p class="text-muted small mb-0">{{ __('site.Manage and review vehicle listings, approvals and AI appraisal.') }}</p>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-white text-dark shadow-sm border px-3 py-2">
            {{ __('site.Total Cars') }}: <strong>{{ $cars->total() }}</strong>
        </span>
    </div>
</div>

<!-- Status Filters -->
<div class="card border-0 shadow-sm rounded-3 mb-3">
    <div class="card-body p-2 d-flex flex-wrap gap-2 align-items-center">
        @php $currentStatus = request('status', 'all'); @endphp
        
        <a href="{{ route('admin.cars.index', ['status' => 'all']) }}" 
           class="filter-btn {{ $currentStatus === 'all' ? 'bg-dark text-white' : 'bg-light text-muted' }}">
            {{ __('site.All') }}
        </a>
        <a href="{{ route('admin.cars.index', ['status' => 'pending']) }}" 
           class="filter-btn {{ $currentStatus === 'pending' ? 'bg-warning text-dark' : 'bg-light text-muted' }}">
            <i class="bi bi-clock-history me-1"></i> {{ __('site.Pending') }}
        </a>
        <a href="{{ route('admin.cars.index', ['status' => 'approved']) }}" 
           class="filter-btn {{ $currentStatus === 'approved' ? 'bg-success text-white' : 'bg-light text-muted' }}">
            <i class="bi bi-check-circle-fill me-1"></i> {{ __('site.Approved') }}
        </a>
        <a href="{{ route('admin.cars.index', ['status' => 'rejected']) }}" 
           class="filter-btn {{ $currentStatus === 'rejected' ? 'bg-danger text-white' : 'bg-light text-muted' }}">
            <i class="bi bi-x-circle-fill me-1"></i> {{ __('site.Rejected') }}
        </a>
    </div>
</div>

<!-- Cars Table Card -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">{{ __('site.Vehicle') }}</th>
                        <th>{{ __('site.Price & Details') }}</th>
                        <th>{{ __('site.Seller') }}</th>
                        <th>{{ __('site.AI Appraisal') }}</th>
                        <th>{{ __('site.Status') }}</th>
                        <th>{{ __('site.Date Added') }}</th>
                        <th class="text-end pe-4">{{ __('site.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cars as $car)
                    @php
                        // Surat ýoluny anyklamak we fallback
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
                    <tr>
                        <!-- Vehicle (Image + Brand/Model + Title) -->
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ $carImg }}" 
                                     alt="{{ $car->title }}" 
                                     class="car-thumb me-3"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='{{ $fallbackImg }}';">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">
                                        {{ $car->brand->name ?? '' }} {{ $car->model }}
                                    </h6>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 220px;" title="{{ $car->title }}">
                                        {{ $car->title }}
                                    </small>
                                </div>
                            </div>
                        </td>

                        <!-- Price & Technical Details -->
                        <td>
                            <div class="fw-bold text-dark">${{ number_format($car->price, 2) }}</div>
                            <small class="text-muted">
                                {{ $car->year }} • {{ number_format($car->mileage ?? 0) }} km
                            </small>
                        </td>

                        <!-- Seller / User -->
                        <td>
                            @if($car->user)
                                <div class="fw-medium text-dark">{{ $car->user->name }}</div>
                                <small class="text-muted">{{ $car->user->phone ?? $car->user->email }}</small>
                            @else
                                <span class="text-muted small">N/A</span>
                            @endif
                        </td>

                        <!-- AI Appraisal Verdict -->
                        <td>
                            @if($car->ai_verdict)
                                @php
                                    $v = strtoupper($car->ai_verdict);
                                    $badgeClass = 'bg-soft-info';
                                    if (str_contains($v, 'GREAT')) $badgeClass = 'bg-soft-success';
                                    elseif (str_contains($v, 'OVER')) $badgeClass = 'bg-soft-danger';
                                    elseif (str_contains($v, 'FAIR')) $badgeClass = 'bg-soft-primary';
                                @endphp
                                <span class="status-badge {{ $badgeClass }}" title="{{ $car->ai_review }}">
                                    <i class="bi bi-robot"></i> {{ __('site.' . $car->ai_verdict) ?? $car->ai_verdict }}
                                </span>
                            @else
                                <span class="text-muted small">
                                    <i class="bi bi-dash-circle me-1"></i>{{ __('site.Not Analyzed') }}
                                </span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td>
                            @if($car->is_sold)
                                <span class="status-badge bg-soft-secondary">
                                    <i class="bi bi-bag-check-fill"></i> {{ __('site.Sold') }}
                                </span>
                            @elseif($car->status === 'approved')
                                <span class="status-badge bg-soft-success">
                                    <i class="bi bi-check-circle-fill"></i> {{ __('site.Approved') }}
                                </span>
                            @elseif($car->status === 'pending')
                                <span class="status-badge bg-soft-warning">
                                    <i class="bi bi-clock-history"></i> {{ __('site.Pending') }}
                                </span>
                            @elseif($car->status === 'rejected')
                                <span class="status-badge bg-soft-danger" title="{{ $car->admin_note }}">
                                    <i class="bi bi-x-circle-fill"></i> {{ __('site.Rejected') }}
                                </span>
                            @endif
                        </td>

                        <!-- Date Added -->
                        <td>
                            <span class="text-muted small">
                                <i class="bi bi-calendar3 me-1"></i> {{ $car->created_at->format('d M, Y') }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <!-- Review / View Details -->
                                <a href="{{ route('admin.cars.show', $car->id) }}" 
                                   class="btn btn-sm btn-light text-primary" 
                                   title="{{ __('site.Review Car') }}">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <!-- Delete Car -->
                                <form action="{{ route('admin.cars.destroy', $car->id) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('{{ __('site.Are you sure you want to permanently delete this car?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="{{ __('site.Delete Car') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-car-front fs-2 d-block mb-2 opacity-50"></i>
                            {{ __('site.No cars found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer -->
    @if($cars->hasPages())
    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-end">
        {{ $cars->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection