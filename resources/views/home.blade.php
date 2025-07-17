@extends('layout.base')
@section('title', 'Dashboard')
@section('content')
    <div class="text-gray-800 text-lg sm:max-w-5xl mx-auto p-5">
        <div x-data="salesChart()" x-init="fetchData('/dashboard/chart-json')" class="p-2 mx-auto">
            <div class="flex gap-4 mb-6">
                <!-- Filter Tahun Saja -->
                <select x-model="selectedYear" @change="updateChart()"
                    class="border p-1 rounded border-gray-300 focus:outline-none focus:ring focus:border-gray-300">
                    <template x-for="year in years" :key="year">
                        <option :value="year" x-text="year"></option>
                    </template>
                </select>

            </div>

            <div class="md:overflow-hidden  overflow-x-auto">
                <div id="chart-area" class="bg-white rounded shadow p-4"></div>
            </div>
        </div>
    </div>
@endsection
