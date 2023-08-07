@extends('layouts.app')
@section('title', "Kalendereintrag bearbeiten - {$entry->sigEvent->name}")
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
                                <div class="form-check">
                                    <label>
                                        <input class="form-check-input" type="checkbox" name="reset_update">
                                        Reset Update-Timestamp
                                    </label>
                                </div>
                            </div>
                            <hr>
                            @if ($entry->sigEvent->reg_possible)
                                <div class="mt-3 row">
                                    <label>
                                        <h3>Timeslots verwalten</h3>
                                    </label>
                                </div>
        
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><strong>Slot Zeitraum</strong></th>
                                            <th><strong>Reg Zeitraum</strong></th>
                                            <th><strong>Teilnehmer</strong></th>
                                            <th><strong>Aktionen</strong></th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach($entry->sigTimeslots AS $timeslot)
                                            <tr id="{{ $timeslot->id }}">
                                                <td>
                                                    {{ date('H:i', strtotime($timeslot->slot_start)) }} - {{ date('H:i', strtotime($timeslot->slot_end)) }}
                                                </td>
                                                <td>
                                                    @if ($timeslot->reg_start)
                                                        {{ date('d.m. H:i', strtotime($timeslot->reg_start)) }} - {{ date('d.m. H:i', strtotime($timeslot->reg_end)) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $timeslot->sigAttendees->count() }} / {{ $timeslot->max_users }}
                                                </td>
                                                <td>
                                                    <a type="button" class="btn btn-info text-white" href="/timeslots/{{ $timeslot->id }}/edit">
                                                        <span class="bi bi-pencil"></span>
                                                    </a>
                                                    <a type="button" class="btn btn-success text-white" onclick="$('#userModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/{{ $timeslot->id }}')" data-toggle="modal" data-target="#deleteModal" data-timeslot="{{ $timeslot->id }}">
                                                        <span class="bi bi-people-fill"></span>
                                                    </a>
                                                    <button type="button" class="btn btn-danger text-white" onclick="$('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/{{ $timeslot->id }}')" data-toggle="modal" data-target="#deleteModal" data-timeslot="{{ $timeslot->id }}">
                                                        <span class="bi bi-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-secondary text-white" onclick="$('#createModal').modal('show');" data-toggle="modal" data-target="#createModal">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            @else
                                <div class="mt-3 row">
                                    <label>Registrierung für dieses Event sind deaktiviert</label>
                                </div>
                            @endif
                            
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
                                Kalendereintrag löschen
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
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
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
          </div>
        </div>
    </div>
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="userModalLabel">Teilnehmerliste</h5>
            </div>
            <div class="modal-body">
                <ul>
                    <li>Teilnehmer 1</li>
                    <li>Teilnehmer 2</li>
                </ul>
            </div>
            <div class="modal-footer">
                <form id="userForm" action="" method="POST">
                    @method('CREATE')
                    @csrf
                    <a class="btn btn-secondary" onclick="$('#userModal').modal('hide');" data-dismiss="modal">Schließen</a>
                </form>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">Timeslot löschen?</h5>
            </div>
            <div class="modal-body">
                Timeslot wirklich löschen?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    @method('DELETE')
                    @csrf
                    <a class="btn btn-secondary" onclick="$('#deleteModal').modal('hide');" data-dismiss="modal">Abbrechen</a>
                    <button type="submit" class="btn btn-danger">LÖSCHEN</button>
                </form>
            </div>
          </div>
        </div>
    </div>
    <script>
		$('#addTimeslot').click(function() {
            $('#timeslots').append($('#timeslot').parent().html());
            $('#timeslots').parent().find('input[type="time"]').each(function(i,e) {
                if(i > 1)
                    $(e).attr('name', $(e).data("name")).show();
            });
            $('#timeslots').parent().find('input[type="datetime-local"]').each(function(i,e) {
                if(i > 1)
                    $(e).attr('name', $(e).data("name")).show();
            });
            $('#timeslots').parent().find('input[type="number"]').each(function(i,e) {
                if(i > 1)
                    $(e).attr('name', $(e).data("name")).show();
            });
        });
        document.getElementById('reg_start').onchange = function () {
            var start = $('#reg_start').val();
            start = new Date(start);
            var month = ('0' + (start.getMonth()+1)).slice(-2);
            var year = start.getFullYear();
            var day = ('0'+start.getDate()).slice(-2);

            var hour = start.getHours()+24;
            var min = ('0'+start.getMinutes()).slice(-2);
            var sec = ('0'+start.getMilliseconds()).slice(-2);

            end = year+'-'+month+'-'+day+' '+hour+':'+min+':'+sec;
            $('#reg_end').val(end)
        }
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
        function addHours(date, hours) {
            date.setHours(date.getHours() + hours);

            return date;
        }
	</script>
@endsection
