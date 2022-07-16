@extends('layouts.app')
@section('title', "Locations")
@section('content')
    <div class="container">
        <table class="table table-hover">
            <tr>
                <th>Name</th>
                <th>Beschreibung</th>
                <th>Anzahl SIGs</th>
            </tr>
            @forelse($locations AS $location)
                <tr>
                    <td>
                        <a href="{{ route("locations.show", $location) }}">{{ $location->name }}</a>
                    </td>
                    <td>{{ $location->description }}</td>
                    <td class="col-1">{{ $location->sig_events_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Noch keine Locations eingetragen</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
