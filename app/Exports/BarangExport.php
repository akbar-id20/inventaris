<?php

namespace App\Exports;

use App\Models\Barang;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BarangExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithColumnWidths, WithEvents
{
    private $no = 0;
    private $data;

    public function __construct()
    {
        $this->data = Barang::all();
    }

    public function collection()
    {
        return $this->data;
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function headings(): array
    {
        return ['No', 'Kode Barang', 'Nama Barang', 'Stok'];
    }

    public function map($barang): array
    {
        return [++$this->no, $barang->kode, $barang->nama, $barang->stok];
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 20, 'C' => 35, 'D' => 12];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $center = Alignment::HORIZONTAL_CENTER;

                $akhir = 7 + $this->data->count();   // baris data terakhir
                $total = $akhir + 1;                  // baris total
                $tanggal = Carbon::now()->locale('id');

                // Kop
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'SISTEM INFORMASI INVENTARIS');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal($center);

                $sheet->mergeCells('A2:D2');
                $sheet->setCellValue('A2', 'Laporan Pengelolaan Data Barang');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal($center);
                $sheet->getStyle('A2:D2')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

                // Judul laporan
                $sheet->mergeCells('A4:D4');
                $sheet->setCellValue('A4', 'LAPORAN DATA BARANG');
                $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal($center);

                $sheet->mergeCells('A5:D5');
                $sheet->setCellValue('A5', 'Dicetak pada: ' . $tanggal->translatedFormat('d F Y, H:i') . ' WIB');
                $sheet->getStyle('A5')->getAlignment()->setHorizontal($center);

                // Header tabel
                $sheet->getStyle('A7:D7')->getFont()->setBold(true);
                $sheet->getStyle('A7:D7')->getAlignment()->setHorizontal($center);
                $sheet->getStyle('A7:D7')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D9D9D9');

                // Baris total
                $sheet->mergeCells("A{$total}:C{$total}");
                $sheet->setCellValue("A{$total}", 'Total Stok');
                $sheet->setCellValue("D{$total}", $this->data->sum('stok'));
                $sheet->getStyle("A{$total}:D{$total}")->getFont()->setBold(true);
                $sheet->getStyle("A{$total}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("A{$total}:D{$total}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');

                // Garis tabel
                $sheet->getStyle("A7:D{$total}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Rata tengah kolom No, Kode, Stok
                $sheet->getStyle("A8:B{$total}")->getAlignment()->setHorizontal($center);
                $sheet->getStyle("D8:D{$total}")->getAlignment()->setHorizontal($center);

                // Tanda tangan
                $ttd = $total + 3;
                $sheet->mergeCells("C{$ttd}:D{$ttd}");
                $sheet->setCellValue("C{$ttd}", 'Banjarmasin, ' . $tanggal->translatedFormat('d F Y'));
                $sheet->mergeCells('C' . ($ttd + 1) . ':D' . ($ttd + 1));
                $sheet->setCellValue('C' . ($ttd + 1), 'Admin');
                $sheet->mergeCells('C' . ($ttd + 5) . ':D' . ($ttd + 5));
                $sheet->setCellValue('C' . ($ttd + 5), auth()->user()->username ?? 'Admin');
                $sheet->getStyle('C' . ($ttd + 5))->getFont()->setBold(true)->setUnderline(true);
                $sheet->getStyle("C{$ttd}:D" . ($ttd + 5))->getAlignment()->setHorizontal($center);
            },
        ];
    }
}