<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengaturanExport;

class PengaturanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaturan::query();
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('key', 'like', '%' . $request->search . '%')
                  ->orWhere('label', 'like', '%' . $request->search . '%')
                  ->orWhere('value', 'like', '%' . $request->search . '%');
            });
        }

        $sortField = $request->get('sort', 'key');
        $sortDirection = $request->get('direction', 'asc');
        $perPage = $request->get('per_page', 10);

        $pengaturans = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();

        return view('pengaturans.index', compact('pengaturans'));
    }

    public function edit(Pengaturan $pengaturan)
    {
        return view('pengaturans.edit', compact('pengaturan'));
    }

    public function update(Request $request, Pengaturan $pengaturan)
    {
        if ($pengaturan->key === 'logo_desa') {
            $validated = $request->validate([
                'value' => 'nullable|image|max:2048',
                'label' => 'nullable|string|max:255',
                'keterangan' => 'nullable|string',
            ]);
            
            if ($request->hasFile('value')) {
                if ($pengaturan->value && !\Illuminate\Support\Str::startsWith($pengaturan->value, ['http://', 'https://'])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($pengaturan->value);
                }
                
                $path = $request->file('value')->store('logos', 'public');
                $pengaturan->value = $path;
            } elseif ($request->remove_logo == 1) {
                if ($pengaturan->value && !\Illuminate\Support\Str::startsWith($pengaturan->value, ['http://', 'https://'])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($pengaturan->value);
                }
                $pengaturan->value = null;
            }
            
            $pengaturan->label = $validated['label'] ?? $pengaturan->label;
            $pengaturan->keterangan = $validated['keterangan'] ?? $pengaturan->keterangan;
            $pengaturan->save();
        } else {
            $validated = $request->validate([
                'value' => 'nullable|string|max:255',
                'label' => 'nullable|string|max:255',
                'keterangan' => 'nullable|string',
            ]);
            
            $pengaturan->update($validated);
        }
        
        return redirect()->route('pengaturans.index')->with('success', 'Data berhasil diubah');
    }

    public function export(Request $request)
    {
        return Excel::download(new PengaturanExport($request->all()), 'pengaturans.xlsx');
    }
}
