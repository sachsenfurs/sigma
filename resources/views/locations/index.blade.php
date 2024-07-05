@extends('layouts.app')
@section('title', "Locations")
@section('content')
    <div class="container">
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
@endsection
