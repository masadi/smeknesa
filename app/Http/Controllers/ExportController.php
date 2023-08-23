<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rombongan_belajar;
use App\Exports\AbsensiSiswa;
use App\Exports\RekapRemedial;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportController extends Controller
{
    public function rekap_absensi_siswa(){
        $semester_id = request()->route('semester_id');
        $rombongan_belajar_id = request()->route('rombongan_belajar_id');
        $bulan = request()->route('bulan');
        $jenis = request()->route('jenis');
        $file = 'DOWNLOAD REKAP ABSENSI SISWA PERBULAN.xlsx';
        if($jenis == 2){
            $file = 'DOWNLOAD REKAP ABSENSI SISWA PERSEMESTER.xlsx';
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
}
