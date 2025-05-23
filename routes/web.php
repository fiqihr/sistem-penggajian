<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\GajiSayaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KodeAksesController;
use App\Http\Controllers\PotonganGajiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TunjanganController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/guru/{id}/upload-photo', [GuruController::class, 'uploadPhoto'])->name('guru.uploadPhoto');
    Route::get('/cek-presensi', [PresensiController::class, 'cekPresensi'])->name('presensi.cek');
    Route::post('/gaji/cek-kode', [GajiSayaController::class, 'cekKode']);
    Route::post('/kode-akses/generate/{id}', [KodeAksesController::class, 'generate'])->name('kode-akses.generate');
    Route::post('/kode-akses/simpan', [KodeAksesController::class, 'simpanKode']);
    Route::get('/get-presensi', [PresensiController::class, 'getPresensiJson'])->name('presensi.get.json');
});

Route::middleware(['auth', 'check.access:admin'])->group(function () {
    Route::resource('/potongan-gaji', PotonganGajiController::class);
    Route::resource('/jabatan', JabatanController::class);
    Route::resource('/guru', GuruController::class);
    Route::resource('/presensi', PresensiController::class);
    Route::resource('/gaji', GajiController::class);
    Route::resource('/tunjangan', TunjanganController::class);
    Route::post('/kirim-gaji/{id}', [GajiController::class, 'kirimGaji'])->name('gaji.kirim');
    Route::get('/laporan-gaji', [GajiController::class, 'laporanGajiIndex'])->name('gaji.laporan');
    Route::post('/laporan-gaji/cetak', [GajiController::class, 'laporanGajiCetak'])->name('gaji.laporan.cetak');
    Route::get('/laporan-presensi', [PresensiController::class, 'laporanPresensiIndex'])->name('presensi.laporan');
    Route::post('/laporan-presensi/cetak', [PresensiController::class, 'laporanPresensiCetak'])->name('presensi.laporan.cetak');
    Route::get('/gaji/create/detail-gaji', [GajiController::class, 'detailGaji'])->name('gaji.detail');
});

Route::middleware(['auth', 'check.access:guru'])->group(function () {
    Route::resource('/gaji-saya', GajiSayaController::class);
    Route::get('/kode-akses', [KodeAksesController::class, 'index'])->name('kode-akses.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
