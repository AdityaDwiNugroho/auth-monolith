<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Produk') }}
            </h2>
            <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Menampilkan Flash Message Sukses --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Menampilkan Error Validasi / Exception --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">No</th>
                                <th class="py-3 px-6 text-left">Nama Produk</th>
                                <th class="py-3 px-6 text-left">Slug</th>
                                <th class="py-3 px-6 text-right">Harga</th>
                                <th class="py-3 px-6 text-center">Stok</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse ($products as $index => $product)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left">{{ $products->firstItem() + $index }}</td>
                                    <td class="py-3 px-6 text-left">{{ $product->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $product->slug }}</td>
                                    <td class="py-3 px-6 text-right">Rp {{ number_format($product->price, 2, ',', '.') }}</td>
                                    <td class="py-3 px-6 text-center">{{ $product->stock }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center space-x-2">
                                            {{-- Uji Coba Kurangi Stok --}}
                                            <a href="{{ route('products.reduce-stock.get', [$product->id, 1]) }}" class="text-yellow-600 hover:text-yellow-800 font-semibold text-sm">Kurangi</a>
                                            <a href="{{ route('products.reduce-stock.get', [$product->id, $product->stock + 10]) }}" class="text-purple-600 hover:text-purple-800 font-semibold text-sm" title="Uji Coba InsufficientStockException">Kurangi (Error)</a>

                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold text-sm">Edit</a>

                                            {{-- Form Hapus --}}
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                                        Belum ada data produk. Silakan tambah produk terlebih dahulu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination Links --}}
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
