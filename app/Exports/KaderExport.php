<?php

namespace App\Exports;

use App\Models\Kader;
use App\Models\Posyandu;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KaderExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize, WithMapping, WithColumnFormatting
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $user = auth()->user();
        $query = Kader::with(['penduduk', 'posyandu']);

        if ($user && $user->hasRole('posyandu')) {
            $query->where('posyandu_id', $user->posyandu_id);
        } elseif (!empty($this->filters['posyandu_id'])) {
            $query->where('posyandu_id', $this->filters['posyandu_id']);
        }

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->whereHas('penduduk', function ($q) use ($s) {
                $q->where('nama', 'like', '%' . $s . '%')
                  ->orWhere('nik', 'like', '%' . $s . '%');
            });
        }

        return $query->orderBy('id', 'desc');
    }

    public function map($kader): array
    {
        return [
            $kader->id,
            $kader->penduduk->nama ?? '-',
            ($kader->penduduk->nik ?? '-') . " ",
            $kader->posyandu->nama ?? '-',
            $kader->jabatan ?? '-',
            $kader->tanggal_mulai_tugas ? $kader->tanggal_mulai_tugas->format('d/m/Y') : '-',
            $kader->status_aktif ? 'Aktif' : 'Tidak Aktif',
            $kader->pelatihan ?? '-',
            ($kader->penduduk->dusun ?? '-') . ' / RT ' . ($kader->penduduk->rt ?? '-') . ' / RW ' . ($kader->penduduk->rw ?? '-'),
            ($kader->penduduk->telepon ?? '-') . " ",
            $kader->keterangan ?? '-',
            $kader->created_at ? $kader->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kader',
            'NIK',
            'Posyandu',
            'Jabatan',
            'Tgl Mulai Tugas',
            'Status Aktif',
            'Pelatihan',
            'Dusun / RT / RW',
            'Telepon',
            'Keterangan',
            'Tgl Terdaftar',
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
                $event->sheet->getDelegate()->mergeCells('A1:L1');
                $event->sheet->setCellValue('A1', 'LAPORAN DATA KADER POSYANDU');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->mergeCells('A2:L2');
                $user = auth()->user();
                $filterDesc = "Filter: ";
                if ($user && $user->hasRole('posyandu')) {
                    $filterDesc .= "Posyandu: " . ($user->posyandu->nama ?? 'Posyandu Saya') . " | ";
                } elseif (!empty($this->filters['posyandu_id'])) {
                    $posyanduName = Posyandu::where('id', $this->filters['posyandu_id'])->value('nama');
                    $filterDesc .= "Posyandu: " . ($posyanduName ?? 'Semua') . " | ";
                } else {
                    $filterDesc .= "Posyandu: Semua | ";
                }
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->mergeCells('A3:L3');
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
                $event->sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $headerRange = 'A5:L5';
                $event->sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2563EB'],
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

                $event->sheet->getDelegate()->getRowDimension('5')->setRowHeight(25);

                $lastRow = $event->sheet->getHighestRow();
                if ($lastRow >= 5) {
                    $dataRange = 'A5:L' . $lastRow;
                    $event->sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $event->sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getStyle('F6:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
