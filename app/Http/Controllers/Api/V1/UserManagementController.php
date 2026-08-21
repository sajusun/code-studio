<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class UserManagementController extends Controller
{
    /**
     * List all users with their roles and permissions.
     */
    public function index(Request $request)
    {
        try {
            $query = User::with(['roles', 'permissions']);
            // optional pagination
            $perPage = $request->input('per_page', 25);
            $users = $query->paginate($perPage);
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('UserManagementController index error: '.$e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to retrieve users'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update roles for a user (sync).
     */
    public function updateRoles(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name',
        ]);
        try {
            $user = User::findOrFail($id);
            $user->syncRoles($request->input('roles'));
            return response()->json(['message' => 'Roles updated']);
        } catch (\Exception $e) {
            Log::error('UserManagementController updateRoles error: '.$e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to update roles'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update direct permissions for a user (sync).
     */
    public function updatePermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);
        try {
            $user = User::findOrFail($id);
            $user->syncPermissions($request->input('permissions'));
            return response()->json(['message' => 'Permissions updated']);
        } catch (\Exception $e) {
            Log::error('UserManagementController updatePermissions error: '.$e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to update permissions'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all roles.
     */
    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    /**
     * Get all permissions.
     */
    public function getPermissions()
    {
        $perms = Permission::all();
        return response()->json($perms);
    }

    /**
     * Store a new user with roles.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name',
        ]);
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);
            $user->syncRoles($request->input('roles'));
            return response()->json(['message' => 'User created', 'user' => $user]);
        } catch (\Exception $e) {
            Log::error('UserManagementController store error: '.$e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to create user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update user details and roles.
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$id,
            'roles' => 'sometimes|array',
            'roles.*' => 'string|exists:roles,name',
        ]);
        try {
            $user = User::findOrFail($id);
            if ($request->has('name')) $user->name = $request->input('name');
            if ($request->has('email')) $user->email = $request->input('email');
            $user->save();
            if ($request->hasFile('image')) {
                $this->updateMedia($user, $request->file('image'), 'avatars');
            }
            if ($request->has('roles')) {
                $user->syncRoles($request->input('roles'));
            }
            return response()->json(['message' => 'User updated', 'user' => $user]);
        } catch (\Exception $e) {
            Log::error('UserManagementController updateUser error: '.$e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to update user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->syncRoles([]);
            $user->delete();
            return response()->json(['message' => 'User deleted']);
        } catch (\Exception $e) {
            Log::error('UserManagementController destroy error: '.$e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to delete user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
