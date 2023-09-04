<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Carbon\Carbon;

class Guru extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'guru';
	protected $primaryKey = 'guru_id';
	protected $guarded = [];
	protected $appends = ['nama_lengkap', 'tanggal_lahir_indo'];
	public function sekolah()
	{
		return $this->hasOne(Sekolah::class, 'sekolah_id', 'sekolah_id');
	}
	public function getTanggalLahirIndoAttribute()
	{
		return (isset($this->attributes['tanggal_lahir']) && $this->attributes['tanggal_lahir']) ? strtoupper(Carbon::parse($this->attributes['tanggal_lahir'])->translatedFormat('d F Y')) : '-';
	}
	public function rombongan_belajar(){
		return $this->hasOne(Rombongan_belajar::class, 'guru_id', 'guru_id');
	}
	public function pembelajaran(){
		return $this->hasMany(Pembelajaran::class, 'guru_id', 'guru_id');
	}
	public function getTempatLahirAttribute(){
		return strtoupper($this->attributes['tempat_lahir']);
	}
	public function getNamaLengkapAttribute(){
		return strtoupper($this->attributes['nama']);
		$gelar_depan = '';
		$gelar_belakang = '';
		if($this->gelar_depan()->exists()){
			$gelar_depan = $this->gelar_depan()->get()->unique()->implode('kode', '. ') . '. ';
		}
		if($this->gelar_belakang()->exists()){
			$gelar_belakang = ', ' . $this->gelar_belakang()->get()->unique()->implode('kode', '., ') . '.';
		}
		return $gelar_depan . strtoupper($this->attributes['nama']). $gelar_belakang;
	}
	public function gelar_depan(){
		return $this->hasManyThrough(
            Gelar::class,
            Gelar_ptk::class,
            'guru_id',
            'gelar_akademik_id',
            'guru_id',
            'gelar_akademik_id'
        )->where('posisi_gelar', 1)->whereNotIn('gelar_ptk.gelar_akademik_id', [9999, 99999])->orderBy('kode', 'desc');
	}
	public function gelar_belakang(){
		return $this->hasManyThrough(
            Gelar::class,
            Gelar_ptk::class,
            'guru_id',
            'gelar_akademik_id',
            'guru_id',
            'gelar_akademik_id'
        )->where('posisi_gelar', 2)->whereNotIn('gelar_ptk.gelar_akademik_id', [9999, 99999])->orderBy('kode', 'desc');
	}
	public function pengguna(){
		return $this->hasOne(User::class, 'guru_id', 'guru_id');
	}
	public function kajur(){
		return $this->hasOne(Kajur::class, 'guru_id', 'guru_id');
	}
	public function presensi()
	{
		return $this->hasMany(Presensi::class, 'guru_id', 'guru_id');
	}
	public function jadwal(){
		return $this->hasManyThrough(
            Jadwal::class,
            Pembelajaran::class,
            'guru_id',
            'pembelajaran_id',
            'guru_id',
            'pembelajaran_id'
        );
	}
	public function provinsi(){
		return $this->hasOne(Provinsi::class, 'code', 'provinsi_id');
	}
	public function kabupaten(){
		return $this->hasOne(Kabupaten::class, 'code', 'kabupaten_id');
	}
	public function kecamatan(){
		return $this->hasOne(Kecamatan::class, 'code', 'kecamatan_id');
	}
	public function desa(){
		return $this->hasOne(Kelurahan::class, 'code', 'desa_id');
	}
	public function agama(){
		return $this->hasOne(Agama::class, 'agama_id', 'agama_id');
	}
	public function kelas_bk(){
		return $this->hasMany(Kelas_bk::class, 'guru_id', 'guru_id');
	}
}
