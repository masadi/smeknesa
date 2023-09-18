<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Absensi_ekstra extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'absensi_ekstra';
	protected $primaryKey = 'absensi_id';
	protected $guarded = [];
}
