<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return view("users.manage")->with("users", User::all());
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => "required|string",
            'email' => "required|email|unique:users,email",
        ]);
        if(User::create($validated))
            return back()->withSuccess("User created");

    }

    public function destroy(Request $request, User $user) {
        $request->validate([
            'user' => "exists:" . User::class.",id",
        ]);
        if($user->id == auth()->user()->id)
            return back()->withErrors("Can't delete your own account");
        if($user->delete())
            return back()->withSuccess("User deleted!");

    }
}
