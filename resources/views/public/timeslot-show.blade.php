@extends('layouts.app')
@section('title', "Timeslot - {$entry->sigEvent->name}")
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                {{ $entry->sigEvent->name }}
                {{ $entry->sigEvent->name != $entry->sigEvent->name_en ? " | " . $entry->sigEvent->name_en : "" }}
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>
                            <p>
                                <span class="badge bg-secondary">{{ $entry->sigEvent->sigLocation->name }}</span>
                            </p>
                        </td>
                        <td>
                            @forelse($entry->sigEvent->timetableEntries AS $e)
                                <p>
                                    <b>{{ $e->start->format("d.m.Y") }}</b><br>
                                    {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}
                                </p>
                            @empty
                                Nicht im Programmplan
                            @endforelse
                        </td>
                        <td>
                            {!! nl2br($entry->sigEvent->description) !!}
                            <hr>
                            {!! nl2br($entry->sigEvent->description_en) !!}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
