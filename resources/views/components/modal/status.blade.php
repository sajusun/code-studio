@props([
'name' => 'status-modal',
'type' => null,
'title' => null,
'message' => null,
'show' => false
])

@php
$statusType = $type ?? (session('success') ? 'success' : (session('error') ? 'error' : null));
$statusMessage = $message ?? (session('success') ?? session('error'));
$statusTitle = $title ?? ($statusType === 'success' ? 'Success!' : 'Error Occurred');
$shouldShow = $show || !empty($statusMessage);
@endphp

@if($statusType && $statusMessage)
<x-modal :name="$name" :show="$shouldShow" maxWidth="md" focusable>
    <div class="p-6 text-center">
        @if($statusType === 'success')
        <div
            class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mb-4">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        @else
        <div
            class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mb-4">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        @endif

        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
            {{ $statusTitle }}
        </h3>

        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            {{ $statusMessage }}
        </p>

        <div class="flex justify-center">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">Dismiss</x-secondary-button>
        </div>
    </div>
</x-modal>
@endif