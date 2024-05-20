<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Rencana_ukk extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'rencana_ukk';
	protected $primaryKey = 'rencana_ukk_id';
	protected $guarded = [];
	public function sekolah()
	{
		return $this->belongsTo(Sekolah::class, 'sekolah_id', 'sekolah_id');
	}
	public function guru_internal()
	{
		return $this->belongsTo(Guru::class, 'internal', 'guru_id');
	}
	public function asesor()
	{
		return $this->belongsTo(Asesor::class, 'asesor_id', 'asesor_id');
	}
	public function dudi(){
		return $this->hasOne(Dudi::class, 'dudi_id', 'dudi_id');
	}
	public function paket_ukk(){
		return $this->hasOne(Paket_ukk::class, 'paket_ukk_id', 'paket_ukk_id');
	}
	public function nilai_ukk(){
		return $this->hasOne(Nilai_ukk::class, 'rencana_ukk_id', 'rencana_ukk_id');
	}
	public function pd()
	{
		return $this->hasManyThrough(
			Peserta_didik::class, 
			Nilai_ukk::class,
			'rencana_ukk_id',
			'peserta_didik_id',
			'rencana_ukk_id',
			'peserta_didik_id',
		);
	}
}
