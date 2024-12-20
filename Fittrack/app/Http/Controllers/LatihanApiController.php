<?php
// app/Http/Controllers/LatihanApiController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LatihanApiController extends Controller
{
    /**
     * Mengembalikan data latihan dari file JSON
     */
    public function getLatihanData()
    {
        // Tentukan path file JSON
        $filePath = public_path('assets/latihan_data.json');

        // Cek apakah file JSON ada
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File JSON tidak ditemukan'], 404);
        }

        // Baca data dari file JSON
        $jsonData = file_get_contents($filePath);

        // Ubah data JSON menjadi array
        $data = json_decode($jsonData, true);

        // Kembalikan data sebagai JSON
        return response()->json($data);
    }
}
