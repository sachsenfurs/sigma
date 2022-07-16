@extends('layouts.app')
@section('title', "Locations")
@section('content')
    <div class="container">
        <div class="mt-4 mb-4 text-center">
            <a class="btn btn-primary" href="{{ route("locations.create") }}">Location hinzufügen</a>
        </div>

        <table class="table table-hover">
            <tr>
                <th>Name</th>
                <th>Beschreibung</th>
                <th>Anzahl SIGs</th>
                <th class="text-end">Aktionen</th>
            </tr>
            @forelse($locations AS $location)
                <tr>
                    <td>
                        <a href="{{ route("locations.show", $location) }}">{{ $location->name }}</a>
                    </td>
                    <td>{{ $location->description }}</td>
                    <td class="col-1">{{ $location->sig_events_count }}</td>
                    <td class="text-end">
                        <a href="{{ route("locations.edit", $location) }}">
                            <button type="button" class="btn btn-light"><i class="bi bi-pen"></i></button>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Noch keine Locations eingetragen</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
