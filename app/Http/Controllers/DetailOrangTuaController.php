<?php

namespace App\Http\Controllers;

use App\Models\DetailOrangTua;
use Illuminate\Http\Request;

class DetailOrangTuaController extends Controller
{
    public function index()
    {
        return DetailOrangTua::all();
    }

    public function show($id)
    {
        return DetailOrangTua::findOrFail($id);
    }

    public function store(Request $request)
    {
        $detailOrangTua = DetailOrangTua::create($request->all());
        return response()->json($detailOrangTua, 201);
    }

    public function update(Request $request, $id)
    {
        $detailOrangTua = DetailOrangTua::findOrFail($id);
        $detailOrangTua->update($request->all());
        return response()->json($detailOrangTua, 200);
    }

    public function destroy($id)
    {
        DetailOrangTua::destroy($id);
        return response()->json(null, 204);
    }
}
