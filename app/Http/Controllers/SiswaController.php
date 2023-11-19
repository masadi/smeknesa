<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelajaran;
use App\Models\Semester;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;
use App\Models\Guru;

class SiswaController extends Controller
{
    public function index(){
        $data = NULL;
        if(request()->aksi == 'akademik'){
            $data = Pembelajaran::where(function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->whereHas('anggota_rombel', function($query){
                        $query->where('peserta_didik_id', request()->peserta_didik_id);
                        $query->where('semester_id', request()->semester_id);
                    });
                });
            })->withAvg([
                'all_nilai as nilai_formatif' => function($query){
                    $query->where('jenis_penilaian_id', 2);
                    $query->whereHas('pd', function($query){
                        $query->where('anggota_rombel.peserta_didik_id', request()->peserta_didik_id);
                        $query->where('semester_id', semester_id());
                    });
                },
                'all_nilai as nilai_sumatif' => function($query){
                    $query->where('jenis_penilaian_id', 3);
                    $query->whereHas('pd', function($query){
                        $query->where('anggota_rombel.peserta_didik_id', request()->peserta_didik_id);
                        $query->where('semester_id', semester_id());
                    });
                },
            ], 'angka')->orderBy('mata_pelajaran_id')->get();

        }
        if(request()->aksi == 'remedial'){
            $pembelajaran = Pembelajaran::where(function($query){
                $query->whereNotNull('kktp');
                $query->whereHas('rombongan_belajar', function($query){
                    $query->whereHas('anggota_rombel', function($query){
                        $query->where('peserta_didik_id', request()->peserta_didik_id);
                        $query->where('semester_id', request()->semester_id);
                    });
                });
                $query->whereHas('nilai', function($query){
                    $query->whereIn('jenis_penilaian_id', [2, 3]);
                    //$query->where('angka', '<', '75');
                    $query->wherehas('pd', function($query){
                        $query->where('anggota_rombel.peserta_didik_id', request()->peserta_didik_id);
                    });
                });
            })->join('nilai', function ($join) {
                $join->on('pembelajaran.pembelajaran_id', '=', 'nilai.pembelajaran_id');
                $join->on('nilai.angka', '<', 'pembelajaran.kktp');
            })->orderBy('no_urut')->get();
            $unique = $pembelajaran->unique('pembelajaran_id');
            $data = $unique->values()->all();
        }
        if(request()->aksi == 'presensi'){
            $semester = Semester::find(request()->semester_id);
            $data = Peserta_didik::withCount([
                'presensi as alpha' => function($query) use ($semester){
                    $query->where('absen', 'A');
                    $query->whereBetween('tanggal', [$semester->tanggal_mulai, $semester->tanggal_selesai]);
                },
                'presensi as izin' => function($query) use ($semester){
                    $query->where('absen', 'I');
                    $query->whereBetween('tanggal', [$semester->tanggal_mulai, $semester->tanggal_selesai]);
                },
                'presensi as sakit' => function($query) use ($semester){
                    $query->where('absen', 'S');
                    $query->whereBetween('tanggal', [$semester->tanggal_mulai, $semester->tanggal_selesai]);
                },
                'presensi as dispen' => function($query) use ($semester){
                    $query->where('absen', 'D');
                    $query->whereBetween('tanggal', [$semester->tanggal_mulai, $semester->tanggal_selesai]);
                },
            ])->find(request()->peserta_didik_id);
        }
        if(request()->aksi == 'sikap'){

        }
        if(request()->aksi == 'remedial'){

        }
        if(request()->aksi == 'ekskul'){

        }
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
    public function teman(){
        $data = Rombongan_belajar::where(function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->whereHas('peserta_didik', function($query){
                    $query->where('peserta_didik_id', request()->peserta_didik_id);
                });
                $query->where('semester_id', request()->semester_id);
            });
            //$query->where('peserta_didik_id', '<>', request()->peserta_didik_id);
        })->with(['pd' => function($query){
            $query->where('peserta_didik.peserta_didik_id', '<>', request()->peserta_didik_id);
            $query->orderBy('nama');
        }])->first();
        return response()->json(['list' => ($data) ? $data->pd : []]);
    }
    public function guru(){
        $data = Guru::withWhereHas('pembelajaran', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('peserta_didik_id', request()->peserta_didik_id);
                    $query->where('semester_id', request()->semester_id);
                });
            });
        })->orderBy('nama')->get();
        return response()->json($data);
    }
}
