@extends('layouts.app')
@section('title', "Benutzer bearbeiten | {$user->name}")
@section('content')
<form id="createForm" action="/users/{{ $user->id }}" method="POST" class="col-6 col-md-6 mx-auto">
    @method("PUT")
    <div class="card">
        <div class="card-header text-center">
            <strong>
                Benutzer "{{ $user->name }}" bearbeiten
            </strong>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="col-12 col-md-12">
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">E-Mail</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">RegNr</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="reg_id" id="reg_id" value="{{ old("reg_id") ?? $user->reg_id }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Benutzerrolle</label>
                        <div class="col-sm-8">
                            <select name="user_role_id" class="form-control">
                                <option value="">-</option>
                                @foreach($roles AS $role)
                                    <option value="{{ $role->id }}" {{ $user->user_role_id == $role->id ? "selected" : "" }}>{{ $role->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="card-footer">
            @csrf
            <div class="d-flex flex-row-reverse">
                <a href="{{url()->previous()}}" class="btn btn-secondary m-1">Abbrechen</a>
                <button type="submit" class="btn btn-primary m-1">Benutzer aktualisieren</button>
            </div>
        </div>
    </div>
</form>
@endsection
