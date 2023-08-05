<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReferensiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\WhatsappController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('sekolah', [DashboardController::class, 'hitung_sekolah']);
Route::get('no-access', [DashboardController::class, 'no_access'])->name('login');
Route::group(['prefix' => 'auth'], function () {
  Route::get('/semester', [AuthController::class, 'semester']);
  Route::post('login', [AuthController::class, 'login']);
  Route::post('register', [AuthController::class, 'register']);
  Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
  });
});
Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::post('/upload', [UploadController::class, 'index']);
  Route::group(['prefix' => 'dashboard'], function () {
    Route::post('/', [DashboardController::class, 'index']);
    Route::get('/aplikasi', [DashboardController::class, 'aplikasi']);
    Route::get('/pengembangan', [DashboardController::class, 'pengembangan']);
    Route::get('/get-chart', [DashboardController::class, 'get_chart']);
    Route::get('/kirim-whatsapp', [DashboardController::class, 'kirim_whatsapp']);
    Route::post('/get-kelas', [DashboardController::class, 'get_kelas']);
    Route::post('/get-mapel', [DashboardController::class, 'get_mapel']);
    Route::post('/get-nilai', [DashboardController::class, 'get_nilai']);
    Route::post('/notifikasi', [DashboardController::class, 'notifikasi']);
  });
  Route::group(['prefix' => 'pengaturan'], function () {
    Route::get('/umum', [PengaturanController::class, 'index']);
    Route::post('/umum', [PengaturanController::class, 'umum']);
    Route::get('/pengguna', [PengaturanController::class, 'pengguna']);
    Route::get('/guru-mapel', [PengaturanController::class, 'guru_mapel']);
    Route::post('/guru-mapel', [PengaturanController::class, 'guru_mapel']);
    Route::get('/remedial', [PengaturanController::class, 'remedial']);
    Route::post('/remedial', [PengaturanController::class, 'remedial']);
  });
  Route::group(['prefix' => 'referensi'], function () {
    Route::get('/sekolah', [ReferensiController::class, 'sekolah']);
    Route::get('/mata-pelajaran', [ReferensiController::class, 'mata_pelajaran']);
    Route::get('/jurusan', [ReferensiController::class, 'jurusan']);
    Route::get('/rombongan-belajar', [ReferensiController::class, 'rombongan_belajar']);
    Route::post('/pembelajaran', [ReferensiController::class, 'pembelajaran']);
    Route::post('/simpan-pembelajaran', [ReferensiController::class, 'simpan_pembelajaran']);
    Route::post('/hapus-pembelajaran', [ReferensiController::class, 'hapus_pembelajaran']);
    Route::post('/perangkat-pembelajaran', [ReferensiController::class, 'perangkat_pembelajaran']);
    Route::post('/anggota-rombel', [ReferensiController::class, 'anggota_rombel']);
    Route::get('/peserta-didik', [ReferensiController::class, 'peserta_didik']);
    Route::get('/pd-keluar', [ReferensiController::class, 'pd_keluar']);
    Route::get('/guru-mapel', [ReferensiController::class, 'guru_mapel']);
    Route::post('/sekolah', [ReferensiController::class, 'sekolah']);
    Route::get('/guru', [ReferensiController::class, 'data_guru']);
    Route::post('/get-data', [ReferensiController::class, 'get_data']);
    Route::post('/add-data', [ReferensiController::class, 'add_data']);
    Route::post('/update-data', [ReferensiController::class, 'update_data']);
    Route::post('/hapus-data', [ReferensiController::class, 'hapus_data']);
    Route::post('/detil-data', [ReferensiController::class, 'detil_data']);
    Route::get('/jadwal', [ReferensiController::class, 'jadwal']);
    Route::post('/wilayah', [ReferensiController::class, 'wilayah']);
    Route::get('/jadwal-mengajar', [ReferensiController::class, 'jadwal_mengajar']);
    Route::post('/jadwal-mengajar', [ReferensiController::class, 'jadwal_mengajar']);
    Route::get('/get-guru', [ReferensiController::class, 'get_guru']);
    Route::post('/set-ketua', [ReferensiController::class, 'set_ketua']);
    Route::post('/get-jurusan', [ReferensiController::class, 'get_jurusan']);
    Route::post('/get-rombel', [ReferensiController::class, 'get_rombel']);
    Route::post('/get-mapel', [ReferensiController::class, 'get_mapel']);
    Route::post('/get-group-mapel', [ReferensiController::class, 'get_group_mapel']);
    Route::post('/get-siswa', [ReferensiController::class, 'get_siswa']);
    Route::get('/get-semester', [ReferensiController::class, 'get_semester']);
    Route::post('/add-semester', [ReferensiController::class, 'add_semester']);
    Route::post('/update-semester', [ReferensiController::class, 'update_semester']);
    Route::post('/lanjutkan-data', [ReferensiController::class, 'lanjutkan']);
    Route::get('/non-anggota', [ReferensiController::class, 'non_anggota']);
    Route::post('/set-anggota', [ReferensiController::class, 'set_anggota']);
    Route::post('/set-anggota', [ReferensiController::class, 'set_anggota']);
    Route::post('/proses-kelulusan', [ReferensiController::class, 'proses_kelulusan']);
    Route::post('/proses-kenaikan', [ReferensiController::class, 'proses_kenaikan']);
  });
  Route::group(['prefix' => 'jadwal'], function () {
    Route::get('/', [JadwalController::class, 'index']);
    Route::post('/tambah', [JadwalController::class, 'tambah']);
    Route::post('/add', [JadwalController::class, 'add']);
    Route::post('/jam', [JadwalController::class, 'jam']);
  });
  Route::group(['prefix' => 'nilai'], function () {
    Route::get('/', [NilaiController::class, 'index']);
    Route::get('kenaikan', [NilaiController::class, 'kenaikan']);
    Route::get('/list-cp', [NilaiController::class, 'list_cp']);
    Route::post('/get-cp', [NilaiController::class, 'get_cp']);
    Route::post('/get-tp', [NilaiController::class, 'get_tp']);
    Route::post('/add-cp', [NilaiController::class, 'add_cp']);
    Route::post('/get-siswa', [NilaiController::class, 'get_siswa']);
    Route::post('/simpan', [NilaiController::class, 'simpan']);
    Route::post('/rekap', [NilaiController::class, 'rekap']);
    Route::post('/detil', [NilaiController::class, 'detil']);
    Route::post('/chart', [NilaiController::class, 'chart']);
    Route::post('/nilai-pd', [NilaiController::class, 'nilai_pd']);
    Route::post('/get-desc', [NilaiController::class, 'get_desc']);
    Route::post('/deskripsi', [NilaiController::class, 'deskripsi']);
    Route::post('/reset-deskripsi', [NilaiController::class, 'reset_deskripsi']);
    Route::post('/detil-data', [NilaiController::class, 'detil_data']);
    Route::post('/peserta-didik', [NilaiController::class, 'peserta_didik']);
  });
  Route::group(['prefix' => 'modul'], function () {
    Route::get('/', [ModulController::class, 'index']);
    Route::get('/ref', [ModulController::class, 'referensi']);
    Route::post('/simpan', [ModulController::class, 'simpan']);
    Route::post('/cari-mapel', [ModulController::class, 'cari_mapel']);
  });
  Route::group(['prefix' => 'presensi'], function () {
    Route::get('/', [PresensiController::class, 'index']);
    Route::get('/pd', [PresensiController::class, 'pd']);
    Route::get('/rekap', [PresensiController::class, 'rekap']);
    Route::get('/dashboard', [PresensiController::class, 'dashboard']);
    Route::post('/get-kelas', [PresensiController::class, 'get_kelas']);
    Route::post('/get-rekap', [PresensiController::class, 'get_rekap']);
    Route::get('/get_hari', [PresensiController::class, 'get_hari']);
    Route::post('/simpan', [PresensiController::class, 'simpan']);
  });
  Route::group(['prefix' => 'editor'], function () {
    Route::post('/edit-data', [EditorController::class, 'index']);
    Route::post('/update-data', [EditorController::class, 'update']);
  });
  Route::group(['prefix' => 'whatsapp'], function () {
    Route::get('/', [WhatsappController::class, 'index']);
    Route::post('/add', [WhatsappController::class, 'add']);
    Route::post('/detil', [WhatsappController::class, 'detil']);
    Route::post('/hapus', [WhatsappController::class, 'hapus']);
    Route::post('/kirim-pesan', [WhatsappController::class, 'kirim_pesan']);
  });
});
