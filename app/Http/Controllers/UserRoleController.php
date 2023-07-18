<?php

namespace App\Http\Controllers;

use \Gate;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     */
    public function create()
    {
        Gate::authorize('manage_users');

        return view('user-roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('manage_users');

        $attributes = $request->validate([
            'title'                     => 'required|min:2|max:20',
            'perm_manage_settings'      => '',
            'perm_manage_users'         => '',
            'perm_manage_events'        => '',
            'perm_manage_locations'     => '',
            'perm_manage_hosts'         => ''
        ]);

        if ($request->has('perm_manage_settings')) {
			$attributes['perm_manage_settings'] = true;
		} else {
            $attributes['perm_manage_settings'] = false;
        }

        if ($request->has('perm_manage_users')) {
			$attributes['perm_manage_users'] = true;
		} else {
            $attributes['perm_manage_users'] = false;
        }

        if ($request->has('perm_manage_events')) {
			$attributes['perm_manage_events'] = true;
		} else {
            $attributes['perm_manage_events'] = false;
        }

        if ($request->has('perm_manage_locations')) {
			$attributes['perm_manage_locations'] = true;
		} else {
            $attributes['perm_manage_locations'] = false;
        }

        if ($request->has('perm_manage_hosts')) {
			$attributes['perm_manage_hosts'] = true;
		} else {
            $attributes['perm_manage_hosts'] = false;
        }

        $userRole = UserRole::create($attributes);

        return redirect('/user-roles')->with('success', 'Benutzerrolle erfolgreich erstellt');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function show(UserRole $userRole)
    {
        Gate::authorize('manage_users');

        return view('user-roles.create', compact('userRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRole $userRole)
    {
        Gate::authorize('manage_users');

        return view('user-roles.edit', compact('userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRole $userRole)
    {
        Gate::authorize('manage_users');

        if($userRole->id == 1) {
            return redirect('/user-roles')->with('error', 'Benutzerrolle "Administrator" kann nicht bearbeitet werden!');
        }

        $attributes = $request->validate([
            'title'                     => 'required|min:2|max:20',
            'perm_manage_settings'      => '',
            'perm_manage_users'         => '',
            'perm_manage_events'        => '',
            'perm_manage_locations'     => '',
            'perm_manage_hosts'         => ''
        ]);

        if ($request->has('perm_manage_settings')) {
			$attributes['perm_manage_settings'] = true;
		} else {
            $attributes['perm_manage_settings'] = false;
        }

        if ($request->has('perm_manage_users')) {
			$attributes['perm_manage_users'] = true;
		} else {
            $attributes['perm_manage_users'] = false;
        }

        if ($request->has('perm_manage_events')) {
			$attributes['perm_manage_events'] = true;
		} else {
            $attributes['perm_manage_events'] = false;
        }

        if ($request->has('perm_manage_locations')) {
			$attributes['perm_manage_locations'] = true;
		} else {
            $attributes['perm_manage_locations'] = false;
        }

        if ($request->has('perm_manage_hosts')) {
			$attributes['perm_manage_hosts'] = true;
		} else {
            $attributes['perm_manage_hosts'] = false;
        }

        $userRole->update($attributes);

        return redirect('/user-roles')->with('success', 'Benutzerrolle erfolgreich aktualisiert');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRole $userRole)
    {
        Gate::authorize('manage_users');

        if($userRole->id == 1) {
            return redirect('/user-roles')->with('error', 'Benutzerrolle "Administrator" kann nicht glöscht werden!');
        }

        $userRole->delete();

        return redirect('/user-roles')->with('success', 'Benutzerrolle erfolgreich gelöscht');
    }
}
