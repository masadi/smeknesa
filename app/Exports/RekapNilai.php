<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class RekapNilai implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;
    public function __construct($pembelajaran_id, $rombongan_belajar_id, $guru_id, $data)
    {
        $this->pembelajaran_id = $pembelajaran_id;
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $this->guru_id = $guru_id;
        $this->data = $data;
    }
    public function styles(Worksheet $sheet)
    {
        $totalValue = $this->data['diagnosis']->count() + $this->data['data_tp']->count() + $this->data['sumatif']->count() + 5;
        $alphabet = num2alpha($totalValue - 1);
        $totalRow = $this->data['pd']->count() + 5;
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
        /*$data = Peserta_didik::with(['nilai' => function($query){
            $query->where('pembelajaran_id', $this->pembelajaran_id);
        }])->where(function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            });
        })->orderBy('nama')->get();
        $data_tp = Tujuan_pembelajaran::where(function($query){
            $query->whereHas('cp', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                });
                $query->where('guru_id', $this->guru_id);
            });
            $query->whereHas('nilai', function($query){
                $query->where('pembelajaran_id', $this->pembelajaran_id);
            });
        })->orderBy('created_at')->get();
        $diagnosis = Penilaian::whereHas('jenis_penilaian', function($query){
            $query->where('nama', 'Assesment Diagnosis');
        })->whereHas('pembelajaran', function($query){
            $query->where('pembelajaran_id', $this->pembelajaran_id);
        })->whereHas('nilai', function($query){
            $query->where('pembelajaran_id', $this->pembelajaran_id);
        })->orderBy('created_at')->get();
        $sumatif = Penilaian::whereHas('jenis_penilaian', function($query){
            $query->where('nama', 'Assesment Sumatif');
        })->whereHas('pembelajaran', function($query){
            $query->where('pembelajaran_id', $this->pembelajaran_id);
        })->whereHas('nilai', function($query){
            $query->where('pembelajaran_id', $this->pembelajaran_id);
        })->orderBy('created_at')->get();*/
        return view('exports.rekap-nilai', [
            'data_siswa' => $this->data['pd'],
            'data_tp' => $this->data['data_tp'],
            'data_diagnosis' => $this->data['diagnosis'],
            'data_sumatif' => $this->data['sumatif'],
            'nama_mata_pelajaran' => $this->data['nama_mata_pelajaran'],
            'nama' => $this->data['nama'],
            'semester' => $this->data['semester'],
            'today' => Carbon::today()->translatedFormat('j F Y'),
            'totalValue' => $this->data['diagnosis']->count() + $this->data['data_tp']->count() + $this->data['sumatif']->count() + 5,
        ]);
    }
}
