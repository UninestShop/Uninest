<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::withCount('users')->get();
            return view('admin.roles.index', compact('roles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load roles: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $permissions = Permission::all();
            return view('admin.roles.create', compact('permissions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load create role page: ' . $e->getMessage());
        }
    }

    public function store(RoleRequest $request)
    {
        try {
            $role = Role::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }
            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create role: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Role $role)
    {
        try {
            $permissions = Permission::all();
            return view('admin.roles.edit', compact('role', 'permissions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load edit role page: ' . $e->getMessage());
        }
    }

    public function update(RoleRequest $request, Role $role)
    {
        try {
            $role->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);
            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update role: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }

    public function updatePermissions(Request $request, Role $role)
    {
        try {
            $role->permissions()->sync($request->permissions);
            return redirect()->back()->with('success', 'Permissions updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update permissions: ' . $e->getMessage());
        }
    }
}
