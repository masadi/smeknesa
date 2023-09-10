<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use App\Models\Peserta_didik;
use App\Models\Ijin;
use App\Models\User;
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
                        //$query->where('angka', '<', 75);
                        $query->join('pembelajaran', function ($join) {
                            $join->on('nilai.pembelajaran_id', '=', 'pembelajaran.pembelajaran_id');
                            $join->on('nilai.angka', '<', 'pembelajaran.kktp');
                        });
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
    public function perijinan(){
        $user = User::find(request()->route('user_id'));
        $ijin = Ijin::with(['pd' => function($query){
            $query->withCount([
                'presensi as A' => function($query){
                    $query->where('absen', 'A');
                    $query->where('semester_id', semester_id());
                },
                'presensi as I' => function($query){
                    $query->where('absen', 'I');
                    $query->where('semester_id', semester_id());
                },
                'presensi as S' => function($query){
                    $query->where('absen', 'S');
                    $query->where('semester_id', semester_id());
                },
                'presensi as D' => function($query){
                    $query->where('absen', 'D');
                    $query->where('semester_id', semester_id());
                },
            ]);
            $query->with(['kelas' => function($query){
                $query->where('rombongan_belajar.semester_id', semester_id());
            }]);
        }])->with(['presensi'])->find(request()->route('ijin_id'));
        $data = [
            'user' => $user->name,
            'ijin' => $ijin,
            'qrcode' => base64_encode(QrCode::format('svg')->size(150)->errorCorrection('H')->generate($ijin->pd->nisn??'string')),
        ];
        if(request()->aksi == 'preview'){
            $pdf = PDF::loadView('cetak.perijinan', $data);
            $pdf->getMpdf()->defaultfooterfontsize=6;
            $pdf->getMpdf()->defaultfooterline=0;
            $pdf->getMpdf()->allow_charset_conversion=true;  // Set by default to TRUE
            $pdf->getMpdf()->charset_in='UTF-8';
            $pdf->getMpdf()->autoLangToFont = true;
            //$pdf->getMpdf()->SetFooter('|Surat ini dicetak dari akun: ---|');
            //$pdf->getMpdf()->SetFooter('Surat ini dicetak dari akun: '.auth()->user()->name);
            $output = 'perijikan_' . date('Y_m_d_H_i_s') . '.pdf';
            //$pdf->getMpdf()->Output("$output", 'F');
            $pdf->stream('document.pdf'); 
        } else {
            return view('cetak.cetak-perijinan', $data);
        }      
    }
}
