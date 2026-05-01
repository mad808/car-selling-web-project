@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card mt-5">
            <div class="card-header bg-dark text-white">{{ __('site.login') }}</div>
            <div class="card-body">

                @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>{{ __('site.Email') }}</label>
                        <input type="email" placeholder="christmas4017@gmail.com -&- eziz5505@gmal.com" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('site.Password') }}</label>
                        <input type="password" placeholder="password" name="password" class="form-control" required>
                    </div>

                    <button class="btn btn-dark w-100">{{ __('site.login') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection