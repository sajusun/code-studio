<div class="mb-4">
    <label class="inline-flex items-center cursor-pointer">
        <input 
            type="radio" 
            class="border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 @error($name) border-red-500 @enderror" 
            name="{{ $name }}" 
            id="{{ $id ?? $name . '_' . $value }}" 
            value="{{ $value }}" 
            {{ (isset($checked) && $checked) || old($name) == $value ? 'checked' : '' }}
            {{ $attributes }}
        />
        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 font-medium">{!! $label !!}</span>
    </label>
    @error($name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
