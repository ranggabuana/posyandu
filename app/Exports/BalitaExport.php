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

class BalitaExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize, WithMapping, WithColumnFormatting
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
            ->whereRaw("TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) > 12")
            ->whereRaw("TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 60");

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

        $penduduk = $bayi->penduduk;
        $jk = $penduduk->kelamin == 'laki-laki' ? 'L' : 'P';

        $row = [
            $no,
            $penduduk->nama ?? '-',
            $bayi->tanggal_lahir ?? '-',
            $jk,
            $bayi->nama_ibu ?? '-',
            $bayi->status_akta ?? '-',
        ];

        // BB Months 13-60
        for ($i = 13; $i <= 60; $i++) {
            $exam = $bayi->pemeriksaans->firstWhere('umur_bulan', $i);
            $row[] = $exam ? $exam->berat_badan : '';
        }

        // Vitamin A (18 to 60)
        foreach ([18, 24, 30, 36, 42, 48, 54, 60] as $month) {
            $exam = $bayi->pemeriksaans->firstWhere('umur_bulan', $month);
            $row[] = ($exam && ($exam->vitamin_a === 'merah' || $exam->vitamin_a === 'biru')) ? 'sudah' : 'belum';
        }

        // Booster
        $checkImun = function($name) use ($bayi) {
            return $bayi->imunisasis->where('nama_vaksin', $name)->isNotEmpty();
        };

        $row[] = $checkImun('DPT-HB-Hib Booster') ? 'sudah' : 'belum';
        $row[] = $checkImun('Campak/MR Booster') ? 'sudah' : 'belum';

        $lastExam = $bayi->pemeriksaans->whereNotNull('catatan_perkembangan')->last();
        $row[] = $lastExam ? $lastExam->catatan_perkembangan : '-';

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
                $sheet->mergeCells('A1:BM1');
                $sheet->setCellValue('A1', 'REGISTER ANAK BALITA DALAM WILAYAH KERJA POSYANDU');
                $sheet->getStyle('A1')->getFont()->setBold(false)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A2:BM2');
                $sheet->setCellValue('A2', 'TAHUN: ' . date('Y'));
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Location Info
                $posyanduName = '-';
                if (!empty($this->filters['posyandu_id'])) {
                    $p = Posyandu::find($this->filters['posyandu_id']);
                    if ($p) $posyanduName = $p->nama;
                } elseif (!empty($this->filters['rw'])) {
                    $p = Posyandu::whereJsonContains('rw_diampu', $this->filters['rw'])->first();
                    if ($p) $posyanduName = $p->nama;
                }

                $sheet->setCellValue('A3', 'RT / RW / POSYANDU');
                $sheet->setCellValue('C3', ': ' . ($this->filters['rt'] ?? '-') . ' / ' . ($this->filters['rw'] ?? '-') . ' / ' . $posyanduName);
                
                $sheet->setCellValue('A4', 'STRATA POSYANDU');
                $sheet->setCellValue('C4', ': -');
                
                $sheet->setCellValue('A5', 'DESA / KELURAHAN');
                $sheet->setCellValue('C5', ': ' . ($this->settings['nama_desa'] ?? 'BANJAR'));
                
                $sheet->setCellValue('A6', 'KECAMATAN');
                $sheet->setCellValue('C6', ': ' . ($this->settings['kecamatan'] ?? 'BANJAR'));
                
                $sheet->setCellValue('A7', 'KABUPATEN');
                $sheet->setCellValue('C7', ': ' . ($this->settings['kabupaten'] ?? 'BANJAR'));

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

                // NO, NAMA, TGL LAHIR, JK, ORTU, AKTA
                $sheet->mergeCells('A9:A13'); $sheet->setCellValue('A9', 'NO.');
                $sheet->mergeCells('B9:B13'); $sheet->setCellValue('B9', 'N A M A');
                $sheet->mergeCells('C9:C13'); $sheet->setCellValue('C9', 'TGL. L A H I R');
                $sheet->mergeCells('D9:D13'); $sheet->setCellValue('D9', "JENIS KELAMIN\nL / P");
                $sheet->mergeCells('E9:E13'); $sheet->setCellValue('E9', 'NAMA ORANG TUA');
                $sheet->mergeCells('F9:F13'); $sheet->setCellValue('F9', 'MEMPUNYAI AKTE KELAHIRAN');
                
                $sheet->getStyle('D9')->getAlignment()->setTextRotation(90);
                $sheet->getStyle('F9')->getAlignment()->setTextRotation(90);

                // PELAYANAN ANAK BALITA
                $sheet->mergeCells('G9:BB9'); $sheet->setCellValue('G9', 'PELAYANAN ANAK BALITA');
                
                $years = ['TAHUN KE II', 'TAHUN KE III', 'TAHUN KE IV', 'TAHUN KE V'];
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Juni', 'Juli', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                
                foreach ($years as $yIdx => $yearText) {
                    $startColIndex = 7 + ($yIdx * 12);
                    $endColIndex = $startColIndex + 11;
                    $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColIndex);
                    $endCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endColIndex);
                    
                    $sheet->mergeCells($startCol . '10:' . $endCol . '10');
                    $sheet->setCellValue($startCol . '10', $yearText);
                    
                    foreach ($months as $mIdx => $m) {
                        $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColIndex + $mIdx);
                        $sheet->setCellValue($col . '11', $m);
                        $sheet->mergeCells($col . '12:' . $col . '13');
                        $sheet->setCellValue($col . '12', 'BB');
                    }
                }

                // VITAMIN A
                $sheet->mergeCells('BC9:BJ10'); $sheet->setCellValue('BC9', 'PEMBERIAN VITAMIN A');
                $vits = ['18 bln', '24 bln', '30 bln', '36 bln', '42 bln', '48 bln', '54 bln', '60 bln'];
                foreach ($vits as $idx => $v) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(55 + $idx);
                    $sheet->mergeCells($col . '11:' . $col . '13');
                    $sheet->setCellValue($col . '11', $v);
                    $sheet->getStyle($col . '11')->getAlignment()->setTextRotation(90);
                }

                // BOOSTER
                $sheet->mergeCells('BK9:BL9'); $sheet->setCellValue('BK9', 'Imunisasi Booster/ Ulang');
                $sheet->mergeCells('BK10:BK13'); $sheet->setCellValue('BK10', "DPT-Hb-Hib\n(18-36-bl)");
                $sheet->mergeCells('BL10:BL13'); $sheet->setCellValue('BL10', "Campak\n(24-36 bl)");
                $sheet->getStyle('BK10')->getAlignment()->setTextRotation(90);
                $sheet->getStyle('BL10')->getAlignment()->setTextRotation(90);

                // KET
                $sheet->mergeCells('BM9:BM13'); $sheet->setCellValue('BM9', 'Ket');

                $sheet->getStyle('A9:BM13')->applyFromArray($headerStyle);
                $sheet->getRowDimension('11')->setRowHeight(20);
                $sheet->getRowDimension('12')->setRowHeight(20);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                
                $dataRange = 'A14:BM' . $lastRow;
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
                $sheet->getStyle('E14:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                
                // Auto size first few cols
                foreach (range(1, 6) as $idx) {
                    $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx))->setAutoSize(true);
                }
                // Fixed width for weight cols (G to BB)
                foreach (range(7, 54) as $idx) {
                    $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx))->setWidth(4);
                }
                // Fixed width for vitamin and booster (BC to BL)
                foreach (range(55, 64) as $idx) {
                    $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx))->setWidth(4);
                }
                $sheet->getColumnDimension('BM')->setWidth(15);
            },
        ];
    }
}
