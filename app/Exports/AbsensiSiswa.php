<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Rombongan_belajar;
use App\Models\Semester;
use App\Models\Peserta_didik;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AbsensiSiswa implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;
    public function __construct($semester_id, $rombongan_belajar_id, $bulan, $jenis)
    {
        $this->semester_id = $semester_id;
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $this->bulan = $bulan;
        $this->jenis = $jenis;
    }
    /*public function registerEvents(): array
    {
        $semester = Semester::find($this->semester_id);
        if($this->jenis == 1){
            $date = date('Y').'-'.$this->bulan.'-'.date('d');
            $bulan = Carbon::create($date);
            $firstOfMonth = Carbon::create($date)->firstOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::create($date)->endOfMonth()->format('Y-m-d');
            $period = CarbonPeriod::between($firstOfMonth, $endOfMonth);
            $plus = 6;
        } else {
            $period = CarbonPeriod::create($semester->tanggal_mulai, '1 month', $semester->tanggal_selesai);
            $plus = 5;
        }
        $totalValue = (count($period) * 4) + 7;
        $alphabet = num2alpha($totalValue - 1);
        $pd = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with(['presensi' => function($query) use ($semester){
            $query->orderBy('tanggal');
            if($this->jenis == 1){
                $query->whereMonth('tanggal', $this->bulan);
            } else {
                $query->whereBetween('tanggal', [$semester->tanggal_mulai, $semester->tanggal_selesai]);
            }
        }])->count();
        $totalRow = $pd + $plus;
        $cellRange      = 'A1:'.$alphabet.$totalRow;
        return [
            BeforeSheet::class    => function(BeforeSheet $event) use($cellRange) {
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ])->getAlignment()->setWrapText(true);
            },
        ];
    }*/
    public function styles(Worksheet $sheet)
    {
        $semester = Semester::find($this->semester_id);
        if($this->jenis == 1){
            $date = date('Y').'-'.$this->bulan.'-'.date('d');
            $bulan = Carbon::create($date);
            $firstOfMonth = Carbon::create($date)->firstOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::create($date)->endOfMonth()->format('Y-m-d');
            $period = CarbonPeriod::between($firstOfMonth, $endOfMonth);
            $plus = 6;
        } else {
            $period = CarbonPeriod::create($semester->tanggal_mulai, '1 month', $semester->tanggal_selesai);
            $plus = 5;
        }
        $totalValue = (count($period) * 4) + 7;
        $alphabet = num2alpha($totalValue - 1);
        $pd = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with(['presensi' => function($query) use ($semester){
            $query->orderBy('tanggal');
            if($this->jenis == 1){
                $query->whereMonth('tanggal', $this->bulan);
            } else {
                $query->whereBetween('tanggal', [$semester->tanggal_mulai, $semester->tanggal_selesai]);
            }
        }])->count();
        $totalRow = $pd + $plus;
        $cellRange      = 'A1:'.$alphabet.$totalRow;
        //$sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle($cellRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ])->getAlignment()->setWrapText(true);
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
        }
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
            'today' => Carbon::today()->translatedFormat('j F Y'),
        ]);
    }
}
