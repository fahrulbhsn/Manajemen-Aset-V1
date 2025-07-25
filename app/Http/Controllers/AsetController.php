<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil semua parameter dari URL
        $search = $request->input('search');
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');
        $kategori_id = $request->input('kategori_id');
        $status_id = $request->input('status_id');
        $status_name = $request->input('status_name');
        $per_page = $request->input('per_page', 10);
        $query = Aset::with(['kategori', 'status']);

        //Filter dari Pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_aset', 'like', '%' . $search . '%')
                    
                  ->orWhereHas('kategori', function ($q_kategori) use ($search) {
                      $q_kategori->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('status', function ($q_status) use ($search) {
                      $q_status->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        //Filter dari Halaman Status (berdasarkan ID)
        if ($status_id) {
            $query->where('status_id', $status_id);
        }

        //Filter dari Halaman Kategori (berdasarkan ID)
        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }

        //Filter tambahan dari Halaman Kategori (berdasarkan nama 'Tersedia')
        if ($status_name) {
            $query->whereHas('status', function($q) use ($status_name) {
                $q->where('name', $status_name);
            });
        }

        // --- LOGIKA PENGURUTAN ---
        if ($sort) {
            if ($sort === 'kategori') {
                $query->select('asets.*')
                      ->join('kategoris', 'asets.kategori_id', '=', 'kategoris.id')
                      ->orderBy('kategoris.name', $direction);
            } elseif ($sort === 'status') {
                $query->select('asets.*')
                      ->join('statuses', 'asets.status_id', '=', 'statuses.id')
                      ->orderBy('statuses.name', $direction);
            } else {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Menggunakan paginate dengan jumlah baris sesuai input per_page
        $asets = $query->paginate($per_page)->withQueryString();

        return view('aset.index', compact('asets', 'search', 'sort', 'direction', 'per_page'));
    }

    /**
     * Mencari aset untuk 
     */
    public function search(Request $request)
    {
        // Mengambil parameter pencarian dari Select2 (menggunakan 'term' sesuai )
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

        // UNGGAH FOTO
        if ($request->hasFile('foto')) {
            $namaFile = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('foto_aset'), $namaFile);
            $data['foto'] = $namaFile;
        }

        $aset = Aset::create($data);

        // Log menambah aset
        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'menambah',
            'description' => "Menambah aset baru '{$aset->nama_aset}'"
        ]);

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

        // Log mengedit aset
        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'mengubah',
            'description' => "Mengubah data aset '{$aset->nama_aset}'"
        ]);

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
        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'menghapus',
            'description' => "Menghapus aset '{$aset->nama_aset}'"
        ]);

        return redirect()->route('aset.index')
                         ->with('success', 'Aset dan semua riwayat transaksinya berhasil dihapus.');
    }
}