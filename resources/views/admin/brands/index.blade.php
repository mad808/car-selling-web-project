@extends('admin_layout')

@section('content')

<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">{{ __('site.Brand Management') }} </h4>
        <p class="text-muted small mb-0">{{ __('site.Create and manage vehicle brands used in listings') }} .</p>
    </div>
    <div>
        <span class="badge bg-white text-dark shadow-sm border px-3 py-2">
            {{ __('site.Total Brands') }}: <strong>{{ $brands->count() }}</strong>
        </span>
    </div>
</div>

<div class="row g-4">

    <!-- LEFT COLUMN: ADD BRAND FORM -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-plus-circle-fill me-2"></i> {{ __('site.Add New Brand') }}
                </h6>
            </div>
            <div class="card-body pt-0">
                <form action="{{ route('admin.brands.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">{{ __('site.Brand Name') }} </label>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="e.g. Tesla" required>
                    </div>

                    <button class="btn btn-primary w-100 py-2 fw-bold mb-3">
                        <i class="bi bi-save me-1"></i> {{ __('site.Save Brand') }}
                    </button>

                    <!-- Helpful Tip -->
                    <div class="alert alert-light border d-flex align-items-start small text-muted mb-0">
                        <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                        <div>
                            {{ __('site.Make sure the brand name does not already exist to avoid duplicates') }}.
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: BRANDS LIST -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">{{ __('site.Existing Brands') }} </h6>
                <!-- Optional search could go here -->
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light sticky-top">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4" style="width: 80px;">ID</th>
                                <th> {{ __('site.Name') }} </th>
                                <th class="text-end pe-4" style="width: 100px;">{{ __('site.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                            <tr>
                                <!-- ID Badge -->
                                <td class="ps-4">
                                    <span class="badge bg-light text-secondary border">#{{ $brand->id }}</span>
                                </td>

                                <!-- Brand Name -->
                                <td>
                                    <span class="fw-bold text-dark">{{ $brand->name }}</span>
                                </td>

                                <!-- Delete Action -->
                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Are you sure? This might affect cars linked to this brand.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger border-0" title="Delete Brand">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    {{ __('site.No brands found. Start adding some!') }} 
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection