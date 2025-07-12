@extends('layout.base')
@section('title', 'Dashboard')
@section('content')
    <div class="flex justify-between mb-5" x-data='dataTable(@json($head, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT))'>
        <div class="w-1/2 bg-gray-100 p-4 overflow-y-auto">
            <div class="p-4 max-w-4xl mx-auto">

                <table class="min-w-full bg-white border border-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th @click="sortBy('name')" class="cursor-pointer px-4 py-2">No</th>
                            <th @click="sortBy('email')" class="cursor-pointer px-4 py-2">Waktu</th>
                            <th @click="sortBy('age')" class="cursor-pointer px-4 py-2">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, index)  in paginatedData()" :key="row.id">
                            <tr class="border-t hover:bg-blue-50 cursor-pointer" @click="selectData(row)">
                                <td class="px-4 py-2" x-text="((currentPage - 1) * perPage) + index + 1"></td>
                                <td class="px-4 py-2" x-text="dateParse(row.created_at)"></td>
                                <td class="px-4 py-2" x-text="rupiah(row.nominal)"></td>
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

        <div class="w-1/2 bg-white p-4 flex flex-col">
            <h2 class="font-semibold mb-2 text-xl">Detail</h2>

            <template x-if="selectedRow">
                <div>
                    <p class="mb-2"><strong>Tanggal:</strong> <span x-text="selectedRow.tanggal"></span></p>
                    <p class="mb-2"><strong>Nominal:</strong> <span x-text="rupiah(selectedRow.nominal)"></span></p>

                    <template x-for="item in selectedRow.cart" :key="item.items.id">
                        <div class="p-2 mb-2 border rounded bg-gray-50">
                            <p><strong>Nama:</strong> <span x-text="item.items.name"></span></p>
                            <p><strong>Harga:</strong> <span x-text="rupiah(item.items.price)"></span></p>
                            <p><strong>Jumlah:</strong> <span x-text="item.count"></span></p>
                        </div>
                    </template>
                </div>
            </template>

            <template x-if="!selectedRow">
                <p class="text-gray-400 italic">Pilih data untuk melihat detail.</p>
            </template>

        </div>

    </div>
@endsection
