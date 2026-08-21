@props(['logo' => null])
@php
    $logoPath = $logo ?? 'default/logo.png';
@endphp
<div class="flex items-center gap-3 group">
    <div class="w-10 h-10 rounded-2xl bg-theme-primary flex items-center justify-center font-black text-white text-xl shadow-lg group-hover:scale-105 transition-transform shrink-0">
        M
    </div>
    <span class="font-extrabold text-lg text-theme-main tracking-tight group-hover:text-theme-primary transition-colors">
        {{ config('app.name', 'Master Admin') }}
    </span>
</div>
