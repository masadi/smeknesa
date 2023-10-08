<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elemen_projek extends Model
{
    use HasFactory;
    protected $table = 'elemen_projek';
	protected $guarded = [];
    
    public function sub_elemen()
    {
        return $this->hasMany(Elemen_projek::class, 'elemen_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(Elemen_projek::class, 'elemen_id', 'id');
    }
}
