<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Peminjaman #{{ $loan->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            max-width: 100%;
            margin: auto;
            padding: 10px;
        }
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        table td {
            padding: 8px;
            vertical-align: top;
        }
        .header-table td {
            padding-bottom: 30px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1a56db;
            text-transform: uppercase;
        }
        .info-badge {
            background-color: #eff6ff;
            color: #1e40af;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 11px;
            border-radius: 4px;
            display: inline-block;
        }
        .details-table {
            margin-bottom: 30px;
        }
        .details-table td {
            padding: 4px 8px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #9ca3af;
            text-transform: uppercase;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }
        .item-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .item-table th {
            background-color: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
            padding: 10px;
            font-weight: bold;
            text-align: left;
            font-size: 12px;
        }
        .item-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        .notes {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 12px;
            border-radius: 6px;
            font-size: 11px;
            color: #6b7280;
        }
        .signature-container {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }
        .signature-box.right {
            float: right;
            text-align: right;
        }
        .signature-space {
            height: 60px;
        }
        .line {
            border-top: 1px solid #d1d5db;
            width: 160px;
            margin-top: 5px;
        }
        .line-right {
            border-top: 1px solid #d1d5db;
            width: 160px;
            margin-top: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="invoice-box">
        <!-- Header Kop Surat -->
        <table class="header-table">
            <tr>
                <td>
                    <div class="title">PERPUSTAKAAN KAMPUS</div>
                    <span style="font-size: 12px; color: #6b7280;">Sistem Informasi Manajemen Perpustakaan Digital</span><br>
                    <span style="font-size: 11px; color: #9ca3af;">Jl. Edukasi No. 45, Kota Jakarta</span>
                </td>
                <td style="text-align: right;">
                    <div class="info-badge">INVOICE PEMINJAMAN</div>
                    <p style="font-family: monospace; font-size: 12px; margin: 8px 0 0 0; color: #4b5563;">
                        ID: #TRX-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </td>
            </tr>
        </table>

        <!-- Detail Informasi Transaksi -->
        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <div class="section-title">Identitas Peminjam</div>
                    <strong>{{ $loan->user->name }}</strong><br>
                    <span style="font-family: monospace; font-size: 12px; color: #4b5563;">{{ $loan->user->email }}</span><br>
                    <span style="font-size: 12px; color: #6b7280;">Role: <span style="text-transform: capitalize;">{{ $loan->user->role }}</span></span>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="section-title">Tanggal Transaksi</div>
                    <span><strong>Pinjam:</strong> {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</span><br>
                    <span><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</span><br>
                    <span style="font-size: 12px; color: #b45309; font-weight: bold;">
                        Status: {{ $loan->status === 'returned' ? 'Sudah Kembali' : 'Sedang Dipinjam' }}
                    </span>
                </td>
            </tr>
        </table>

        <!-- Tabel Koleksi Buku -->
        <table class="item-table">
            <thead>
                <tr>
                    <th>Detail Koleksi Buku</th>
                    <th style="text-align: center; width: 80px;">Jumlah</th>
                    <th style="text-align: right; width: 120px;">Durasi</th>
                </tr>
            </thead>
            <tbody>
                <!-- 🔔 LOOPING SEMUA BUKU YANG ADA DI DALAM TRANSAKSI INI -->
                @foreach($loan->books as $book)
                <tr>
                    <td>
                        <strong>{{ $book->title }}</strong><br>
                        <span style="font-size: 11px; color: #9ca3af;">ISBN: {{ $book->isbn ?? 'N/A' }}</span>
                    </td>
                    <td style="text-align: center; font-family: monospace;">1 Ekspl.</td>
                    <td style="text-align: right; font-size: 12px; color: #4b5563;">7 Hari Peminjaman</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Ketentuan -->
        <div class="notes">
            <strong>📌 Ketentuan Perpustakaan:</strong>
            <ol style="margin: 5px 0 0 0; padding-left: 15px;">
                <li>Harap kembalikan buku tepat waktu sebelum tanggal jatuh tempo tertera.</li>
                <li>Keterlambatan pengembalian akan dikenakan sanksi denda administratif sesuai regulasi kampus.</li>
            </ol>
        </div>

        <!-- Tanda Tangan Pengesahan -->
        <div class="signature-container">
            <div class="signature-box">
                <span style="font-size: 11px; color: #9ca3af;">Tanda Tangan Anggota</span>
                <div class="signature-space"></div>
                <strong>{{ $loan->user->name }}</strong>
                <div class="line"></div>
            </div>
            <div class="signature-box right">
                <span style="font-size: 11px; color: #9ca3af;">Petugas Perpustakaan</span>
                <div class="signature-space" style="line-height: 60px; font-style: italic; color: #d1d5db; font-size: 12px; text-align: right; padding-right: 40px;">[ Validasi Sistem ]</div>
                <strong>{{ auth()->user()->name }}</strong>
                <div class="line-right"></div>
            </div>
        </div>
    </div>

</body>
</html>