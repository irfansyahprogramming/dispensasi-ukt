<?php

use App\Http\Controllers\DataUKTController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DispensasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeneriamDispensasiController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\VerifikasiDekanController;
use App\Http\Controllers\VerifikasiUKTController;
use App\Http\Controllers\VerifikasiWR2Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/attemptLogin', [LoginController::class, 'attemptLogin'])->name('login.attemptLogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dispensasi', [DispensasiController::class, 'index'])->name('index');

Route::group(['prefix' => 'periode', 'as' => 'periode.'], function() {
    Route::get('/', [PeriodeController::class, 'index'])->name('index');
    Route::get('/edit/{id}', [PeriodeController::class, 'edit'])->name('edit');
    Route::post('simpan', [PeriodeController::class, 'simpan'])->name('simpan');
    Route::post('aktifin', [PeriodeController::class, 'aktifin'])->name('aktifin');
    Route::delete('/{id}', [PeriodeController::class, 'delete'])->name('delete');
});

Route::group(['prefix' => 'dataUKT', 'as' => 'dataUKT.'], function() {
    Route::get('/', [DataUKTController::class, 'index'])->name('index');
    Route::get('/edit/{id}', [DataUKTController::class, 'edit'])->name('edit');
    Route::post('simpan', [DataUKTController::class, 'simpan'])->name('simpan');
    Route::delete('/{id}', [DataUKTController::class, 'delete'])->name('delete');
});

Route::group(['prefix' => 'dispensasi', 'as' => 'dispensasi.'], function () {
    Route::get('/dispensasi', [DispensasiController::class, 'index'])->name('index');
    Route::post('simpan', [DispensasiController::class, 'simpan'])->name('simpan');
    Route::get('/edit/{id}', [DispensasiController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [DispensasiController::class, 'delete'])->name('delete');
});

// Route::get('/verifikasi_dispensasi', [VerifikasiUKTController::class, 'index'])->name('index');
Route::group(['prefix' => 'verifikasi_dispensasi', 'as' => 'verifikasi_dispensasi.'], function () {
    Route::get('/', [VerifikasiUKTController::class, 'index'])->name('index');
    Route::post('simpan', [VerifikasiUKTController::class, 'simpan'])->name('simpan');
    Route::get('/detil/{id}', [VerifikasiUKTController::class, 'detil'])->name('detil');  
    Route::get('/dataukt/{in1}/{in2}', [VerifikasiUKTController::class, 'dataukt'])->name('dataukt');
    Route::delete('/{id}', [VerifikasiUKTController::class, 'delete'])->name('delete');
});

// Route::get('/verifikasi_dispensasi', [VerifikasiUKTController::class, 'index'])->name('index');
Route::group(['prefix' => 'verifikasiDekan_dispensasi', 'as' => 'verifikasiDekan_dispensasi.'], function () {
    Route::get('/', [VerifikasiDekanController::class, 'index'])->name('index');
    Route::post('simpan', [VerifikasiDekanController::class, 'simpan'])->name('simpan');
    Route::get('/detil/{id}', [VerifikasiDekanController::class, 'detil'])->name('detil');
    Route::delete('/{id}', [VerifikasiDekanController::class, 'delete'])->name('delete');
    Route::post('layakpost', [VerifikasiDekanController::class, 'layakpost'])->name('layakpost');
    Route::post('tidaklayakpost', [VerifikasiDekanController::class, 'tidaklayakpost'])->name('tidaklayakpost');
});

// Route::get('/verifikasi_dispensasi', [VerifikasiUKTController::class, 'index'])->name('index');
Route::group(['prefix' => 'verifikasiWR2_dispensasi', 'as' => 'verifikasiWR2_dispensasi.'], function () {
    Route::get('/', [VerifikasiWR2Controller::class, 'index'])->name('index');
    Route::post('simpan', [VerifikasiWR2Controller::class, 'simpan'])->name('simpan');
    Route::get('/detil/{id}', [VerifikasiWR2Controller::class, 'detil'])->name('detil');
    Route::delete('/{id}', [VerifikasiWR2Controller::class, 'delete'])->name('delete');
    Route::post('layakpost', [VerifikasiWR2Controller::class, 'layakpost'])->name('layakpost');
    Route::post('tidaklayakpost', [VerifikasiWR2Controller::class, 'tidaklayakpost'])->name('tidaklayakpost');
});

// Route::get('/penerima_dispensasi', [PeneriamDispensasiController::class, 'index'])->name('index');
Route::group(['prefix' => 'penerima_dispensasi', 'as' => 'penerima_dispensasi.'], function () {
    Route::get('/', [PeneriamDispensasiController::class, 'index'])->name('index');
    Route::get('/cetak/{id}', [PeneriamDispensasiController::class, 'cetak'])->name('cetak');

    // cetak penerima dispensasi
    Route::get('/print/{semester}/{kode_prodi}', [PrintController::class, 'cetakPenerimaDispensasi'])->name('print');
});

// Route::get('/laporan', [LaporanController::class, 'index'])->name('index');
Route::group(['prefix' => 'laporan', 'as' => 'laporan.'], function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
});
