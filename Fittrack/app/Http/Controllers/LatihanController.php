<?php

namespace App\Http\Controllers;

use App\Models\Latihan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LatihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latihans = Latihan::latest()->get(); // Ambil semua data latihan, urutkan berdasarkan waktu terbaru
        return view('latihan.index', compact('latihans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('latihan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'nama_latihan' => 'required|string|max:255',
        'gambar_latihan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Simpan file gambar sebagai Base64 jika ada
    $base64Image = $request->hasFile('gambar_latihan') 
        ? base64_encode(file_get_contents($request->file('gambar_latihan')->getRealPath())) 
        : null;

    // Simpan data ke database
    $latihan = Latihan::create([
        'nama_latihan' => $validated['nama_latihan'],
        'gambar_latihan' => $base64Image,
    ]);

    // Membuat file JSON dengan data latihan yang baru
    $latihanData = [
        'id_latihan' => $latihan->id_latihan,
        'nama_latihan' => $latihan->nama_latihan,
        'gambar_latihan' => $latihan->gambar_latihan, // jika perlu
        'created_at' => $latihan->created_at,
        'updated_at' => $latihan->updated_at,
    ];

    // Menentukan path untuk menyimpan file JSON di public/assets
    $filePath = public_path('assets/latihan_data.json');
    
    // Pastikan folder 'assets' ada
    if (!file_exists(public_path('assets'))) {
        mkdir(public_path('assets'), 0775, true);
    }

    // Cek apakah file JSON sudah ada, jika ada kita akan menambahkan data baru ke file tersebut
    $existingData = [];
    if (file_exists($filePath)) {
        $existingData = json_decode(file_get_contents($filePath), true);
    }
    
    // Menambahkan data latihan baru
    $existingData[] = $latihanData;

    // Menyimpan data baru ke dalam file JSON
    file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));

    // Menampilkan alert dan redirect
    Alert::success('Success', 'Latihan berhasil ditambahkan!');
    return redirect()->route('latihan.index');
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $latihan = $this->findLatihan($id);
        return view('latihan.edit', compact('latihan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Validasi input
    $validated = $request->validate([
        'nama_latihan' => 'required|string|max:255',
        'gambar_latihan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $latihan = $this->findLatihan($id);

    // Update gambar sebagai Base64 jika ada gambar baru
    if ($request->hasFile('gambar_latihan')) {
        $validated['gambar_latihan'] = base64_encode(file_get_contents($request->file('gambar_latihan')->getRealPath()));
    } else {
        $validated['gambar_latihan'] = $latihan->gambar_latihan; // Pertahankan gambar lama
    }

    // Update data di database
    $latihan->update($validated);

    // Perbarui file JSON
    $this->updateJsonFile();

    Alert::success('Success', 'Latihan berhasil diperbarui!');
    return redirect()->route('latihan.index');
}

public function destroy($id)
{
    $latihan = $this->findLatihan($id);

    // Hapus data dari database
    $latihan->delete();

    // Perbarui file JSON
    $this->updateJsonFile();

    Alert::success('Success', 'Latihan berhasil dihapus!');
    return redirect()->route('latihan.index');
}

/**
 * Fungsi untuk memperbarui file JSON dengan data terbaru dari database.
 */
private function updateJsonFile()
{
    // Ambil semua data latihan dari database
    $latihans = Latihan::all();

    // Siapkan array data untuk file JSON
    $data = $latihans->map(function ($latihan) {
        return [
            'id_latihan' => $latihan->id_latihan,
            'nama_latihan' => $latihan->nama_latihan,
            'gambar_latihan' => $latihan->gambar_latihan,
            'created_at' => $latihan->created_at,
            'updated_at' => $latihan->updated_at,
        ];
    })->toArray();

    // Menentukan path untuk menyimpan file JSON
    $filePath = public_path('assets/latihan_data.json');

    // Simpan data ke file JSON
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

    /**
     * Cari data latihan atau tampilkan error jika tidak ditemukan.
     */
    private function findLatihan($id)
    {
        return Latihan::findOrFail($id);
    }
}
