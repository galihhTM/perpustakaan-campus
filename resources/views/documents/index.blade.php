<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Arsip Dokumen & Panduan Digital') }}
            </h2>
            @if(auth()->user()->role !== 'member')
            <a href="{{ route('documents.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                + Unggah Dokumen Baru
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded shadow-sm text-sm font-medium">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($documents as $document)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex justify-between items-start">
                            <!-- Badge Tipe File Dinamis -->
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 font-mono text-xs uppercase font-bold rounded border border-gray-200">
                                .{{ $document->file_type }}
                            </span>
                            <!-- Badge Versi -->
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">
                                {{ $document->version }}
                            </span>
                        </div>
                        
                        <h3 class="font-bold text-gray-900 text-lg leading-snug mt-3">{{ $document->title }}</h3>
                        
                        <!-- Informasi Lokasi File Fisik (Sangat bagus untuk pembuktian ke Asesor) -->
                        <div class="mt-4 p-2 bg-gray-50 border border-gray-100 rounded-lg text-xs text-gray-500 font-mono overflow-x-auto">
                            <span class="text-gray-400">Path:</span> {{ $document->file_location }}
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center">
                        <a href="{{ route('documents.download', $document->id) }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                            📥 Unduh Berkas
                        </a>

                        @if(auth()->user()->role !== 'member')
                        <form action="{{ route('documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Hapus berkas arsip ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-medium bg-rose-50 px-2 py-1 rounded transition">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white text-center py-12 rounded-xl border border-dashed border-gray-200 text-gray-400 italic">
                    Belum ada dokumen digital yang diarsipkan saat ini.
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>