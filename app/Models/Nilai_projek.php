<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Nilai_projek extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'nilai_projek';
	protected $primaryKey = 'nilai_projek_id';
	protected $guarded = [];
}
