@extends('layouts.app')
@section('title', "Meine veranstalteten Events")
@section('content')
<div class="container">
    <div class="col-12 col-md-12">
        <div class="row mb-4">
            <div class="col-12 col-md-2 text-center">
                <a class="btn btn-primary" href="{{ route('mysigs.index') }}">Back to list</a>
            </div>
            <div class="col-12 col-md-8 text-center">
                <h1>{{ $sig->name }}</h1>
            </div>
            <div class="col-12 col-md-2"></div>
        </div>
        <div class="row">
            <div class="col-12 col-md-8">
                <h2 class="text-center text-md-start">Timetable Entries</h2>
                <div class="accordion" id="accordionPanelsStayOpenExample">
                    @foreach ($sig->timetableEntries as $tte)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-{{ $tte->id }}" aria-expanded="true" aria-controls="panelsStayOpen-collapse{{ $tte->id }}">
                                    {{ date('l', strtotime($tte->start)) }} ({{ date('H:i', strtotime($tte->start)) }} - {{ date('H:i', strtotime($tte->end)) }}) - <i class="bi bi-heart-fill text-danger pl-2 pr-2"></i> {{ $additionalInformations[$tte->id]['favorites'] }}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapse-{{ $tte->id }}" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    @forelse ($tte->sigTimeslots as $ts)
                                        <a href="{{ route('timeslots.editNotes', $ts->id) }}" style="text-decoration: none; color: #000;">
                                            <div class="col-12 col-md-12 mb-2">
                                                <div class="row border border-light">
                                                    <div class="col-12 col-md-3 bg-light d-flex" style="align-items: center; justify-content: center;">
                                                        <h3>{{ date('H:i', strtotime($ts->slot_start)) }} - {{ date('H:i', strtotime($ts->slot_end)) }}</h3>
                                                    </div>
                                                    <div class="col-12 col-md-9 p-2">
                                                        <h3>{{ __('Attendees') }}</h3>
                                                        <div class="row">
                                                            @foreach ($additionalInformations[$tte->id]['timeslots'][$ts->id] as $attendee)
                                                                <div class="col-12 col-md-6">
                                                                    @if ($attendee->user->hasGroup('fursuiter'))
                                                                        <img height="16px" src="{{ asset('icons/paw.svg') }}" alt="Attendee Paw">
                                                                    @endif
                                                                    {{ $attendee->user->name }} ({{ $attendee->user->reg_id }})
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @if ($ts->notes)
                                                            <h3 class="mt-2">Notes:</h3>
                                                            <div class="row m-0">
                                                                <div class="col-12 col-md-12 p-2">
                                                                    {{ $ts->notes }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="col-12 col-md-12 text-center m-0 p-0">
                                            @if ($sig->reg_possible)
                                                <p>No timeslots created yet.</p>
                                            @else
                                                <p>Registrations for this Event are disabled.</p>
                                            @endif
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                  </div>
            </div>
            <div class="col-12 col-md-12 mt-5 d-block d-sm-none mx-auto">
            </div>
            <div class="col-12 col-md-4">
                <div class="col-12 col-md-12">
                    <h2 class="text-center text-md-start">General</h2>
                    <div class="row">
                        <div class="col-6 col-md-4 text-end">
                            Room
                        </div>
                        <div class="col-6 col-md-8 text-start">
                            {{ $sig->sigLocation->name }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-4 text-end">
                            Attendee list
                        </div>
                        <div class="col-6 col-md-8 text-start">
                            @if ($sig->attendees_public)
                                PUBLIC
                            @else
                                PRIVATE
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 mt-3">
                    <h2 class="text-center text-md-start">Description</h2>
                    <div class="col-12 col-md-12 text-center text-md-start">
                        <h3>German</h3>
                        <p>{{ $sig->description }}</p>
                    </div>
                    <div class="col-12 col-md-12 text-center text-md-start">
                        <h3>English</h3>
                        <p>{{ $sig->description_en }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-2 text-center">
                <a class="btn btn-primary" href="{{ route('mysigs.index') }}">Back to list</a>
            </div>
            <div class="col-12 col-md-8 text-center">
                <p class="text-secondary">If you have questions, please reach out to the Leitstelle!</p>
            </div>
            <div class="col-12 col-md-2"></div>
        </div>
    </div>
</div>
@endsection