<?php

namespace App\Http\Controllers;

use App\Models\LaporanHarian;
use Illuminate\Http\Request;

class LaporanHarianController extends Controller
{
    public function index()
    {
        return LaporanHarian::all();
    }

    public function show($id)
    {
        return LaporanHarian::findOrFail($id);
    }

    public function store(Request $request)
    {
        $laporan = LaporanHarian::create($request->all());
        return response()->json($laporan, 201);
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanHarian::findOrFail($id);
        $laporan->update($request->all());
        return response()->json($laporan, 200);
    }

    public function destroy($id)
    {
        LaporanHarian::destroy($id);
        return response()->json(null, 204);
    }
}
