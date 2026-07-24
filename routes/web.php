<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\MemberImportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('books', BookController::class);
    Route::resource('loans', LoanController::class);
    Route::resource('documents', DocumentController::class);

    // Route Khusus Impor CSV Anggota
    Route::get('/admin/import-members', [MemberImportController::class, 'showForm'])->name('members.import.form');
    Route::post('/admin/import-members', [MemberImportController::class, 'import'])->name('members.import.submit');
    
    // Rute Modul Dokumen
    Route::resource('documents', DocumentController::class)->except(['show', 'edit', 'update']);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    
    // Rute manajemen user/anggota khusus admin
    Route::resource('users', UserController::class)->except(['create', 'store', 'show']);

    // // Rute cetak invoice peminjaman
    Route::get('/loans/{loan}/invoice', [LoanController::class, 'invoice'])->name('loans.invoice');

    // Taruh di dalam kelompok middleware auth kamu
    Route::get('/loans/report/pdf', [LoanController::class, 'report'])->name('loans.report');

    Route::get('/settings/backup', [BackupController::class, 'downloadBackup'])->name('settings.backup');

});

require __DIR__.'/auth.php';
