<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\Pengaturan;
use App\Traits\HasHierarchicalFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WusExport;

class WusController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $minAge = Pengaturan::where('key', 'wus_umur_min')->value('value') ?? 15;
        $maxAge = Pengaturan::where('key', 'wus_umur_max')->value('value') ?? 49;

        $query = Penduduk::query()
            ->select('*')
            ->selectRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) AS umur")
            ->where('kelamin', 'perempuan')
            ->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        $query = $this->applyHierarchicalFilters($query, $request);

        $sortField = $request->get('sort', 'nama');
        $sortDirection = $request->get('direction', 'asc');
        $perPage = $request->get('per_page', 10);

        $wuses = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        $filters = $this->getHierarchicalFilterOptions($request);
        return view('wuses.index', array_merge(compact('wuses', 'minAge', 'maxAge'), $filters));
    }

    public function export(Request $request)
    {
        return Excel::download(new WusExport($request->all()), 'wuses.xlsx');
    }
}
