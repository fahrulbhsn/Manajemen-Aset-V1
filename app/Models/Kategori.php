<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity; // Import trait untuk mencatat aktivitas

class Kategori extends Model
{
    use HasFactory; // Gunakan trait HasFactory untuk factory testing
    use HasFactory, LogsActivity; // Gunakan trait LogsActivity untuk mencatat aktivitas

    protected $fillable = ['name'];

    // TAMBAHKAN FUNGSI INI
    public function asets()
    {
        // Satu Kategori bisa dimiliki oleh banyak Aset
        return $this->hasMany(Aset::class);
    }
}