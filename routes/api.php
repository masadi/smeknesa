<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SinkronisasiController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\PdController;
use App\Http\Controllers\ReferensiController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\WalasController;
use App\Http\Controllers\WakaController;
use App\Http\Controllers\UkkController;
use App\Http\Controllers\ProgressController;
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
  Route::group(['prefix' => 'dashboard'], function () {
    Route::post('/', [DashboardController::class, 'index']);
    Route::post('/status-penilaian', [DashboardController::class, 'status_penilaian']);
    Route::post('/anggota-dinilai', [DashboardController::class, 'anggota_dinilai']);
    Route::post('/detil-penilaian', [DashboardController::class, 'detil_penilaian']);
    Route::post('/wali', [DashboardController::class, 'wali']);
    Route::get('/waka', [DashboardController::class, 'waka']);
    Route::get('/projek', [DashboardController::class, 'projek']);
    Route::post('/detil-projek', [DashboardController::class, 'detil_projek']);
    Route::post('/detil-rencana', [DashboardController::class, 'detil_rencana']);
  });
  Route::group(['prefix' => 'sinkronisasi'], function () {
    Route::post('/', [SinkronisasiController::class, 'index']);
    Route::post('/dapodik', [SinkronisasiController::class, 'proses_sync']);
    Route::get('/hitung/{sekolah_id}', [SinkronisasiController::class, 'hitung']);
    Route::post('/erapor', [SinkronisasiController::class, 'erapor']);
    Route::post('/kirim-data', [SinkronisasiController::class, 'kirim_data']);
    Route::post('/nilai-dapodik', [SinkronisasiController::class, 'nilai_dapodik']);
    Route::post('/cek-koneksi', [SinkronisasiController::class, 'cek_koneksi']);
    Route::get('/rombongan-belajar', [SinkronisasiController::class, 'rombongan_belajar']);
    Route::post('/matev-rapor', [SinkronisasiController::class, 'matev_rapor']);
    Route::post('/kirim-nilai', [SinkronisasiController::class, 'kirim_nilai']);
  });
  Route::group(['prefix' => 'users'], function () {
    Route::get('/', [UsersController::class, 'index']);
    Route::post('/generate', [UsersController::class, 'generate']);
    Route::post('/detil', [UsersController::class, 'detil']);
    Route::post('/update', [UsersController::class, 'update']);
    Route::post('/reset-password', [UsersController::class, 'reset_password']);
    Route::post('/hapus-akses', [UsersController::class, 'hapusAkses']);
    Route::get('/profil', [UsersController::class, 'profil']);
    Route::post('/update-profile', [UsersController::class, 'update_profile']);
  });
  Route::group(['prefix' => 'setting'], function () {
    Route::post('/', [ApiController::class, 'index']);
    Route::post('/update', [ApiController::class, 'update']);
    Route::get('/unduhan', [ApiController::class, 'unduhan']);
    Route::get('/changelog', [ApiController::class, 'changelog']);
    Route::get('/check-update', [ApiController::class, 'check_update']);
  });
  Route::group(['prefix' => 'guru'], function () {
    Route::get('/', [GuruController::class, 'index']);
    Route::post('/detil', [GuruController::class, 'detil']);
    Route::post('/update', [GuruController::class, 'update']);
    Route::post('/upload', [GuruController::class, 'upload']);
    Route::post('/simpan', [GuruController::class, 'simpan']);
  });
  Route::group(['prefix' => 'rombongan-belajar'], function () {
    Route::get('/', [RombelController::class, 'index']);
    Route::post('/anggota-rombel', [RombelController::class, 'anggota_rombel']);
    Route::post('/keluarkan', [RombelController::class, 'keluarkan']);
    Route::post('/pembelajaran', [RombelController::class, 'pembelajaran']);
    Route::post('/simpan-pembelajaran', [RombelController::class, 'simpan_pembelajaran']);
    Route::post('/hapus-pembelajaran', [RombelController::class, 'hapus_pembelajaran']);
    Route::post('/get-rombel', [RombelController::class, 'get_rombel']);
  });
  Route::group(['prefix' => 'peserta-didik'], function () {
    Route::get('/', [PdController::class, 'index']);
    Route::post('/detil', [PdController::class, 'detil']);
    Route::post('/update', [PdController::class, 'update']);
    Route::post('/get-pd', [PdController::class, 'get_pd']);
  });
  Route::group(['prefix' => 'referensi'], function () {
    Route::get('/mata-pelajaran', [ReferensiController::class, 'mata_pelajaran']);
    Route::get('/ekstrakurikuler', [ReferensiController::class, 'ekstrakurikuler']);
    Route::post('/sinkron', [ReferensiController::class, 'proses_sync']);
    Route::post('/detil-dudi', [ReferensiController::class, 'detil_dudi']);
    Route::post('/anggota-prakerin', [ReferensiController::class, 'anggota_prakerin']);
    Route::get('/dudi', [ReferensiController::class, 'dudi']);
    Route::get('/kompetensi-dasar', [ReferensiController::class, 'kompetensi_dasar']);
    Route::post('/add-kd', [ReferensiController::class, 'add_kd']);
    Route::post('/update-kd', [ReferensiController::class, 'update_kd']);
    Route::post('/status-kd', [ReferensiController::class, 'status_kd']);
    Route::post('/hapus-kd-ganda', [ReferensiController::class, 'hapus_kd_ganda']);
    Route::get('/capaian-pembelajaran', [ReferensiController::class, 'capaian_pembelajaran']);
    Route::post('/add-cp', [ReferensiController::class, 'add_cp']);
    Route::post('/status-cp', [ReferensiController::class, 'status_cp']);
    Route::get('/tujuan-pembelajaran', [ReferensiController::class, 'tujuan_pembelajaran']);
    Route::post('/get-cp-tp', [ReferensiController::class, 'get_cp_tp']);
    Route::post('/upload-tp', [ReferensiController::class, 'upload_tp']);
    Route::post('/update-tp', [ReferensiController::class, 'update_tp']);
    Route::post('/hapus-tp', [ReferensiController::class, 'hapus_tp']);
    Route::post('/rombel-tp', [ReferensiController::class, 'rombel_tp']);
    Route::post('/add-rombel-tp', [ReferensiController::class, 'add_rombel_tp']);
    //select option
    Route::post('/get-rombel', [ReferensiController::class, 'get_rombel']);
    Route::post('/get-mapel', [ReferensiController::class, 'get_mapel']);
  });
  Route::group(['prefix' => 'penilaian'], function () {
    Route::post('/get-rombel', [PenilaianController::class, 'get_rombel']);
    Route::post('/get-mapel', [PenilaianController::class, 'get_mapel']);
    Route::post('/get-nilai-akhir', [PenilaianController::class, 'get_nilai_akhir']);
    Route::post('/simpan-nilai-akhir', [PenilaianController::class, 'simpan_nilai_akhir']);
    Route::post('/get-capaian-kompetensi', [PenilaianController::class, 'get_capaian_kompetensi']);
    Route::post('/simpan-capaian-kompetensi', [PenilaianController::class, 'simpan_capaian_kompetensi']);
    Route::post('/reset-capaian-kompetensi', [PenilaianController::class, 'reset_capaian_kompetensi']);
    Route::post('/upload-nilai', [PenilaianController::class, 'upload_nilai']);
    Route::get('/nilai-sikap', [PenilaianController::class, 'nilai_sikap']);
    Route::get('/ref-sikap', [PenilaianController::class, 'ref_sikap']);
    Route::post('/get-elemen', [PenilaianController::class, 'get_elemen']);
    Route::post('/simpan-nilai-sikap', [PenilaianController::class, 'simpan_nilai_sikap']);
    Route::get('/nilai-projek', [PenilaianController::class, 'nilai_projek']);
    Route::post('/get-tema', [PenilaianController::class, 'get_tema']);
    Route::post('/get-budaya-kerja', [PenilaianController::class, 'get_budaya_kerja']);
    Route::post('/get-rencana-projek', [PenilaianController::class, 'get_rencana_projek']);
    Route::post('/simpan-projek', [PenilaianController::class, 'simpan_projek']);
    Route::post('/detil-projek', [PenilaianController::class, 'detil_projek']);
    Route::post('/update-projek', [PenilaianController::class, 'update_projek']);
    Route::post('/hapus-projek', [PenilaianController::class, 'hapus_projek']);
    Route::post('/get-ekskul', [PenilaianController::class, 'get_ekskul']);
    Route::post('/get-rombel-reguler', [PenilaianController::class, 'get_rombel_reguler']);
    Route::post('/get-kelas', [PenilaianController::class, 'get_kelas']);
    Route::post('/simpan-nilai-ekskul', [PenilaianController::class, 'simpan_nilai_ekskul']);
    Route::post('/hapus-nilai-ekskul', [PenilaianController::class, 'hapus_nilai_ekskul']);
    Route::post('/get-teknik-penilaian', [PenilaianController::class, 'get_teknik_penilaian']);
    Route::post('/get-cp', [PenilaianController::class, 'get_cp']);
    Route::post('/get-tp', [PenilaianController::class, 'get_tp']);
    Route::post('/get-nilai-tp', [PenilaianController::class, 'get_nilai_tp']);
    Route::post('/get-nilai-akhir-sumatif', [PenilaianController::class, 'get_nilai_akhir_sumatif']);
    Route::post('/simpan-nilai-tp', [PenilaianController::class, 'simpan_nilai_tp']);
    Route::post('/simpan-nilai-akhir-sumatif', [PenilaianController::class, 'simpan_nilai_akhir_sumatif']);
  });
  Route::group(['prefix' => 'ukk'], function () {
    Route::get('/paket-ukk', [UkkController::class, 'paket_ukk']);
    Route::post('/get-jurusan', [UkkController::class, 'get_jurusan']);
    Route::post('/get-kurikulum', [UkkController::class, 'get_kurikulum']);
    Route::post('/simpan-ukk', [UkkController::class, 'simpan_ukk']);
    Route::post('/status-ukk', [UkkController::class, 'status_ukk']);
    Route::post('/detil-ukk', [UkkController::class, 'detil_ukk']);
    Route::post('/add-unit-ukk', [UkkController::class, 'add_unit_ukk']);
    Route::post('/update-ukk', [UkkController::class, 'update_ukk']);
    Route::get('/get-rencana-ukk', [UkkController::class, 'index']);
    Route::post('/get-siswa', [UkkController::class, 'get_siswa']);
    Route::get('/rencana-ukk', [UkkController::class, 'rencana_ukk']);
    Route::post('/get-penguji-ukk', [UkkController::class, 'get_penguji_ukk']);
    Route::post('/get-paket-ukk', [UkkController::class, 'get_paket_ukk']);
    Route::post('/simpan-rencana-ukk', [UkkController::class, 'simpan_rencana_ukk']);
    Route::post('/hapus-rencana-ukk', [UkkController::class, 'hapus_rencana_ukk']);
    Route::post('/detil-rencana', [UkkController::class, 'detil_rencana']);
    Route::get('/nilai-ukk', [UkkController::class, 'nilai_ukk']);
    Route::post('/rencana-ukk', [UkkController::class, 'rencana_ukk']);
    Route::post('/siswa-ukk', [UkkController::class, 'siswa_ukk']);
    Route::post('/simpan-nilai-ukk', [UkkController::class, 'simpan_nilai_ukk']);
    Route::post('/delete-unit-ukk', [UkkController::class, 'delete_unit_ukk']);
  });
  Route::group(['prefix' => 'waka'], function () {
    Route::post('/nilai-sikap', [WakaController::class, 'nilai_sikap']);
    Route::post('/pkl', [WakaController::class, 'pkl']);
    Route::post('/ketidakhadiran', [WakaController::class, 'ketidakhadiran']);
    Route::post('/nilai-ekskul', [WakaController::class, 'nilai_ekskul']);
    Route::post('/cetak-rapor', [WakaController::class, 'cetak_rapor']);
    Route::post('/unduh-legger', [WakaController::class, 'unduh_legger']);
  });
  Route::group(['prefix' => 'walas'], function () {
    Route::post('/catatan-sikap', [WalasController::class, 'catatan_sikap']);
    Route::post('/praktik-kerja-lapangan', [WalasController::class, 'praktik_kerja_lapangan']);
    Route::post('/anggota-pkl', [WalasController::class, 'anggota_pkl']);
    Route::post('/hapus-prakerin', [WalasController::class, 'hapus_prakerin']);
    Route::post('/ketidakhadiran', [WalasController::class, 'ketidakhadiran']);
    Route::post('/nilai-ekskul', [WalasController::class, 'nilai_ekskul']);
    Route::post('/kenaikan-kelas', [WalasController::class, 'kenaikan_kelas']);
    Route::post('/get-next-rombel', [WalasController::class, 'get_next_rombel']);
    Route::post('/find-rombel', [WalasController::class, 'find_rombel']);
    Route::post('/cetak-rapor', [WalasController::class, 'cetak_rapor']);
    Route::post('/unduh-legger', [WalasController::class, 'unduh_legger']);
    Route::post('/save', [WalasController::class, 'save']);
  });
  Route::group(['prefix' => 'progress'], function () {
    Route::get('/penilaian', [ProgressController::class, 'index']);
    Route::post('/detil', [ProgressController::class, 'detil']);
    Route::get('/nilai-projek', [ProgressController::class, 'nilai_projek']);
    Route::get('/nilai-ekskul', [ProgressController::class, 'nilai_ekskul']);
    Route::get('/nilai-ukk', [ProgressController::class, 'nilai_ukk']);
    Route::get('/nilai-pkl', [ProgressController::class, 'nilai_pkl']);
  });
});
