@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block text-xs font-bold text-theme-main tracking-tight uppercase mb-1.5 cursor-pointer']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-rose-500 font-bold">*</span>
    @endif
</label>
