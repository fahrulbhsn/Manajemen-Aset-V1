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
        // Ambil semua kategori, lengkap dengan relasi ke aset dan status asetnya.
        // Perhitungan akan kita lakukan di view.
        $kategoris = Kategori::with('asets.status')->latest()->get();

        return view('kategori.index', compact('kategoris'));
    }

       public function create()
    {
        // Hanya menampilkan halaman formulir tambah data
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:kategoris,name']);
        Kategori::create($request->all());

        // Jika ada permintaan redirect, kembali ke halaman sebelumnya. Jika tidak, ke halaman index.
        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Kategori baru berhasil ditambahkan.');
        }

        return redirect()->route('kategori.index')->with('success', 'Kategori baru berhasil ditambahkan.');
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