<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Jadwal extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'jadwal';
	protected $primaryKey = 'jadwal_id';
	protected $guarded = [];
    public function pembelajaran()
	{
		return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
    public function kelas()
	{
		return $this->hasOne(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function guru()
    {
        return $this->hasOneThrough(
            Guru::class,
            Pembelajaran::class,
            'pembelajaran_id',
            'guru_id',
            'pembelajaran_id',
            'guru_id'
        );
    }
    public function jam()
    {
        return $this->hasMany(Jam::class, 'jadwal_id', 'jadwal_id');
    }
}
