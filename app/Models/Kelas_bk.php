<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Kelas_bk extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'kelas_bk';
	protected $primaryKey = 'kelas_bk_id';
	protected $guarded = [];
	
	public function rombongan_belajar()
	{
		return $this->hasOne(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function guru()
	{
		return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
	}
}
