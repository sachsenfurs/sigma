@extends('layouts.app')
@section('title', "Host bearbeiten - {$host->name}")
@section('content')
    <div class="container" style="margin-bottom: 600px">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" action="{{ route("hosts.update", $host) }}">
                    @csrf
                    @method("PUT")
                    <div class="card">
                        <div class="card-header">
                            <strong>{{ $host->name }}</strong>
                        </div>
                        <div class="card-body">

                            <label>{{ __("Change name") }}:</label>
                            <input type="text" class="form-control" name="name" value="{{ old("name", $host->name) }}">

                            <label>{{ __("Reg Number") }}:</label>
                            <input type="number" class="form-control" name="reg_id" value="{{ old("reg_id", $host->reg_id) }}">

                            <div class="mt-3">
                                <label>{{ __("Description") }}:</label>
                                <textarea class="form-control" name="description">{{ old("description", $host->description) }}</textarea>

                            </div>
                            <div class="mt-3">
                                <div class="form-check">
                                    <label>
                                        <input class="form-check-input" type="checkbox" name="hide" {{ $host->hide ? "checked" : "" }}>
                                        {{ __("Hide name on schedule") }}
                                    </label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <input type="submit" class="btn btn-success" value="{{ __("Save") }}">

                            </div>
                        </div>
                    </div>
                </form>

                <div class="accordion mt-4" id="options">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                {{ __("Delete Host") }}
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#options">
                            <div class="accordion-body text-center">
                                <form method="POST" action="{{ route("hosts.destroy", $host) }}">
                                    @csrf
                                    @method("DELETE")
                                    <input type="submit" class="btn btn-danger" name="delete" value="{{ __("Really delete it?") }}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
