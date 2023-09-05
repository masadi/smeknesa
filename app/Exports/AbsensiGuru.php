<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AbsensiGuru implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;
    public function __construct($data, $bulan, $nama)
    {
        $this->data = $data;
        $this->bulan = $bulan;
        $this->nama = $nama;
    }
    public function styles(Worksheet $sheet)
    {
        $period = $this->periode();
        $totalValue = (count($period) * 3) + 2;
        $alphabet = num2alpha($totalValue - 1);
        $totalRow = $this->data->count() + 3;
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
        $period = $this->periode();
        return view('exports.rekap-absensi-guru', [
            'data' => $this->data,
            'presensi' => ['A', 'I', 'S'],
            'hari' => count($period),
            'period' => $period,
            'nama' => $this->nama,
            'today' => Carbon::today()->translatedFormat('j F Y'),
        ]);
    }
    private function periode(){
        $date = date('Y').'-'.$this->bulan.'-'.date('d');
        $bulan = Carbon::create($date);
        $firstOfMonth = Carbon::create($date)->firstOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::create($date)->endOfMonth()->format('Y-m-d');
        $period = CarbonPeriod::between($firstOfMonth, $endOfMonth);
        return $period;
    }
}
