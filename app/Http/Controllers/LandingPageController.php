<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\IbuHamil;
use App\Models\BayiBalita;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Posyandu;
use App\Models\Pengaturan;
use App\Models\Wus;
use App\Models\LaporanMasyarakat;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $settings = Pengaturan::pluck('value', 'key');
        
        $stats = [
            'total_penduduk' => Penduduk::count(),
            'ibu_hamil' => IbuHamil::where('status', 'aktif')->count(),
            'balita' => BayiBalita::count(),
            'wus' => Wus::count(),
        ];

        $minAgeLansia = $settings['umur_lansia_min'] ?? 60;
        $stats['lansia'] = Penduduk::whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= ?", [$minAgeLansia])->count();

        $beritas = Berita::latest()->take(3)->get();
        $galeries = Galeri::latest()->take(4)->get();
        $posyandus = Posyandu::orderBy('nama')->get();
        $jadwals = \App\Models\Jadwal::all(); // Provide schedules to landing view
        $tims = \App\Models\Tim::all(); // Provide team to landing view

        return view('landing', compact('settings', 'stats', 'beritas', 'galeries', 'posyandus', 'jadwals', 'tims'));
    }

    public function news(Request $request)
    {
        $settings = Pengaturan::pluck('value', 'key');
        $query = Berita::where('status', 'publikasi');
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%');
            });
        }

        $beritas = $query->latest()->paginate(6);
        return view('public.news.index', compact('settings', 'beritas'));
    }

    public function newsDetail($id)
    {
        $settings = Pengaturan::pluck('value', 'key');
        // Accept either slug or id for better SEO
        $berita = Berita::where('status', 'publikasi')
            ->where(function($q) use ($id) {
                $q->where('id', $id)->orWhere('slug', $id);
            })->firstOrFail();
            
        $recent_news = Berita::where('status', 'publikasi')->where('id', '!=', $berita->id)->latest()->take(5)->get();
        
        return view('public.news.show', compact('settings', 'berita', 'recent_news'));
    }

    public function aduan()
    {
        $settings = Pengaturan::pluck('value', 'key');
        
        // Simple Math Captcha
        $n1 = rand(1, 9);
        $n2 = rand(1, 9);
        session(['captcha_result' => $n1 + $n2]);
        $captcha_question = "$n1 + $n2 = ?";

        return view('public.aduan', compact('settings', 'captcha_question'));
    }

    public function refreshCaptcha()
    {
        $n1 = rand(1, 9);
        $n2 = rand(1, 9);
        session(['captcha_result' => $n1 + $n2]);
        return response()->json(['question' => "$n1 + $n2 = ?"]);
    }

    public function submitAduan(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'nik_pelapor' => 'required|string|size:16',
            'no_telepon' => 'required|string|max:20',
            'isi_laporan' => 'required|string',
            'kategori' => 'required|string|max:100',
            'foto_bukti' => 'nullable|image|max:2048',
            'captcha' => 'required|integer|in:' . session('captcha_result'),
        ], [
            'nik_pelapor.size' => 'NIK harus berjumlah 16 digit.',
            'captcha.in' => 'Jawaban captcha salah.',
            'no_telepon.required' => 'No. Telepon wajib diisi.',
        ]);

        // Remove captcha from validated data — it's only for validation, not for storage
        unset($validated['captcha']);

        $validated['status'] = 'baru';
        
        // Clean session after use
        session()->forget('captcha_result');

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('laporan', 'public');
        }

        LaporanMasyarakat::create($validated);

        return redirect()->back()->with('success', 'Laporan berhasil terkirim!');
    }

    public function cekStatusAduan(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
        ]);

        $laporans = LaporanMasyarakat::where('nik_pelapor', $request->nik)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'nama_pelapor', 'isi_laporan', 'kategori', 'status', 'balasan', 'created_at']);

        return response()->json($laporans);
    }

    public function gallery(Request $request)
    {
        $settings = Pengaturan::pluck('value', 'key');
        $posyandus = Posyandu::has('galeries')->orderBy('nama')->get();
        
        // If there are no galleries, we can fall back to all posyandus
        if ($posyandus->isEmpty()) {
            $posyandus = Posyandu::orderBy('nama')->get();
        }

        $query = Galeri::with('posyandu');

        if ($request->filled('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }

        $galeries = $query->latest()->paginate(6)->withQueryString();
        return view('public.gallery.index', compact('settings', 'galeries', 'posyandus'));
    }
}
