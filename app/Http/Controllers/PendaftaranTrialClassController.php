<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranTrialClass;
use Illuminate\Http\Request;

class PendaftaranTrialClassController extends Controller
{
    public function index()
    {
        return PendaftaranTrialClass::all();
    }

    public function show($id)
    {
        return PendaftaranTrialClass::findOrFail($id);
    }

    public function store(Request $request)
    {
        $pendaftaran = PendaftaranTrialClass::create($request->all());
        return response()->json($pendaftaran, 201);
    }

    public function update(Request $request, $id)
    {
        $pendaftaran = PendaftaranTrialClass::findOrFail($id);
        $pendaftaran->update($request->all());
        return response()->json($pendaftaran, 200);
    }

    public function destroy($id)
    {
        PendaftaranTrialClass::destroy($id);
        return response()->json(null, 204);
    }
}
