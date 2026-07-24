<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::latest()->get();
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        if (Auth::user()->role === 'member') {
            abort(403, 'Akses ditolak.');
        }
        return view('documents.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'member') {
            abort(403, 'Akses ditolak.');
        }

        // Validasi disesuaikan dengan kebutuhan model baru
        $request->validate([
            'title'   => 'required|string|max:255',
            'version' => 'required|string|max:50', // Contoh: v1.0, 2.1.4
            'file'    => 'required|file|mimes:pdf,docx,doc|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Simpan file ke storage privat
            $file->storeAs('documents', $fileName);

            // Eksekusi penyimpanan sesuai persis dengan isi $fillable model kamu
            Document::create([
                'title'         => $request->title,
                'file_name'     => $fileName,
                'file_type'     => $file->getClientOriginalExtension(), // otomatis mengambil 'pdf' / 'docx'
                'file_location' => 'documents/' . $fileName, // path lengkap untuk dipanggil Storage
                'version'       => $request->version,
            ]);
        }

        return redirect()->route('documents.index')->with('success', 'Dokumen versi baru berhasil diarsipkan!');
    }

    public function download(Document $document)
    {
        // Langsung panggil kolom file_location yang sudah disimpan di database
        if (!Storage::exists($document->file_location)) {
            abort(404, 'Berkas fisik tidak ditemukan di server.');
        }

        // Memberikan nama unduhan yang rapi di komputer user, contoh: Panduan_v1.0.pdf
        $downloadName = str_replace(' ', '_', $document->title) . '_' . $document->version . '.' . $document->file_type;

        return Storage::download($document->file_location, $downloadName);
    }

    public function destroy(Document $document)
    {
        if (Auth::user()->role === 'member') {
            abort(403, 'Akses ditolak.');
        }

        // Hapus file fisik menggunakan path dari kolom file_location
        if (Storage::exists($document->file_location)) {
            Storage::delete($document->file_location);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus permanen.');
    }
}