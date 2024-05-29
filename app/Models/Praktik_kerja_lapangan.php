<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;

class Praktik_kerja_lapangan extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	protected $table = 'praktik_kerja_lapangan';
	protected $primaryKey = 'pkl_id';
	protected $guarded = [];
	protected $appends = ['tgl_mulai', 'tgl_selesai', 'nama_dudi'];
	public function guru(){
		return $this->belongsTo(Guru::class, 'guru_id', 'guru_id');
	}
	public function rombongan_belajar(){
		return $this->belongsTo(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function pd_pkl(){
		return $this->hasMany(Pd_pkl::class, 'pkl_id', 'pkl_id');
	}
	public function nilai_pkl(){
		return $this->hasMany(Nilai_pkl::class, 'pkl_id', 'pkl_id');
	}
	public function tp_pkl(){
		return $this->hasMany(Tp_pkl::class, 'pkl_id', 'pkl_id');
	}
	public function dudi(){
		return $this->belongsTo(Dudi::class, 'dudi_id', 'dudi_id');
	}
	public function getNamaDudiAttribute()
	{
		return ($this->dudi) ? $this->dudi->nama : '';
	}
	public function getTglMulaiAttribute(){
		return Carbon::parse($this->attributes['tanggal_mulai'])->translatedFormat('d F Y');
	}
	public function getTglSelesaiAttribute(){
		return Carbon::parse($this->attributes['tanggal_selesai'])->translatedFormat('d F Y');
	}
	public function pembelajaran()
	{
		return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
}
