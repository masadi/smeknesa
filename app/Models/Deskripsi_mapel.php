<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Deskripsi_mapel extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'deskripsi_mapel';
	protected $primaryKey = 'deskripsi_mapel_id';
	protected $guarded = [];
    public function kelas(){
		return $this->hasOneThrough(
            Rombongan_belajar::class,
			Pembelajaran::class,
			'pembelajaran_id',
			'rombongan_belajar_id',
			'pembelajaran_id',
			'rombongan_belajar_id'
        );
    }
    public function pembelajaran()
	{
		return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
}
