<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Langkah extends Model
{
    use HasFactory;

    protected $table = 'langkah'; // Menentukan nama tabel jika berbeda dari default plural

    protected $primaryKey = 'id_langkah'; // Menentukan primary key

    protected $fillable = [
        'id_latihan', 'nama_step', 'gambar_step', 'interval', // Menentukan field yang dapat diisi
    ];

    /**
     * Get the latihan that owns the langkah.
     */
    public function latihan()
    {
        return $this->belongsTo(Latihan::class, 'id_latihan', 'id_latihan');
    }
}
