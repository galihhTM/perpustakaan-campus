<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Perpustakaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded shadow-sm text-sm font-medium flex items-center justify-between">
                <div class="flex items-center">
                    <span class="text-xl mr-2">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-900 font-bold text-sm">
                    ✕
                </button>
            </div>
            @endif
            <!-- Selamat Datang Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="text-gray-950 font-medium text-lg">
                    Selamat Datang, <span class="text-blue-600 font-bold">{{ auth()->user()->name }}</span>!
                </div>
            </div>

            <!-- GRID MENU BERDASARKAN ROLE -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- 1. MENU KHUSUS ANGGOTA / MEMBER (Bisa dilihat oleh semua, termasuk Admin/Staff) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <h3 class="font-bold text-gray-800 text-lg mb-3">Layanan Anggota</h3>
                    <p class="text-gray-650 text-sm mb-4">Cari buku yang tersedia di perpustakaan kampus dan cek riwayat peminjaman Anda.</p>
                    <div class="space-y-2">
                        <a href="{{ route('books.index') }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded transition">
                            Cari Katalog Buku
                        </a>
                        @if(auth()->user()->role === 'member')
                            <a href="{{ route('loans.index') }}" class="block text-center text-gray-600 hover:text-blue-600 text-sm font-medium py-2 transition">
                                Riwayat Peminjaman Saya
                            </a>
                        @endif
                    </div>
                </div>

                <!-- 2. MENU KHUSUS STAFF / PETUGAS (Hanya muncul jika Role = Staff atau Admin) -->
                @if(auth()->user()->role === 'staff' || auth()->user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                    <h3 class="font-bold text-gray-800 text-lg mb-3">Transaksi Peminjaman</h3>
                    <p class="text-gray-650 text-sm mb-4">Kelola transaksi peminjaman, pengembalian buku, serta pembaruan data stok fisik.</p>
                    <div class="space-y-2">
                        <a href="{{ route('loans.create') }}" class="block text-center bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2 px-4 rounded transition">
                            Catat Peminjaman Baru
                        </a>
                        <a href="{{ route('loans.index') }}" class="block text-center bg-gray-150 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2 px-4 rounded transition">
                            Kelola Pengembalian
                        </a>
                        <!-- <a href="{{ route('books.create') }}" class="block text-center bg-gray-150 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2 px-4 rounded transition">
                            Tambah Koleksi Buku Baru
                        </a> -->
                    </div>
                </div>
                @endif

                <!-- 3. MENU KHUSUS ADMINISTRATOR (Hanya muncul jika Role = Admin) -->
                @if(auth()->user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-amber-500">
                    <h3 class="font-bold text-gray-800 text-lg mb-3">Pengaturan Administrator</h3>
                    <p class="text-gray-650 text-sm mb-4">Akses penuh untuk integrasi data tingkat lanjut, manajemen kualitas data, dan arsip dokumen.</p>
                    <div class="space-y-2">
                        <a href="{{ route('members.import.form') }}" class="block text-center bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold py-2 px-4 rounded transition">
                            Impor Data Anggota (CSV)
                        </a>
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('users.index') }}" class="block text-center bg-gray-800 hover:bg-gray-950 text-white text-sm font-semibold py-2 px-4 rounded transition">
                            Kelola Semua Anggota
                        </a>
                        @endif
                        <a href="{{ route('documents.index') }}" class="block text-center bg-gray-150 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2 px-4 rounded transition">
                            Metadata & Dokumen Digital
                        </a>
                        <a href="{{ route('settings.backup') }}" class="block text-center bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded transition">
                            Backup Database Sistem
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>