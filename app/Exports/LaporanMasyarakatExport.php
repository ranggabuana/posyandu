<?php

namespace App\Exports;

use App\Models\LaporanMasyarakat;
use App\Models\Posyandu;
use App\Models\Pengaturan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanMasyarakatExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, WithMapping, WithColumnFormatting
{
    protected $filters;
    protected $settings;
    private $rowNumber = 0;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->settings = Pengaturan::pluck('value', 'key')->toArray();
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
                \DB::raw('COALESCE(laporan_masyarakats.no_kk, penduduks.no_kk) as no_kk'),
                'laporan_masyarakats.created_at',
                'laporan_masyarakats.posyandu_id'
            );

        if (!empty($this->filters['posyandu_id'])) {
            $query->where('laporan_masyarakats.posyandu_id', $this->filters['posyandu_id']);
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('laporan_masyarakats.kategori', $this->filters['kategori']);
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('laporan_masyarakats.created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('laporan_masyarakats.created_at', '<=', $this->filters['end_date']);
        }

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('laporan_masyarakats.nama_pelapor', 'like', '%' . $s . '%')
                  ->orWhere('laporan_masyarakats.nik_pelapor', 'like', '%' . $s . '%')
                  ->orWhere('laporan_masyarakats.isi_laporan', 'like', '%' . $s . '%');
            });
        }

        return $query->orderBy('laporan_masyarakats.created_at', 'asc');
    }

    public function map($laporan): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $laporan->hari_tanggal ? \Carbon\Carbon::parse($laporan->hari_tanggal)->format('d/m/Y') : '-',
            $laporan->nama_pelapor,
            $laporan->nik_pelapor . " ",
            ($laporan->no_kk ?? '-') . " ",
            $laporan->alamat,
            $laporan->kategori,
            $laporan->isi_laporan,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'HARI/TGL',
            'NAMA',
            'NO KTP',
            'NO KK',
            'ALAMAT',
            'JENIS PERMOHONAN',
            'KETERANGAN'
        ];
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Merge A2:H2 for the dynamic title
                $sheet->mergeCells("A2:H2");
                
                $kategoriName = 'MASYARAKAT';
                if (!empty($this->filters['kategori'])) {
                    $kategoriName = strtoupper($this->filters['kategori']);
                }
                
                if (str_starts_with($kategoriName, 'BIDANG')) {
                    $title = "BUKU PELAYANAN {$kategoriName}";
                } else {
                    $title = "BUKU PELAYANAN BIDANG {$kategoriName}";
                }
                
                $sheet->setCellValue('A2', $title);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Merge A3:H3 for Posyandu & Village info
                $posyanduName = '.............';
                if (!empty($this->filters['posyandu_id'])) {
                    $p = Posyandu::find($this->filters['posyandu_id']);
                    if ($p) {
                        $posyanduName = strtoupper($p->nama);
                    }
                }
                $desaName = strtoupper($this->settings['nama_desa'] ?? 'BANJAR');
                $sheet->mergeCells("A3:H3");
                
                if (str_starts_with($desaName, 'DESA')) {
                    $sheet->setCellValue('A3', "POSYANDU {$posyanduName} {$desaName}");
                } else {
                    $sheet->setCellValue('A3', "POSYANDU {$posyanduName} DESA {$desaName}");
                }
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Row 4: A4 for Month / Period
                $bulanText = '…...............................';
                if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
                    $start = \Carbon\Carbon::parse($this->filters['start_date'])->locale('id');
                    $end = \Carbon\Carbon::parse($this->filters['end_date'])->locale('id');
                    if ($start->format('Y-m') === $end->format('Y-m')) {
                        $bulanText = strtoupper($start->translatedFormat('F Y'));
                    } else {
                        $bulanText = strtoupper($start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y'));
                    }
                }
                $sheet->setCellValue('A4', "BULAN : {$bulanText}");
                $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(11);
                
                // Row 5: Merge A5:C5 with thin bottom border
                $sheet->mergeCells("A5:C5");
                $sheet->getStyle('A5:C5')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ]
                    ]
                ]);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                
                // Set Column Widths exactly as in aduan.xlsx
                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(30);
                $sheet->getColumnDimension('E')->setWidth(27);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(37);
                $sheet->getColumnDimension('H')->setWidth(29);
                
                // Table style range: from A6 to H[lastRow]
                $tableRange = "A6:H" . ($lastRow < 6 ? 6 : $lastRow);
                
                // Set Borders to thin black, matching the template
                $sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                
                // Set Header styling at row 6
                $sheet->getStyle('A6:H6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Style the header row height
                $sheet->getRowDimension('6')->setRowHeight(25);
                
                // Alignments and text wrapping for data cells
                if ($lastRow >= 7) {
                    $sheet->getStyle("A7:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B7:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D7:E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    // Left align other columns
                    $sheet->getStyle("C7:C{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle("F7:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    
                    // V-align top and enable wrapText
                    $sheet->getStyle("A7:H{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                    $sheet->getStyle("A7:H{$lastRow}")->getAlignment()->setWrapText(true);
                }
            },
        ];
    }
}
