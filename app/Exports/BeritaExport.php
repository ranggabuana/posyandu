<?php

namespace App\Exports;

use App\Models\Berita;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BeritaExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $user = auth()->user();
        $query = Berita::query();

        if ($user && $user->hasRole('posyandu') && $user->posyandu_id) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('posyandu_id', $user->posyandu_id);
            });
        }

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('judul', 'like', '%' . $s . '%')
                  ->orWhere('kategori', 'like', '%' . $s . '%');
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Slug',
            'Konten',
            'Gambar',
            'Kategori',
            'Penulis ID',
            'Status',
            'Created At',
            'Updated At'
        ];
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $event->sheet->setCellValue('A1', 'LAPORAN DATA BERITA');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                
                $filterDesc = "Filter: ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
            },
        ];
    }
}
