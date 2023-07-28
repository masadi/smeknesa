<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'sekolah';
	protected $primaryKey = 'sekolah_id';
	protected $guarded = [];

	public function kepala_sekolah(){
		return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
	}
	public function ptk()
	{
		return $this->hasMany(Guru::class, 'sekolah_id', 'sekolah_id');
	}
	/**
	 * Get the user that owns the Sekolah
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'sekolah_id', 'sekolah_id');
	}
	
	public function rombongan_belajar()
	{
		return $this->hasMany(Rombongan_belajar::class, 'sekolah_id', 'sekolah_id');
	}
	public function peserta_didik(){
		return $this->hasMany(Peserta_didik::class, 'sekolah_id', 'sekolah_id');
	}
	public function pd_aktif()
	{
		return $this->hasMany(Peserta_didik::class, 'sekolah_id', 'sekolah_id')->whereHas('anggota_rombel', function(Builder $query){
			$query->whereHas('rombongan_belajar', function(Builder $query){
				$query->where('jenis_rombel', 1);
				$query->where('semester_id', session('semester_aktif'));
			});
			$query->where('semester_id', session('semester_aktif'));
		});
	}
	
	public function pd_keluar()
	{
		return $this->hasMany(Peserta_didik::class, 'sekolah_id', 'sekolah_id')->whereDoesntHave('anggota_rombel', function(Builder $query){
			$query->whereHas('rombongan_belajar', function(Builder $query){
				//$query->where('jenis_rombel', 1);
				$query->where('semester_id', session('semester_aktif'));
			});
			$query->where('semester_id', session('semester_aktif'));
		});
	}
	public function pembelajaran()
	{
		return $this->hasMany(Pembelajaran::class, 'sekolah_id', 'sekolah_id');
	}
	public function ekstrakurikuler()
	{
		return $this->hasMany(Rombongan_belajar::class, 'sekolah_id', 'sekolah_id')->where('jenis_rombel', 51);
	}
	public function anggota_ekskul()
	{
		return $this->hasMany(Anggota_rombel::class, 'sekolah_id', 'sekolah_id')->whereHas('rombongan_belajar', function(Builder $query){
			$query->where('jenis_rombel', 51);
		});
	}
	public function presensi_guru(){
		return $this->hasManyThrough(
            Presensi::class,
			Guru::class,
			'sekolah_id',
			'guru_id',
			'sekolah_id',
			'guru_id',
        );
	}
	public function presensi_pd(){
		return $this->hasManyThrough(
            Presensi::class,
			Anggota_rombel::class,
			'sekolah_id',
			'anggota_rombel_id',
			'sekolah_id',
			'anggota_rombel_id'
        );
	}
	public function all_nilai(){
		return $this->hasManyThrough(
            Nilai::class,
			Pembelajaran::class,
			'sekolah_id',
			'pembelajaran_id',
			'sekolah_id',
			'pembelajaran_id'
        );
		return $this->hasMany(Nilai::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function kabupaten(){
		return $this->hasOne(Kabupaten::class, 'code', 'kabupaten_id');
	}
}
