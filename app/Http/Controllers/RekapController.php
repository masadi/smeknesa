<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;

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
            $query->where('jenis_penilaian_id', 2);
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
}
