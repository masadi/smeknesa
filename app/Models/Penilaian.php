<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Penilaian extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'penilaian';
	protected $primaryKey = 'penilaian_id';
	protected $guarded = [];
	public function pembelajaran()
	{
		return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
	public function jenis_penilaian()
	{
		return $this->belongsTo(Jenis_penilaian::class, 'jenis_penilaian_id', 'id');
	}
	public function nilai()
	{
		return $this->hasOne(Nilai::class, 'penilaian_id', 'penilaian_id');
	}
}
