<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Guru;

class RekapController extends Controller
{
    public function remedial(){
        $data = Rombongan_belajar::with(['wali_kelas'])->withCount([
            'pd as jml_remedial' => function($query){
                $query->whereHas('nilai', function($query){
                    $query->where('angka', 75);
                });
            },
        ])->where(function($query){
            $query->where('semester_id', request()->semester_id);
            $query->whereHas('pd', function($query){
                $query->whereHas('nilai', function($query){
                    $query->where('angka', 75);
                });
            });
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function remed(){
        $data = Peserta_didik::withWhereHas('nilai', function($query){
            //$query->where('jenis_penilaian_id', 2);
            $query->whereIn('jenis_penilaian_id', [2, 3]);
            //$query->where('angka', '<', 75);
            $query->with(['pembelajaran']);
            $query->join('pembelajaran', function ($join) {
                $join->on('nilai.pembelajaran_id', '=', 'pembelajaran.pembelajaran_id');
                $join->on('nilai.angka', '<', 'pembelajaran.kktp');
            });
        })->where(function($query){
            $query->whereHas('kelas', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
            });
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function absen_guru(){
        $data = Guru::withCount([
            'presensi as alpha' => function($query){
                $query->has('presensi_jadwal');
                $query->where('absen', 'A');
                //$query->whereMonth('fecha', '=', '06');
            },
            'presensi as izin' => function($query){
                $query->has('presensi_jadwal');
                $query->where('absen', 'I');
            },
            'presensi as sakit' => function($query){
                $query->has('presensi_jadwal');
                $query->where('absen', 'S');
            },
        ])->where($this->kondisi())->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisi());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    private function kondisi(){
        return function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        };
    }
}
