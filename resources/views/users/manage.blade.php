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
                        <th>E-Mail</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($users AS $user)
                        <tr>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ date('Y-m-d H:i', $user->created_at) }}</td>
                            <td class="align-middle">
                                <form method="POST" action="{{ route("users.destroy", $user) }}">
                                    @method("DELETE")
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </table>
            </div>
        </div>

    </div>
@endsection
