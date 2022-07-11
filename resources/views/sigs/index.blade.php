@extends('layouts.app')
@section('title', "Übersicht")
@section('content')
    <div class="container">
        <table class="table table-hover">
            <tr>
                <th>Titel</th>
                <th>Host</th>
                <th>Sprachen</th>
                <th>Location</th>
                <th>Im Programmplan</th>
            </tr>
            @forelse($sigs AS $sig)
                <tr class="@if($sig->timeTableCount == 0) alert-danger @endif">
                    <td><a href="{{ route("sigs.edit", $sig) }}">{{ $sig->name }}</a></td>
                    <td>{{ $sig->sigHost->name }}</td>
                    <td>
                        @foreach($sig->languages AS $lang)
                            {{ $lang }}
                        @endforeach
                    </td>
                    <td>{{ $sig->sigLocation->name ?? "-" }}</td>
                    <td>{{ $sig->timeTableCount }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Noch keine SIGs eingetragen</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
