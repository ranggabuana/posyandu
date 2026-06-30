<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$file = __DIR__ . '/aduan.xlsx';
if (!file_exists($file)) {
    echo "File not found: $file\n";
    exit(1);
}

$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();

$output = "";
$output .= "Sheet Title: " . $sheet->getTitle() . "\n";
$output .= "Highest Row: " . $sheet->getHighestRow() . "\n";
$output .= "Highest Column: " . $sheet->getHighestColumn() . "\n";

$output .= "\n--- Merged Cells ---\n";
foreach ($sheet->getMergeCells() as $mergeRange) {
    $output .= "Merged Range: $mergeRange\n";
}

$output .= "\n--- Column Widths ---\n";
foreach (range('A', $sheet->getHighestColumn()) as $col) {
    $output .= "Col $col Width: " . $sheet->getColumnDimension($col)->getWidth() . "\n";
}

$output .= "\n--- Cell Grid and Styling ---\n";
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

for ($row = 1; $row <= $highestRow; $row++) {
    $rowCells = [];
    for ($col = 1; $col <= $highestColumnIndex; $col++) {
        $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
        $cell = $sheet->getCell($cellCoordinate);
        $val = $cell->getValue();
        $style = $sheet->getStyle($cellCoordinate);
        
        $font = $style->getFont();
        $fontDesc = "";
        if ($font->getBold()) $fontDesc .= "Bold ";
        if ($font->getSize()) $fontDesc .= "Size:" . $font->getSize() . " ";
        
        $fill = $style->getFill();
        $fillDesc = "";
        if ($fill->getFillType() !== \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE) {
            $fillDesc = "BG:" . $fill->getStartColor()->getRGB() . " ";
        }
        
        $alignment = $style->getAlignment();
        $alignDesc = "";
        if ($alignment->getHorizontal()) $alignDesc .= "H:" . $alignment->getHorizontal() . " ";
        if ($alignment->getVertical()) $alignDesc .= "V:" . $alignment->getVertical() . " ";

        $borderDesc = "";
        $borders = $style->getBorders();
        if ($borders->getTop()->getBorderStyle()) $borderDesc .= "T:" . $borders->getTop()->getBorderStyle() . " ";
        if ($borders->getBottom()->getBorderStyle()) $borderDesc .= "B:" . $borders->getBottom()->getBorderStyle() . " ";
        if ($borders->getLeft()->getBorderStyle()) $borderDesc .= "L:" . $borders->getLeft()->getBorderStyle() . " ";
        if ($borders->getRight()->getBorderStyle()) $borderDesc .= "R:" . $borders->getRight()->getBorderStyle() . " ";

        $styleSummary = trim("$fontDesc $fillDesc $alignDesc $borderDesc");
        if ($val !== null || !empty($styleSummary)) {
            $rowCells[] = "$cellCoordinate: [" . ($val ?? '') . "] (" . $styleSummary . ")";
        }
    }
    if (!empty($rowCells)) {
        $output .= "Row $row:\n  " . implode("\n  ", $rowCells) . "\n";
    }
}

file_put_contents(__DIR__ . '/aduan_structure.txt', $output);
echo "Successfully wrote to aduan_structure.txt\n";
