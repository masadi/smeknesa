<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Rombongan_belajar extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'rombongan_belajar';
	protected $primaryKey = 'rombongan_belajar_id';
	protected $guarded = [];
	
	public function wali_kelas()
	{
		return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
	}
	public function kelas_bk()
	{
		return $this->hasOne(Kelas_bk::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function kurikulum()
	{
		return $this->hasOne(Kurikulum::class, 'kurikulum_id', 'kurikulum_id');
	}
	
	public function jurusan_sp()
	{
		return $this->hasOne(Jurusan_sp::class, 'jurusan_sp_id', 'jurusan_sp_id');
	}

	public function pembelajaran()
	{
		return $this->hasMany(Pembelajaran::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function jadwal()
	{
		return $this->hasManyThrough(
            Jadwal::class,
            Pembelajaran::class,
            'rombongan_belajar_id', // Foreign key on the environments table...
            'pembelajaran_id', // Foreign key on the deployments table...
            'rombongan_belajar_id', // Local key on the projects table...
            'pembelajaran_id' // Local key on the environments table...
        );
		return $this->hasMany(Pembelajaran::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	
	public function anggota_rombel()
	{
		return $this->hasMany(Anggota_rombel::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function kelas_ekskul(){
		return $this->hasOne(Ekstrakurikuler::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function semester()
	{
		return $this->hasOne(Semester::class, 'semester_id', 'semester_id');
	}
	public function jurusan()
	{
		return $this->hasOne(Jurusan::class, 'jurusan_id', 'jurusan_id');
	}
	public function sekolah()
	{
		return $this->hasOne(Sekolah::class, 'sekolah_id', 'sekolah_id');
	}
	public function pd(){
		return $this->hasManyThrough(
            Peserta_didik::class,
            Anggota_rombel::class,
            'rombongan_belajar_id', // Foreign key on the environments table...
            'peserta_didik_id', // Foreign key on the deployments table...
            'rombongan_belajar_id', // Local key on the projects table...
            'peserta_didik_id' // Local key on the environments table...
        );
	}
	public function rombel_trigger()
	{
		return $this->hasOne(Rombel_trigger::class, 'id_rombel_lama', 'rombongan_belajar_id');
	}
	public function materi_ekstra()
	{
		return $this->hasMany(Materi_ekstra::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function nilai_ekstra()
	{
		return $this->hasMany(Nilai_ekstra::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
}
