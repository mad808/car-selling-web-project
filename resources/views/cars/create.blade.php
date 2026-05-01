@extends('layout')

@section('content')
<div class="container py-5">

    <!-- Page Header -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8 text-center">
            <h2 class="fw-bold text-primary">{{ __('site.🚗 Sell Your Car') }}</h2>
            <p class="text-muted">{{ __('site.Fill in the details below to list your car for thousands of buyers.') }}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">

                    <!-- Error Display -->
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <strong><i class="bi bi-exclamation-triangle-fill"></i> {{ __('site.Oops!') }}</strong> {{ __('site.Please fix the following errors:') }}
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- SECTION 1: Vehicle Details -->
                        <h5 class="text-primary fw-bold mb-3">{{ __('site.Choice') }}</h5>
                        <div class="row g-3 mb-4">
                            <!-- Brand -->
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select name="brand_id" class="form-select" id="brandSelect" required>
                                        <option value="" disabled selected>{{ __('site.Select...') }}</option>
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label for="brandSelect">{{ __('site.Brand') }}</label>
                                </div>
                            </div>

                            <!-- Model -->
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" name="model" class="form-control" id="modelInput" placeholder="Camry" value="{{ old('model') }}" required>
                                    <label for="modelInput">{{ __('site.Model') }}</label>
                                </div>
                            </div>

                            <!-- Year -->
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" name="year" class="form-control" id="yearInput" placeholder="2020" value="{{ old('year') }}" required>
                                    <label for="yearInput">{{ __('site.Year') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Advertisement Info -->
                        <h5 class="text-primary fw-bold mb-3 mt-4">{{ __('site.2. Listing Info') }}</h5>
                        <div class="row g-3 mb-4">
                            <!-- Title -->
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <input type="text" name="title" class="form-control" id="titleInput" placeholder="e.g. White Toyota Camry LE" value="{{ old('title') }}" required>
                                    <label for="titleInput">{{ __('site.Ad Title (e.g. "Clean Toyota Camry 2020")') }}</label>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-md-4">
                                <div class="input-group has-validation" style="height: 58px;"> <!-- Height matches form-floating -->
                                    <span class="input-group-text bg-light fw-bold">TMT</span>
                                    <div class="form-floating is-invalid">
                                        <input type="number" step="0.01" name="price" class="form-control" id="priceInput" placeholder="Price" value="{{ old('price') }}" required style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                        <label for="priceInput">{{ __('site.Price') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Description -->
                        <div class="form-floating mb-4">
                            <textarea name="description" class="form-control" placeholder="Describe your car here" id="descInput" style="height: 150px">{{ old('description') }}</textarea>
                            <label for="descInput">{{ __('site.Description (Condition, features, history...)') }}</label>
                        </div>

                        <!-- SECTION 4: Image -->
                        <h5 class="text-primary fw-bold mb-3 mt-4">{{ __('site.3. Upload Photo') }}</h5>
                        <div class="mb-4">
                            <div class="p-4 border border-2 border-dashed rounded-3 bg-light text-center">
                                <div class="mb-2 text-muted">{{ __('site.📸 Select a clear cover photo for your car') }}</div>
                                <input type="file" name="image" class="form-control form-control-lg" accept="image/*" required>
                                <div class="form-text mt-2">{{ __('site.Accepted formats: JPG, PNG, WEBP. Max 3MB.') }}</div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <a href="{{ route('home') }}" class="btn btn-light btn-lg px-4">{{ __('site.Cancel') }}</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow-sm">
                                {{ __('site.Post Listing 🚀') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection