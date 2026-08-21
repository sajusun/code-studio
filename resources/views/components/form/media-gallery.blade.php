@props([
'label' => 'Image Gallery',
'name' => 'images',
'model' => null,
'multiple' => true,
])

@php
$instance = 'media_' . \Illuminate\Support\Str::random(8);
$medias = $model->media ?? collect();

// dd($data);
@endphp

<div class="media-gallery border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden"
    data-instance="{{ $instance }}" data-name="{{ $name }}">
    <div
        class="px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">

        <div class="flex items-center gap-2">

            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>

            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">

                {{ $label }}

                <span
                    class="ml-1.5 px-2 py-0.5 text-xs bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-full">

                    {{ $medias->count() }}

                </span>

            </h3>

        </div>

        <p class="text-xs text-gray-400">
            Drag to reorder
        </p>

    </div>

    <div class="p-5">

        @if($medias->isEmpty())

        <div class="text-center py-12">

            <div
                class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4">

                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />

                </svg>

            </div>

            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">

                No images yet

            </p>

        </div>

        @else

        <div class="media-sortable grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-5">
            @foreach($medias as $media)

            <div class="media-item relative group rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm hover:shadow-md transition"
                data-id="{{ $media->id }}">

                <div class="relative aspect-video">

                    <img src="{{ asset($media->path) }}" class="w-full h-full object-cover" alt="">

                    {{-- Drag Handle --}}
                    <div
                        class="drag-handle absolute top-2 left-2 w-7 h-7 rounded-lg bg-white/80 dark:bg-gray-800/80 backdrop-blur flex items-center justify-center cursor-move opacity-0 group-hover:opacity-100 transition">

                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />

                        </svg>

                    </div>

                </div>

                <div class="px-3 py-2 flex items-center justify-between">

                    {{-- Status --}}

                    <label class="relative inline-flex items-center cursor-pointer">

                        <input type="checkbox" class="sr-only media-status" data-id="{{ $media->id }}" {{ $media->status
                        ? 'checked' : '' }}
                        >

                        <div class="toggle-bg w-9 h-5 rounded-full transition
                {{ $media->status ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-700'}}">
                        </div>

                        <div class="toggle-dot absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow transition
                {{ $media->status ? 'translate-x-4' : '' }}">
                        </div>

                    </label>


                    {{-- Delete --}}

                    <button type="button"
                        class="media-delete w-7 h-7 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40 flex items-center justify-center"
                        data-id="{{ $media->id }}">

                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />

                        </svg>

                    </button>

                </div>

            </div>

            @endforeach

        </div>
        @endif
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Add New Images</label>

            <label for="{{ $instance }}_upload"
                class="media-upload flex flex-col items-center justify-center gap-2 w-full py-8 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-indigo-500 transition">

                <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />

                </svg>

                <span>

                    Browse Images

                </span>

                <input id="{{ $instance }}_upload" type="file" name="{{ $name }}[]" class="hidden media-input"
                    accept="image/*" {{ $multiple ? 'multiple' : '' }}>

            </label>

            <div class="media-preview hidden mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">

            </div>

        </div>

    </div>

</div>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.media-gallery').forEach(initMediaGallery);

    function initMediaGallery(component) {

        if (component.dataset.initialized) {
            return;
        }

        component.dataset.initialized = true;

        const input = component.querySelector('.media-input');
        const preview = component.querySelector('.media-preview');
        const sortable = component.querySelector('.media-sortable');

        /*
        |--------------------------------------------------------------------------
        | Preview Images
        |--------------------------------------------------------------------------
        */


        // image delete function
        input?.addEventListener('change', function () {

            preview.innerHTML = '';

            if (!this.files.length) {
                preview.classList.add('hidden');
                return;
            }

            preview.classList.remove('hidden');

            [...this.files].forEach(file => {

                const reader = new FileReader();

                reader.onload = function (e) {

                    const item = document.createElement('div');

                    item.className =
                        'relative group rounded-xl overflow-hidden border';

                    item.innerHTML = `
                        <img
                            src="${e.target.result}"
                            class="w-full h-32 object-cover"
                        >

                        <button
                            type="button"
                            class="remove-preview absolute top-2 right-2 w-7 h-7 rounded-full bg-red-500 text-white hidden group-hover:flex items-center justify-center">

                            ✕

                        </button>
                    `;

                    item.querySelector('.remove-preview')
                        .addEventListener('click', function () {

                            item.remove();

                        });

                    preview.appendChild(item);

                }

                reader.readAsDataURL(file);

            });

        });

        component.querySelectorAll('.media-delete').forEach(btn => {
        btn.addEventListener('click', function () {
        const id = this.dataset.id;
        const form = document.getElementById('confirm-delete-form');
        form.action = "{{ route('media.delete', ':id') }}".replace(':id', id);
        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'confirm-user-delete'
        }));
        });
        });

        // status update function
        component.querySelectorAll('.media-status').forEach(toggle => {

            toggle.addEventListener('change', async function () {
            const id = this.dataset.id;
            const checked = this.checked;
            this.checked = !checked;
                try {
                    const res = await axios.post("{{ route('media.status.update', ':id') }}".replace(':id', id), {
                        status: checked ? 1 : 0
                    });
                    this.checked = checked;
                    const bg = this.parentElement.querySelector('.toggle-bg');
                    const dot = this.parentElement.querySelector('.toggle-dot');
                    bg?.classList.toggle('bg-indigo-600', checked);
                    bg?.classList.toggle('bg-gray-200', !checked);
                    dot?.classList.toggle('translate-x-4', checked);
                    iziToast.success({message: res.data.message,position: 'topCenter',timeout: 800,progressBar: false});
                } catch (e) {
                    iziToast.error({message: e.response?.data?.message ?? 'Update failed.'});
                }
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Sortable
        |--------------------------------------------------------------------------
        */

        if (sortable && typeof Sortable !== 'undefined') {
            new Sortable(sortable, {
                animation: 200,
                handle: '.drag-handle',
                onEnd: async () => {
                    const orders = [];
                sortable.querySelectorAll('.media-item').forEach((item, index) => {
                    orders.push({
                        id: item.dataset.id,
                        position: index + 1
                    });
                });

                try {
                    const res = await axios.post('{{ route("media.order.update") }}', { orders });
                    iziToast.success({message: res.data.message,position: 'topCenter',timeout: 800,progressBar: false});
                } catch (e) {
                    iziToast.error({message: e.response?.data?.message ?? 'Reorder failed.'});
                }
            }
            });
        }
    }
    });
</script>
@endpush
@endonce