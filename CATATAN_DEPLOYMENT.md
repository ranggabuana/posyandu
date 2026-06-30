# Catatan Penting Deployment Laravel ke Shared Hosting (cPanel)

Dokumen ini berisi rangkuman masalah yang sering terjadi saat mengunggah (deploy) aplikasi Laravel 11/12 ke *shared hosting* dan cara menyelesaikannya.

---

## 1. Masalah Versi PHP (Web vs Terminal/CLI)
Di sebagian besar *shared hosting*, versi PHP yang diatur di menu cPanel ("Select PHP Version") **hanya berlaku untuk tampilan web (browser)**. Sedangkan terminal SSH seringkali masih menggunakan versi PHP lama bawaan sistem operasi (misalnya PHP 7.4).

Hal ini menyebabkan perintah `composer` atau `php artisan` gagal berjalan karena Laravel 11/12 membutuhkan minimal PHP 8.2.

**Solusinya (Mencari dan Menggunakan PHP yang Benar):**
Anda harus mencari lokasi eksekusi (binary) PHP versi baru di server. Biasanya ada di `/opt/alt/php83/usr/bin/php` atau `/usr/local/bin/ea-php83`.

**Cara membuat Alias (Agar tidak perlu mengetik panjang):**
Jalankan perintah ini di terminal SSH agar Anda cukup mengetik `php`:
```bash
echo "alias php='/opt/alt/php83/usr/bin/php'" >> ~/.bashrc
source ~/.bashrc
```
*(Sesuaikan path `/opt/alt/php83/...` dengan hasil pencarian di server Anda).*

---

## 2. Masalah Composer (Lock File Tidak Kompatibel)
Terkadang, meskipun sudah menggunakan PHP yang benar, `composer install` gagal dengan pesan *"Your lock file does not contain a compatible set of packages"*. Ini karena ada perbedaan minor pada ekstensi PHP antara komputer lokal dan server.

**Solusinya (Abaikan Pengecekan Ekstensi):**
Gunakan tambahan flag `--ignore-platform-reqs`:
```bash
php $(which composer) install --ignore-platform-reqs --optimize-autoloader --no-dev
```

---

## 3. Masalah URL Aplikasi Harus Menggunakan `/public`
Secara default, jika Anda menaruh kode Laravel di dalam `public_html`, aplikasi tidak akan langsung terbuka. Anda harus menambahkan `/public` di URL (contoh: `domain.com/public`). Ini buruk untuk SEO dan keamanan.

**Solusi 1: Mengubah Document Root (Paling Direkomendasikan)**
- Masuk ke cPanel -> menu **Domains**.
- Edit Document Root untuk domain tersebut.
- Tambahkan `/public` di akhirnya (misal: `/public_html/posyandu/public`).

**Solusi 2: Menggunakan .htaccess (Jika tidak bisa ubah Document Root)**
Buat file `.htaccess` di *root* folder Laravel (sejajar dengan folder `app`) dan isikan:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## 4. Masalah Vite (Manifest Not Found)
Jika menggunakan Vite (pengganti Mix di Laravel baru), Anda mungkin mendapati error layar putih bertuliskan *"Vite manifest not found"*.
Hal ini terjadi karena hasil *compile* CSS/JS berada di folder `public/build`, dan folder tersebut secara default di-blokir oleh git (`.gitignore`), sehingga tidak ikut terunggah ke hosting.

**Solusi 1: Build di Hosting (Jika ada Node.js di server)**
Jalankan di terminal SSH:
```bash
npm install
npm run build
```

**Solusi 2: Build di Komputer Lokal & Upload via Git (Paling Mudah)**
- Buka file `.gitignore` di komputer Anda.
- Cari baris `/public/build` dan hapus (atau beri tanda `#` agar jadi komentar).
- Jalankan `npm run build` di komputer Anda.
- Lakukan `git add .`, `git commit`, dan `git push` ke GitHub.
- Di server hosting, lakukan `git pull`. File aset akan ikut terunduh.
