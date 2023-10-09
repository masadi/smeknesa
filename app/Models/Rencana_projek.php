<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Rencana_projek extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'rencana_projek';
	protected $primaryKey = 'rencana_projek_id';
	protected $guarded = [];
	public function pembelajaran()
	{
		return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function rombongan_belajar()
	{
		return $this->belongsTo(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function tema()
	{
		return $this->belongsTo(Tema_projek::class, 'tema_id', 'id');
	}
	public function elemen_rencana_projek()
	{
		return $this->hasMany(Elemen_rencana_projek::class, 'rencana_projek_id', 'rencana_projek_id');
	}
	public function catatan_projek()
	{
		return $this->hasOne(Catatan_projek::class, 'rencana_projek_id', 'rencana_projek_id');
	}
}
