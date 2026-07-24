<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengaturanExport;

class PengaturanController extends Controller
{
    private function authorizeAdmin()
    {
        if (auth()->check() && auth()->user()->hasRole('posyandu')) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();
        $defaults = [
            ['key' => 'umur_lansia_min', 'value' => '60', 'label' => 'Batas Umur Minimal Lansia', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'remaja_umur_min', 'value' => '10', 'label' => 'Batas Umur Minimal Remaja', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'remaja_umur_max', 'value' => '18', 'label' => 'Batas Umur Maksimal Remaja', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'wus_umur_min', 'value' => '15', 'label' => 'Batas Umur Minimal WUS', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'wus_umur_max', 'value' => '49', 'label' => 'Batas Umur Maksimal WUS', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'pus_umur_min', 'value' => '15', 'label' => 'Batas Umur Minimal PUS', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'pus_umur_max', 'value' => '49', 'label' => 'Batas Umur Maksimal PUS', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'nama_aplikasi', 'value' => 'Posyandu Melati Sehat', 'label' => 'Nama Aplikasi', 'keterangan' => 'Nama aplikasi yang ditampilkan di header'],
            ['key' => 'nama_desa', 'value' => 'Desa Banjar', 'label' => 'Nama Desa', 'keterangan' => 'Nama desa/kelurahan'],
            ['key' => 'alamat_desa', 'value' => 'Jl. Raya Banjar No. 1, Desa Banjar', 'label' => 'Alamat Desa', 'keterangan' => 'Alamat kantor desa'],
            ['key' => 'moto', 'value' => 'Melayani dengan Hati, Menjaga Kesehatan Komunitas.', 'label' => 'Moto / Slogan', 'keterangan' => 'Slogan yang tampil di footer/hero'],
            ['key' => 'email', 'value' => 'posyandu.melati@gmail.com', 'label' => 'Email Kontak', 'keterangan' => 'Email resmi posyandu'],
            ['key' => 'no_whatsapp', 'value' => '6285123456789', 'label' => 'Nomor WhatsApp', 'keterangan' => 'Nomor WA aktif kader (awali dengan 62)'],
            ['key' => 'logo_desa', 'value' => '', 'label' => 'Logo Desa', 'keterangan' => 'Path file logo'],
            ['key' => 'jam_operasional', 'value' => "Senin – Jumat: 07:30 – 12:00\nSabtu: 07:30 – 11:00\nMinggu & Libur: Tutup", 'label' => 'Jam Operasional', 'keterangan' => 'Jam operasional pelayanan (pisahkan baris dengan Enter)'],
        ];

        foreach ($defaults as $d) {
            Pengaturan::firstOrCreate(['key' => $d['key']], $d);
        }

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
