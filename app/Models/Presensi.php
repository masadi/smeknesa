<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;

class Presensi extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'presensi';
	protected $primaryKey = 'presensi_id';
	protected $guarded = [];
	public function anggota_rombel()
	{
		return $this->hasOne(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function presensi_jadwal()
	{
		return $this->hasOneThrough(
            Jadwal::class,
            Presensi_jadwal::class,
            'presensi_id', // Foreign key on the cars table...
            'jadwal_id', // Foreign key on the owners table...
            'presensi_id', // Local key on the mechanics table...
            'jadwal_id' // Local key on the cars table...
        );
	}
}
