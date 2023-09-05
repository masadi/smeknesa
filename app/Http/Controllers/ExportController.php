<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rombongan_belajar;
use App\Models\Nilai_ekskul;
use App\Exports\AbsensiSiswa;
use App\Exports\RekapRemedial;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

class ExportController extends Controller
{
    public function rekap_absensi_siswa(){
        $semester_id = request()->route('semester_id');
        $rombongan_belajar_id = request()->route('rombongan_belajar_id');
        $rombel = Rombongan_belajar::find($rombongan_belajar_id);
        $bulan = request()->route('bulan');
        $jenis = request()->route('jenis');
        $file = 'REKAP ABSENSI KELAS '.$rombel->nama.' PERBULAN.xlsx';
        if($jenis == 2){
            $file = 'REKAP ABSENSI KELAS '.$rombel->nama.' PERSEMESTER.xlsx';
        }
        return Excel::download(new AbsensiSiswa($semester_id, $rombongan_belajar_id, $bulan, $jenis), $file);
    }
    public function rekap_remedial(){
        $semester_id = request()->route('semester_id');
        return Excel::download(new RekapRemedial($semester_id), 'REKAPITULASI REMEDIAL.xlsx');
    }
    public function password_pd(){
        $rombel = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
        $users = User::whereHas('pd', function($query){
            $query->whereHas('kelas', function($query){
                $query->where('rombongan_belajar.rombongan_belajar_id', request()->route('rombongan_belajar_id'));
            });
        })->orderBy('name')->get();
        $list = [];
        foreach($users as $key => $user){
            $list[] = [
                'NO' => $key + 1,
                'NAMA SISWA' => $user->name,
                'EMAIL' => $user->email,
                'PASSWORD' => $user->status_password,
            ];
        }
        return (new FastExcel(collect($list)))->download('PASSWORD PD KELAS '.$rombel->nama.'.xlsx');
    }
    public function nilai_ekskul(){
        $rombel = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
        $ekskul = Rombongan_belajar::where(function($query) use ($rombel){
            $query->where('tingkat', 0);
            $query->where('semester_id', $rombel->semester_id);
        })->withWhereHas('pd', function($query) use ($rombel){
            $query->whereHas('kelas', function($query) use ($rombel){
                $query->where('rombongan_belajar.rombongan_belajar_id', $rombel->rombongan_belajar_id);
            });
        })->orderBy('nama')->get();
        /*
        $query->with(['anggota_ekskul' => function($query) use ($rombel){
            $query->where('semester_id', $rombel->semester_id);
            $query->with(['nilai_ekskul']);
        }]);
        */
        $collection = [];
        foreach($ekskul as $eks){
            foreach($eks->pd as $pd){
                $nilai_ekskul = Nilai_ekskul::where('peserta_didik_id', $pd->peserta_didik_id)->where('rombongan_belajar_id', $eks->rombongan_belajar_id)->first();
                $collection[$eks->nama][] = [
                    'Ekskul_ID' => $eks->rombongan_belajar_id,
                    'PD_ID' => $pd->peserta_didik_id,
                    'nama' => $pd->nama,
                    'nisn' => $pd->nisn,
                    'ekskul' => $eks->nama,
                    'nilai' => ($nilai_ekskul) ? $nilai_ekskul->nilai : '',
                    'deskripsi' => ($nilai_ekskul) ? $nilai_ekskul->deskripsi : '',
                ];
            }
        }
        $sheets = new SheetCollection($collection);
        return (new FastExcel($sheets))->download('Nilai Ekstrakurikuler Kelas '.$rombel->nama.'.xlsx');
    }
}
