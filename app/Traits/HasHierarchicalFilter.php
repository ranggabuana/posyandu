<?php

namespace App\Traits;

use App\Models\Penduduk;
use Illuminate\Http\Request;

trait HasHierarchicalFilter
{
    /**
     * Apply hierarchical filters (Dusun, RW, RT, Kelamin) to a query.
     */
    public function applyHierarchicalFilters($query, Request $request, $tablePrefix = '')
    {
        $prefix = $tablePrefix ? $tablePrefix . '.' : '';
        $user = auth()->user();

        // If user is a Posyandu, restrict to their assigned RWs
        if ($user && $user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $query->whereIn($prefix . 'rw', $rwDiampu);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->dusun) {
            $query->where($prefix . 'dusun', $request->dusun);
        }
        if ($request->rw) {
            $query->where($prefix . 'rw', $request->rw);
        }
        if ($request->rt) {
            $query->where($prefix . 'rt', $request->rt);
        }
        if ($request->kelamin) {
            $query->where($prefix . 'kelamin', $request->kelamin);
        }
        
        return $query;
    }

    /**
     * Get unique values for hierarchical filter dropdowns.
     */
    public function getHierarchicalFilterOptions(Request $request)
    {
        $user = auth()->user();
        $baseQuery = Penduduk::query();

        // If user is a Posyandu, restrict options to their assigned RWs
        if ($user && $user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $baseQuery->whereIn('rw', $rwDiampu);
            }
        }

        $dusuns = (clone $baseQuery)->distinct()->whereNotNull('dusun')->where('dusun', '!=', '')->orderBy('dusun')->pluck('dusun');
        
        $rws = collect();
        $rwQuery = (clone $baseQuery);
        if ($request->dusun) {
            $rwQuery->where('dusun', $request->dusun);
        }
        $rws = $rwQuery->distinct()->whereNotNull('rw')->where('rw', '!=', '')->orderBy('rw')->pluck('rw');

        $rts = collect();
        if ($request->dusun && $request->rw) {
            $rts = (clone $baseQuery)->where('dusun', $request->dusun)->where('rw', $request->rw)->distinct()->whereNotNull('rt')->where('rt', '!=', '')->orderBy('rt')->pluck('rt');
        }

        return compact('dusuns', 'rws', 'rts');
    }
}
