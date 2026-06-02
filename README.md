# Sistem Informasi Posyandu (SIP)

Aplikasi **Sistem Informasi Posyandu (SIP)** adalah sistem manajemen data Posyandu dan Kependudukan berbasis web yang dibangun menggunakan **Laravel 12**. Aplikasi ini dirancang untuk memudahkan kader posyandu dan perangkat desa dalam mencatat, mengelola, dan memantau kesehatan masyarakat mulai dari bayi, balita, ibu hamil, hingga lansia.

---

## 🚀 Fitur Utama

### 1. Manajemen Kependudukan
- **Data Penduduk:** Menampilkan daftar seluruh penduduk di wilayah Posyandu.
- **Data Ibu:** Pencatatan spesifik untuk ibu rumah tangga.

### 2. Rekam Medis & Kesehatan
Aplikasi ini menyediakan pencatatan komprehensif untuk berbagai kelompok sasaran Posyandu:
- 🤰 **Ibu Hamil:** Pencatatan pemeriksaan kehamilan berkala, deteksi faktor resiko, dan pendataan suami.
- 👶 **Bayi & Balita:** Pencatatan berat badan, tinggi badan, imunisasi, dan perkembangan balita.
- 👵 **Lansia:** Pemantauan kesehatan lanjut usia.
- 👩 **WUS (Wanita Usia Subur) & PUS (Pasangan Usia Subur):** Pemantauan kesehatan reproduksi.

### 3. Modul Konten Web Publik (Landing Page)
Sistem ini tidak hanya portal admin, melainkan berfungsi sebagai *Company Profile* atau portal informasi masyarakat:
- **Berita & Artikel:** Publikasi berita atau pengumuman seputar kegiatan Posyandu.
- **Galeri Kegiatan:** Dokumentasi foto kegiatan desa dan posyandu.
- **Jadwal Pelayanan:** Informasi jadwal buka Posyandu di setiap RW/Dusun.
- **Tim Kami:** Profil kader posyandu dan perangkat yang bertugas.

### 4. Interaksi Masyarakat
- **Buku Tamu:** Pencatatan kunjungan fisik maupun virtual.
- **Laporan/Aduan Masyarakat:** Sistem pelaporan (aduan) online dari warga yang dapat ditindaklanjuti oleh pengurus (dilengkapi dengan pelacakan status laporan).

### 5. Pengaturan & Laporan
- **Export Excel:** Semua data penduduk, kesehatan, dan interaksi dapat diunduh dalam format `.xlsx`.
- **Manajemen User (Role & Permission):** Pengaturan hak akses untuk Kader, Bidan, dan Administrator.
- **Master Data Posyandu:** Mengelola data detail, wilayah Dusun, dan RW yang diampu oleh masing-masing posyandu.

---

## 🛠️ Teknologi yang Digunakan

- **Backend:** Laravel 12.x (PHP ^8.2)
- **Frontend Admin:** Blade Templates + Tailwind CSS + Select2 + SweetAlert2
- **Database:** MySQL / SQLite
- **Autentikasi & Otorisasi:** Laravel Sanctum + Spatie Laravel Permission
- **Export Data:** Maatwebsite Excel

---

## ⚙️ Panduan Instalasi (Local Development)

### 1. Kebutuhan Sistem
Pastikan sistem Anda sudah menginstal:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB (via XAMPP / Laragon dll)

### 2. Clone Repositori
```bash
git clone https://github.com/username/posyandu-banjar.git
cd posyandu-banjar
```

### 3. Instalasi Dependensi
Jalankan perintah berikut untuk menginstal dependensi PHP dan Node.js:
```bash
composer install
npm install
```

### 4. Konfigurasi Environment (`.env`)
Salin file konfigurasi bawaan dan sesuaikan nama database Anda:
```bash
cp .env.example .env
```
Buka file `.env` dan atur koneksi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=posyandu_banjar
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Key & Migrasi Database
Jalankan migrasi untuk membuat tabel beserta data awal (*seeder*):
```bash
php artisan key:generate
php artisan migrate --seed
```

### 6. Build Aset Frontend
```bash
npm run build
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui *browser* di: `http://localhost:8000`

---

## 📄 Lisensi

Aplikasi ini adalah perangkat lunak sumber terbuka yang dilisensikan di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).
