<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;

// Public routes
Route::get('/', [\App\Http\Controllers\LandingPageController::class, 'index'])->name('landing');
Route::get('/berita', [\App\Http\Controllers\LandingPageController::class, 'news'])->name('public.news.index');
Route::get('/berita/{id}', [\App\Http\Controllers\LandingPageController::class, 'newsDetail'])->name('public.news.show');
Route::get('/gallery', [\App\Http\Controllers\LandingPageController::class, 'gallery'])->name('public.gallery.index');
Route::get('/aduan', [\App\Http\Controllers\LandingPageController::class, 'aduan'])->name('public.aduan');
Route::get('/aduan/refresh-captcha', [\App\Http\Controllers\LandingPageController::class, 'refreshCaptcha'])->name('public.aduan.refresh-captcha');
Route::get('/aduan/cek-status', [\App\Http\Controllers\LandingPageController::class, 'cekStatusAduan'])->name('public.aduan.cek-status');
Route::post('/aduan', [\App\Http\Controllers\LandingPageController::class, 'submitAduan'])->name('public.aduan.submit');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/sync-asmara', [\App\Http\Controllers\SyncAsmaraController::class, 'sync'])->name('sync-asmara');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('posyandus/export', [\App\Http\Controllers\PosyanduController::class, 'export'])->name('posyandus.export');
    Route::get('posyandus/rws', [\App\Http\Controllers\PosyanduController::class, 'getRws'])->name('posyandus.rws');
    Route::resource('posyandus', \App\Http\Controllers\PosyanduController::class);
    Route::get('kaders/get-penduduks', [\App\Http\Controllers\KaderController::class, 'getPenduduks'])->name('kaders.get-penduduks');
    Route::resource('kaders', \App\Http\Controllers\KaderController::class);

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    Route::get('penduduks/export', [\App\Http\Controllers\PendudukController::class, 'export'])->name('penduduks.export');
    Route::resource('penduduks', \App\Http\Controllers\PendudukController::class);

    Route::get('ibus/export', [\App\Http\Controllers\IbuController::class, 'export'])->name('ibus.export');
    Route::resource('ibus', \App\Http\Controllers\IbuController::class);

    Route::get('ibu-hamils/export', [\App\Http\Controllers\IbuHamilController::class, 'export'])->name('ibu-hamils.export');
    Route::get('ibu-hamils/get-suami', [\App\Http\Controllers\IbuHamilController::class, 'getSuamiByIbu'])->name('ibu-hamils.get-suami');
    Route::get('ibu-hamils/{ibu_hamil}/pemeriksaan', [\App\Http\Controllers\IbuHamilController::class, 'pemeriksaan'])->name('ibu-hamils.pemeriksaan');
    Route::put('ibu-hamils/{ibu_hamil}/pemeriksaan', [\App\Http\Controllers\IbuHamilController::class, 'updatePemeriksaan'])->name('ibu-hamils.update-pemeriksaan');
    Route::resource('ibu-hamils', \App\Http\Controllers\IbuHamilController::class);

    Route::get('bayi-balitas/export', [\App\Http\Controllers\BayiBalitaController::class, 'export'])->name('bayi-balitas.export');
    Route::get('bayi-balitas/{bayi_balita}/pemeriksaan', [\App\Http\Controllers\BayiBalitaController::class, 'pemeriksaan'])->name('bayi-balitas.pemeriksaan');
    Route::put('bayi-balitas/{bayi_balita}/pemeriksaan', [\App\Http\Controllers\BayiBalitaController::class, 'updatePemeriksaan'])->name('bayi-balitas.update-pemeriksaan');
    Route::get('bayi-balitas/{bayi_balita}/imunisasi', [\App\Http\Controllers\BayiBalitaController::class, 'imunisasi'])->name('bayi-balitas.imunisasi');
    Route::post('bayi-balitas/{bayi_balita}/imunisasi', [\App\Http\Controllers\BayiBalitaController::class, 'storeImunisasi'])->name('bayi-balitas.store-imunisasi');
    Route::put('pemeriksaans/{pemeriksaan}', [\App\Http\Controllers\BayiBalitaController::class, 'updatePemeriksaanRecord'])->name('pemeriksaans.update');
    Route::delete('pemeriksaans/{pemeriksaan}', [\App\Http\Controllers\BayiBalitaController::class, 'destroyPemeriksaan'])->name('pemeriksaans.destroy');
    Route::put('imunisasis/{imunisasi}', [\App\Http\Controllers\BayiBalitaController::class, 'updateImunisasiRecord'])->name('imunisasis.update');
    Route::delete('imunisasis/{imunisasi}', [\App\Http\Controllers\BayiBalitaController::class, 'destroyImunisasi'])->name('imunisasis.destroy');
    Route::resource('bayi-balitas', \App\Http\Controllers\BayiBalitaController::class);

    Route::get('balitas/export', [\App\Http\Controllers\BalitaController::class, 'export'])->name('balitas.export');
    Route::get('balitas/{balita}/pemeriksaan', [\App\Http\Controllers\BalitaController::class, 'pemeriksaan'])->name('balitas.pemeriksaan');
    Route::put('balitas/{balita}/pemeriksaan', [\App\Http\Controllers\BalitaController::class, 'updatePemeriksaan'])->name('balitas.update-pemeriksaan');
    Route::get('balitas/{balita}/imunisasi', [\App\Http\Controllers\BalitaController::class, 'imunisasi'])->name('balitas.imunisasi');
    Route::post('balitas/{balita}/imunisasi', [\App\Http\Controllers\BalitaController::class, 'storeImunisasi'])->name('balitas.store-imunisasi');
    Route::resource('balitas', \App\Http\Controllers\BalitaController::class);

    Route::get('lansias/export', [\App\Http\Controllers\LansiaController::class, 'export'])->name('lansias.export');
    Route::resource('lansias', \App\Http\Controllers\LansiaController::class);

    Route::get('wuses/export', [\App\Http\Controllers\WusController::class, 'export'])->name('wuses.export');
    Route::resource('wuses', \App\Http\Controllers\WusController::class);

    Route::get('puses/export', [\App\Http\Controllers\PusController::class, 'export'])->name('puses.export');
    Route::get('puses/get-istri', [\App\Http\Controllers\PusController::class, 'getIstriBySuami'])->name('puses.get-istri');
    Route::resource('puses', \App\Http\Controllers\PusController::class);

    Route::get('beritas/export', [\App\Http\Controllers\BeritaController::class, 'export'])->name('beritas.export');
    Route::resource('beritas', \App\Http\Controllers\BeritaController::class);

    Route::get('jadwals/export', [\App\Http\Controllers\JadwalController::class, 'export'])->name('jadwals.export');
    Route::resource('jadwals', \App\Http\Controllers\JadwalController::class);

    Route::get('tims/export', [\App\Http\Controllers\TimController::class, 'export'])->name('tims.export');
    Route::resource('tims', \App\Http\Controllers\TimController::class);

    Route::get('galeries/export', [\App\Http\Controllers\GaleriController::class, 'export'])->name('galeries.export');
    Route::resource('galeries', \App\Http\Controllers\GaleriController::class)->parameters(['galeries' => 'galeri']);

    Route::get('buku-tamus/export', [\App\Http\Controllers\BukuTamuController::class, 'export'])->name('buku-tamus.export');
    Route::resource('buku-tamus', \App\Http\Controllers\BukuTamuController::class);

    Route::get('laporan-masyarakats/export', [\App\Http\Controllers\LaporanMasyarakatController::class, 'export'])->name('laporan-masyarakats.export');
    Route::resource('laporan-masyarakats', \App\Http\Controllers\LaporanMasyarakatController::class);

    Route::get('pengaturans/export', [\App\Http\Controllers\PengaturanController::class, 'export'])->name('pengaturans.export');
    Route::resource('pengaturans', \App\Http\Controllers\PengaturanController::class)->only(['index', 'edit', 'update']);
});
