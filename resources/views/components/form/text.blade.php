@props([
    'name',
    'label',
    'value' => '',
    'placeholder' => ''
])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{!! $label !!}</label>
    <input 
        type="text" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}" 
        id="{{ $name }}" 
        value="{{ $value }}" 
        {{ $attributes->merge(['class' => 'w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm' . ($errors->has($name) ? ' border-red-500 focus:border-red-500 focus:ring-red-500' : '')]) }} 
    />
    {{ $slot }}
    @error($name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>