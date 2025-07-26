<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
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
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'role' => 'required|string|in:admin,editor',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');

    }

    /**
    * Menonaktifkan akun pengguna.
    */
    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);
        return redirect()->route('users.index')->with('success', 'Akun pengguna berhasil dinonaktifkan.');
    }
    
    /**
     * Mengaktifkan akun pengguna.
     */
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);
        return redirect()->route('users.index')->with('success', 'Akun pengguna berhasil diaktifkan.');
    }

    /**
     * Menghapus pengguna secara permanen jika tidak ada data terkait.
     */
    public function destroy(User $user)
    {
        if ($user->transaksis()->exists() || $user->activityLogs()->exists()) {
            // Jika ada, kembalikan dengan pesan eror
            return redirect()->route('users.index')
                             ->with('error', 'Pengguna "'. $user->name .'" tidak dapat dihapus karena memiliki riwayat aktivitas atau transaksi.');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil dihapus permanen.');
    }
}