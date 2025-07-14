<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $guarded = ['id']; // Izinkan semua kolom diisi

    // Definisikan relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}