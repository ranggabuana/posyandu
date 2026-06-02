<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendudukExport;
use App\Traits\HasHierarchicalFilter;

class PendudukController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $query = Penduduk::query();
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%')
                  ->orWhere('no_kk', 'like', '%' . $request->search . '%');
            });
        }

        $query = $this->applyHierarchicalFilters($query, $request);

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $penduduks = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        // Fetch unique values for filters
        $filters = $this->getHierarchicalFilterOptions($request);
        $dusuns = $filters['dusuns'];
        $rws = $filters['rws'];
        $rts = $filters['rts'];
        
        return view('penduduks.index', compact('penduduks', 'dusuns', 'rws', 'rts'));
    }

    public function create()
    {
        return view('penduduks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_kk' => 'required|string|max:20',
            'nik' => 'required|string|max:20|unique:penduduks,nik',
            'nama_kk' => 'required|string|max:255',
            'hubungan_keluarga' => 'required|string|max:100',
            'kelamin' => 'required|in:laki-laki,perempuan',
            'tempatlahir' => 'required|string|max:100',
            'tanggallahir' => 'required|date',
            'agama' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'status_kawin' => 'required|in:belum kawin,kawin,cerai hidup,cerai mati',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'goldar' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'alamat' => 'nullable|string',
            'rw' => 'nullable|string|max:5',
            'rt' => 'nullable|string|max:5',
            'dusun' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'bpjs' => 'nullable|boolean',
        ]);
        
        $validated['bpjs'] = $request->has('bpjs');
        
        Penduduk::create($validated);
        return redirect()->route('penduduks.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Penduduk $penduduk)
    {
        return view('penduduks.edit', compact('penduduk'));
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_kk' => 'required|string|max:20',
            'nik' => 'required|string|max:20|unique:penduduks,nik,' . $penduduk->id,
            'nama_kk' => 'required|string|max:255',
            'hubungan_keluarga' => 'required|string|max:100',
            'kelamin' => 'required|in:laki-laki,perempuan',
            'tempatlahir' => 'required|string|max:100',
            'tanggallahir' => 'required|date',
            'agama' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'status_kawin' => 'required|in:belum kawin,kawin,cerai hidup,cerai mati',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'goldar' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'alamat' => 'nullable|string',
            'rw' => 'nullable|string|max:5',
            'rt' => 'nullable|string|max:5',
            'dusun' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'bpjs' => 'nullable|boolean',
        ]);

        $validated['bpjs'] = $request->has('bpjs');

        $penduduk->update($validated);
        return redirect()->route('penduduks.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();
        return redirect()->route('penduduks.index')->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new PendudukExport($request->all()), 'penduduks.xlsx');
    }
}
