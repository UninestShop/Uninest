<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        try {
            $permissions = Permission::with('roles')->get();
            return view('admin.permissions.index', compact('permissions'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to fetch permissions. Please try again.'. $e->getMessage());
        }
    }
}
