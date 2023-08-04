@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="modal fade" id="createUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="email">
                            </div>
                            <div class="mb-3">
                                <label for="email">E-Mail</label>
                                <input type="text" class="form-control" name="email" id="email">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
            Add User
        </button>

        <div class="card mt-4">
            <div class="card-header">
                <div class="card-title">Users</div>
            </div>
            <div class="card-body">
                <table class="table ">
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>E-Mail / RegNr</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($users AS $user)
                        <tr>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->role->title }}</td>
                            <td class="align-middle">{{ $user->email ?? $user->reg_id }}</td>
                            <td class="align-middle">{{ $user->created_at ?? "unknown" }}</td>
                            <td class="align-middle">
                                <a type="button" class="btn btn-info text-white" href="/users/{{ $user->id }}/edit">
                                    <span class="bi bi-pencil"></span>
                                </a>
                                <button type="button" class="btn btn-danger text-white" onclick="$('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/users/{{ $user->id }}')" data-toggle="modal" data-target="#deleteModal" data-timeslot="{{ $user->id }}">
                                    <span class="bi bi-trash"></span>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">Benutzer löschen?</h5>
            </div>
            <div class="modal-body">
                Benutzer wirklich löschen?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    @method('DELETE')
                    @csrf
                    <a class="btn btn-secondary" onclick="$('#deleteModal').modal('hide');" data-dismiss="modal">Abbrechen</a>
                    <button type="submit" class="btn btn-danger">LÖSCHEN</button>
                </form>
            </div>
          </div>
        </div>
    </div>
@endsection
