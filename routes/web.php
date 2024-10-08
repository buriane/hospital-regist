<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\CutiDokterController;

Route::get('/', [RegistrasiController::class, 'index'])->name('regis.index');
Route::post('/', [RegistrasiController::class, 'store'])->name('regis.store');
Route::get('/jadwal/{tgl}', [RegistrasiController::class, 'jadwal'])->name('regis.jadwal');
Route::get('/check-patient', [RegistrasiController::class, 'checkPatient'])->name('regis.check');
Route::get('/check-email/{email}', [RegistrasiController::class, 'emailCheck'])->name('regis.emailCheck');
Route::get('/check-no-kartu/{noKartu}', [RegistrasiController::class, 'noKartuCheck'])->name('regis.noKartuCheck');
Route::get('/download/pdf/{kode}/{tanggal}', [RegistrasiController::class, 'download_pdf'])->name('download.pdf');

Route::get('/jadwal', [JadwalDokterController::class, 'index'])->name('jadwal.index');

Route::get('/info', [JadwalDokterController::class, 'indexinfo'])->name('info.indexinfo');

