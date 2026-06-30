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
                'laporan_masyarakats.no_telepon',
                'laporan_masyarakats.isi_laporan',
                'laporan_masyarakats.kategori',
                'laporan_masyarakats.status',
                'laporan_masyarakats.balasan',
                'laporan_masyarakats.hari_tanggal',
                'laporan_masyarakats.alamat',
                'penduduks.dusun',
                'penduduks.rw',
                'penduduks.rt',
                'laporan_masyarakats.created_at',
                'laporan_masyarakats.posyandu_id'
            );

        if (!empty($this->filters['posyandu_id'])) {
            $query->where('laporan_masyarakats.posyandu_id', $this->filters['posyandu_id']);
        }

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
        $data = [
            $laporan->hari_tanggal ? \Carbon\Carbon::parse($laporan->hari_tanggal)->format('d/m/Y') : '-',
            $laporan->nama_pelapor,
            $laporan->nik_pelapor . " ",
            $laporan->no_telepon,
            $laporan->alamat,
            $laporan->kategori,
            $laporan->isi_laporan,
            $laporan->status,
            $laporan->balasan,
            $laporan->dusun,
            $laporan->rw,
            $laporan->rt,
            $laporan->created_at ? $laporan->created_at->format('d/m/Y H:i') : '-',
        ];

        $user = auth()->user();
        if (!($user && $user->hasRole('posyandu') && $user->posyandu_id)) {
            $data[] = $laporan->posyandu->nama ?? 'Umum/Semua';
        }

        return $data;
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function headings(): array
    {
        $headers = [
            'Hari/Tanggal Kejadian',
            'Nama Lengkap',
            'No. KTP',
            'No. HP',
            'Alamat',
            'Jenis Keperluan',
            'Keterangan',
            'Status',
            'Balasan',
            'Dusun',
            'RW',
            'RT',
            'Tanggal Masuk'
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
        $maxCol = $isPosyandu ? 'M' : 'N';

        return [
            BeforeSheet::class => function(BeforeSheet $event) use ($maxCol) {
                // Title
                $event->sheet->getDelegate()->mergeCells("A1:{$maxCol}1");
                $event->sheet->setCellValue('A1', 'LAPORAN DATA ADUAN MASYARAKAT');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Filters info
                $event->sheet->getDelegate()->mergeCells("A2:{$maxCol}2");
                $filterDesc = "Filter: ";
                $filterDesc .= "Dusun: " . ($this->filters['dusun'] ?? 'Semua') . " | ";
                $filterDesc .= "RW: " . ($this->filters['rw'] ?? 'Semua') . " | ";
                $filterDesc .= "RT: " . ($this->filters['rt'] ?? 'Semua') . " | ";
                $filterDesc .= "Search: " . ($this->filters['search'] ?? '-');
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Meta info
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
                
                // Center align specific columns (Hari/Tanggal, KTP, Status, Dusun, RW, RT, Tanggal Masuk)
                $event->sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('C6:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('H6:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('J6:L' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('M6:M' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                if ($maxCol === 'N') {
                    $event->sheet->getStyle('N6:N' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
