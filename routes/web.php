<?php

use App\Http\Controllers\AbsensiGuruController;
use App\Http\Controllers\AbsensiMuridController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/absensi', [AbsensiMuridController::class, 'index'])->name('absensi.index');
Route::post('/absensi/store', [AbsensiMuridController::class, 'store'])->name('absensi.store');
Route::post('/absensi/pulang', [AbsensiMuridController::class, 'pulang'])->name('absensi.pulang');

Route::get('/absensi_guru', [AbsensiGuruController::class, 'index'])->name('absensi_guru.index');
Route::post('/absensi_guru/store', [AbsensiGuruController::class, 'store'])->name('absensi_guru.store');
Route::post('/absensi_guru/pulang', [AbsensiGuruController::class, 'pulang'])->name('absensi_guru.pulang');
