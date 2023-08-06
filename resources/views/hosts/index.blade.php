@extends('layouts.app')
@section('title', "Hosts")
@section('content')
    <div class="container">
        @foreach($hosts AS $host)
                <div class="card mb-3" style="">
                    <div class="row g-0">
                        @if($host->avatar)
                            <div class="col-md-2" style="max-height: 100%">
                                <img src="{{ $host->avatar }}" class="img-fluid h-100 w-100 rounded-top" style="object-fit: cover; max-height: 30vw" alt="">
                            </div>
                        @endif
                        <div class="@if($host->avatar)col-md-8 @else col-md-10 @endif">
                            <div class="card-body">
                                <h2 class="card-title">{{ $host->name }}</h2>
                                <h6 class="card-subtitle mb-2 text-body-secondary"></h6>
                                <p class="card-text">{{ $host->description }}</p>
                                @can("manage_hosts")
                                    <a href="{{ route("hosts.edit", $host) }}" class="btn btn-info">Edit</a>
                                @endcan
                                <a href="{{ route("hosts.show", $host) }}" class="btn btn-primary">Show Events</a>
                            </div>

                        </div>
                        <div class="col-md-2 align-middle">
                            <div class="container d-flex h-100 w-100">
                                <div class="align-self-center w-100 m-3" style="text-align: right">
                                    <div class="display-6">{{ $host->sig_events_count }}</div>
                                    Events
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        @endforeach

    </div>
@endsection
