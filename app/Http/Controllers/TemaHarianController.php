<?php

namespace App\Http\Controllers;

use App\Models\TemaHarian;
use Illuminate\Http\Request;

class TemaHarianController extends Controller
{
    public function index()
    {
        return TemaHarian::all();
    }

    public function show($id)
    {
        return TemaHarian::findOrFail($id);
    }

    public function store(Request $request)
    {
        $temaHarian = TemaHarian::create($request->all());
        return response()->json($temaHarian, 201);
    }

    public function update(Request $request, $id)
    {
        $temaHarian = TemaHarian::findOrFail($id);
        $temaHarian->update($request->all());
        return response()->json($temaHarian, 200);
    }

    public function destroy($id)
    {
        TemaHarian::destroy($id);
        return response()->json(null, 204);
    }
}
