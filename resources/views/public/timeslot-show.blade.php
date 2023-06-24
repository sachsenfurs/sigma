@extends('layouts.app')
@section('title', "Timeslot - {$entry->sigEvent->name}")
@section('content')
    <div class="container">
            <div class="col-12 col-md-12 text-center">
                <h1>{{ $entry->sigEvent->name }}{{ $entry->sigEvent->name != $entry->sigEvent->name_en ? " | " . $entry->sigEvent->name_en : "" }}</h1>
            </div>
            <div class="row text-center">
                <div class="col-6 col-md-12">
                    <div class="col-12 text-center col-md-12">
                        <h3>Room</h3>
                    </div>
                    <div class="col-12 text-center col-md-12">
                        <p>{{ $entry->sigEvent->sigLocation->name }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-12">
                    <div class="col-12 text-center col-md-12">
                        <h3>Host</h3>
                    </div>
                    <div class="col-12 text-center col-md-12">
                        <p>{{ $entry->sigEvent->sigHost->name }}</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-12 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link w-50 active" id="desc-nav-ger-tab" data-bs-toggle="tab" data-bs-target="#description-german" type="button" role="tab" aria-controls="description-german" aria-selected="true">DE</button>
                        <button class="nav-link w-50" id="desc-nav-en-tab" data-bs-toggle="tab" data-bs-target="#description-english" type="button" role="tab" aria-controls="description-english" aria-selected="false">EN</button>                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active border border-top-0 p-1" id="description-german" role="tabpanel" aria-labelledby="desc-nav-ger-tab">
                        {!! nl2br($entry->sigEvent->description) !!}
                    </div>
                    <div class="tab-pane fade border border-top-0 p-1" id="description-english" role="tabpanel" aria-labelledby="desc-nav-en-tab">
                        {!! nl2br($entry->sigEvent->description_en) !!}
                    </div>
                </div>
                <div id="event-description">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-4 mb-md-0 text-center">
                            
                            <span class="readmore-link"></span>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-4 mb-md-0 text-center">
                            <i class="flag flag-us"></i>
                            
                            <span class="readmore-link"></span>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
                <div class="row">
                        @if ($entry->sigEvent->reg_possible)
                            <div class="col-12 col-md-12 text-center mb-2 mt-1">
                                <h2>Buchbare Zeitslots</h2>
                            </div>
                        @endif
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
                        <div class="col-12 col-md-6">                                
                            <div class="accordion">
                                @if ($e->sigTimeslots->isEmpty() == false)
                                    <div class="accordion-item mt-1 mb-1">
                                        <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-ts_{{ $e->id }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                            {{ $e->start->format("d.m.Y") }} - {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}<br>{{ $counter }} Freie Plätze
                                        </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapse-ts_{{ $e->id }}" class="accordion-collapse collapse show">
                                            @if ($counter != 0)
                                                <div class="accordion-body container text-center">
                                                    @forelse ($e->sigTimeslots as $timeslot)
                                                        @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                                                        <div class="row mb-3 mb-md-0">
                                                        @else
                                                        <div class="row mb-3 mb-md-0 disabled">
                                                        @endif
                                                            <!-- TODO Change text style into class with mobile view -->
                                                            <div class="col-lg-4 col-6 border" style="padding: 6px;">
                                                                <span class="text-center">{{ date('H:i', strtotime($timeslot->slot_start)) }} - {{ date('H:i', strtotime($timeslot->slot_end))  }}</span>
                                                            </div>
                                                            <div class="col-lg-4 col-6 border" style="padding: 6px;">
                                                                <span>{{ $timeslot->sigAttendees->count() }}/{{$timeslot->max_users}}</span><br class="d-lg-none">
                                                                <span>Slots taken</span>
                                                            </div>
                                                            @php
                                                                $currentTime = Carbon\Carbon::now()->toDateTimeString();
                                                                $regStart = strtotime($timeslot->reg_start);
                                                                $regEnd = strtotime($timeslot->reg_end);
                                                            @endphp
                                                            @if ($currentTime > $regStart && $currentTime < $regEnd)
                                                                <a class="col-lg-4 col-12 border align-middle text-center btn btn-primary" href="#">
                                                                    Register
                                                                </a>
                                                            @elseif ($currentTime < $regStart)
                                                                <a class="col-lg-4 col-12 border align-middle text-center btn btn-warning" disabled>
                                                                    Registeration opens soon
                                                                </a>
                                                            @elseif ($currentTime > $regEnd)
                                                                <a class="col-lg-4 col-12 border align-middle text-center btn btn-secondary" disabled>
                                                                    Expired
                                                                </a>
                                                            @endif
                                                        </div>
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
                            </div>
                        </div>
                    @empty
                        Nicht im Programmplan
                    @endforelse                        
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
