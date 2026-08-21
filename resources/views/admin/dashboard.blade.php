<x-layouts.admin>
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card 
            title="Total Revenue" 
            value="$48,290.00" 
            change="+14.2%" 
            :isIncrease="true"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' 
        />
        <x-stat-card 
            title="Active Users" 
            value="1,420" 
            change="+8.1%" 
            :isIncrease="true"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>' 
        />
        <x-stat-card 
            title="OTP Verified" 
            value="98.4%" 
            change="+2.4%" 
            :isIncrease="true"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>' 
        />
        <x-stat-card 
            title="Media Uploaded" 
            value="8,940 Files" 
            change="-1.2%" 
            :isIncrease="false"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' 
        />
    </div>

    <!-- Active Core Modules Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2">
            <x-card title="Recent Payment Transactions" subtitle="Multi-driver gateway audit trail">
                <x-slot name="actions">
                    <button type="button" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-theme-primary text-white hover:bg-theme-primary-hover transition-colors">
                        + New Payment
                    </button>
                </x-slot>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs uppercase text-theme-muted bg-theme-main border-b border-theme">
                            <tr>
                                <th class="p-3">Transaction ID</th>
                                <th class="p-3">Provider</th>
                                <th class="p-3">Amount</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-theme">
                            <tr>
                                <td class="p-3 font-mono font-medium">STRIPE_89A2BF19</td>
                                <td class="p-3"><span class="px-2 py-1 rounded bg-indigo-500/10 text-indigo-500 text-xs font-bold">Stripe</span></td>
                                <td class="p-3 font-semibold">$250.00 USD</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-xs font-semibold">Completed</span></td>
                                <td class="p-3">
                                    <x-crud-actions show="#" edit="#" delete="#" />
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3 font-mono font-medium">SSLC_7119FA82</td>
                                <td class="p-3"><span class="px-2 py-1 rounded bg-sky-500/10 text-sky-500 text-xs font-bold">SSLCommerz</span></td>
                                <td class="p-3 font-semibold">৳ 12,500.00 BDT</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-xs font-semibold">Completed</span></td>
                                <td class="p-3">
                                    <x-crud-actions show="#" edit="#" delete="#" />
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3 font-mono font-medium">BKASH_10294812</td>
                                <td class="p-3"><span class="px-2 py-1 rounded bg-pink-500/10 text-pink-500 text-xs font-bold">bKash</span></td>
                                <td class="p-3 font-semibold">৳ 3,200.00 BDT</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-500 text-xs font-semibold">Pending</span></td>
                                <td class="p-3">
                                    <x-crud-actions show="#" edit="#" delete="#" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        <!-- Active Core Engine Status -->
        <div>
            <x-card title="System Status & Modules">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-theme-main border border-theme">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <div>
                                <h5 class="font-bold text-sm">Headless Mode</h5>
                                <p class="text-xs text-theme-muted">Config: HEADLESS_MODE=false</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/10 text-emerald-500">Hybrid</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-theme-main border border-theme">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <div>
                                <h5 class="font-bold text-sm">OTP & Token Verification</h5>
                                <p class="text-xs text-theme-muted">Cooldown 60s | Max 5 Attempts</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/10 text-emerald-500">Active</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-theme-main border border-theme">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <div>
                                <h5 class="font-bold text-sm">Polymorphic Media Module</h5>
                                <p class="text-xs text-theme-muted">S3 / Local Storage Abstraction</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/10 text-emerald-500">Active</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-theme-main border border-theme">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <div>
                                <h5 class="font-bold text-sm">Multi-Channel Notification</h5>
                                <p class="text-xs text-theme-muted">DB + FCM Push + WebSockets</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/10 text-emerald-500">Active</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.admin>
