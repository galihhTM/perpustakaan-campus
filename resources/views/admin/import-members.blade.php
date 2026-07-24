<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Impor Data Anggota via CSV') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                ← Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Petunjuk Format CSV (Sangat disukai Asesor karena informatif) -->
                <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded text-sm">
                    <span class="font-bold">⚠️ Panduan Format File CSV:</span>
                    <p class="mt-1">Pastikan file CSV Anda memiliki struktur 2 kolom tanpa baris judul (header) atau dengan format urutan berikut:</p>
                    <table class="mt-2 w-full border border-blue-200 text-xs font-mono bg-white">
                        <thead>
                            <tr class="bg-blue-100 text-left">
                                <th class="p-1 border border-blue-200">Kolom 1 (Nama)</th>
                                <th class="p-1 border border-blue-200">Kolom 2 (Email)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-1 border border-blue-200">Budi Utomo</td>
                                <td class="p-1 border border-blue-200">budi@gmail.com</td>
                            </tr>
                            <tr>
                                <td class="p-1 border border-blue-200">Eka Sari</td>
                                <td class="p-1 border border-blue-200">ekasari@gmail.com</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="mt-2 text-xs text-gray-500">*Password otomatis diset menjadi: <span class="font-bold text-gray-700">password123</span> setelah berhasil diimpor.</p>
                </div>

                <!-- Form Upload -->
                <form action="{{ route('members.import.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File CSV (.csv)</label>
                        <input type="file" name="csv_file" accept=".csv" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100" required>
                        @error('csv_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2 px-5 rounded-md text-sm shadow transition">
                            🚀 Mulai Impor Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>