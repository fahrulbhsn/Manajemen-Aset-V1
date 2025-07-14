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
        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            // Membuat deskripsi dinamis, contoh: "mengubah kategori 'Laptop Second'"
            'description' => "{$action} " . strtolower(class_basename($model)) . " '" . ($model->name ?? $model->nama_aset ?? 'ID: ' . $model->id) . "'",
        ]);
    }
}