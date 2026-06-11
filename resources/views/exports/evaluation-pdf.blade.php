<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Kinerja Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f97316;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #f97316;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
            font-size: 14px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .info-item strong {
            display: block;
            color: #555;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .info-item span {
            color: #333;
            font-size: 14px;
        }
        .score-box {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            margin: 20px 0;
        }
        .score-box .score {
            font-size: 48px;
            font-weight: bold;
            margin: 10px 0;
        }
        .score-box .category {
            font-size: 24px;
            margin-top: 10px;
        }
        .criteria-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .criteria-item {
            text-align: center;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .criteria-item .value {
            font-size: 32px;
            font-weight: bold;
            color: #f97316;
            margin: 10px 0;
        }
        .criteria-item .label {
            font-size: 12px;
            color: #666;
        }
        .recommendation {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .recommendation h3 {
            color: #1e40af;
            margin: 0 0 10px;
            font-size: 16px;
        }
        .recommendation p {
            color: #1e3a8a;
            margin: 0;
            line-height: 1.6;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        @media print {
            body {
                padding: 0;
            }
            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hasil Penilaian Kinerja Karyawan</h1>
            <p>Sistem Penilaian Kinerja GaBoard</p>
            <p>Perusahaan - GaBoard</p>
        </div>

        <div class="section">
            <h2>Informasi Karyawan</h2>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Nama Karyawan</strong>
                    <span>{{ $evaluation->employee->user->name }}</span>
                </div>
                <div class="info-item">
                    <strong>NIP</strong>
                    <span>{{ $evaluation->employee->employee_code }}</span>
                </div>
                <div class="info-item">
                    <strong>Jabatan</strong>
                    <span>{{ $evaluation->employee->position }}</span>
                </div>
                <div class="info-item">
                    <strong>Departemen</strong>
                    <span>{{ $evaluation->employee->department->name }}</span>
                </div>
                <div class="info-item">
                    <strong>Periode Penilaian</strong>
                    <span>{{ $evaluation->evaluation_period }}</span>
                </div>
                <div class="info-item">
                    <strong>Tanggal Penilaian</strong>
                    <span>{{ $evaluation->created_at->format('d F Y') }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Hasil Perhitungan Skor Kinerja</h2>
            <div class="score-box">
                <div>Skor Kinerja</div>
                <div class="score">{{ $evaluation->fuzzy_score ?? 'N/A' }}</div>
                <div class="category">{{ $evaluation->category_label }}</div>
            </div>
        </div>

        <div class="section">
            <h2>Kriteria Penilaian</h2>
            <div class="criteria-grid">
                <div class="criteria-item">
                    <div class="label">KPI Pencapaian</div>
                    <div class="value">{{ number_format($evaluation->kpi_score, 1) }}%</div>
                    <div class="label">Target Penjualan</div>
                </div>
                <div class="criteria-item">
                    <div class="label">Tingkat Kehadiran</div>
                    <div class="value">{{ number_format($evaluation->attendance_rate, 1) }}%</div>
                    <div class="label">Kedisiplinan</div>
                </div>
                <div class="criteria-item">
                    <div class="label">Kepuasan Pelanggan</div>
                    <div class="value">{{ number_format($evaluation->customer_satisfaction, 1) }}</div>
                    <div class="label">Kualitas Layanan</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="recommendation">
                <h3>Rekomendasi HR</h3>
                <p>{{ $evaluation->hr_recommendation }}</p>
            </div>
        </div>

        @if($evaluation->notes)
        <div class="section">
            <h2>Catatan Tambahan</h2>
            <p>{{ $evaluation->notes }}</p>
        </div>
        @endif

        <div class="section">
            <h2>Informasi Penilai</h2>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Nama Penilai</strong>
                    <span>{{ $evaluation->evaluator->name }}</span>
                </div>
                <div class="info-item">
                    <strong>Jabatan Penilai</strong>
                    <span>{{ $evaluation->evaluator->employee->position ?? 'HR' }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Dokumen ini dihasilkan secara otomatis oleh sistem GaBoard</p>
            <p>Tanggal cetak: {{ date('d F Y H:i') }}</p>
            <p>© 2026 Perusahaan - Sistem Penilaian Kinerja Karyawan</p>
        </div>
    </div>
</body>
</html>