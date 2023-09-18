<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi_ekstra;
use App\Models\Nilai_ekstra;
use App\Models\Absensi_ekstra;
use App\Models\Rombongan_belajar;

class EkstraController extends Controller
{
    public function index(){
        $data = Materi_ekstra::withWhereHas('rombongan_belajar', function($query){
            $query->where('tingkat', 0);
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisiMapel());
            $query->where('deskripsi', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    public function ekskul(){
        $data = [
            'data' => Rombongan_belajar::where(function($query){
                $query->where('tingkat', 0);
                $query->where('guru_id', request()->guru_id);
                $query->where('semester_id', request()->semester_id);
            })->get(),
        ];
        return response()->json($data);
    }
    public function add_materi_ekstra(){
        request()->validate(
            [
                'rombongan_belajar_id' => 'required',
                'materi_ekstra.*' => 'required',
            ],
            [
                'rombongan_belajar_id.required' => 'Kelas Ekstra tidak boleh kosong!',
                'materi_ekstra.*.required' => 'Materi Ekstra tidak boleh kosong!',
            ]
        );
        foreach(array_filter(json_decode(request()->materi_ekstra)) as $deskripsi){
            Materi_ekstra::create([
                'rombongan_belajar_id' => request()->rombongan_belajar_id,
                'deskripsi' => $deskripsi,
            ]);
        }
        $data = [
            'icon' => 'success',
            'text' => 'Data Materi Ekstra berhasil disimpan',
            'title' => 'Berhasil',
        ];
        return response()->json($data);
    }
    public function detil_materi(){
        $data = [
            'data' => Materi_ekstra::find(request()->id),
        ];
        return response()->json($data);
    }
    public function update_materi(){
        request()->validate(
            [
                'materi_ekstra' => 'required',
            ],
            [
                'materi_ekstra.required' => 'Materi Ekstra tidak boleh kosong!',
            ]
        );
        $find = Materi_ekstra::find(request()->materi_id);
        $find->deskripsi = request()->materi_ekstra;
        if($find->save()){
            $data = [
                'icon' => 'success',
                'text' => 'Data Materi Ekstra berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Data Materi Ekstra gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function hapus_materi(){
        $find = Materi_ekstra::find(request()->id);
        if($find->delete()){
            $data = [
                'icon' => 'success',
                'text' => 'Data Materi Ekstra berhasil dihapus',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Data Materi Ekstra gagal dihapus. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
}
