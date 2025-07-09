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

        <div class="font-bold mb-3 text-xl border-b-1 inline-block">{{$action}} Item</div>

        @if (session('success'))
            <div class="text-green-800 my-3">{{ session('success') }}</div>
        @endif

        @isset($items)
            <form method="POST" action="{{ route('dashboard.items.update',$items->id) }}"  enctype="multipart/form-data">
            @method('PUT')
        @else
            <form method="POST" action="{{ route('dashboard.items.store') }}"  enctype="multipart/form-data">
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
                    <input type="number" min="1" name="pcs" value="{{ old('pcs', isset($items) ? $items->pcs : null) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                </div>
                @error('pcs')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4" x-data="imageCropper()">
                <input type="file" accept="image/*" @change="previewImage">

                <div x-show="imageUrl" class="mt-2">
                    <img :src="imageUrl" id="preview" class="border rounded">
                </div>

                <input type="file" name="cropped_image" class="hidden" x-ref="croppedInput">

                <button type="button" x-show="imageUrl && cropper" @click="cropImage" class="mt-2 bg-blue-500 text-white p-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-crop-icon lucide-crop">
                        <path d="M6 2v14a2 2 0 0 0 2 2h14" />
                        <path d="M18 22V8a2 2 0 0 0-2-2H2" />
                    </svg>
                </button>
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
