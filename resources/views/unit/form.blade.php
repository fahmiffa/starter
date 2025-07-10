@extends('layout.base')
@section('title', 'Dashboard')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
    <style>
        img {
            max-width: 100%;
        }
    </style>
@endpush
@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md mx-auto">

        <div class="font-bold mb-3 text-xl border-b-1 inline-block">{{ $action }} Unit</div>

        @if (session('success'))
            <div class="text-green-800 my-3">{{ session('success') }}</div>
        @endif

        @isset($items)
            <form method="POST" action="{{ route('dashboard.unit.update', $items->id) }}">
                @method('PUT')
            @else
                <form method="POST" action="{{ route('dashboard.unit.store') }}">
                @endisset
                @csrf

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Nama</label>
                    <div class="relative">
                        <input type="text" name="name" value="{{ old('name', isset($items) ? $items->name : null) }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>


                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">PCS</label>
                    <div class="relative">
                        <input type="number" min="1" name="pcs"
                            value="{{ old('pcs', isset($items) ? $items->pcs : null) }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    </div>
                    @error('pcs')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>


                <div class="flex items-center">
                    <button type="submit"
                        class="cursor-pointer bg-gray-800 text-sm hover:bg-gray-900 text-white font-bold py-2 px-3 rounded-2xl focus:outline-none focus:shadow-outline">
                        Simpan
                    </button>
                </div>
            </form>
    </div>
@endsection
