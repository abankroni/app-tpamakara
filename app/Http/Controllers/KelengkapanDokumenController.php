<?php

namespace App\Http\Controllers;

use App\Models\KelengkapanDokumen; // Pastikan model KelengkapanDokumen sudah ada
use Illuminate\Http\Request;

class KelengkapanDokumenController extends Controller
{
    // Menampilkan semua kelengkapan dokumen
    public function index()
    {
        return KelengkapanDokumen::with('siswa')->get(); // Mengambil data dengan relasi siswa
    }

    // Menampilkan kelengkapan dokumen berdasarkan ID
    public function show($id)
    {
        return KelengkapanDokumen::with('siswa')->findOrFail($id);
    }

    // Menyimpan kelengkapan dokumen baru
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'siswa_id' => 'required|exists:siswa,id', // Memastikan siswa_id valid
            'akta_lahir_anak' => 'nullable|string|max:255',
            'kartu_keluarga' => 'nullable|string|max:255',
            'ktp_orang_tua' => 'nullable|string|max:255',
            'npwp_orang_tua' => 'nullable|string|max:255',
            'foto_anak' => 'nullable|string|max:255',
        ]);

        $kelengkapanDokumen = KelengkapanDokumen::create($validatedData);
        return response()->json($kelengkapanDokumen, 201);
    }

    // Memperbarui kelengkapan dokumen yang ada
    public function update(Request $request, $id)
    {
        $kelengkapanDokumen = KelengkapanDokumen::findOrFail($id);

        // Validasi data yang diterima
        $validatedData = $request->validate([
            'siswa_id' => 'sometimes|required|exists:siswa,id',
            'akta_lahir_anak' => 'nullable|string|max:255',
            'kartu_keluarga' => 'nullable|string|max:255',
            'ktp_orang_tua' => 'nullable|string|max:255',
            'npwp_orang_tua' => 'nullable|string|max:255',
            'foto_anak' => 'nullable|string|max:255',
        ]);

        $kelengkapanDokumen->update($validatedData);
        return response()->json($kelengkapanDokumen, 200);
    }

    // Menghapus kelengkapan dokumen berdasarkan ID
    public function destroy($id)
    {
        KelengkapanDokumen::destroy($id);
        return response()->json(null, 204);
    }
}
