<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengadaanBarang;

class PengadaanBarangController extends Controller
{
    public function store(Request $request)
    {
        $item = PengadaanBarang::create($request->all());
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = PengadaanBarang::findOrFail($id);
        $item->update($request->all());
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = PengadaanBarang::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }
}
