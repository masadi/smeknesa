<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AbsensiSiswa;
use App\Exports\RekapRemedial;
use Maatwebsite\Excel\Facades\Excel;

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
}
