@extends('layouts.app')
@section('title', "Hosts- {$host->name}")
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                {{ $host->name }}
            </div>
            <div class="card-body">
                <table class="table">
                    @forelse($sigs AS $sig)
                        <tr>
                            <td>
                                <strong><a href="{{ route("sigs.edit", $sig) }}">{{ $sig->name }}</a></strong>
                                <p>
                                    <a href="{{ route("locations.show", $sig->sigLocation) }}"><span class="badge bg-secondary">{{ $sig->sigLocation->name }}</span></a>
                                </p>
                            </td>
                            <td>
                                @forelse($sig->timetableEntries AS $entry)
                                    <p>
                                        <b>{{ $entry->start->format("d.m.Y") }}</b><br>
                                        {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                                    </p>
                                @empty
                                    Nicht im Programmplan
                                @endforelse
                            </td>
                            <td>
                                {{ $sig->description }}
                            </td>
                        </tr>
                    @empty
                        Keine SIGs zugeordnet
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection
