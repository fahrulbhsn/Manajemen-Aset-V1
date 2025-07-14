<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity; // Import trait untuk mencatat aktivitas

class Status extends Model
{
    use HasFactory;
    use HasFactory, LogsActivity; // Gunakan trait LogsActivity untuk mencatat aktivitas

    protected $fillable = [
        'name',
    ];

    // TAMBAHKAN FUNGSI INI
    public function asets()
    {
        // Satu Status bisa dimiliki oleh banyak Aset
        return $this->hasMany(Aset::class);
    }
}