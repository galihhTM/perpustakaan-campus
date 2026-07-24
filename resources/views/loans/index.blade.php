<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Sirkulasi Peminjaman Buku') }}
            </h2>
            
            <!-- 🔔 BAGIAN KANAN ATAS: Tombol Cetak Laporan & Catat Baru Bersisian -->
            <div class="flex items-center space-x-3 print:hidden">
                @if(in_array(auth()->user()->role, ['admin', 'staff']))
                    <!-- Mengarah ke route laporan PDF secara langsung -->
                    <a href="{{ route('loans.report') }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition flex items-center">
                        Cetak Laporan
                    </a>
                    
                    <a href="{{ route('loans.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                        Catat Transaksi Baru
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Notifikasi Sukses -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded shadow-sm text-sm font-medium">
                {{ session('success') }}
            </div>
            @endif

            <!-- Notifikasi Error -->
            @if(session('error'))
            <div class="mb-6 p-4 bg-rose-100 border-l-4 border-rose-500 text-rose-700 rounded shadow-sm text-sm font-medium">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 text-gray-600 text-xs uppercase font-bold tracking-wider">
                                <th class="px-6 py-3">Peminjam</th>
                                <th class="px-6 py-3">Buku yang Dipinjam</th>
                                <th class="px-6 py-3">Tgl Pinjam</th>
                                <th class="px-6 py-3">Batas Kembali</th>
                                <th class="px-6 py-3">Tgl Pengembalian</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <!-- Kolom Aksi Khusus Petugas/Admin -->
                                @if(auth()->user()->role !== 'member')
                                <th class="px-6 py-3 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($loans as $loan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $loan->user->name }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    @foreach($loan->loanDetails as $detail)
                                        <div class="font-semibold text-blue-600">{{ $detail->book->title }}</div>
                                        <div class="text-xs text-gray-400">ISBN: {{ $detail->book->isbn }}</div>
                                    @endforeach
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ $loan->loan_date->format('d M Y') }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ $loan->due_date->format('d M Y') }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ $loan->return_date ? $loan->return_date->format('d M Y') : '-' }}
                                </td>
                                
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($loan->status === 'borrowed')
                                        <span class="px-2.5 py-1 bg-amber-500 text-amber-100 rounded-full text-xs font-bold uppercase">Dipinjam</span>
                                    @elseif($loan->status === 'returned')
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase">Selesai</span>
                                    @endif
                                </td>

                                <!-- Tombol Aksi Pengembalian Dinamis -->
                                @if(auth()->user()->role !== 'member')
                                <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-semibold">
                                    @if($loan->status === 'borrowed')
                                        <form action="{{ route('loans.update', $loan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin buku ini sudah dikembalikan dengan benar?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-blue-50 hover:bg-blue-100 text-blue-700 py-1.5 px-3 rounded transition">
                                                Telah diKembalikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic">Peminjaman Selesai</span>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">
                                    Belum ada peminjaman.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @if(session('automagic_invoice_id'))
        <div id="invoice-toast" class="fixed bottom-5 right-5 z-50 bg-gray-900 text-white p-4 rounded-xl shadow-2xl border border-gray-700 max-w-sm transform translate-y-0 transition-all duration-500 flex flex-col space-y-3 animate-bounce">
            <div class="flex items-start space-x-3">
                <span class="text-2xl">🎉</span>
                <div>
                    <h4 class="font-bold text-sm text-emerald-400">Peminjaman Berhasil!</h4>
                    <p class="text-xs text-gray-300 mt-1">Data telah diarsipkan. Silakan cetak struk untuk diserahkan kepada anggota.</p>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-1">
                <button onclick="document.getElementById('invoice-toast').remove()" class="text-xs text-gray-400 hover:text-white px-2 py-1">
                    Tutup
                </button>
                <!-- Tombol utama yang akan memicu pembukaan PDF tanpa diblokir browser -->
                <a href="{{ route('loans.invoice', session('automagic_invoice_id')) }}" 
                    target="_blank" 
                    id="auto-click-invoice"
                    onclick="setTimeout(() => { document.getElementById('invoice-toast').remove() }, 1000)"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-1.5 px-3 rounded-lg shadow transition flex items-center space-x-1">
                    <span>Cetak Struk PDF</span>
                </a>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var invoiceUrl = "{{ route('loans.invoice', session('automagic_invoice_id')) }}";
                
                // 🛠️ Trik 1: Coba tembak otomatis dulu
                var newWindow = window.open(invoiceUrl, '_blank');
                
                // 🛠️ Trik 2: Jika diblokir browser, tombol melayang di atas akan berkedip/fokus meminta klik user
                if (newWindow) {
                    // Jika sukses tembus pop-up blocker, hilangkan toast melayang agar bersih
                    document.getElementById('invoice-toast').remove();
                } else {
                    // Jika diblokir, buat tombol berkedip menuntut perhatian petugas perpustakaan
                    console.log('Pop-up blocked by browser. Showing fallback button.');
                    document.getElementById('auto-click-invoice').focus();
                }
            });
        </script>
    @endif
</x-app-layout>


