<x-layouts.admin>
    <div class="max-w-6xl mx-auto space-y-8 pb-12" x-data="{ activeTab: 'personal' }">
        
        <!-- Top Cover Header & Profile Bar -->
        <div class="relative rounded-3xl bg-theme-card border border-theme shadow-xl overflow-hidden">
            <!-- Decorative Gradient Banner -->
            <div class="h-44 sm:h-52 bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-700 relative overflow-hidden">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute left-1/3 -bottom-10 w-48 h-48 bg-emerald-400/20 rounded-full blur-xl"></div>
            </div>

            <!-- Profile Info Header Content -->
            <div class="px-6 sm:px-8 pb-6 flex flex-col sm:flex-row items-center sm:items-end justify-between gap-6 -mt-16 sm:-mt-20 relative z-10">
                <!-- Avatar & Core Info -->
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-5 text-center sm:text-left">
                    <div class="relative shrink-0">
                        <img src="{{ $user->profile?->avatar_url ?? $user->image }}" 
                             alt="{{ $user->name }}" 
                             class="w-28 h-28 sm:w-32 sm:h-32 rounded-3xl object-cover border-4 border-theme-card shadow-2xl bg-theme-card"
                             style="object-fit: cover;">
                        <span class="absolute bottom-2 right-2 w-5 h-5 bg-emerald-500 border-3 border-theme-card rounded-full shadow-md" title="Online / Active"></span>
                    </div>

                    <div class="space-y-1 mb-1">
                        <div class="flex items-center justify-center sm:justify-start gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-black text-theme-main tracking-tight leading-none">{{ $user->name }}</h1>
                            @if($user->isEmailVerified())
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20" title="Verified Account">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-theme-muted font-medium flex items-center justify-center sm:justify-start gap-2">
                            <span>{{ $user->email }}</span>
                            <span class="w-1 h-1 rounded-full bg-theme-muted/50"></span>
                            <span>Joined {{ $user->created_at->format('M Y') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Quick Badge Actions -->
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-2xl text-xs font-bold bg-theme-primary/10 text-theme-primary border border-theme-primary/20 uppercase tracking-wider shadow-xs">
                        {{ $user->roles->first()?->name ?? 'Administrator' }}
                    </span>
                    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 rounded-2xl text-xs font-bold bg-theme-main/10 text-theme-main hover:bg-theme-main/20 border border-theme transition-all">
                        Security Settings
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Alert Notification -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-sm font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500/60 hover:text-emerald-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Sidebar Overview Cards -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact & Metadata Card -->
                <x-card title="Account Details">
                    <div class="space-y-4 text-xs">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-theme-main/40 border border-theme">
                            <span class="font-medium text-theme-muted">Phone Number</span>
                            <span class="font-bold text-theme-main">{{ $user->profile?->phone ?? 'Not specified' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-theme-main/40 border border-theme">
                            <span class="font-medium text-theme-muted">Account Status</span>
                            <span class="inline-flex items-center gap-1.5 font-bold text-emerald-500">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-theme-main/40 border border-theme">
                            <span class="font-medium text-theme-muted">Location</span>
                            <span class="font-bold text-theme-main">
                                {{ implode(', ', array_filter([$user->profile?->city, $user->profile?->country])) ?: 'Not specified' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-theme-main/40 border border-theme">
                            <span class="font-medium text-theme-muted">Last Activity</span>
                            <span class="font-semibold text-theme-main">
                                {{ $user->last_activity_at ? $user->last_activity_at->diffForHumans() : 'Recently active' }}
                            </span>
                        </div>
                    </div>
                </x-card>

                <!-- Bio Summary Card -->
                <x-card title="About / Biography">
                    <p class="text-xs text-theme-muted leading-relaxed italic">
                        {{ $user->profile?->bio ? '"' . $user->profile->bio . '"' : 'No biography provided yet. Update your details to add a short profile summary.' }}
                    </p>
                </x-card>
            </div>

            <!-- Right Side Form Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Section Tab Buttons -->
                <div class="flex items-center gap-2 border-b border-theme pb-2 overflow-x-auto whitespace-nowrap">
                    <button @click="activeTab = 'personal'" 
                            :class="activeTab === 'personal' ? 'bg-theme-primary text-white shadow-md' : 'text-theme-muted hover:text-theme-main hover:bg-theme-main/10'"
                            class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Personal Info & Avatar</span>
                    </button>
                    <button @click="activeTab = 'address'" 
                            :class="activeTab === 'address' ? 'bg-theme-primary text-white shadow-md' : 'text-theme-muted hover:text-theme-main hover:bg-theme-main/10'"
                            class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Address & Location</span>
                    </button>
                </div>

                <!-- Main Form -->
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Tab 1: Personal Details & Avatar Upload -->
                    <div x-show="activeTab === 'personal'" class="space-y-6">
                        <x-card title="Profile Avatar & Personal Information">
                            <div class="space-y-6">
                                <!-- Interactive Drag and Drop File Component -->
                                <x-form.file 
                                    name="avatar" 
                                    label="Avatar Picture (Drag & Drop image or click to upload)" 
                                    :file="$user->profile?->avatar_url ?? $user->image" 
                                />

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Full Name -->
                                    <x-form.text 
                                        name="name" 
                                        label="Full Name" 
                                        :value="old('name', $user->name)" 
                                        required 
                                    />

                                    <!-- Email Address -->
                                    <x-form.email 
                                        name="email" 
                                        label="Email Address" 
                                        :value="old('email', $user->email)" 
                                        required 
                                    />
                                </div>

                                <!-- Phone Number -->
                                <x-form.text 
                                    name="phone" 
                                    label="Contact Phone Number" 
                                    :value="old('phone', $user->profile?->phone)" 
                                    placeholder="+1 (555) 000-0000" 
                                />

                                <!-- Bio -->
                                <x-form.textarea 
                                    name="bio" 
                                    label="Biography / Profile Summary" 
                                    :value="old('bio', $user->profile?->bio)" 
                                    rows="3" 
                                    placeholder="Write a short summary about your role and background..." 
                                />
                            </div>
                        </x-card>
                    </div>

                    <!-- Tab 2: Location Information -->
                    <div x-show="activeTab === 'address'" class="space-y-6" x-cloak>
                        <x-card title="Street Address & Country Details">
                            <div class="space-y-4">
                                <x-form.text 
                                    name="address" 
                                    label="Street Address" 
                                    :value="old('address', $user->profile?->address)" 
                                    placeholder="123 Corporate Blvd, Suite 400" 
                                />

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <x-form.text 
                                        name="city" 
                                        label="City" 
                                        :value="old('city', $user->profile?->city)" 
                                        placeholder="New York" 
                                    />
                                    <x-form.text 
                                        name="state" 
                                        label="State / Province" 
                                        :value="old('state', $user->profile?->state)" 
                                        placeholder="NY" 
                                    />
                                    <x-form.text 
                                        name="zip_code" 
                                        label="Zip / Postal Code" 
                                        :value="old('zip_code', $user->profile?->zip_code)" 
                                        placeholder="10001" 
                                    />
                                </div>

                                <x-form.text 
                                    name="country" 
                                    label="Country" 
                                    :value="old('country', $user->profile?->country)" 
                                    placeholder="United States" 
                                />
                            </div>
                        </x-card>
                    </div>

                    <!-- Form Action Controls -->
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-theme-card border border-theme shadow-lg">
                        <span class="text-xs text-theme-muted font-medium">Make sure to save changes before leaving this page</span>
                        <x-form.submit>
                            Save Profile Changes
                        </x-form.submit>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
