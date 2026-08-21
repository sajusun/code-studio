@props(['title' => null, 'subtitle' => null, 'actions' => null])

<div {{ $attributes->merge(['class' => 'bg-theme-card rounded-2xl border border-theme p-4 sm:p-6 shadow-xs transition-all duration-200']) }}>
    @if($title || $actions)
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-theme">
            <div>
                @if($title)
                    <h3 class="text-base sm:text-lg font-bold text-theme-main tracking-tight">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-theme-muted mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if($actions)
                <div class="flex items-center gap-2 shrink-0">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div>
        {{ $slot }}
    </div>
</div>
