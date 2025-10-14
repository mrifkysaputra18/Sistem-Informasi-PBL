<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GroupTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function headings(): array
    {
        return [
            'nama_kelompok',
            'ketua_nim_atau_email',
            'anggota_1_nim_atau_email',
            'anggota_2_nim_atau_email',
            'anggota_3_nim_atau_email',
            'anggota_4_nim_atau_email',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Kelompok 1',
                '2401301001', // Bisa NIM atau email
                '2401301002',
                '2401301003',
                'mahasiswa4@mhs.politala.ac.id', // Atau bisa email
                'mahasiswa5@mhs.politala.ac.id',
            ],
            [
                'Kelompok 2',
                'mahasiswa6@mhs.politala.ac.id',
                '2401301007',
                '2401301008',
                '2401301009',
                '2401301010',
            ],
            [
                'Kelompok 3',
                '2401301011',
                '2401301012',
                '2401301013',
                '', // Anggota opsional
                '', // Anggota opsional
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style header row
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            // Style data rows
            2 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E7FF']]],
            3 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F9FF']]],
            4 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E7FF']]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // nama_kelompok
            'B' => 30, // ketua
            'C' => 30, // anggota_1
            'D' => 30, // anggota_2
            'E' => 30, // anggota_3
            'F' => 30, // anggota_4
        ];
    }
}
