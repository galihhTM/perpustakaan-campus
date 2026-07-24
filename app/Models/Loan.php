<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_date',
        'due_date',
        'return_date',
        'status'
    ];

    // Laravel 12 Casting: Otomatis mengubah string database menjadi objek Carbon/Date PHP
    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
        ];
    }

    // RELASI: Transaksi pinjam ini dilakukan oleh seorang User/Anggota
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // RELASI: Satu transaksi induk bisa berisi banyak item buku di detailnya
    public function loanDetails(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'loan_details');
    }
}
