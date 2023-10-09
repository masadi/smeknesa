<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Catatan_projek extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'catatan_projek';
	protected $primaryKey = 'catatan_projek_id';
	protected $guarded = [];
	public function rencana_projek()
	{
		return $this->belongsTo(Rencana_projek::class, 'rencana_projek_id', 'rencana_projek_id');
	}
}
