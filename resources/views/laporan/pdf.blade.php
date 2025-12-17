<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Progress Mingguan</title>
    <style>
        @page {
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #000;
            line-height: 1.3;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #000;
        }
        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .header p {
            font-size: 9px;
        }
        
        /* Info */
        .info {
            margin-bottom: 15px;
            font-size: 9px;
        }
        .info table {
            border: none;
        }
        .info td {
            padding: 2px 0;
            border: none;
            vertical-align: top;
        }
        .info .label {
            width: 100px;
            font-weight: bold;
        }
        
        /* Tabel Excel Style */
        table.excel {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
        }
        table.excel th,
        table.excel td {
            border: 1px solid #000;
            padding: 4px 3px;
            font-size: 8px;
            vertical-align: middle;
        }
        table.excel th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }
        
        /* Week Section */
        .week-section {
            margin-bottom: 25px;
        }
        .week-title {
            font-size: 12px;
            font-weight: bold;
            background: #4472c4;
            color: white;
            padding: 8px 10px;
            margin-bottom: 10px;
        }
        
        /* Class Section */
        .class-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .class-title {
            font-size: 10px;
            font-weight: bold;
            background: #d6dce5;
            padding: 5px 10px;
            border: 1px solid #000;
            border-bottom: none;
        }
        
        /* Alignment */
        .center { text-align: center; }
        .left { text-align: left; }
        .right { text-align: right; }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            font-size: 8px;
            text-align: center;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PROGRESS MINGGUAN</h1>
        <p>Sistem Informasi PBL - Politeknik Negeri Tanah Laut</p>
    </div>

    <!-- Info -->
    <div class="info">
        <table>
            <tr>
                <td class="label">Tanggal Cetak</td>
                <td>: {{ date('d-m-Y H:i') }} WIB</td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan -->
    <table class="excel">
        <tr>
            <th>Total Minggu</th>
            <th>Sudah Submit</th>
            <th>Disetujui</th>
            <th>Belum Dikumpulkan</th>
            <th>Terlambat</th>
            <th>Progress</th>
        </tr>
        <tr>
            <td class="center">{{ $stats['total'] }}</td>
            <td class="center">{{ $stats['submitted'] }}</td>
            <td class="center">{{ $stats['approved'] }}</td>
            <td class="center">{{ $stats['pending'] }}</td>
            <td class="center">{{ $stats['late'] }}</td>
            <td class="center"><b>{{ $stats['progress_rate'] }}%</b></td>
        </tr>
    </table>

    <!-- Tabel Per Minggu, lalu Per Kelas -->
    @if($targets->count() > 0)
        @php
            // Group targets by week number first
            $targetsByWeek = $targets->groupBy('week_number')->sortKeys();
        @endphp
        
        @foreach($targetsByWeek as $weekNumber => $weekTargets)
        @php
            // Get target title for this week
            $targetTitle = $weekTargets->first()->title ?? '-';
            // Then group by class
            $targetsByClass = $weekTargets->groupBy(function($target) {
                return $target->group->classRoom->name ?? 'Tanpa Kelas';
            })->sortKeys();
        @endphp
        
        <div class="week-section">
            <div class="week-title">Minggu {{ $weekNumber }} - Target: {{ ucfirst($targetTitle) }}</div>
            
            @foreach($targetsByClass as $className => $classTargets)
            <div class="class-section">
                <div class="class-title">Kelas: {{ $className }}</div>
                <table class="excel">
                    <tr>
                        <th style="width: 20px;">No</th>
                        <th style="width: 80px;">Kelompok</th>
                        <th style="width: 120px;">Ketua Kelompok</th>
                        <th style="width: 75px;">Tgl Kumpul</th>
                        <th style="width: 65px;">Status</th>
                        <th style="width: 50px;">Progress</th>
                    </tr>
                    @foreach($classTargets as $index => $target)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td class="left">{{ $target->group->name ?? '-' }}</td>
                        <td class="left">{{ $target->group->leader->name ?? '-' }}</td>
                        <td class="center">{{ $target->completed_at ? $target->completed_at->format('d-m-Y H:i') : '-' }}</td>
                        <td class="center">
                            @php
                                $statusLabel = [
                                    'submitted' => 'Dikumpulkan',
                                    'late' => 'Terlambat',
                                    'approved' => 'Disetujui',
                                    'revision' => 'Revisi',
                                    'pending' => 'Pending',
                                ][$target->submission_status] ?? $target->submission_status;
                            @endphp
                            {{ $statusLabel }}
                        </td>
                        <td class="right">{{ $target->progress_percent ?? 0 }}%</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @endforeach
        </div>
        @endforeach
    @else
    <p style="text-align: center; padding: 20px;">Tidak ada data.</p>
    @endif

    <!-- Footer -->
    <div class="footer">
        Dokumen digenerate otomatis oleh Sistem Informasi PBL &copy; {{ date('Y') }}
    </div>
</body>
</html>
