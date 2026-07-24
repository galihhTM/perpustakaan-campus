<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Best Practice: Gunakan ::with('category') agar Laravel mengambil data kategori sekaligus 
        // dalam 1 query saja (menghemat memori & performa database)
        $books = \App\Models\Book::with('category')->latest()->get();

        // Kirim data buku ke halaman view
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua data kategori
        $categories = Category::all();

        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Best Practice Validation: Amankan data sebelum masuk ke database
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'isbn'        => 'required|string|unique:books,isbn', // Mencegah duplikasi ISBN
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'publisher'   => 'required|string|max:255',
            'year'        => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'stock'       => 'required|integer|min:0',
            'cover'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Batasan file gambar max 2MB
        ]);

        // Handle Upload Cover Buku
        if ($request->hasFile('cover')) {
            // Beri nama unik pada file untuk menghindari bentrok nama file yang sama
            $fileName = time() . '_' . $request->file('cover')->getClientOriginalName();
            // Simpan ke folder public/storage/covers
            $request->file('cover')->storeAs('covers', $fileName, 'public');
            // Masukkan nama file ke array data yang divalidasi
            $validatedData['cover'] = $fileName;
        }

        // Simpan ke database menggunakan teknik Mass Assignment yang sudah kita amankan di Model
        \App\Models\Book::create($validatedData);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        // Ambil semua kategori untuk opsi dropdown di form edit
        $categories = Category::all();
        
        // Mengirim data buku yang dipilih secara spesifik lewat Route Binding
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        // Validasi ketat untuk update data
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            // PENTING: Mengabaikan ID buku ini sendiri saat pengecekan unique ISBN agar tidak error saat disimpan
            'isbn'        => 'required|string|unique:books,isbn,' . $book->id, 
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'publisher'   => 'required|string|max:255',
            'year'        => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'stock'       => 'required|integer|min:0',
            'cover'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek jika user mengunggah cover baru
        if ($request->hasFile('cover')) {
            // Hapus file gambar cover lama di storage agar tidak menimbun sampah media
            if ($book->cover && Storage::exists('public/covers/' . $book->cover)) {
                Storage::delete('public/covers/' . $book->cover);
            }

            // Simpan file cover yang baru
            $fileName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->storeAs('public/covers', $fileName);
            $validatedData['cover'] = $fileName;
        }

        $book->update($validatedData);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Bersihkan file cover dari storage sebelum data bukunya dihapus permanen
        if ($book->cover && Storage::exists('public/covers/' . $book->cover)) {
            Storage::delete('public/covers/' . $book->cover);
        }

        // Hapus data dari tabel books
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus dari katalog perpustakaan!');
    }
}
