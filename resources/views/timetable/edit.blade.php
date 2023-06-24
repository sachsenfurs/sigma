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
							<div class="mt-3 row">
								<label>Timeslots festlegen</label>
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
                                            <td >
                                                {{ date('d.m. H:i', strtotime($timeslot->reg_start)) }} - {{ date('d.m. H:i', strtotime($timeslot->reg_end)) }}
											</td>
                                            <td>
                                                {{ $timeslot->sigAttendees->count() }} / {{ $timeslot->max_users }}
                                            </td>
											<td>
                                                <a type="button" class="btn btn-info text-white" href="/timeslot/{{ $timeslot->id }}/edit">
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
							<div id="timeslots-parent" style="display: none">
								<div class="mt-1 row timeslot" id="timeslot">
									<div class="col-2">
										<input type="time" class="form-control" data-name="time-start[]" name="tester" value="{{ date('H:i') }}">
									</div>
									<div class="col-2">
										<input type="time" class="form-control" data-name="time-end[]" name="tester2" value="{{ date('H:i', strtotime("+15 Minutes")) }}">
									</div>
                                    <div class="col-3">
                                        <input type="datetime-local" class="form-control" name="reg-start[]" value="">
                                    </div>
                                    <div class="col-3">
                                        <input type="datetime-local" class="form-control" name="reg-end[]" value="">
                                    </div>
                                    <div class="col-1">
                                        <input type="number" class="form-control" name="max-users[]" value="1">
                                    </div>
									<div class="col-1 row">
										<button type="button" class="btn btn-danger text-white" onclick="if($('.timeslot').length > 1) $(this).parent().parent().remove()">
											<span class="bi bi-trash"></span>
										</button>
									</div>
								</div>
							</div>

							<div class="mt-3">
								<button type="button" class="btn btn-secondary" id="addTimeslot"><i class="bi bi-plus"></i></button>
                                <button type="button" class="btn btn-secondary text-white" onclick="$('#createModal').modal('show');" data-toggle="modal" data-target="#createModal">
                                    +
                                </button>
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
            <div class="modal-header">
              <h5 class="modal-title" id="createModalLabel">Neuen Timeslot erstellen</h5>
            </div>
            <div class="modal-body">
                Slot Start: <input type="time" data-name="time_start" value="">
                Slot End: <input type="time" data-name="time_start" value="">
                Reg Start: <input type="datetime-local" name="reg_start" value="">
                Reg Start: <input type="datetime-local" name="reg_end" value="">
                Max Users: <input type="number" data-name="max-users" value="1">
            </div>
            <div class="modal-footer">
                <form id="createForm" action="" method="POST">
                    @method('CREATE')
                    @csrf
                    <a class="btn btn-secondary" onclick="$('#createModal').modal('hide');" data-dismiss="modal">Abbrechen</a>
                    <button type="submit" class="btn btn-primary">Timeslot erstellen</button>
                </form>
            </div>
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
 
	</script>
@endsection
