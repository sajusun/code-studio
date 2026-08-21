<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $roles = Role::with(['permissions'])->withCount('users')->get();
        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('.', $perm->name)[0] ?? 'general';
        });

        if ($request->wantsJson()) {
            return self::success('Roles list', compact('roles', 'permissions'));
        }

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => strtolower($validated['name']),
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions']);

        if ($request->wantsJson()) {
            return self::success('Role created successfully', $role, 201);
        }

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($role->name === 'admin' && $validated['name'] !== 'admin') {
            return redirect()->back()->with('error', 'Cannot rename super-admin role.');
        }

        $role->name = strtolower($validated['name']);
        $role->save();

        $role->syncPermissions($validated['permissions']);

        if ($request->wantsJson()) {
            return self::success('Role updated successfully', $role);
        }

        return redirect()->back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete super-admin role.');
        }

        $role->delete();

        if (request()->wantsJson()) {
            return self::success('Role deleted successfully');
        }

        return redirect()->back()->with('success', 'Role deleted successfully.');
    }
}
