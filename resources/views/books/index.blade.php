<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Katalog Buku Perpustakaan') }}
            </h2>
            
            <!-- TOMBOL TAMBAH BUKU: Hanya muncul untuk Admin & Staff -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
            <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                + Tambah Buku Baru
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Notifikasi Sukses Terintegrasi -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded shadow-sm text-sm font-medium">
                {{ session('success') }}
            </div>
            @endif

            <!-- Tabel Katalog Buku -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg opacity-95">
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 text-gray-650 text-xs uppercase font-bold tracking-wider">
                                <th class="px-6 py-3">Cover</th>
                                <th class="px-6 py-3">Informasi Buku</th>
                                <th class="px-6 py-3">ISBN</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3 text-center">Stok</th>
                                <!-- Kolom Aksi Hanya Muncul untuk Admin & Staff -->
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                                <th class="px-6 py-3 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($books as $book)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Kolom Cover -->
                                <td class="px-6 py-4 whitespace-nowrap w-24">
                                    <!-- UKURAN DIKUNCI: w-16 (lebar 64px) dan h-24 (tinggi 96px) untuk proporsi buku -->
                                    <div class="w-16 h-24 bg-gray-100 border border-gray-200 rounded-md shadow-sm flex items-center justify-center text-gray-400 text-xs overflow-hidden mx-auto">
                                        @if($book->cover)
                                            <!-- object-cover memastikan gambar dipotong rapi secara portrait, tidak gepeng -->
                                            <img src="{{ asset('storage/covers/' . $book->cover) }}" 
                                                alt="{{ $book->title }}" 
                                                class="w-full h-full object-cover">
                                        @else
                                            <!-- Desain placeholder No Cover biar tetap berbentuk buku -->
                                            <div class="flex flex-col items-center justify-center text-center p-1 text-[10px] text-gray-400 font-semibold select-none">
                                                <span class="text-lg mb-1">📖</span>
                                                <span>NO</span>
                                                <span>COVER</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Kolom Info -->
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-base">{{ $book->title }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">Penulis: <span class="text-gray-700 font-medium">{{ $book->author }}</span> | Penerbit: <span class="text-gray-700 font-medium">{{ $book->publisher }} ({{ $book->year }})</span></div>
                                </td>
                                
                                <!-- Kolom ISBN -->
                                <td class="px-6 py-4 font-mono text-gray-600 text-xs">
                                    {{ $book->isbn }}
                                </td>
                                
                                <!-- Kolom Kategori -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-blue-700 rounded-full text-xs font-semibold">
                                        {{ $book->category->name }}
                                    </span>
                                </td>
                                
                                <!-- Kolom Stok -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($book->stock > 0)
                                        <span class="px-2 py-1 text-emerald-700 rounded font-bold text-xs">
                                            {{ $book->stock }} Eks
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-rose-50 text-rose-700 rounded font-bold text-xs">
                                            Habis
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Tombol Aksi Masif: Hanya Terbuka untuk Petugas/Admin -->
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                                <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-semibold">
                                    <div class="flex justify-center items-center space-x-2">
                                        <a href="{{ route('books.edit', $book->id) }}" class="text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 py-1.5 px-3 rounded transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 py-1.5 px-3 rounded transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                    Belum ada koleksi buku di database perpustakaan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>