<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Status;
use Illuminate\Http\Request; // Pastikan ini ada
use Illuminate\Support\Facades\File;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil kata kunci pencarian dari URL, jika ada.
        $search = $request->input('search');

        // Memulai query ke tabel aset, selalu sertakan data relasi.
        $query = Aset::with(['kategori', 'status']);

        // --- LOGIKA FILTER DIMULAI DI SINI ---

        // Jika ada filter pencarian
        if ($search) {
        $query->where('nama_aset', 'like', '%' . $search . '%');
        }

        // Jika ada filter status_id dari URL (dari halaman Status)
        if ($request->has('status_id')) {
        $query->where('status_id', $request->status_id);
        }

        // Jika ada filter kategori_id dari URL (dari halaman Kategori)
        if ($request->has('kategori_id')) {
        $query->where('kategori_id', $request->kategori_id);

        // Khusus untuk Kategori, kita juga pastikan statusnya "Tersedia"
        $query->whereHas('status', function($q) {
            $q->where('name', 'Tersedia');
        });
    }

    // --- AKHIR LOGIKA FILTER ---

    // Mengurutkan berdasarkan ID dari yang terbesar dan membaginya per halaman.
    $asets = $query->orderBy('id', 'desc')->paginate(10); 

    // Mengirim data ke view.
    return view('aset.index', compact('asets', 'search')); 
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

        // LOGIKA UNGGAH FOTO DIMULAI DI SINI (untuk membuat aset baru)
        if ($request->hasFile('foto')) {
            $namaFile = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('foto_aset'), $namaFile);
            $data['foto'] = $namaFile;
        }
        // LOGIKA UNGGAH FOTO SELESAI

        Aset::create($data);

        return redirect()->route('aset.index')->with('success', 'Aset baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aset $aset)
    {
        // Mengirim data aset yang spesifik ke halaman view 'aset.show'
        return view('aset.show', compact('aset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aset $aset)
    {
        // Ambil data untuk dropdown
        $kategoris = Kategori::all();
        // Perbaiki baris ini untuk tidak menyertakan "Terjual"
        $statuses = Status::where('name', '!=', 'Terjual')->get(); 
        return view('aset.edit', compact('aset', 'kategoris', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aset $aset)
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar untuk update
        ]);

        $data = $request->except('foto');

        // LOGIKA CEK PERUBAHAN STATUS DAN UPDATE tanggal_update
        if ($request->status_id != $aset->status_id) {
            $data['tanggal_update'] = now();
        }

        // LOGIKA UNTUK MENGGANTI/MEMPERBARUI FOTO
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($aset->foto && File::exists(public_path('foto_aset/' . $aset->foto))) {
                File::delete(public_path('foto_aset/' . $aset->foto));
            }

            // Unggah foto baru
            $namaFile = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('foto_aset'), $namaFile);
            $data['foto'] = $namaFile;
        }

        $aset->update($data);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $aset)
    {
        // Hapus transaksi terkait
        $aset->transaksis()->delete();

        // Hapus foto dari server
        if ($aset->foto && File::exists(public_path('foto_aset/' . $aset->foto))) {
            File::delete(public_path('foto_aset/' . $aset->foto));
        }

        // Hapus aset
        $aset->delete();

        return redirect()->route('aset.index')->with('success', 'Aset dan semua riwayat transaksinya berhasil dihapus.');
    }
}   