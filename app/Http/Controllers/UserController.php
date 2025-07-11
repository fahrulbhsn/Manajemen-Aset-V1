<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan halaman daftar pengguna.
     */
    public function index()
    {
        // Ambil semua data pengguna, kecuali admin yang sedang login
        $users = User::where('id', '!=', auth()->id())->latest()->get();

        return view('users.index', compact('users'));
    }
}