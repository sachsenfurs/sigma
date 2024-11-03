@extends('layouts.app')
@section('title', "{$location->name}")
@section('content')
    <div class="container">

        <div class="row justify-content-md-center">
            <div class="col-xl-12 mb-3">
                <div class="d-flex align-items-start">
                    <div class="w-100 ms-3 align-self-center">
                        <h2 class="d-flex">
                            <i class="bi bi-geo-alt pe-2"></i>
                            {{ $location->name_localized }}
                        </h2>
                        <p class="text-muted">
                            @if($location->essential)
                                {{ $location->essential_description_localized }}
                            @else
                                {{ $location->description_localized }}
                            @endif
                        </p>
                        @can("update", $location)
                            <a href="{{ \App\Filament\Resources\SigLocationResource::getUrl('edit', ['record' => $location]) }}"><i class="bi bi-pen"></i> {{ __("Edit") }}</a>
                        @endcan
                    </div>
                </div>
            </div>

        </div>

        @forelse($events AS $sig)
            <x-list-sig :sig="$sig" />
        @empty
            <div class="alert alert-info">
                {{ __("No SIGs assigned") }}
            </div>
        @endforelse
    </div>
@endsection
