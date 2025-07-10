<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // TAMBAHKAN KODE DI BAWAH INI
    protected $fillable = [
        'name',
    ];
    // BATAS KODE TAMBAHAN
}