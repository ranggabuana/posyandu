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
                'tanggal_kunjungan',
                'nama',
                'jabatan',
                'alamat',
                'keperluan',
                'keterangan'
            );

        if (!empty($this->filters['posyandu_id'])) {
            $query->where('posyandu_id', $this->filters['posyandu_id']);
        }

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('nama', 'like', '%' . $s . '%')
                  ->orWhere('jabatan', 'like', '%' . $s . '%')
                  ->orWhere('alamat', 'like', '%' . $s . '%')
                  ->orWhere('keperluan', 'like', '%' . $s . '%');
            });
        }

        return $query->orderBy('tanggal_kunjungan', 'desc');
    }

    public function map($tamu): array
    {
        $data = [
            $tamu->tanggal_kunjungan ? \Carbon\Carbon::parse($tamu->tanggal_kunjungan)->format('d/m/Y') : '-',
            $tamu->nama,
            $tamu->jabatan,
            $tamu->alamat,
            $tamu->keperluan,
            $tamu->keterangan,
        ];

        $user = auth()->user();
        if (!($user && $user->hasRole('posyandu') && $user->posyandu_id)) {
            $data[] = $tamu->posyandu->nama ?? 'Umum/Semua';
        }

        return $data;
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function headings(): array
    {
        $headers = [
            'Tanggal',
            'Nama Lengkap',
            'Jabatan',
            'Alamat',
            'Tujuan',
            'Kesan / Pesan'
        ];

        $user = auth()->user();
        if (!($user && $user->hasRole('posyandu') && $user->posyandu_id)) {
            $headers[] = 'Posyandu';
        }

        return $headers;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function registerEvents(): array
    {
        $user = auth()->user();
        $isPosyandu = $user && $user->hasRole('posyandu') && $user->posyandu_id;
        $maxCol = $isPosyandu ? 'F' : 'G';

        return [
            BeforeSheet::class => function(BeforeSheet $event) use ($maxCol) {
                $event->sheet->getDelegate()->mergeCells("A1:{$maxCol}1");
                $event->sheet->setCellValue('A1', 'LAPORAN DATA BUKU TAMU');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->mergeCells("A2:{$maxCol}2");
                $filterDesc = "Filter: ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->mergeCells("A3:{$maxCol}3");
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
                $event->sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
            AfterSheet::class => function(AfterSheet $event) use ($maxCol) {
                $headerRange = "A5:{$maxCol}5";
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
                $dataRange = "A5:{$maxCol}" . $lastRow;
                $event->sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Center align specific columns (Tanggal)
                $event->sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
