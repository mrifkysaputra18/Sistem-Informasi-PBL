<?php

namespace App\Exports;

use App\Models\TargetMingguan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class WeeklyProgressExport implements WithMultipleSheets
{
    protected $targets;
    protected $stats;

    public function __construct(Collection $targets, array $stats)
    {
        $this->targets = $targets;
        $this->stats = $stats;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        // Sheet 1: Ringkasan
        $sheets[] = new WeeklyProgressSummarySheet($this->stats);
        
        // Sheets per kelas (bukan per minggu)
        $targetsByClass = $this->targets->groupBy(function($target) {
            return $target->group->classRoom->name ?? 'Tanpa Kelas';
        })->sortKeys();
        
        foreach ($targetsByClass as $className => $classTargets) {
            $sheets[] = new WeeklyProgressClassSheet($className, $classTargets);
        }
        
        return $sheets;
    }
}

// Sheet Ringkasan
class WeeklyProgressSummarySheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $stats;

    public function __construct(array $stats)
    {
        $this->stats = $stats;
    }

    public function collection()
    {
        return collect([
            [
                $this->stats['total'],
                $this->stats['submitted'],
                $this->stats['approved'],
                $this->stats['pending'],
                $this->stats['late'],
                $this->stats['progress_rate'] . '%',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Total Minggu',
            'Sudah Submit',
            'Disetujui',
            'Belum Dikumpulkan',
            'Terlambat',
            'Progress',
        ];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling - abu-abu dengan border
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BFBFBF'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Data row styling - border
        $sheet->getStyle('A2:F2')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 22,
            'E' => 15,
            'F' => 12,
        ];
    }
}

// Sheet Per Kelas
class WeeklyProgressClassSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths
{
    protected $className;
    protected $targets;
    protected $rowIndex = 0;

    public function __construct(string $className, Collection $targets)
    {
        $this->className = $className;
        $this->targets = $targets;
    }

    public function collection()
    {
        // Sort by week number then by group name
        return $this->targets->sortBy([
            ['week_number', 'asc'],
            fn($a, $b) => strcmp($a->group->name ?? '', $b->group->name ?? ''),
        ]);
    }

    public function headings(): array
    {
        return [
            'No',
            'Minggu',
            'Kelompok',
            'Ketua Kelompok',
            'Judul Target',
            'Tgl Kumpul',
            'Status',
            'Progress',
        ];
    }

    public function map($target): array
    {
        $this->rowIndex++;
        
        $statusLabel = [
            'submitted' => 'Dikumpulkan',
            'late' => 'Terlambat',
            'approved' => 'Disetujui',
            'revision' => 'Revisi',
            'pending' => 'Pending',
        ][$target->submission_status] ?? $target->submission_status;

        return [
            $this->rowIndex,
            $target->week_number,
            $target->group->name ?? '-',
            $target->group->leader->name ?? '-',
            ucfirst($target->title),
            $target->completed_at ? $target->completed_at->format('d-m-Y H:i') : '-',
            $statusLabel,
            ($target->progress_percent ?? 0) . '%',
        ];
    }

    public function title(): string
    {
        // Limit sheet name to 31 characters (Excel limit)
        $title = $this->className;
        return strlen($title) > 31 ? substr($title, 0, 31) : $title;
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = $this->targets->count() + 1; // +1 for header
        $lastRow = $rowCount;
        
        // Header styling - abu-abu dengan border tebal
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BFBFBF'], // Abu-abu
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Data rows styling - border semua sisi
        if ($lastRow > 1) {
            $sheet->getStyle("A2:H{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Alignment per kolom
            $sheet->getStyle("A2:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No, Minggu
            $sheet->getStyle("C2:E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Kelompok, Ketua, Judul
            $sheet->getStyle("F2:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tgl, Status
            $sheet->getStyle("H2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Progress
        }

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(20);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 10,  // Minggu
            'C' => 18,  // Kelompok
            'D' => 28,  // Ketua Kelompok
            'E' => 35,  // Judul Target
            'F' => 18,  // Tgl Kumpul
            'G' => 14,  // Status
            'H' => 12,  // Progress
        ];
    }
}
