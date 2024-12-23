<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        return Guru::all();
    }

    public function show($id)
    {
        return Guru::findOrFail($id);
    }

    public function store(Request $request)
    {
        $guru = Guru::create($request->all());
        return response()->json($guru, 201);
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        $guru->update($request->all());
        return response()->json($guru, 200);
    }

    public function destroy($id)
    {
        Guru::destroy($id);
        return response()->json(null, 204);
    }
}
