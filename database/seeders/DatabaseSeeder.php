<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@library.com',
            'password' => Hash::make('sunrise0990'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Dila Nabila',
            'email' => 'staff1@library.com',
            'password' => Hash::make('bila136'),
            'role' => 'staff',
        ]);

        $member =User::create([
            'name' => 'Galih Min Fadlil',
            'email' => 'galih@library.com',
            'password' => Hash::make('g147258369'),
            'role' => 'member',
        ]);

        $fiksi = Category::create(['name' => 'Fiksi']);
        $teknologi = Category::create(['name' => 'Teknologi']);
        $sains = Category::create(['name' => 'Sains']);

        $book1 = Book::create([
            'category_id' => $fiksi->id,
            'isbn' => '9793062797',
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'publisher' => 'Bentang Pustaka',
            'year' => 2008,
            'stock' => 15,
            'cover' => 'Cover_Laskar_Pelangi.jpg',
        ]);

        $book2 = Book::create([
            'category_id' => $teknologi->id,
            'isbn' => '9786239150884',
            'title' => 'Pemrograman Website dengan PHP-MySQL untuk Pemula',
            'author' => 'Rusli, Ansari Saleh Ahmar, Abdul Rahman',
            'publisher' => 'Yayasan Ahmar Cendekia Indonesia',
            'year' => 2019,
            'stock' => 10,
            'cover' => 'Cover_PhpdanMysql.jpg',
        ]);

        $book3 = Book::create([
            'category_id' => $sains->id,
            'isbn' => '9876340424836',
            'title' => 'Membaca Semesta-Fisika di Sekitar Kita',
            'author' => 'Fajrul Falah, Diah Ayu Suci Kinasih, Moh Iir Ilsatoham',
            'publisher' => 'Fusi Pustaka Semesta',
            'year' => 2026,
            'stock' => 8,
            'cover' => 'Cover_Membaca_Semesta.png',
        ]);

        Document::create([
            'title' => 'SOP Pelayanan Sirkulasi Perpustakaan Kampus',
            'file_name' => 'sop_sirkulasi_perpustakaan_v1.pdf',
            'file_type' => 'pdf',
            'file_location' => 'storage/app/private/documents/sop_sirkulasi_perpustakaan_v1.pdf',
            'version' => '1.0',
        ]);

        Document::create([
            'title' => 'Panduan Penggunaan Repository Digital Universitas',
            'file_name' => 'panduan_repository_final.pdf',
            'file_type' => 'pdf',
            'file_location' => 'storage/app/private/documents/panduan_repository_final.pdf',
            'version' => '2.1',
        ]);

        $loanActive = Loan::create([
            'user_id' => $member->id,
            'loan_date' => now()->subDays(3)->format('Y-m-d'), // Dipinjam 3 hari lalu
            'due_date' => now()->addDays(4)->format('Y-m-d'),   // Batas kembali 4 hari lagi
            'return_date' => null,
            'status' => 'borrowed',
        ]);

        LoanDetail::create([
            'loan_id' => $loanActive->id,
            'book_id' => $book1->id,
            'quantity' => 1,
        ]);

        $loanFinished = Loan::create([
            'user_id' => $member->id,
            'loan_date' => now()->subDays(10)->format('Y-m-d'),
            'due_date' => now()->subDays(3)->format('Y-m-d'),
            'return_date' => now()->subDays(3)->format('Y-m-d'), // Sudah dikembalikan pas tenggat waktu[cite: 1]
            'status' => 'returned',
        ]);

        LoanDetail::create([
            'loan_id' => $loanFinished->id,
            'book_id' => $book2->id,
            'quantity' => 1,
        ]);

    }
}
