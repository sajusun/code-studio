@props(['id', 'url', 'columns'])

<div
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl shadow-indigo-100/20 dark:shadow-gray-900/40 border border-gray-100/50 dark:border-gray-700/50 overflow-hidden transition-all duration-300 hover:shadow-indigo-200/30 dark:hover:shadow-gray-700/30">
    <!-- Table Header with Search on Right -->
    <div
        class="px-6 py-5 border-b border-gray-100/70 dark:border-gray-700/70 bg-gradient-to-r from-gray-50/80 to-indigo-50/30 dark:from-gray-800/80 dark:to-gray-900/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                {{-- <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">All Users</h3> --}}
                <p class="text-xs text-gray-500 dark:text-gray-400">Manage your team members and users</p>
            </div>
        </div>

        <!-- Search Box - Now on Right -->
        <div class="w-full sm:w-auto flex items-center gap-3">
            <div class="relative flex-1 sm:flex-initial min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="search-input-{{ $id }}" placeholder="Search users..."
                    class="w-full sm:w-64 pl-10 pr-4 py-2.5 text-sm bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200/50 dark:focus:ring-indigo-800/50 transition-all duration-200 placeholder-gray-400 dark:placeholder-gray-500">
            </div>
            {{-- <button
                class="p-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200/50 
                dark:shadow-indigo-900/40 transition-all duration-200 hover:shadow-indigo-300/60 hover:-translate-y-0.5 focus:ring-4 focus:ring-indigo-300/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </button> --}}
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto p-4">
        <table id="{{ $id }}"
            class="w-full text-sm text-left text-gray-600 dark:text-gray-300 datatable-tailwind display responsive nowrap"
            style="width:100%">
            <thead
                class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/80 dark:bg-gray-800/80 border-b border-gray-200/70 dark:border-gray-700/70">
                <tr>
                    @foreach($columns as $col)
                    <th scope="col" class="px-4 py-3.5 first:pl-6 last:pr-6 whitespace-nowrap">
                        <div class="flex items-center gap-1.5">
                            <span>{{ $col['title'] }}</span>
                            @if($col['title'] !== 'Action' && $col['title'] !== 'STATUS')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            @endif
                        </div>
                    </th>
                    @endforeach
                </tr>
            </thead>
        </table>
    </div>

    <!-- Table Footer with Info & Pagination -->
    <div
        class="px-6 py-4 border-t border-gray-100/70 dark:border-gray-700/70 bg-gray-50/50 dark:bg-gray-900/30 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-2">
                <span>Show</span>
                <select id="length-select-{{ $id }}"
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg text-sm px-2 py-1.5 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200/50 dark:focus:ring-indigo-800/50">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entries</span>
            </div>
            <div class="hidden sm:block text-xs text-gray-400 dark:text-gray-500" id="info-text-{{ $id }}">
                Showing 1 to 2 of 6 entries
            </div>
        </div>
        <div class="flex items-center gap-2" id="pagination-{{ $id }}">
            <button
                class="px-3.5 py-1.5 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                class="px-4 py-1.5 text-sm font-medium bg-indigo-600 text-white rounded-lg shadow-md shadow-indigo-200/50 dark:shadow-indigo-900/40">1</button>
            <button
                class="px-3.5 py-1.5 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</div>

