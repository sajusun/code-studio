@props([
'name',
'label',
'value' => '',
'placeholder' => '',
'rows' => 3,
'required' => false,
])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
        {{ $label }}

        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>

    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
        @required($required) {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-20 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-y'
        ]) }}
    >{{ old($name, $value) }}</textarea>

    {{ $slot }}

    @error($name)
    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
        {{ $message }}
    </p>
    @enderror
</div>