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
@push('script')
    <script>
        window.unitOptions = @json($unit);
    </script>
@endpush
@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md ml-10">

        <div class="font-bold mb-3 text-xl border-b-1 inline-block">{{ $action }} Item</div>

        @if (session('success'))
            <div class="text-green-800 my-3">{{ session('success') }}</div>
        @endif

        @isset($items)
            <form method="POST" action="{{ route('dashboard.items.update', $items->id) }}" enctype="multipart/form-data">
                @method('PUT')
            @else
                <form method="POST" action="{{ route('dashboard.items.store') }}" enctype="multipart/form-data">
                @endisset
                @csrf

                <div class="mb-4">
                    <div class="relative">
                        <input type="text" name="name" value="{{ old('name', isset($items) ? $items->name : null) }}"
                            placeholder="nama"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="relative">
                        <input type="number" name="stok" min="1"
                            value="{{ old('stok', isset($items) ? $items->stok : null) }}" placeholder="Stok"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    </div>
                    @error('stok')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="relative">
                        <input type="number" name="price" min="1"
                            value="{{ old('stok', isset($items) ? $items->price : null) }}" placeholder="Harga"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data='dataSelect(@json($cat, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT))' class="mb-4 relative">
                    <!-- Hidden Input to Submit ID -->
                    <input type="hidden" name="cat_id" :value="selected?.id">

                    <!-- Input Search -->
                    <input x-model="search" x-on:focus="open = true" x-on:click.outside="open = false" type="text"
                        placeholder="Pilih Categori"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring">

                    <!-- Dropdown List -->
                    <ul x-show="open" x-transition
                        class="absolute z-10 mt-1 w-100 bg-white border border-gray-300 rounded shadow max-h-60 overflow-y-auto">
                        <template x-for="(item, index) in filteredOptions" :key="index">
                            <li x-on:click="selectItem(item)"
                                class="px-3 py-2 hover:bg-gray-500 hover:text-white cursor-pointer" x-text="item.name"></li>
                        </template>
                    </ul>

                    @error('cat_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4" x-data="imageCropper()">
                    <input type="file" accept="image/*" @change="previewImage"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-600 file:text-white hover:file:bg-gray-700 transition">

                    <div x-show="imageUrl" class="mt-2">
                        <img :src="imageUrl" id="preview" class="border rounded">
                    </div>

                    <input type="file" name="cropped_image" class="hidden" x-ref="croppedInput">

                    <button type="button" x-show="imageUrl && cropper" @click="cropImage"
                        class="mt-2 bg-blue-500 text-white p-2 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-crop-icon lucide-crop">
                            <path d="M6 2v14a2 2 0 0 0 2 2h14" />
                            <path d="M18 22V8a2 2 0 0 0-2-2H2" />
                        </svg>
                    </button>
                </div>

                <div x-data='dataSelect(@json($unit, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT))' class="mb-4 relative">
                    <!-- Hidden Input to Submit ID -->
                    <input type="hidden" name="unit_id" :value="selected?.id">

                    <!-- Input Search -->
                    <input x-model="search" x-on:focus="open = true" x-on:click.outside="open = false" type="text"
                        placeholder="Pilih unit"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring">

                    <!-- Dropdown List -->
                    <ul x-show="open" x-transition
                        class="absolute z-10 mt-1 w-100 bg-white border border-gray-300 rounded shadow max-h-60 overflow-y-auto">
                        <template x-for="(item, index) in filteredOptions" :key="index">
                            <li x-on:click="selectItem(item)"
                                class="px-3 py-2 hover:bg-gray-500 hover:text-white cursor-pointer" x-text="item.name"></li>
                        </template>
                    </ul>

                    @error('unit_id')
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
