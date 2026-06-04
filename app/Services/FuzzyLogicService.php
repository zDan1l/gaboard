<?php

namespace App\Services;

class FuzzyLogicService
{
    /**
     * Calculate performance evaluation using Fuzzy Logic Sugeno method.
     *
     * @param float $kpiScore KPI Pencapaian (0-100)
     * @param float $attendanceRate Tingkat Kehadiran (0-100)
     * @param float $customerSatisfaction Kepuasan Pelanggan (1-10)
     * @return array Array containing fuzzy_score, category, and recommendation
     */
    public function calculatePerformance(float $kpiScore, float $attendanceRate, float $customerSatisfaction): array
    {
        // Normalize inputs
        $kpiScore = max(0, min(100, $kpiScore));
        $attendanceRate = max(0, min(100, $attendanceRate));
        $customerSatisfaction = max(1, min(10, $customerSatisfaction));

        // Step 1: Fuzzification
        $kpiMembership = $this->fuzzifyKPI($kpiScore);
        $attendanceMembership = $this->fuzzifyAttendance($attendanceRate);
        $satisfactionMembership = $this->fuzzifySatisfaction($customerSatisfaction);

        // Step 2: Inference Engine with Rule Base
        $ruleResults = $this->applyRules($kpiMembership, $attendanceMembership, $satisfactionMembership);

        // Step 3: Defuzzification (Weighted Average - Sugeno)
        $fuzzyScore = $this->defuzzify($ruleResults);

        // Step 4: Determine category and HR recommendation
        $category = $this->determineCategory($fuzzyScore);
        $recommendation = $this->getHRRecommendation($category);

        // Get active rules (rules that fired)
        $activeRules = array_filter($ruleResults, function($rule) {
            return $rule['strength'] > 0;
        });

        // Sort active rules by strength
        uasort($activeRules, function($a, $b) {
            return $b['strength'] <=> $a['strength'];
        });

        return [
            'fuzzy_score' => round($fuzzyScore, 2),
            'category' => $category,
            'hr_recommendation' => $recommendation,
            'fuzzification_detail' => [
                'kpi' => [
                    'input' => $kpiScore,
                    'membership' => $kpiMembership,
                    'dominant' => $this->getDominantMembership($kpiMembership)
                ],
                'attendance' => [
                    'input' => $attendanceRate,
                    'membership' => $attendanceMembership,
                    'dominant' => $this->getDominantMembership($attendanceMembership)
                ],
                'satisfaction' => [
                    'input' => $customerSatisfaction,
                    'membership' => $satisfactionMembership,
                    'dominant' => $this->getDominantMembership($satisfactionMembership)
                ]
            ],
            'active_rules' => array_values($activeRules),
            'defuzzification_process' => [
                'numerator' => array_sum(array_map(function($rule) {
                    return $rule['strength'] * $rule['z'];
                }, $ruleResults)),
                'denominator' => array_sum(array_column($ruleResults, 'strength')),
                'rules_used' => count(array_filter($ruleResults, function($rule) {
                    return $rule['strength'] > 0;
                }))
            ]
        ];
    }

    /**
     * Get dominant membership (highest value)
     */
    protected function getDominantMembership(array $membership): array
    {
        arsort($membership);
        $dominantKey = array_key_first($membership);
        return [
            'category' => $dominantKey,
            'value' => $membership[$dominantKey]
        ];
    }

    /**
     * Fuzzify KPI Pencapaian (0-100)
     * Rendah: 0-60, Sedang: 50-85, Tinggi: 78-100
     */
    protected function fuzzifyKPI(float $value): array
    {
        return [
            'rendah' => $this->trapezoidalMembership($value, 0, 0, 40, 60),
            'sedang' => $this->trapezoidalMembership($value, 50, 60, 80, 85),
            'tinggi' => $this->trapezoidalMembership($value, 78, 85, 100, 100),
        ];
    }

    /**
     * Fuzzify Tingkat Kehadiran (0-100)
     * Rendah: 0-80, Sedang: 75-95, Tinggi: 90-100
     */
    protected function fuzzifyAttendance(float $value): array
    {
        return [
            'rendah' => $this->trapezoidalMembership($value, 0, 0, 60, 80),
            'sedang' => $this->trapezoidalMembership($value, 75, 80, 90, 95),
            'tinggi' => $this->trapezoidalMembership($value, 90, 95, 100, 100),
        ];
    }

