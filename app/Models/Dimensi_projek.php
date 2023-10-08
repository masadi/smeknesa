<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimensi_projek extends Model
{
    use HasFactory;
    protected $table = 'dimensi_projek';
	protected $guarded = [];
    public function elemen()
    {
        return $this->hasMany(Elemen_projek::class, 'dimensi_id', 'id')->whereNull('elemen_id');
    }
}
