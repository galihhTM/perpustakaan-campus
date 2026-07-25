<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Koleksi Buku Baru') }}
            </h2>
            <a href="{{ route('books.index') }}" class="text-sm bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Form Menggunakan enctype karena ada input type="file" -->
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul Buku -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }}" required>
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori (Dropdown Dinamis) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('category_id') ? 'border-red-500' : 'border-gray-300' }}" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Penulis -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penulis / Pengarang</label>
                            <input type="text" name="author" value="{{ old('author') }}" class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('author') ? 'border-red-500' : 'border-gray-300' }}" required>
                            @error('author') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Penerbit -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penerbit</label>
                            <input type="text" name="publisher" value="{{ old('publisher') }}" class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('publisher') ? 'border-red-500' : 'border-gray-300' }}" required>
                            @error('publisher') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor ISBN</label>
                            <input type="text" name="isbn" value="{{ old('isbn') }}" class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono {{ $errors->has('isbn') ? 'border-red-500' : 'border-gray-300' }}" placeholder="Contoh: 978602..." required>
                            @error('isbn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- grid kecil untuk Tahun dan Stok -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                                <input type="number" name="year" value="{{ old('year', '') }}" class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('year') ? 'border-red-500' : 'border-gray-300' }}" required>
                                @error('year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Stok</label>
                                <input type="number" name="stock" value="{{ old('stock', '') }}" min="0" class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('stock') ? 'border-red-500' : 'border-gray-300' }}" required>
                                @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Input Gambar Cover -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cover Buku (Opsional)</label>
                        <input type="file" name="cover" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-blue-100 {{ $errors->has('cover') ? 'border-dashed border-red-500 rounded-md' : '' }}">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                        @error('cover') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <hr class="border-gray-100">

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end space-x-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Reset Form
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold shadow transition">
                            Simpan Buku
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>