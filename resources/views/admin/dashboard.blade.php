<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Notifications --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Statistik Pengguna</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 border border-blue-100 p-5 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-blue-700">Total Pengguna</p>
                            <p class="text-3xl font-extrabold text-blue-900 mt-1">{{ $totalUsers }}</p>
                        </div>
                        <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-emerald-700">Administrator</p>
                            <p class="text-3xl font-extrabold text-emerald-900 mt-1">{{ $totalAdmins }}</p>
                        </div>
                        <div class="bg-amber-50 border border-amber-100 p-5 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-amber-700">User Biasa</p>
                            <p class="text-3xl font-extrabold text-amber-900 mt-1">{{ $totalRegularUsers }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Management Table (Nilai Plus) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Manajemen Pengguna (RBAC)</h3>
                        <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Fitur Tambahan (Nilai Plus)</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role Saat Ini</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $index => $u)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $u->name }}
                                            @if ($u->id === auth()->id())
                                                <span class="ml-1 text-xs text-indigo-600 font-semibold">(Anda)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $u->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($u->role === 'admin')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                    Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                    User
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $u->created_at ? $u->created_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            @if ($u->id !== auth()->id())
                                                {{-- Toggle Role Form --}}
                                                <form action="{{ route('admin.users.updateRole', $u) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="role" value="{{ $u->role === 'admin' ? 'user' : 'admin' }}">
                                                    <button type="submit" class="text-xs px-2.5 py-1.5 rounded bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition">
                                                        Ubah ke {{ $u->role === 'admin' ? 'User' : 'Admin' }}
                                                    </button>
                                                </form>

                                                {{-- Delete User Form --}}
                                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs px-2.5 py-1.5 rounded bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Akun aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data user.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
