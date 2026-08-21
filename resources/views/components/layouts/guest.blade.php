@props(['title' => null])

<x-guest-layout :title="$title">
    {{ $slot }}
</x-guest-layout>
