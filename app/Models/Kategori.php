<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // TAMBAHKAN FUNGSI INI
    public function asets()
    {
        // Satu Kategori bisa dimiliki oleh banyak Aset
        return $this->hasMany(Aset::class);
    }
}