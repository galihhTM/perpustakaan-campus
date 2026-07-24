<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sirkulasi Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
            color: #1e3a8a;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .meta-info {
            margin-bottom: 15px;
            width: 100%;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .report-table th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px;
            font-weight: bold;
            text-align: left;
        }
        .report-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-borrowed { background-color: #fef3c7; color: #d97706; }
        .badge-returned { background-color: #d1fae5; color: #059669; }
        .footer-sign {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 200px;
        }
        .space { height: 60px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Data Sirkulasi Peminjaman Buku</h2>
        <p>Sistem Informasi Perpustakaan Digital Kampus | Tanggal Cetak: {{ date('d M Y') }}</p>
    </div>

    <table class="meta-info">
        <tr>
            <td><strong>Dicetak Oleh:</strong> {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</td>
            <td style="text-align: right;"><strong>Total Transaksi:</strong> {{ $loans->count() }} Data Peminjaman</td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 4px; text-align: center;">No</th>
                <th>Peminjam</th>
                <th>Buku Yang Dipinjam</th>
                <th style="width: 90px;">Tgl Pinjam</th>
                <th style="width: 90px;">Batas Kembali</th>
                <th style="width: 90px;">Tgl Kembali</th>
                <th style="width: 80px; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $index => $loan)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $loan->user->name }}</strong><br>
                    <small style="color: #666;">{{ $loan->user->email }}</small>
                </td>
                <td>
                    @foreach($loan->books as $book)
                        • {{ $book->title }} <br>
                        <small style="color: #999;">ISBN: {{ $book->isbn ?? '-' }}</small><br>
                    @endforeach
                </td>
                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                <td>
                    {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d M Y') : '-' }}
                </td>
                <td style="text-align: center;">
                    <span class="badge {{ $loan->status === 'returned' ? 'badge-returned' : 'badge-borrowed' }}">
                        {{ $loan->status === 'returned' ? 'Selesai' : 'Dipinjam' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-sign">
        <p>Jakarta, {{ date('d M Y') }}<br>Petugas Perpustakaan,</p>
        <div class="space"></div>
        <strong>{{ auth()->user()->name }}</strong>
        <div style="border-top: 1px solid #333; margin-top: 5px;"></div>
    </div>

</body>
</html>