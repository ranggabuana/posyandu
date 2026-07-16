<?php

namespace App\Exports;

use App\Models\BayiBalita;
use App\Models\Posyandu;
use App\Models\Pengaturan;
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
use Illuminate\Support\Carbon;

class BayiBalitaExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize, WithMapping, WithColumnFormatting
{
    protected $filters;
    protected $settings;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->settings = Pengaturan::pluck('value', 'key')->toArray();
    }

    public function query()
    {
        $query = BayiBalita::with(['penduduk', 'posyandu', 'pemeriksaans', 'imunisasis'])
            ->whereRaw("TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 12");

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->whereHas('penduduk', function($q) use ($s) {
                $q->where('nama', 'like', '%' . $s . '%')
                  ->orWhere('nik', 'like', '%' . $s . '%');
            });
        }

        if (!empty($this->filters['dusun']) || !empty($this->filters['rw']) || !empty($this->filters['rt'])) {
            $query->whereHas('penduduk', function($q) {
                if (!empty($this->filters['dusun'])) $q->where('dusun', $this->filters['dusun']);
                if (!empty($this->filters['rw'])) $q->where('rw', $this->filters['rw']);
                if (!empty($this->filters['rt'])) $q->where('rt', $this->filters['rt']);
            });
        }

        if (!empty($this->filters['posyandu_id'])) {
            $query->where('posyandu_id', $this->filters['posyandu_id']);
        }

        return $query;
    }

    public function map($bayi): array
    {
        static $no = 0;
        $no++;

        $jk = $bayi->penduduk->kelamin == 'laki-laki' ? 'L' : 'P';

        $checkImun = function($name) use ($bayi) {
            return $bayi->imunisasis->where('nama_vaksin', $name)->isNotEmpty();
        };

        $hbo_k7 = $checkImun('HBO < 7 Hari');
        $hbo_l7 = $checkImun('HBO > 7 Hari');
        $bcg_polio1 = $checkImun('BCG & Polio 1');
        $penta1_polio2 = $checkImun('Pentavalen 1 & Polio 2');
        $penta2_polio3 = $checkImun('Pentavalen 2 & Polio 3');
        $penta3_polio4 = $checkImun('Pentavalen 3 & Polio 4');
        $campak = $checkImun('Campak/MR 1');

        $row = [
            $no,
            $bayi->penduduk->nama ?? '-',
            $bayi->tanggal_lahir ?? '-',
            $jk,
            $bayi->penduduk->nama_ayah ?? '-',
            $bayi->nama_ibu ?? '-',
        ];

        // BB Months 1-12
        for ($i = 1; $i <= 12; $i++) {
            $exam = $bayi->pemeriksaans->firstWhere('umur_bulan', $i);
            $row[] = $exam ? $exam->berat_badan : '';
        }

        // IMUNISASI
        $row[] = ($jk == 'L' && $hbo_k7) ? 'v' : '';
        $row[] = ($jk == 'P' && $hbo_k7) ? 'v' : '';
        $row[] = ($jk == 'L' && $hbo_l7) ? 'v' : '';
        $row[] = ($jk == 'P' && $hbo_l7) ? 'v' : '';
        $row[] = ($jk == 'L' && $bcg_polio1) ? 'v' : '';
        $row[] = ($jk == 'P' && $bcg_polio1) ? 'v' : '';
        $row[] = ($jk == 'L' && $penta1_polio2) ? 'v' : '';
        $row[] = ($jk == 'P' && $penta1_polio2) ? 'v' : '';
        $row[] = ($jk == 'L' && $penta2_polio3) ? 'v' : '';
        $row[] = ($jk == 'P' && $penta2_polio3) ? 'v' : '';
        $row[] = ($jk == 'L' && $penta3_polio4) ? 'v' : '';
        $row[] = ($jk == 'P' && $penta3_polio4) ? 'v' : '';
        $row[] = ($jk == 'L' && $campak) ? 'v' : '';
        $row[] = ($jk == 'P' && $campak) ? 'v' : '';
        
        $row[] = $bayi->keterangan ?? '-';

        return $row;
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function headings(): array
    {
        return [];
    }

    public function startCell(): string
    {
        return 'A14';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Title
                $sheet->mergeCells('A1:AG1');
                $sheet->setCellValue('A1', 'REGISTER BAYI DALAM WILAYAH KERJA POSYANDU');
                $sheet->getStyle('A1')->getFont()->setBold(false)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Info
                $posyanduName = '-';
                if (!empty($this->filters['posyandu_id'])) {
                    $p = Posyandu::find($this->filters['posyandu_id']);
                    if ($p) $posyanduName = $p->nama;
                } elseif (!empty($this->filters['rw'])) {
                    $p = Posyandu::whereJsonContains('rw_diampu', $this->filters['rw'])->first();
                    if ($p) $posyanduName = $p->nama;
                }

                $sheet->setCellValue('A4', 'RT/ RW / POSYANDU');
                $sheet->setCellValue('C4', ': ' . ($this->filters['rt'] ?? '-') . ' / ' . ($this->filters['rw'] ?? '-') . ' / ' . $posyanduName);
                
                $sheet->setCellValue('A5', 'STRATA POSYANDU');
                $sheet->setCellValue('C5', ': -');
                
                $sheet->setCellValue('A6', 'DESA / KELURAHAN');
                $sheet->setCellValue('C6', ': ' . ($this->settings['nama_desa'] ?? 'BANJAR'));
                
                $sheet->setCellValue('A7', 'KECAMATAN');
                $sheet->setCellValue('C7', ': ' . ($this->settings['kecamatan'] ?? 'BANJAR'));
                
                $sheet->setCellValue('A8', 'KABUPATEN');
                $sheet->setCellValue('C8', ': ' . ($this->settings['kabupaten'] ?? 'BANJAR'));

                // Header Style
                $headerStyle = [
                    'font' => ['bold' => false, 'size' => 10],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ];

                // NO, NAMA BAYI, TGL LAHIR, JK
                $sheet->mergeCells('A10:A13'); $sheet->setCellValue('A10', 'NO');
                $sheet->mergeCells('B10:B13'); $sheet->setCellValue('B10', 'NAMA BAYI');
                $sheet->mergeCells('C10:C13'); $sheet->setCellValue('C10', 'TGL LAHIR');
                $sheet->mergeCells('D10:D13'); $sheet->setCellValue('D10', "JENIS\nKELAMIN\n(L/P)");
                
                // NAMA ORTU
                $sheet->mergeCells('E10:F10'); $sheet->setCellValue('E10', 'NAMA');
                $sheet->mergeCells('E11:E13'); $sheet->setCellValue('E11', 'AYAH');
                $sheet->mergeCells('F11:F13'); $sheet->setCellValue('F11', 'IBU');

                // KUNJUNGAN (BB)
                $sheet->mergeCells('G10:R10'); $sheet->setCellValue('G10', 'KUNJUNGAN BAYI PADA BULAN');
                $months = ['JAN', 'FEB', 'MAR', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUST', 'SEP', 'OKT', 'NOP', 'DES'];
                foreach ($months as $idx => $m) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $idx);
                    $sheet->mergeCells($col . '11:' . $col . '11');
                    $sheet->setCellValue($col . '11', $m);
                    $sheet->mergeCells($col . '12:' . $col . '13');
                    $sheet->setCellValue($col . '12', 'BB');
                    $sheet->getStyle($col . '11')->getAlignment()->setTextRotation(90);
                }

                // IMUNISASI
                $sheet->mergeCells('S10:AF10'); $sheet->setCellValue('S10', 'IMUNISASI');
                
                // HBO
                $sheet->mergeCells('S11:V11'); $sheet->setCellValue('S11', 'HBO');
                $sheet->mergeCells('S12:S13'); $sheet->setCellValue('S12', '< 7Hari');
                $sheet->mergeCells('T12:T13'); $sheet->setCellValue('T12', ''); // P
                $sheet->mergeCells('U12:U13'); $sheet->setCellValue('U12', '> 7Hari');
                $sheet->mergeCells('V12:V13'); $sheet->setCellValue('V12', ''); // P
                $sheet->getStyle('S12:V13')->getAlignment()->setTextRotation(90);

                // BCG & Polio I
                $sheet->mergeCells('W11:X12'); $sheet->setCellValue('W11', "BCG &\nPolio I");
                $sheet->setCellValue('W13', 'L'); $sheet->setCellValue('X13', 'P');

                // Pentavalen 1
                $sheet->mergeCells('Y11:Z11'); $sheet->setCellValue('Y11', "Pentavalen 1\n(DPT,Hb,Hib)");
                $sheet->mergeCells('Y12:Z12'); $sheet->setCellValue('Y12', 'Polio II');
                $sheet->setCellValue('Y13', 'L'); $sheet->setCellValue('Z13', 'P');

                // Pentavalen 2
                $sheet->mergeCells('AA11:AB11'); $sheet->setCellValue('AA11', "Pentavalen 2\n(DPT,Hb,Hib)");
                $sheet->mergeCells('AA12:AB12'); $sheet->setCellValue('AA12', 'Polio III');
                $sheet->setCellValue('AA13', 'L'); $sheet->setCellValue('AB13', 'P');

                // Pentavalen 3
                $sheet->mergeCells('AC11:AD11'); $sheet->setCellValue('AC11', "Pentavalen 3\n(DPT,Hb,Hib)");
                $sheet->mergeCells('AC12:AD12'); $sheet->setCellValue('AC12', 'Polio IV');
                $sheet->setCellValue('AC13', 'L'); $sheet->setCellValue('AD13', 'P');

                // CAMPAK
                $sheet->mergeCells('AE11:AF12'); $sheet->setCellValue('AE11', 'CAMPAK');
                $sheet->setCellValue('AE13', 'L'); $sheet->setCellValue('AF13', 'P');

                // KET
                $sheet->mergeCells('AG10:AG13'); $sheet->setCellValue('AG10', 'KET');

                $sheet->getStyle('A10:AG13')->applyFromArray($headerStyle);
                $sheet->getRowDimension('11')->setRowHeight(40);
                $sheet->getRowDimension('12')->setRowHeight(30);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                
                $dataRange = 'A14:AG' . $lastRow;
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B14:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('E14:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                
                // Auto size first few cols
                foreach (range(1, 6) as $idx) {
                    $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx))->setAutoSize(true);
                }
                // Fixed width for weight and v/x cols
                foreach (range(7, 32) as $idx) {
                    $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx))->setWidth(4);
                }
                $sheet->getColumnDimension('AG')->setWidth(15);
            },
        ];
    }
}
