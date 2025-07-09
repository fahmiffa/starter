@extends('layout.base')
@section('title', 'Dashboard')
@section('content')
<div class="text-gray-800 text-lg bg-gray-50 rounded-2xl shadow-xl max-w-3xl mx-auto">
    <div x-data='dataTable(@json($users, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT))' class="p-4 max-w-4xl mx-auto">
        <div class="mb-4 flex justify-between">
            <input type="text" x-model="search" placeholder="Search..."
                class="border border-gray-900 bg-gray-50 ring-0 rounded-xl p-1 w-full md:w-1/3" />
        </div>

        <table class="min-w-full bg-white border border-gray-200 text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th @click="sortBy('name')" class="cursor-pointer px-4 py-2">Name</th>
                    <th @click="sortBy('email')" class="cursor-pointer px-4 py-2">Email</th>
                    <th @click="sortBy('age')" class="cursor-pointer px-4 py-2">Age</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="row in paginatedData()" :key="row.id">
                    <tr class="border-t">
                        <td class="px-4 py-2" x-text="row.name"></td>
                        <td class="px-4 py-2" x-text="row.email"></td>
                        <td class="px-4 py-2" x-text="row.age"></td>
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
