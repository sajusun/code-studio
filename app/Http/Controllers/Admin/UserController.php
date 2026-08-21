<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Media\Traits\HandlesMedia;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse, HandlesMedia;

    public function index(Request $request)
    {
        $query = User::with(['roles', 'media']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->input('role'));
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = \Spatie\Permission\Models\Role::all();

        if ($request->wantsJson()) {
            return self::success('Users list', $users);
        }

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'avatar' => 'nullable|image|max:5120',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $user->syncRoles($validated['roles']);

        if ($request->hasFile('avatar')) {
            $this->uploadMedia($user, $request->file('avatar'), 'avatars');
        }

        if ($request->wantsJson()) {
            return self::success('User created successfully', $user, 201);
        }

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'avatar' => 'nullable|image|max:5120',
        ]);

        $user->name = $validated['name'];
        $user->email = strtolower($validated['email']);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $user->syncRoles($validated['roles']);

        if ($request->hasFile('avatar')) {
            $this->updateMedia($user, $request->file('avatar'), 'avatars');
        }

        if ($request->wantsJson()) {
            return self::success('User updated successfully', $user);
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->syncRoles([]);
        $user->delete();

        if (request()->wantsJson()) {
            return self::success('User deleted successfully');
        }

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
