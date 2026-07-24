<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Catat Transaksi Peminjaman Baru') }}
            </h2>
            <a href="{{ route('loans.index') }}" class="text-sm bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('loans.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Pilih Anggota/Member -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Anggota Perpustakaan</label>
                        <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('user_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>{{ $member->name }} ({{ $member->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pilih Buku -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buku yang Dipinjam</label>
                        
                        <!-- Tempat baris pilihan buku akan bertambah -->
                        <div id="book-list" class="space-y-2">
                            <div class="flex items-center space-x-2 book-row">
                                <select name="book_ids[]" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                    <option value="">-- Pilih Judul Buku --</option>
                                    @foreach($books as $book)
                                        @if($book->stock > 0)
                                            <option value="{{ $book->id }}">{{ $book->title }} (Stok: {{ $book->stock }})</option>
                                        @endif
                                    @endforeach
                                </select>
                                <!-- Tombol hapus baris (disembunyikan untuk baris pertama) -->
                                <button type="button" onclick="removeBookRow(this)" class="hidden remove-btn text-red-600 hover:text-red-800 text-sm font-bold px-2 py-1">Hapus</button>
                            </div>
                        </div>

                        <!-- Tombol untuk menambah pilihan buku baru -->
                        <button type="button" onclick="addBookRow()" class="mt-2 inline-flex items-center text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                            ➕ Tambah Buku Lain
                        </button>
                        <p class="text-xs text-gray-400 mt-1">*Hanya buku dengan stok fisik yang tersedia yang akan muncul.</p>
                    </div>

                    <!-- Grid Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Peminjaman</label>
                            <input type="date" name="loan_date" id="loan_date" value="{{ old('loan_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Batas Waktu Pengembalian (Tenggat)</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div class="flex justify-end space-x-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Reset Form
                        </button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-sm font-semibold shadow transition">
                            Terbitkan Peminjaman
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Otomatisasi Input Tanggal Default (Best Practice Kemudahan User) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(!document.getElementById('loan_date').value) {
                const today = new Date();
                const dd = String(today.getDate()).padStart(2, '0');
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const yyyy = today.getFullYear();
                
                // Set Hari Ini
                const todayFormatted = `${yyyy}-${mm}-${dd}`;
                document.getElementById('loan_date').value = todayFormatted;

                // Set 7 Hari Kedepan
                const nextWeek = new Date(today);
                nextWeek.setDate(today.getDate() + 7);
                const dd7 = String(nextWeek.getDate()).padStart(2, '0');
                const mm7 = String(nextWeek.getMonth() + 1).padStart(2, '0');
                const yyyy7 = nextWeek.getFullYear();
                
                document.getElementById('due_date').value = `${yyyy7}-${mm7}-${dd7}`;
            }
        });
    </script>

    <script>
        function addBookRow() {
            const bookList = document.getElementById('book-list');
            const firstRow = document.querySelector('.book-row');
            
            // Kloning baris dropdown pertama
            const newRow = firstRow.cloneNode(true);
            
            // Reset nilai select di baris baru
            newRow.querySelector('select').value = "";
            
            // Tampilkan tombol hapus untuk baris baru
            const removeBtn = newRow.querySelector('.remove-btn');
            removeBtn.classList.remove('hidden');
            
            // Masukkan baris baru ke dalam kontainer
            bookList.appendChild(newRow);
        }

        function removeBookRow(button) {
            // Hapus baris pilihan buku yang bersangkutan
            button.closest('.book-row').remove();
        }
    </script>
</x-app-layout>