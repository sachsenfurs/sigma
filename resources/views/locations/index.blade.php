@extends('layouts.app')
@section('title', __("Locations"))
@section('content')
    <div class="container">

        <div class="sticky-top container-fluid p-0 pt-2">
            <ul class="nav nav-underline navbar-nav-scroll d-flex bg-body flex-nowrap" id="locationTab" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 active" id="location-tab" data-bs-toggle="tab" data-bs-target="#location-2-tab-pane" type="button" role="tab" aria-controls="location-2-tab-pane" aria-selected="false">
                        <h3>{{ __("Rooms") }}</h3>
                    </button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100" id="location-1-tab" data-bs-toggle="tab" data-bs-target="#location-1-tab-pane" type="button" role="tab" aria-controls="location-1-tab-pane" aria-selected="true">
                        <h3>{{ __("Essentials") }}</h3>
                    </button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100" id="location-3-tab" data-bs-toggle="tab" data-bs-target="#location-3-tab-pane" type="button" role="tab" aria-controls="location-3-tab-pane" aria-selected="true">
                        <h3>{{ __("Map") }}</h3>
                    </button>
                </li>

            </ul>
        </div>
        <div class="tab-content" id="locationTabContent">
            <div class="tab-pane fade" id="location-1-tab-pane" role="tabpanel" aria-labelledby="location-1" tabindex="0">
                @foreach($locations->filter(fn($l) => $l->essential) AS $location)
                    <x-list-host-location
                        :instance="$location"
                        :link="route('locations.show', $location)"
                        :title="$location->description_localized"
                        :edit_link="\App\Filament\Resources\SigLocationResource::getUrl('edit', [ 'record' => $location ])"
                    >
                        {{ $location->essential_description_localized }}
                        <p class="text-muted">
                            <i class="bi bi-geo-alt"></i>
                            {{ $location->name_localized }}
                        </p>
                    </x-list-host-location>
                @endforeach
            </div>
            <div class="tab-pane fade show active" id="location-2-tab-pane" role="tabpanel" aria-labelledby="location-2" tabindex="0">
                @forelse($locations AS $location)
                    <x-list-host-location
                        :instance="$location"
                        :link="route('locations.show', $location)"
                        :title="$location->name_localized"
                        :edit_link="\App\Filament\Resources\SigLocationResource::getUrl('edit', [ 'record' => $location ])"
                    >
                        {{ $location->description_localized }}
                    </x-list-host-location>
                @empty
                    <x-infocard>
                        {{ __("The Schedule is not published yet") }}
                    </x-infocard>
                @endforelse
            </div>
            <div class="tab-pane fade" id="location-3-tab-pane" role="tabpanel" aria-labelledby="location-3" tabindex="0">
                <div class="overflow-auto mt-3">
                    {{--  // TODO: make maps dynamic --}}
                    <img src="//static.sachsenfurs.de/east/map_{{ app()->getLocale() }}.jpg" id="map_small" class="img-fluid" loading="lazy" alt="" onclick="$('#map').toggle();$('#map_small').toggle()">

                    <div id="map" style="display:none; top: 0; left: 0; overflow: scroll ; z-index: 2000">
                        <div class="" style="">
                            <img src="//static.sachsenfurs.de/east/map_{{ app()->getLocale() }}.jpg" style="height: 100%" class="" loading="lazy" alt="" onclick="$('#map').toggle();$('#map_small').toggle()">
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
