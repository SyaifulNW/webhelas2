<?php

namespace App\Http\Controllers;

use App\Models\MonitoringPerbaikan;
use Illuminate\Http\Request;

class MonitoringPerbaikanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fasilitas' => 'nullable|string',
            'kerusakan' => 'nullable|string',
            'timeline'  => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'progress'   => 'nullable|string',
            'rencana'   => 'nullable|string',
            'budget'    => 'nullable|numeric',
        ]);

        $item = MonitoringPerbaikan::create($validated);
        return response()->json(['success' => true, 'message' => 'Data berhasil ditambahkan.', 'data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'fasilitas' => 'nullable|string',
            'kerusakan' => 'nullable|string',
            'timeline'  => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'progress'   => 'nullable|string',
            'rencana'   => 'nullable|string',
            'budget'    => 'nullable|numeric',
        ]);

        $item = MonitoringPerbaikan::findOrFail($id);
        $item->update($validated);

        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = MonitoringPerbaikan::findOrFail($id);
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }
}
