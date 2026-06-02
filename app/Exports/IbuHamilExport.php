<?php

namespace App\Exports;

use App\Models\IbuHamil;
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

class IbuHamilExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize, WithMapping, WithColumnFormatting
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
        $query = IbuHamil::with(['penduduk', 'suami']);

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

        return $query;
    }

    public function map($ibu): array
    {
        static $no = 0;
        $no++;

        $umur = $ibu->penduduk->tanggallahir ? Carbon::parse($ibu->penduduk->tanggallahir)->age : '-';
        
        // Faktor Resiko mapping
        $fr = $ibu->faktor_resiko ?? '';
        $isUmurResiko = (strpos($fr, '< 20') !== false || strpos($fr, '> 35') !== false) ? 'v' : '';
        $isAnakResiko = (strpos($fr, '> 4') !== false) ? 'v' : '';
        $isJarakResiko = (strpos($fr, 'Jarak') !== false) ? 'v' : '';
        $isLilaResiko = (strpos($fr, 'lila') !== false) ? 'v' : '';
        $isTbResiko = (strpos($fr, 'TB') !== false) ? 'v' : '';

        return [
            $no,
            $ibu->created_at ? $ibu->created_at->format('d/m/Y') : '-',
            $ibu->penduduk->nama ?? '-',
            $umur,
            $ibu->suami->nama ?? '-',
            '-', // Kehamilan Anak Ke
            $isUmurResiko,
            $isAnakResiko,
            $isJarakResiko,
            $isLilaResiko,
            $isTbResiko,
            $ibu->imunisasi_tt3 ?? '-',
            $ibu->imunisasi_tt4 ?? '-',
            $ibu->imunisasi_tt5 ?? '-',
            $ibu->tablet_tambah_darah_1 ?? '-',
            $ibu->tablet_tambah_darah_2 ?? '-',
            $ibu->tablet_tambah_darah_3 ?? '-',
            $ibu->bb_bulan_1 ?? '-',
            $ibu->bb_bulan_2 ?? '-',
            $ibu->bb_bulan_3 ?? '-',
            $ibu->bb_bulan_4 ?? '-',
            $ibu->bb_bulan_5 ?? '-',
            $ibu->bb_bulan_6 ?? '-',
            $ibu->bb_bulan_7 ?? '-',
            $ibu->bb_bulan_8 ?? '-',
            $ibu->bb_bulan_9 ?? '-',
            $ibu->bb_bulan_10 ?? '-',
            $ibu->bb_bulan_11 ?? '-',
            $ibu->bb_bulan_12 ?? '-',
            $ibu->tgl_melahirkan ?? '-',
            $ibu->ditolong_oleh ?? '-',
            $ibu->bb_bayi ?? '-',
            $ibu->jk_bayi ?? '-',
        ];
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
        return 'A12';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Title
                $sheet->mergeCells('A1:AG1');
                $sheet->setCellValue('A1', 'REGISTER IBU HAMIL DALAM WILAYAH KERJA POSYANDU');
                $sheet->getStyle('A1')->getFont()->setBold(false)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Location Info
                $posyanduName = '-';
                if (!empty($this->filters['rw'])) {
                    $p = Posyandu::whereJsonContains('rw_diampu', $this->filters['rw'])->first();
                    if ($p) $posyanduName = $p->nama;
                }
                
                $sheet->setCellValue('A4', 'RT/ RW / POSYANDU');
                $sheet->setCellValue('D4', ': ' . ($this->filters['rt'] ?? '-') . ' / ' . ($this->filters['rw'] ?? '-') . ' / ' . $posyanduName);
                
                $sheet->setCellValue('A5', 'DESA / KELURAHAN');
                $sheet->setCellValue('D5', ': ' . ($this->settings['nama_desa'] ?? 'BANJAR'));
                
                $sheet->setCellValue('A6', 'KECAMATAN');
                $sheet->setCellValue('D6', ': ' . ($this->settings['kecamatan'] ?? 'BANJAR'));
                
                $sheet->setCellValue('A7', 'KABUPATEN');
                $sheet->setCellValue('D7', ': ' . ($this->settings['kabupaten'] ?? 'BANJAR'));

                // Complex Header Style (Non-bold, borders, centered)
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

                // NO
                $sheet->mergeCells('A9:A11'); $sheet->setCellValue('A9', 'NO');
                // TANGGAL
                $sheet->mergeCells('B9:B11'); $sheet->setCellValue('B9', 'TANGGAL');
                // NAMA IBU HAMIL
                $sheet->mergeCells('C9:C11'); $sheet->setCellValue('C9', 'NAMA IBU HAMIL');
                // UMUR IBU
                $sheet->mergeCells('D9:D11'); $sheet->setCellValue('D9', 'UMUR IBU');
                // NAMA SUAMI
                $sheet->mergeCells('E9:E11'); $sheet->setCellValue('E9', 'NAMA SUAMI');
                // KEHAMILAN ANAK KE
                $sheet->mergeCells('F9:F11'); $sheet->setCellValue('F9', 'KEHAMILAN ANAK KE');
                
                // FAKTOR RESIKO
                $sheet->mergeCells('G9:K9'); $sheet->setCellValue('G9', 'FAKTOR RESIKO');
                $sheet->getStyle('G9')->getFont()->setBold(true);
                
                $sheet->mergeCells('G10:G11'); $sheet->setCellValue('G10', "umur < 20 th\nATAU > 35 th");
                $sheet->mergeCells('H10:H11'); $sheet->setCellValue('H10', "Jumlah anak\n> 4");
                $sheet->mergeCells('I10:I11'); $sheet->setCellValue('I10', "Jarak Hamil\n< 2 th");
                $sheet->mergeCells('J10:J11'); $sheet->setCellValue('J10', "lila kurang\ndr 23,5 cm");
                $sheet->mergeCells('K10:K11'); $sheet->setCellValue('K10', "TB < 145 cm");

                // IMUNISASI
                $sheet->mergeCells('L9:N9'); $sheet->setCellValue('L9', 'IMUNISASI TT TGL/BLN');
                $sheet->mergeCells('L10:L11'); $sheet->setCellValue('L10', 'TT ³');
                $sheet->mergeCells('M10:M11'); $sheet->setCellValue('M10', 'TT⁺⁴');
                $sheet->mergeCells('N10:N11'); $sheet->setCellValue('N10', 'TT⁺⁵');

                // TABLET FE
                $sheet->mergeCells('O9:Q10'); $sheet->setCellValue('O9', 'TABLET TAMBAH DARAH');
                $sheet->setCellValue('O11', '1'); $sheet->setCellValue('P11', '2'); $sheet->setCellValue('Q11', '3');

                // HASIL PENIMBANGAN (BB)
                $sheet->mergeCells('R9:AC9'); $sheet->setCellValue('R9', 'HASIL PENIMBANGAN (BB)');
                $months = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGT', 'SEP', 'OKT', 'NOP', 'DES'];
                foreach ($months as $idx => $m) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(18 + $idx);
                    $sheet->mergeCells($col . '10:' . $col . '11');
                    $sheet->setCellValue($col . '10', $m);
                    // Vertical month names
                    $sheet->getStyle($col . '10')->getAlignment()->setTextRotation(90);
                }

                // MELAHIRKAN
                $sheet->mergeCells('AD9:AE9'); $sheet->setCellValue('AD9', 'MELAHIRKAN');
                $sheet->mergeCells('AD10:AD11'); $sheet->setCellValue('AD10', 'TGL melahirkan');
                $sheet->mergeCells('AE10:AE11'); $sheet->setCellValue('AE10', 'DITOLONG OLEH');
                $sheet->getStyle('AD10:AE11')->getAlignment()->setTextRotation(90);

                // BB BAYI
                $sheet->mergeCells('AF9:AF11'); $sheet->setCellValue('AF9', 'BERAT BADAN BAYI');
                $sheet->getStyle('AF9')->getAlignment()->setTextRotation(90);
                
                // JK BAYI
                $sheet->mergeCells('AG9:AG11'); $sheet->setCellValue('AG9', 'JENIS KELAMIN BAYI');
                $sheet->getStyle('AG9')->getAlignment()->setTextRotation(90);

                // Apply general styles to entire header range
                $sheet->getStyle('A9:AG11')->applyFromArray($headerStyle);
                
                // Set row heights
                $sheet->getRowDimension('1')->setRowHeight(25);
                $sheet->getRowDimension('9')->setRowHeight(15);
                $sheet->getRowDimension('10')->setRowHeight(60); // Reduced from 80
                $sheet->getRowDimension('11')->setRowHeight(25);
                
                // Adjust column widths
                // G-N are Horizontal now
                foreach (range(7, 14) as $colIndex) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->getColumnDimension($colLetter)->setWidth(12);
                }
                // R-AG are Vertical
                foreach (range(18, 33) as $colIndex) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(false);
                    $sheet->getColumnDimension($colLetter)->setWidth(5);
                }
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                
                // Borders for data
                $dataRange = 'A12:AG' . $lastRow;
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Left align names
                $sheet->getStyle('C12:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('E12:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            },
        ];
    }
}
