<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Tujuan_pembelajaran extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'tujuan_pembelajaran';
	protected $primaryKey = 'tp_id';
	protected $guarded = [];
	
	public function cp()
	{
		return $this->belongsTo(Capaian_pembelajaran::class, 'cp_id', 'cp_id');
	}
	public function nilai()
	{
		return $this->hasOne(Nilai::class, 'tp_id', 'tp_id');
	}
}
