<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $barang = Barang::count(); // Menghitung jumlah barang
        $mahasiswa = Mahasiswa::count(); // Menghitung jumlah mahasiswa
    
        return view('dashboard.dashboard', compact('barang', 'mahasiswa'));
    }
}
