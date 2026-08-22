<x-layouts.admin>
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Header & Navigation -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-theme-card border border-theme text-theme-muted hover:text-theme-main transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-black text-theme-main tracking-tight">{{ $product->title }}</h1>
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-lg border {{ $product->type_badge_class }}">
                            {{ $product->type_label }}
                        </span>
                    </div>
                    <p class="text-xs text-theme-muted mt-0.5">{{ $product->category }} • Base Price: <span class="font-bold text-theme-primary">${{ number_format($product->price, 2) }}</span></p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 rounded-xl bg-theme-card border border-theme text-theme-main font-bold text-xs hover:bg-theme-main transition-colors">
                    Edit Product
                </a>
                @if($product->demo_url)
                    <a href="{{ $product->demo_url }}" target="_blank" class="px-4 py-2 rounded-xl bg-theme-primary text-white font-bold text-xs hover:bg-theme-primary-hover shadow-md transition-colors flex items-center gap-2">
                        <span>Live Demo</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Details & Media -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Thumbnail & Overview -->
                <x-card class="p-6">
                    @if($product->thumbnail_url)
                        <img src="{{ $product->thumbnail_url }}" class="w-full h-64 object-cover rounded-xl border border-theme mb-6" alt="{{ $product->title }}">
                    @endif

                    <h3 class="text-base font-bold text-theme-main mb-2">Summary Overview</h3>
                    <p class="text-sm text-theme-muted leading-relaxed mb-6">{{ $product->short_description ?? 'No summary provided.' }}</p>

                    @if($product->description)
                        <h3 class="text-base font-bold text-theme-main mb-2">Full Description</h3>
                        <div class="text-sm text-theme-main leading-relaxed space-y-2 whitespace-pre-line">
                            {{ $product->description }}
                        </div>
                    @endif
                </x-card>

                <!-- Screenshots Gallery -->
                @if(count($product->gallery_urls) > 0)
                    <x-card title="Screenshots Gallery">
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($product->gallery_urls as $img)
                                <a href="{{ $img }}" target="_blank" class="group relative rounded-xl overflow-hidden border border-theme">
                                    <img src="{{ $img }}" class="w-full h-36 object-cover group-hover:scale-105 transition-transform" alt="Screenshot">
                                </a>
                            @endforeach
                        </div>
                    </x-card>
                @endif

                <!-- Features & Module Costs -->
                @if(!empty($product->features))
                    <x-card title="Included Features & Module Estimator">
                        <div class="space-y-3">
                            @foreach($product->features as $feat)
                                <div class="p-3.5 rounded-xl bg-theme-main border border-theme flex items-center justify-between gap-4">
                                    <div>
                                        <h5 class="text-xs font-bold text-theme-main">{{ $feat['title'] ?? 'Feature' }}</h5>
                                        @if(!empty($feat['desc']))
                                            <p class="text-[11px] text-theme-muted mt-0.5">{{ $feat['desc'] }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ ($feat['cost'] ?? 0) > 0 ? 'bg-amber-500/10 text-amber-500' : 'bg-emerald-500/10 text-emerald-500' }}">
                                        {{ ($feat['cost'] ?? 0) > 0 ? '+$' . number_format($feat['cost'], 2) . ' USD' : 'Included Free' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                @endif
            </div>

            <!-- Right Column: Meta, Credentials & Contact Channels -->
            <div class="space-y-6">
                <!-- Tech Stack Tags -->
                <x-card title="Tech Stack Tags">
                    <div class="flex flex-wrap gap-2">
                        @forelse($product->tech_stack ?? [] as $tech)
                            <span class="px-3 py-1 rounded-lg bg-theme-primary/10 text-theme-primary text-xs font-bold border border-theme-primary/20">
                                {{ $tech }}
                            </span>
                        @empty
                            <p class="text-xs text-theme-muted">No tech stack specified.</p>
                        @endforelse
                    </div>
                </x-card>

                <!-- Admin Demo Credentials Box -->
                @if($product->admin_demo_url || $product->admin_demo_username)
                    <x-card title="Admin Demo Credentials">
                        <div class="space-y-3 text-xs">
                            @if($product->admin_demo_url)
                                <div>
                                    <p class="text-theme-muted uppercase font-bold text-[10px]">Admin URL</p>
                                    <a href="{{ $product->admin_demo_url }}" target="_blank" class="text-theme-primary font-mono font-medium hover:underline truncate block">
                                        {{ $product->admin_demo_url }}
                                    </a>
                                </div>
                            @endif
                            @if($product->admin_demo_username)
                                <div class="p-2.5 rounded-xl bg-theme-main border border-theme font-mono">
                                    <p class="text-theme-muted text-[10px] uppercase font-bold">Username</p>
                                    <p class="font-bold text-theme-main select-all">{{ $product->admin_demo_username }}</p>
                                </div>
                            @endif
                            @if($product->admin_demo_password)
                                <div class="p-2.5 rounded-xl bg-theme-main border border-theme font-mono">
                                    <p class="text-theme-muted text-[10px] uppercase font-bold">Password</p>
                                    <p class="font-bold text-theme-main select-all">{{ $product->admin_demo_password }}</p>
                                </div>
                            @endif
                        </div>
                    </x-card>
                @endif

                <!-- Dynamic Contact Channels -->
                <x-card title="Contact & Lead Channels">
                    @php $contacts = $product->contact_channels ?? []; @endphp
                    <div class="space-y-2 text-xs">
                        @if(!empty($contacts['whatsapp']))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contacts['whatsapp']) }}?text={{ urlencode($contacts['whatsapp_text'] ?? 'Hi, I am interested in ' . $product->title) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-xl bg-emerald-500/10 text-emerald-500 font-bold hover:bg-emerald-500/20 transition-colors">
                                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-0.999 3.648 3.742-.981z"/></svg>
                                <span class="truncate">WhatsApp: {{ $contacts['whatsapp'] }}</span>
                            </a>
                        @endif

                        @if(!empty($contacts['email']))
                            <div class="p-3 rounded-xl bg-theme-main border border-theme">
                                <p class="text-[10px] text-theme-muted uppercase font-bold">Direct Email</p>
                                <a href="mailto:{{ $contacts['email'] }}" class="font-bold text-theme-main hover:text-theme-primary truncate block">{{ $contacts['email'] }}</a>
                            </div>
                        @endif

                        @if(!empty($contacts['fiverr']))
                            <a href="{{ $contacts['fiverr'] }}" target="_blank" class="flex items-center justify-between p-3 rounded-xl bg-emerald-600/10 text-emerald-600 font-bold hover:bg-emerald-600/20 transition-colors">
                                <span>Fiverr Profile / Gig</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif

                        @if(!empty($contacts['upwork']))
                            <a href="{{ $contacts['upwork'] }}" target="_blank" class="flex items-center justify-between p-3 rounded-xl bg-green-500/10 text-green-500 font-bold hover:bg-green-500/20 transition-colors">
                                <span>Upwork Profile / Agency</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif

                        @if($product->allow_custom_quotes)
                            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-500 font-bold text-center mt-2">
                                ✓ Custom Quote Requests Enabled
                            </div>
                        @endif
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.admin>
