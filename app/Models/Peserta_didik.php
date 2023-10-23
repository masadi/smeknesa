<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Carbon\Carbon;

class Peserta_didik extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'peserta_didik';
	protected $primaryKey = 'peserta_didik_id';
	protected $guarded = [];
    protected $appends = ['nama_lengkap', 'tanggal_lahir_indo', 'diterima_indo'];
	
	public function anggota_rombel()
	{
		return $this->hasOne(Anggota_rombel::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function kelas(){
		return $this->hasOneThrough(
            Rombongan_belajar::class,
            Anggota_rombel::class,
            'peserta_didik_id', // Foreign key on the cars table...
            'rombongan_belajar_id', // Foreign key on the owners table...
            'peserta_didik_id', // Local key on the mechanics table...
            'rombongan_belajar_id' // Local key on the cars table...
        )->where('tingkat', '<>', 0);
	}
	public function ekskul(){
		return $this->hasManyThrough(
            Rombongan_belajar::class,
            Anggota_rombel::class,
            'peserta_didik_id', // Foreign key on the cars table...
            'rombongan_belajar_id', // Foreign key on the owners table...
            'peserta_didik_id', // Local key on the mechanics table...
            'rombongan_belajar_id' // Local key on the cars table...
        )->where('tingkat', 0);
	}
	public function anggota_ekskul(){
		return $this->hasMany(Anggota_rombel::class, 'peserta_didik_id', 'peserta_didik_id')->whereHas('rombongan_belajar', function($query){
			$query->where('tingkat', 0);
		});
	}
	public function keluar(){
		return $this->hasOneThrough(
            Jenis_keluar::class,
            Pd_keluar::class,
            'peserta_didik_id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'peserta_didik_id', // Local key on the mechanics table...
            'jenis_keluar_id' // Local key on the cars table...
        );
	}
    public function pd_keluar()
	{
		return $this->hasOne(Pd_keluar::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function kenaikan_kelas()
	{
		return $this->hasOneThrough(
            Kenaikan_kelas::class,
            Anggota_rombel::class,
            'peserta_didik_id', // Foreign key on the cars table...
            'anggota_rombel_id', // Foreign key on the owners table...
            'peserta_didik_id', // Local key on the mechanics table...
            'anggota_rombel_id' // Local key on the cars table...
        );
		return $this->hasOne(Kenaikan_kelas::class, 'peserta_didik_id', 'peserta_didik_id');
	}
    public function getTanggalLahirIndoAttribute()
	{
		return (isset($this->attributes['tanggal_lahir']) && $this->attributes['tanggal_lahir']) ? Carbon::parse($this->attributes['tanggal_lahir'])->translatedFormat('d F Y') : '-';
	}
	public function getDiterimaIndoAttribute()
	{
		return (isset($this->attributes['diterima']) && $this->attributes['diterima']) ? Carbon::parse($this->attributes['diterima'])->translatedFormat('d F Y') : '-';
	}
	public function getNamaLengkapAttribute()
	{
		return strtoupper($this->attributes['nama']);
	}
	public function getTempatLahirAttribute()
	{
		return strtoupper($this->attributes['tempat_lahir']);
	}
	public function pengguna(){
		return $this->hasOne(User::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function nilai(){
		return $this->hasManyThrough(
            Nilai::class,
			Anggota_rombel::class,
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id'
        );
    }
	public function deskripsi_tercapai()
	{
		return $this->hasOneThrough(
            Deskripsi_mapel::class,
			Anggota_rombel::class,
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id'
        )->where('tercapai', 1);
		return $this->hasOne(Deskripsi_mapel::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('tercapai', 1);
	}
	public function deskripsi_belum_tercapai()
	{
		return $this->hasOneThrough(
            Deskripsi_mapel::class,
			Anggota_rombel::class,
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id'
        )->where('tercapai', 0);
		return $this->hasOne(Deskripsi_mapel::class, 'anggota_rombel_id', 'anggota_rombel_id')->where('tercapai', 0);
	}
	public function presensi()
	{
		return $this->hasManyThrough(
            Presensi::class,
			Anggota_rombel::class,
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id'
        );
		return $this->hasMany(Presensi::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function desa()
	{
		return $this->belongsTo(Kelurahan::class, 'desa_id', 'code');
	}
	public function kecamatan()
	{
		return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'code');
	}
	public function kabupaten()
	{
		return $this->belongsTo(Kabupaten::class, 'kabupaten_id', 'code');
	}
	public function provinsi()
	{
		return $this->belongsTo(Provinsi::class, 'provinsi_id', 'code');
	}
	public function user()
	{
		return $this->belongsTo(User::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function agama(){
		return $this->hasOne(Agama::class, 'agama_id', 'agama_id');
	}
	public function pekerjaan_ayah(){
		return $this->hasOne(Pekerjaan::class, 'pekerjaan_id', 'kerja_ayah');
	}
	public function pekerjaan_ibu(){
		return $this->hasOne(Pekerjaan::class, 'pekerjaan_id', 'kerja_ibu');
	}
	public function alpa()
	{
		return $this->hasManyThrough(
            Presensi::class,
			Anggota_rombel::class,
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id'
        )->where('absen', 'A');
	}
	public function ijin()
	{
		return $this->hasManyThrough(
            Presensi::class,
			Anggota_rombel::class,
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id'
        )->where('absen', 'I');
	}
	public function sakit()
	{
		return $this->hasManyThrough(
            Presensi::class,
			Anggota_rombel::class,
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id'
        )->where('absen', 'S');
	}
	public function dispen()
	{
		return $this->hasManyThrough(
            Presensi::class,
			Anggota_rombel::class,
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id'
        )->where('absen', 'D');
	}
	public function pd_pkl()
	{
		return $this->hasOne(Pd_pkl::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function nilai_pkl()
	{
		return $this->hasMany(Nilai_pkl::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function sekolah()
	{
		return $this->belongsTo(Sekolah::class, 'sekolah_id', 'sekolah_id');
	}
	public function nilai_ekstra()
	{
		return $this->hasManyThrough(
            Nilai_ekstra::class,
			Anggota_rombel::class,
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id'
        );
	}
	public function absensi_pkl()
	{
		return $this->hasOne(Absensi_pkl::class, 'peserta_didik_id', 'peserta_didik_id');
	}
}
