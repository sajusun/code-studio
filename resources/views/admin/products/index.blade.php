<x-layouts.admin>
    <div class="space-y-6">
        <!-- Page Title & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-theme-main tracking-tight">Products & Software Showcase</h1>
                <p class="text-xs text-theme-muted mt-1">Manage web apps, mobile apps, and custom software offerings.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-theme-primary text-white font-bold text-sm shadow-md hover:bg-theme-primary-hover transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Product
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-sm font-semibold flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-theme-card p-4 rounded-2xl border border-theme shadow-xs flex flex-col md:flex-row gap-4 justify-between items-center">
            <form method="GET" action="{{ route('admin.products.index') }}" class="w-full md:w-auto flex flex-wrap items-center gap-3">
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search products..." 
                           class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    <svg class="w-4 h-4 text-theme-muted absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Type Filter -->
                <select name="type" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden">
                    <option value="">All Types</option>
                    <option value="web_app" {{ request('type') === 'web_app' ? 'selected' : '' }}>Web App</option>
                    <option value="android" {{ request('type') === 'android' ? 'selected' : '' }}>Android App</option>
                    <option value="ios" {{ request('type') === 'ios' ? 'selected' : '' }}>iOS App</option>
                    <option value="custom" {{ request('type') === 'custom' ? 'selected' : '' }}>Custom App</option>
                </select>

                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                @if(request()->anyFilled(['search', 'type', 'status']))
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-rose-500 hover:underline font-semibold">Clear Filters</a>
                @endif
            </form>
            
            <div class="text-xs text-theme-muted font-medium">
                Total Products: <span class="font-bold text-theme-main">{{ $products->total() }}</span>
            </div>
        </div>

        <!-- Products Table -->
        <x-card class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs uppercase text-theme-muted bg-theme-main border-b border-theme">
                        <tr>
                            <th class="p-4">Product Details</th>
                            <th class="p-4">Type</th>
                            <th class="p-4">Base Price</th>
                            <th class="p-4">Live Links</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        @forelse($products as $product)
                            <tr class="hover:bg-theme-main/30 transition-colors">
                                <!-- Title & Category -->
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        @if($product->thumbnail)
                                            <img src="{{ $product->thumbnail }}" class="w-12 h-12 rounded-xl object-cover border border-theme shrink-0" alt="{{ $product->title }}">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-theme-primary/10 text-theme-primary flex items-center justify-center font-bold shrink-0">
                                                {{ strtoupper(substr($product->title, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.products.show', $product) }}" class="font-bold text-theme-main hover:text-theme-primary transition-colors truncate">
                                                    {{ $product->title }}
                                                </a>
                                                @if($product->is_featured)
                                                    <span class="px-2 py-0.5 text-[10px] font-extrabold rounded bg-amber-500/10 text-amber-500 uppercase">Featured</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-theme-muted mt-0.5 truncate">{{ $product->category }} • {{ $product->slug }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Type -->
                                <td class="p-4">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg border {{ $product->type_badge_class }}">
                                        {{ $product->type_label }}
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="p-4 font-semibold text-theme-main">
                                    ${{ number_format($product->price, 2) }}
                                </td>

                                <!-- Links -->
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        @if($product->demo_url)
                                            <a href="{{ $product->demo_url }}" target="_blank" class="p-1.5 rounded-lg bg-theme-main text-theme-muted hover:text-theme-primary hover:bg-theme-primary/10 transition-colors" title="Live Web Demo">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endif
                                        @if($product->admin_demo_url)
                                            <a href="{{ $product->admin_demo_url }}" target="_blank" class="p-1.5 rounded-lg bg-theme-main text-theme-muted hover:text-indigo-500 hover:bg-indigo-500/10 transition-colors" title="Admin Demo">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            </a>
                                        @endif
                                        @if($product->apk_url)
                                            <a href="{{ $product->apk_url }}" target="_blank" class="p-1.5 rounded-lg bg-theme-main text-theme-muted hover:text-sky-500 hover:bg-sky-500/10 transition-colors" title="APK Download">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="p-4">
                                    @if($product->is_active)
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-500">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-500/10 text-slate-500">Inactive</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.products.show', $product) }}" class="p-2 rounded-xl text-theme-muted hover:text-theme-primary hover:bg-theme-main transition-colors" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="p-2 rounded-xl text-theme-muted hover:text-sky-500 hover:bg-theme-main transition-colors" title="Edit Product">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl text-theme-muted hover:text-rose-500 hover:bg-theme-main transition-colors" title="Delete Product">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-theme-muted">
                                    <div class="max-w-xs mx-auto space-y-3">
                                        <svg class="w-12 h-12 mx-auto text-theme-muted opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        <p class="font-bold text-sm text-theme-main">No Products Found</p>
                                        <p class="text-xs">Start showcasing your web apps, mobile apps, or custom templates by adding your first product.</p>
                                        <a href="{{ route('admin.products.create') }}" class="inline-block px-4 py-2 text-xs font-bold rounded-xl bg-theme-primary text-white hover:bg-theme-primary-hover">
                                            + Add First Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="p-4 border-t border-theme">
                    {{ $products->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.admin>
