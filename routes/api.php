<?php

use App\Http\Controllers\Api\V1\InteraksiApiController;
use App\Http\Controllers\Api\V1\KependudukanApiController;
use App\Http\Controllers\Api\V1\KesehatanApiController;
use App\Http\Controllers\Api\V1\KontenApiController;
use App\Http\Controllers\Api\V1\MasterApiController;
use App\Http\Controllers\Api\V1\StatistikApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Health Check (always reachable for monitoring; CORS still applies)
    Route::get('/health', [StatistikApiController::class, 'health']);

    // Statistik & Ringkasan
    Route::middleware('api.security:read:statistik')->group(function () {
        Route::get('/statistik', [StatistikApiController::class, 'statistik']);
    });

    // Kependudukan
    Route::middleware('api.security:read:penduduk')->group(function () {
        Route::get('/penduduks', [KependudukanApiController::class, 'penduduks']);
        Route::get('/penduduks/{id}', [KependudukanApiController::class, 'pendudukShow']);
        Route::get('/ibus', [KependudukanApiController::class, 'ibus']);
    });

    // Kesehatan (Siklus Hidup)
    Route::middleware('api.security:read:kesehatan')->group(function () {
        Route::get('/ibu-hamils', [KesehatanApiController::class, 'ibuHamils']);
        Route::get('/ibu-hamils/{id}', [KesehatanApiController::class, 'ibuHamilShow']);

        Route::get('/bayi', [KesehatanApiController::class, 'bayis']);
        Route::get('/bayi/{id}', [KesehatanApiController::class, 'bayiShow']);

        Route::get('/balita', [KesehatanApiController::class, 'balitas']);
        Route::get('/balita/{id}', [KesehatanApiController::class, 'balitaShow']);

        Route::get('/bayi-balitas', [KesehatanApiController::class, 'bayiBalitas']);
        Route::get('/bayi-balitas/{id}', [KesehatanApiController::class, 'bayiBalitaShow']);
        Route::get('/pemeriksaan-balitas', [KesehatanApiController::class, 'pemeriksaanBalitas']);
        Route::get('/imunisasi-balitas', [KesehatanApiController::class, 'imunisasiBalitas']);

        Route::get('/remajas', [KesehatanApiController::class, 'remajas']);
        Route::get('/remajas/{id}', [KesehatanApiController::class, 'remajaShow']);
        Route::get('/pemeriksaan-remajas', [KesehatanApiController::class, 'pemeriksaanRemajas']);

        Route::get('/lansias', [KesehatanApiController::class, 'lansias']);
        Route::get('/lansias/{id}', [KesehatanApiController::class, 'lansiaShow']);
        Route::get('/pemeriksaan-lansias', [KesehatanApiController::class, 'pemeriksaanLansias']);

        Route::get('/wuses', [KesehatanApiController::class, 'wuses']);
        Route::get('/wuses/{id}', [KesehatanApiController::class, 'wusShow']);

        Route::get('/puses', [KesehatanApiController::class, 'puses']);
        Route::get('/puses/{id}', [KesehatanApiController::class, 'pusShow']);
    });

    // Konten Publik
    Route::middleware('api.security:read:konten')->group(function () {
        Route::get('/beritas', [KontenApiController::class, 'beritas']);
        Route::get('/beritas/{id}', [KontenApiController::class, 'beritaShow']);

        Route::get('/galeries', [KontenApiController::class, 'galeries']);
        Route::get('/galeries/{id}', [KontenApiController::class, 'galeriShow']);

        Route::get('/jadwals', [KontenApiController::class, 'jadwals']);
        Route::get('/jadwals/{id}', [KontenApiController::class, 'jadwalShow']);

        Route::get('/tims', [KontenApiController::class, 'tims']);
        Route::get('/tims/{id}', [KontenApiController::class, 'timShow']);
    });

    // Interaksi
    Route::middleware('api.security:read:interaksi')->group(function () {
        Route::get('/buku-tamus', [InteraksiApiController::class, 'bukuTamus']);
        Route::get('/buku-tamus/{id}', [InteraksiApiController::class, 'bukuTamuShow']);

        Route::get('/laporan-masyarakats', [InteraksiApiController::class, 'laporanMasyarakats']);
        Route::get('/laporan-masyarakats/{id}', [InteraksiApiController::class, 'laporanMasyarakatShow']);
    });

    // Master & Pengaturan
    Route::middleware('api.security:read:master')->group(function () {
        Route::get('/posyandus', [MasterApiController::class, 'posyandus']);
        Route::get('/posyandus/{id}', [MasterApiController::class, 'posyanduShow']);

        Route::get('/kaders', [MasterApiController::class, 'kaders']);
        Route::get('/kaders/{id}', [MasterApiController::class, 'kaderShow']);

        Route::get('/pengaturans', [MasterApiController::class, 'pengaturans']);
    });
});

