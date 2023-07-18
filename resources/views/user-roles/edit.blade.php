@extends('layouts.app')
@section('title', "Benutzerrolle bearbeiten | {$userRole->title}")
@section('content')
<form id="createForm" action="/user-roles/{{ $userRole->id }}" method="POST" class="col-6 col-md-6 mx-auto">
    @method("PUT")
    <div class="card">
        <div class="card-header text-center">
            <strong>    
                Benutzerrolle "{{ $userRole->title }}" bearbeiten
            </strong>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="col-12 col-md-12">
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Titel</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="title" id="title" value="{{ $userRole->title }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Manage Settings</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perm_manage_settings" id="perm_manage_settings" @if($userRole->perm_manage_settings) checked @endif>
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Manage Users</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perm_manage_users" id="perm_manage_users" @if($userRole->perm_manage_users) checked @endif>
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Manage Events</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perm_manage_events" id="perm_manage_events" @if($userRole->perm_manage_events) checked @endif>
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Manage Locations</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perm_manage_locations" id="perm_manage_locations" @if($userRole->perm_manage_locations) checked @endif>
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Manage Hosts</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perm_manage_hosts" id="perm_manage_hosts" @if($userRole->perm_manage_hosts) checked @endif>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="card-footer">
            @csrf
            <div class="d-flex flex-row-reverse">
                <a class="btn btn-secondary m-1" onclick="$('#createModal').modal('hide');" data-dismiss="modal">Abbrechen</a>
                <button type="submit" class="btn btn-primary m-1">Benutzerrolle aktualisieren</button>
            </div>
        </div>
    </div>
</form>
@endsection
