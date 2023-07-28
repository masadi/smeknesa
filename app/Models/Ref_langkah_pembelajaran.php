<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ref_langkah_pembelajaran extends Model
{
    use HasFactory;
    protected $table = 'ref.langkah_pembelajaran';
	protected $guarded = [];
    
    public function sub()
    {
        return $this->hasMany(Ref_langkah_pembelajaran::class, 'langkah_id');
    }
}
