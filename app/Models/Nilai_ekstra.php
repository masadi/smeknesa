<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Nilai_ekstra extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'nilai_ekstra';
	protected $primaryKey = 'nilai_id';
	protected $guarded = [];
	public function anggota_rombel()
	{
		return $this->belongsTo(Anggota_rombel::class, 'anggota_rombel_id', 'anggota_rombel_id');
	}
	public function materi_ekstra()
	{
		return $this->belongsTo(Materi_ekstra::class, 'materi_id', 'materi_id');
	}
	public function rombongan_belajar()
	{
		return $this->belongsTo(Rombongan_belajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
}
