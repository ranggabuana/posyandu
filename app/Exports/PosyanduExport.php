<?php

namespace App\Exports;

use App\Models\Posyandu;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PosyanduExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Posyandu::query()
            ->select(
                'nama',
                'alamat'
            );

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('nama', 'like', '%' . $s . '%')
                  ->orWhere('alamat', 'like', '%' . $s . '%');
            });
        }

        return $query->orderBy('nama', 'asc');
    }

    public function headings(): array
    {
        return [
            'Nama Posyandu',
            'Alamat'
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
                $event->sheet->setCellValue('A1', 'LAPORAN DATA POSYANDU');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                
                $filterDesc = "Filter: ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
            },
        ];
    }
}
