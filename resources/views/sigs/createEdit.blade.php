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
                            {{ isset($sig) ? __("Edit SIG") : __("Create SIG") }}
                        </div>
                        <div class="card-body">
                            <div class="col-12 p-2">
                                <div class="row">
                                    <div class="col-8 col-md-8 p-2">
                                        <h2>Sig Name</h2>
                                        <div class="form-group row m-1">
                                            <label for="name" class="col-sm-3 col-form-label text-end">
                                                {{ __("German") }}
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="name"
                                                       id="name" value="{{ old("name", $sig->name ?? "") }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row m-1">
                                            <label for="name_en" class="col-sm-3 col-form-label text-end">{{ __("English") }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="name_en"
                                                       id="name_en" value="{{ old("name_en", $sig->name_en ?? "") }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-4 p-2 rounded-top">
                                        <h2>{{ __("Languages") }}</h2>
                                        <div class="form-group row m-1">
                                            <label>
                                                <input class="form-check-input" type="checkbox"
                                                       name="lang_de" {{ !empty(old("lang_de", in_array("de", $sig->languages ?? []))) ? "checked" : "" }}>
                                                {{ __("German") }}
                                            </label>
                                        </div>
                                        <div class="form-group row m-1">
                                            <label>
                                                <input class="form-check-input" type="checkbox"
                                                       name="lang_en" {{ !empty(old("lang_en", in_array("en", $sig->languages ?? []))) ? "checked" : "" }}>
                                                {{ __("English") }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8 col-md-8 p-2">
                                        <h2>Sig Host</h2>
                                        {{-- <!--<input type="hidden" name="host_id" id="host_id" value="{{ old("host_id", $sig->sigHost->id ?? "") }}">-->--}}
                                        <div class="form-group row m-1">
                                            <label for="location" class="col-sm-3 col-form-label text-end">
                                                {{ __("Host") }}
                                            </label>
                                            <div class="col-sm-9">
                                                <select name="host_id" id="host_id" class="form-control">
                                                    <option value="NEW">-- {{ __("Create New Host") }} --</option>
                                                    @foreach($hosts AS $host)
                                                        <option value="{{ $host->id }}" {{ old("host_id", $sig->sigHost->id ?? null) == $host->id ? "selected" : "" }}>
                                                            {{ $host->name }}@if ($host->reg_id != '') - {{ $host->reg_id }}@endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if (!isset($sig))
                                            <div id="host_row" class="form-group row m-1">
                                                <label for="host" class="col-sm-3 col-form-label text-end">
                                                    {{ __("Name") }}
                                                </label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="host"
                                                           id="host" value="{{ old("host", $sig->sigHost->name ?? "") }}" required>
                                                </div>
                                            </div>
                                            <div id="reg_id_row" class="form-group row m-1">
                                                <label for="reg_id" class="col-sm-3 col-form-label text-end">
                                                    {{ __("Reg Number") }}
                                                </label>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control" name="reg_id"
                                                           id="reg_id" value="{{ old("host", $sig->sigHost->reg_id ?? "") }}">
                                                </div>
                                            </div>
                                        @else
                                            <div id="host_row" class="form-group row m-1" @if ($sig->sigHost->id != null) style="display: none;" @endif>
                                                <label for="host" class="col-sm-3 col-form-label text-end">
                                                    {{ __("Name") }}
                                                </label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="host" id="host"
                                                           value="{{ old("host", $sig->sigHost->name ?? "") }}" required>
                                                </div>
                                            </div>
                                            <div id="reg_id_row" class="form-group row m-1" @if ($sig->sigHost->id != null) style="display: none;" @endif>
                                                <label for="reg_id" class="col-sm-3 col-form-label text-end">
                                                    {{ __("Reg Number") }}
                                                </label>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control" name="reg_id"
                                                           id="reg_id" value="{{ old("host", $sig->sigHost->reg_id ?? "") }}">
                                                </div>
                                            </div>
                                        @endif

                                        <h2 class="mt-3">{{ __("SIG Location") }}</h2>
                                        <div class="form-group row m-1">
                                            <label for="location" class="col-sm-3 col-form-label text-end">Ort</label>
                                            <div class="col-sm-9">
                                                <select name="location" class="form-control">
                                                    @foreach($locations AS $location)
                                                        <option value="{{ $location->id }}" {{ old("location", $sig->sigLocation->id ?? null) == $location->id ? "selected" : "" }}>
                                                            {{ $location->name . ($location->description ? " - " . $location->description : "")}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <h2 class="mt-3">{{ __("Registration") }}</h2>
                                        <div class="form-group row m-1">
                                            <div class="form-group row m-1">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                    <label>
                                                        <input class="form-check-input" type="checkbox" name="reg_possible"
                                                            @isset($sig)
                                                                @if ($sig->reg_possible)
                                                                     checked
                                                                @endif
                                                            @endisset>
                                                        {{ __("Allow Registrations for this Event") }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="max_regs_per_day_row" class="form-group row m-1">
                                                <label for="reg_id" class="col-sm-3 col-form-label text-end">{{ __("Registrations per day") }}</label>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control" name="max_regs_per_day"
                                                           id="max_regs_per_day" value="{{ old("max_regs_per_day", $sig->max_regs_per_day ?? "1") }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 p-2">
                                        <h2 class="mb-2">{{ __("Description") }}</h2>
                                        <div class="row">
                                            <div class="col-2 col-md-2 text-end" style="display:flex; align-items: center; justify-content: center;">
                                                <h3 class="align-middle">{{ __("German") }}</h3>
                                            </div>
                                            <div class="col-10 col-md-10">
                                                <textarea class="form-control mb-1" name="description" style="min-height: 180px">{{ old("description", $sig->description ?? "") }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2 col-md-2 text-end" style="display:flex; align-items: center; justify-content: center;">
                                                <h3 class="align-middle">{{ __("English") }}</h3>
                                            </div>
                                            <div class="col-10 col-md-10">
                                                <textarea class="form-control mb-1" name="description_en" style="min-height: 180px;">{{ old("description_en", $sig->description_en ?? "") }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h3>Tags</h3>
                                        @foreach(\App\Models\SigTag::all() AS $tag)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="tag_{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}"
                                                    @isset($sig)
                                                        @checked($sig->sigTags->find($tag->id))
                                                    @endisset
                                                >
                                                <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->description }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 p-2">
                                        @if(!isset($sig))
                                            <div class="mt-3 row">
                                                <h2>{{ __("Define time spans") }}</h2>
                                                <span class="small">({{ __("Optional, can be changed later") }})</span>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-5"><strong>{{ __("Beginning") }}</strong></div>
                                                <div class="col-5"><strong>{{ __("End") }}</strong></div>
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
                                        @else
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th><strong>{{ __("Day") }}</strong></th>
                                                        <th><strong>{{ __("Time span") }}</strong></th>
                                                        <th><strong>{{ __("Time slots") }}</strong></th>
                                                        <th><strong>{{ __("Actions") }}</strong></th>
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
                                                                <a type="button" class="btn btn-info text-white" href="{{ route("timetable.edit", $timetableEntry) }}">
                                                                    <span class="bi bi-pencil"></span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-secondary text-white" onclick="$('#createModal').modal('show');" data-toggle="modal" data-target="#createModal">
                                                    <i class="bi bi-plus"></i> (Vorher speichern nicht vergessen!)
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            @csrf
                            <div class="d-flex flex-row-reverse">
                                <a href="{{url()->previous()}}" class="btn btn-secondary m-1">{{ __("Cancel") }}</a>
                                <button class="btn btn-primary m-1" type="submit">{{ __("Save") }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                @isset($sig)
                    <div class="accordion mt-4" id="options">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    {{ __("Delete SIG") }}
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#options">
                                <div class="accordion-body text-center">
                                    <form method="POST" action="{{ route("sigs.destroy", $sig) }}">
                                        @csrf
                                        @method("DELETE")
                                        <input type="submit" class="btn btn-danger" name="delete" value="{{ __("Really delete it?") }}">
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
    @if(isset($sig))
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="createForm" action="{{ route("timetable.store") }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">{{ __("Create Schedule Entry") }}</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="sig_event_id" id="sig_event_id" value="{{ $sig->id }}">
                        <div class="form-group row m-1">
                            <label for="" class="col-sm-4 col-form-label text-end">
                                {{ __("Start") }}
                            </label>
                            <div class="col-sm-8">
                                <input type="datetime-local" class="form-control" required="true" name="start" id="start" value="">
                            </div>
                        </div>
                        <div class="form-group row m-1">
                            <label for="" class="col-sm-4 col-form-label text-end">
                                {{ __("End") }}
                            </label>
                            <div class="col-sm-8">
                                <input type="datetime-local" class="form-control" required="true" name="end" id="end" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <a class="btn btn-secondary" onclick="$('#createModal').modal('hide');" data-dismiss="modal">
                            {{ __("Cancel") }}
                        </a>
                        <button type="submit" class="btn btn-primary">{{ __("Create Entry") }}</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    @endif
    <!-- Modals End -->
    <script>
        $(document).ready(function(){
            var availableTags = @json($host_names);
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
    <script>
        document.getElementById('host_id').onchange = function () {
            if(this.value != 'NEW') {
                document.getElementById("reg_id").disabled = true;
                document.getElementById("reg_id_row").style.display="none";
                document.getElementById("host").disabled = true;
                document.getElementById("host_row").style.display="none";
            } else {
                document.getElementById("reg_id").disabled = false;
                document.getElementById("reg_id_row").style.display="flex";
                document.getElementById("host").disabled = false;
                document.getElementById("host_row").style.display="flex";
            }
        }
        document.getElementById('start').onchange = function () {
            var start = $('#start').val();
            start = new Date(start);
            var month = ('0' + (start.getMonth()+1)).slice(-2);
            var year = start.getFullYear();
            var day = ('0'+start.getDate()).slice(-2);

            var hour = start.getHours()+1;
            var min = ('0'+start.getMinutes()).slice(-2);
            var sec = ('0'+start.getMilliseconds()).slice(-2);

            end = year+'-'+month+'-'+day+' '+hour+':'+min+':'+sec;
            $('#end').val(end)
        }
        function addHours(date, hours) {
            date.setHours(date.getHours() + hours);

            return date;
        }
    </script>
@endsection
