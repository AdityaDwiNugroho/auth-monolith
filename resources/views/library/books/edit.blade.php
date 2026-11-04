<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Koleksi Buku: ') . $book->title }}
            </h2>
            <a href="{{ route('library.books.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                            <strong class="font-bold">Gagal Memperbarui! Periksa kesalahan berikut:</strong>
                            <ul class="list-disc list-inside mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('library.books.update', $book->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Judul Buku --}}
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Buku <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Penulis Buku --}}
                        <div class="mb-4">
                            <label for="author_id" class="block text-sm font-medium text-gray-700">Penulis Buku <span class="text-red-500">*</span></label>
                            <select name="author_id" id="author_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('author_id') border-red-500 @enderror">
                                <option value="">-- Pilih Penulis --</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }} ({{ $author->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('author_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ISBN --}}
                        <div class="mb-4">
                            <label for="isbn" class="block text-sm font-medium text-gray-700">Nomor ISBN <span class="text-red-500">*</span></label>
                            <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('isbn') border-red-500 @enderror">
                            @error('isbn')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            {{-- Tahun Terbit --}}
                            <div>
                                <label for="published_year" class="block text-sm font-medium text-gray-700">Tahun Terbit <span class="text-red-500">*</span></label>
                                <input type="number" name="published_year" id="published_year" value="{{ old('published_year', $book->published_year) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('published_year') border-red-500 @enderror"
                                    min="1900" max="{{ date('Y') }}">
                                @error('published_year')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Harga Buku --}}
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" step="any" name="price" id="price" value="{{ old('price', $book->price) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('price') border-red-500 @enderror">
                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Stok Buku --}}
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stok <span class="text-red-500">*</span></label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', $book->stock) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('stock') border-red-500 @enderror"
                                    min="0">
                                @error('stock')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 mt-6">
                            <a href="{{ route('library.books.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Perbarui Buku
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
