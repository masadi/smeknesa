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
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\EkstraController;
use App\Http\Controllers\PrakerinController;
use App\Http\Controllers\ProjekController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\UkkController;
use App\Http\Controllers\PerijinanController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ModuleController;
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
Route::get('menu', [DashboardController::class, 'menu']);
Route::get('sekolah', [DashboardController::class, 'hitung_sekolah']);
Route::get('no-access', [DashboardController::class, 'no_access'])->name('login');
Route::get('profile/data', [AuthController::class, 'profile_pd']);
Route::group(['prefix' => 'artikel'], function () {
  Route::get('/', [PostController::class, 'artikel']);
  Route::post('/baca', [PostController::class, 'baca']);
});
Route::group(['prefix' => 'module'], function () {
  Route::get('/', [ModuleController::class, 'index']);
  Route::post('/download/{id}', [ModuleController::class, 'download']);
  Route::get('/video', [ModuleController::class, 'video']);
  Route::get('/slider', [ModuleController::class, 'slider']);
});
Route::group(['prefix' => 'auth'], function () {
  Route::get('/semester', [AuthController::class, 'semester']);
  Route::post('login', [AuthController::class, 'login']);
  Route::post('register', [AuthController::class, 'register']);
  Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::post('user', [AuthController::class, 'update_user']);
    Route::post('users/generate', [AuthController::class, 'generate']);
    Route::get('users/list', [AuthController::class, 'list']);
    Route::post('user/delete', [AuthController::class, 'hapus']);
    Route::post('user/detil', [AuthController::class, 'detil']);
    Route::post('user/reset-password', [AuthController::class, 'reset_password']);
    Route::post('user/foto', [AuthController::class, 'foto']);
    Route::post('user/ganti-password', [AuthController::class, 'ganti_password']);
    Route::post('user/update-role', [AuthController::class, 'update_role']);
  });
});
Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::post('/upload', [UploadController::class, 'index']);
  Route::group(['prefix' => 'dashboard'], function () {
    Route::post('/', [DashboardController::class, 'index']);
    Route::get('/aplikasi', [DashboardController::class, 'aplikasi']);
    Route::get('/grafik', [DashboardController::class, 'grafik']);
    Route::get('/pengembangan', [DashboardController::class, 'pengembangan']);
    Route::get('/get-chart', [DashboardController::class, 'get_chart']);
    Route::get('/kirim-whatsapp', [DashboardController::class, 'kirim_whatsapp']);
    Route::post('/get-kelas', [DashboardController::class, 'get_kelas']);
    Route::post('/get-mapel', [DashboardController::class, 'get_mapel']);
    Route::post('/get-nilai', [DashboardController::class, 'get_nilai']);
    Route::post('/notifikasi', [DashboardController::class, 'notifikasi']);
    Route::post('/read-notif', [DashboardController::class, 'read_notif']);
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
    Route::post('/sekolah', [ReferensiController::class, 'sekolah']);
    Route::get('/mata-pelajaran', [ReferensiController::class, 'mata_pelajaran']);
    Route::get('/kelompok-mapel', [ReferensiController::class, 'kelompok_mapel']);
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
    Route::get('/guru-bk', [ReferensiController::class, 'guru_bk']);
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
    Route::post('/non-anggota', [ReferensiController::class, 'non_anggota']);
    Route::post('/set-anggota', [ReferensiController::class, 'set_anggota']);
    Route::post('/set-anggota', [ReferensiController::class, 'set_anggota']);
    Route::post('/proses-kelulusan', [ReferensiController::class, 'proses_kelulusan']);
    Route::post('/proses-kenaikan', [ReferensiController::class, 'proses_kenaikan']);
    Route::post('/hapus-kenaikan', [ReferensiController::class, 'hapus_kenaikan']);
    Route::post('/prepare-data', [ReferensiController::class, 'prepare_data']);
    Route::post('/simpan-data', [ReferensiController::class, 'simpan_data']);
    Route::get('/ekstrakurikuler', [ReferensiController::class, 'ekstrakurikuler']);
    Route::post('/anggota-ekskul', [ReferensiController::class, 'anggota_ekskul']);
    Route::post('/non-anggota-ekskul', [ReferensiController::class, 'non_anggota_ekskul']);
    Route::get('/capaian-pembelajaran', [ReferensiController::class, 'capaian_pembelajaran']);
    Route::post('/get-pembelajaran', [ReferensiController::class, 'get_pembelajaran']);
    Route::post('/add-cp', [ReferensiController::class, 'add_cp']);
    Route::post('/update-cp', [ReferensiController::class, 'update_cp']);
    Route::post('/get-cp', [ReferensiController::class, 'get_cp']);
    Route::get('/bahan-ajar', [ModuleController::class, 'index']);
    Route::post('/add-bahan-ajar', [ModuleController::class, 'add_bahan_ajar']);
    Route::get('/get-video', [ModuleController::class, 'get_video']);
    Route::post('/add-video', [ModuleController::class, 'add_video']);
    Route::get('/get-slider', [ModuleController::class, 'get_slider']);
    Route::post('/add-slider', [ModuleController::class, 'add_slider']);
  });
  Route::group(['prefix' => 'magang'], function () {
    Route::get('/dudi', [PrakerinController::class, 'dudi']);
    Route::get('/pembimbing', [PrakerinController::class, 'pembimbing']);
    Route::post('/get-guru', [PrakerinController::class, 'get_guru']);
    Route::get('/get-dudi', [PrakerinController::class, 'get_dudi']);
    Route::post('/get-rombel', [PrakerinController::class, 'get_rombel']);
    Route::post('/get-mapel', [PrakerinController::class, 'get_mapel']);
    Route::post('/get-siswa', [PrakerinController::class, 'get_siswa']);
    Route::post('/simpan-data', [PrakerinController::class, 'simpan_data']);
    Route::post('/detil', [PrakerinController::class, 'detil']);
    Route::post('/update-data', [PrakerinController::class, 'update_data']);
    Route::get('/list-cp', [PrakerinController::class, 'list_cp']);
    Route::get('/', [PrakerinController::class, 'index']);
    Route::post('/get-pkl', [PrakerinController::class, 'get_pkl']);
    Route::post('/get-pd-pkl', [PrakerinController::class, 'get_pd_pkl']);
    Route::post('/simpan-nilai', [PrakerinController::class, 'simpan_nilai']);
    Route::post('/detil-nilai', [PrakerinController::class, 'detil_nilai']);
    Route::get('/absensi', [PrakerinController::class, 'absensi']);
    Route::post('/simpan-absensi', [PrakerinController::class, 'simpan_absensi']);
    Route::post('/hapus-absensi', [PrakerinController::class, 'hapus_absensi']);
    Route::post('/hapus-data', [PrakerinController::class, 'hapus_data']);
    Route::group(['prefix' => 'asesor'], function () {
      Route::get('/', [AsesorController::class, 'index']);
      Route::get('/dudi', [AsesorController::class, 'dudi']);
      Route::post('/store', [AsesorController::class, 'store']);
      Route::get('/show/{id}', [AsesorController::class, 'show']);
      Route::post('/update/{id}', [AsesorController::class, 'update']);
      Route::delete('/destroy/{id}', [AsesorController::class, 'destroy']);
    });
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
    Route::post('/update-cp', [NilaiController::class, 'update_cp']);
    Route::post('/update-tp', [NilaiController::class, 'update_tp']);
    Route::post('/get-siswa', [NilaiController::class, 'get_siswa']);
    Route::post('/simpan', [NilaiController::class, 'simpan']);
    Route::post('/rekap', [NilaiController::class, 'rekap']);
    Route::post('/detil', [NilaiController::class, 'detil']);
    Route::post('/chart', [NilaiController::class, 'chart']);
    Route::post('/chart-jurusan', [NilaiController::class, 'chart_jurusan']);
    Route::post('/nilai-pd', [NilaiController::class, 'nilai_pd']);
    Route::post('/get-desc', [NilaiController::class, 'get_desc']);
    Route::post('/deskripsi', [NilaiController::class, 'deskripsi']);
    Route::post('/reset-deskripsi', [NilaiController::class, 'reset_deskripsi']);
    Route::post('/detil-data', [NilaiController::class, 'detil_data']);
    Route::post('/peserta-didik', [NilaiController::class, 'peserta_didik']);
    Route::get('/materi-ekstra', [EkstraController::class, 'index']);
    Route::post('/get-ekskul', [EkstraController::class, 'ekskul']);
    Route::post('/add-materi-ekstra', [EkstraController::class, 'add_materi_ekstra']);
    Route::post('/detil-materi', [EkstraController::class, 'detil_materi']);
    Route::post('/update-materi', [EkstraController::class, 'update_materi']);
    Route::post('/hapus-materi', [EkstraController::class, 'hapus_materi']);
    Route::post('/get-ektra', [EkstraController::class, 'get_ektra']);
    Route::post('/get-siswa-ektra', [EkstraController::class, 'get_siswa_ektra']);
    Route::post('/add-nilai-ektra', [EkstraController::class, 'add_nilai_ektra']);
    Route::post('/add-absen-ektra', [EkstraController::class, 'add_absen_ektra']);
    Route::post('/hapus-penilaian', [NilaiController::class, 'hapus_penilaian']);
    Route::post('/detil-nilai', [NilaiController::class, 'detil_nilai']);
    Route::post('/update', [NilaiController::class, 'update']);
    Route::post('/rangking', [NilaiController::class, 'rangking']);
    Route::get('/get-semester', [NilaiController::class, 'get_semester']);
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
    Route::post('/get-rekap-guru', [PresensiController::class, 'get_rekap_guru']);
    Route::get('/get-rombel', [PresensiController::class, 'get_rombel']);
    Route::post('/get-guru', [PresensiController::class, 'get_guru']);
    Route::get('/get-hari', [PresensiController::class, 'get_hari']);
    Route::post('/get-hari', [PresensiController::class, 'get_hari']);
    Route::post('/simpan', [PresensiController::class, 'simpan']);
    Route::post('/detil', [PresensiController::class, 'detil']);
    Route::post('/hapus', [PresensiController::class, 'hapus']);
    Route::get('/perijinan', [PresensiController::class, 'perijinan']);
    Route::post('/get-siswa', [PresensiController::class, 'get_siswa']);
    Route::post('/add-perijinan', [PresensiController::class, 'add_perijinan']);
    Route::post('/get-jam', [PresensiController::class, 'get_jam']);
    Route::get('/terlambat', [PresensiController::class, 'terlambat']);
    Route::post('/add-terlambat', [PresensiController::class, 'add_terlambat']);
    Route::post('/update-terlambat', [PresensiController::class, 'update_terlambat']);
    Route::get('/alpha-tinggi', [PresensiController::class, 'alpha_tinggi']);
    Route::get('/sering-terlambat', [PresensiController::class, 'sering_terlambat']);
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
  Route::group(['prefix' => 'siswa'], function () {
    Route::post('/', [SiswaController::class, 'index']);
    Route::post('/teman', [SiswaController::class, 'teman']);
    Route::post('/guru', [SiswaController::class, 'guru']);
  });
  Route::group(['prefix' => 'rekapitulasi'], function () {
    Route::get('/remedial', [RekapController::class, 'remedial']);
    Route::get('/remed', [RekapController::class, 'remed']);
    Route::get('/absen-guru', [RekapController::class, 'absen_guru']);
  });
  Route::group(['prefix' => 'projek'], function () {
    Route::get('/', [ProjekController::class, 'index']);
    Route::post('/get-rombel', [ProjekController::class, 'get_rombel']);
    Route::post('/get-mapel', [ProjekController::class, 'get_mapel']);
    Route::post('/get-tema', [ProjekController::class, 'get_tema']);
    Route::post('/get-elemen', [ProjekController::class, 'get_elemen']);
    Route::post('/simpan-projek', [ProjekController::class, 'simpan_projek']);
    Route::post('/update-projek', [ProjekController::class, 'update_projek']);
    Route::post('/hapus-rencana', [ProjekController::class, 'hapus_rencana']);
    Route::post('/get-rencana', [ProjekController::class, 'get_rencana']);
    Route::post('/simpan-nilai', [ProjekController::class, 'simpan_nilai']);
  });
  Route::group(['prefix' => 'prakerin'], function () {
    Route::get('/', [PrakerinController::class, 'index']);
    Route::get('/list-cp', [PrakerinController::class, 'list_cp']);
  });
  Route::group(['prefix' => 'monitoring'], function () {
    Route::post('/legger', [MonitoringController::class, 'legger']);
  });
  Route::group(['prefix' => 'ukk'], function () {
    Route::get('/', [UkkController::class, 'index']);
    Route::post('/get-jurusan', [UkkController::class, 'get_jurusan']);
    Route::post('/get-kurikulum', [UkkController::class, 'get_kurikulum']);
    Route::post('/get-paket', [UkkController::class, 'get_paket']);
    Route::delete('/destroy', [UkkController::class, 'destroy']);
    Route::post('/status-ukk', [UkkController::class, 'status_ukk']);
    Route::post('/update', [UkkController::class, 'update']);
    Route::post('/siswa-ukk', [UkkController::class, 'siswa_ukk']);
    Route::post('/simpan-nilai-ukk', [UkkController::class, 'simpan_nilai_ukk']);
    Route::group(['prefix' => 'rencana'], function () {
      Route::get('/', [UkkController::class, 'rencana']);
      Route::post('/get-dudi', [UkkController::class, 'get_dudi']);
      Route::post('/get-siswa', [UkkController::class, 'get_siswa']);
      Route::post('/get-paket', [UkkController::class, 'get_paket_ukk']);
      Route::post('/store', [UkkController::class, 'store']);
      Route::post('/update', [UkkController::class, 'update_rencana']);
      Route::delete('/destroy', [UkkController::class, 'destroy']);
      Route::post('/show', [UkkController::class, 'show']);
      Route::post('/get-rencana', [UkkController::class, 'get_rencana']);
      Route::post('/get-penguji', [UkkController::class, 'get_penguji']);
    });
  });
  Route::group(['prefix' => 'post'], function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('/category', [PostController::class, 'category']);
    Route::post('/save-category', [PostController::class, 'save_category']);
    Route::post('/save', [PostController::class, 'save']);
    Route::post('/delete', [PostController::class, 'destroy']);
    Route::post('/show', [PostController::class, 'show']);
  });
});
Route::group(['prefix' => 'perijinan'], function () {
  Route::get('/', [PerijinanController::class, 'index']);
  Route::post('/get-jam', [PerijinanController::class, 'get_jam']);
  Route::post('/store', [PerijinanController::class, 'store']);
  Route::post('/detil', [PerijinanController::class, 'detil']);
});
