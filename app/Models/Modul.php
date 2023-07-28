<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Modul extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'modul';
	protected $primaryKey = 'modul_id';
	protected $guarded = [];
	public function guru()
    {
        return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
    }
    
    public function mapel(){
        return $this->hasManyThrough(
            Pembelajaran::class,
            Modul_mapel::class,
            'modul_id', // Foreign key on the cars table...
            'pembelajaran_id', // Foreign key on the owners table...
            'modul_id', // Local key on the mechanics table...
            'pembelajaran_id' // Local key on the cars table...
        );
    }
}
