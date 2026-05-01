@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mt-5">
            <div class="card-header bg-primary text-white">{{ __('site.register') }}</div>
            <div class="card-body">
                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label>{{ __('site.Name') }}</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label>{{ __('site.Email') }}</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <!-- Phone (Added this) -->
                    <div class="mb-3">
                        <label>{{ __('site.Phone') }}</label>
                        <input type="text" name="phone" class="form-control" placeholder="+9936..." required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label>{{ __('site.Password') }}</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label>{{ __('site.Confirm Password') }}</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button class="btn btn-primary w-100">{{ __('site.register') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection