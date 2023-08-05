<?php

namespace App\Http\Controllers;

use \Gate;
use App\Models\UserRole;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function index()
    {
        Gate::authorize('manage_users');
        $roles = UserRole::all();

        return view('user-roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function create()
    {
        Gate::authorize('manage_users');

        return view('user-roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        Gate::authorize('manage_users');

        $attributes = $request->validate([
            'title'                     => 'required|min:2|max:20',
            'perms'                     => 'array',
        ]);

        $role = new UserRole();
        $role->title = $attributes['title'];
        $role->save();

        $role->setPerms($attributes['perms'] ?? []);

        return back()->with('success', 'Benutzerrolle erfolgreich erstellt');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\UserRole $userRole
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function show(UserRole $userRole)
    {
        Gate::authorize('manage_users');

        return view('user-roles.create', compact('userRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\UserRole $userRole
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function edit(UserRole $userRole)
    {
        Gate::authorize('manage_users');

        return view('user-roles.edit', compact('userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\UserRole $userRole
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function update(Request $request, UserRole $userRole)
    {
        Gate::authorize('manage_users');

        $attributes = $request->validate([
            'title' => 'required|min:2|max:20',
            'perms' => 'array',
        ]);

        $userRole->setPerms($attributes['perms'] ?? []);

        return back()->with('success', 'Benutzerrolle erfolgreich aktualisiert');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\UserRole $userRole
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function destroy(UserRole $userRole)
    {
        Gate::authorize('manage_users');

        $userRole->delete();

        return back()->with('success', 'Benutzerrolle erfolgreich gelöscht');
    }
}
