@props([
    'name' => null,
    'id' => null,
    'title' => null,
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$modalId = $name ?? $id ?? 'modal';

$maxWidthClass = match ($maxWidth) {
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    default => 'sm:max-w-xl',
};
@endphp

<div x-data="{ 
         show: @js($show),
         focusables() {
             let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
             return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'));
         },
         firstFocusable() { return this.focusables()[0] },
         lastFocusable() { return this.focusables().slice(-1)[0] },
         nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
         prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
         nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
         prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1 },
     }"
     x-init="$watch('show', value => {
         if (value) {
             document.body.classList.add('overflow-y-hidden');
             {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable() && firstFocusable().focus(), 100)' : '' }}
         } else {
             document.body.classList.remove('overflow-y-hidden');
         }
     })"
     x-on:open-modal.window="if ($event.detail === '{{ $modalId }}') show = true"
     x-on:close-modal.window="if ($event.detail === '{{ $modalId }}') show = false"
     x-on:close.stop="show = false"
     x-on:keydown.escape.window="show = false"
     x-show="show"
     class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20"
     style="display: {{ $show ? 'block' : 'none' }};"
     x-cloak>
    
    <!-- Backdrop Overlay -->
    <div x-show="show" 
         x-on:click="show = false"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    <!-- Modal Dialog Panel -->
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
         class="mx-auto w-full {{ $maxWidthClass }} rounded-2xl bg-theme-card border border-theme p-6 shadow-2xl relative z-10">
        
        @if($title)
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-theme">
                <h3 class="text-base font-bold text-theme-main tracking-tight">{{ $title }}</h3>
                <button @click="show = false" type="button" class="text-theme-muted hover:text-theme-main transition-colors cursor-pointer text-lg leading-none">&times;</button>
            </div>
        @endif
            
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
