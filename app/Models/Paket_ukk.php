<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Paket_ukk extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'paket_ukk';
	protected $primaryKey = 'paket_ukk_id';
	protected $guarded = [];
	public function jurusan_sp()
	{
		return $this->belongsTo(Jurusan_sp::class, 'jurusan_sp_id', 'jurusan_sp_id');
	}
	public function kurikulum()
	{
		return $this->belongsTo(Kurikulum::class, 'kurikulum_id', 'kurikulum_id');
	}
	public function unit_ukk(){
		return $this->hasMany(Unit_ukk::class, 'paket_ukk_id', 'paket_ukk_id');
	}
}
