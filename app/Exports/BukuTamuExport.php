<?php
 
namespace App\Exports;
 
use App\Models\BukuTamu;
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
 
class BukuTamuExport implements FromQuery, WithHeadings, WithEvents, WithCustomStartCell, WithMapping, WithColumnFormatting
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
        $query = BukuTamu::query();
 
        if (!empty($this->filters['posyandu_id'])) {
            $query->where('posyandu_id', $this->filters['posyandu_id']);
        }
 
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('tanggal_kunjungan', '>=', $this->filters['start_date']);
        }
 
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('tanggal_kunjungan', '<=', $this->filters['end_date']);
        }
 
        return $query->orderBy('tanggal_kunjungan', 'asc');
    }
 
    public function map($tamu): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $tamu->tanggal_kunjungan ? \Carbon\Carbon::parse($tamu->tanggal_kunjungan)->format('d/m/Y') : '-',
            $tamu->nama,
            $tamu->jabatan,
            $tamu->alamat,
            $tamu->keperluan,
            $tamu->keterangan,
        ];
    }
 
    public function columnFormats(): array
    {
        return [];
    }
 
    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL',
            'NAMA',
            'JABATAN',
            'ALAMAT',
            'TUJUAN',
            'KESAN/PESAN'
        ];
    }
 
    public function startCell(): string
    {
        return 'A7';
    }
 
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Merge cells A1:G1 for title
                $sheet->mergeCells("A1:G1");
                $sheet->setCellValue('A1', 'BUKU TAMU');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Fetch posyandu name dynamically
                $posyanduName = '.............';
                if (!empty($this->filters['posyandu_id'])) {
                    $p = Posyandu::find($this->filters['posyandu_id']);
                    if ($p) {
                        $posyanduName = strtoupper($p->nama);
                    }
                }
                
                $desaName = strtoupper($this->settings['nama_desa'] ?? 'BANJAR');
                $sheet->mergeCells("A2:G2");
                if (str_starts_with($desaName, 'DESA')) {
                    $sheet->setCellValue('A2', "POSYANDU {$posyanduName} {$desaName}");
                } else {
                    $sheet->setCellValue('A2', "POSYANDU {$posyanduName} DESA {$desaName}");
                }
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
 
                // Merge empty rows 3 and 4
                $sheet->mergeCells("A3:G3");
                $sheet->mergeCells("A4:G4");
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                
                // Set Column Widths exactly as in buku tamu.xlsx
                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(23);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(23);
                $sheet->getColumnDimension('E')->setWidth(31);
                $sheet->getColumnDimension('F')->setWidth(26);
                $sheet->getColumnDimension('G')->setWidth(38);
                
                // Table style range: from A7 to G[lastRow]
                $tableRange = "A7:G" . ($lastRow < 7 ? 7 : $lastRow);
                
                // Set Borders to thin black, matching the template
                $sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                
                // Set Header styling at row 7
                $sheet->getStyle('A7:G7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Style the header row height
                $sheet->getRowDimension('7')->setRowHeight(25);
                
                // Alignments and text wrapping for data cells
                if ($lastRow >= 8) {
                    $sheet->getStyle("A8:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B8:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    // Left align columns C to G
                    $sheet->getStyle("C8:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    
                    // V-align top and enable wrapText for all data rows
                    $sheet->getStyle("A8:G{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                    $sheet->getStyle("A8:G{$lastRow}")->getAlignment()->setWrapText(true);
                }
            },
        ];
    }
}
