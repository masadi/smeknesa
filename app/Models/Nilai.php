<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Nilai extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'nilai';
	protected $primaryKey = 'nilai_id';
	protected $guarded = [];
	public function pembelajaran()
	{
		return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function tp()
	{
		return $this->belongsTo(Tujuan_pembelajaran::class, 'tp_id', 'tp_id');
	}
	public function pd()
	{
		return $this->hasOneThrough(
            Peserta_didik::class,
			Anggota_rombel::class,
			'anggota_rombel_id',
			'peserta_didik_id',
			'anggota_rombel_id',
			'peserta_didik_id'
        );
	}
	public function anggota_rombel()
	{
		return $this->belongsTo(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
}
