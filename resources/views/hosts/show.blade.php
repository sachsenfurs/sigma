@extends('layouts.app')
@section('title', "Hosts- {$host->name}")
@section('content')
    <div class="container">

        <div class="row justify-content-md-center">
            <div class="col-xl-12 mb-5">
                <div class="d-flex align-items-start p-2">
                    @if($host->avatar)
                        <img src="{{ $host->avatar }}" alt="" class="rounded-circle img-thumbnail" style="width: 10em">
                    @endif
                    <div class="w-100 ms-3 align-self-center">
                        <h2>{{ $host->name }}</h2>
                        <p class="text-muted">{{ $host->description }}</p>
                        @can("manage_hosts")
                            <a href="{{ route("hosts.edit", $host) }}"><i class="bi bi-pen"></i> Edit</a>
                        @endcan
                    </div>
                </div>
            </div>

        </div>
        @forelse($sigs AS $sig)
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="d-inline">{{ $sig->name }}</h3>
                    @can("manage_events")
                        <a href="{{ route("sigs.edit", $sig) }}" class="inline float-end"><i class="bi bi-pen"></i> Edit</a>
                    @endcan
                </div>

                <div class="card-body">
                    {{ $sig->description }}
                </div>

                @forelse($sig->timetableEntries AS $entry)
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-auto d-flex">
                                        <i class="bi bi-clock align-self-center"></i>
                                    </div>
                                    <div class="col-auto">
                                        <b>{{ $entry->start->format("l") }}</b> <i class="text-muted"> {{ $entry->start->format("d.m.y") }}</i>
                                        <div class="text-muted">
                                            {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex">
                                        <div class="align-self-center">
                                            @if($entry->cancelled)
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                @if($entry->sigEvent->reg_possible)
                                                    <a href="{{ route("public.timeslot-show", $entry) }}" class="btn btn-success">Click here to sign up</a>
                                                @endif
                                                @if($entry->hasTimeChanged())
                                                    <span class="badge bg-warning">Changed</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-auto d-flex">
                                        <i class="bi bi-pin-map-fill align-self-center"></i>
                                    </div>
                                    <div class="col-auto">
                                        <b>{{ $entry->sigLocation->name }}</b>
                                    </div>
                                    <div class="col-auto d-flex">
                                        <div class="align-self-center">
                                            @if($entry->hasLocationChanged())
                                                <span class="badge bg-warning">Changed</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </li>
                        </ul>

                @empty
                    <div class="card-footer">
                        Nicht im Programmplan
                    </div>
                @endforelse
            </div>
        @empty
            <div class="alert alert-info">
                Keine SIGs zugeordnet
            </div>
        @endforelse

    </div>
@endsection