@once
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
<style>
    /* Custom Tailwind Datatable Overrides */
    .dataTables_wrapper .dataTables_filter {
        display: none !important;
    }

    .dataTables_wrapper .dataTables_length {
        display: none !important;
    }

    .dataTables_wrapper .dataTables_info {
        display: none !important;
    }

    .dataTables_wrapper .dataTables_paginate {
        display: none !important;
    }

    table.dataTable tbody tr {
        background-color: transparent !important;
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.15s ease;
    }

    .dark table.dataTable tbody tr {
        border-bottom: 1px solid #374151;
    }

    table.dataTable tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .dark table.dataTable tbody tr:hover {
        background-color: #1f2937 !important;
    }

    table.dataTable.no-footer {
        border-bottom: none !important;
    }

    table.dataTable tbody td {
        padding: 0.85rem 1rem !important;
        vertical-align: middle;
    }

    table.dataTable thead th {
        padding: 0.85rem 1rem !important;
    }

    .dataTables_wrapper .dataTables_processing {
        background: rgba(255, 255, 255, 0.9) !important;
        border-radius: 1rem !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1) !important;
        padding: 1.5rem !important;
    }

    .dark .dataTables_wrapper .dataTables_processing {
        background: rgba(17, 24, 39, 0.9) !important;
    }

    /* Status badge colors */
    .badge-active {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .dark .badge-active {
        background: #052e16;
        color: #86efac;
        border-color: #166534;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .dark .badge-inactive {
        background: #2e0a0a;
        color: #fca5a5;
        border-color: #7f1d1d;
    }

    /* Action button styling */
    .action-btn {
        @apply p-2 text-gray-400 hover: text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200;
    }

    .action-btn:hover {
        @apply scale-110;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@endonce

@push('scripts')
<script>
    $(document).ready(function() {
        const tableId = '#{{ $id }}';
        const searchInput = '#search-input-{{ $id }}';
        const lengthSelect = '#length-select-{{ $id }}';
        const paginationContainer = '#pagination-{{ $id }}';
        const infoText = '#info-text-{{ $id }}';

        // Initialize DataTable
        const table = $(tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ $url }}",
                data: function(d) {
                    // Additional params if needed
                }
            },
            columns: @json($columns),
            language: {
                search: "",
                searchPlaceholder: "Search records...",
                processing: `
                    <div class="flex items-center justify-center gap-3 py-8">
                        <div class="w-8 h-8 border-4 border-indigo-200 dark:border-indigo-800 border-t-indigo-600 dark:border-t-indigo-400 rounded-full animate-spin"></div>
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Loading...</span>
                    </div>
                `,
                emptyTable: `
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No users found</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Try adjusting your search or filters</p>
                    </div>
                `
            },
            dom: 't',
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            drawCallback: function() {
                updatePaginationAndInfo();
            }
        });

        // Custom Search Handler
        $(searchInput).on('keyup', function() {
            table.search(this.value).draw();
        });

        // Custom Length Handler
        $(lengthSelect).on('change', function() {
            table.page.len(parseInt(this.value)).draw();
        });

        // Update Pagination & Info UI
        function updatePaginationAndInfo() {
            const info = table.page.info();
            
            // Update info text
            const start = info.start + 1;
            const end = Math.min(info.end, info.recordsTotal);
            $(infoText).text(`Showing ${start} to ${end} of ${info.recordsTotal} entries`);

            // Build pagination
            const totalPages = info.pages;
            const currentPage = info.page + 1;
            let paginationHtml = '';

            // Previous button
            paginationHtml += `
                <button class="px-3.5 py-1.5 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors ${currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : ''}" 
                        onclick="goToPage(${currentPage - 1}, tableId)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            `;

            // Page numbers
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);
            
            if (startPage > 1) {
                paginationHtml += `<button class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" onclick="goToPage(1, tableId)">1</button>`;
                if (startPage > 2) {
                    paginationHtml += `<span class="px-2 text-gray-400">...</span>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const isActive = i === currentPage;
                paginationHtml += `
                    <button class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all duration-200 ${isActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200/50 dark:shadow-indigo-900/40' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'}" 
                            onclick="goToPage(${i}, tableId)">
                        ${i}
                    </button>
                `;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationHtml += `<span class="px-2 text-gray-400">...</span>`;
                }
                paginationHtml += `<button class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" onclick="goToPage(${totalPages}, tableId)">${totalPages}</button>`;
            }

            // Next button
            paginationHtml += `
                <button class="px-3.5 py-1.5 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors ${currentPage >= totalPages ? 'opacity-50 cursor-not-allowed' : ''}" 
                        onclick="goToPage(${currentPage + 1}, tableId)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            `;

            $(paginationContainer).html(paginationHtml);
        }

        // Global function for pagination clicks
        window.goToPage = function(page, tableId) {
            const table = $(tableId).DataTable();
            table.page(page - 1).draw('page');
        };

        // Store table ID for global access
        window.tableId = tableId;

        // Initial render
        updatePaginationAndInfo();

        // Re-render on window resize
        $(window).on('resize', function() {
            table.columns.adjust();
        });
    });
</script>
@endpush