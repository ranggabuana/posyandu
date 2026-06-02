<?php

namespace App\Exports;

use App\Models\Pengaturan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PengaturanExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Pengaturan::query()
            ->select(
                'key',
                'value',
                'label',
                'keterangan'
            );

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('key', 'like', '%' . $s . '%')
                  ->orWhere('label', 'like', '%' . $s . '%')
                  ->orWhere('value', 'like', '%' . $s . '%');
            });
        }

        return $query->orderBy('key', 'asc');
    }

    public function headings(): array
    {
        return [
            'Key',
            'Value',
            'Label',
            'Keterangan'
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
                $event->sheet->setCellValue('A1', 'LAPORAN DATA PENGATURAN');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                
                $filterDesc = "Filter: ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
            },
        ];
    }
}
