<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanAjar extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected function author(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => User::find($value)?->name,
        );
    }
    public function jurusan_sp()
    {
        return $this->belongsTo(Jurusan_sp::class, 'jurusan_sp_id', 'jurusan_sp_id');
    }
    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
    }
}
