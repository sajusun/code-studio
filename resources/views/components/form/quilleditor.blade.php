<div class="mb-4" x-data="{
    content: `{!! addslashes(old($name, $value ?? '')) !!}`,
    quill: null,
    init() {
        this.quill = new Quill(this.$refs.editor, {
            theme: 'snow',
            placeholder: '{{ $placeholder }}',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });
        
        if(this.content) {
            this.quill.root.innerHTML = this.content;
        }

        this.quill.on('text-change', () => {
            this.content = this.quill.root.innerHTML;
        });
    }
}">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{!! $label
        !!}</label>
    <div class="flex justify-end mb-2">
        <button type="button" @click="toggleMode()" class="text-sm text-indigo-600 hover:text-indigo-700"
            x-text="htmlMode ? 'Visual Editor' : 'HTML Source'"> ds
        </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-md shadow-sm @error($name) border-red-500 @enderror">
        <div x-ref="editor" class="min-h-[200px] dark:text-gray-300"></div>
    </div>

    <input type="hidden" name="{{ $name }}" id="{{ $name }}" x-model="content">

    {{ $slot }}
    @error($name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>


@once
@push('styles')
<!-- Quill Snow Theme (optional, keep if needed) -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Additional subtle enhancements -->
<style>
    .form-control,
    .form-select,
    .form-textarea {
        @apply rounded-xl border-gray-200 dark: border-gray-600 focus:border-indigo-400 focus:ring focus:ring-indigo-200/50 dark:focus:ring-indigo-800/50 transition-all duration-200;
    }

    .card-body .form-group {
        @apply mb-0;
    }

    .btn-primary {
        @apply shadow-lg shadow-indigo-200/50 dark: shadow-indigo-900/30;
    }

    .btn-primary:hover {
        @apply shadow-indigo-300/60 dark: shadow-indigo-800/50;
    }
</style>
@endpush

@push('scripts')
<!-- Quill JS (if used) -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<!-- optional placeholder for any custom js -->
@endpush
@endonce