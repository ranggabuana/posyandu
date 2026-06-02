<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penulis = User::first();
        $penulisId = $penulis ? $penulis->id : null;

        $beritas = [
            [
                'judul' => 'Pentingnya Imunisasi Dasar Lengkap untuk Bayi',
                'konten' => 'Imunisasi dasar lengkap sangat penting untuk melindungi bayi dari berbagai penyakit menular yang berbahaya. Posyandu Melati Sehat mengimbau kepada seluruh ibu yang memiliki bayi dan balita untuk rutin membawa anaknya ke posyandu untuk mendapatkan imunisasi sesuai jadwal. Penyakit yang dapat dicegah dengan imunisasi antara lain difteri, pertusis, tetanus, hepatitis B, polio, campak, rubela, dan lain-lain.',
                'kategori' => 'Kesehatan Bayi',
                'status' => 'publikasi',
            ],
            [
                'judul' => 'Jadwal Posyandu Bulan Ini Telah Dirilis',
                'konten' => 'Diberitahukan kepada seluruh warga Desa Banjar, jadwal pelaksanaan Posyandu untuk bulan ini telah dirilis. Silakan periksa di bagian jadwal pada website ini atau melalui papan pengumuman di Balai Desa. Pastikan membawa Buku KIA saat berkunjung.',
                'kategori' => 'Pengumuman',
                'status' => 'publikasi',
            ],
            [
                'judul' => 'Panduan Gizi Sehat untuk Ibu Hamil',
                'konten' => 'Asupan gizi yang seimbang sangat penting selama masa kehamilan untuk mendukung pertumbuhan dan perkembangan janin, serta menjaga kesehatan ibu. Makanan yang kaya akan zat besi, asam folat, kalsium, dan vitamin sangat direkomendasikan. Jangan lupa untuk rutin minum tablet tambah darah (TTD) sesuai anjuran bidan.',
                'kategori' => 'Kesehatan Ibu',
                'status' => 'publikasi',
            ],
            [
                'judul' => 'Senam Lansia Rutin Setiap Hari Minggu',
                'konten' => 'Dalam rangka menjaga kebugaran dan kesehatan para lansia di Desa Banjar, Posyandu Melati Sehat mengadakan kegiatan senam lansia rutin setiap hari Minggu pagi di halaman Balai Desa. Kegiatan ini terbuka untuk seluruh lansia. Mari bersama-sama kita wujudkan lansia yang sehat, mandiri, dan bahagia.',
                'kategori' => 'Kegiatan Lansia',
                'status' => 'publikasi',
            ],
            [
                'judul' => 'Waspada Stunting: Kenali Tanda dan Pencegahannya',
                'konten' => 'Stunting adalah kondisi gagal tumbuh pada anak balita akibat kekurangan gizi kronis, terutama pada 1.000 Hari Pertama Kehidupan (HPK). Kenali tanda-tandanya seperti tinggi badan anak yang lebih pendek dibanding anak seusianya. Pencegahan stunting dapat dilakukan dengan pemenuhan gizi sejak masa kehamilan hingga anak berusia 2 tahun.',
                'kategori' => 'Informasi Gizi',
                'status' => 'publikasi',
            ],
            [
                'judul' => 'Pembagian Vitamin A untuk Balita',
                'konten' => 'Bulan Agustus adalah bulan vitamin A! Posyandu akan membagikan kapsul vitamin A secara gratis untuk balita usia 6-59 bulan. Kapsul biru untuk balita 6-11 bulan dan kapsul merah untuk balita 12-59 bulan. Segera bawa anak Anda ke Posyandu terdekat.',
                'kategori' => 'Program Posyandu',
                'status' => 'publikasi',
            ],
            [
                'judul' => 'Tips Menjaga Kesehatan Reproduksi WUS',
                'konten' => 'Kesehatan reproduksi bagi Wanita Usia Subur (WUS) sangatlah penting. Menjaga kebersihan area kewanitaan, mengonsumsi makanan bergizi, dan rutin melakukan pemeriksaan kesehatan adalah beberapa langkah pencegahan penyakit reproduksi.',
                'kategori' => 'Kesehatan Reproduksi',
                'status' => 'publikasi',
            ],
            [
                'judul' => 'Penyuluhan Pentingnya ASI Eksklusif',
                'konten' => 'Posyandu mengadakan penyuluhan mengenai pentingnya ASI eksklusif bagi bayi usia 0-6 bulan. ASI mengandung semua nutrisi yang dibutuhkan bayi untuk tumbuh kembang optimal dan melindungi bayi dari infeksi. Kegiatan penyuluhan akan diadakan bersamaan dengan jadwal posyandu.',
                'kategori' => 'Penyuluhan',
                'status' => 'publikasi',
            ],
            [
                'judul' => 'Pentingnya Pemeriksaan Kehamilan (ANC) Teratur',
                'konten' => 'Pemeriksaan kehamilan atau Antenatal Care (ANC) sangat penting dilakukan secara rutin selama kehamilan. Tujuannya adalah untuk memantau kesehatan ibu dan janin, serta mendeteksi dini jika terdapat kelainan atau komplikasi. Segera hubungi bidan desa jika terdapat keluhan.',
                'kategori' => 'Kesehatan Ibu',
                'status' => 'publikasi',
            ],
            [
                'judul' => 'Layanan Konsultasi Keluarga Berencana (KB)',
                'konten' => 'Posyandu bekerja sama dengan Bidan Desa membuka layanan konsultasi Keluarga Berencana (KB) bagi pasangan usia subur yang ingin merencanakan kehamilan. Terdapat berbagai metode KB yang dapat dipilih sesuai dengan kondisi kesehatan dan kebutuhan keluarga.',
                'kategori' => 'Keluarga Berencana',
                'status' => 'publikasi',
            ],
        ];

        $now = Carbon::now();

        foreach ($beritas as &$berita) {
            $berita['slug'] = Str::slug($berita['judul']) . '-' . uniqid();
            $berita['penulis'] = $penulisId;
            $berita['created_at'] = $now->subDays(rand(1, 30));
            $berita['updated_at'] = $berita['created_at'];
        }

        DB::table('beritas')->insert($beritas);
    }
}
