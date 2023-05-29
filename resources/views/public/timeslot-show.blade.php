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
                                @if ($entry->sigEvent->SigTimeSlot->max_users >= $entry->sigEvent->SigTimeSlot->attendees->count() )
                                    <a href="" class="btn btn-success" role="button">Register</a>
                                @else
                                    <a href="" class="btn btn-secondary disabled" role="button" aria-disabled="true">No more slots available</a>
                                @endif
                            </p>
                        </td>
                        <td>
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                @forelse($entry->sigEvent->timetableEntries AS $e)
                                    @if ($e->Timeslots->count() >= 1)
                                        // Add Check for reg start & reg end
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                <b>{{ $e->start->format("d.m.Y") }}</b><br>
                                                {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}
                                            </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                                <div class="accordion-body">
                                                   @foreach ($e->Timeslots as $ts)
                                                    <div class="card">
                                                        <div class="card-body">
                                                            {{ $ts->slot_start->format("H:i") }} - {{ $ts->slot_end->format("H:i") }}
                                                            <button type="button" href="" class="btn btn-primary">Register</button>
                                                        </div>
                                                    </div>
                                                   @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>
                                            <b>{{ $e->start->format("d.m.Y") }}</b><br>
                                            {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}
                                        </p>
                                    @endif
                                @empty
                                    Nicht im Programmplan
                                @endforelse
                            </div>
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
