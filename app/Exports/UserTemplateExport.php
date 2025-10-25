<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UserTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * Return template data with examples
     */
    public function array(): array
    {
        return [
            // Row 2: Instruksi
            ['', 'PETUNJUK: Hapus baris ini dan isi data mahasiswa di bawah', '', '', ''],
            // Row 3: Kosong
            ['', '', '', '', ''],
            // Contoh data row 4
            [
                '241001001',
                'Ahmad Fauzi',
                'ahmad.fauzi@mhs.politala.ac.id',
                'Teknik Informatika',
                '3B'
            ],
            // Contoh data row 5
            [
                '241001002',
                'Siti Nurhaliza',
                'siti.nur@mhs.politala.ac.id',
                'Teknik Informatika',
                '3B'
            ],
            // Contoh data row 6
            [
                '241001003',
                'Budi Santoso',
                'budi.santoso@mhs.politala.ac.id',
                'Teknik Mesin',
                '3C'
            ],
        ];
    }

    /**
     * Define headings (header row)
     */
    public function headings(): array
    {
        return [
            'nim',
            'nama_lengkap',
            'email_sso',
            'program_studi',
            'kelas'
        ];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style header row (row 1)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'], // Blue background
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Style data rows (row 2-4)
            '2:4' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E7E6E6'], // Light gray
                ],
            ],
        ];
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15,  // nim
            'B' => 30,  // nama_lengkap
            'C' => 35,  // email_sso
            'D' => 25,  // program_studi
            'E' => 10,  // kelas
        ];
    }
}
