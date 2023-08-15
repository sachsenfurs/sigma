@extends('layouts.app')
@section('title', "Timeslot - {$entry->sigEvent->name}")
@section('content')
    <div class="container">
            <div class="col-12 col-md-12 text-center">
                <h1>
                    {{ $entry->sigEvent->name }}<br>
                    <small class="text-muted">{{ $entry->sigEvent->name != $entry->sigEvent->name_en ? $entry->sigEvent->name_en : "" }}</small>
                </h1>
                @if (in_array('de' ,$entry->sigEvent->languages))
                    <img height="19px" src="{{ asset('icons/de-flag.svg') }}" alt="Event in german">
                @endif
                @if (in_array('en' ,$entry->sigEvent->languages))
                    <img height="19px" src="{{ asset('icons/uk-flag.svg') }}" alt="Event in english">
                @endif
            </div>
            <div class="row text-center" style="margin-top: 0.5rem;">
                <div class="col-12 col-md-12">
                    <div class="col-12 text-center col-md-12">
                        <i class="bi bi-geo-alt"></i> {{ $entry->sigEvent->sigLocation->name }}
                    </div>
                    <div class="col-12 text-center col-md-12">
                        <i class="bi bi-person-circle"></i> {{ $entry->sigEvent->sigHost->name }}
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-12 col-md-12">
                <x-markdown>
                    {{ $entry->sigEvent->description_localized }}
                </x-markdown>
            </div>
            <hr>
            @php

            @endphp
                <div class="row">
                    @if ($entry->sigEvent->reg_possible)
                        <div class="col-12 col-md-12 text-center mb-2 mt-1">
                            <h2>Buchbare Zeitslots</h2>
                        </div>
                    @else
                        <div class="col-12 col-md-12 text-center mb-2 mt-1">
                            <h2>Zeitslots</h2>
                        </div>
                    @endif
                    @forelse($entry->sigEvent->timetableEntries AS $e)
                        @php
                            $counter = 0;
                        @endphp

                        @foreach ($e->sigTimeslots as $timeslot)
                            @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                            @php
                                $counter = $counter + ($timeslot->max_users - $timeslot->sigAttendees->count());
                            @endphp
                            @endif
                        @endforeach

                        @if ($e->sigTimeslots)
                            <div class="col-12 col-md-6">
                                <div class="accordion">
                                <div class="accordion-item mt-1 mb-1">
                                    <div class="accordion-header">

                                    @if ($e->end > Carbon\Carbon::now()->toDateTimeString())
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-ts_{{ $e->id }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                            {{ $e->start->format("l") }} - {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}<br>{{ $counter }} Freie Plätze<br>@if($e->sigEvent->max_regs_per_day)Max. {{ $e->sigEvent->max_regs_per_day }} Registrierungen pro Tag @endif
                                        </button>
                                    @elseif (Carbon\Carbon::create($e->end)->addHours(12) > Carbon\Carbon::now()->toDateTimeString())
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-ts_{{ $e->id }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                            {{ $e->start->format("l") }} - {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}<br>Event hat bereits stattgefunden
                                        </button>
                                    @else
                                        <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-ts_{{ $e->id }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                            {{ $e->start->format("l") }} - {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}<br>Event hat bereits stattgefunden
                                        </button>
                                    @endif

                                    </div>
                                    @if ($e->end > Carbon\Carbon::now()->toDateTimeString())
                                    <div id="panelsStayOpen-collapse-ts_{{ $e->id }}" class="accordion-collapse collapse show">
                                    @else
                                    <div id="panelsStayOpen-collapse-ts_{{ $e->id }}" class="accordion-collapse collapse">
                                    @endif
                                        <div class="accordion-body container text-center">
                                            @foreach ($e->sigTimeslots as $timeslot)
                                                @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                                                    <div class="row mb-3 mb-md-0">
                                                @else
                                                    <div class="row mb-3 mb-md-0 bg-secondary text-white">
                                                @endif
                                                    <!-- TODO Change text style into class with mobile view -->
                                                    <div class="col-lg-4 col-6 border" style="padding: 6px;">
                                                        <span class="text-center">{{ date('H:i', strtotime($timeslot->slot_start)) }} - {{ date('H:i', strtotime($timeslot->slot_end))  }}</span>
                                                    </div>
                                                    <div class="col-lg-4 col-6 border" style="padding: 6px;">
                                                        <span>{{ $timeslot->sigAttendees->count() }}/{{$timeslot->max_users}}</span><br class="d-lg-none">
                                                        <span>Plätze belegt</span>
                                                    </div>
                                                    @php
                                                        $currentTime = strtotime(Carbon\Carbon::now()->toDateTimeString());
                                                        $regStart = strtotime($timeslot->reg_start);
                                                        $regEnd = strtotime($timeslot->reg_end);
                                                    @endphp

                                                    @if ($regStart > $currentTime)
                                                        <a class="col-lg-4 col-12 border align-middle text-center btn btn-warning" disabled>
                                                            Registrierung öffnet bald
                                                        </a>
                                                    @elseif ($regEnd < $currentTime)
                                                        <a class="col-lg-4 col-12 border align-middle text-center btn btn-secondary" disabled>
                                                            Ausgelaufen
                                                        </a>
                                                    @else
                                                        @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                                                            @if (auth()->user()?->sigTimeslots?->find($timeslot))
                                                                <a class="col-lg-4 col-12 border align-middle text-center btn btn-success" disabled>
                                                                    Bereits registriert
                                                                </a>
                                                            @elseif ($timeslot->timetableEntry->maxUserRegsExeeded())
                                                                <a class="col-lg-4 col-12 border align-middle text-center btn btn-secondary" disabled>
                                                                    Tageslimit erreicht
                                                                </a>
                                                            @else
                                                                <a class="col-lg-4 col-12 border align-middle text-center btn btn-primary"
                                                                   onclick="$('#registerModal').modal('show'); $('#registerForm').attr('action', '{{ route("registration.register", $timeslot) }}')">
                                                                    Registrieren
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a class="col-lg-4 col-12 border align-middle text-center btn btn-secondary" disabled>
                                                                Ausgebucht
                                                            </a>
                                                        @endif
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-5 col-md-4 m-1 mx-auto">
                            <div class="card text-center">
                                <div class="card-header">
                                    {{ $e->start->format("l") }}
                                </div>
                                <div class="card-body">
                                    {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    Nicht im Programmplan
                @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content text-center">
            <div class="modal-header text-center">
              <h5 class="modal-title w-100" id="registerModalLabel">Registrieren</h5>
            </div>
            <div class="modal-body">
                <p>Möchtest du dich für dieses Event registrieren?</p>
            </div>
            <div class="modal-footer">
                <form class="text-center w-100" id="registerForm" action="" method="POST">
                    @csrf
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary m-1">Registrieren</button>
                </form>
            </div>
          </div>
        </div>
    </div>
@endsection
