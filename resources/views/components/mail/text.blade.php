@props([
    'variant' => 'body',
])

@php
$styles = [
    'body'    => 'margin: 0 0 20px; color: #334155; font-family: Inter, Arial, sans-serif; font-size: 14px; line-height: 1.7;',
    'small'   => 'margin: 0 0 16px; color: #475569; font-family: Inter, Arial, sans-serif; font-size: 13px; line-height: 1.6;',
    'muted'   => 'margin: 0 0 12px; color: #94a3b8; font-family: Inter, Arial, sans-serif; font-size: 12px; line-height: 1.6;',
    'heading' => 'margin: 0 0 16px; color: #0f172a; font-family: Inter, Arial, sans-serif; font-size: 18px; font-weight: 800; line-height: 1.4;',
];
$style = $styles[$variant] ?? $styles['body'];
@endphp

<p style="{{ $style }}">{{ $slot }}</p>
