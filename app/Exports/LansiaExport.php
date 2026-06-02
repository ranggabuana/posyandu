<?php

namespace App\Exports;

use App\Models\Penduduk;
use App\Models\Pengaturan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LansiaExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize, WithMapping, WithColumnFormatting
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $minAge = Pengaturan::where('key', 'umur_lansia_min')->value('value') ?? 60;

        $query = Penduduk::query()
            ->select('nama', 'nik', 'tanggallahir', 'dusun', 'rw', 'rt', 'kelamin')
            ->selectRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) AS umur")
            ->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= ?", [$minAge]);

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('nama', 'like', '%' . $s . '%')
                  ->orWhere('nik', 'like', '%' . $s . '%');
            });
        }

        if (!empty($this->filters['dusun'])) {
            $query->where('dusun', $this->filters['dusun']);
        }
        if (!empty($this->filters['rw'])) {
            $query->where('rw', $this->filters['rw']);
        }
        if (!empty($this->filters['rt'])) {
            $query->where('rt', $this->filters['rt']);
        }

        return $query->orderBy('nama', 'asc');
    }

    public function map($lansia): array
    {
        return [
            $lansia->nama,
            $lansia->nik . " ",
            $lansia->tanggallahir,
            $lansia->dusun,
            $lansia->rw,
            $lansia->rt,
            $lansia->kelamin == 'laki-laki' ? 'L' : 'P',
            $lansia->umur,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIK',
            'Tanggal Lahir',
            'Dusun',
            'RW',
            'RT',
            'L/P',
            'Umur (Tahun)'
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
                $minAge = Pengaturan::where('key', 'umur_lansia_min')->value('value') ?? 60;
                // Title
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->setCellValue('A1', 'LAPORAN DATA LANSIA (USIA >= ' . $minAge . ' TAHUN)');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Filters info
                $event->sheet->getDelegate()->mergeCells('A2:H2');
                $filterDesc = "Filter: ";
                $filterDesc .= "Dusun: " . ($this->filters['dusun'] ?? 'Semua') . " | ";
                $filterDesc .= "RW: " . ($this->filters['rw'] ?? 'Semua') . " | ";
                $filterDesc .= "RT: " . ($this->filters['rt'] ?? 'Semua') . " | ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Meta info
                $event->sheet->getDelegate()->mergeCells('A3:H3');
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
                $event->sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
            AfterSheet::class => function(AfterSheet $event) {
                // Style the header row (A5:H5)
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
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Set row height for header
                $event->sheet->getDelegate()->getRowDimension('5')->setRowHeight(25);

                // Borders for data
                $lastRow = $event->sheet->getHighestRow();
                $dataRange = 'A5:H' . $lastRow;
                $event->sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Align content
                $event->sheet->getStyle('D6:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Dusun, RW, RT
            },
        ];
    }
}
