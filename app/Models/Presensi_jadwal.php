<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Presensi_jadwal extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'presensi_jadwal';
	protected $primaryKey = 'presensi_jadwal_id';
	protected $guarded = [];
    
}
