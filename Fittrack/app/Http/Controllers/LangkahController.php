<?php

namespace App\Http\Controllers;

use App\Models\Latihan;
use App\Models\Langkah;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LangkahController extends Controller
{
    // Menampilkan semua langkah berdasarkan latihan
    public function index($latihan_id)
    {
        $latihan = Latihan::findOrFail($latihan_id);
        $langkahs = Langkah::where('id_latihan', $latihan_id)->get();
        return view('langkah.index', compact('latihan', 'langkahs'));
    }

    // Menampilkan form untuk menambah langkah
    public function create($latihan_id)
    {
        $latihan = Latihan::findOrFail($latihan_id);
        return view('langkah.create', compact('latihan'));
    }

    // Menyimpan langkah baru
    public function store(Request $request, $latihan_id)
    {
        $request->validate([
            'nama_step' => 'required|string|max:255',
            'gambar_step' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'interval' => 'required|string',
        ]);

        $gambar_step = null;
        if ($request->hasFile('gambar_step')) {
            $gambar_step = $request->file('gambar_step')->store('images');
        }

        Langkah::create([
            'id_latihan' => $latihan_id,
            'nama_step' => $request->nama_step,
            'gambar_step' => $gambar_step,
            'interval' => $request->interval,
        ]);

        Alert::success('Success', 'Langkah added successfully!');
        return redirect()->route('langkah.index', ['latihan_id' => $latihan_id]);
    }

    // Menampilkan form untuk mengedit langkah
    public function edit($latihan_id, $id)
    {
        $latihan = Latihan::findOrFail($latihan_id);
        $langkah = Langkah::findOrFail($id);
        return view('langkah.edit', compact('latihan', 'langkah'));
    }

    // Menyimpan perubahan langkah
    public function update(Request $request, $latihan_id, $id)
    {
        $request->validate([
            'nama_step' => 'required|string|max:255',
            'gambar_step' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'interval' => 'required|string',
        ]);

        $langkah = Langkah::findOrFail($id);
        $gambar_step = $langkah->gambar_step;
        
        if ($request->hasFile('gambar_step')) {
            $gambar_step = $request->file('gambar_step')->store('images');
        }

        $langkah->update([
            'nama_step' => $request->nama_step,
            'gambar_step' => $gambar_step,
            'interval' => $request->interval,
        ]);

        Alert::success('Success', 'Langkah updated successfully!');
        return redirect()->route('langkah.index', ['latihan_id' => $latihan_id]);
    }

    // Menghapus langkah
    public function destroy($latihan_id, $id)
    {
        $langkah = Langkah::findOrFail($id);
        $langkah->delete();

        Alert::success('Success', 'Langkah deleted successfully!');
        return redirect()->route('langkah.index', ['latihan_id' => $latihan_id]);
    }
}
