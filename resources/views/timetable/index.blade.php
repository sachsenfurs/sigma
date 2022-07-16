@extends('layouts.app')
@section('title', "Timetable")
@section('content')
    <div class="mt-4 mb-4 text-center">

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEntryModal"> Eintrag hinzufügen</button>

    </div>
    <div class="modal fade" id="createEntryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Timeslot hinzufügen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name">SIG</label>
                            <select name="sig_event_id" class="form-control">
                                @foreach($sigEvents AS $sig)
                                    <option value="{{ $sig->id }}">{{ $sig->name }} - {{ $sig->sigLocation->name . " " . $sig->sigLocation->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email">Zeiten</label>
                            <div class="mt-1 row">
                                <div class="col-6">
                                    <input type="datetime-local" class="form-control" name="start" value="{{ \Carbon\Carbon::now()->format("Y-m-d\TH:i") }}">
                                </div>
                                <div class="col-6">
                                    <input type="datetime-local" class="form-control" name="end" value="{{ \Carbon\Carbon::now()->format("Y-m-d\TH:i") }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="name">Abweichende Location (optional)</label>
                            <select name="sig_location_id" class="form-control">
                                <option value="">-</option>
                                @foreach($sigLocations AS $location)
                                    <option value="{{ $location->id }}">{{ $location->name }} {{ $location->description ? " - " . $location->description : "" }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary">Eintragen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <table class="table table-hover">
            <tr>
                <th>Zeit</th>
                <th>Event</th>
                <th>Location</th>
                <th class="text-end">Aktionen</th>
            </tr>
            @foreach($entriesPerDay AS $day=>$entries)
                <tr>
                    <th colspan="4" class="text-capitalize">{{ Str::upper(\Carbon\Carbon::parse($day)->dayName) }}, {{ \Carbon\Carbon::parse($day)->format("d.m.Y") }}</th>
                </tr>
                @foreach($entries AS $entry)
                    <tr class="{{ $entry->start < now() ? ($entry->end > now() ? "table-primary" : "table-secondary dosab") : ""}}">
                        <td>
                            {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                        </td>
                        <td>
                            <a href="{{ route("sigs.edit", $entry->sigEvent) }}">{{ $entry->sigEvent->name }}</a></td>
                        </td>
                        <td>
                            <a href="{{ route("locations.show", $entry->sigLocation) }}">
                                <span class="badge bg-secondary">{{ $entry->sigLocation->name }}</span>
                            </a>
                        </td>
                        <td class="text-end">
                            <a href="{{ route("timetable.edit", $entry) }}">
                                <button type="button" class="btn btn-secondary">Bearbeiten</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </div>
@endsection
