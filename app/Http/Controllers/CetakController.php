<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta_didik;
use PDF;

class CetakController extends Controller
{
    public function rapor_cover()
    {
        $data = [
            'foo' => 'bar'
        ];
        $pdf = PDF::loadView('cetak.document', $data);
        $pdf->getMpdf()->defaultfooterfontsize=7;
		$pdf->getMpdf()->defaultfooterline=1;
		$pdf->getMpdf()->SetFooter('Nama Siswa - Nama Kelas |{PAGENO}|Dicetak dari '.config('app.name').' v.'.get_setting('app_version'));
        return $pdf->stream('document.pdf');
    }
    public function rapor_semester(){
        return $this->rapor_cover();
    }
    public function rapor_p5(){
        return $this->rapor_cover();
    }
    public function rapor_pelengkap(){
        return $this->rapor_cover();
    }
    public function rekap_remedial(){
        $data = [
            'pd' => Peserta_didik::with(['kelas' => function($query){
                $query->with(['wali_kelas', 'jurusan_sp', 'semester', 'pembelajaran' => function($query){
                    $query->with(['nilai' => function($query){
                        $query->where('jenis_penilaian_id', 2);
                        $query->where('angka', '<', 75);
                        $query->with('tp');
                        $query->whereHas('pd', function($query){
                            $query->where('peserta_didik.peserta_didik_id', request()->route('peserta_didik_id'));
                        });
                    }]);
                }]);
            }])->find(request()->route('peserta_didik_id')),
        ];
        $pdf = PDF::loadView('cetak.rekap-remedial', $data);
        $pdf->getMpdf()->defaultfooterfontsize=7;
		$pdf->getMpdf()->defaultfooterline=1;
		$pdf->getMpdf()->SetFooter('{PAGENO} ||Dicetak dari '.config('app.name').' v.'.get_setting('app_version'));
        return $pdf->stream('document.pdf');
    }
}
