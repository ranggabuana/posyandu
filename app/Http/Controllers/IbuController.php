<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IbuExport;

use App\Traits\HasHierarchicalFilter;

class IbuController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $query = Penduduk::where('kelamin', 'perempuan')
            ->where(function($q) {
                $q->where('status_kawin', '!=', 'belum kawin')
                  ->orWhereIn('nama', function($sub) {
                      $sub->select('nama_ibu')
                          ->from('penduduks')
                          ->whereNotNull('nama_ibu');
                  })
                  ->orWhereIn('nama', function($sub) {
                      $sub->select('nama_ibu')
                          ->from('bayi_balitas')
                          ->whereNotNull('nama_ibu');
                  });
            });
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        $query = $this->applyHierarchicalFilters($query, $request);

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $ibus = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        $filters = $this->getHierarchicalFilterOptions($request);
        
        return view('ibus.index', array_merge(compact('ibus'), $filters));
    }

    public function export(Request $request)
    {
        return Excel::download(new IbuExport($request->all()), 'data_ibu.xlsx');
    }
}
