@props([
    'title',
    'value',
    'change' => null,
    'isIncrease' => true,
    'icon' => null
])

<div {{ $attributes->merge(['class' => 'bg-theme-card rounded-2xl border border-theme p-6 shadow-xs relative overflow-hidden transition-transform duration-200 hover:-translate-y-0.5']) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-theme-muted mb-1">{{ $title }}</p>
            <h4 class="text-3xl font-extrabold text-theme-main tracking-tight">{{ $value }}</h4>

            @if($change !== null)
                <div class="flex items-center gap-1 mt-2 text-xs font-medium">
                    <span class="{{ $isIncrease ? 'text-emerald-500 bg-emerald-500/10' : 'text-rose-500 bg-rose-500/10' }} px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                        @if($isIncrease)
                            ↑
                        @else
                            ↓
                        @endif
                        {{ $change }}
                    </span>
                    <span class="text-theme-muted">vs last month</span>
                </div>
            @endif
        </div>

        @if($icon)
            <div class="p-3.5 rounded-xl bg-theme-primary/10 text-theme-primary">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
