@extends('layouts.app')
@section('title', "{$entry->sigEvent->name}")
@section('content')
    <div class="container pt-3">

        @if($entry->sigEvent->is_private)
            <div class="alert text-purple-100 bg-purple-800 border-light-subtle row m-1 mb-4 d-flex align-items-center">
                <div class="col-auto ">
                    <i class="bi bi-lock fs-4"></i>
                </div>
                <div class="col">
                    {{ __("This event is not listed in the public schedule and only selected attendees can see this. Be careful sharing this with others as it may contain sensitive information!") }}
                </div>
            </div>
        @endif
        @if($entry->cancelled)
            <h1 class="d-grid mb-4">
                <span class="badge bg-danger">
                    {{ __("Cancelled") }}
                </span>
            </h1>
        @endif

        <div class="row d-flex">
            <div class="col-6 text-center align-self-center">
                <h1>{{ $entry->sigEvent->name_localized }}</h1>

                @if (in_array('de' ,$entry->sigEvent->languages))
                    <x-flag language="de" />
                @endif
                @if (in_array('en' ,$entry->sigEvent->languages))
                    <x-flag language="en" />
                @endif
            </div>
            <div class="col-6 text-center align-self-center">
                <h3 class="d-flex justify-content-center py-2">

                    <a href="{{ route("locations.show", $entry->sigLocation) }}" class="text-decoration-none">
                        <i class="bi bi-geo-alt icon-link pe-1"></i>
                        {{ $entry->sigLocation->name }}
                        @if($entry->sigLocation->description != $entry->sigLocation->name)
                            ({{ $entry->sigLocation->description }})
                        @endisset
                    </a>
                    @if($entry->hasLocationChanged)
                        <span class="badge bg-warning">{{ __("Changed") }}</span>
                    @endif
                </h3>

                <div class="d-flex flex-wrap justify-content-center">
                    @foreach($entry->sigEvent->public_hosts as $host)
                        <div class="text-nowrap d-flex">
                            <a href="{{ route("hosts.show", $host) }}" class="text-decoration-none fs-5">
                                @if($host->avatar_thumb)
                                    <img src="{{ $host->avatar_thumb }}"  class="img-thumbnail rounded-circle icon-link" style="height: 2.3em" alt="">
                                @else
                                    <i class="bi bi-person-circle fs-5 px-1 ps-3 icon-link"></i>
                                @endif
                                {{ $host->name }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <x-markdown>
                    {{ $entry->sigEvent->description_localized }}
                </x-markdown>

                @can("update", $entry->sigEvent)
                    <div class="text-end">
                        <a href="{{ App\Filament\Resources\SigEventResource::getUrl('edit', ['record' => $entry->sigEvent]) }}">
                            <i class="bi bi-pencil"></i>
                            {{ __("Edit") }}
                        </a>
                    </div>
                @endcan
            </div>
        </div>

        <div class="col-12 text-center mb-2 mt-4">
            <h2>{{ $entry->sigEvent->reg_possible ? __("Sign up") : __("Scheduled") }}</h2>
        </div>

        @if($entry->sigEvent->forms->count() > 0)
            <div class="alert alert-info mt-3">
                <p>
                    {{ __("There is a sign up form for this event:") }}
                </p>
                @foreach($entry->sigEvent->forms AS $form)
                    <a href="{{ route("forms.show", $form) }}">
                        <button type="button" class="btn btn-info">{{ $form->name_localized }}</button>
                    </a>
                @endforeach
            </div>
        @endif
        <div class="row mt-4">
            @forelse($entry->sigEvent->timetableEntries AS $e)
                @if ($e->sigTimeslots->count() > 0)
                    <div class="col-12 col-md-6">
                        <x-timeslot.accordion :entry="$e"></x-timeslot.accordion>
                    </div>
                @else
                    <div class="col-12 col-md-6 col-xl-4 m-3 mx-auto">
                        <div class="card text-center">
                            <div class="card-header">
                                <span style="font-size: 1.3em">
                                    {{ $e->start->dayName }}
                                </span>
                                <p class="card-subtitle text-secondary">
                                    {{ $e->start->format("d.m.Y") }}
                                </p>

                                @can("update", $e)
                                    <div class="text-end">
                                        <a href="{{ \App\Filament\Resources\TimetableEntryResource::getUrl('edit', ['record' => $e]) }}">
                                            <i class="bi bi-pencil"></i>
                                            {{ __("Edit") }}
                                        </a>
                                    </div>
                                @endcan
                            </div>
                            <div class="card-body">
                                {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}
                            </div>
                            <div class="card-footer">
                                <i class="bi bi-geo-alt icon-link"></i>
                                {{ $e->sigLocation->name }}
                            </div>
                        </div>
                    </div>
                @endif

            @empty
            {{ __("Not listed in schedule") }}
            @endforelse
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-center">
                <form class="w-100" id="registerForm" action="" method="POST">
                    @csrf
                    <div class="modal-header text-center">
                        <h5 class="modal-title w-100" id="registerModalLabel">{{ __("Register") }}</h5>
                    </div>
                    <div class="modal-body">
                        <p>{{ __("Would you like to register for this event?") }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button type="submit" class="btn btn-primary m-1">{{ __("Yes") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
