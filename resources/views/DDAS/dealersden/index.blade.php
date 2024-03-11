@extends('layouts.app')
@section('title', __('Dealers Den'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">
            {{ __('Dealers Den Sign Up') }}
        </h1>

        <div class="d-flex justify-content-center p-3">
            <a href="{{ route('dealersden.create') }}" class="btn btn-primary">{{ __('New Dealer') }}</a>
        </div>

        <div class="justify-center px-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">{{ __('Dealer List') }}</h3>
                </div>
                <div class="card-body">
                    @foreach ($dealers as $dealer)
                        @if ($dealer->approved == 1)
                            <div class="row">
                                <div class="col-md-2 mb-3 pe-md-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('Dealer') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <a href="{{ route('dealersden.show', $dealer) }}">{{ $dealer->name }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3 pe-md-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('Social Media') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col ">
                                                <a href="{{ $dealer->gallery_link }}">{{ $dealer->name }} Social-Media</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 pe-md-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('Dealer Infos') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col ">
                                                {{ $dealer->info }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('Logo') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col ">
                                                <img src="{{ $dealer->icon_file }}" alt="{{ $dealer->name }}" class="card-img-bottom">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endsection
