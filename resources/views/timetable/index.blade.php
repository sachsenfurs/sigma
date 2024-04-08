@extends('layouts.app')
@section('title', "Timetable")
@section('content')
    <div class="container">

        <div class="mt-4 mb-4 text-center">

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEntryModal"> {{ __("Add Entry") }}</button>
        </div>
        <div class="m-3 text-center">
            <a class="btn btn-secondary" href="{{ route("public.tableview-old") }}">@lang("Table View")</a>
        </div>
    </div>
    <div class="modal fade" id="createEntryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("Add Time Slot") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name">{{ __("SIG") }}</label>
                            <select name="sig_event_id" class="form-control">
                                @foreach($sigEvents AS $sig)
                                    <option value="{{ $sig->id }}">{{ $sig->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>{{ __("Time span") }}</label>
                            <div class="mt-1 row">
                                <div class="col-6">
                                    <input type="datetime-local" class="form-control" name="start" value="{{ \Carbon\Carbon::now()->setMinutes(0)->format("Y-m-d\TH:i") }}">
                                </div>
                                <div class="col-6">
                                    <input type="datetime-local" class="form-control" name="end" value="{{ \Carbon\Carbon::now()->setMinutes(0)->addMinutes(60)->format("Y-m-d\TH:i") }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name">{{ __("Different Location") }}</label>
                            <select name="sig_location_id" class="form-control">
                                <option value="">-</option>
                                @foreach($sigLocations AS $location)
                                    <option value="{{ $location->id }}">{{ $location->name }} {{ $location->description ? " - " . $location->description : "" }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>
                                <input type="checkbox" class="form-check-input" name="hide"> @lang("Internal Event")
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn-primary">{{ __("Save") }}</button>
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
                <th class="text-end">{{ __("Actions") }}</th>
            </tr>
            @foreach($entriesPerDay AS $day=>$entries)
                <tr>
                    <th colspan="4" class="text-capitalize">{{ Str::upper(\Carbon\Carbon::parse($day)->dayName) }}, {{ \Carbon\Carbon::parse($day)->format("d.m.Y") }}</th>
                </tr>
                @foreach($entries AS $entry)
                    <tr class="{{ $entry->start < now() ? ($entry->end > now() ? "table-primary" : "table-secondary") : ""}}">
                        <td>
                            {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                            @if($entry->cancelled)
                                <span class="badge bg-danger">{{ __("Cancelled") }}</span>
                            @else
                                @if($entry->new)
                                    <span class="badge bg-info">{{ __("New") }}</span>
                                @endif
                                @if($entry->hasTimeChanged)
                                    <span class="badge bg-info">{{ __("Changed") }}</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            <a href="{{ route("sigs.edit", $entry->sigEvent) }}"><button type="button" @class(["btn", "btn-secondary" => !$entry->hide, "btn-dark" => $entry->hide])>{{ $entry->sigEvent->name }}</button></a>
                        </td>
                        <td>
                            <a href="{{ route("locations.show", $entry->sigLocation) }}">
                                <span class="badge bg-secondary">{{ $entry->sigLocation->name }}</span>
                            </a>
                        </td>
                        <td class="text-end">
                            <a href="{{ route("timetable.edit", $entry) }}">
                                <button type="button" class="btn btn-secondary">{{ __("Edit") }}</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </div>
@endsection
