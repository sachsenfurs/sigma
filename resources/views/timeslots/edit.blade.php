@extends('layouts.app')
@section('title', "Timeslot bearbeiten | {$timeslot->slot_start} - {$timeslot->slot_end}")
@section('content')
<form id="createForm" action="/timeslots/{{ $timeslot->id}}" method="post" class="col-6 col-md-6 mx-auto">
    @method("POST")
    <div class="card">
        <div class="card-header text-center">
            <strong>    
                Timeslot bearbeiten
            </strong>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-12 col-md-3 border-right">
                        <h2>Infos</h2>
                    </div>
                    <div class="col-12 col-md-9">
                        <div class="row">
                            <div class="col-4 col-md-4 text-end">
                                <strong>Event</strong>
                            </div>
                            <div class="col-8 col-md-8">
                                {{ $timeslot->timetableEntry->sigEvent->name }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 col-md-4 text-end">
                                <strong>Kalendereintrag</strong>
                            </div>
                            <div class="col-8 col-md-8">
                                {{ date('d.h.Y', strtotime($timeslot->timetableEntry->start)) }}<br>
                                {{ date('H:i', strtotime($timeslot->timetableEntry->start)) }} - {{ date('H:i', strtotime($timeslot->timetableEntry->end)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="col-12 col-md-12">
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Slot Start</label>
                        <div class="col-sm-8">
                            <input type="time" class="form-control" name="slot_start" id="slot_start" value="{{ date('H:i', strtotime($timeslot->slot_start)) }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Slot Ende</label>
                        <div class="col-sm-8">
                            <input type="time" class="form-control" name="slot_end" id="slot_end" value="{{ date('H:i', strtotime($timeslot->slot_end)) }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Registrierung Start</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" name="reg_start" id="reg_start" value="{{ date('Y-m-d H:i', strtotime($timeslot->reg_start)) }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Registrierung Ende</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" name="reg_end" id="reg_end" value="{{ date('Y-m-d H:i', strtotime($timeslot->reg_end)) }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Max Teilnehmer</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="max_users" id="max_users" value="{{ $timeslot->max_users }}">
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="card-footer">
            @csrf
            <div class="d-flex flex-row-reverse">
                <a class="btn btn-secondary m-1" onclick="$('#createModal').modal('hide');" data-dismiss="modal">Abbrechen</a>
                <button type="submit" class="btn btn-primary m-1">Timeslot aktualisieren</button>
            </div>
        </div>
    </div>
</form>
@endsection
