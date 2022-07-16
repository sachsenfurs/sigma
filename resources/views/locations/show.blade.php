@extends('layouts.app')
@section('title', "Locations - {$location->name}")
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                {{ $location->name }}
            </div>
            <div class="card-body">
                <table class="table">
                    @forelse($location->timetableEntries AS $entry)
                        <tr>
                            <td>
                                <strong>
                                    <a href="{{ route("sigs.edit", $entry->sigEvent) }}">{{ $entry->sigEvent->name }}</a>
                                </strong>
                            </td>
                            <td>

                                    <p>
                                        <b>{{ $entry->start->format("d.m.Y") }}</b><br>
                                        {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                                    </p>

                            </td>
                            <td>{{ $entry->sigEvent->description }}</td>
                        </tr>
                    @empty
                        Keine SIGs zugeordnet
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection
