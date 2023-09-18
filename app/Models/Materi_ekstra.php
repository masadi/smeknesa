<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Materi_ekstra extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'materi_ekstra';
	protected $primaryKey = 'materi_id';
	protected $guarded = [];
	
	public function rombongan_belajar()
	{
		return $this->belongsTo(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
}
