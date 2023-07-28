<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NilaiExport;
use App\Models\User;
use App\Models\Guru;
use App\Models\Anggota_rombel;
use App\Models\Kelompok;
use App\Models\Pembelajaran;
use PDF;

class DownloadController extends Controller
{
    public function pdf(){
        $data = [
            'foo' => 'bar'
        ];
        $pdf = PDF::loadView('document', $data);
        return $pdf->stream('document.pdf');
    }
    public function excel(){
        return (new FastExcel(User::whereRoleIs('administrator', periode_aktif())->get()))->download('file.xlsx');
    }
    public function nilai_cp(){
        //echo request()->route('pembelajaran_id');
        $data = Pembelajaran::find(request()->route('pembelajaran_id'));
        return Excel::download(new NilaiExport(request()->route('pembelajaran_id')), 'Nilai Mata Pelajaran '.$data->nama_mata_pelajaran.' Kelas '.$data->rombongan_belajar->nama.'.xlsx');
        //return FastExcel::data($data)->download('file.xlsx');
        //return (new FastExcel($data))->download('file.xlsx');
        //data($list)->
    }
    public function rapor(){
        $tanggal_semester = tanggal_semester();
        $anggota = Anggota_rombel::with(['rombongan_belajar.semester', 'peserta_didik', 'sekolah'])->withCount([
            'presensi as sakit' => function($query) use ($tanggal_semester){
                $query->where('absen', 'S');
                $query->whereBetween('tanggal', [$tanggal_semester['tanggal_mulai'], $tanggal_semester['tanggal_selesai']]);
            },
            'presensi as izin' => function($query) use ($tanggal_semester){
                $query->where('absen', 'I');
                $query->whereBetween('tanggal', [$tanggal_semester['tanggal_mulai'], $tanggal_semester['tanggal_selesai']]);
            },
            'presensi as alpa' => function($query) use ($tanggal_semester){
                $query->where('absen', 'A');
                $query->whereBetween('tanggal', [$tanggal_semester['tanggal_mulai'], $tanggal_semester['tanggal_selesai']]);
            },
        ])->find(request()->route('anggota_rombel_id'));
        $data = [
            'pd' => $anggota->peserta_didik,
            'rombel' => $anggota->rombongan_belajar,
            'sekolah' => $anggota->sekolah,
            'tanggal_rapor' => $tanggal_semester['tanggal_cetak'],
            'kepala_sekolah' => Guru::whereHas('pengguna', function($query){
                $query->whereRoleIs('kepsek', periode_aktif());
            })->first(),
            'kelompok' => Kelompok::withWhereHas('pembelajaran', function ($query) {
                $query->orderBy('no_urut');
                $query->withWhereHas('rombongan_belajar', function ($query) {
                    $query->whereHas('anggota_rombel', function($query){
                        $query->where('anggota_rombel_id', request()->route('anggota_rombel_id'));
                    });
                });
                $query->withAvg([
                    'nilai as rerata' => function($query){
                        $query->where('jenis_penilaian_id', '<>', 1);
                        $query->whereHas('pd', function($query){
                            $query->where('anggota_rombel.anggota_rombel_id', request()->route('anggota_rombel_id'));
                            $query->where('anggota_rombel.semester_id', semester_id());
                        });
                    },
                ], 'angka');
                $query->with([
                    'deskripsi_tercapai' => function($query){
                        $query->where('anggota_rombel_id', request()->route('anggota_rombel_id'));
                    },
                    'deskripsi_belum_tercapai' => function($query){
                        $query->where('anggota_rombel_id', request()->route('anggota_rombel_id'));
                    },
                ]);
            })->orderBy('kelompok_id')->get(),
        ];
        $pdf = PDF::loadView('cetak.rapor', $data);
        return $pdf->stream('document.pdf');
    }
    public function remedial(){
        $data = Anggota_rombel::with(['rombongan_belajar' => function($query){
            $query->with(['semester', 'jurusan_sp.kajur', 'pembelajaran' => function($query){
                $query->withWhereHas('nilai', function($query){
                    $query->where('jenis_penilaian_id', 2);
                    $query->where('angka', '<', 75);
                    $query->with(['tp']);
                    $query->where('anggota_rombel_id', request()->route('anggota_rombel_id'));
                });
            }]);
        }, 'peserta_didik', 'sekolah'])->find(request()->route('anggota_rombel_id'));
        $pdf = PDF::loadView('cetak.remedial', ['data' => $data]);
        return $pdf->stream('document.pdf');
    }
}
