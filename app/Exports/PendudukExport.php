<?php

namespace App\Exports;

use App\Models\Penduduk;
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

class PendudukExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize, WithMapping, WithColumnFormatting
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $user = auth()->user();
        $query = Penduduk::query();

        if ($user && $user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $query->whereIn('rw', $rwDiampu);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('nama', 'like', '%' . $s . '%')
                  ->orWhere('nik', 'like', '%' . $s . '%')
                  ->orWhere('no_kk', 'like', '%' . $s . '%');
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
        if (!empty($this->filters['kelamin'])) {
            $query->where('kelamin', $this->filters['kelamin']);
        }

        return $query->orderBy('nama', 'asc');
    }

    public function map($penduduk): array
    {
        return [
            $penduduk->id,
            $penduduk->nama,
            $penduduk->no_kk . " ",
            $penduduk->nik . " ",
            $penduduk->nama_kk,
            $penduduk->hubungan_keluarga,
            $penduduk->kelamin,
            $penduduk->tempatlahir,
            $penduduk->tanggallahir,
            $penduduk->agama,
            $penduduk->pendidikan,
            $penduduk->pekerjaan,
            $penduduk->status_kawin,
            $penduduk->nama_ayah,
            $penduduk->nama_ibu,
            $penduduk->goldar,
            $penduduk->alamat,
            $penduduk->rw,
            $penduduk->rt,
            $penduduk->dusun,
            $penduduk->telepon,
            $penduduk->bpjs,
            $penduduk->created_at,
            $penduduk->updated_at,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'U' => NumberFormat::FORMAT_TEXT, // Telepon
        ];
    }

    public function headings(): array
    {
        return [
            'ID', 'Nama', 'No KK', 'NIK', 'Nama KK', 'Hubungan Keluarga', 
            'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Agama', 
            'Pendidikan', 'Pekerjaan', 'Status Kawin', 'Nama Ayah', 
            'Nama Ibu', 'Goldar', 'Alamat', 'RW', 'RT', 'Dusun', 
            'Telepon', 'BPJS', 'Created At', 'Updated At'
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
                $user = auth()->user();
                $isPosyandu = $user && $user->hasRole('posyandu') && $user->posyandu;

                // Title
                $event->sheet->getDelegate()->mergeCells('A1:X1');
                $title = 'LAPORAN DATA PENDUDUK';
                if ($isPosyandu) {
                    $title .= ' - ' . mb_strtoupper($user->posyandu->nama);
                }
                $event->sheet->setCellValue('A1', $title);
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Filters info
                $event->sheet->getDelegate()->mergeCells('A2:X2');
                $filterDesc = "";
                if ($isPosyandu) {
                    $rwDiampu = $user->posyandu->rw_diampu ?? [];
                    $rwFormatted = !empty($rwDiampu) 
                        ? implode(', ', array_map(function($rw) { return 'RW ' . sprintf('%02d', $rw); }, $rwDiampu))
                        : 'Tidak Ada';
                    $filterDesc .= "Posyandu: " . $user->posyandu->nama . " | RW Diampu: " . $rwFormatted . " | ";
                }
                $filterDesc .= "Filter: ";
                $filterDesc .= "Dusun: " . (!empty($this->filters['dusun']) ? $this->filters['dusun'] : 'Semua') . " | ";
                $filterDesc .= "RW: " . (!empty($this->filters['rw']) ? $this->filters['rw'] : 'Semua') . " | ";
                $filterDesc .= "RT: " . (!empty($this->filters['rt']) ? $this->filters['rt'] : 'Semua') . " | ";
                $filterDesc .= "Kelamin: " . (!empty($this->filters['kelamin']) ? $this->filters['kelamin'] : 'Semua') . " | ";
                $filterDesc .= "Search: " . (!empty($this->filters['search']) ? $this->filters['search'] : '-');
                $event->sheet->setCellValue('A2', $filterDesc);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Meta info
                $event->sheet->getDelegate()->mergeCells('A3:X3');
                $event->sheet->setCellValue('A3', "Tanggal Cetak: " . now()->format('d/m/Y H:i'));
                $event->sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
            AfterSheet::class => function(AfterSheet $event) {
                // Style the header row (A5:X5)
                $headerRange = 'A5:X5';
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
                $dataRange = 'A5:X' . $lastRow;
                $event->sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Align content
                $event->sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // ID
                $event->sheet->getStyle('R6:T' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // RW, RT, Dusun
            },
        ];
    }
}
