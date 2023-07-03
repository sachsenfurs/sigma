@extends('layouts.app')
@section('title', "SIG " . (isset($sig) ? "Bearbeiten" : "Anlegen" ))
@section('content')
    <div class="container" style="margin-bottom: 600px">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <form method="POST" action="{{ isset($sig) ? route("sigs.update", $sig) : route("sigs.store") }}">
                    @csrf
                    @isset($sig)
                        @method("PUT")
                    @endisset
                    <div class="card">
                        <div class="card-header">
                            SIG {{ isset($sig) ? "Bearbeiten" : "Anlegen" }}
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label for="name" class="form-label">SIG Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old("name", $sig->name ?? "") }}" autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">SIG Name Englisch</label>
                                <input type="text" class="form-control" id="name" name="name_en" value="{{ old("name_en", $sig->name_en ?? "") }}" autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="host" class="form-label">SIG Host</label>
                                <input type="text" class="form-control" id="host" name="host" value="{{ old("host", $sig->sigHost->name ?? "") }}">
                            </div>

                            <label>Sprache</label>
                            <div class="form-check">
                                <label>
                                    <input class="form-check-input" type="checkbox" name="lang_de" {{ !empty(old("lang_de", in_array("de", $sig->languages ?? []))) ? "checked" : "" }}> Deutsch
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input class="form-check-input" type="checkbox" name="lang_en" {{ !empty(old("lang_en", in_array("en", $sig->languages ?? []))) ? "checked" : "" }}> Englisch
                                </label>
                            </div>

                            <div class="mt-3">Beschreibung Deutsch</div>
                            <textarea class="form-control" name="description" style="min-height: 180px">{{ old("description", $sig->description ?? "") }}</textarea>
                            <div class="mt-3">Beschreibung Englisch</div>
                            <textarea class="form-control" name="description_en" style="min-height: 180px">{{ old("description_en", $sig->description_en ?? "") }}</textarea>


                            <div class="mt-3">
                                <label>Location</label>
                                <select name="location" class="form-control">
                                    @foreach($locations AS $location)
                                        <option value="{{ $location->id }}" {{ old("location", $sig->sigLocation->id ?? null) == $location->id ? "selected" : "" }}>
                                            {{ $location->name . ($location->description ? " - " . $location->description : "")}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label>Timeslots für Teilnahme</label>
								<div class="form-check">
									<label>
										<input class="form-check-input" type="checkbox" name="reg_possible" @isset($sig)
                                            @if ($sig->reg_possible) checked @endif @endisset>Registrierungen für dieses Event erlauben 
									</label>
								</div>
                            </div>

                            @if(!isset($sig))
                                <hr>
                                <div class="mt-3 row">
                                    <label>Zeit festlegen</label>
                                    <span class="small">(Optional, kann auch später erfolgen)</span>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-5"><strong>Start</strong></div>
                                    <div class="col-5"><strong>Ende</strong></div>
                                </div>
                                <div id="timetableEntries-parent" style="display: none">
                                    <div class="mt-1 row timetableEntry" id="timetableEntry">
                                        <div class="col-5">
                                            <input type="datetime-local" class="form-control" data-name="date-start[]" name="tester" value="{{ \Illuminate\Support\Carbon::now()->setMinutes(0)->format("Y-m-d\TH:i") }}">
                                        </div>
                                        <div class="col-5">
                                            <input type="datetime-local" class="form-control" data-name="date-end[]" name="tester2" value="{{ \Illuminate\Support\Carbon::now()->setMinutes(0)->addMinutes(60)->format("Y-m-d\TH:i") }}">
                                        </div>
                                        <div class="col-2 row">
                                            <button type="button" class="btn btn-danger text-white" onclick="if($('.timetableEntry').length > 1) $(this).parent().parent().remove()">
                                                <span class="bi bi-trash"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="timetableEntries">
                                    @if(old("date-start") AND is_array(old("date-start")))
                                        @foreach(old("date-start") AS $dateStart)
                                            <div class="mt-1 row timetableEntry" id="timetableEntry">
                                                <div class="col-5">
                                                    <input type="datetime-local" class="form-control" name="date-start[]" value="{{ $dateStart }}">
                                                </div>
                                                <div class="col-5">
                                                    <input type="datetime-local" class="form-control" name="date-end[]" value="{{ old("date-end")[$loop->index] }}">
                                                </div>
                                                <div class="col-2 row">
                                                    <button type="button" class="btn btn-danger text-white" onclick="if($('.timetableEntry').length > 1) $(this).parent().parent().remove()">
                                                        <span class="bi bi-trash"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <button type="button" class="btn btn-secondary" id="addTimetableEntry"><i class="bi bi-plus"></i></button>
                                </div>

                                <div class="mt-3">
                                    <div class="form-check">
                                        <label>
                                            <input class="form-check-input" type="checkbox" name="hide" {{ !empty(old("hide")) ? "checked" : "" }}>
                                            Nicht auf Programmplan zeigen (Internes Event)
                                        </label>
                                    </div>
                                </div>
                            @else
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><strong>Tag</strong></th>
                                            <th><strong>Zeitraum</strong></th>
                                            <th><strong>Timeslots</strong></th>
                                            <th><strong>Aktionen</strong></th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach($sig->timetableEntries AS $timetableEntry)
                                            <tr id="{{ $timetableEntry->id }}">
                                                <td>
                                                    {{ date('d.m.Y', strtotime($timetableEntry->start)) }} 
                                                    @if (date('d.m.Y', strtotime($timetableEntry->start)) != date('d.m.Y', strtotime($timetableEntry->end)))
                                                    - {{ date('d.m.Y', strtotime($timetableEntry->end)) }}
                                                    @endif 
                                                </td>
                                                <td >
                                                    {{ date('H:i', strtotime($timetableEntry->start)) }} - {{ date('H:i', strtotime($timetableEntry->end)) }}
                                                </td>
                                                <td>
                                                    {{ $timetableEntry->sigTimeslots->count() }}
                                                </td>
                                                <td>
                                                    <a type="button" class="btn btn-info text-white" href="/timetable/{{ $timetableEntry->id }}/edit">
                                                        <span class="bi bi-pencil"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <div class="mt-3">
                                <button type="button" class="btn btn-secondary text-white" onclick="$('#createModal').modal('show');" data-toggle="modal" data-target="#createModal">
                                    <i class="bi bi-plus"></i>
                                </button>
							</div>
                            <div class="mt-3">
                                <input type="submit" value="Speichern" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </form>
                @isset($sig)
                    <div class="accordion mt-4" id="options">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    SIG löschen
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#options">
                                <div class="accordion-body text-center">
                                    <form method="POST" action="{{ route("sigs.destroy", $sig) }}">
                                        @csrf
                                        @method("DELETE")
                                        <input type="submit" class="btn btn-danger" name="delete" value="Wirklich löschen?">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>
    <!-- Modals -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form id="createForm" action="/timetable" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Neuen Kalendereintrag erstellen</h5>
                </div>
                <div class="modal-body">
                    <input type="number" class="d-none" name="sig_event_id" id="sig_host_id" value="{{ $sig->id }}">
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Start</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" required="true" name="start" id="start" value="">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Ende</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" required="true" name="end" id="end" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <a class="btn btn-secondary" onclick="$('#createModal').modal('hide');" data-dismiss="modal">Abbrechen</a>
                    <button type="submit" class="btn btn-primary">Kalendereintrag erstellen</button>
                </div>
            </form>
          </div>
        </div>
    </div>
    <!-- Modals End -->
    <script>
        $(document).ready(function(){
            var availableTags = @json($hosts);
            $( "#host" ).autocomplete({
                source: availableTags,
                minLength: 0,
                delay: 0,
            });

            $('#addTimetableEntry').click(function() {
                $('#timetableEntries').append($('#timetableEntry').parent().html());
                $('#timetableEntries').parent().find('input[type="datetime-local"]').each(function(i,e) {
                    if(i > 1)
                        $(e).attr('name', $(e).data("name")).show();
                });
            });
        });
    </script>
@endsection
