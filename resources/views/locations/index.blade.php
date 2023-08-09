@extends('layouts.app')
@section('title', "Locations")
@section('content')
    <div class="container">
        @can("manage_locations")
            <div class="mt-4 mb-4 text-center">
                <a class="btn btn-primary" href="{{ route("locations.create") }}">Location hinzufügen</a>
            </div>
        @endcan

        @foreach($locations AS $location)
                <x-list-host-location
                    :instance="$location"
                    :link="route('locations.show', $location)"
                    :title="$location->name"
                    edit_permission="manage_locations"
                    :edit_link="route('locations.edit', $location)"
                >
                    {{ $location->description }}
                </x-list-host-location>

        @endforeach

    </div>
@endsection
