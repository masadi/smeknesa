<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Anggota_rombel extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'anggota_rombel';
	protected $primaryKey = 'anggota_rombel_id';
	protected $guarded = [];
	public function semester()
	{
		return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
	}
    public function rombongan_belajar()
	{
		return $this->hasOne(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
    public function peserta_didik()
	{
		return $this->hasOne(Peserta_didik::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function sekolah()
	{
		return $this->hasOne(Sekolah::class, 'sekolah_id', 'sekolah_id');
	}
	public function presensi()
	{
		return $this->hasMany(Presensi::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_satuan()
	{
		return $this->hasOne(Nilai::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function all_nilai()
	{
		return $this->hasMany(Nilai::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function deskripsi_tercapai()
	{
		return $this->hasOne(Deskripsi_mapel::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('tercapai', 1);
	}
	public function deskripsi_belum_tercapai()
	{
		return $this->hasOne(Deskripsi_mapel::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('tercapai', 0);
	}
	public function nilai_ekskul()
	{
		return $this->hasOne(Nilai_ekskul::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_ekstra()
	{
		return $this->hasMany(Nilai_ekstra::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function absensi_ekstra()
	{
		return $this->hasOne(Absensi_ekstra::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_projek()
	{
		return $this->hasMany(Nilai_projek::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function catatan_projek()
	{
		return $this->hasMany(Catatan_projek::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function kenaikan_kelas()
	{
		return $this->hasOne(Kenaikan_kelas::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai()
	{
		return $this->hasOne(Nilai::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function nilai_ukk()
	{
		return $this->hasOne(Nilai_ukk::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
}
