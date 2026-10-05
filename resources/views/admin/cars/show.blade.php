@extends('admin_layout')

@section('content')

<style>
    /* Soft Background Badges */
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

    /* Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Spec Item Card */
    .spec-card {
        background-color: #f8fafc;
        border: 1px solid #eef2f6;
        border-radius: 10px;
        padding: 12px 14px;
        height: 100%;
    }

    .spec-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* AI Card Glow & Border */
    .ai-card {
        background: linear-gradient(145deg, #ffffff, #f7faff);
        border: 1px solid rgba(13, 110, 253, 0.15) !important;
    }
</style>

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

<!-- Header Section -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.cars.index') }}" class="btn btn-sm btn-light border shadow-sm px-3 py-2 text-dark">
            <i class="bi bi-arrow-left me-1"></i> {{ __('site.Back to List') }}
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-0">{{ $car->brand->name ?? '' }} {{ $car->model }}</h4>
            <small class="text-muted">{{ $car->title }}</small>
        </div>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <!-- Current Status Badge -->
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
            <span class="status-badge bg-soft-danger">
                <i class="bi bi-x-circle-fill"></i> {{ __('site.Rejected') }}
            </span>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Çep Tarap: Ulagyň Esasy Maglumatlary -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                
                <!-- Ulag Suraty -->
                <div class="position-relative mb-4">
                    <img src="{{ $carImg }}" 
                         alt="{{ $car->title }}" 
                         class="w-100 rounded-3 border" 
                         style="max-height: 380px; object-fit: cover;"
                         loading="lazy"
                         onerror="this.onerror=null; this.src='{{ $fallbackImg }}';">
                    
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-dark bg-opacity-75 shadow-sm px-3 py-2 fs-6">
                            ${{ number_format($car->price, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Başlyk we Baha -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">{{ $car->brand->name ?? '' }} {{ $car->model }} ({{ $car->year }})</h4>
                        <p class="text-muted mb-0">{{ $car->title }}</p>
                    </div>
                </div>

                <hr class="my-3 text-muted opacity-25">

                <!-- Tehniki Maglumatlar Grid -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="spec-card d-flex align-items-center gap-3">
                            <div class="spec-icon bg-soft-primary">
                                <i class="bi bi-speedometer2"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('site.Mileage') }}</small>
                                <span class="fw-bold text-dark">{{ number_format($car->mileage ?? 0) }} km</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="spec-card d-flex align-items-center gap-3">
                            <div class="spec-icon bg-soft-success">
                                <i class="bi bi-fuel-pump"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('site.Fuel Type') }}</small>
                                <span class="fw-bold text-dark">{{ $car->fuel_type ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="spec-card d-flex align-items-center gap-3">
                            <div class="spec-icon bg-soft-warning">
                                <i class="bi bi-gear-wide-connected"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('site.Transmission') }}</small>
                                <span class="fw-bold text-dark">{{ $car->transmission ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="spec-card d-flex align-items-center gap-3">
                            <div class="spec-icon bg-soft-info">
                                <i class="bi bi-calendar3"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">{{ __('site.Date Added') }}</small>
                                <span class="fw-bold text-dark">{{ $car->created_at->format('d M, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Satyjynyň Maglumatlary -->
                <h6 class="fw-bold text-dark mb-2">{{ __('site.Seller Information') }}</h6>
                <div class="spec-card d-flex align-items-center gap-3 mb-4">
                    <div class="spec-icon bg-soft-secondary">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="w-100 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <div class="fw-bold text-dark">{{ $car->user->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $car->user->email ?? '' }}</small>
                        </div>
                        @if(!empty($car->user->phone))
                            <a href="tel:{{ $car->user->phone }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-telephone-fill me-1"></i> {{ $car->user->phone }}
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Düşündiriş -->
                <h6 class="fw-bold text-dark mb-2">{{ __('site.Description') }}</h6>
                <div class="p-3 bg-light rounded-3 text-secondary" style="white-space: pre-line; font-size: 0.92rem; line-height: 1.6;">
                    {{ $car->description ?: __('site.No description provided.') }}
                </div>

            </div>
        </div>
    </div>

    <!-- Sag Tarap: Lokal AI & Moderasiýa -->
    <div class="col-lg-5">
        
        <!-- 🤖 Lokal AI Synçy Kerti -->
        <div class="card border-0 shadow-sm rounded-3 ai-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="spec-icon bg-soft-primary">
                            <i class="bi bi-robot"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">{{ __('site.AI Smart Appraisal') }}</h6>
                            <small class="text-muted" style="font-size: 11px;">Local Offline Engine / Ollama</small>
                        </div>
                    </div>

                    <form action="{{ route('admin.cars.ai-check', $car->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-dark px-3 py-1.5 shadow-sm">
                            <i class="bi bi-arrow-repeat me-1"></i> {{ $car->ai_verdict ? __('site.Re-analyze with AI') : __('site.Run AI Appraisal') }}
                        </button>
                    </form>
                </div>

                @if($car->ai_verdict)
                    @php
                        $v = strtoupper($car->ai_verdict);
                        $badgeClass = 'bg-soft-info';
                        if (str_contains($v, 'GREAT')) $badgeClass = 'bg-soft-success';
                        elseif (str_contains($v, 'OVER')) $badgeClass = 'bg-soft-danger';
                        elseif (str_contains($v, 'FAIR')) $badgeClass = 'bg-soft-primary';
                    @endphp
                    
                    <div class="mb-3">
                        <span class="status-badge {{ $badgeClass }} px-3 py-2">
                            <i class="bi bi-shield-check"></i> {{ __('site.' . $car->ai_verdict) ?? $car->ai_verdict }}
                        </span>
                    </div>

                    <div class="p-3 bg-white border rounded-3 text-secondary small" style="line-height: 1.6;">
                        {{ $car->ai_review }}
                    </div>
                @else
                    <div class="text-center py-4 text-muted bg-white border rounded-3 p-3">
                        <i class="bi bi-cpu fs-1 d-block mb-2 text-primary opacity-50"></i>
                        <p class="small mb-0">{{ __('site.This car has not been analyzed by AI yet. Click below to generate an automated valuation and report.') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 🛡️ Moderasiýa Çözgüdi Formasy -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold text-dark mb-1">{{ __('site.Moderation Decision') }}</h5>
                <p class="text-muted small mb-0">{{ __('site.Approve or reject this car to manage its visibility.') }}</p>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('admin.cars.update-status', $car->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <!-- Ýagdaýy Saýlamak -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">{{ __('site.Listing Status') }}</label>
                        <select name="status" class="form-select py-2" id="statusSelect" onchange="toggleRejectionBox()">
                            <option value="approved" {{ $car->status === 'approved' ? 'selected' : '' }}>
                                ✅ {{ __('site.Approve (Visible on website)') }}
                            </option>
                            <option value="pending" {{ $car->status === 'pending' ? 'selected' : '' }}>
                                ⏳ {{ __('site.Pending Review') }}
                            </option>
                            <option value="rejected" {{ $car->status === 'rejected' ? 'selected' : '' }}>
                                ❌ {{ __('site.Reject (Hidden from website)') }}
                            </option>
                        </select>
                    </div>

                    <!-- Ret Edilme Sebäbi -->
                    <div class="mb-3" id="adminNoteGroup" style="{{ $car->status === 'rejected' ? 'display: block;' : 'display: none;' }}">
                        <label class="form-label fw-bold small text-muted text-uppercase">{{ __('site.Rejection Reason / Admin Note') }}</label>
                        <textarea name="admin_note" 
                                  id="adminNoteTextarea"
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="{{ __('site.Explain why this listing was rejected...') }}">{{ $car->admin_note }}</textarea>
                        <small class="text-muted mt-1 d-block" style="font-size: 11px;">
                            {{ __('site.This reason will help the user understand why their listing was declined.') }}
                        </small>
                    </div>

                    <!-- Ýatda Saklamak Düwmesi -->
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm">
                        <i class="bi bi-save2 me-1"></i> {{ __('site.Save Decision') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Ulagy Pozmak -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <span class="small text-muted">{{ __('site.Danger Zone') }}</span>
                <form action="{{ route('admin.cars.destroy', $car->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('{{ __('site.Are you sure you want to permanently delete this car?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> {{ __('site.Delete Listing') }}
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function toggleRejectionBox() {
        const select = document.getElementById('statusSelect');
        const box = document.getElementById('adminNoteGroup');
        if (select.value === 'rejected') {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }
</script>

@endsection