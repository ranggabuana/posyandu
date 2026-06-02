Kamu adalah senior Laravel developer. Bantu saya membangun aplikasi manajemen Posyandu berbasis web menggunakan Laravel 12 dan Tailwind CSS. Aplikasi ini sudah memiliki sistem login dan dashboard admin (kosong). Gunakan bahasa Indonesia untuk semua label, judul, dan pesan pada antarmuka.

---

## STACK TEKNIS

- Laravel 12
- Tailwind CSS (sudah terpasang)
- Laravel Breeze / Auth sudah ada (login + dashboard)
- Database: MySQL
- Export Excel: paket `maatwebsite/excel`
- Role/permission: `spatie/laravel-permission` (role: admin, posyandu)

---

## STRUKTUR DATABASE & MODUL

### 1. TABEL `penduduks`

Kolom: id, nama, no_kk, nik (unique), nama_kk, hubungan_keluarga, kelamin (enum: laki-laki|perempuan), tempatlahir, tanggallahir, agama, pendidikan, pekerjaan, status_kawin (enum: belum kawin|kawin|cerai hidup|cerai mati), nama_ayah, nama_ibu, goldar (enum: A|B|AB|O), alamat, rw, rt, dusun, telepon, bpjs (boolean), timestamps.

### 2. TABEL `ibus` (Data Ibu)

Sumber data dari tabel penduduks dengan filter: kelamin = perempuan AND status_kawin = kawin.
Tidak perlu tabel terpisah — cukup query/relasi dari penduduks. Tampilkan dalam CRUD tersendiri dengan view yang menampilkan kolom relevan. Tambahkan kolom: posyandu_id (foreign key ke tabel posyandus jika ada).

### 3. TABEL `ibu_hamils` (Data Ibu Hamil)

Kolom: id, penduduk_id (FK ke penduduks, filter ibu), usia_kehamilan_minggu, hpht (date), taksiran_persalinan (date), jumlah_kunjungan, status (enum: aktif|selesai|gugur), keterangan, timestamps.

### 4. TABEL `bayi_balitas` (Data Bayi & Balita)

Kolom: id, penduduk_id (FK ke penduduks), nama_ibu (FK ke penduduks atau string), tanggal_lahir, berat_lahir, panjang_lahir, goldar, bpjs (boolean), posyandu_id, keterangan, timestamps.
Kategori umur otomatis dihitung dari tanggal_lahir:

- Bayi: 0–11 bulan
- Balita: 12–59 bulan

### 5. TABEL `lansias` (Data Lansia)

Kolom: id, penduduk_id (FK ke penduduks), posyandu_id, keterangan, timestamps.
Batasan umur lansia diambil dari tabel pengaturan (key: `umur_lansia_min`, default: 60 tahun). Filter otomatis berdasarkan tanggallahir di penduduks.

### 6. TABEL `wus` (Wanita Usia Subur)

Kolom: id, penduduk_id (FK ke penduduks, filter kelamin=perempuan), posyandu_id, keterangan, timestamps.
Batasan umur WUS diambil dari tabel pengaturan (key: `wus_umur_min` default:15, `wus_umur_max` default:49).

### 7. TABEL `users` (sudah ada, extend saja)

Tambahkan kolom: nama_lengkap, posyandu_id (nullable FK), role via spatie/permission.
Role: admin (akses semua), posyandu (akses terbatas per posyandu).

### 8. TABEL `beritas` (Berita / Artikel)

Kolom: id, judul, slug, konten (longText), gambar, kategori, penulis (FK users), status (enum: draft|publikasi), timestamps.

### 9. TABEL `galeries` (Galeri per Posyandu)

Kolom: id, posyandu_id, judul, foto (string path), keterangan, timestamps.

### 10. TABEL `buku_tamus`

Kolom: id, nama, instansi, keperluan, no_telepon, tanggal_kunjungan, jam_masuk, jam_keluar, keterangan, timestamps.

### 11. TABEL `laporan_masyarakats`

Kolom: id, nama_pelapor, nik_pelapor, isi_laporan, kategori, foto_bukti (nullable), status (enum: baru|diproses|selesai), balasan (nullable), timestamps.

### 12. TABEL `pengaturans`

Kolom: id, key (unique), value, label, keterangan, timestamps.
Data awal (seeder):

- umur_lansia_min = 60
- wus_umur_min = 15
- wus_umur_max = 49
- nama_aplikasi = "Sistem Informasi Posyandu"
- nama_desa, alamat_desa, logo_desa

---

## FITUR YANG WAJIB DIBANGUN

### A. CRUD LENGKAP untuk semua modul:

Setiap modul harus memiliki:

- Index (tabel dengan search, filter, pagination 15 per halaman)
- Create (form dengan validasi server-side + client-side feedback)
- Edit (form pre-filled)
- Delete (konfirmasi modal sebelum hapus)
- Show/Detail (opsional, tampilkan semua field)
- Flash message sukses/gagal setiap aksi

### B. RELASI & FILTER OTOMATIS:

- Data Ibu: otomatis filter dari penduduks (kelamin=perempuan, status_kawin=kawin)
- Data Lansia: otomatis filter umur >= pengaturan `umur_lansia_min`
- Data WUS: otomatis filter kelamin=perempuan, umur antara `wus_umur_min` dan `wus_umur_max`
- Semua filter umur dihitung real-time dari kolom `tanggallahir`

### C. EXPORT EXCEL (maatwebsite/excel):

Setiap modul memiliki tombol "Export Excel" yang mengunduh data sesuai filter aktif saat itu. Buat Export class terpisah untuk tiap modul di folder `app/Exports/`.

### D. MANAJEMEN USER:

- Admin bisa CRUD user
- Assign role: admin atau posyandu
- User posyandu hanya bisa melihat data posyandu-nya sendiri (middleware check posyandu_id)

### E. PENGATURAN:

- Halaman pengaturan umum (nama aplikasi, logo, alamat)
- Pengaturan kategori umur (lansia, WUS) dengan input number
- Perubahan langsung update tabel pengaturans

---

## PANDUAN IMPLEMENTASI

Buat dalam urutan ini:

1. Migration semua tabel sekaligus, lalu `php artisan migrate`
2. Seeder untuk tabel pengaturans dan user admin default
3. Model + relasi (belongsTo, hasMany)
4. Route resource di `routes/web.php` dengan middleware auth + role
5. Controller (gunakan Resource Controller) untuk tiap modul
6. Request class (FormRequest) untuk validasi tiap modul
7. Export class (maatwebsite/excel) tiap modul
8. Views Blade dengan layout admin menggunakan Tailwind CSS
9. Komponen Blade: tabel, form input, modal konfirmasi hapus, flash message, pagination

---

## TAMPILAN & UI

- Sidebar navigasi dengan grup menu: Data Kependudukan, Data Kesehatan, Konten, Pengaturan
- Header dengan nama user + tombol logout
- Tabel dengan stripe, hover highlight, tombol aksi (edit, hapus) per baris
- Badge warna untuk status (aktif=hijau, draft=abu, selesai=biru)
- Form menggunakan komponen Blade yang reusable
- Responsive mobile-friendly
- Semua teks antarmuka dalam Bahasa Indonesia

---

Mulai dari langkah 1: buat semua file migration terlebih dahulu. Tampilkan kode lengkap tiap file satu per satu. Setelah migration selesai, lanjut ke seeder, kemudian model, dst sesuai urutan di atas.
