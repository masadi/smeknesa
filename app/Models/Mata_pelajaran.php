<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mata_pelajaran extends Model
{
    use HasFactory;
    protected $table = 'mata_pelajaran';
	protected $primaryKey = 'mata_pelajaran_id';
	protected $guarded = [];
	protected $casts = [
		'tingkat' => 'array',
	];
	public function pembelajaran()
	{
		return $this->hasOne(Pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	
	public function kompetensi_dasar()
	{
		return $this->hasMany(Kompetensi_dasar::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	} 
	public function mapel_tingkat()
	{
		return $this->hasMany(Mapel_tingkat::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	/*public function getTingkatAttribute(){
		return json_decode($this->attributes['tingkat']);
	}*/
}
