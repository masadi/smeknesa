<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Jurusan_sp extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'jurusan_sp';
	protected $primaryKey = 'jurusan_sp_id';
	protected $guarded = [];
	public function kajur()
	{
		return $this->belongsTo(Guru::class, 'guru_id', 'guru_id');
	}
	public function rombongan_belajar(){
		return $this->hasMany(Rombongan_belajar::class, 'jurusan_sp_id', 'jurusan_sp_id');
	}
}
