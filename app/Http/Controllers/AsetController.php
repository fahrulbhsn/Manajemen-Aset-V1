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

        if ($status_id) {
            $query->where('status_id', $status_id);
        }
        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }
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

        $asets = $query->paginate($per_page)->withQueryString();

        return view('aset.index', compact('asets', 'search', 'sort', 'direction', 'per_page'));
    }

    /**
     * Mencari aset
     */
    public function search(Request $request)
    {
        $search = $request->input('term');

        $asets = Aset::where('nama_aset', 'LIKE', "%{$search}%")
                     ->whereHas('status', function($query) {
                         $query->where('name', 'Tersedia'); // Hanya cari aset yang tersedia
                     })
                     ->select('id', 'nama_aset', 'harga_jual') // Sertakan harga_jual untuk dropdown
                     ->limit(20) // Batasi hasil agar tidak terlalu banyak
                     ->get();

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
        return view('aset.show', compact('aset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aset $aset)
    {
        $kategoris = Kategori::all();
        $statuses = Status::where('name', '!=', 'Terjual')->get(); 
        return view('aset.edit', compact('aset', 'kategoris', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aset $aset)
    {
        //Validasi data
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'status_id' => 'required|exists:statuses,id',
            'tanggal_beli' => 'required|date',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'detail' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek pengguna
        if (Auth::user()->role == 'admin') {
            $data = $request->except('foto');

            if ($request->status_id != $aset->status_id) {
                $data['tanggal_update'] = now();
            }

            if ($request->hasFile('foto')) {
                if ($aset->foto && File::exists(public_path('foto_aset/' . $aset->foto))) {
                    File::delete(public_path('foto_aset/' . $aset->foto));
                }
                $namaFile = time() . '.' . $request->foto->extension();
                $request->foto->move(public_path('foto_aset'), $namaFile);
                $data['foto'] = $namaFile;
            }

            $aset->update($data);

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'mengubah',
                'description' => "Mengubah data aset '{$aset->nama_aset}'"
            ]);

            return redirect()->route('aset.index')->with('success', 'Aset berhasil diperbarui.');

        } else {

            $pendingData = $request->except('_token', '_method', 'foto');
            if ($request->hasFile('foto')) {
                $namaFile = time() . '.' . $request->foto->extension();
                $request->foto->move(public_path('foto_aset'), $namaFile);
                $pendingData['foto'] = $namaFile;
            }            
            $aset->pending_data = json_encode($pendingData);
            $aset->approval_status = 'menunggu persetujuan edit';
            $aset->save();

            // Catat aktivitas pengajuan
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'mengajukan',
                'description' => "Mengajukan perubahan untuk aset '{$aset->nama_aset}'"
            ]);

            return redirect()->route('aset.index')->with('success', 'Permintaan perubahan aset telah diajukan untuk persetujuan Admin.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $aset)
    {
        if (Auth::user()->role == 'admin') {
            $namaAset = $aset->nama_aset;
            $aset->transaksis()->delete(); // Hapus transaksi terkait
            if ($aset->foto && File::exists(public_path('foto_aset/' . $aset->foto))) {
                File::delete(public_path('foto_aset/' . $aset->foto)); // Hapus file foto
            }
            $aset->delete();
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'menghapus',
                'description' => "Menghapus aset '{$namaAset}'"
            ]);
            return redirect()->route('aset.index')
                             ->with('success', 'Aset dan semua riwayat transaksinya berhasil dihapus.');

        } else {
            $aset->approval_status = 'menunggu persetujuan hapus';
            $aset->save();

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'mengajukan',
                'description' => "Mengajukan penghapusan untuk aset '{$aset->nama_aset}'"
            ]);

            return redirect()->route('aset.index')->with('success', 'Permintaan penghapusan aset telah diajukan untuk persetujuan Admin.');
        }
    }
}