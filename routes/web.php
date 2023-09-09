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
    Route::get('/rapor-cover/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_cover'])->name('rapor-cover');
    Route::get('/rapor-semester/{anggota_rombel_id}/{sekolah_id}/{semester_id}', [CetakController::class, 'rapor_semester'])->name('rapor-semester');
    Route::get('/rapor-p5/{anggota_rombel_id}/{semester_id}', [CetakController::class, 'rapor_p5'])->name('rapor-p5');
    Route::get('/rapor-pelengkap/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_pelengkap'])->name('rapor-pelengkap');
    Route::get('/rekap-remedial/{peserta_didik_id}', [CetakController::class, 'rekap_remedial']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/perijinan/{ijin_id}', [CetakController::class, 'perijinan']);
    });
});
Route::group(['prefix' => 'export'], function () {
    Route::get('/rekap-absensi-siswa/{semester_id}/{rombongan_belajar_id}/{bulan}/{jenis}', [ExportController::class, 'rekap_absensi_siswa']);
    Route::get('/rekap-absensi-guru/{bulan}', [ExportController::class, 'rekap_absensi_guru']);
    Route::get('/rekap-remedial/{semester_id}', [ExportController::class, 'rekap_remedial']);
    Route::get('/password-pd/{rombongan_belajar_id}', [ExportController::class, 'password_pd']);
    Route::get('/nilai-ekskul/{rombongan_belajar_id}', [ExportController::class, 'nilai_ekskul']);
    Route::get('/rekap-nilai/{pembelajaran_id}', [ExportController::class, 'rekap_nilai']);
});
Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');