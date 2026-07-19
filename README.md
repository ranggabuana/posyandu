# 🏥 Sistem Informasi Posyandu (SIP) - Desa Banjar

[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-^8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38BDF8?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License MIT](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**SIP Posyandu Banjar** adalah platform sistem informasi manajemen kesehatan posyandu dan kependudukan berbasis web terintegrasi. Platform ini dirancang khusus untuk memodernisasi tata kelola Posyandu, mempercepat pencatatan rekam medis digital lintas siklus hidup (*Life Course Approach*), serta mendukung deteksi dini stunting (WHO Standard), penyakit tidak menular (PTM), dan kesehatan remaja sesuai standar **Kementerian Kesehatan Republik Indonesia (Kemenkes RI)**.

---

## 🌟 Fitur Unggulan & Inovasi Utama

### 1. 👶 Pemantauan Tumbuh Kembang Balita & Kalkulasi Stunting WHO
- **Perhitungan Z-Score Presisi (Permenkes RI No. 2 Tahun 2020):**
  - Standar Antropometri Anak WHO 2006 untuk **BB/U** (Berat Badan menurut Umur) dan **TB/U** (Tinggi/Panjang Badan menurut Umur).
  - Menggunakan rumus *LMS* ($Z = \frac{(Y/M)^L - 1}{L \times S}$) dan Standar Deviasi ($Z = \frac{Y - M}{SD}$).
  - Menampilkan badge nilai Z-Score numerik presisi (contoh: `Z: -0.45 SD`, `Z: -2.34 SD`) beserta klasifikasi otomatis (*Sangat Pendek / Stunting, Pendek, Normal, Tinggi*).
- **Rumus Terintegrasi & Modal Antropometri:**
  - Dilengkapi modal rumus WHO interaktif (`#antropometri-detail-modal`) dan penjelasan subteks rumus langsung pada header tabel riwayat penimbangan bulanan.
- **Manajemen Imunisasi Lengkap:**
  - Pencatatan jadwal dan status imunisasi wajib & tambahan anak.

---

### 2. 👵 Pemeriksaan Kesehatan Lansia (Standar PTM Kemenkes RI)
- **Skrining Penyakit Tidak Menular (PTM):**
  - Tekanan Darah / Tensi (Sistolik & Diastolik) $\rightarrow$ *Hipertensi D2, Hipertensi D1, Prehipertensi, Normal*.
  - Kadar Gula Darah (Sewaktu / Puasa) $\rightarrow$ *Diabetes, Pre-Diabetes, Normal*.
  - Kadar Kolesterol & Asam Urat (mg/dL) $\rightarrow$ *Tinggi, Batas Tinggi, Normal*.
  - Indeks Massa Tubuh (IMT) & Lingkar Perut (cm) $\rightarrow$ *Sangat Kurus, Kurus, Normal, Gemuk, Obesitas*.
- **Modal Standar Medis & Edukasi:**
  - Tombol *"Detail Standar Medis Kemenkes RI"* (`#lansia-ref-modal`) untuk rujukan batas normal laboratorium dan vital sign.

---

### 3. 🎓 Skrining Kesehatan Remaja (PKPR Kemenkes RI)
- **Pelayanan Kesehatan Peduli Remaja (PKPR):**
  - Rentang usia remaja dinamis melalui **Pengaturan Sistem** (Default: 10 - 18 Tahun).
  - **Skrining KEK (Kurang Energi Kronis):** Pengukuran LiLA (Lingkar Lengan Atas) dengan batas KEK $< 23.5\text{ cm}$.
  - **Skrining Anemia:** Kadar Hemoglobin / HB (g/dL) dengan kategori *Anemia Berat, Sedang, Ringan, Normal*.
  - **Pemberian Tablet Tambah Darah (TTD):** Pencatatan pemberian suplemen TTD remaja putri/putra.
  - Skrining IMT, Tekanan Darah, Gula Darah, Anamnesa Keluhan & Catatan Konseling.

---

### 4. 🤰 Kesehatan Ibu Hamil, WUS & PUS
- **Ibu Hamil:** Monitoring lingkar lengan, grafik kenaikan berat badan, pemberian vitamin/suplemen, deteksi resiko tinggi kehamilan, serta integrasi data suami.
- **WUS (Wanita Usia Subur) & PUS (Pasangan Usia Subur):** Pemantauan kesehatan reproduksi dan pendataan KB secara berkala.

---

### 5. 🏛️ Kependudukan & Sinkronisasi OpenSID
- **Hierarchical Filter System:** Filter bertingkat otomatis berdasarkan **Dusun**, **RW**, dan **RT** pada seluruh halaman data kesehatan & kependudukan.
- **Sinkronisasi OpenSID / SID Asmara:** Integrasi sekali klik untuk impor & penyelarasan data penduduk desa secara otomatis.

---

### 6. 🌐 Portal Web Publik (Landing Page) & Interaksi Warga
- **Portal Informasi Publik:**
  - Berita & Artikel Posyandu, Galeri Foto Kegiatan Desa, Jadwal Pelayanan Rutin Posyandu tiap RW/Dusun, dan Profil Tim Kesehatan/Kader.
- **Buku Tamu Digital & Pengaduan Masyarakat:**
  - Fitur pelaporan aduan warga online dengan nomor tiket pelacakan status (*Menunggu, Diproses, Selesai*).

---

### 7. 🎨 UI/UX Modern & Component Architecture
- **Konsistensi Header (`<x-page-header>`):**
  - Seluruh halaman dilengkapi navigasi *Breadcrumbs*, ikon MDI modul, judul, subjudul, dan tombol aksi terstandarisasi.
- **Kartu Identitas Gender-Aware:**
  - Tema visual dinamis (*Gradient Blue* untuk Pria / *Gradient Pink* untuk Wanita).
- **Export Data Excel Pro:**
  - Seluruh modul dilengkapi fitur Export Excel (`.xlsx`) berformat rapi, lengkap dengan header khusus, border, dan penanganan format teks NIK/WA.

---

## 💻 Arsitektur & Teknologi

| Komponen | Teknologi / Library |
| :--- | :--- |
| **Framework Utama** | Laravel 12.x (PHP ^8.2) |
| **Frontend Styling** | Tailwind CSS 3.4 + Vanilla CSS Custom Design |
| **Icon Systems** | Material Design Icons (MDI 7.4) |
| **Interaktivitas UI** | SweetAlert2, Alpine.js, Select2 |
| **Autentikasi & Akses** | Laravel Sanctum + Spatie Laravel-Permission |
| **Export Engine** | Maatwebsite Excel (`maatwebsite/excel`) |
| **Database** | MySQL / MariaDB / SQLite |

---

## ⚡ Panduan Instalasi (Local Development)

### 1. Prasyarat Sistem
- PHP $\ge$ 8.2 (dengan ekstensi `pdo`, `mbstring`, `openssl`, `curl`, `xml`, `zip`, `gd`)
- Composer $\ge$ 2.x
- Node.js $\ge$ 18.x & NPM
- Database MySQL / MariaDB (Laragon / XAMPP)

### 2. Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/username/posyandu-banjar.git
cd posyandu-banjar

# 2. Install dependensi PHP & Node.js
composer install
npm install

# 3. Konfigurasi File Environment
cp .env.example .env

# 4. Generate Application Key
php artisan key:generate

# 5. Konfigurasi Database di .env
# Edit .env dan atur DB_DATABASE=posyandu_banjar

# 6. Jalankan Migrasi & Database Seeder
php artisan migrate --seed

# 7. Compile Aset Frontend
npm run build

# 8. Jalankan Local Server
php artisan serve
```

Akses aplikasi di browser melalui URL: `http://localhost:8000`

---

## 🛡️ Hak Akses & Pengguna Bawaan (Default Credentials)

| Peran (Role) | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@posyandu.id` | `password` | Akses Penuh Sistem & Pengaturan |
| **Kader Posyandu** | `kader@posyandu.id` | `password` | Akses Pendataan & Pemeriksaan Kesehatan |

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [Lisensi MIT](LICENSE). Bebas digunakan dan dikembangkan untuk kemajuan pelayanan kesehatan masyarakat.

---

<p center="text-center">
  Dibuat dengan ❤️ untuk <strong>Posyandu Melati Sehat Desa Banjar</strong>
</p>
