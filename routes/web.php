<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AuthController;
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
Route::group(['prefix' => 'cetak'], function () {
    Route::get('/', [CetakController::class, 'viewPdf']);
});
Route::group(['prefix' => 'export'], function () {
    Route::get('/rekap-absensi-siswa/{semester_id}/{rombongan_belajar_id}/{bulan}/{jenis}', [ExportController::class, 'rekap_absensi_siswa']);
});
Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');