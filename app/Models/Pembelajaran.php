<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Pembelajaran extends Model
{
    use HasFactory, SoftDeletes, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'pembelajaran';
	protected $primaryKey = 'pembelajaran_id';
	protected $guarded = [];
	protected $appends = ['nama_rombel'];
	public function getNamaRombelAttribute(){
		$nama_rombel = NULL;
		if(isset($this->attributes['nama_mata_pelajaran']) && $this->rombongan_belajar){
			$nama_rombel = $this->rombongan_belajar->nama;
		}
		return $nama_rombel;
	}
	public function guru(){
		return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
	}
	public function pengajar(){
		return $this->hasOne(Guru::class, 'guru_id', 'guru_pengajar_id');
	}
	public function mata_pelajaran(){
		return $this->hasOne(Mata_pelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function rombongan_belajar(){
		return $this->hasOne(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function nilai(){
		return $this->hasMany(Nilai::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function anggota_rombel(){
		return $this->hasManyThrough(
            Anggota_rombel::class,
			Rombongan_belajar::class,
			'rombongan_belajar_id',
			'rombongan_belajar_id',
			'rombongan_belajar_id',
			'rombongan_belajar_id'
        );
    }
	public function pd(){
		return $this->hasOneThrough(
            Anggota_rombel::class,
			Nilai::class,
			'pembelajaran_id',
			'anggota_rombel_id',
			'pembelajaran_id',
			'anggota_rombel_id'
        );
    }
	public function nilai_formatif(){
		return $this->hasMany(Nilai::class, 'pembelajaran_id', 'pembelajaran_id')->where('jenis_penilaian_id', 2);
	}
	public function nilai_sumatif(){
		return $this->hasMany(Nilai::class, 'pembelajaran_id', 'pembelajaran_id')->where('jenis_penilaian_id', 3);
	}
	public function all_nilai(){
		return $this->hasMany(Nilai::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function rencana_keterampilan(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 2);
	}
	public function rencana_pk(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 3);
	}
	public function rencana_pengetahuan_dinilai(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 1)->whereHas('nilai');
	}
	public function rencana_keterampilan_dinilai(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 2)->whereHas('nilai');
	}
	public function rencana_pk_dinilai(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 3)->whereHas('nilai');
	}
	public function nilai_akhir(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function nilai_akhir_pengetahuan(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 1);
	}
	public function nilai_akhir_keterampilan(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 2);
	}
	public function nilai_akhir_pk(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 3);
	}
	public function nilai_akhir_kurmer(){
		return $this->hasOne(Nilai_akhir::class, 'pembelajaran_id', 'pembelajaran_id')->where('kompetensi_id', '=', 4);
	}
	public function kelas(){
		return $this->hasOneThrough(
            Anggota_rombel::class,
			Rombongan_belajar::class,
			'rombongan_belajar_id',
			'rombongan_belajar_id',
			'rombongan_belajar_id',
			'rombongan_belajar_id'
        );
    }
	public function rencana_penilaian(){
		return $this->hasMany(Rencana_penilaian::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function kd_nilai()
    {
        return $this->hasManyThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        );
    }
	public function kd_nilai_capaian()
    {
        return $this->hasOneThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        );
    }
	public function kd_nilai_p()
    {
        return $this->hasManyThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        )->where('kompetensi_id', 1);
    }
	public function kd_nilai_k()
    {
        return $this->hasManyThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        )->where('kompetensi_id', 2);
    }
	public function kd_nilai_pk()
    {
        return $this->hasManyThrough(
            Kd_nilai::class,
            Rencana_penilaian::class,
            'pembelajaran_id', // Foreign key on users table...
            'rencana_penilaian_id', // Foreign key on posts table...
            'pembelajaran_id', // Local key on countries table...
            'rencana_penilaian_id' // Local key on users table...
        )->where('kompetensi_id', 3);
    }
	public function kelompok(){
		return $this->hasOne(Kelompok::class, 'kelompok_id', 'kelompok_id');
	}
	public function rapor_pts(){
		//return $this->hasOne(Rapor_pts::class, 'pembelajaran_id', 'pembelajaran_id');
		return $this->hasMany(Rapor_pts::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function nilai_akhir_p(){
		return $this->hasOneThrough(
            Nilai_akhir::class,
            Pembelajaran::class,
            'pembelajaran_id', // Foreign key on users table...
            'pembelajaran_id', // Foreign key on history table...
            'pembelajaran_id', // Local key on suppliers table...
            'pembelajaran_id' // Local key on users table...
        )->where('kompetensi_id', 1);
	}
	public function nilai_akhir_k(){
		return $this->hasOneThrough(
            Nilai_akhir::class,
            Pembelajaran::class,
            'pembelajaran_id', // Foreign key on users table...
            'pembelajaran_id', // Foreign key on history table...
            'pembelajaran_id', // Local key on suppliers table...
            'pembelajaran_id' // Local key on users table...
        )->where('kompetensi_id', 2);
	}
	public function nilai_akhir_is_pk(){
		return $this->hasOneThrough(
            Nilai_akhir::class,
            Pembelajaran::class,
            'pembelajaran_id', // Foreign key on users table...
            'pembelajaran_id', // Foreign key on history table...
            'pembelajaran_id', // Local key on suppliers table...
            'pembelajaran_id' // Local key on users table...
        )->where('kompetensi_id', 3);
	}
	public function nilai_rapor(){
		return $this->hasOne(Nilai_rapor::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function deskripsi_mata_pelajaran(){
		return $this->hasMany(Deskripsi_mata_pelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function single_deskripsi_mata_pelajaran(){
		return $this->hasOne(Deskripsi_mata_pelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function getSkmAttribute()
    {
		if($this->kkm){
			return $this->kkm;
		}
		$check_2018 = $this->check_2018();
		if ($check_2018) {
			$produktif = [4, 5, 9, 10, 13];
			$non_produktif = [1, 2, 3, 6, 7, 8, 11, 12, 99];
			if (in_array($this->kelompok_id, $produktif)) {
				return 65;
			} elseif (in_array($this->kelompok_id, $non_produktif)) {
				return 60;
			} else {
				return $this->kkm;
			}
		}
    }
	private function check_2018()
	{
		$semester_id = session('semester_aktif');
		$tahun = substr($semester_id, 0, 4);
		if ($tahun >= 2018) {
			return true;
		} else {
			return false;
		}
	}
	public function induk(){
		return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'induk_pembelajaran_id');
	}
	public function semester(){
		return $this->hasOne(Semester::class, 'semester_id', 'semester_id');
	}
	public function tema()
	{
		return $this->hasMany(Pembelajaran::class, 'induk_pembelajaran_id', 'pembelajaran_id');
	}
	public function cp()
	{
		return $this->hasMany(Capaian_pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function tp()
	{
		return $this->hasManyThrough(
            Tujuan_pembelajaran::class,
			Capaian_pembelajaran::class,
			'mata_pelajaran_id',
			'cp_id',
			'mata_pelajaran_id',
			'cp_id'
        );
		return $this->hasMany(Capaian_pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function jadwal()
	{
		return $this->belongsTo(Jadwal::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function deskripsi_tercapai(){
		return $this->hasOne(Deskripsi_mapel::class, 'pembelajaran_id', 'pembelajaran_id')->where('tercapai', 1);
	}
	public function deskripsi_belum_tercapai(){
		return $this->hasOne(Deskripsi_mapel::class, 'pembelajaran_id', 'pembelajaran_id')->where('tercapai', 0);
	}
	public function jam(){
		return $this->hasManyThrough(
            Jam::class,
			Jadwal::class,
			'pembelajaran_id',
			'jadwal_id',
			'pembelajaran_id',
			'jadwal_id'
        );
    }
	public function penilaian()
	{
		return $this->hasMany(Penilaian::class, 'pembelajaran_id', 'pembelajaran_id');
	}
}
