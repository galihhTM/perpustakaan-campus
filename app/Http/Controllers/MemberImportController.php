<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class MemberImportController extends Controller
{
    public function showForm()
    {
        // Proteksi tingkat controller: Hanya Admin yang boleh membuka halaman ini
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.import-members');
    }

    public function import(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // 1. Validasi file yang diunggah harus berformat csv/txt dengan ukuran max 2MB
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        
        // Open file untuk dibaca
        $handle = fopen($file->getRealPath(), 'r');
        
        // Baca baris pertama sebagai header (Nama, Email)
        $header = fgetcsv($handle, 1000, ',');

        $importedCount = 0;
        $skippedCount = 0;

        // 🔥 BEST PRACTICE: Gunakan DB Transaction agar jika ada satu data error, tidak merusak data lainnya
        DB::transaction(function () use ($handle, &$importedCount, &$skippedCount) {
            // Loop membaca baris demi baris hingga file habis
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // Lewati jika baris kosong
                if (empty($row[0]) || empty($row[1])) {
                    continue;
                }

                $name = trim($row[0]);
                $email = trim($row[1]);

                // Cek apakah email sudah terdaftar di database untuk menghindari duplicate entry error
                $emailExists = User::where('email', $email)->exists();

                if (!$emailExists) {
                    User::create([
                        'name'     => $name,
                        'email'    => $email,
                        'password' => Hash::make('password123'), // Password default untuk anggota baru
                        'role'     => 'member', // Otomatis diset sebagai member
                    ]);
                    $importedCount++;
                } else {
                    $skippedCount++;
                }
            }
        });

        fclose($handle);

        return redirect()->route('dashboard')->with('success', "Proses Impor Selesai! {$importedCount} anggota berhasil ditambahkan. ({$skippedCount} email dilewati karena sudah terdaftar).");
    }
}
