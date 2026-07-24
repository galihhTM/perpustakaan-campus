<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function downloadBackup()
    {
        // 🔒 Proteksi: Hanya selain member (Admin/Staff) yang bisa unduh backup
        if (Auth::user()->role === 'member') {
            abort(403, 'Unauthorized.');
        }

        try {
            $filename = 'backup_perpus_' . now()->format('Y-m-d_H-i-s') . '.sql';
            $tempDir = storage_path('app/private/temp_backups');

            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            $storagePath = $tempDir . '/' . $filename;
            $pdo = DB::connection()->getPdo();
            $dbName = config('database.connections.mysql.database');

            // 📝 1. Susun Header Dokumen SQL
            $sqlContent = "-- ==============================================\n";
            $sqlContent .= "-- Backup Database Perpustakaan\n";
            $sqlContent .= "-- Waktu Pembuatan: " . now()->format('d-m-Y H:i:s') . "\n";
            $sqlContent .= "-- Database: {$dbName}\n";
            $sqlContent .= "-- Engine: Pure PHP Native Generator\n";
            $sqlContent .= "-- ==============================================\n\n";
            $sqlContent .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            // 🔍 2. Ambil seluruh daftar tabel dalam database
            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $tableObj) {
                // Ambil nama tabel secara dinamis
                $tableVars = get_object_vars($tableObj);
                $tableName = array_values($tableVars)[0];

                // A. Dapatkan Struktur Tabel (CREATE TABLE)
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableObj = get_object_vars($createTable[0]);
                $createSql = $createTableObj['Create Table'] ?? array_values($createTableObj)[1];

                $sqlContent .= "-- ----------------------------------------------\n";
                $sqlContent .= "-- Struktur untuk tabel `{$tableName}`\n";
                $sqlContent .= "-- ----------------------------------------------\n";
                $sqlContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sqlContent .= $createSql . ";\n\n";

                // B. Dapatkan Data Isi Tabel (INSERT INTO)
                $rows = DB::table($tableName)->get();

                if ($rows->count() > 0) {
                    $sqlContent .= "-- Data untuk tabel `{$tableName}`\n";
                    foreach ($rows as $row) {
                        $values = array_map(function ($value) use ($pdo) {
                            if (is_null($value)) {
                                return 'NULL';
                            }
                            // Escape karakter khusus secara aman menggunakan PDO
                            return $pdo->quote($value);
                        }, (array) $row);

                        $sqlContent .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sqlContent .= "\n";
                }
            }

            $sqlContent .= "SET FOREIGN_KEY_CHECKS=1;\n";

            // 📄 3. Tulis konten ke dalam file temporer
            File::put($storagePath, $sqlContent);

            // 📥 4. Unduh file ke browser dan hapus file di server setelah selesai
            return response()->download($storagePath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses backup database: ' . $e->getMessage());
        }
    }
}