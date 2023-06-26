@extends('layouts.app')
@section('title', "Timeslot erstellen - {$entry->sigEvent->name}")
@section('content')
<form id="createForm" action="/timeslots" method="POST">
    <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Neuen Timeslot erstellen</h5>
    </div>
    <div class="modal-body">
        <input type="number" class="d-none" name="timetable_entry_id" id="timetable_entry_id" value="{{ $entry->id }}">
        <div class="form-group row m-1">
            <label for="" class="col-sm-4 col-form-label text-end">Slot Start</label>
            <div class="col-sm-8">
                <input type="time" class="form-control" name="slot_start" id="slot_start" value="{{ date('H:i', strtotime($entry->start)) }}">
            </div>
        </div>
        <div class="form-group row m-1">
            <label for="" class="col-sm-4 col-form-label text-end">Slot Ende</label>
            <div class="col-sm-8">
                <input type="time" class="form-control" name="slot_end" id="slot_end" value="{{ date('H:i', strtotime("+15 minutes", strtotime($entry->start))) }}">
            </div>
        </div>
        <div class="form-group row m-1">
            <label for="" class="col-sm-4 col-form-label text-end">Registrierung Start</label>
            <div class="col-sm-8">
                <input type="datetime-local" class="form-control" name="reg_start" id="reg_start" value="{{ date('Y-m-d H:i', strtotime("-24 hours", strtotime($entry->start))) }}">
            </div>
        </div>
        <div class="form-group row m-1">
            <label for="" class="col-sm-4 col-form-label text-end">Registrierung Ende</label>
            <div class="col-sm-8">
                <input type="datetime-local" class="form-control" name="reg_end" id="reg_end" value="{{ date('Y-m-d H:i', strtotime("-60 minutes", strtotime($entry->start))) }}">
            </div>
        </div>
        <div class="form-group row m-1">
            <label for="" class="col-sm-4 col-form-label text-end">Max Teilnehmer</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" name="max_users" id="max_users" value="1">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @csrf
        <a class="btn btn-secondary" onclick="$('#createModal').modal('hide');" data-dismiss="modal">Abbrechen</a>
        <button type="submit" class="btn btn-primary">Timeslot erstellen</button>
    </div>
</form>
@endsection
