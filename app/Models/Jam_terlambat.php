<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Jam_terlambat extends Model
{
    use HasFactory;
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'jam_terlambat';
	protected $primaryKey = 'jam_terlambat_id';
	protected $guarded = [];
}
