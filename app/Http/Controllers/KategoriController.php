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
        // Ambil kategori beserta jumlah aset yang statusnya 'Tersedia'
        $kategoris = Kategori::withCount(['asets' => function ($query) {
            $query->whereHas('status', function ($subQuery) {
                $subQuery->where('name', 'Tersedia');
        });
        }])->latest()->get();

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

   public function store(Request $request)
{
    // Langkah 1: Validasi input dasar (tanpa aturan unique)
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Langkah 2: Pengecekan manual apakah nama kategori sudah ada
    $existingKategori = Kategori::where('name', $request->name)->first();

    // Langkah 3: Jika sudah ada, kembalikan dengan pesan eror
    if ($existingKategori) {
        return back()->withInput()->withErrors(['name' => 'Nama kategori ini sudah ada.']);
    }

    // Langkah 4: Jika aman, baru simpan data ke database
    Kategori::create([
        'name' => $request->name,
    ]);

    // Langkah 5: Kembalikan ke halaman index dengan pesan sukses
    return redirect()->route('kategori.index')
                     ->with('success', 'Kategori berhasil ditambahkan.');
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