<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function showSite(): array
    {
        $setting = Setting::query()->where('key', 'site')->first();

        return [
            'data' => $setting?->value ?? $this->defaultSiteSettings(),
        ];
    }

    public function updateSite(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'title' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:300'],
            'tagline' => ['nullable', 'string', 'max:120'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'favicon_url' => ['nullable', 'string', 'max:255'],

            'owner_name' => ['nullable', 'string', 'max:120'],
            'owner_email' => ['nullable', 'email', 'max:120'],
            'owner_phone' => ['nullable', 'string', 'max:60'],

            'support_email' => ['nullable', 'email', 'max:120'],
            'support_phone' => ['nullable', 'string', 'max:60'],
            'support_hours' => ['nullable', 'string', 'max:120'],
            'support_url' => ['nullable', 'string', 'max:255'],

            // Newsletter / Promotions
            'newsletter_enabled' => ['nullable', 'boolean'],
            'newsletter_from_name' => ['nullable', 'string', 'max:120'],
            'newsletter_from_email' => ['nullable', 'email', 'max:120'],
            'newsletter_reply_to_email' => ['nullable', 'email', 'max:120'],
            'newsletter_footer_text' => ['nullable', 'string', 'max:300'],
            'newsletter_manage_url' => ['nullable', 'string', 'max:255'],
        ]);

        $setting = Setting::query()->updateOrCreate(
            ['key' => 'site'],
            [
                'value' => array_merge($this->defaultSiteSettings(), $validated),
                'updated_by' => $request->user()?->id,
            ],
        );

        return [
            'data' => $setting->value,
        ];
    }

    private function defaultSiteSettings(): array
    {
        return [
            'name' => 'HMS',
            'title' => 'HMS Admin',
            'description' => null,
            'tagline' => null,
            'seo_keywords' => null,
            'logo_url' => null,
            'favicon_url' => null,

            'owner_name' => null,
            'owner_email' => null,
            'owner_phone' => null,

            'support_email' => null,
            'support_phone' => null,
            'support_hours' => null,
            'support_url' => null,

            'newsletter_enabled' => false,
            'newsletter_from_name' => null,
            'newsletter_from_email' => null,
            'newsletter_reply_to_email' => null,
            'newsletter_footer_text' => null,
            'newsletter_manage_url' => null,
        ];
    }
}
