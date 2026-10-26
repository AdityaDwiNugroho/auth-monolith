<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-indigo-50 text-indigo-700 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Selamat datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm text-gray-600">Anda berhasil login ke sistem menggunakan autentikasi session-based Laravel Breeze.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">Informasi Akun</h4>
                    <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="bg-gray-50 p-3 rounded">
                            <dt class="text-gray-500 font-medium">Nama Lengkap</dt>
                            <dd class="text-gray-900 font-semibold mt-1">{{ Auth::user()->name }}</dd>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <dt class="text-gray-500 font-medium">Alamat Email</dt>
                            <dd class="text-gray-900 font-semibold mt-1">{{ Auth::user()->email }}</dd>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <dt class="text-gray-500 font-medium">Role (Peran)</dt>
                            <dd class="mt-1">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ Auth::user()->isAdmin() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
