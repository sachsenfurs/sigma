<?php

namespace App\Http\Controllers\User;

use \Gate;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index() {
        Gate::authorize('manage_users');

        return view("users.index")->with("users", User::all());
    }

    public function store(Request $request) {
        Gate::authorize('manage_users');

        $validated = $request->validate([
            'name' => "required|string",
            'email' => "email|unique:users,email|nullable",
        ]);
        if(User::create($validated))
            return back()->withSuccess("User created");

    }

    public function edit(User $user) {
        $roles = UserRole::all();

        if($user->id == auth()->user()->id) {
            return back()->withErrors("You can't edit your own account!");
        }

        return view("users.edit", compact([
            'user',
            'roles',
        ]));
    }

    public function update(Request $request, User $user) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'email|string|nullable',
            'reg_id' => [
                'int',
                'nullable',
                Rule::unique('users')->ignore($user->id),
            ],
            'user_role_id' => 'required|exists:' . UserRole::class . ',id',
        ]);

        $user->role()->associate($validated['user_role_id']);
        $user->update($validated);

        return redirect(route("users.index"))->withSuccess("Änderungen gespeichert!");

    }

    public function destroy(Request $request, User $user) {
        Gate::authorize('manage_users');

        $request->validate([
            'user' => "exists:" . User::class.",id",
        ]);
        if($user->id == auth()->user()->id)
            return back()->withErrors("Can't delete your own account");
        if($user->delete())
            return back()->withSuccess("Benutzer gelöscht!");

    }
}
