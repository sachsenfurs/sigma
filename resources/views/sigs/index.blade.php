@extends('layouts.app')
@section('title', "SIG Übersicht")
@section('content')
    <div class="container">
        <div class="mt-4 mb-4 text-center">
            <a class="btn btn-primary" href="{{ route("sigs.create") }}">SIG Eintragen</a>
        </div>
        <table class="table table-hover">
            <tr>
                <th>Titel</th>
                <th>Host</th>
                <th>Sprachen</th>
                <th>Location</th>
                <th>Im Programmplan</th>
                <th>Aktion</th>
            </tr>
            @forelse($sigs AS $sig)
                <tr class="@if($sig->timeTableCount == 0) alert-danger @endif">
                    <td>
                        @if($sig->description == "")
                            <div class="badge bg-danger">Text fehlt!</div>
                        @endif
                        <a href="{{ route("sigs.show", $sig) }}">
                            {{ $sig->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route("hosts.show", $sig->sigHost) }}">
                            <span class="badge bg-light text-dark">
                                {{ $sig->sigHost->name }}
                            </span>
                        </a>
                    </td>
                    <td>
                        @foreach($sig->languages AS $lang)
                            {{ $lang }}
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route("locations.show", $sig->sigLocation) }}">
                            <span class="badge bg-secondary">
                                {{ $sig->sigLocation->name }}
                            </span>
                        </a>
                    </td>
                    <td>{{ $sig->timeTableCount }}</td>
                    <td>
                        <a href="{{ route("sigs.edit", $sig) }}">
                            <button type="button" class="btn btn-light"><i class="bi bi-pen"></i></button>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Noch keine SIGs eingetragen</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
