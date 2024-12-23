<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        return Siswa::all();
    }

    public function show($id)
    {
        return Siswa::findOrFail($id);
    }

    public function store(Request $request)
    {
        $siswa = Siswa::create($request->all());
        return response()->json($siswa, 201);
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->all());
        return response()->json($siswa, 200);
    }

    public function destroy($id)
    {
        Siswa::destroy($id);
        return response()->json(null, 204);
    }
}
