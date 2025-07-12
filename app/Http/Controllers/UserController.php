<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ... (fungsi index dan create Anda sudah ada di sini) ...

    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,editor',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data user di database.
     */
    public function update(Request $request, User $user)
    {
            // 1. Validasi data yang masuk
    $request->validate([
        'name' => 'required|string|max:255',
        // Pastikan validasi email mengabaikan email user saat ini
        'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        // Peran harus salah satu dari 'admin' atau 'editor'
        'role' => 'required|string|in:admin,editor',
        // Password tidak wajib diisi (nullable) saat edit
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    // 2. Siapkan data untuk diupdate
    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role, // <-- INI BAGIAN KUNCI YANG DIPERBAIKI
    ];

    // 3. Hanya update password jika kolomnya diisi
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // 4. Lakukan update ke database
    $user->update($data);

    // 5. Kembali ke halaman daftar dengan pesan sukses
    return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');

    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}