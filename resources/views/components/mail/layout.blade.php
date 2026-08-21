@props([
    'title'     => config('app.name', 'Master Admin'),
    'preheader' => null,
])

<x-email-layout :title="$title" :preheader="$preheader">
    {{ $slot }}
</x-email-layout>
