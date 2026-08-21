@props([
    'name',
    'label' => '',
    'file' => null,
    'multiple' => false
])

@php
    $previewUrl = asset('default/logo.png');
    if (!empty($file)) {
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $previewUrl = $file;
        } else {
            $cleanPath = ltrim($file, '/');
            if (file_exists(public_path($cleanPath))) {
                $previewUrl = asset($cleanPath);
            } elseif (file_exists(public_path('storage/' . $cleanPath))) {
                $previewUrl = asset('storage/' . $cleanPath);
            } else {
                $previewUrl = asset($cleanPath);
            }
        }
    }
@endphp

<div class="mb-4" x-data="{
    preview: '{{ $previewUrl }}',
    isDragging: false,
    fileChosen(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    },
    handleDrop(event) {
        this.isDragging = false;
        if(event.dataTransfer.files.length > 0) {
            this.$refs.fileInput.files = event.dataTransfer.files;
            this.fileChosen({target: this.$refs.fileInput});
        }
    }
}">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{!! $label
        !!}</label>

    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md relative overflow-hidden transition-colors cursor-pointer group"
        :class="isDragging ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-700 hover:border-indigo-400'"
        @click="$refs.fileInput.click()" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
        @drop.prevent="handleDrop($event)">

        <template x-if="preview">
            <div class="absolute inset-0 w-full h-full p-2">
                <img :src="preview" class="w-full h-full object-contain rounded-md" />
            </div>
        </template>

        <div class="space-y-1 text-center relative z-10 transition-opacity duration-300"
            :class="{ 'opacity-0 group-hover:opacity-100 bg-black/60 p-4 rounded-md': preview }">
            <svg class="mx-auto h-12 w-12" :class="preview ? 'text-gray-200' : 'text-gray-400'" stroke="currentColor"
                fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path
                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm justify-center"
                :class="preview ? 'text-gray-200' : 'text-gray-600 dark:text-gray-400'">
                <span
                    class="relative cursor-pointer rounded-md font-medium text-indigo-500 hover:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    Upload a file
                </span>
                <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs" :class="preview ? 'text-gray-300' : 'text-gray-500'">PNG, JPG, GIF up to 5MB</p>
        </div>
    </div>

    <input x-ref="fileInput" @change="fileChosen" @if(isset($multiple) && $multiple) multiple @endif
        type="file" class="hidden" name="{{ $name }}" id="{{ $name }}" />

    {{ $slot }}
    @error($name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>