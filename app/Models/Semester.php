<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Semester extends Model
{
    use HasFactory;
    public $incrementing = false;
	protected $table = 'semester';
	protected $primaryKey = 'semester_id';
	protected $guarded = [];
	protected $appends = ['tanggal_mulai_str', 'tanggal_selesai_str', 'tanggal_cetak_str', 'tanggal_remedial_str'];
	public function getTanggalMulaiStrAttribute()
	{
		return Carbon::parse($this->attributes['tanggal_mulai'])->translatedFormat('d F Y');
	}
	public function getTanggalSelesaiStrAttribute()
	{
		return Carbon::parse($this->attributes['tanggal_selesai'])->translatedFormat('d F Y');
	}
	public function getTanggalCetakStrAttribute()
	{
		return ($this->attributes['tanggal_cetak']) ? Carbon::parse($this->attributes['tanggal_cetak'])->translatedFormat('d F Y') : '-';
	}
	public function getTanggalRemedialStrAttribute()
	{
		return ($this->attributes['tanggal_remedial']) ? Carbon::parse($this->attributes['tanggal_remedial'])->translatedFormat('d F Y') : '-';
	}
	public function tahun_ajaran()
	{
		return $this->hasOne(Tahun_ajaran::class, 'tahun_ajaran_id', 'tahun_ajaran_id');
	}
}
