<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DispensasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeneriamDispensasiController;
use App\Http\Controllers\VerifikasiUKTController;

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
    Route::delete('/{id}', [VerifikasiUKTController::class, 'delete'])->name('delete');
});

// Route::get('/penerima_dispensasi', [PeneriamDispensasiController::class, 'index'])->name('index');
Route::group(['prefix' => 'penerima_dispensasi', 'as' => 'penerima_dispensasi.'], function () {
    Route::get('/', [PeneriamDispensasiController::class, 'index'])->name('index');
    Route::get('/cetak/{id}', [PeneriamDispensasiController::class, 'cetak'])->name('cetak');
});

// Route::get('/laporan', [LaporanController::class, 'index'])->name('index');
Route::group(['prefix' => 'laporan', 'as' => 'laporan.'], function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
});
