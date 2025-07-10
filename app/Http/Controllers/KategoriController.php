<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- Tambahkan baris ini

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data kategori dari database, diurutkan dari yang terbaru
        $kategoris = Kategori::latest()->get();
        // Mengirim data tersebut ke file view 'index.blade.php'
        return view('kategori.index', compact('kategoris'));
    }

    /**O
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya menampilkan halaman formulir tambah data
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari formulir
        $request->validate([
            'name' => [ // <-- Ubah menjadi array
                'required',
                'string',
                'max:255',
                Rule::unique('kategoris', 'name') // <-- Gunakan Rule::unique
            ],
        ]);
        // 2. Simpan data baru ke dalam tabel 'kategoris'
        Kategori::create([
            'name' => $request->name,
        ]);
        // 3. Kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        // Untuk update, pastikan validasi unique mengabaikan ID kategori saat ini
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategoris', 'name')->ignore($kategori->id), // <-- Ini juga lebih baik pakai Rule object
            ],
        ]);

        $kategori->update(['name' => $request->name]);

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}