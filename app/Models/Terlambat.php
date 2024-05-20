<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;

class Terlambat extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'terlambat';
	protected $primaryKey = 'terlambat_id';
	protected $guarded = [];
	protected $appends = ['tanggal_terlambat'];
	public function getTanggalTerlambatAttribute(){
        return Carbon::parse($this->attributes['tanggal'])->translatedFormat('d F Y');
	}
	public function pd(){
		return $this->hasOneThrough(
            Peserta_didik::class,
            Anggota_rombel::class,
            'anggota_rombel_id', // Foreign key on the cars table...
            'peserta_didik_id', // Foreign key on the owners table...
            'anggota_rombel_id', // Local key on the mechanics table...
            'peserta_didik_id' // Local key on the cars table...
        );
	}
    public function jam_terlambat()
    {
        return $this->hasMany(Jam_terlambat::class, 'terlambat_id', 'terlambat_id');
    }
}
