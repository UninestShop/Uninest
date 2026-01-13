<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use DataTables;

class UserPermissionController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $users = User::with('roles');
                return DataTables::of($users)
                    ->addIndexColumn()
                    ->make(true);
            }
            $roles = Role::all();
            return view('admin.users.permissions.index', compact('roles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load user permissions: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        try {
            $roles = Role::all();
            return view('admin.users.permissions.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load edit user permissions: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $user->roles()->sync($request->roles);
            return redirect()->route('admin.users.permissions')->with('success', 'User permissions updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update user permissions: ' . $e->getMessage());
        }
    }
}
