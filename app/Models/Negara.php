<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negara extends Model
{
    use HasFactory;
    public $incrementing = false;
    public $keyType = 'string';
    protected $table = 'ref.negara';
	protected $primaryKey = 'negara_id';
    protected $guarded = [];
}
