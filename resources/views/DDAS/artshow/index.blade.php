@extends('layouts.app')
@section('title', __('Art Show'))

@section('content')
    <div class="container">
        @foreach ($artshow as $artist)
                <div class="card mt-3">
                    <div class="card-body">
                        <h2 class="card-title">
                            <i class="bi bi-person-circle"></i>
                            {{ $artist->name }}
                        </h2>
                        <a href="{{ $artist->social }}" class="card-text">
                            {{ $artist->social }}
                        </a>
                        <div class="col-md-2 align-middle">
                            <div class="container d-flex h-100 w-100">
                                <div class="align-self-center" style="text-align: right">
                                    <div class="display-6">{{ $artist->id }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="card-footer">
                        <div class="w-100 container p-2">
                            <a href="#" class="">
                                <i class="bi bi-pen"></i> {{ __('Edit') }}
                            </a>
                        </div>
                    </div>
        @endforeach
    </div>
@endsection
