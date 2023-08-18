<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Jam extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'jam';
	protected $primaryKey = 'jam_id';
	protected $guarded = [];
    
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'jadwal_id');
    }
    public function presensi_jam()
    {
        return $this->hasManyThrough(
            Presensi::class,
            Presensi_jam::class,
            'jam_id', // Foreign key on the environments table...
            'presensi_id', // Foreign key on the deployments table...
            'jam_id', // Local key on the projects table...
            'presensi_id' // Local key on the environments table...
        );
    }
}
