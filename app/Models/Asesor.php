<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Asesor extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'asesor';
	protected $primaryKey = 'asesor_id';
	protected $guarded = [];
    public function dudi()
    {
        return $this->belongsTo(Dudi::class, 'dudi_id', 'dudi_id');
    }
}
