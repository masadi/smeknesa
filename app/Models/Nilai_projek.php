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
	public function elemen_rencana_projek()
    {
        return $this->belongsTo(Elemen_rencana_projek::class, 'elemen_rencana_id', 'elemen_rencana_id');
    }
    public function opsi_projek()
    {
        return $this->belongsTo(Opsi_projek::class, 'opsi_id', 'id');
    }
    public function elemen_projek()
    {
        return $this->belongsTo(Elemen_projek::class, 'elemen_id', 'id');
    }
    public function dimensi_projek()
    {
        return $this->belongsTo(Dimensi_projek::class, 'dimensi_id', 'id');
    }
}
