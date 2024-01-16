<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\Peserta_didik;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;
use Carbon\Carbon;

class LeggerNilai implements FromView, ShouldAutoSize
{
    use Exportable;
    public function __construct($rombongan_belajar_id)
    {
        $this->rombongan_belajar_id = $rombongan_belajar_id;
    }
    public function view(): View
    {
        return view('exports.legger-nilai', [
            'pd' => Peserta_didik::withWhereHas('anggota_rombel', function ($query) {
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            })->orderBy('nama')->get(),
            'pembelajaran' => Pembelajaran::where('rombongan_belajar_id', $this->rombongan_belajar_id)->with([
                'nilai' => function($query){
                    $query->where('jenis_penilaian_id', 3);
                },
            ])->where(function($query){
                $query->whereNotNull('kelompok_id');
                //$query->whereNotNull('no_urut');
            })->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get(),
            'rombongan_belajar' => Rombongan_belajar::with(['semester'])->find($this->rombongan_belajar_id),
            'today' => Carbon::today()->translatedFormat('j F Y'),
        ]);
    }
}
