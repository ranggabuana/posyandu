<?php

namespace App\Exports;

use App\Models\LaporanMasyarakat;
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

class LaporanMasyarakatExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize, WithMapping, WithColumnFormatting
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = LaporanMasyarakat::query()
            ->leftJoin('penduduks', 'laporan_masyarakats.nik_pelapor', '=', 'penduduks.nik')
            ->select(
                'laporan_masyarakats.nama_pelapor',
                'laporan_masyarakats.nik_pelapor',
                'laporan_masyarakats.isi_laporan',
                'laporan_masyarakats.kategori',
                'laporan_masyarakats.status',
                'laporan_masyarakats.balasan',
                'penduduks.dusun',
                'penduduks.rw',
                'penduduks.rt',
                'laporan_masyarakats.created_at'
            );

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('laporan_masyarakats.nama_pelapor', 'like', '%' . $s . '%')
                  ->orWhere('laporan_masyarakats.nik_pelapor', 'like', '%' . $s . '%')
                  ->orWhere('laporan_masyarakats.isi_laporan', 'like', '%' . $s . '%');
            });
        }

        if (!empty($this->filters['dusun'])) {
            $query->where('penduduks.dusun', $this->filters['dusun']);
        }
        if (!empty($this->filters['rw'])) {
            $query->where('penduduks.rw', $this->filters['rw']);
        }
        if (!empty($this->filters['rt'])) {
            $query->where('penduduks.rt', $this->filters['rt']);
        }

        return $query->orderBy('laporan_masyarakats.created_at', 'desc');
    }

    public function map($laporan): array
    {
        return [
            $laporan->nama_pelapor,
            $laporan->nik_pelapor . " ",
            $laporan->isi_laporan,
            $laporan->kategori,
            $laporan->status,
            $laporan->balasan,
            $laporan->dusun,
            $laporan->rw,
            $laporan->rt,
            $laporan->created_at ? $laporan->created_at->format('d/m/Y H:i') : '-',
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
            'Nama Pelapor',
            'NIK Pelapor',
            'Isi Laporan',
            'Kategori',
            'Status',
            'Balasan',
            'Dusun',
            'RW',
            'RT',
            'Tanggal Laporan'
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
                $event->sheet->getDelegate()->mergeCells('A1:J1');
                $event->sheet->setCellValue('A1', 'LAPORAN DATA ADUAN MASYARAKAT');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Filters info
                $event->sheet->getDelegate()->mergeCells('A2:J2');
                $filterDesc = "Filter: ";
                $filterDesc .= "Dusun: " . ($this->filters['dusun'] ?? 'Semua') . " | ";
                $filterDesc .= "RW: " . ($this->filters['rw'] ?? 'Semua') . " | ";
                $filterDesc .= "RT: " . ($this->filters['rt'] ?? 'Semua') . " | ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Meta info
                $event->sheet->getDelegate()->mergeCells('A3:J3');
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
                $event->sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $headerRange = 'A5:J5';
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
                $dataRange = 'A5:J' . $lastRow;
                $event->sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                $event->sheet->getStyle('G6:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
