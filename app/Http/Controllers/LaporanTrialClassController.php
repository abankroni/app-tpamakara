<?php

namespace App\Http\Controllers;

use App\Models\LaporanTrialClass;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanTrialClassController extends Controller
{
    public function index()
    {
        return LaporanTrialClass::all();
    }

    public function show($id)
    {
        return LaporanTrialClass::findOrFail($id);
    }

    public function store(Request $request)
    {
        $laporan = LaporanTrialClass::create($request->all());
        return response()->json($laporan, 201);
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanTrialClass::findOrFail($id);
        $laporan->update($request->all());
        return response()->json($laporan, 200);
    }

    public function destroy($id)
    {
        LaporanTrialClass::destroy($id);
        return response()->json(null, 204);
    }

    public function preview($id)
    {
        $laporan = LaporanTrialClass::findOrFail($id);

        // Ambil data yang akan ditampilkan di PDF
        $data = [
            'laporan' => $laporan,
            // Tambahkan data lain jika perlu
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.laporan_trial_class', $data);

        // Menampilkan PDF di browser tanpa mengunduh
        return $pdf->stream('Laporan_Trial_Class.pdf');
    }

    public function download($id)
    {
        $laporan = LaporanTrialClass::findOrFail($id);

        $data = [
            'laporan' => $laporan,
        ];

        $pdf = Pdf::loadView('pdf.laporan_trial_class', $data);

        // Unduh file PDF
        return $pdf->download('Laporan_Trial_Class.pdf');
    }

}
