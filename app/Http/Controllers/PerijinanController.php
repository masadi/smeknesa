<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta_didik;

class PerijinanController extends Controller
{
    public function index(){
        $data = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->where('semester_id', get_setting('semester_id'));
            $query->withWhereHas('rombongan_belajar', function($query){
                $query->where('tingkat', '<>', 0);
            });
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['data' => $data, 'semester_id' => get_setting('semester_id')]);
    }
    public function store(){
        $data = [
            'icon' => 'success',
            'text' => 'Perijinan berhasil disimpan',
            'title' => 'Berhasil',
        ];
        return response()->json($data);
    }
}
