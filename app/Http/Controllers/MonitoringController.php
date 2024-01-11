<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta_didik;
use App\Models\Pembelajaran;

class MonitoringController extends Controller
{
    public function legger(){
        $data = [
            'pd' => Peserta_didik::withWhereHas('kelas', function ($query) {
                $query->where('rombongan_belajar.rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
            })->withWhereHas('anggota_rombel', function ($query) {
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                    $query->where('semester_id', request()->semester_id);
                });
            })->orderBy('nama')->get(),
            'pembelajaran' => Pembelajaran::withWhereHas('rombongan_belajar', function($query){
                $query->where('semester_id', request()->semester_id);
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->with([
                'nilai' => function($query){
                    $query->where('jenis_penilaian_id', 3);
                },
            ])->where(function($query){
                $query->whereNotNull('kelompok_id');
                //$query->whereNotNull('no_urut');
            })->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get(),
        ];
        return response()->json($data);
    }
}
