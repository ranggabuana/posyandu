<?php

namespace App\Exports;

use App\Models\Galeri;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GaleriExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Galeri::query()
            ->leftJoin('posyandus', 'galeries.posyandu_id', '=', 'posyandus.id')
            ->select(
                'galeries.id',
                'posyandus.nama as nama_posyandu',
                'galeries.judul',
                'galeries.foto',
                'galeries.keterangan',
                'galeries.created_at'
            );

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where('galeries.judul', 'like', '%' . $s . '%');
        }

        return $query->orderBy('galeries.created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Posyandu',
            'Judul',
            'Foto',
            'Keterangan',
            'Created At'
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
                $event->sheet->setCellValue('A1', 'LAPORAN DATA GALERI');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                
                $filterDesc = "Filter: ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
            },
        ];
    }
}
