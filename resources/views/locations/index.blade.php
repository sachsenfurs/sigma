@extends('layouts.app')
@section('title', __("Locations"))
@section('content')
    <div class="container">

        <x-tabs :tabs="['Rooms', 'Essentials', 'Map']">

            {{-- Rooms --}}
            @slot("Rooms")
                @forelse($locations->filter(fn($l) => !$l->essential) AS $location)
                    <x-list-host-location
                        :instance="$location"
                        :link="route('locations.show', $location)"
                        :title="$location->name_localized"
                    >
                        {{ $location->description_localized }}
                    </x-list-host-location>
                @empty
                    <x-infocard>
                        {{ __("The Schedule is not published yet") }}
                    </x-infocard>
                @endforelse
            @endslot

            {{-- Essentials --}}
            @if($locations->filter(fn($l) => $l->essential)->count() > 0)
                @slot("Essentials")
                    @foreach($locations->filter(fn($l) => $l->essential) AS $location)
                        <x-list-host-location
                            :instance="$location"
                            :link="route('locations.show', $location)"
                            :title="$location->description_localized"
                        >
                            <x-markdown>{{ $location->essential_description_localized }}</x-markdown>
                            <div class="text-muted">
                                <i class="bi bi-geo-alt icon-link"></i>
                                {{ $location->name_localized }}
                            </div>
                        </x-list-host-location>
                    @endforeach
                @endslot
            @endif

            {{-- Map --}}
            @slot("Map")
                <div class="overflow-auto mt-3">
                    {{--  // TODO: make maps dynamic --}}
                    <img src="//static.sachsenfurs.de/east/map_{{ app()->getLocale() }}.jpg" id="map_small" class="img-fluid" loading="lazy" alt="" onclick="$('#map').toggle();$('#map_small').toggle()">

                    <div id="map" style="display:none; top: 0; left: 0; overflow: scroll ; z-index: 2000">
                        <div class="" style="">
                            <img src="//static.sachsenfurs.de/east/map_{{ app()->getLocale() }}.jpg" style="height: 100%" class="" loading="lazy" alt="" onclick="$('#map').toggle();$('#map_small').toggle()">
                        </div>
                    </div>

                </div>
            @endslot
        </x-tabs>
    </div>
@endsection
