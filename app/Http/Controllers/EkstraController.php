<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Materi_ekstra;
use App\Models\Nilai_ekstra;
use App\Models\Absensi_ekstra;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;

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
    /*
    Rombongan_belajar::where(function($query){
                $query->where('tingkat', 0);
                $query->where('semester_id', request()->semester_id);
            })->withWhereHas('pd', function($query){
                $query->withWhereHas('kelas', function($query){
                    $query->where('tingkat', '<>', 0);
                    $query->where('guru_id', request()->guru_id);
                    $query->where('rombongan_belajar.semester_id', request()->semester_id);
                });
            })->orderBy('nama')->get()
    */
    public function get_ektra(){
        $data = [
            'data' => Rombongan_belajar::where(function($query){
                $query->where('tingkat', 0);
                $query->where('semester_id', request()->semester_id);
                $query->where('guru_id', request()->guru_id);
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function get_siswa_ektra(){
        $data = [
            'data' => Peserta_didik::withWhereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->with([
                    'nilai_ekstra' => function($query){
                        $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                    },
                    'absensi_ekstra' => function($query){
                        $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                    },
                ]);
            })->withWhereHas('kelas', function($query){
                $query->where('tingkat', '<>', 0);
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
            })->orderBy('nama')->get(),
            'materi' => Materi_ekstra::where('rombongan_belajar_id', request()->rombongan_belajar_id)->orderBy('created_at')->get(),
        ];
        return response()->json($data);
    }
    public function add_nilai_ektra(){
        $insert = 0;
        foreach(request()->nilai as $uuid => $angka){
            $segments = Str::of($uuid)->split('/[\s#]+/');
            $anggota_rombel_id = $segments->first();
            $materi_id = $segments->last();
            if($angka){
                if(Nilai_ekstra::updateOrCreate(
                    [
                        'materi_id' => $materi_id,
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'rombongan_belajar_id' => request()->rombongan_belajar_id,
                    ],
                    [
                        'angka' => $angka,
                    ]
                )){
                    $insert++;
                }
            } else {
                Nilai_ekstra::where('materi_id', $materi_id)->where('anggota_rombel_id', $anggota_rombel_id)->where('rombongan_belajar_id', request()->rombongan_belajar_id)->delete();
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Nilai Ekstra berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Nilai Ekstra gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function add_absen_ektra(){
        $insert = 0;
        foreach(request()->alpa as $anggota_rombel_id => $alpa){
            $insert++;
            Absensi_ekstra::updateOrCreate(
                [
                    'anggota_rombel_id' => $anggota_rombel_id,
                    'rombongan_belajar_id' => request()->rombongan_belajar_id,
                ],
                [
                    'alpa' => $alpa??0,
                    'sakit' => request()->sakit[$anggota_rombel_id]??0,
                    'izin' => request()->izin[$anggota_rombel_id]??0,
                ],
            );
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Absensi Ekstra berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Absensi Ekstra gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
}
