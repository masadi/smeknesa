<?php 
use App\Models\Setting;
use App\Models\Peserta_didik;
use App\Models\Anggota_rombel;
use App\Models\Agama;
use App\Models\Pembelajaran;
use App\Models\Sekolah;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Semester;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

function get_setting($key, $semester_id = NULL, $sekolah_id = NULL){
    $data = Setting::where(function($query) use ($key, $semester_id, $sekolah_id){
        $query->where('key', $key);
        if($semester_id){
            $query->where('semester_id', $semester_id);
        }
        if($sekolah_id){
            $query->where('sekolah_id', $sekolah_id);
        }
    })->first();
    return ($data) ? $data->value : NULL;
}
/*
function get_setting($key){
    $data = Setting::ofKey($key)->first();
    return ($data) ? $data->value : NULL;
}
*/
function filter_agama_siswa($pembelajaran_id, $rombongan_belajar_id){
    $ref_agama = Agama::all();
	$agama_id = [];
	foreach ($ref_agama as $agama) {
        $nama_agama = str_replace('Budha', 'Buddha', $agama->nama);
        $agama_id[$agama->agama_id] = $nama_agama;
    }
    $get_mapel = Pembelajaran::with('mata_pelajaran')->find($pembelajaran_id);
    $nama_mapel = str_replace('Pendidikan Agama', '', $get_mapel->mata_pelajaran->nama);
    $nama_mapel = str_replace('KongHuChu', 'Konghuchu', $nama_mapel);
    $nama_mapel = str_replace('Kong Hu Chu', 'Konghuchu', $nama_mapel);
    $nama_mapel = str_replace('dan Budi Pekerti', '', $nama_mapel);
    $nama_mapel = str_replace('Pendidikan Kepercayaan terhadap', '', $nama_mapel);
    $nama_mapel = str_replace('Tuhan YME', 'Kepercayaan kpd Tuhan YME', $nama_mapel);
    $nama_mapel = trim($nama_mapel);
    $agama_id = array_search($nama_mapel, $agama_id);
    return $agama_id;
}
function mapel_agama(){
	return ['100014000', '100014140', '100015000', '100015010', '100016000', '100016010', '109011000', '109011010', '100011000', '100011070', '100013000', '100013010', '100012000', '100012050'];
}
function filter_pembelajaran_agama($agama_siswa, $nama_agama){
    $nama_agama = str_replace('Budha', 'Buddha', $nama_agama);
	$nama_agama = str_replace('Pendidikan Agama', '', $nama_agama);
	$nama_agama = str_replace('dan Budi Pekerti', '', $nama_agama);
	$nama_agama = str_replace('Pendidikan Kepercayaan', '', $nama_agama);
	$nama_agama = str_replace('terhadap', 'kpd', $nama_agama);
	$nama_agama = str_replace('KongHuChu', 'Konghuchu', $nama_agama);
	$nama_agama = str_replace('Kong Hu Chu', 'Konghuchu', $nama_agama);
	$nama_agama = trim($nama_agama);
	$agama_siswa = str_replace('KongHuChu', 'Konghuchu', $agama_siswa);
	$agama_siswa = str_replace('Kong Hu Chu', 'Konghuchu', $agama_siswa);
    $agama_siswa = str_replace('Kepercayaan ', '', $agama_siswa);
    if ($agama_siswa == $nama_agama) {
        return true;
    } else {
        return false;
    }
}
function jenis_gtk($query){
    $data['tendik'] = array(11, 30, 40, 41, 42, 43, 44, 57, 58, 59);
    $data['guru'] = array(3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14, 20, 25, 26, 51, 52, 53, 54, 56);
    $data['instruktur'] = array(97);
    $data['asesor'] = array(98);
    return collect($data[$query]);
}
function bilangan_bulat($angka){
    return number_format($angka, 0);
}
function nilai_ekskul($nilai){
    $predikat = [
        1 => 'Sangat Baik',
        2 => 'Baik',
        3 => 'Cukup',
        4 => 'Kurang',
    ];
    return $predikat[$nilai];
}
function terbilang($angka){
    if($angka){
        return ucwords(Terbilang::make(number_format($angka,0)));
    }
}
function predikat($kkm, $nilai, $produktif = NULL){
    if ($produktif) {
        $result = array(
            'A+'	=> 100, // 95 - 100
            'A'		=> 94, // 90 - 94
            'A-'	=> 89, // 85 - 89
            'B+'	=> 84, // 80 - 84
            'B'		=> 79, // 75 - 79
            'B-'	=> 74, // 70 - 74
            'C'		=> 69, // 65 - 69
            'D'		=> 64, // 0 - 59
        );
    } else {
        $result = array(
            'A+'	=> 100, // 95 - 100
            'A'		=> 94, // 90 - 94
            'A-'	=> 89, // 85 - 89
            'B+'	=> 84, // 80 - 84
            'B'		=> 79, // 75 - 79
            'B-'	=> 74, // 70 - 74
            'C'		=> 69, // 60 - 69
            'D'		=> 59, // 0 - 59
        );
    }
    if ($result[$nilai] > 100)
        $result[$nilai] = 100;
    return $result[$nilai];
}
function konversi_huruf($kkm, $nilai, $produktif = NULL){
    $check_2018 = check_2018();
    if ($check_2018) {
        $show = 'predikat';
        $a = predikat($kkm, 'A') + 1;
        $a_min = predikat($kkm, 'A-') + 1;
        $b_plus = predikat($kkm, 'B+') + 1;
        $b = predikat($kkm, 'B') + 1;
        $b_min = predikat($kkm, 'B-') + 1;
        $c = predikat($kkm, 'C') + 1;
        $d = predikat($kkm, 'D', $produktif) + 1;
        if ($nilai == 0) {
            $predikat 	= '-';
        } elseif ($nilai >= $a) { //$settings->a_min){ //86
            $predikat 	= 'A+';
        } elseif ($nilai >= $a_min) { //$settings->a_min){ //86
            $predikat 	= 'A';
        } elseif ($nilai >= $b_plus) { //$settings->a_min){ //86
            $predikat 	= 'A-';
        } elseif ($nilai >= $b) { //$settings->a_min){ //86
            $predikat 	= 'B+';
        } elseif ($nilai >= $b_min) { //$settings->a_min){ //86
            $predikat 	= 'B';
        } elseif ($nilai >= $c) { //$settings->a_min){ //86
            $predikat 	= 'B-';
        } elseif ($nilai >= $d) { //$settings->a_min){ //86
            $predikat 	= 'C';
        } elseif ($nilai < $d) { //$settings->a_min){ //86
            $predikat 	= 'D';
        }
    } else {
        $b = predikat($kkm, 'b') + 1;
        $c = predikat($kkm, 'c') + 1;
        $d = predikat($kkm, 'd') + 1;
        if ($n == 0) {
            $predikat 	= '-';
            $sikap		= '-';
            $sikap_full	= '-';
        } elseif ($n >= $b) { //$settings->a_min){ //86
            $predikat 	= 'A';
            $sikap		= 'SB';
            $sikap_full	= 'Sangat Baik';
        } elseif ($n >= $c) { //71
            $predikat 	= 'B';
            $sikap		= 'B';
            $sikap_full	= 'Baik';
        } elseif ($n >= $d) { //56
            $predikat 	= 'C';
            $sikap		= 'C';
            $sikap_full	= 'Cukup';
        } elseif ($n < $d) { //56
            $predikat 	= 'D';
            $sikap		= 'K';
            $sikap_full	= 'Kurang';
        }
    }
    return $predikat;		
}
function status_kenaikan($status){
    $data = [
        0 => 'Tinggal Dikelas',
        1 => 'Naik Ke Kelas',
        2 => 'Lulus',
        4 => 'Tidak Lulus',
    ];
    return (!is_null($status)) ? $data[$status] : NULL;
}
function warna_dimensi($id){
    $data = [
        1 => 'primary',
        2 => 'success',
        3 => 'danger',
        4 => 'warning',
        5 => 'info',
        6 => 'secondary'
    ];
    return $data[$id];
}
function opsi_budaya($n)
{
    if (!$n) {
        $predikat 	= '-';
    } elseif ($n >= 4) {
        $predikat 	= '<span class="badge bg-green">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    } elseif ($n >= 3) {
        $predikat 	= '<span class="badge bg-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    } elseif ($n >= 2) {
        $predikat 	= '<span class="badge bg-blue">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    } elseif ($n >= 1) {
        $predikat 	= '<span class="badge bg-yellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    }
    return $predikat;
}
function get_kkm($kelompok_id, $kkm)
{
    if ($kkm) {
        return $kkm;
    }
    $check_2018 = check_2018();
    if ($check_2018) {
        $produktif = array(4, 5, 9, 10, 13);
        $non_produktif = array(1, 2, 3, 6, 7, 8, 11, 12, 99);
        if (in_array($kelompok_id, $produktif)) {
            $new_kkm = 65;
        } elseif (in_array($kelompok_id, $non_produktif)) {
            $new_kkm = 60;
        } else {
            $new_kkm = $kkm;
        }
    }
    return $new_kkm;
}
function status_label($status)
{
    if ($status == '1') :
        $label = '<span class="btn btn-sm btn-success"> Aktif </span>';
    elseif ($status == '0') :
        $label = '<span class="btn btn-sm btn-danger"> Non Aktif </span>';
    endif;
    return $label;
}
function keterangan_ukk($n, $lang = 'ID')
{
    if ($lang == 'ID') {
        if (!$n) {
            $predikat 	= '';
        } elseif ($n >= 90) {
            $predikat 	= 'Sangat Kompeten';
        } elseif ($n >= 75 && $n <= 89) {
            $predikat 	= 'Kompeten';
        } elseif ($n >= 70 && $n <= 74) {
            $predikat 	= 'Cukup Kompeten';
        } elseif ($n < 70) {
            $predikat 	= 'Belum Kompeten';
        }
    } else {
        if (!$n) {
            $predikat 	= '';
        } elseif ($n >= 90) {
            $predikat 	= 'Highly Competent';
        } elseif ($n >= 75 && $n <= 89) {
            $predikat 	= 'Competent';
        } elseif ($n >= 70 && $n <= 74) {
            $predikat 	= 'Partly Competent';
        } elseif ($n < 70) {
            $predikat 	= 'Not Yet Competent';
        }
    }
    return $predikat;
}
function prepare_send($str){
    return rawurlencode(base64_encode(gzcompress(encryptor(serialize($str)))));
}
function prepare_receive($str){
    return unserialize(decryptor(gzuncompress(base64_decode(rawurldecode($str)))));
}
function encryptor($str){
    return $str;
}
function decryptor($str){
    return $str;
}
function clean($string){
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
function get_previous_letter($string)
{
    $last = substr($string, -1);
    $part = substr($string, 0, -1);
    if (strtoupper($last) == 'A') {
        $l = substr($part, -1);
        if ($l == 'A') {
            return substr($part, 0, -1) . "Z";
        }
        return $part . chr(ord($l) - 1);
    } else {
        return $part . chr(ord($last) - 1);
    }
}
function sebaran($input, $a, $b)
{
    $range_data = range($a, $b);
    $output = array_intersect($input, $range_data);
    return $output;
}
function sebaran_tooltip($input, $a, $b, $c)
{
    $range_data = range($a, $b);
    $output = array_intersect($input, $range_data);
    $data = array();
    $nama_siswa = '';
    foreach ($output as $k => $v) {
        $data[] = $k;
    }
    if (count($output) == 0) {
        $result = count($output);
    } else {
        //$result = '<a class="tooltip-' . $c . '" href="javascript:void(0)" title="' . implode('<br />', $data) . '" data-html="true">' . count($output) . '</a>';
        $result = '<a data-bs-toggle="tooltip" data-bs-placement="' . $c . '" data-bs-html="true" href="javascript:void(0)" title="' . implode('<br />', $data) . '">' . count($output) . '</a>';
    }
    return $result;
}
function tingkat_kelas($kelas_10, $kelas_11, $kelas_12, $kelas_13){
    $data = collect([
        ['kelas' => $kelas_10, 'tingkat' => 10],
        ['kelas' => $kelas_11, 'tingkat' => 11], 
        ['kelas' => $kelas_12, 'tingkat' => 12], 
        ['kelas' => $kelas_13, 'tingkat' => 13]
    ]);
    $filtered = $data->filter(function ($value, $key) {
        //dump($key);
        //dump($value['kelas']);
        return $value['kelas'] > 0;
    });
    //dd($filtered->all());
    return $filtered->implode('tingkat', ', ');
}
function table_sync(){
    return [
        'ref.paket_ukk',
        'ref.kompetensi_dasar',
        'ref.capaian_pembelajaran',
        'users',
        'unit_ukk',
        'tujuan_pembelajaran',
        'tp_nilai',
        'sekolah',
        'rombongan_belajar',
        'rombel_4_tahun',
        'rencana_ukk',
        'rencana_penilaian',
        'rencana_budaya_kerja',
        'rapor_pts',
        'ptk_keluar',
        'prestasi',
        'prakerin',
        'peserta_didik',
        'pembelajaran',
        'pd_keluar',
        'nilai_us',
        'nilai_un',
        'nilai_ukk',
        'nilai_tp',
        'nilai_sumatif',
        'nilai_sikap',
        'nilai_remedial',
        'nilai_rapor',
        'nilai_karakter',
        'nilai_ekstrakurikuler',
        'nilai_budaya_kerja',
        'nilai_akhir',
        'nilai',
        'mou',
        'kewirausahaan',
        'kenaikan_kelas',
        'kd_nilai',
        'jurusan_sp',
        'guru',
        'gelar_ptk',
        'ekstrakurikuler',
        'dudi',
        'deskripsi_sikap',
        'deskripsi_mata_pelajaran',
        'catatan_wali',
        'catatan_ppk',
        'catatan_budaya_kerja',
        'bobot_keterampilan',
        'bimbing_pd',
        'aspek_budaya_kerja',
        'asesor',
        'anggota_rombel',
        'anggota_kewirausahaan',
        'anggota_akt_pd',
        'akt_pd',
        'absensi',
    ];
}
function get_table($table, $sekolah_id, $tahun_ajaran_id, $semester_id, $count = NULL){
    $request = DB::table($table)->where(function($query) use ($table, $sekolah_id, $tahun_ajaran_id, $semester_id){
        if(in_array($table, ['ref.kompetensi_dasar'])){
            $query->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('users')
                      ->whereColumn('ref.kompetensi_dasar.user_id', 'users.user_id');
            });
            $query->whereRaw('updated_at > last_sync');
        }
        if(in_array($table, ['ref.paket_ukk', 'users']) || Schema::hasColumn($table, 'sekolah_id')){
            $query->where('sekolah_id', $sekolah_id);
            $query->whereRaw('updated_at > last_sync');
        }
        if(in_array($table, ['ref.capaian_pembelajaran'])){
            $query->where('is_dir', 0);
            $query->whereRaw('updated_at > last_sync');
        }
        if (Schema::hasColumn($table, 'tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $tahun_ajaran_id);
        }
        if (Schema::hasColumn($table, 'semester_id')) {
            $query->where('semester_id', $semester_id);
        }
        if (Schema::hasColumn($table, 'last_sync')) {
            $query->whereRaw('updated_at > last_sync');
        }
    });
    if($count){
        return $request->count();
    } else {
        return $request->get();
    }
}
function nama_table($table){
    $data = str_replace('_', ' ', $table);
    $data = str_replace('ref.', '', $data);
    return ucwords($data);
}
function http_client($satuan, $data_sync){
    $response = Http::withOptions([
        'verify' => false,
        //'debug' => config('app.debug') ? fopen('php://stderr', 'w') : FALSE,
    ])->withHeaders([
        'x-api-key' => $data_sync['sekolah_id'],
    ])->retry(3, 100)->post('http://sync.erapor-smk.net/api/v7/dapodik/'.$satuan, $data_sync);
    return $response->object();
}
function merdeka($nama_kurikulum){
    return Str::contains($nama_kurikulum, 'Merdeka');
}
function get_fase($tingkat){
    if(in_array($tingkat, [1,2])){
        $fase = 'A';
    } elseif(in_array($tingkat, [3,4])){
        $fase = 'B';
    } elseif(in_array($tingkat, [5,6])){
        $fase = 'C';
    } elseif(in_array($tingkat, [7,8,9])){
        $fase = 'D';
    } elseif($tingkat == 10){
        $fase = 'E';
    } else {
        $fase = 'F';
    }
    return $fase;
}
/*function semester_id_salah(){
    return get_setting('semester_id');
    return request()->header('X-Semester-Id') ?? get_setting('semester_id');
    return request()->header('X-Semester-Id');
}
function tahun_ajaran_id(){
    $data = Semester::find(semester_id_salah());
    return ($data) ? $data->tahun_ajaran_id : NULL;
}
function tanggal_semester(){
    $semester = Semester::find(semester_id_salah());
    $data = [
        'tanggal_mulai' => $semester->tanggal_mulai,
        'tanggal_selesai' => $semester->tanggal_selesai,
        'tanggal_cetak' => ($semester->tanggal_cetak) ? tanggalIndo($semester->tanggal_cetak) : tanggalIndo($semester->tanggal_selesai),
    ];
    return $data;
}*/
function semester_id(){
    $data = Semester::where('periode_aktif', 1)->first();
    return ($data) ? $data->semester_id : NULL;
}
function periode_aktif(){
    return get_setting('periode_aktif');
    return request()->header('X-Periode-Aktif') ?? get_setting('periode_aktif');
    return request()->header('X-Periode-Aktif');
}
function sekolah_id(){
    $data = Sekolah::first();
    return $data->sekolah_id;
}
function tanggalIndo($tanggal){
    return ($tanggal) ? Carbon::createFromTimeStamp(strtotime($tanggal))->translatedFormat('j F Y') : NULL;
}
function tanggalSekarang(){
    return strtoupper(Carbon::now()->translatedFormat('j F Y'));
}
function kabupaten($nama){
    return str_replace('KABUPATEN ', '', $nama);
}
function agama($id){
    $data = [
        1 => "Islam",
        2 => "Kristen",
        3 => "Katholik",
        4 => "Hindu",
        5 => "Buddha",
        6 => "Kong Hu Chu",
        7 => "Kepercayaan kpd Tuhan YME",
        98 => "Tidak diisi",
        99 => "Lainnya",
    ];
    return $data[$id];
}
function rupiah($angka){
    return number_format($angka, 0, '.', '.');
}
function loggedUser(){
    return auth()->user();
}
function persen($jml, $total){
    return ($jml) ? round($jml / $total * 100) : 0;
    //return ($jml) ?? round($jml / $total * 100,2);
}
function nilai_asesmen($penilaian_id, $data_nilai){
    $push_nilai = '';
    foreach($data_nilai as $key => $nilai){
        if($data_nilai[$key] && $data_nilai[$key]->penilaian_id == $penilaian_id){
            $push_nilai = $data_nilai[$key]->angka;
        }
    }
    return $push_nilai;
}
function rerata_asesmen($data_nilai, $data_asesmen){
    $push_nilai = [];
    foreach($data_asesmen as $asesmen){
        foreach($data_nilai as $nilai){
            if($nilai && $nilai->penilaian_id == $asesmen->penilaian_id){
                $push_nilai[] = $nilai;
            }
        }
    }
    return ceil(collect($push_nilai)->avg('angka'));
}
function nilai_tp($tp_id, $data_nilai){
    $push_nilai = '';
    foreach($data_nilai as $key => $nilai){
        if($data_nilai[$key] && $data_nilai[$key]->tp_id == $tp_id){
            $push_nilai = $data_nilai[$key]->angka;
        }
    }
    return $push_nilai;
}
function rerata_tp($data_nilai, $data_tp){
    $push_nilai = [];
    foreach($data_tp as $tp){
        foreach($data_nilai as $nilai){
            if($nilai && $nilai->tp_id == $tp->tp_id){
                $push_nilai[] = $nilai;
            }
        }
    }
    return ceil(collect($push_nilai)->avg('angka'));
}
function all_provinsi(){
    return Indonesia::allProvinces();
}
function get_kabupaten($provinsi_id){
    $data = Kabupaten::where('province_code', $provinsi_id)->get();
    return $data;
}
function get_kecamatan($kabupaten_id){
    $data = Kecamatan::where('city_code', $kabupaten_id)->get();
    return $data;
}
function get_desa($kecamatan_id){
    $data = Kelurahan::where('district_code', $kecamatan_id)->get();
    return $data;
}
function hp($nohp) {
    $nohp = str_replace(" ","",$nohp);
    $nohp = str_replace("(","",$nohp);
    $nohp = str_replace(")","",$nohp);
    $nohp = str_replace(".","",$nohp);

    if(!preg_match('/[^+0-9]/',trim($nohp))){
        if(substr(trim($nohp), 0, 3)=='+62'){
            $hp = str_replace('+','',trim($nohp));
        }
        elseif(substr(trim($nohp), 0, 1)=='0'){
            $hp = '62'.substr(trim($nohp), 1);
        }
    }
    return $hp;
}
function getAbsen($presensi, $tanggal, $absen){
    $filtered = $presensi->filter(function ($value, $key) use ($tanggal, $absen){
        $date = Carbon::createFromDate($value->tanggal);
        return $date->format('j') === $tanggal && $value->absen === $absen;
    });
    return ($filtered->count()) ? $filtered->count() : '';
}
function getAbsenBulan($presensi, $bulan, $absen){
    $filtered = $presensi->filter(function ($value, $key) use ($bulan, $absen){
        $date = Carbon::createFromDate($value->tanggal);
        return $date->format('m') === $bulan && $value->absen === $absen;
    });
    return ($filtered->count()) ? $filtered->count() : '';
}
function jmlAbsen($presensi, $absen){
    $filtered = $presensi->filter(function ($value, $key) use ($absen){
        return $value->absen === $absen;
    });
    return ($filtered->count()) ? number_format(($filtered->count() / 11), 0, '.', '.') : '';
}
function createColumnsArray($end_column, $first_letters = '')
{
  $columns = array();
  $length = strlen($end_column);
  $letters = range('A', 'Z');

  // Iterate over 26 letters.
  foreach ($letters as $letter) {
      // Paste the $first_letters before the next.
      $column = $first_letters . $letter;

      // Add the column to the final array.
      $columns[] = $column;

      // If it was the end column that was added, return the columns.
      if ($column == $end_column)
          return $columns;
  }

  // Add the column children.
  foreach ($columns as $column) {
      // Don't itterate if the $end_column was already set in a previous itteration.
      // Stop iterating if you've reached the maximum character length.
      if (!in_array($end_column, $columns) && strlen($column) < $length) {
          $new_columns = createColumnsArray($end_column, $column);
          // Merge the new columns which were created with the final columns array.
          $columns = array_merge($columns, $new_columns);
      }
  }

  return $columns;
}
function num2alpha($n) {
    $r = '';
    for ($i = 1; $n >= 0 && $i < 10; $i++) {
    $r = chr(0x41 + ($n % pow(26, $i) / pow(26, $i - 1))) . $r;
    $n -= pow(26, $i);
    }
    return $r;
}
function alpha2num($a) {
    $r = 0;
    $l = strlen($a);
    for ($i = 0; $i < $l; $i++) {
    $r += pow(26, $i) * (ord($a[$l - $i - 1]) - 0x40);
    }
    return $r - 1;
}
function showNilaiNama($penilaian_id, $nilai){
    for ($i = 0; $i < count($nilai); $i++) {
      if($nilai[$i] && $nilai[$i]->penilaian_id === $penilaian_id){
        return $nilai[$i]->angka;
      }
    }
    return '-';
}
function showNilaiTp($tp_id, $nilai) {
    for ($i = 0; $i < count($nilai); $i++) {
      if($nilai[$i] && $nilai[$i]->tp_id === $tp_id){
        return $nilai[$i]->angka;
      }
    }
    return '-';
}
function findAverageAge($arr){
    $collection = collect($arr);
    $total = $collection->reduce(function ($carry, $item) use ($arr){
        return $carry + ($item->angka / count($arr));
    });
    return $total;
}
function nilaiTp($data_nilai, $data_tp){
    $push_nilai = [];
    for ($a = 0; $a < count($data_tp); $a++) {
      for ($i = 0; $i < count($data_nilai); $i++) {
        if($data_nilai[$i] && $data_nilai[$i]->tp_id === $data_tp[$a]->tp_id){
          if($data_nilai[$i]->tp_id){
            array_push($push_nilai, $data_nilai[$i]);
          }
        }
      }
    }
    return $push_nilai;
}
function nilaiAsesmen($data_nilai, $data_asesmen){
    $push_nilai = [];
    for ($a = 0; $a < count($data_asesmen); $a++) {
      for ($i = 0; $i < count($data_nilai); $i++) {
        if($data_nilai[$i] && $data_nilai[$i]->penilaian_id === $data_asesmen[$a]->penilaian_id){
          if($data_nilai[$i]->penilaian_id){
            array_push($push_nilai, $data_nilai[$i]);
          }
        }
      }
    }
    return $push_nilai;
}
function predikat_ekstra($angka){
    $predikat = '';
    if($angka > 0 && $angka < 70){
        $predikat = 'Kurang';
    }
    if($angka >= 70 && $angka < 80){
        $predikat = 'Cukup';
    }
    if($angka >= 80 && $angka < 90){
        $predikat = 'Baik';
    }
    if($angka >= 90){
        $predikat = 'Sangat Baik';
    }
    return $predikat;
}
function getNilai($all_nilai, $anggota_rombel_id){
    $collection = collect($all_nilai);
    $filtered = $collection->filter(function ($value, $key) use ($anggota_rombel_id){
        return $value['anggota_rombel_id'] == $anggota_rombel_id;
    });
    $total = ceil($filtered->avg('angka'));
    return $total;
}