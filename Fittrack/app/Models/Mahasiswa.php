<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Menentukan nama tabel, jika berbeda dengan nama model (opsional)
    protected $table = 'mahasiswa';

    // Menentukan kolom yang boleh diisi (fillable)
    protected $fillable = [
        'nim',
        'nama',
        'jurusan',
        'angkatan',
        'jenis_kelamin',
    ];

    // Menentukan kolom yang tidak boleh diisi (guarded)
    // protected $guarded = ['id_mahasiswa'];

    // Menentukan tipe data untuk kolom tertentu jika diperlukan (opsional)
    protected $casts = [
        'angkatan' => 'integer', // Jika angkatan seharusnya disimpan sebagai integer
    ];
}
