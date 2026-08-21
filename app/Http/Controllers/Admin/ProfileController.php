<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = auth()->user()->load('profile');

        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ]);

        // Update User Core Attributes
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Handle Avatar File Upload
        $avatarUrl = $user->profile?->avatar_url;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $avatarUrl = Storage::url($path);
        }

        // Update or Create Profile Relation
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'country' => $validated['country'] ?? null,
                'zip_code' => $validated['zip_code'] ?? null,
                'avatar_url' => $avatarUrl,
            ]
        );

        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully!');
    }
}
