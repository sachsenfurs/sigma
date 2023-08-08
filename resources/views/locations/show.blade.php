@extends('layouts.app')
@section('title', "{$location->name}")
@section('content')
    <div class="container">

        <div class="row justify-content-md-center">
            <div class="col-xl-12 mb-3">
                <div class="d-flex align-items-start p-2">
                    <div class="w-100 ms-3 align-self-center">
                        <h2>
                            <i class="bi bi-geo-alt"></i>
                            {{ $location->name }}
                        </h2>
                        <p class="text-muted">{{ $location->description }}</p>
                        @can("manage_locations")
                            <a href="{{ route("locations.edit", $location) }}"><i class="bi bi-pen"></i> Edit</a>
                        @endcan
                    </div>
                </div>
            </div>

        </div>

        @forelse($events AS $sig)
            <x-list-sig :sig="$sig" />
        @empty
            <div class="alert alert-info">
                Keine SIGs zugeordnet
            </div>
        @endforelse

    </div>
@endsection
