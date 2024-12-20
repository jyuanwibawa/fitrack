<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Latihan extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika berbeda dari default plural
    protected $table = 'latihan';

    // Menentukan primary key
    protected $primaryKey = 'id_latihan';

    // Menentukan apakah primary key menggunakan auto increment
    public $incrementing = true;

    // Menentukan tipe data primary key
    protected $keyType = 'int';

    // Menentukan apakah timestamps diaktifkan
    public $timestamps = true;

    // Field yang dapat diisi (mass assignable)
    protected $fillable = [
        'nama_latihan',
        'gambar_latihan', // Tambahkan kolom jika ada
    ];

    // Field yang disembunyikan saat serialisasi model
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Field yang di-cast ke tipe tertentu
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi dengan model Langkah.
     *
     * @return HasMany
     */
    public function langkahs(): HasMany
    {
        return $this->hasMany(Langkah::class, 'id_latihan', 'id_latihan');
    }

    /**
     * Akses URL gambar latihan jika ada.
     *
     * @return string|null
     */
    public function getGambarLatihanUrlAttribute(): ?string
    {
        return $this->gambar_latihan
            ? asset('storage/' . $this->gambar_latihan)
            : null;
    }

    /**
     * Scope untuk pencarian berdasarkan nama latihan.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nama
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCariNama($query, string $nama)
    {
        return $query->where('nama_latihan', 'LIKE', "%$nama%");
    }

    /**
     * Mutator untuk menyimpan nama latihan dalam format title case.
     *
     * @param string $value
     */
    public function setNamaLatihanAttribute(string $value)
    {
        $this->attributes['nama_latihan'] = ucwords(strtolower($value));
    }
}
