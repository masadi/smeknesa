<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Modul_pembelajaran extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'modul_pembelajaran';
	protected $primaryKey = 'modul_pembelajaran_id';
	protected $guarded = [];
}
