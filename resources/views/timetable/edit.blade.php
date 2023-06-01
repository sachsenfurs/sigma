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
								<span class="small">(Optional, kann auch später erfolgen)</span>
							</div>
	
							<div class="row mt-3">
								<div class="col-2"><strong>Slot - Start</strong></div>
								<div class="col-2"><strong>Slot - Ende</strong></div>
                                <div class="col-3"><strong>Reg - Start</strong></div>
								<div class="col-3"><strong>Reg - Ende</strong></div>
                                <div class="col-1"><strong>User</strong></div>
							</div>
							<div id="timeslots-parent" style="display: none">
								<div class="mt-1 row timeslot" id="timeslot">
									<div class="col-2">
										<input type="time" class="form-control" data-name="time-start[]" name="tester" value="{{ date('H:i') }}">
									</div>
									<div class="col-2">
										<input type="time" class="form-control" data-name="time-end[]" name="tester2" value="{{ date('H:i') }}">
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
	
							<div id="timeslots">
								@if(old("time-start") AND is_array(old("time-start")))
									@foreach(old("time-start") AS $dateStart)
										<div class="mt-1 row timeslot" id="timeslot">
											<div class="col-2">
												<input type="time" class="form-control" name="time-start[]" value="{{ $timeStart }}">
											</div>
											<div class="col-2">
												<input type="time" class="form-control" name="time-end[]" value="{{ old("time-end")[$loop->index] }}">
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
									@endforeach
								@endif
							</div>
	
							<div class="mt-3">
								<button type="button" class="btn btn-secondary" id="addTimeslot"><i class="bi bi-plus"></i></button>
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
