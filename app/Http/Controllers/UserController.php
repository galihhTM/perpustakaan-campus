<?php

namespace App\Http\Controllers;

use App\Models\BaseUser; // Atau App\Models\User tergantung nama model default Anda
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    // Fungsi internal untuk memastikan hanya admin yang bisa lewat
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Administrator yang diizinkan.');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();
        
        // Mengambil semua user kecuali admin yang sedang login saat ini (agar tidak sengaja terhapus sendiri)
        $users = User::where('id', '!=', Auth::id())->latest()->get();
        
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,staff,member',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Data anggota berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();
        
        // Hapus user dari database
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Anggota berhasil dihapus secara permanen.');
    }
}