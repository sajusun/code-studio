@props([
'name',
'action',
'title' => 'Delete Confirmation',
'message' => 'Are you sure you want to delete this item? This action cannot be undone.',
'method' => 'DELETE',
'show' => false
])

<x-modal :name="$name" :show="$show" maxWidth="md" focusable>
    <form method="POST" action="{{ $action }}" id="confirm-delete-form" class="p-6">
        @csrf
        @method($method)

        <div class="flex items-start gap-4">
            <div
                class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <div class="mt-0 text-left">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ $title }}
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ $message }}
                </p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">Cancel</x-secondary-button>

            <x-danger-button type="submit">Delete</x-danger-button>
        </div>
    </form>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('confirm-delete-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            
            const url = form.getAttribute('action');
            const token = form.querySelector('input[name="_token"]').value;
            const method = form.querySelector('input[name="_method"]').value;
            
            $.ajax({
                url: url,
                type: method,
                data: {
                    _token: token
                },
                success: function(response) {
                    // Close the modal
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: '{{ $name }}' }));
                    
                    if (response.status || response.success) {
                        // Reload datatable if it exists
                        if ($('.dataTable').length > 0) {
                            $('.dataTable').DataTable().ajax.reload();
                        } else if ($('.datatable-tailwind').length > 0) {
                            $('.datatable-tailwind').DataTable().ajax.reload();
                        } else {
                            window.location.reload();
                        }
                        
                        // Show success alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message || 'Item deleted successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Something went wrong.'
                        });
                    }
                },
                error: function(xhr) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: '{{ $name }}' }));
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while deleting.'
                    });
                }
            });
        });
    }
});
</script>