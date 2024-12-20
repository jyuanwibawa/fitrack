<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        $filePath = public_path('assets/users_data.json');

        // Jika request adalah POST (menyimpan data baru)
        if ($request->isMethod('post')) {
            // Validasi data yang diterima dari request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8',
            ]);

            // Ambil data dari cache atau file
            $data = Cache::remember('users_data', 60, function () use ($filePath) {
                return File::exists($filePath) ? json_decode(File::get($filePath), true) : [];
            });

            // Validasi username dan email secara manual
            if (!$this->isUnique($data, 'username', $validated['username'])) {
                return response()->json(['error' => 'Username sudah digunakan'], 422);
            }

            if (!$this->isUnique($data, 'email', $validated['email'])) {
                return response()->json(['error' => 'Email sudah digunakan'], 422);
            }

            // Tambahkan data baru
            $validated['role'] = 'user';
            $validated['created_at'] = now()->format('Y-m-d H:i:s');
            $validated['updated_at'] = now()->format('Y-m-d H:i:s');
            $validated['id_user'] = count($data) + 1; // ID dibuat berdasarkan jumlah data
            $data[] = $validated;

            // Simpan data dengan lock untuk menghindari konflik penulisan
            Cache::lock('users_data_write_lock', 10)->get(function () use ($filePath, $data) {
                File::put($filePath, json_encode($data, JSON_PRETTY_PRINT));
                Cache::put('users_data', $data, 60);
            });

            return response()->json($data, 201);
        }

        // Jika request adalah GET (mengambil data)
        if (!File::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $data = Cache::remember('users_data', 60, function () use ($filePath) {
            return json_decode(File::get($filePath), true);
        });

        // Pagination jika parameter page ada
        if ($request->has('page')) {
            $page = (int) $request->query('page', 1);
            $perPage = 10; // Jumlah data per halaman
            $paginatedData = array_slice($data, ($page - 1) * $perPage, $perPage);
            return response()->json($paginatedData);
        }

        return response()->json($data);
    }

    /**
     * Fungsi untuk mengecek apakah data unik berdasarkan kunci tertentu.
     */
    private function isUnique($data, $key, $value)
    {
        foreach ($data as $item) {
            if ($item[$key] === $value) {
                return false;
            }
        }
        return true;
    }
}
