@extends('layouts.app')
@section('title', "SIG Anlegen")
@section('content')
    <div class="container  ">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <form method="POST" action="{{ route("sigs.store") }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            SIG Anlegen
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label for="name" class="form-label">SIG Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">SIG Name Englisch</label>
                                <input type="text" class="form-control" id="name" name="name_en" value="{{ old("name_en") }}" autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="host" class="form-label">SIG Host</label>
                                <input type="text" class="form-control" id="host" name="host" value="{{ old("host") }}">
                            </div>

                            <label>Sprache</label>
                            <div class="form-check">
                                <label>
                                    <input class="form-check-input" type="checkbox" name="lang_de" {{ !empty(old("lang_de")) ? "checked" : "" }} value="de"> Deutsch
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input class="form-check-input" type="checkbox" name="lang_en" {{ !empty(old("lang_en")) ? "checked" : "" }} value="en"> Englisch
                                </label>
                            </div>

                            <div class="mt-3">Beschreibung Deutsch</div>
                            <textarea class="form-control" name="description" style="min-height: 180px">{{ old("description") }}</textarea>
                            <div class="mt-3">Beschreibung Englisch</div>
                            <textarea class="form-control" name="description_en" style="min-height: 180px">{{ old("description_en") }}</textarea>


                            <div class="mt-3">
                                <label>Location</label>
                                <select name="location" class="form-control">
                                    @foreach($locations AS $location)
                                        <option value="{{ $location->id }}" {{ old("location") == $location->id ? "selected" : "" }}>
                                            {{ $location->name . ($location->description ? " - " . $location->description : "")}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-3">
                                <input type="submit" value="Speichern" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var availableTags = @json($hosts);
            $( "#host" ).autocomplete({
                source: availableTags,
                minLength: 0,
                delay: 0,
            });
        });
    </script>
@endsection
