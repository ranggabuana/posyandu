<?php

namespace App\Exports;

use App\Models\Pus;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Carbon;

class PusExport implements FromQuery, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, ShouldAutoSize, WithColumnFormatting
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Pus::with(['suami', 'istri', 'posyandu']);

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->whereHas('suami', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%');
            })->orWhereHas('istri', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }

        if (!empty($this->filters['posyandu_id'])) {
            $query->where('posyandu_id', $this->filters['posyandu_id']);
        }
        
        return $query;
    }

    public function headings(): array
    {
        return [
            'Nama Suami',
            'NIK Suami',
            'Umur Suami',
            'Nama Istri',
            'NIK Istri',
            'Umur Istri',
            'Posyandu',
            'Keterangan',
        ];
    }

    public function map($pus): array
    {
        $umurSuami = $pus->suami ? Carbon::parse($pus->suami->tanggallahir)->age . ' Tahun' : '-';
        $umurIstri = $pus->istri ? Carbon::parse($pus->istri->tanggallahir)->age . ' Tahun' : '-';

        return [
            $pus->suami->nama ?? '-',
            ($pus->suami->nik ?? '-') . " ",
            $umurSuami,
            $pus->istri->nama ?? '-',
            ($pus->istri->nik ?? '-') . " ",
            $umurIstri,
            $pus->posyandu->nama ?? '-',
            $pus->keterangan ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
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
                // Title
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->setCellValue('A1', 'LAPORAN DATA PASANGAN USIA SUBUR (PUS)');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Filters info
                $event->sheet->getDelegate()->mergeCells('A2:H2');
                $filterDesc = "Filter: ";
                $filterDesc .= "Posyandu: " . ($this->filters['posyandu_id'] ?? 'Semua') . " | ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Meta info
                $event->sheet->getDelegate()->mergeCells('A3:H3');
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
                $event->sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $headerRange = 'A5:H5';
                $event->sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0F9A7B'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $event->sheet->getDelegate()->getRowDimension('5')->setRowHeight(25);

                $lastRow = $event->sheet->getHighestRow();
                $dataRange = 'A5:H' . $lastRow;
                $event->sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                $event->sheet->getStyle('C6:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('F6:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
