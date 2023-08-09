@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-2">
                <h3>{{ __("Welcome to the SIG-Manager, here you can:") }}</h3>

                <div class="list-group mt-4">
                    <li class="list-group-item">
                        <h5 class="mb-1"><i class="bi bi-calendar-week"></i> {{ __("Access the event schedule") }}</h5>
                        <small><a href="{{ route("public.tableview") }}">{{ __("View Schedule") }}</a></small>
                    </li>
                    <li class="list-group-item">
                        <h5 class="mb-1"><i class="bi bi-list-check"></i> {{ __("Signup for specific SIGs") }}</h5>
                    </li>
                    <li class="list-group-item">
                        <h5 class="mb-1"><i class="bi bi-gear"></i> {{ __("Manage your own SIGs") }}</h5>
                    </li>
                    <li class="list-group-item">
                        <h5 class="mb-1"><i class="bi bi-alarm"></i> {{ __("Set up reminder Notifications") }}</h5>
                    </li>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        {{ __("Login") }}
                    </div>
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <a class="btn btn-success" href="{{ route('oauthlogin_regsys') }}">
                                    {{ __('Login with your existing con-registration') }}
                                </a>
                                <small class="d-flex w-100 justify-content-end mt-4">
                                    <a class="justify-content-end text-decoration-none"  href="{{ route('oauthlogin') }}">
                                        {{ __('SF Staff-Login') }}
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{--
                <hr>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>
                --}}

            </div>
        </div>
    </div>
@endsection
