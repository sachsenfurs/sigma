@extends('layouts.app')
@section('title', "{$entry->sigEvent->name}")
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("schedule.listview") }}">{{ __("Event Schedule") }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route("schedule.listview") }}">{{ $entry->start->dayName }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($entry->sigEvent->name, 20) }}</li>
            </ol>
        </nav>

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
                    <img height="19px" src="{{ asset('icons/de-flag.svg') }}" alt="Event in german">
                @endif
                @if (in_array('en' ,$entry->sigEvent->languages))
                    <img height="19px" src="{{ asset('icons/uk-flag.svg') }}" alt="Event in english">
                @endif
            </div>
            <div class="col-6 text-center align-self-center">
                <h3 class="">
                    <i class="bi bi-geo-alt align-self-center"></i>
                    <a href="{{ route("locations.show", $entry->sigLocation) }}" class="text-decoration-none">
                        {{ $entry->sigLocation->name }}
                    </a>
                    @if($entry->hasLocationChanged)
                        <span class="badge bg-warning">{{ __("Changed") }}</span>
                    @endif
                </h3>
                @if(!$entry->sigEvent->sigHost->hide)
                    <h5>
                        <i class="bi bi-person-circle align-self-center"></i>
                        <a href="{{ route("hosts.show", $entry->sigEvent->sigHost) }}" class="text-decoration-none">
                            {{ $entry->sigEvent->sigHost->name }}
                        </a>
                    </h5>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <x-markdown>
                    {{ $entry->sigEvent->description_localized }}
                </x-markdown>

                @can("manage_events")
                    <div class="text-end">
                        <a href="{{ App\Filament\Resources\SigEventResource::getUrl('edit', ['record' => $entry->sigEvent]) }}">
                            <i class="bi bi-pencil"></i>
                            {{ __("Edit") }}
                        </a>
                    </div>
                @endcan
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center mb-2 mt-1">
                <h2>{{ $entry->sigEvent->reg_possible ? __("Sign up") : __("Scheduled") }}</h2>
            </div>

            @forelse($entry->sigEvent->timetableEntries AS $e)
                @if ($e->sigTimeslots->count() > 0)
                    <div class="col-12 col-md-6">
                        <x-timeslot.accordion :entry="$e"></x-timeslot.accordion>
                    </div>
                @else
                    <div class="col-5 col-md-4 m-3 mx-auto">
                        <div class="card text-center">
                            <div class="card-header">
                                <span style="font-size: 1.3em">
                                    {{ $e->start->dayName }}
                                </span>
                                <p class="card-subtitle text-secondary">
                                    {{ $e->start->format("d.m.Y") }}
                                </p>

                                @can("manage_events")
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
                                <i class="bi bi-geo-alt"></i>
                                {{ $e->sigLocation->name }}
                            </div>
                        </div>
                    </div>
                @endif

            @empty
                Nicht im Programmplan
            @endforelse
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-center">
                <form class="w-100" id="registerForm" action="" method="POST">
                    <div class="modal-header text-center">
                        <h5 class="modal-title w-100" id="registerModalLabel">Registrieren</h5>
                    </div>
                    <div class="modal-body">
                        @can("manage_events")
                            <p>Wen möchtest du für dieses Event registrieren?</p>
                            <p><a href=""></a></p>
                            <div class="row">
                                <div class="col-4">
                                    <p>Reg-Nummer</p>
                                </div>
                                <div class="col-8">
                                    <input type="text" class="form-control" name="regId" placeholder="Reg-ID" value="{{ Auth::user()->reg_id }}">
                                </div>
                            </div>
                        @else
                            <p>Möchtest du dich für dieses Event registrieren?</p>
                        @endcan
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary m-1">Registrieren</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
