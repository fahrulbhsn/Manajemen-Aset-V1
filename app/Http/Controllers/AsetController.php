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
    // Mengambil kata kunci pencarian dari URL, jika ada.
    $search = $request->input('search');

    // Mengambil pilihan jumlah data per halaman dari URL. Jika tidak ada, defaultnya adalah 10.
    $perPage = $request->input('per_page', 10);

    // Memulai query ke tabel aset, selalu sertakan data relasi.
    $query = Aset::with(['kategori', 'status']);

    // Jika ada input pencarian, filter data.
    if ($search) {
        $query->where('nama_aset', 'like', '%' . $search . '%')
              ->orWhereHas('kategori', function ($q) use ($search) {
                  $q->where('name', 'like', '%' . $search . '%');
              })
              ->orWhereHas('status', function ($q) use ($search) {
                  $q->where('name', 'like', '%' . $search . '%');
              });
    }

    // Mengurutkan data berdasarkan ID dari yang terbesar (descending).
    // Menggunakan paginate() (bukan simplePaginate) dengan jumlah data sesuai pilihan pengguna.
    $asets = $query->orderBy('id', 'desc')->paginate($perPage);

    // Mengirim semua data yang dibutuhkan ke view.
    return view('aset.index', compact('asets', 'search', 'perPage'));
}

    /**
     * Mencari aset untuk Select2 AJAX.
     */
    public function search(Request $request)
    {
        // Mengambil parameter pencarian dari Select2 (menggunakan 'term' sesuai standar Select2)
        $search = $request->input('term');

        $asets = Aset::where('nama_aset', 'LIKE', "%{$search}%")
                     ->whereHas('status', function($query) {
                         $query->where('name', 'Tersedia'); // Hanya cari aset yang tersedia
                     })
                     ->select('id', 'nama_aset', 'harga_jual') // Sertakan harga_jual untuk dropdown
                     ->limit(20) // Batasi hasil agar tidak terlalu banyak
                     ->get();

        // Format respons sesuai kebutuhan Select2 dan create.blade.php
        $formatted_asets = $asets->map(function($aset) {
            return [
                'id' => $aset->id,
                'nama_aset' => $aset->nama_aset,
                'harga_jual' => $aset->harga_jual,
                'text' => $aset->nama_aset . ' (Harga: Rp ' . number_format($aset->harga_jual, 0, ',', '.') . ')'
            ];
        });

        return response()->json($formatted_asets);
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