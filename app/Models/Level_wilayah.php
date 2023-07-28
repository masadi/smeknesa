<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level_wilayah extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'ref.level_wilayah';
	protected $primaryKey = 'id_level_wilayah';
    protected $guarded = [];
}
