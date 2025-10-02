<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GroupTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * Return example data
     */
    public function array(): array
    {
        return [
            [
                'Kelompok 1',
                'mahasiswa1@politala.ac.id',
                'mahasiswa2@politala.ac.id',
                'mahasiswa3@politala.ac.id',
                'mahasiswa4@politala.ac.id',
                'mahasiswa5@politala.ac.id',
            ],
            [
                'Kelompok 2',
                'mahasiswa6@politala.ac.id',
                'mahasiswa7@politala.ac.id',
                'mahasiswa8@politala.ac.id',
                'mahasiswa9@politala.ac.id',
                'mahasiswa10@politala.ac.id',
            ],
        ];
    }

    /**
     * Define headings
     */
    public function headings(): array
    {
        return [
            'nama_kelompok',
            'ketua_email',
            'anggota_1_email',
            'anggota_2_email',
            'anggota_3_email',
            'anggota_4_email',
        ];
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
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
            'A' => 20,
            'B' => 30,
            'C' => 30,
            'D' => 30,
            'E' => 30,
            'F' => 30,
        ];
    }
}

