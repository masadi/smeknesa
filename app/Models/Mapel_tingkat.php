<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class Mapel_tingkat extends Model
{
    use HasFactory, HasCompositeKey;
    public $incrementing = false;
    protected $table = 'mapel_tingkat';
    protected $primaryKey = ['mata_pelajaran_id', 'tingkat', 'jurusan_sp_id'];
    protected $guarded = [];
    public function jurusan_sp()
    {
        return $this->hasOne(Jurusan_sp::class, 'jurusan_sp_id', 'jurusan_sp_id');
    }
}
