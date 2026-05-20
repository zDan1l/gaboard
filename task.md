# GaBoard
## Sistem Penilaian Kinerja Karyawan

**Product Requirements Document (PRD)**  
**Versi 1.0**  
**Mei 2026**

---

## Daftar Isi

1. [Ringkasan Eksekutif](#ringkasan-eksekutif)
2. [Latar Belakang](#latar-belakang)
3. [Tujuan Produk](#tujuan-produk)
4. [Target User](#target-user)
5. [Fitur Utama](#fitur-utama)
6. [Output Utama](#output-utama)
7. [Metodologi Teknis](#metodologi-teknis)
8. [Alur Sistem](#alur-sistem)
9. [Persyaratan Non-Fungsional](#persyaratan-non-fungsional)
10. [Timeline & Milestone](#timeline--milestone)

---

## Ringkasan Eksekutif

GaBoard adalah sistem penilaian kinerja karyawan berbasis web yang dirancang khusus untuk memenuhi kebutuhan operasional Perusahaan. Sistem ini mengintegrasikan teknologi **Fuzzy Logic metode Sugeno** untuk menghasilkan evaluasi kinerja yang **objektif, konsisten, dan mudah dipahami** oleh seluruh level manajemen—dari HR pusat hingga manajer gerai.

Dengan pendekatan berbasis data dan logika fuzzy, GaBoard mengeliminasi bias subjektif dalam penilaian kinerja dan memberikan rekomendasi yang dapat ditindaklanjuti untuk pengembangan karir karyawan.

---

## Latar Belakang

Penilaian kinerja karyawan merupakan komponen kritis dalam manajemen sumber daya manusia. Namun, sistem penilaian tradisional sering kali:

- Bergantung pada subjektivitas penilai
- Inkonsisten dalam kriteria evaluasi antar departemen
- Sulit untuk diterima dan dipahami oleh semua pihak
- Memerlukan waktu yang lama dalam proses administrasi

GaBoard mengatasi tantangan ini dengan menerapkan **Fuzzy Logic**—sebuah metodologi yang memungkinkan sistem untuk membuat keputusan berdasarkan data yang samar atau tidak presisi, mirip dengan cara manusia berpikir dan membuat keputusan dalam kondisi ketidakpastian.

---

## Tujuan Produk

### Tujuan Utama

Menyediakan platform penilaian kinerja karyawan yang berbasis teknologi, objektif, dan mudah digunakan untuk mendukung pengambilan keputusan HR yang lebih baik.

### Tujuan Spesifik

1. Mengeliminasi bias subjektif dalam penilaian kinerja melalui logika fuzzy
2. Menghasilkan skor kinerja yang konsisten dan terstandar untuk semua karyawan
3. Mempercepat proses penilaian dan pelaporan kinerja
4. Memberikan rekomendasi aksi HR yang jelas untuk setiap kategori kinerja
5. Meningkatkan transparansi dan penerimaan proses penilaian di seluruh organisasi

---

## Target User

### User Primary
**Human Resources (HR) Department**

### Pengguna Sistem

- **HR Pusat** - Mengelola kebijakan penilaian, monitoring keseluruhan, dan analisis tren
- **Manajer Gerai/Departemen** - Melakukan penilaian terhadap staf mereka
- **Staf Karyawan** - Melihat hasil penilaian mereka (read-only access)
- **Executive/Direktur** - Melihat dashboard analytics dan laporan strategis

---

## Fitur Utama

### 5.1 Modul Input Data Penilaian

- Form input untuk kriteria penilaian yang telah ditentukan
- Skala penilaian yang mudah dipahami (numeric atau linguistic)
- Validasi data untuk memastikan kelengkapan input

### 5.2 Engine Fuzzy Logic Sugeno

- Sistem inferensi berbasis aturan fuzzy yang telah dikalibrasi
- Membership functions untuk setiap kriteria penilaian
- Defuzzification menggunakan metode Sugeno untuk output numerik

### 5.3 Dashboard & Reporting

- Dashboard interaktif menampilkan ranking karyawan
- Laporan terperinci per karyawan dengan breakdown skor
- Analytics tren kinerja per departemen dan periode
- Export data dalam format Excel/PDF

### 5.4 Manajemen User & Akses

- Role-based access control (HR Admin, Manager, Karyawan)
- Authentikasi aman dan audit trail
- Management departemen dan struktur organisasi

---

## Output Utama

GaBoard menghasilkan output utama yang menjadi basis untuk pengambilan keputusan HR:

### 6.1 Skor Kinerja Numerik (0.00 - 1.00)

Setiap karyawan menerima skor kinerja dalam rentang **0.00 hingga 1.00**, yang dihitung menggunakan Fuzzy Logic metode Sugeno berdasarkan input kriteria penilaian.

### 6.2 Kategori Tingkat Kinerja

Skor numerik diterjemahkan ke dalam kategori kinerja yang mudah dipahami:

| Rentang Skor | Kategori | Label | Aksi HR |
|---|---|---|---|
| 0.80 - 1.00 | Sangat Baik | Excellent | Promosi, bonus |
| 0.60 - 0.79 | Baik | Good | Pertahankan |
| 0.40 - 0.59 | Cukup | Fair | Pelatihan |
| 0.20 - 0.39 | Kurang | Poor | PDP intensif |
| 0.00 - 0.19 | Sangat Kurang | Very Poor | Terminasi/PHK |

### 6.3 Ranking Karyawan

Sistem menghasilkan ranking objektif karyawan berdasarkan skor kinerja, memudahkan identifikasi top performers dan karyawan yang memerlukan intervensi.

### 6.4 Rekomendasi Aksi HR

Berdasarkan kategori kinerja, sistem memberikan rekomendasi spesifik untuk tindakan HR, termasuk:

- Promosi dan pengembangan karir
- Program pelatihan dan pengembangan (PDP)
- Bonus dan insentif
- Sertifikasi profesional
- Terminasi atau pemutusan kontrak (untuk performa sangat rendah)

---

## Metodologi Teknis

### 7.1 Fuzzy Logic Metode Sugeno

Fuzzy Logic adalah metodologi yang memungkinkan komputer untuk bekerja dengan konsep yang samar atau tidak presisi, mirip dengan cara manusia berpikir. Metode Sugeno (juga dikenal sebagai Takagi-Sugeno) adalah salah satu pendekatan paling efektif dalam sistem inferensi fuzzy.

#### Komponen Utama Fuzzy System

1. **Fuzzification**: Konversi input numerik ke linguistic variables (misal: "Sangat Baik", "Baik", "Cukup")

2. **Knowledge Base**: Kumpulan fuzzy rules yang dikalibrasi berdasarkan expertise HR dan analisis historis

3. **Inference Engine**: Proses evaluasi rules menggunakan operator logika fuzzy (AND, OR, NOT)

4. **Defuzzification**: Konversi output fuzzy ke nilai numerik spesifik (0.00-1.00) menggunakan metode Sugeno

### 7.2 Kriteria Penilaian

Sistem akan mengevaluasi karyawan berdasarkan kriteria komprehensif yang meliputi:

| No. | Kriteria | Deskripsi |
|---|---|---|
| 1 | Kuantitas Kerja | Volume output & target penjualan |
| 2 | Kualitas Kerja | Akurasi, ketelitian, dan standar kualitas |
| 3 | Kedisiplinan | Kehadiran, ketepatan waktu, mematuhi aturan |
| 4 | Inisiatif & Kreativitas | Inovasi, proaktif, continuous improvement |
| 5 | Kerjasama Tim | Kolaborasi, komunikasi, sinergi departemen |

### 7.3 Proses Perhitungan

Setiap kriteria akan memiliki fuzzy sets dan membership functions yang didefinisikan. Proses perhitungan mengikuti langkah:

1. Input numerik dari setiap kriteria dikonversi ke linguistic variables
2. Evaluasi terhadap knowledge base dengan IF-THEN rules
3. Agregasi output dari semua rules
4. Defuzzification menghasilkan skor akhir (0.00-1.00)

---

## Alur Sistem

Berikut adalah alur umum penggunaan sistem GaBoard:

1. **Login & Authentikasi**: User melakukan login dengan kredensial yang aman

2. **Navigasi Dashboard**: Pengguna melihat dashboard sesuai role mereka

3. **Input Penilaian (untuk Manajer)**: Manajer mengisi form penilaian untuk staff mereka

4. **Pemrosesan Fuzzy Logic**: Sistem melakukan perhitungan otomatis menggunakan engine fuzzy

5. **Hasil Penilaian**: Sistem menghasilkan skor numerik dan kategori kinerja

6. **Rekomendasi & Laporan**: Sistem memberikan rekomendasi aksi HR dan laporan terperinci

7. **Export & Sharing**: HR dapat mengexport dan membagikan laporan kepada stakeholder

---

## Persyaratan Non-Fungsional

### 9.1 Performance

- Response time untuk perhitungan fuzzy < 2 detik
- Mampu menangani 1000+ evaluasi karyawan per periode

### 9.2 Security

- Enkripsi data menggunakan SSL/TLS
- Authentikasi multi-factor (optional)
- Audit trail untuk semua aktivitas penilaian

### 9.3 Usability

- Interface user-friendly dan responsif (mobile-friendly)
- Dokumentasi lengkap dan training untuk semua user

### 9.4 Availability

- Uptime 99.5% untuk operasional bisnis
- Backup otomatis data setiap hari

---

## Timeline & Milestone

| Phase | Milestone | Timeline |
|---|---|---|
| Phase 1 | Requirement & Design | Bulan 1 |
| Phase 2 | Development & Fuzzy Logic Implementation | Bulan 2-3 |
| Phase 3 | Testing & QA | Bulan 4 |
| Phase 4 | Training & Deployment | Bulan 5 |
| Phase 5 | Go-Live & Support | Bulan 5 onwards |

---

## Kesimpulan

GaBoard merupakan solusi inovatif yang menggabungkan teknologi Fuzzy Logic dengan kebutuhan praktis HR dalam penilaian kinerja karyawan. Dengan sistem ini, Perusahaan dapat:

- Meningkatkan objektivitas dan konsistensi penilaian
- Mempercepat proses administrasi HR
- Membuat keputusan yang lebih informed dan data-driven
- Meningkatkan kepuasan dan penerimaan karyawan terhadap proses penilaian
- Mendukung pengembangan karir karyawan secara lebih terstruktur

Implementasi GaBoard diharapkan dapat memberikan dampak signifikan terhadap efektivitas manajemen sumber daya manusia di Perusahaan.

---

**End of Document**