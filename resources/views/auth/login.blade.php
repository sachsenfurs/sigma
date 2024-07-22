@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-12 mx-auto mb-4">
            <div class="card">
                <div class="card-header">
                    {{ __("Login") }}
                </div>
                <div class="card-body">
                    <div class="row mt-3 flex">
                        <div class="col-12 text-center align-items-center">
                            <a class="btn btn-success btn-lg" href="{{ route('oauthlogin_regsys') }}">
                                {{ __('Login with your existing con-registration') }}
                            </a>
{{--                            <small class="d-flex w-100 justify-content-end mt-4">--}}
{{--                                <a class="justify-content-end text-decoration-none"  href="{{ route('oauthlogin') }}">--}}
{{--                                    {{ __('SF Staff-Login') }}--}}
{{--                                </a>--}}
{{--                            </small>--}}
                            @if(\Illuminate\Support\Facades\App::environment("local"))
                                <small class="d-flex w-100 justify-content-end mt-1">
                                    <a class="justify-content-end text-decoration-none"  href="{{ route('devlogin', 1) }}">
                                        {{ __('Dev-Login ID 1') }}
                                    </a>
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3>{{ __("Welcome to the SIG-Manager, here you can:") }}</h3>
        <div class="row g-3 mt-3 align-items-stretch">
            <x-home.overview icon="bi-calendar-week">
                {{ __("Access the event schedule") }}
                <x-slot:subtitle>
                    {{ __("View our schedule and don't miss anything!") }}
                </x-slot:subtitle>
            </x-home.overview>

            <x-home.overview icon="bi-gear">
                {{ __("Manage your own SIGs") }}
                <x-slot:subtitle>
                    {{ __("Submit a new SIG or register for specific events during the convetion") }}
                </x-slot:subtitle>
            </x-home.overview>

            <x-home.overview icon="bi-alarm">
                {{ __("Notifications") }}
                <x-slot:subtitle>
                    {{ __("Set up reminder Notifications") }}
                </x-slot:subtitle>
            </x-home.overview>
        </div>
        <div class="row g-3 mt-3 align-items-stretch">
            <x-home.overview icon="bi-easel">
                {{ __("Art Show Item Sign Up") }}
                <x-slot:subtitle>
                    {{ __("Submit items for the art show and auction") }}
                </x-slot:subtitle>
            </x-home.overview>

            <x-home.overview icon="bi-cash-coin">
                {{ __("Art Show Bidding") }}
                <x-slot:subtitle>
                    {{ __("View and submit bids for all art show items") }}
                </x-slot:subtitle>
            </x-home.overview>
        </div>
        <div class="row g-3 mt-3 align-items-stretch">
            <x-home.overview icon="bi-cart">
                {{ __("Dealer's Den Sign Up") }}
                <x-slot:subtitle>
                    {{ __("Sign up and manage your Dealer's Den application") }}
                </x-slot:subtitle>
            </x-home.overview>

            <x-home.overview icon="bi-cart">
                {{ __("Dealer's Den Overview") }}
                <x-slot:subtitle>
                    {{ __("List all attending dealers") }}
                </x-slot:subtitle>
            </x-home.overview>

        </div>

        @if(app(\App\Settings\AppSettings::class)->lost_found_enabled)
            <div class="row g-3 mt-3 align-items-stretch">
                <x-home.overview icon="bi-box2">
                    {{ __("Lost & Found") }}
                    <x-slot:subtitle>
                        {{ __("Have you lost something at the venue?") }}
                    </x-slot:subtitle>
                </x-home.overview>
            </div>
        @endif



    </div>

@endsection
