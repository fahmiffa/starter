@extends('layout.base')
@section('title', 'Dashboard')
@section('content')
    <div class="text-gray-800 text-lg bg-gray-50 rounded-2xl shadow-md max-w-3xl mx-auto">
        <div x-data='dataTable(@json($items, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT))' class="p-4 max-w-4xl mx-auto">
            <div class="mb-4 flex justify-between items-center">
                <input type="text" x-model="search" placeholder="Search..."
                    class="border border-gray-400 bg-gray-50 ring-0 rounded-xl px-3 py-2 w-full md:w-1/3 focus:outline-none" />

                <a href="{{ route('dashboard.categori.create') }}"
                    class="p-2 text-sm bg-gray-800 rounded-3xl text-white">Tambah</a>
            </div>

            <table class="min-w-full bg-white border border-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="cursor-pointer px-4 py-2">No</th>
                        <th @click="sortBy('name')" class="cursor-pointer px-4 py-2">Name</th>
                        <th class="cursor-pointer px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in paginatedData()" :key="row.id">
                        <tr class="border-t border-gray-400">
                            <td class="px-4 py-2" x-text="((currentPage - 1) * perPage) + index + 1"></td>
                            <td class="px-4 py-2" x-text="row.name"></td>
                            <td class="px-4 py-2 flex items-center gap-1">
                                <a :href="`{{ url('dashboard/categori') }}/${row.id}/edit`"
                                    class="text-gray-800 hover:text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-pencil-icon lucide-pencil">
                                        <path
                                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                </a>
                                <form :action="`{{ url('dashboard/categori') }}/${row.id}`" method="POST"
                                    x-data="{ showConfirm: false }"
                                    @submit.prevent="showConfirm = confirm('Yakin ingin menghapus item ini?'); if (showConfirm) $el.submit();"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-800 hover:text-gray-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trash2-icon lucide-trash-2">
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredData().length === 0">
                        <td colspan="3" class="text-center px-4 py-2 text-gray-500">No results found.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4">
                <button @click="prevPage()" :disabled="currentPage === 1"
                    class="px-3 py-1 border rounded bg-gray-100 hover:bg-gray-200 disabled:opacity-50">Prev</button>

                <span>Page <span x-text="currentPage"></span> of <span x-text="totalPages()"></span></span>

                <button @click="nextPage()" :disabled="currentPage === totalPages()"
                    class="px-3 py-1 border rounded bg-gray-100 hover:bg-gray-200 disabled:opacity-50">Next</button>
            </div>
        </div>
    </div>
@endsection
