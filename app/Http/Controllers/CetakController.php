<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
