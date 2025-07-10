<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Status;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data aset, termasuk data relasinya (kategori & status)
        $asets = Aset::with(['kategori', 'status'])->latest()->get();  
        return view('aset.index', compact('asets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua data kategori dan status untuk ditampilkan di dropdown
        $kategoris = Kategori::all();
        $statuses = Status::whereIn('name', ['Tersedia', 'Perbaikan'])->get(); // Hanya tampilkan status yang relevan
        return view('aset.create', compact('kategoris', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
    $request->validate([
        'nama_aset' => 'required|string|max:255',
        'kategori_id' => 'required|exists:kategoris,id',
        'status_id' => 'required|exists:statuses,id',
        'tanggal_beli' => 'required|date',
        'harga_beli' => 'required|integer',
        'harga_jual' => 'required|integer',
        'detail' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
    ]);

    $data = $request->all();

    // **LOGIKA UNGGAH FOTO DIMULAI DI SINI**
    if ($request->hasFile('foto')) {
        $namaFile = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('foto_aset'), $namaFile);
        $data['foto'] = $namaFile;
    }
    // **LOGIKA UNGGAH FOTO SELESAI**

    Aset::create($data);

    return redirect()->route('aset.index')->with('success', 'Aset baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aset $aset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aset $aset)
    {
        // Ambil data untuk dropdown
        $kategoris = Kategori::all();
        $statuses = Status::all();
        return view('aset.edit', compact('aset', 'kategoris', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aset $aset)
    {
        // Validasi data (mirip dengan store)
        $request->validate([
        'nama_aset' => 'required|string|max:255',
        'kategori_id' => 'required|exists:kategoris,id',
        'status_id' => 'required|exists:statuses,id',
        'tanggal_beli' => 'required|date',
        'harga_beli' => 'required|integer',
        'harga_jual' => 'required|integer',
        'detail' => 'nullable|string',
        ]);

        $data = $request->all();
        // (Logika update foto akan kita tambahkan nanti)

        $aset->update($data);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $aset)
    {
        //(Logika untuk hapus foto dari server akan kita tambahkan nanti)
        $aset->delete();
        return redirect()->route('aset.index')->with('success', 'Aset berhasil dihapus.');
    }
}
