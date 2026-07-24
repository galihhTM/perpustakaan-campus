<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'isbn',
        'title',
        'author',
        'publisher',
        'year',
        'stock',
        'cover',
    ];

    // RELASI: Buku ini berkiblat (dimiliki) oleh sebuah Kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // RELASI: Satu buku bisa masuk ke dalam banyak detail peminjaman
    public function loanDetails(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
