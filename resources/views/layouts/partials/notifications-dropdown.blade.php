<div class="relative inline-block text-left" 
     x-data="{ 
         open: false, 
         loading: false,
         notifications: [],
         unreadCount: 0,
         
         init() {
             this.fetchNotifications();
             // Real-time Polling Interval every 8 seconds
             setInterval(() => this.fetchNotifications(), 8000);
         },

         async fetchNotifications() {
             try {
                 const res = await fetch('{{ route('admin.notifications.index') }}', {
                     headers: { 'Accept': 'application/json' }
                 });
                 if (res.ok) {
                     const data = await res.json();
                     this.notifications = data.notifications;
                     this.unreadCount = data.unread_count;
                 }
             } catch (e) {
                 console.error('Realtime notification fetch error:', e);
             }
         },

         async markAsRead(id) {
             const item = this.notifications.find(n => n.id === id);
             if (item && !item.read) {
                 item.read = true;
                 if (this.unreadCount > 0) this.unreadCount--;
                 fetch(`{{ url('admin/notifications') }}/${id}/read`, {
                     method: 'POST',
                     headers: { 
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json'
                     }
                 });
             }
         },

         async markAllAsRead() {
             this.notifications.forEach(n => n.read = true);
             this.unreadCount = 0;
             fetch('{{ route('admin.notifications.read-all') }}', {
                 method: 'POST',
                 headers: { 
                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     'Accept': 'application/json'
                 }
             });
         },

         async deleteNotification(id) {
             const item = this.notifications.find(n => n.id === id);
             if (item && !item.read && this.unreadCount > 0) {
                 this.unreadCount--;
             }
             this.notifications = this.notifications.filter(n => n.id !== id);
             fetch(`{{ url('admin/notifications') }}/${id}`, {
                 method: 'DELETE',
                 headers: { 
                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     'Accept': 'application/json'
                 }
             });
         },

         async deleteAll() {
             if (confirm('Are you sure you want to delete all notifications?')) {
                 this.notifications = [];
                 this.unreadCount = 0;
                 fetch('{{ route('admin.notifications.destroy-all') }}', {
                     method: 'DELETE',
                     headers: { 
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json'
                     }
                 });
             }
         }
     }">

    <!-- Notification Bell Button -->
    <button @click="open = !open" 
            type="button" 
            class="relative p-2 rounded-xl text-theme-muted hover:text-theme-main hover:bg-theme-main/10 focus:outline-hidden transition-all duration-200 cursor-pointer" 
            title="Notifications">
        
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        <!-- Unread Badge Counter -->
        <span x-show="unreadCount > 0" 
              x-transition
              class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-xs">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative" x-text="unreadCount"></span>
        </span>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.away="open = false" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
         class="absolute right-0 z-50 mt-2 w-80 sm:w-[420px] md:w-[450px] max-w-[calc(100vw-2rem)] origin-top-right rounded-2xl bg-theme-card border border-theme shadow-2xl overflow-hidden focus:outline-hidden" 
         x-cloak>
        
        <!-- Header -->
        <div class="px-4 py-3 bg-theme-main/50 border-b border-theme flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <h3 class="text-xs font-bold text-theme-main uppercase tracking-wider">Notifications</h3>
                <span x-show="unreadCount > 0" class="px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-theme-primary/10 text-theme-primary" x-text="`${unreadCount} Unread`"></span>
            </div>

            <div class="flex items-center gap-3">
                <button @click="markAllAsRead()" x-show="unreadCount > 0" class="text-xs font-semibold text-theme-primary hover:underline transition-all cursor-pointer">
                    Mark all read
                </button>
                <button @click="deleteAll()" x-show="notifications.length > 0" class="text-xs font-semibold text-rose-500 hover:text-rose-600 hover:underline flex items-center gap-1 cursor-pointer" title="Delete All Notifications">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Clear All
                </button>
            </div>
        </div>

        <!-- Notification Items List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-theme">
            <template x-for="item in notifications" :key="item.id">
                <div class="p-3.5 flex items-start gap-3 hover:bg-theme-main/40 transition-colors group relative"
                     :class="{ 'bg-theme-primary/5': !item.read }">
                    
                    <!-- Icon -->
                    <div @click="markAsRead(item.id)" class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5 cursor-pointer" :class="item.color">
                        <template x-if="item.icon === 'user'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </template>
                        <template x-if="item.icon === 'card'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </template>
                        <template x-if="item.icon === 'shield'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </template>
                        <template x-if="item.icon === 'bell'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </template>
                    </div>

                    <!-- Content -->
                    <div @click="markAsRead(item.id)" class="flex-1 min-w-0 cursor-pointer">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-bold text-theme-main truncate" :class="{ 'font-black': !item.read }" x-text="item.title"></p>
                            <span class="text-[10px] text-theme-muted shrink-0" x-text="item.time"></span>
                        </div>
                        <p class="text-xs text-theme-muted mt-0.5 line-clamp-2 leading-relaxed" x-text="item.desc"></p>
                    </div>

                    <!-- Action Actions: Unread Indicator & Delete Button -->
                    <div class="flex items-center gap-1.5 shrink-0 self-center">
                        <span x-show="!item.read" class="w-2 h-2 rounded-full bg-theme-primary shrink-0"></span>
                        <button @click.stop="deleteNotification(item.id)" 
                                type="button"
                                class="p-1 rounded-lg text-theme-muted hover:text-rose-500 hover:bg-rose-500/10 opacity-0 group-hover:opacity-100 transition-all cursor-pointer" 
                                title="Delete notification">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="notifications.length === 0">
                <div class="p-8 text-center text-xs text-theme-muted">
                    No notifications available.
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="p-2.5 bg-theme-main/30 border-t border-theme text-center">
            <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-bold text-theme-primary hover:text-theme-primary-hover hover:underline transition-all block py-1">
                View All Activity Logs & Notifications →
            </a>
        </div>
    </div>
</div>
