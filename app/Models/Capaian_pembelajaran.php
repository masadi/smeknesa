<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Capaian_pembelajaran extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'capaian_pembelajaran';
	protected $primaryKey = 'cp_id';
	protected $guarded = [];
	
	public function pembelajaran()
	{
		return $this->belongsTo(Pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function mata_pelajaran()
	{
		return $this->belongsTo(Mata_pelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function guru()
	{
		return $this->belongsTo(Guru::class, 'guru_id', 'guru_id');
		return $this->hasOneThrough(
            Guru::class,
			Pembelajaran::class,
			'mata_pelajaran_id',
			'guru_id',
			'mata_pelajaran_id',
			'guru_id'
        );
	}
	public function tp()
	{
		return $this->hasMany(Tujuan_pembelajaran::class, 'cp_id', 'cp_id');
	}
	public function nilai_tp(){
		return $this->hasManyThrough(
            Nilai::class,
			Tujuan_pembelajaran::class,
			'cp_id',
			'tp_id',
			'cp_id',
			'tp_id'
        );
	}
}
