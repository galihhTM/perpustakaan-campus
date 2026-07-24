<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Eager loading relasi untuk performa optimal (Menghindari N+1 Query)
        $query = Loan::with(['user', 'loanDetails.book'])->latest();

        // Best Practice: Proteksi data di level Query database
        if ($user->role === 'member') {
            $query->where('user_id', $user->id); // Member hanya bisa melihat datanya sendiri
        }

        $loans = $query->get();

        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tolak akses jika member mencoba nakal menembak URL /loans/create
        if (Auth::user()->role === 'member') {
            abort(403, 'Anda tidak memiliki akses untuk mencatat transaksi.');
        }

        // Ambil data user yang rolenya member & buku yang stoknya di atas 0
        $members = User::where('role', 'member')->get();
        $books = Book::where('stock', '>', 0)->get();

        return view('loans.create', compact('members', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role === 'member') {
            abort(403, 'Unauthorized.');
        }

        // 1. Validasi input: Sekarang mendeteksi book_ids sebagai array
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'book_ids'   => 'required|array|min:1',
            'book_ids.*' => 'required|exists:books,id',
            'loan_date'  => 'required|date',
            'due_date'   => 'required|date|after_or_equal:loan_date',
        ]);

        // Bersihkan duplikasi jika petugas tidak sengaja memilih buku yang sama di baris berbeda
        $uniqueBookIds = array_unique($request->book_ids);

        // 2. Cek ketersediaan stok semua buku pilihan secara realtime sebelum memproses transaksi
        foreach ($uniqueBookIds as $bookId) {
            $book = Book::findOrFail($bookId);
            if ($book->stock < 1) {
                return back()->withErrors(['book_ids' => "Buku '{$book->title}' baru saja kehabisan stok fisik."])->withInput();
            }
        }

        // 3. Eksekusi penyimpanan dengan DB Transaction
        $loan = DB::transaction(function () use ($request, $uniqueBookIds) {
            
            // Buat data induk peminjaman sekali saja
            $currentLoan = Loan::create([
                'user_id'     => $request->user_id,
                'loan_date'   => $request->loan_date,
                'due_date'    => $request->due_date,
                'status'      => 'borrowed',
            ]);

            // Simpan setiap buku ke tabel detail peminjaman dan potong stoknya
            foreach ($uniqueBookIds as $bookId) {
                LoanDetail::create([
                    'loan_id'  => $currentLoan->id,
                    'book_id'  => $bookId,
                    'quantity' => 1,
                ]);

                // Kurangi stok buku
                Book::where('id', $bookId)->decrement('stock');
            }

            return $currentLoan;
        });

        // Kembalikan ke halaman indeks perpustakaan dan picu pencetakan PDF otomatis
        return redirect()->route('loans.index')
            ->with('success', 'Transaksi peminjaman multi-buku berhasil dicatat!')
            ->with('automagic_invoice_id', $loan->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        // Hanya Staff dan Admin yang boleh memproses pengembalian buku
    if (Auth::user()->role === 'member') {
        abort(403, 'Anda tidak memiliki akses untuk memproses pengembalian.');
    }

    // Validasi: Pastikan transaksi ini memang statusnya masih dipinjam
    if ($loan->status !== 'borrowed') {
        return back()->with('error', 'Buku pada transaksi ini sudah dikembalikan sebelumnya.');
    }

    // 🔥 BEST PRACTICE: Menggunakan DB Transaction agar pembaruan data & stok sinkron
    DB::transaction(function () use ($loan) {
        // 1. Perbarui status transaksi dan catat tanggal pengembalian hari ini
        $loan->update([
            'return_date' => now()->format('Y-m-d'),
            'status'      => 'returned',
        ]);

        // 2. Kembalikan stok fisik buku berdasarkan data di tabel loan_details
        foreach ($loan->loanDetails as $detail) {
            $detail->book->increment('stock', $detail->quantity);
        }
    });

    return redirect()->route('loans.index')->with('success', 'Pengembalian buku berhasil diproses.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        //
    }

    public function invoice(Loan $loan)
    {
        // Proteksi role
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Akses ditolak.');
        }

        $loan->load(['user', 'books']); 

        // 🛠️ MENGUBAH HTML MENJADI PDF
        // Kita arahkan ke file view baru bernama 'invoice_pdf'
        $pdf = Pdf::loadView('loans.invoice_pdf', compact('loan'));
        
        // ->stream() akan membuka PDF di tab baru browser.
        // Jika ingin langsung otomatis terdownload ke komputer, ganti ->stream() menjadi ->download()
        return $pdf->stream('Invoice_Peminjaman_#'.$loan->id.'.pdf');
    }

    public function report()
    {
        // Proteksi hak akses staff dan admin
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Akses ditolak.');
        }

        // Tarik semua data sirkulasi beserta relasi user dan books
        $loans = Loan::with(['user', 'books'])->latest()->get();

        // Load view khusus laporan rekap
        $pdf = Pdf::loadView('loans.report_pdf', compact('loans'));
        
        // 💡 SET KERTAS KE A4 DENGAN ORIENTASI LANDSCAPE AGAR MUAT BANYAK KOLOM
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Sirkulasi_Peminjaman_Buku.pdf');
    }
}
