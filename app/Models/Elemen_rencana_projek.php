<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Elemen_rencana_projek extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'elemen_rencana_projek';
	protected $primaryKey = 'elemen_rencana_id';
	protected $guarded = [];
    public function dimensi_projek()
    {
        return $this->belongsTo(Dimensi_projek::class, 'dimensi_id', 'id');
    }
    public function elemen_projek()
    {
        return $this->belongsTo(Elemen_projek::class, 'elemen_id', 'id');
    }
    public function rencana_projek()
    {
        return $this->belongsTo(Rencana_projek::class, 'rencana_projek_id', 'rencana_projek_id');
    }
    public function nilai_projek()
    {
        return $this->hasOne(Nilai_projek::class, 'elemen_rencana_id', 'elemen_rencana_id');
    }
}
