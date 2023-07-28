<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis_penilaian extends Model
{
    use HasFactory;
    protected $table = 'jenis_penilaian';
	protected $guarded = [];
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'jenis_penilaian_id', 'id');
    }
    public function tp()
    {
        return $this->hasMany(Penilaian::class, 'jenis_penilaian_id', 'id');
    }
}