    /**
     * Fuzzify Kepuasan Pelanggan (1-10)
     * Rendah: 1-5.5, Sedang: 4.5-8, Tinggi: 7-10
     */
    protected function fuzzifySatisfaction(float $value): array
    {
        return [
            'rendah' => $this->trapezoidalMembership($value, 1, 1, 4, 5.5),
            'sedang' => $this->trapezoidalMembership($value, 4.5, 5.5, 7.5, 8),
            'tinggi' => $this->trapezoidalMembership($value, 7, 8, 10, 10),
        ];
    }

    /**
     * Trapezoidal membership function.
     * μ(x) = 0 if x ≤ a or x ≥ d
     * μ(x) = (x-a)/(b-a) if a < x < b
     * μ(x) = 1 if b ≤ x ≤ c
     * μ(x) = (d-x)/(d-c) if c < x < d
     */
    protected function trapezoidalMembership(float $x, float $a, float $b, float $c, float $d): float
    {
        if ($x < $a || $x > $d) {
            return 0.0;
        }

        if ($x >= $b && $x <= $c) {
            return 1.0;
        }

        if ($x > $a && $x < $b) {
            return ($x - $a) / ($b - $a);
        }

        if ($x > $c && $x < $d) {
            return ($d - $x) / ($d - $c);
        }

        return 0.0;
    }

    /**
     * Apply fuzzy rules using MIN operator for AND.
     * Returns array of active rules with their firing strengths.
     */
    protected function applyRules(array $kpi, array $attendance, array $satisfaction): array
    {
        $rules = [];

        // Rule 1: KPI Tinggi AND Hadir Tinggi AND Puas Tinggi → 0.92
        $rules[] = [
            'strength' => min($kpi['tinggi'], $attendance['tinggi'], $satisfaction['tinggi']),
            'z' => 0.92,
        ];

        // Rule 2: KPI Tinggi AND Hadir Tinggi AND Puas Sedang → 0.83
        $rules[] = [
            'strength' => min($kpi['tinggi'], $attendance['tinggi'], $satisfaction['sedang']),
            'z' => 0.83,
        ];

        // Rule 3: KPI Tinggi AND Hadir Sedang AND Puas Tinggi → 0.80
        $rules[] = [
            'strength' => min($kpi['tinggi'], $attendance['sedang'], $satisfaction['tinggi']),
            'z' => 0.80,
        ];

        // Rule 4: KPI Sedang AND Hadir Tinggi AND Puas Tinggi → 0.78
        $rules[] = [
            'strength' => min($kpi['sedang'], $attendance['tinggi'], $satisfaction['tinggi']),
            'z' => 0.78,
        ];

        // Rule 5: KPI Tinggi AND Hadir Tinggi AND Puas Rendah → 0.70
        $rules[] = [
            'strength' => min($kpi['tinggi'], $attendance['tinggi'], $satisfaction['rendah']),
            'z' => 0.70,
        ];

        // Rule 6: KPI Tinggi AND Hadir Sedang AND Puas Sedang → 0.68
        $rules[] = [
            'strength' => min($kpi['tinggi'], $attendance['sedang'], $satisfaction['sedang']),
            'z' => 0.68,
        ];

        // Rule 7: KPI Sedang AND Hadir Sedang AND Puas Sedang → 0.55
        $rules[] = [
            'strength' => min($kpi['sedang'], $attendance['sedang'], $satisfaction['sedang']),
            'z' => 0.55,
        ];

        // Rule 8: KPI Sedang AND Hadir Tinggi AND Puas Rendah → 0.50
        $rules[] = [
            'strength' => min($kpi['sedang'], $attendance['tinggi'], $satisfaction['rendah']),
            'z' => 0.50,
        ];

        // Rule 9: KPI Sedang AND Hadir Sedang AND Puas Rendah → 0.44
        $rules[] = [
            'strength' => min($kpi['sedang'], $attendance['sedang'], $satisfaction['rendah']),
            'z' => 0.44,
        ];

        // Rule 10: KPI Rendah AND Hadir Tinggi AND Puas Sedang → 0.38
        $rules[] = [
            'strength' => min($kpi['rendah'], $attendance['tinggi'], $satisfaction['sedang']),
            'z' => 0.38,
        ];

        // Rule 11: KPI Rendah AND Hadir Sedang AND Puas Rendah → 0.20
        $rules[] = [
            'strength' => min($kpi['rendah'], $attendance['sedang'], $satisfaction['rendah']),
            'z' => 0.20,
        ];

        // Rule 12: KPI Rendah AND Hadir Rendah AND Puas Rendah → 0.08
        $rules[] = [
            'strength' => min($kpi['rendah'], $attendance['rendah'], $satisfaction['rendah']),
            'z' => 0.08,
        ];

        // Additional rules for completeness (27 total rules)
        $rules[] = ['strength' => min($kpi['tinggi'], $attendance['sedang'], $satisfaction['rendah']), 'z' => 0.60];
        $rules[] = ['strength' => min($kpi['tinggi'], $attendance['rendah'], $satisfaction['tinggi']), 'z' => 0.58];
        $rules[] = ['strength' => min($kpi['tinggi'], $attendance['rendah'], $satisfaction['sedang']), 'z' => 0.48];
        $rules[] = ['strength' => min($kpi['tinggi'], $attendance['rendah'], $satisfaction['rendah']), 'z' => 0.35];
        $rules[] = ['strength' => min($kpi['sedang'], $attendance['tinggi'], $satisfaction['sedang']), 'z' => 0.72];
        $rules[] = ['strength' => min($kpi['sedang'], $attendance['sedang'], $satisfaction['tinggi']), 'z' => 0.65];
        $rules[] = ['strength' => min($kpi['sedang'], $attendance['rendah'], $satisfaction['tinggi']), 'z' => 0.42];
        $rules[] = ['strength' => min($kpi['sedang'], $attendance['rendah'], $satisfaction['sedang']), 'z' => 0.32];
        $rules[] = ['strength' => min($kpi['sedang'], $attendance['rendah'], $satisfaction['rendah']), 'z' => 0.25];
        $rules[] = ['strength' => min($kpi['rendah'], $attendance['tinggi'], $satisfaction['tinggi']), 'z' => 0.45];
        $rules[] = ['strength' => min($kpi['rendah'], $attendance['tinggi'], $satisfaction['rendah']), 'z' => 0.30];
        $rules[] = ['strength' => min($kpi['rendah'], $attendance['sedang'], $satisfaction['tinggi']), 'z' => 0.28];
        $rules[] = ['strength' => min($kpi['rendah'], $attendance['sedang'], $satisfaction['sedang']), 'z' => 0.22];
        $rules[] = ['strength' => min($kpi['rendah'], $attendance['rendah'], $satisfaction['tinggi']), 'z' => 0.15];
        $rules[] = ['strength' => min($kpi['rendah'], $attendance['rendah'], $satisfaction['sedang']), 'z' => 0.12];

        return $rules;
    }

