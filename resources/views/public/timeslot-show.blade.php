@extends('layouts.app')
@section('title', "Timeslot - {$entry->sigEvent->name}")
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <strong>
                    {{ $entry->sigEvent->name }}
                    {{ $entry->sigEvent->name != $entry->sigEvent->name_en ? " | " . $entry->sigEvent->name_en : "" }}
                </strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center col-md-12">
                        <h2>Room</h2>
                    </div>
                    <div class="col-12 text-center col-md-12">
                        <p><span class="badge bg-secondary">{{ $entry->sigEvent->sigLocation->name }}</span></p>
                    </div>
                </div>
                <hr>
                <div id="event-description">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-4 mb-md-0 text-center">
                            {!! nl2br($entry->sigEvent->description) !!}
                        </div>
                        <div class="col-lg-6 col-md-12 mb-4 mb-md-0 text-center">
                            {!! nl2br($entry->sigEvent->description_en) !!}
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="accordion" id="timeslot-list">
                        @forelse($entry->sigEvent->timetableEntries AS $e)  
                            @php
                                $counter = 0;
                            @endphp                               
                            @if ($e->sigTimeslots->isEmpty() == false)
                                @forelse ($e->sigTimeslots as $timeslot)
                                    @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                                    @php
                                        $counter = $counter + ($timeslot->max_users - $timeslot->sigAttendees->count());
                                    @endphp
                                    @endif
                                @empty
                                @endforelse
                            @endif                                
                            @if ($e->sigTimeslots->isEmpty() == false)
                                <div class="accordion-item mt-1 mb-1">
                                    <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-ts_{{ $e->id }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                        {{ $e->start->format("d.m.Y") }} - {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}<br>{{ $counter }} Freie Plätze
                                    </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapse-ts_{{ $e->id }}" class="accordion-collapse collapse show">
                                        @if ($counter != 0)
                                            <div class="accordion-body">
                                                @forelse ($e->sigTimeslots as $timeslot)
                                                    @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                                                        <div class="row mb-3 mb-md-0">
                                                            <div class="col-lg-2 col-6 border">
                                                                <span class="align-middle text-center">{{ date('H:i', strtotime($timeslot->slot_start)) }} - {{ date('H:i', strtotime($timeslot->slot_end))  }}</span>
                                                            </div>
                                                            <div class="col-lg-2 col-6 border">
                                                                <span>{{ $timeslot->sigAttendees->count() }}/{{$timeslot->max_users}}</span><br>
                                                                <span>Slots taken</span>
                                                            </div>
                                                            @php
                                                                $currentTime = Carbon\Carbon::now()->toDateTimeString();
                                                                $regStart = strtotime($timeslot->reg_start);
                                                                $regEnd = strtotime($timeslot->reg_end);
                                                            @endphp
                                                            @if ($currentTime > $regStart && $currentTime < $regEnd)
                                                                <a class="col-lg-2 col-12 border align-middle text-center btn btn-primary" href="#">
                                                                    Register
                                                                </a>
                                                            @elseif ($currentTime < $regStart)
                                                                <a class="col-lg-2 col-12 border align-middle text-center btn btn-warning" disabled>
                                                                    Registeration opens soon
                                                                </a>
                                                            @elseif ($currentTime > $regEnd)
                                                                <a class="col-lg-2 col-12 border align-middle text-center btn btn-secondary" disabled>
                                                                    Expired
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @empty
                                                    <p> All Slots are currently booked</p>
                                                @endforelse
                                            </div>
                                        @else
                                            <p>No more slots available.</p>
                                        @endif
                                        
                                    </div>
                                </div>  
                            @else
                                <p class="mt-1 mb-1">
                                    <b>{{ $e->start->format("d.m.Y") }}</b><br>
                                    {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}
                                </p>
                            @endif
                        @empty
                            Nicht im Programmplan
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <!--
        <h2>LEGACY</h2>
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
                            <div class="accordion w-75 p-3" id="timeslot-list">
                                @php
                                    $counter = 0;
                                @endphp
                                @forelse($entry->sigEvent->timetableEntries AS $e)                                    
                                    @if ($e->sigTimeslots->isEmpty() == false)
                                        @forelse ($e->sigTimeslots as $timeslot)
                                            @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                                            @php
                                                $counter = $counter + ($timeslot->max_users - $timeslot->sigAttendees->count());
                                            @endphp
                                            @endif
                                        @empty
                                        @endforelse
                                    @endif
                                @empty
                                @endforelse
                                @forelse($entry->sigEvent->timetableEntries AS $e)                                    
                                    @if ($e->sigTimeslots->isEmpty() == false)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-ts_{{ $e->id }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                {{ $e->start->format("d.m.Y") }} - {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }} | Freie Plätze: {{ $counter }}
                                            </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapse-ts_{{ $e->id }}" class="accordion-collapse collapse show">
                                                @if ($counter != 0)
                                                    <div class="accordion-body">
                                                        @forelse ($e->sigTimeslots as $timeslot)
                                                            @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        {{ $timeslot->slot_start }} - {{ $timeslot->slot_end }} | {{ $timeslot->max_users - $timeslot->sigAttendees->count() }}/{{$timeslot->max_users}} Slots available
                                                                        <button type="button" href="" class="btn btn-primary">Register</button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @empty
                                                            <p> All Slots are currently booked</p>
                                                        @endforelse
                                                    </div>
                                                @else
                                                    <p>No more slots available.</p>
                                                @endif
                                                
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
        -->
    </div>
@endsection
