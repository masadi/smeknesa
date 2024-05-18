<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket_ukk;
use App\Models\Unit_ukk;
use App\Models\Rencana_ukk;
use App\Models\Nilai_ukk;
use App\Models\Jurusan_sp;
use App\Models\Kurikulum;

class UkkController extends Controller
{
    public function index(){
        $data = Paket_ukk::withCount('unit_ukk')->withWhereHas('jurusan_sp', function($query){
            $query->where('guru_id', request()->user()->guru_id);
        })->with(['kurikulum'])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nomor_paket', 'ilike', '%'.request()->q.'%');
            $query->orWhere('nama_paket_id', 'ilike', '%'.request()->q.'%');
            $query->orWhere('nama_paket_en', 'ilike', '%'.request()->q.'%');
            $query->orWhereHas('jurusan_sp', function($query){
                $query->where('nama_jurusan_sp', 'ilike', '%'.request()->q.'%');
            });
            $query->where($this->kondisiModul());
        })
        ->paginate(request()->per_page);
        return response()->json([
            'status' => 'success', 
            'data' => $data, 
            'semester_id' => request()->semester_id,
            'user' => request()->user(),
        ]);
    }
    public function get_jurusan(){
        $data = Jurusan_sp::where(function($query){
            $query->where('guru_id', request()->user()->guru_id);
        })->orderBy('nama_jurusan_sp')->get();
        return response()->json($data);
    }
    public function get_kurikulum(){
        $data = Kurikulum::whereHas('rombongan_belajar', function($query){
            $query->where('jurusan_sp_id', request()->jurusan_sp_id);
        })->orderBy('nama')->get();
        return response()->json($data);
    }
    public function get_paket(){
        $data = Paket_ukk::with(['jurusan_sp', 'kurikulum', 'unit_ukk'])->find(request()->paket_ukk_id);
        return response()->json($data);
    }
    public function status_ukk(){
        $find = Paket_ukk::find(request()->paket_ukk_id);
        $find->status = (request()->status) ? 0 : 1;
        $text = (request()->status) ? 'nonaktifkan' : 'aktifkan';
        if($find->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Paket UKK berhasil di '.$text,
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Paket UKK gagal di '.$text.'. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function update(){
        $find = Paket_ukk::find(request()->paket_ukk_id);
        $find->nama_paket_id = request()->nama_paket_id;
        $find->nama_paket_en = request()->nama_paket_en;
        $find->nomor_paket = request()->nomor_paket;
        $find->status = request()->status;
        $find->save();
        if(request()->has('unit_ukk')){
            foreach(request()->unit_ukk as $item){
                Unit_ukk::where('unit_ukk_id', $item['unit_ukk_id'])->update([
                    'kode_unit' => $item['kode_unit'],
                    'nama_unit' => $item['nama_unit'],
                ]);
            }
        }
        return response()->json(['icon' => 'success', 'title' => 'Success', 'text' => "Data Paket UKK berhasil diperbaharui."]);
    }
    public function destroy()
    {
        if(request()->data == 'paket-ukk'){
            $find = Paket_ukk::find(request()->id);
            $text = 'Paket UKK';
        } else {
            $find = Unit_ukk::find(request()->id);
            $text = 'Unit UKK';
        }
        if($find ){
            $find->delete();
            return response()->json(['icon' => 'success', 'title' => 'Berhasil', 'text' => "$text berhasil dihapus."]);
        }else{
            return response()->json(['icon' => 'error', 'title' => 'Gagal', 'text' => "$text tidak ditemukan."]);
        }
    }
}
