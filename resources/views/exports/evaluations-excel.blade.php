<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data Penilaian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f97316;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #f97316;
            margin: 0;
        }
        .summary {
            margin-bottom: 30px;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 5px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #f97316;
        }
        .summary-item .label {
            font-size: 11px;
            color: #666;
        }
        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Sistem Penilaian Kinerja Karyawan - Perusahaan</p>
        <p>Tanggal Export: {{ date('d F Y H:i') }}</p>
    </div>

    @if($evaluations->count() > 0)
        <div class="summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="value">{{ $evaluations->count() }}</div>
                    <div class="label">Total Penilaian</div>
                </div>
                <div class="summary-item">
                    <div class="value">{{ number_format($evaluations->avg('fuzzy_score'), 2) }}</div>
                    <div class="label">Rata-rata Skor</div>
                </div>
                <div class="summary-item">
                    <div class="value">{{ $evaluations->where('category', 'sangat_baik')->count() + $evaluations->where('category', 'baik')->count() }}</div>
                    <div class="label">Performa Baik</div>
                </div>
                <div class="summary-item">
                    <div class="value">{{ $evaluations->where('category', 'cukup')->count() + $evaluations->where('category', 'buruk')->count() + $evaluations->where('category', 'sangat_buruk')->count() }}</div>
                    <div class="label">Perlu Perhatian</div>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>NIP</th>
                    <th>Departemen</th>
                    <th>Jabatan</th>
                    <th>Periode</th>
                    <th>KPI (%)</th>
                    <th>Kehadiran (%)</th>
                    <th>Kepuasan (1-10)</th>
                    <th>Skor Fuzzy</th>
                    <th>Kategori</th>
                    <th>Tanggal Penilaian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluations as $index => $evaluation)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $evaluation->employee->user->name }}</td>
                        <td>{{ $evaluation->employee->employee_code }}</td>
                        <td>{{ $evaluation->employee->department->name }}</td>
                        <td>{{ $evaluation->employee->position }}</td>
                        <td>{{ $evaluation->evaluation_period }}</td>
                        <td>{{ number_format($evaluation->kpi_score, 1) }}%</td>
                        <td>{{ number_format($evaluation->attendance_rate, 1) }}%</td>
                        <td>{{ number_format($evaluation->customer_satisfaction, 1) }}</td>
                        <td><strong>{{ $evaluation->fuzzy_score }}</strong></td>
                        <td>{{ $evaluation->category_label }}</td>
                        <td>{{ $evaluation->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #666;">Tidak ada data penilaian untuk ditampilkan.</p>
    @endif

    <div style="margin-top: 30px; text-align: center; color: #666; font-size: 10px;">
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem GaBoard</p>
        <p>© 2026 Perusahaan - Sistem Penilaian Kinerja Karyawan</p>
    </div>
</body>
</html>