    /**
     * Defuzzification using weighted average (Sugeno method).
     * z* = Σ(μᵢ × zᵢ) / Σ(μᵢ)
     */
    protected function defuzzify(array $rules): float
    {
        $numerator = 0.0;
        $denominator = 0.0;

        foreach ($rules as $rule) {
            if ($rule['strength'] > 0) {
                $numerator += $rule['strength'] * $rule['z'];
                $denominator += $rule['strength'];
            }
        }

        if ($denominator == 0) {
            return 0.0;
        }

        return $numerator / $denominator;
    }

    /**
     * Determine performance category based on fuzzy score.
     */
    protected function determineCategory(float $fuzzyScore): string
    {
        if ($fuzzyScore >= 0.85) {
            return 'sangat_baik';
        }

        if ($fuzzyScore >= 0.65) {
            return 'baik';
        }

        if ($fuzzyScore >= 0.40) {
            return 'cukup';
        }

        if ($fuzzyScore >= 0.20) {
            return 'buruk';
        }

        return 'sangat_buruk';
    }

    /**
     * Get HR recommendation based on category.
     */
    protected function getHRRecommendation(string $category): string
    {
        return match($category) {
            'sangat_baik' => 'Rekomendasikan bonus kinerja dan fast-track karir. Masukkan ke talent pool unggulan Gacoan. Pertimbangkan promosi ke posisi yang lebih tanggung jawab.',
            'baik' => 'Berikan apresiasi formal. Identifikasi peluang promosi atau penambahan tanggung jawab. Pertahankan performa saat ini dan kembangkan potensi kepemimpinan.',
            'cukup' => 'Daftarkan ke program pelatihan & mentoring. Tetapkan target pengembangan kuartal berikutnya. Evaluasi area yang memerlukan perbaikan dan buat action plan.',
            'buruk' => 'Laksanakan PIP (Performance Improvement Plan). Konseling wajib dengan atasan untuk identifikasi akar masalah. Evaluasi ulang dalam 30 hari dengan target peningkatan yang jelas.',
            'sangat_buruk' => 'Evaluasi serius untuk terminasi atau pemutusan kontrak. Lakukan prosedur HR sesuai kebijakan perusahaan. Dokumentasikan semua percobaan perbaikan sebelum pengambilan keputusan final.',
            default => 'Tidak ada rekomendasi tersedia.',
        };
    }
}
