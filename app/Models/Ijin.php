<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;

class Ijin extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'ijin';
	protected $primaryKey = 'ijin_id';
	protected $guarded = [];
	protected $appends = ['tanggal_ijin', 'pilihan', 'tanggal_cetak'];
    public function getTanggalIjinAttribute(){
        if($this->attributes['tanggal_selesai']){
            $startDate = Carbon::createFromDate($this->attributes['tanggal_mulai']);
            $endDate = Carbon::createFromDate($this->attributes['tanggal_selesai']);
            if($startDate->month == $endDate->month){
                return Carbon::parse($this->attributes['tanggal_mulai'])->translatedFormat('d'). ' s/d ' .Carbon::parse($this->attributes['tanggal_selesai'])->translatedFormat('d F Y');
            } else {
                return Carbon::parse($this->attributes['tanggal_mulai'])->translatedFormat('d F Y'). ' s/d ' .Carbon::parse($this->attributes['tanggal_selesai'])->translatedFormat('d F Y');
            }
        }
		return Carbon::parse($this->attributes['tanggal_mulai'])->translatedFormat('d F Y');
	}
    public function getTanggalCetakAttribute(){
        if($this->attributes['tanggal_selesai']){
            $startDate = Carbon::createFromDate($this->attributes['tanggal_mulai']);
            $endDate = Carbon::createFromDate($this->attributes['tanggal_selesai']);
            return Carbon::parse($this->attributes['tanggal_mulai'])->translatedFormat('l, d-m-y'). ' s/d ' .Carbon::parse($this->attributes['tanggal_selesai'])->translatedFormat('l, d-m-y');
        }
		return Carbon::parse($this->attributes['tanggal_mulai'])->translatedFormat('l, d-m-y');
	}
    public function getPilihanAttribute(){
        $data = [
            'S' => 'Sakit',
            'I' => 'Izin',
            'D' => 'Dispen',
        ];
        return $data[$this->pilihan_ijin];
    }
    public function pd(){
		return $this->hasOneThrough(
            Peserta_didik::class,
            Anggota_rombel::class,
            'anggota_rombel_id', // Foreign key on the environments table...
            'peserta_didik_id', // Foreign key on the deployments table...
            'anggota_rombel_id', // Local key on the projects table...
            'peserta_didik_id' // Local key on the environments table...
        );
	}
    public function presensi(){
        return $this->hasMany(Presensi::class, 'ijin_id', 'ijin_id');
    }
}
