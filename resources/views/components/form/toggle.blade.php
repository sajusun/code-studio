<div class="mb-4" x-data="{ checked: {{ (isset($checked) && $checked) || old($name, $value ?? '') ? 'true' : 'false' }} }">
    <div class="flex items-center justify-between">
        <label for="{{ $name }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">{!! $label !!}</label>
        <button 
            type="button" 
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" 
            :class="checked ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700'"
            @click="checked = !checked"
            role="switch" 
            aria-checked="false"
        >
            <span 
                aria-hidden="true" 
                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                :class="checked ? 'translate-x-5' : 'translate-x-0'"
            ></span>
        </button>
    </div>
    <input type="hidden" name="{{ $name }}" :value="checked ? '1' : '0'" />
    {{ $slot }}
    @error($name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
