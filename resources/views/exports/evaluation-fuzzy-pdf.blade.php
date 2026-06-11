<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian - {{ $evaluation->employee->user->name }}</title>
    <style>
        @page {
            margin: 28mm 25mm 25mm 25mm;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #111;
            background: #fff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 52px;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            margin-bottom: 28px;
            padding-bottom: 14px;
            border-bottom: 2.5px solid #111;
        }

        .header .badge {
            display: inline-block;
            font-size: 8pt;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #555;
            border: 1px solid #aaa;
            padding: 2px 10px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 17pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 4px;
        }

        .header h2 {
            font-size: 10.5pt;
            font-weight: normal;
            color: #444;
            letter-spacing: 1px;
        }

        /* ── Section ── */
        .section {
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #111;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1.5px solid #111;
        }

        /* ── Info Grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 24px;
        }

        .info-row {
            display: flex;
            padding: 5px 0;
            border-bottom: 0.5px solid #e0e0e0;
        }

        .info-label {
            font-weight: bold;
            width: 140px;
            flex-shrink: 0;
            font-size: 10pt;
            color: #444;
        }

        .info-value {
            flex: 1;
            font-size: 10pt;
            color: #111;
        }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10pt;
        }

        table th {
            background-color: #111;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 1px;
            padding: 9px 12px;
            text-align: left;
        }

        table td {
            border: 0.75px solid #ccc;
            padding: 8px 12px;
            color: #222;
        }

        table tr:nth-child(even) td {
            background-color: #f6f6f6;
        }

        table tr:hover td {
            background-color: #efefef;
        }

        table td.num {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        /* ── Result Box ── */
        .result-box {
            border: 2px solid #111;
            padding: 28px 20px;
            text-align: center;
            margin: 18px 0;
            background-color: #f9f9f9;
            position: relative;
        }

        .result-label {
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #555;
            margin-bottom: 6px;
        }

        .result-score {
            font-size: 52pt;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            color: #111;
            line-height: 1.1;
            margin: 8px 0 14px;
        }

        .result-category {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 4px;
            padding: 8px 32px;
            border: 2px solid #111;
            display: inline-block;
            background: #111;
            color: #fff;
        }

        /* ── Category Table: active row ── */
        tr.active-row td {
            border: 2px solid #111 !important;
            font-weight: bold;
        }

        /* ── Recommendation ── */
        .recommendation {
            border-left: 3px solid #111;
            padding: 12px 16px;
            margin: 12px 0;
            background-color: #f7f7f7;
        }

        .recommendation-title {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            font-size: 9pt;
            color: #444;
        }

        .recommendation-text {
            text-align: justify;
            line-height: 1.7;
            font-size: 10.5pt;
        }

        /* ── Notes ── */
        .notes {
            border: 0.75px dashed #888;
            padding: 10px 14px;
            margin: 12px 0;
            font-style: italic;
            font-size: 10pt;
            color: #444;
            background-color: #fafafa;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 0.75px solid #ccc;
            margin: 20px 0;
        }

        /* ── Signature ── */
        .signature-section {
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .signature-box {
            flex: 1;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #111;
            height: 64px;
            margin-bottom: 8px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 10pt;
            color: #111;
        }

        .signature-title {
            font-size: 8.5pt;
            color: #666;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 36px;
            padding-top: 12px;
            border-top: 0.75px solid #bbb;
            text-align: center;
            font-size: 8.5pt;
            color: #777;
            line-height: 1.8;
        }

        .footer strong {
            color: #444;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Header -->
        <div class="header">
            <div class="badge">Confidential Document</div>
            <h1>Laporan Penilaian Kinerja</h1>
            <h2>Sistem GaBoard &mdash; Sistem Penilaian Kinerja</h2>
        </div>

        <!-- Section 1: Informasi Karyawan -->
        <div class="section">
            <div class="section-title">Informasi Karyawan</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-value">{{ $evaluation->employee->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIP</span>
                    <span class="info-value">{{ $evaluation->employee->employee_code }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jabatan</span>
                    <span class="info-value">{{ $evaluation->employee->position }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Departemen</span>
                    <span class="info-value">{{ $evaluation->employee->department->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Periode</span>
                    <span class="info-value">{{ $evaluation->evaluation_period }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Penilaian</span>
                    <span class="info-value">{{ $evaluation->created_at->format('d F Y') }}</span>
                </div>
            </div>
        </div>

        <hr class="divider">

        <!-- Section 2: Variabel Penilaian -->
        <div class="section">
            <div class="section-title">Variabel Penilaian</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Variabel</th>
                        <th style="width: 110px; text-align: right;">Nilai</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="num">1</td>
                        <td>KPI Pencapaian</td>
                        <td class="num">{{ number_format($evaluation->kpi_score, 1) }}%</td>
                        <td>Target penjualan / pencapaian kerja</td>
                    </tr>
                    <tr>
                        <td class="num">2</td>
                        <td>Tingkat Kehadiran</td>
                        <td class="num">{{ number_format($evaluation->attendance_rate, 1) }}%</td>
                        <td>Kedisiplinan kehadiran kerja</td>
                    </tr>
                    <tr>
                        <td class="num">3</td>
                        <td>Kepuasan Pelanggan</td>
                        <td class="num">{{ number_format($evaluation->customer_satisfaction, 1) }}</td>
                        <td>Kualitas layanan (skala 1&ndash;10)</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr class="divider">

        <!-- Section 3: Hasil Penilaian -->
        <div class="section">
            <div class="section-title">Hasil Penilaian</div>

            <div class="result-box">
                <div class="result-label">Skor Kinerja</div>
                <div class="result-score">{{ number_format($evaluation->fuzzy_score, 2) }}</div>
                <div class="result-category">{{ $evaluation->category_label }}</div>
            </div>

            <table style="margin-top: 14px;">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th style="width: 160px; text-align: right;">Interval Skor</th>
                        <th style="width: 80px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="{{ $evaluation->category === 'sangat_baik' ? 'active-row' : '' }}">
                        <td>Sangat Baik</td>
                        <td class="num">0.85 &ndash; 1.00</td>
                        <td style="text-align: center;">{{ $evaluation->category === 'sangat_baik' ? '&#10003;' : '' }}</td>
                    </tr>
                    <tr class="{{ $evaluation->category === 'baik' ? 'active-row' : '' }}">
                        <td>Baik</td>
                        <td class="num">0.65 &ndash; 0.84</td>
                        <td style="text-align: center;">{{ $evaluation->category === 'baik' ? '&#10003;' : '' }}</td>
                    </tr>
                    <tr class="{{ $evaluation->category === 'cukup' ? 'active-row' : '' }}">
                        <td>Cukup</td>
                        <td class="num">0.40 &ndash; 0.64</td>
                        <td style="text-align: center;">{{ $evaluation->category === 'cukup' ? '&#10003;' : '' }}</td>
                    </tr>
                    <tr class="{{ $evaluation->category === 'buruk' ? 'active-row' : '' }}">
                        <td>Buruk</td>
                        <td class="num">0.20 &ndash; 0.39</td>
                        <td style="text-align: center;">{{ $evaluation->category === 'buruk' ? '&#10003;' : '' }}</td>
                    </tr>
                    <tr class="{{ $evaluation->category === 'sangat_buruk' ? 'active-row' : '' }}">
                        <td>Sangat Buruk</td>
                        <td class="num">0.00 &ndash; 0.19</td>
                        <td style="text-align: center;">{{ $evaluation->category === 'sangat_buruk' ? '&#10003;' : '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr class="divider">

        <!-- Section 4: Rekomendasi -->
        <div class="section">
            <div class="section-title">Rekomendasi Manajemen</div>

            <div class="recommendation">
                <div class="recommendation-title">Kebijakan</div>
                <div class="recommendation-text">{{ $evaluation->hr_recommendation }}</div>
            </div>

            @if($evaluation->notes)
            <div class="notes">
                <strong>Catatan:</strong> {{ $evaluation->notes }}
            </div>
            @endif
        </div>

        <!-- Tanda Tangan -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $evaluation->employee->user->name }}</div>
                <div class="signature-title">Karyawan yang Dinilai</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $evaluation->evaluator->name }}</div>
                <div class="signature-title">Penilai</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">HR Manager</div>
                <div class="signature-title">Mengetahui</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dihasilkan secara otomatis oleh <strong>Sistem Penilaian Kinerja GaBoard</strong></p>
            <p>Dicetak pada: {{ date('d F Y, H:i') }} &nbsp;|&nbsp; &copy; 2026 &mdash; Strictly Confidential</p>
        </div>

    </div>
</body>
</html>