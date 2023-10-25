<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Absensi_pkl extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'absensi_pkl';
	protected $primaryKey = 'absensi_pkl_id';
	protected $guarded = [];
	
	public function pkl()
	{
		return $this->belongsTo(Praktik_kerja_lapangan::class, 'pkl_id', 'pkl_id');
	}
}
