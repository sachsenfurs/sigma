@extends('layouts.app')
@section('title', "User Roles Übersicht")
@section('content')
    <div class="container">
        <div class="mt-4 mb-4 text-center">
            <button type="button" class="btn btn-primary text-white" onclick="$('#createModal').modal('show');" data-toggle="modal" data-target="#createModal">
                <i class="bi bi-plus"></i>
                {{ __("Create Role") }}
            </button>
        </div>
        <div class="col-12 col-md-12 text-center">
            <div class="d-none d-xl-block">
                <div class="row border-bottom border-secondary mb-2">
                    <div class="col-4 col-md-4">
                        <strong>{{ __("Title") }}</strong>
                    </div>
                    <div class="col-1 col-md-1">
                        <strong>Manage Settings</strong>
                    </div>
                    <div class="col-1 col-md-1">
                        <strong>Manage Users</strong>
                    </div>
                    <div class="col-1 col-md-1">
                        <strong>Manage Events</strong>
                    </div>
                    <div class="col-1 col-md-1">
                        <strong>Manage Locations</strong>
                    </div>
                    <div class="col-1 col-md-1">
                        <strong>Manage Hosts</strong>
                    </div>
                    <div class="col-1 col-md-1">
                        <strong>Manage Posts</strong>
                    </div>
                    <div class="col-3 col-md-3">
                        <strong>{{ __("Actions") }}</strong>
                    </div>
                </div>
            </div>
            @foreach ($roles as $role)
            <div class="row mb-2" style="min-height: 50px;">
                <div class="col-12 col-md-4 mt-1 mb-1">
                    <div class="row">
                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                            <strong>{{ __("Title") }}</strong>
                        </div>
                        <div class="col-6 col-md-12">
                            <strong>{{ $role->title }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-1 mt-1 mb-1">
                    <div class="row">
                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                            <strong>Manage Settings</strong>
                        </div>
                        <div class="col-6 col-md-12">
                            @if ($role->perm_manage_settings)
                                <i class="bi bi-check-square-fill text-success"></i>
                            @else
                            <i class="bi bi-x-square-fill text-danger"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-1 mt-1 mb-1">
                    <div class="row">
                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                            <strong>Manage Users</strong>
                        </div>
                        <div class="col-6 col-md-12">
                            @if ($role->perm_manage_users)
                                <i class="bi bi-check-square-fill text-success"></i>
                            @else
                            <i class="bi bi-x-square-fill text-danger"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-1 mt-1 mb-1">
                    <div class="row">
                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                            <strong>Manage Events</strong>
                        </div>
                        <div class="col-6 col-md-12">
                            @if ($role->perm_manage_events)
                                <i class="bi bi-check-square-fill text-success"></i>
                            @else
                            <i class="bi bi-x-square-fill text-danger"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-1 mt-1 mb-1">
                    <div class="row">
                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                            <strong>Manage Locations</strong>
                        </div>
                        <div class="col-6 col-md-12">
                            @if ($role->perm_manage_locations)
                                <i class="bi bi-check-square-fill text-success"></i>
                            @else
                            <i class="bi bi-x-square-fill text-danger"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-1 mt-1 mb-1">
                    <div class="row">
                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                            <strong>Manage Hosts</strong>
                        </div>
                        <div class="col-6 col-md-12">
                            @if ($role->perm_manage_hosts)
                                <i class="bi bi-check-square-fill text-success"></i>
                            @else
                            <i class="bi bi-x-square-fill text-danger"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-1 mt-1 mb-1">
                    <div class="row">
                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                            <strong>Manage Hosts</strong>
                        </div>
                        <div class="col-6 col-md-12">
                            @if ($role->perm_post)
                                <i class="bi bi-check-square-fill text-success"></i>
                            @else
                            <i class="bi bi-x-square-fill text-danger"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-1 col-md-2 mt-1 mb-1 p-0">
                    <div class="row">
                        @if ($role->id != 1)
                            <div class="col-6 col-md-6 d-block d-sm-none align-items-center">
                                <strong>{{ __("Actions") }}</strong>
                            </div>
                            <div class="col-6 col-md-12">
                                <a type="button" class="btn btn-info text-white" href="{{ route("user-roles.edit", $role) }}">
                                    <span class="bi bi-pencil"></span>
                                </a>
                                <button type="button" class="btn btn-danger text-white" onclick="$('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/user-roles/{{ $role->id }}')" data-toggle="modal" data-target="#deleteModal" data-timeslot="{{ $role->id }}">
                                    <span class="bi bi-trash"></span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">{{ __("Delete User Role?") }}</h5>
            </div>
            <div class="modal-body">
                {{ __("Really delete it?") }}
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    @method('DELETE')
                    @csrf
                    <a class="btn btn-secondary" onclick="$('#deleteModal').modal('hide');" data-dismiss="modal">{{ __("Cancel") }}</a>
                    <button type="submit" class="btn btn-danger">{{ __("Delete") }}</button>
                </form>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="createForm" action="/user-roles" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">{{ __("Create Role") }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row m-1">
                            <label for="" class="col-sm-4 col-form-label text-end">{{ __("Title") }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="title" id="title" value="">
                            </div>
                        </div>
                        <div class="form-group row m-1">
                            <label for="" class="col-sm-4 col-form-label text-end">Manage Settings</label>
                            <div class="col-sm-8">
                                <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_settings">
                            </div>
                        </div>
                        <div class="form-group row m-1">
                            <label for="" class="col-sm-4 col-form-label text-end">Manage Users</label>
                            <div class="col-sm-8">
                                <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_users">
                            </div>
                        </div>
                        <div class="form-group row m-1">
                            <label for="" class="col-sm-4 col-form-label text-end">Manage Events</label>
                            <div class="col-sm-8">
                                <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_events">
                            </div>
                        </div>
                        <div class="form-group row m-1">
                            <label for="" class="col-sm-4 col-form-label text-end">Manage Locations</label>
                            <div class="col-sm-8">
                                <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_locations">
                            </div>
                        </div>
                        <div class="form-group row m-1">
                            <label for="" class="col-sm-4 col-form-label text-end">Manage Hosts</label>
                            <div class="col-sm-8">
                                <input type="checkbox" class="form-check-input" name="perms[]" value="perm_manage_hosts">
                            </div>
                        </div>
                        <div class="form-group row m-1">
                            <label for="" class="col-sm-4 col-form-label text-end">Manage Posts</label>
                            <div class="col-sm-8">
                                <input type="checkbox" class="form-check-input" name="perms[]" value="perm_post">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <a class="btn btn-secondary" onclick="$('#createModal').modal('hide');" data-dismiss="modal">{{ __("Cancel") }}</a>
                        <button type="submit" class="btn btn-primary">{{ __("Create Role") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
