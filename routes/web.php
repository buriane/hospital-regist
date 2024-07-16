<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\CutiDokterController;

Route::get('/', [RegistrasiController::class, 'index'])->name('regis.index');

Route::get('/jadwal', [JadwalDokterController::class, 'index'])->name('jadwal.index');

Route::get('/info', [JadwalDokterController::class, 'indexinfo'])->name('info.indexinfo');

