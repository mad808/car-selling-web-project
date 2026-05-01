@extends('admin_layout')

@section('content')

<style>
    /* Custom Upload Zone Styling */
    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background-color: #f8fafc;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .upload-zone:hover {
        border-color: #0d6efd;
        background-color: #eff6ff;
    }
    .upload-input {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    /* Banner Card Styling */
    .banner-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        border: none;
    }
    .banner-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .banner-img-wrapper {
        height: 180px;
        position: relative;
        background-color: #e2e8f0;
    }
    .banner-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .delete-btn-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc3545;
        border: none;
        transition: 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .delete-btn-overlay:hover {
        background: #dc3545;
        color: white;
    }
</style>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">{{ __('site.Banner Management') }}</h4>
        <p class="text-muted small mb-0">{{ __('site.Manage homepage sliders and promotional images') }}.</p>
    </div>
    <div>
        <span class="badge bg-white text-dark shadow-sm border px-3 py-2">
            {{ __('site.Active Banners') }}: <strong>{{ $banners->count() }}</strong>
        </span>
    </div>
</div>

<div class="row g-4">
    
    <!-- LEFT: Upload Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i>{{ __('site.Upload New Banner') }}
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Title Input -->
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">{{ __('site.Banner Title (Optional)') }}</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Summer Sale">
                    </div>

                    <!-- Visual Upload Area -->
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">{{ __('site.Image File') }}</label>
                        <div class="upload-zone text-center p-5">
                            <i class="bi bi-image fs-1 text-secondary mb-2 d-block"></i>
                            <span class="text-dark fw-bold small">{{ __('site.Click or Drag image here') }}</span>
                            <br>
                            <span class="text-muted" style="font-size: 0.75rem;">JPG, PNG, WEBP (Max 3MB)</span>
                            <input type="file" name="image" class="upload-input" accept="image/*" required>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100 py-2 fw-bold">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('site.Publish Banner') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT: Gallery Grid -->
    <div class="col-lg-8">
        <div class="row g-3">
            @forelse($banners as $banner)
            <div class="col-md-6">
                <div class="card banner-card shadow-sm rounded-3 h-100">
                    
                    <!-- Image Wrapper -->
                    <div class="banner-img-wrapper">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="banner-img" alt="Banner">
                        
                        <!-- Floating Delete Button -->
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this banner?');">
                            @csrf
                            @method('DELETE')
                            <button class="delete-btn-overlay" title="Delete Banner">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>

                        <!-- Status Badge -->
                        <div class="position-absolute bottom-0 start-0 m-2">
                            <span class="badge bg-success bg-opacity-75 backdrop-blur shadow-sm">
                                <i class="bi bi-check-circle-fill me-1"></i> {{ __('site.Active') }}
                            </span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark mb-1 text-truncate">
                            {{ $banner->title ?? 'Untitled Banner' }}
                        </h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-calendar3 me-1"></i> {{ $banner->created_at->format('d M Y') }}
                            </small>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                ID: #{{ $banner->id }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3 p-5 text-center">
                    <div class="text-muted">
                        <i class="bi bi-images fs-1 d-block mb-3 opacity-50"></i>
                        <h5>{{ __('site.No Banners Uploaded') }} </h5>
                        <p class="small">{{ __('site.Use the form on the left to add your first banner') }}.</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection