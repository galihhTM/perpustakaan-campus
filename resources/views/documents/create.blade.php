<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Unggah Dokumen Baru') }}
            </h2>
            <a href="{{ route('documents.index') }}" class="text-sm bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Judul Dokumen -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Nama / Judul Dokumen</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror" placeholder="Contoh: Standard Operasional Prosedur" required>
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Versi Dokumen (Baru) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Versi</label>
                            <input type="text" name="version" value="{{ old('version', 'v1.0') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('version') border-red-500 @enderror" placeholder="e.g. v1.0" required>
                            @error('version') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Pilih File -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Berkas (.pdf, .doc, .docx)</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                        <p class="text-xs text-gray-400 mt-1">*Ukuran file maksimal 5MB.</p>
                        @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <hr class="border-gray-100">

                    <div class="flex justify-end space-x-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Reset
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold shadow transition">
                            Arsipkan Dokumen
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>