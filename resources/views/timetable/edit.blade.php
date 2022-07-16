@extends('layouts.app')
@section('title', "Timeslot bearbeiten - {$entry->sigEvent->name}")
@section('content')
    <div class="container" style="margin-bottom: 600px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ route("timetable.update", $entry) }}">
                    @csrf
                    @method("PUT")
                    <div class="card">
                        <div class="card-header">
                            <strong><a href="{{ route("sigs.edit", $entry->sigEvent) }}">{{ Str::upper($entry->sigEvent->name) }}</a></strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <label>Start</label>
                                    <input type="datetime-local" class="form-control" name="start" value="{{ $entry->start->format("Y-m-d\TH:i") }}">
                                </div>
                                <div class="col-6">
                                    <label>Ende</label>
                                    <input type="datetime-local" class="form-control" name="end" value="{{ $entry->end->format("Y-m-d\TH:i") }}">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label>Abweichende Location</label>
                                <select name="sig_location_id" class="form-control">
                                    <option value="">-</option>
                                    @foreach($locations AS $location)
                                        <option value="{{ $location->id }}" {{ $entry->sig_location_id == $location->id ? "selected" : "" }}>{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-4">
                                <div class="form-check">
                                    <label>
                                        <input class="form-check-input" type="checkbox" name="cancelled" {{ $entry->cancelled ? "checked" : ""}}>
                                        Event Abgesagt
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label>
                                        <input class="form-check-input" type="checkbox" name="hide" {{ $entry->hide ? "checked" : ""}}>
                                        Internes Event
                                    </label>
                                </div>
                                <div class="form-check mt-3">
                                    <label>
                                        <input class="form-check-input" type="checkbox" name="ignore_update">
                                        Änderung nicht kommunizieren
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <input type="submit" class="btn btn-success" value="Speichern">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="accordion mt-4" id="options">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Timeslot löschen
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#options">
                            <div class="accordion-body text-center">
                                <form method="POST" action="{{ route("timetable.destroy", $entry) }}">
                                    @csrf
                                    @method("DELETE")
                                    <input type="submit" class="btn btn-danger" name="delete" value="Wirklich löschen?">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
