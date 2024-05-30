@extends('layouts.app')
@section('title', "Locations")
@section('content')
    <div class="container">
        @foreach($locations AS $location)
                <x-list-host-location
                    :instance="$location"
                    :link="route('locations.show', $location)"
                    :title="$location->name"
                    :edit_link="\App\Filament\Resources\SigLocationResource::getUrl('edit', [ 'record' => $location ])"
                >
                    {{ $location->description }}
                </x-list-host-location>

        @endforeach

    </div>
@endsection
