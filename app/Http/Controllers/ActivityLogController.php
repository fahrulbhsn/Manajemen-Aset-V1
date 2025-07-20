<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
public function index(Request $request)
{
    // Ambil parameter dari URL
    $perPage = $request->input('per_page', 20); // Default 20 data per halaman
    $tanggal_awal = $request->input('tanggal_awal');
    $tanggal_akhir = $request->input('tanggal_akhir');

    // Mulai query, selalu sertakan relasi ke user
    $query = ActivityLog::with('user')->latest();

    // Terapkan filter tanggal jika ada
    if ($tanggal_awal && $tanggal_akhir) {
        // Tambahkan 1 hari ke tanggal akhir untuk mencakup semua aktivitas di hari itu
        $tanggal_akhir_plus_satu = date('Y-m-d', strtotime($tanggal_akhir . ' +1 day'));
        $query->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir_plus_satu]);
    }

    // Ambil data dengan paginasi sesuai pilihan
    $logs = $query->paginate($perPage);

    // Kirim semua data yang dibutuhkan ke view
    return view('aktivitas.index', compact('logs', 'perPage', 'tanggal_awal', 'tanggal_akhir'));
}
}