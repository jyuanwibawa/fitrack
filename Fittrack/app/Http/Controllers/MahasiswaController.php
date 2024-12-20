<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::all();
        return view('mahasiswa.mahasiswa', compact('mahasiswa'));
    }

    public function create()
    {
        return view('mahasiswa.mahasiswacreate');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nim' => 'required|unique:mahasiswa|max:10',
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'angkatan' => 'required|integer|min:1900|max:'.date('Y'),
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        Mahasiswa::create($validatedData);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('mahasiswa.mahasiswaedit', compact('mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validatedData = $request->validate([
            'nim' => 'sometimes|unique:mahasiswa,nim,'.$id.'|max:10',
            'nama' => 'sometimes|string|max:255',
            'jurusan' => 'sometimes|string|max:255',
            'angkatan' => 'sometimes|integer|min:1900|max:'.date('Y'),
            'jenis_kelamin' => 'sometimes|in:L,P',
        ]);

        $mahasiswa->update($validatedData);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
