<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'book_id',
        'quantity'
    ];

    // RELASI: Detail ini merujuk pada sebuah transaksi Peminjaman Induk
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    // RELASI: Detail ini mencatat data dari Buku tertentu
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
