<x-layouts.admin>
    <div class="max-w-5xl mx-auto space-y-6" x-data="{ activeTab: 'password' }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-theme-main tracking-tight">Account & System Settings</h1>
                <p class="text-xs text-theme-muted mt-0.5">Manage your account security password and platform environment settings</p>
            </div>
        </div>

        <!-- Success Notification -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Tab Navigation Buttons -->
        <div class="flex items-center gap-2 border-b border-theme pb-2 overflow-x-auto whitespace-nowrap">
            <button @click="activeTab = 'password'" 
                    :class="activeTab === 'password' ? 'bg-theme-primary text-white shadow-md' : 'text-theme-muted hover:text-theme-main hover:bg-theme-main/10'"
                    class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Change Password & Security</span>
            </button>
            <button @click="activeTab = 'site'" 
                    :class="activeTab === 'site' ? 'bg-theme-primary text-white shadow-md' : 'text-theme-muted hover:text-theme-main hover:bg-theme-main/10'"
                    class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Site & System Settings</span>
            </button>
        </div>

        <!-- Tab 1: Password & Security Form -->
        <div x-show="activeTab === 'password'" class="space-y-6">
            <x-card title="Update Password">
                <form action="{{ route('admin.settings.password') }}" method="POST" class="space-y-5 max-w-xl">
                    @csrf
                    @method('PUT')

                    <x-form.password 
                        name="current_password" 
                        label="Current Password" 
                        placeholder="••••••••" 
                        required 
                    />

                    <x-form.password 
                        name="password" 
                        label="New Password" 
                        placeholder="Minimum 8 characters" 
                        required 
                    />

                    <x-form.password 
                        name="password_confirmation" 
                        label="Confirm New Password" 
                        placeholder="Re-enter new password" 
                        required 
                    />

                    <div class="pt-3">
                        <x-form.submit label="Update Password" />
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Tab 2: Site & System Settings -->
        <div x-show="activeTab === 'site'" class="space-y-6" x-cloak>
            <x-card title="System & Support Configuration">
                <form action="{{ route('admin.settings.site') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form.text 
                            name="name" 
                            label="Application Name" 
                            :value="old('name', $siteSettings['name'] ?? '')" 
                            required 
                        />

                        <x-form.text 
                            name="title" 
                            label="Control Panel Title" 
                            :value="old('title', $siteSettings['title'] ?? '')" 
                        />
                    </div>

                    <x-form.textarea 
                        name="description" 
                        label="Site Description" 
                        :value="old('description', $siteSettings['description'] ?? '')" 
                        rows="2" 
                    />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-theme">
                        <x-form.email 
                            name="support_email" 
                            label="Support Email" 
                            :value="old('support_email', $siteSettings['support_email'] ?? '')" 
                        />

                        <x-form.text 
                            name="support_phone" 
                            label="Support Phone" 
                            :value="old('support_phone', $siteSettings['support_phone'] ?? '')" 
                        />
                    </div>

                    <div class="pt-4 border-t border-theme">
                        <x-form.toggle 
                            name="newsletter_enabled" 
                            label="Enable System Notifications & Broadcasts" 
                            :checked="!empty($siteSettings['newsletter_enabled'])" 
                        />
                    </div>

                    <div class="flex justify-end pt-4 border-t border-theme">
                        <x-form.submit label="Save System Settings" />
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-layouts.admin>
