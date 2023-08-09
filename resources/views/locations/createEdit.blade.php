@extends('layouts.app')
@section('title', "Location " . (isset($location) ? "Bearbeiten" : "Anlegen"))
@section('content')
    <div class="container" style="margin-bottom: 600px">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" action="{{ isset($location) ? route("locations.update", $location) : route("locations.store") }}">
                    @csrf
                    @isset($location)
                        @method("PUT")
                    @endisset
                    <div class="card">
                        <div class="card-header">
                            <strong>{{ $location->name ?? __("Add Location") }}</strong>
                        </div>
                        <div class="card-body">

                            <label>{{ __("Name") }}:</label>
                            <input type="text" class="form-control" name="name" value="{{ old("name", $location->name ?? "") }}">

                            <div class="mt-3">
                                <label>{{ __("Description") }}:</label>
                                <input type="text" class="form-control" name="description" value="{{ old("description", $location->description ?? "") }}">
                            </div>

                            <div class="mt-3">
                                <label>Render IDs:</label>
                                <input type="text" class="form-control" name="render_ids" value="{{ old("render_ids", implode(", ", $location->render_ids ?? [])) }}">
                            </div>

                            <div class="mt-4">
                                <input type="submit" class="btn btn-success" value="{{ __("Save") }}">

                            </div>
                        </div>
                    </div>
                </form>
                @isset($location)
                    <div class="accordion mt-4" id="options">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    {{ __("Delete Location") }}
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#options">
                                <div class="accordion-body text-center">
                                    <form method="POST" action="{{ route("locations.destroy", $location) }}">
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
@endsection
