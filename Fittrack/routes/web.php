<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\LatihanController;
use App\Http\Controllers\LangkahController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//route login
Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'process']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// route dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/', [AuthController::class, 'index'])->name('login');

//route barang
Route::resource('/barang', BarangController::class)->middleware('auth');
Route::resource('/mahasiswa', MahasiswaController::class)->middleware('auth');
Route::get('latihan', [LatihanController::class, 'index'])->name('latihan.index');

// Route untuk menampilkan form create latihan
Route::get('latihan/create', [LatihanController::class, 'create'])->name('latihan.create');

// Route untuk menyimpan latihan baru
Route::post('latihan', [LatihanController::class, 'store'])->name('latihan.store');

// Route untuk menampilkan form edit latihan
Route::get('latihan/{id}/edit', [LatihanController::class, 'edit'])->name('latihan.edit');

// Route untuk mengupdate latihan yang sudah ada
Route::put('latihan/{id}', [LatihanController::class, 'update'])->name('latihan.update');

// Route untuk menghapus latihan
Route::delete('latihan/{id}', [LatihanController::class, 'destroy'])->name('latihan.destroy');


// Route untuk Langkah dengan middleware auth
Route::resource('latihan/{latihan_id}/langkah', LangkahController::class)->middleware('auth');
// Route untuk menampilkan daftar users (admin panel)
Route::get('users', [AuthController::class, 'indexUsers'])->name('users.index');

// Route untuk menampilkan form create user (admin panel)
Route::get('users/create', [AuthController::class, 'createUser'])->name('users.create');

// Route untuk menyimpan user baru (admin panel)
Route::post('users', [AuthController::class, 'storeUser'])->name('users.store');

// Route untuk menampilkan form edit user (admin panel)
Route::get('users/{id_user}/edit', [AuthController::class, 'editUser'])->name('users.edit');

// Route untuk mengupdate user yang sudah ada (admin panel)
Route::put('users/{id_user}', [AuthController::class, 'updateUser'])->name('users.update');

// Route untuk menghapus user (admin panel)
Route::delete('users/{id_user}', [AuthController::class, 'destroyUser'])->name('users.destroy');
