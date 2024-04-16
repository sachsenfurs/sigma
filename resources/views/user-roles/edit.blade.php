@extends('layouts.app')
@section('title', "Benutzerrolle bearbeiten | {$userRole->title}")
@section('content')
<form id="createForm" action="{{ route("user-roles.update", $userRole) }}" method="POST" class="col-6 col-md-6 mx-auto">
    @method("PUT")
    <div class="card">
        <div class="card-header text-center">
            <strong>
                {{ __("Edit Role :role", ['role' => $userRole->title ]) }}
            </strong>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="col-12 col-md-12">
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">{{ __("Title") }}</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="title" id="title" value="{{ $userRole->title }}">
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Manage Settings</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_settings" @checked($userRole->perm_manage_settings)>
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">{{ __("Manage Users") }}</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_users" @checked($userRole->perm_manage_users)>
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">{{ __("Manage Event Schedule") }}</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_events" @checked($userRole->perm_manage_events)>
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Manage Locations</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_locations" @checked($userRole->perm_manage_locations)>
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Manage Hosts</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_hosts" @checked($userRole->perm_manage_hosts)>
                        </div>
                    </div>
                    <div class="form-group row m-1">
                        <label for="" class="col-sm-4 col-form-label text-end">Manage Posts</label>
                        <div class="col-sm-8">
                            <input type="checkbox" class="form-check-input" name="perms[]" value="perm_post" @checked($userRole->perm_post)>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="card-footer">
            @csrf
            <div class="d-flex flex-row-reverse">
                <a href="{{url()->previous()}}" class="btn btn-secondary m-1">{{ __("Cancel") }}</a>
                <button type="submit" class="btn btn-primary m-1">{{ __("Update") }}</button>
            </div>
        </div>
    </div>
</form>
@endsection
