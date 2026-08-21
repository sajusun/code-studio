<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountSettingsController extends Controller
{
    public function index(): View
    {
        $setting = Setting::query()->where('key', 'site')->first();
        $siteSettings = $setting?->value ?? $this->defaultSiteSettings();

        return view('admin.settings.index', compact('siteSettings'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Account password updated successfully!');
    }

    public function updateSite(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'title' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:300'],
            'support_email' => ['nullable', 'email', 'max:120'],
            'support_phone' => ['nullable', 'string', 'max:60'],
            'newsletter_enabled' => ['nullable', 'boolean'],
        ]);

        $validated['newsletter_enabled'] = $request->has('newsletter_enabled');

        $setting = Setting::query()->firstOrNew(['key' => 'site']);
        $currentValue = $setting->value ?? $this->defaultSiteSettings();
        
        $setting->value = array_merge($currentValue, $validated);
        $setting->updated_by = auth()->id();
        $setting->save();

        return redirect()->route('admin.settings.index')->with('success', 'System settings saved successfully!');
    }

    private function defaultSiteSettings(): array
    {
        return [
            'name' => config('app.name', 'Master Admin'),
            'title' => 'Master Dashboard Control Panel',
            'description' => 'Headless Microservice Enterprise Admin Dashboard',
            'support_email' => 'support@admin.com',
            'support_phone' => '+1 (800) 123-4567',
            'newsletter_enabled' => false,
        ];
    }
}
