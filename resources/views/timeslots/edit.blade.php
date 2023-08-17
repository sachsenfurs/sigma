@extends('layouts.app')
@section('title', "Timeslot bearbeiten | {$timeslot->slot_start} - {$timeslot->slot_end}")
@section('content')
<form id="createForm" action="/timeslots/{{ $timeslot->id}}" method="post" class="col-6 col-md-6 mx-auto">
    @method("POST")
    <div class="card">
        <div class="card-header text-center">
            <strong>
                {{ __("Edit Time Slot") }}
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
                                <strong>{{ __("Event") }}</strong>
                            </div>
                            <div class="col-8 col-md-8">
                                {{ $timeslot->timetableEntry->sigEvent->name }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 col-md-4 text-end">
                                <strong>{{ __("Schedule Entry") }}</strong>
                            </div>
                            <div class="col-8 col-md-8">
                                {{ $timeslot->timetableEntry->start->format("d.m.Y") }}<br>
                                {{ $timeslot->timetableEntry->start->format("H:i") }} - {{ $timeslot->timetableEntry->start->end("H:i") }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="col-12 col-md-12">
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">{{ __("Slot Start") }}</label>
                        <div class="col-sm-8">
                            <input type="time" class="form-control" name="slot_start"
                                   id="slot_start" value="{{ date('H:i', strtotime($timeslot->slot_start)) }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">{{ __("Slot End") }}</label>
                        <div class="col-sm-8">
                            <input type="time" class="form-control" name="slot_end"
                                   id="slot_end" value="{{ date('H:i', strtotime($timeslot->slot_end)) }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">{{ __("Registration Start") }}</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" name="reg_start" id="reg_start" value="{{ date('Y-m-d H:i', strtotime($timeslot->reg_start)) }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">{{ __("Registration End") }}</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" name="reg_end" id="reg_end" value="{{ date('Y-m-d H:i', strtotime($timeslot->reg_end)) }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">{{ __("Max. Attendees") }}</label>
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
                <a href="{{url()->previous()}}" class="btn btn-secondary m-1">{{ __("Cancel") }}</a>
                <button type="submit" class="btn btn-primary m-1">{{ __("Update Time Slot") }}</button>
            </div>
        </div>
    </div>
</form>
<script>
document.getElementById('slot_start').onchange = function () {
    var start = $('#slot_start').val();
    start = new Date(start);
    var month = ('0' + (start.getMonth()+1)).slice(-2);
    var year = start.getFullYear();
    var day = ('0'+start.getDate()).slice(-2);

    var hour = start.getHours();
    var min = ('0'+start.getMinutes()).slice(-2)+15;
    var sec = ('0'+start.getMilliseconds()).slice(-2);

    end = year+'-'+month+'-'+day+' '+hour+':'+min+':'+sec;
    $('#slot_end').val(end)
}
document.getElementById('reg_start').onchange = function () {
    var start = $('#reg_start').val();
    start = new Date(start);
    var month = ('0' + (start.getMonth()+1)).slice(-2);
    var year = start.getFullYear();
    var day = ('0'+start.getDate()).slice(-2);

    var hour = start.getHours()+1;
    var min = ('0'+start.getMinutes()).slice(-2);
    var sec = ('0'+start.getMilliseconds()).slice(-2);

    end = year+'-'+month+'-'+day+' '+hour+':'+min+':'+sec;
    $('#reg_end').val(end)
}
function addHours(date, hours) {
    date.setHours(date.getHours() + hours);

    return date;
}
</script>
@endsection
