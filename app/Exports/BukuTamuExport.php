<?php

namespace App\Exports;

use App\Models\BukuTamu;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BukuTamuExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize, WithMapping, WithColumnFormatting
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = BukuTamu::query()
            ->select(
                'nama',
                'instansi',
                'keperluan',
                'no_telepon',
                'tanggal_kunjungan',
                'jam_masuk',
                'jam_keluar',
                'keterangan'
            );

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('nama', 'like', '%' . $s . '%')
                  ->orWhere('instansi', 'like', '%' . $s . '%')
                  ->orWhere('keperluan', 'like', '%' . $s . '%');
            });
        }

        return $query->orderBy('tanggal_kunjungan', 'desc');
    }

    public function map($tamu): array
    {
        return [
            $tamu->nama,
            $tamu->instansi,
            $tamu->keperluan,
            $tamu->no_telepon . " ",
            $tamu->tanggal_kunjungan,
            $tamu->jam_masuk,
            $tamu->jam_keluar,
            $tamu->keterangan,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Instansi',
            'Keperluan',
            'No. Telepon',
            'Tanggal Kunjungan',
            'Jam Masuk',
            'Jam Keluar',
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
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->setCellValue('A1', 'LAPORAN DATA BUKU TAMU');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->mergeCells('A2:H2');
                $filterDesc = "Filter: ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
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
            },
        ];
    }
}
