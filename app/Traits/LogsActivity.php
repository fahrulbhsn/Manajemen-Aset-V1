<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait to register model event listeners.
     */
    protected static function bootLogsActivity()
    {
        // Mencatat aktivitas saat data baru DIBUAT
        static::created(function ($model) {
            self::logActivity('menambah', $model);
        });

        // Mencatat aktivitas saat data DIUBAH
        static::updated(function ($model) {
            self::logActivity('mengubah', $model);
        });

        // Mencatat aktivitas saat data DIHAPUS
        static::deleted(function ($model) {
            self::logActivity('menghapus', $model);
        });
    }

    /**
     * Log an activity to the ActivityLog model.
     *
     * @param string $action The action performed (e.g., 'menambah', 'mengubah', 'menghapus')
     * @param mixed $model The model instance being acted upon
     */
protected static function logActivity($action, $model)
{
    // Cek jika ada user yang login sebelum mencatat
    if (Auth::check()) {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            // Deskripsi disederhanakan tanpa menampilkan ID
            'description' => "{$action} " . strtolower(class_basename($model)) . " '" . ($model->name ?? $model->nama_aset ?? 'data dengan ID: '.$model->id) . "'",
        ]);
    }
}
}