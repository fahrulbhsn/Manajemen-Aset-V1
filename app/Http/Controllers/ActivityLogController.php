<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Ambil semua data log, urutkan dari yang terbaru, dan gunakan paginasi
        $logs = ActivityLog::with('user')->latest()->paginate(20);

        return view('aktivitas.index', compact('logs'));
    }
}