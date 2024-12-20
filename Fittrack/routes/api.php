<?php

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

use App\Http\Controllers\LatihanController;
// routes/api.php

use App\Http\Controllers\LatihanApiController;
use App\Http\Controllers\Api\UserApiController;

Route::get('users', [UserApiController::class, 'index']);
Route::post('users', [UserApiController::class, 'index']);


Route::get('latihan-data', [LatihanApiController::class, 'getLatihanData']);



// Rute untuk mendapatkan daftar latihan (GET)
Route::get('latihans', [LatihanController::class, 'index']);

// Rute untuk mendapatkan detail latihan berdasarkan ID (GET)
Route::get('latihan/{id}', [LatihanController::class, 'show']);

// Rute untuk membuat latihan baru (POST)
Route::post('latihan', [LatihanController::class, 'store']);

// Rute untuk mengupdate latihan berdasarkan ID (PUT/PATCH)
Route::put('latihan/{id}', [LatihanController::class, 'update']);

// Rute untuk menghapus latihan berdasarkan ID (DELETE)
Route::delete('latihan/{id}', [LatihanController::class, 'destroy']);


Route::prefix('barang')->group(function () {
    Route::get('/', function (Request $request) {
        $barangs = Barang::all();
        if ($barangs->isEmpty()) {
            return [
                'data' => [],
                'status' => 'error',
                'code' => 404
            ];
        }
        return [
            'data' => $barangs,
            'status' => 'success',
            'code' => 200
        ];
    });
    Route::get('/{id}', function (Request $request, $id) {
        $barangs = Barang::find($id);
        if ($barangs == null) {
            return [
                'data' => [],
                'status' => 'error',
                'code' => 404
            ];
        }
        return [
            'data' => $barangs,
            'status' => 'success',
            'code' => 200
        ];
    });
    
    Route::patch('/{id}', function (Request $request, $id) {
        
          $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan',
            ], 404);
        }

   
        if ($barang->stock < $request->jumlah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak cukup',
            ], 400);
        }

        $barang->stock -= $request->jumlah;
        $barang->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Stok berhasil diperbarui',
            'updated_stock' => $barang->stock,
        ], 200);
    });
    
    
});

