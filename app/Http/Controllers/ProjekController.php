<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Tema_projek;
use App\Models\Dimensi_projek;
use App\Models\Elemen_projek;
use App\Models\Rencana_projek;
use App\Models\Opsi_projek;
use App\Models\Elemen_rencana_projek;
use App\Models\Nilai_projek;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;

class ProjekController extends Controller
{
    public function index(){
        $data = Rencana_projek::withWhereHas('pembelajaran', function($query){
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
            $query->whereHas('mata_pelajaran', function($query){
                $query->where('jenis', 'P5');
            });
        })->with(['rombongan_belajar', 'elemen_rencana_projek' => function($query){
            $query->with(['dimensi_projek', 'elemen_projek.parent']);
        }, 'tema'])
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->orWhere('deskripsi', 'ILIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_rombel(){
        $data = Rombongan_belajar::whereHas('pembelajaran', function($query){
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
            $query->whereHas('mata_pelajaran', function($query){
                $query->where('jenis', 'P5');
            });
        })->where('tingkat', request()->tingkat)->orderBy('nama')->get();
        return response()->json($data);
    }
    public function get_mapel(){
        $data = Pembelajaran::whereHas('mata_pelajaran', function($query){
            $query->where('jenis', 'P5');
        })->where('rombongan_belajar_id', request()->rombongan_belajar_id)->orderBy('nama_mata_pelajaran')->get();
        return response()->json($data);
    }
    public function get_tema(){
        $data = Tema_projek::orderBy('id')->get();
        return response()->json($data);
    }
    public function get_elemen(){
        $data = Dimensi_projek::with(['elemen.sub_elemen'])->orderBy('id')->get();
        return response()->json($data);
    }
    public function simpan_projek(){
        request()->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'pembelajaran_id' => 'required',
                'tema_id' => 'required',
                'nama' => 'required',
                'deskripsi' => 'required',
                'no_urut' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!!',
                'pembelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong!!',
                'tema_id.required' => 'Tema tidak boleh kosong!!',
                'nama.required' => 'Nama Projek tidak boleh kosong!!',
                'deskripsi.required' => 'Deskripsi Projek tidak boleh kosong!!',
                'no_urut.required' => 'Nomor Urut Projek tidak boleh kosong!!',
            ],
        );
        $rencana = Rencana_projek::create([
            'sekolah_id' => request()->sekolah_id,
            'rombongan_belajar_id' => request()->rombongan_belajar_id,
            'pembelajaran_id' => request()->pembelajaran_id,
            'tema_id' => request()->tema_id,
            'nama' => request()->nama,
            'deskripsi' => request()->deskripsi,
            'no_urut' => request()->no_urut,
        ]);
        $insert = 0;
        foreach(request()->sub_elemen as $sub_elemen){
            $insert++;
            $segments = Str::of($sub_elemen)->split('/[\s#]+/');
            Elemen_rencana_projek::create([
                'sekolah_id' => request()->sekolah_id,
                'rencana_projek_id' => $rencana->rencana_projek_id,
                'dimensi_id' => $segments->first(),
                'elemen_id' => $segments->last(),
            ]);
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Rencana Projek berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana Projek gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function update_projek(){
        request()->validate(
            [
                'nama' => 'required',
                'deskripsi' => 'required',
                'no_urut' => 'required',
            ],
            [
                'nama.required' => 'Nama Projek tidak boleh kosong!!',
                'deskripsi.required' => 'Deskripsi Projek tidak boleh kosong!!',
                'no_urut.required' => 'Nomor Urut Projek tidak boleh kosong!!',
            ],
        );
        $find = Rencana_projek::find(request()->rencana_projek_id);
        $find->nama = request()->nama;
        $find->deskripsi = request()->deskripsi;
        $find->no_urut = request()->no_urut;
        if($find->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Rencana Projek berhasil diperbaharui',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana Projek gagal diperbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function hapus_rencana(){
        $find = Rencana_projek::find(request()->id);
        if($find->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Rencana Projek berhasil dihapus',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana Projek gagal dihapus. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
}
