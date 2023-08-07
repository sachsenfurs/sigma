@extends('layouts.app')
@section('title', "Timeslot Details")
@section('content')
    <div class="container">
        <div class="col-12 col-md-6 mx-auto">
            <div class="row">
                <div class="col-12 col-md-12 text-center">
                    <h1>{{ $timeslot->timetableEntry->sigEvent->name }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-3 text-end">
                    Timeframe
                </div>
                <div class="col-6 col-md-9 text-start">
                    {{ date('H:i', strtotime($timeslot->slot_start)) }} - {{ date('H:i', strtotime($timeslot->slot_end)) }}
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-3 text-end">
                    Attendees
                </div>
                <div class="col-6 col-md-9 text-start">
                    @foreach ($attendees as $attendee)
                        <div class="col-12 col-md-12">{{ $attendee->user->name }}</div>
                    @endforeach
                </div>
            </div>
            <form method="POST" action="{{ route('timeslots.updateNotes', $timeslot->id) }}">
                <div class="row">
                    <div class="col-12 col-md-3 text-center text-sm-end">
                        <label for="notes">
                            <strong>Notes</strong>
                        </label>
                    </div>
                    <div class="col-10 col-md-9 text-start mx-auto">
                        <textarea class="form-control m-1" name="notes" id="notes" rows="3">{{ $timeslot->notes }}</textarea>
                    </div>
                </div>
                @csrf
                <div class="d-flex flex-row-reverse" style="justify-content: center;">
                    <a href="{{url()->previous()}}" class="btn btn-secondary m-1">Abbrechen</a>
                    <button type="submit" class="btn btn-primary m-1">Speichern</button>
                </div>
            </form>
        </div>
    </div>
@endsection
