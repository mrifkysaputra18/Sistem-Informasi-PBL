<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Progress Mingguan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #003366;
        }
        .header h1 {
            font-size: 18px;
            color: #003366;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .filter-info {
            background: #f5f5f5;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .filter-info span {
            margin-right: 20px;
        }
        .filter-info strong {
            color: #003366;
        }
        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 16.66%;
            text-align: center;
            padding: 10px 5px;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
        }
        .stat-box .number {
            font-size: 18px;
            font-weight: bold;
            color: #003366;
        }
        .stat-box .label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th {
            background: #003366;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        table.data-table td {
            padding: 6px 5px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9px;
        }
        table.data-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-submitted {
            background: #e3f2fd;
            color: #1565c0;
        }
        .status-late {
            background: #ffebee;
            color: #c62828;
        }
        .status-approved {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .status-revision {
            background: #fff3e0;
            color: #ef6c00;
        }
        .progress-bar {
            width: 50px;
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
        }
        .progress-fill {
            height: 100%;
            background: #003366;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .text-center {
            text-align: center;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PROGRESS MINGGUAN</h1>
        <p>Sistem Informasi PBL - Politeknik Negeri Tanah Laut</p>
        <p style="margin-top: 5px;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Filter Info -->
    <div class="filter-info">
        <span><strong>Kelas:</strong> {{ $filterInfo['class_room'] }}</span>
        <span><strong>Periode:</strong> {{ $filterInfo['week'] }}</span>
        <span><strong>Total Data:</strong> {{ $targets->count() }} pengumpulan</span>
    </div>

    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-box">
            <div class="number">{{ $stats['total'] }}</div>
            <div class="label">Total Target</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['submitted'] }}</div>
            <div class="label">Sudah Submit</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['approved'] }}</div>
            <div class="label">Disetujui</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['pending'] }}</div>
            <div class="label">Pending</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['late'] }}</div>
            <div class="label">Terlambat</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['progress_rate'] }}%</div>
            <div class="label">Progress</div>
        </div>
    </div>

    <!-- Data Table -->
    @if($targets->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Kelas</th>
                <th style="width: 100px;">Kelompok</th>
                <th style="width: 50px;">Minggu</th>
                <th>Judul Target</th>
                <th style="width: 90px;">Tgl Pengumpulan</th>
                <th style="width: 70px;">Status</th>
                <th style="width: 80px;">Progress</th>
            </tr>
        </thead>
        <tbody>
            @foreach($targets as $index => $target)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $target->group->classRoom->name ?? '-' }}</td>
                <td>
                    <strong>{{ $target->group->name ?? '-' }}</strong><br>
                    <small style="color: #666;">{{ $target->group->leader->name ?? '-' }}</small>
                </td>
                <td class="text-center">{{ $target->week_number }}</td>
                <td>{{ Str::limit($target->title, 40) }}</td>
                <td class="text-center">
                    {{ $target->completed_at ? $target->completed_at->format('d/m/Y H:i') : '-' }}
                </td>
                <td class="text-center">
                    @php
                        $statusClass = [
                            'submitted' => 'status-submitted',
                            'late' => 'status-late',
                            'approved' => 'status-approved',
                            'revision' => 'status-revision',
                        ][$target->submission_status] ?? '';
                        $statusLabel = [
                            'submitted' => 'Dikumpulkan',
                            'late' => 'Terlambat',
                            'approved' => 'Disetujui',
                            'revision' => 'Revisi',
                        ][$target->submission_status] ?? $target->submission_status;
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $target->progress_percent }}%"></div>
                    </div>
                    {{ $target->progress_percent }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <p>Tidak ada data pengumpulan yang ditemukan.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Informasi PBL</p>
        <p>Politeknik Negeri Tanah Laut &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
