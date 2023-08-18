<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Rombongan_belajar;
use App\Models\Semester;
use App\Models\Peserta_didik;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AbsensiSiswa implements FromView, ShouldAutoSize
{
    use Exportable;
    public function __construct($semester_id, $rombongan_belajar_id, $bulan, $jenis)
    {
        $this->semester_id = $semester_id;
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $this->bulan = $bulan;
        $this->jenis = $jenis;
    }
    public function view(): View
    {
        $semester = Semester::find($this->semester_id);
        $rombel = Rombongan_belajar::find($this->rombongan_belajar_id);
        $pd = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with(['presensi' => function($query) use ($semester){
            $query->orderBy('tanggal');
            if($this->jenis == 1){
                $query->whereMonth('tanggal', $this->bulan);
            } else {
                $query->whereBetween('tanggal', [$semester->tanggal_mulai, $semester->tanggal_selesai]);
            }
        }])->orderBy('nama')->get();
        $bulan = NULL;
        if($this->jenis == 1){
            $date = date('Y').'-'.$this->bulan.'-'.date('d');
            $bulan = Carbon::create($date);
            $firstOfMonth = Carbon::create($date)->firstOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::create($date)->endOfMonth()->format('Y-m-d');
            $period = CarbonPeriod::between($firstOfMonth, $endOfMonth);
        } else {
            $period = CarbonPeriod::create($semester->tanggal_mulai, '1 month', $semester->tanggal_selesai);
            //foreach ($period as $dt) {
                //echo $dt->format("Y-m") . "<br>\n";
            //}
        }
        /*->addFilter(function ($date) {
            return $date->isMonday() || $date->isTuesday() || $date->isWednesday() || $date->isThursday() || $date->isFriday();
        });*/
        $file = 'rekap-absensi-siswa-perbulan';
        if($this->jenis == 2){
            $file = 'rekap-absensi-siswa-persemester';
        }
        return view('exports.'.$file, [
            'rombel' => $rombel,
            'semester' => $semester,
            'siswa' => $pd,
            'period' => $period,
            'presensi' => ['A', 'I', 'S', 'D'],
            'absensi' => ['ALPHA', 'IZIN', 'SAKIT', 'DISPEN'],
            'bulan' => $bulan,
            'hari' => count($period),
        ]);
    }
}
