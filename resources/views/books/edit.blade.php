<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ubah Data Buku: ') }} <span class="text-blue-600">{{ $book->title }}</span>
            </h2>
            <a href="{{ route('books.index') }}" class="text-sm bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT') <!-- Wajib untuk proses Update di Laravel -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul Buku -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                            <input type="text" name="title" value="{{ old('title', $book->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror" required>
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category_id') border-red-500 @enderror" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Penulis -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penulis / Pengarang</label>
                            <input type="text" name="author" value="{{ old('author', $book->author) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('author') border-red-500 @enderror" required>
                            @error('author') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Penerbit -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penerbit</label>
                            <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('publisher') border-red-500 @enderror" required>
                            @error('publisher') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor ISBN</label>
                            <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono @error('isbn') border-red-500 @enderror" required>
                            @error('isbn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tahun dan Stok -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                                <input type="number" name="year" value="{{ old('year', $book->year) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('year') border-red-500 @enderror" required>
                                @error('year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Stok</label>
                                <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('stock') border-red-500 @enderror" required>
                                @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Input Gambar Cover & Preview -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Cover Buku</label>
                        
                        @if($book->cover)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-md border border-gray-200 w-fit">
                            <span class="text-xs font-semibold text-gray-500">Cover Saat Ini:</span>
                            <span class="text-xs text-blue-600 font-mono bg-blue-50 px-2 py-1 rounded">{{ $book->cover }}</span>
                        </div>
                        @endif

                        <input type="file" name="cover" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-blue-100 @error('cover') border-red-500 @enderror">
                        <p class="text-xs text-gray-400">Pilih file baru hanya jika Anda ingin mengganti cover yang lama. Maksimal 2MB.</p>
                        @error('cover') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <hr class="border-gray-100">

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('books.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-md text-sm font-semibold shadow transition">
                            Perbarui Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>