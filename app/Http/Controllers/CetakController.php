<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Anggota_rombel;
use App\Models\Semester;
use App\Models\Rencana_projek;
use App\Models\Opsi_projek;
use App\Models\Dimensi_projek;
use App\Models\Ijin;
use App\Models\User;
use Carbon\Carbon;
use PDF;

class CetakController extends Controller
{
    public function rapor_cover()
    {
        /*$data = [
            'foo' => 'bar'
        ];
        $pdf = PDF::loadView('cetak.document', $data);
        $pdf->getMpdf()->defaultfooterfontsize=7;
		$pdf->getMpdf()->defaultfooterline=1;
		$pdf->getMpdf()->SetFooter('Nama Siswa - Nama Kelas |{PAGENO}|Dicetak dari '.config('app.name').' v.'.get_setting('app_version'));
        return $pdf->stream('document.pdf');*/
        $get_siswa = Anggota_rombel::find(request()->route('anggota_rombel_id'));
        $semester = Semester::find($get_siswa->semester_id);
        $params = array(
            'get_siswa'	=> $get_siswa,
            'kepala_sekolah' => User::with('guru')->whereRoleIs('kepsek', $semester->nama)->first(),
        );
        $pdf = PDF::loadView('cetak.blank', $params, [], [
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);
        $pdf->getMpdf()->defaultfooterfontsize=7;
        $pdf->getMpdf()->defaultfooterline=0;
        $general_title = $get_siswa->peserta_didik->nama.' - '.$get_siswa->rombongan_belajar->nama;
        $pdf->getMpdf()->SetFooter($general_title.'|{PAGENO}|Dicetak dari '.config('app.name').' v.'.get_setting('app_version'));
        $rapor_top = view('cetak.rapor_top', $params);
        $identitas_sekolah = view('cetak.identitas_sekolah', $params);
        $identitas_peserta_didik = view('cetak.identitas_peserta_didik', $params);
        $pdf->getMpdf()->WriteHTML($rapor_top);
        $pdf->getMpdf()->WriteHTML($identitas_sekolah);
        $pdf->getMpdf()->WriteHTML('<pagebreak />');
        $pdf->getMpdf()->WriteHTML($identitas_peserta_didik);
        $general_title = clean($general_title);
        return $pdf->stream($general_title.'-IDENTITAS.pdf');
    }
    public function rapor_semester(){
        $semester = Semester::find(request()->route('semester_id'));
        $get_siswa = Anggota_rombel::with([
            'peserta_didik' => function($query) use ($semester){
                $query->with(['agama', 'pekerjaan_ayah', 'pekerjaan_ibu', 'sekolah' => function($query){
                    $query->with('kabupaten');
                }, 'presensi' => function($query) use ($semester){
                    $query->orderBy('tanggal');
                    $query->whereBetween('tanggal', [$semester->tanggal_mulai, $semester->tanggal_selesai]);
                }]);
            },
            'rombongan_belajar' => function($query){
                $query->with([
                    'pembelajaran' => function($query){
                        $callback = function($query){
                            $query->where('anggota_rombel_id', request()->route('anggota_rombel_id'));
                        };
                        $query->with([
                            'kelompok',
                            //'nilai' => $callback,
                            'deskripsi_tercapai' => $callback,
                            'deskripsi_belum_tercapai' => $callback,
                        ]);
                        $query->withAvg([
                            'nilai as rerata' => function($query){
                                $query->where('jenis_penilaian_id', '<>', 1);
                                $query->where('anggota_rombel_id', request()->route('anggota_rombel_id'));
                            },
                        ], 'angka');
                        $query->whereHas('mata_pelajaran', function($query){
                            $query->where('jenis', 'Umum');
                        });
                        $query->whereNotNull('kelompok_id');
                        $query->orderBy('kelompok_id', 'asc');
                        $query->orderBy('no_urut', 'asc');
                    },
                    'jurusan_sp',
                    'kurikulum',
                    'wali_kelas'
                ]);
            },
            'kenaikan_kelas', 
            'nilai_ekstra',
            'presensi',
        ])->find(request()->route('anggota_rombel_id'));
			$params = array(
				'get_siswa'	=> $get_siswa,
				'tanggal_rapor'	=> Carbon::parse($semester->tanggal_cetak)->translatedFormat('d F Y'),
				'kepala_sekolah' => User::with('guru')->whereRoleIs('kepsek', $semester->nama)->first(),
                'ekstrakurikuler' => Rombongan_belajar::where(function($query) use ($get_siswa){
                    $query->where('tingkat', 0);
                    $query->where('semester_id', request()->route('semester_id'));
                })->withWhereHas('anggota_rombel', function($query) use ($get_siswa){
                    $query->where('peserta_didik_id', $get_siswa->peserta_didik_id);
                })->with(['nilai_ekstra' => function($query) use ($get_siswa){
                    $query->with('materi_ekstra');
                    $query->whereHas('anggota_rombel', function($query) use ($get_siswa){
                        $query->where('peserta_didik_id', $get_siswa->peserta_didik_id);
                    });
                }])->orderBy('nama')->get(),
			);
			//return view('cetak.rapor_nilai', $params);
			//return view('cetak.rapor_catatan', $params);
			if(!$get_siswa->peserta_didik){
				return view('cetak.no_pd');
			}
			$pdf = PDF::loadView('cetak.blank', $params, [], [
				'format' => 'A4',
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 15,
				'margin_bottom' => 15,
				'margin_header' => 5,
				'margin_footer' => 5,
			]);
			$pdf->getMpdf()->defaultfooterfontsize=7;
			$pdf->getMpdf()->defaultfooterline=0;
			$general_title = $get_siswa->peserta_didik->nama.' - '.$get_siswa->rombongan_belajar->nama;
			$pdf->getMpdf()->SetFooter($general_title.'|{PAGENO}|Dicetak dari '.config('app.name').' v.'.get_setting('app_version'));
			$rapor_nilai = view('cetak.rapor_semester', $params);
			//dd($params);
			$pdf->getMpdf()->WriteHTML($rapor_nilai);
			/*if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Pusat') == false){
				$pdf->getMpdf()->WriteHTML('<pagebreak />');
				$rapor_catatan = view('cetak.rapor_catatan', $params);
				$pdf->getMpdf()->WriteHTML($rapor_catatan);
				$rapor_karakter = view('cetak.rapor_karakter', $params);
				$pdf->getMpdf()->WriteHTML('<pagebreak />');
				$pdf->getMpdf()->WriteHTML($rapor_karakter);
			}*/
			return $pdf->stream($general_title.'-NILAI.pdf');
    }
    public function rapor_projek(){
        $anggota_rombel_id = request()->route('anggota_rombel_id');
        $semester_id = request()->route('semester_id');
        $get_siswa = Anggota_rombel::with([
			'peserta_didik', 
			'nilai_projek',
			'rombongan_belajar.sekolah',
		])->find($anggota_rombel_id);
		$params = array(
			'semester' => Semester::find(request()->route('semester_id')),
			'get_siswa'	=> $get_siswa,
			'rencana_projek' => Rencana_projek::where('rombongan_belajar_id', $get_siswa->rombongan_belajar_id)
			->with([
				'elemen_rencana_projek' => function($query) use ($anggota_rombel_id){
					$query->with([
						'nilai_projek' => function($query) use ($anggota_rombel_id){
							$query->where('anggota_rombel_id', $anggota_rombel_id);
                            $query->with([
                                'opsi_projek', 
                                'elemen_projek.parent',
                                'dimensi_projek',
                            ]);
						},
					]);
				},
				'catatan_projek' => function($query) use ($anggota_rombel_id){
					$query->where('anggota_rombel_id', $anggota_rombel_id);
				},
			])->orderBy('no_urut')->get(),
			'opsi_projek' => Opsi_projek::orderBy('id')->get(),
			'dimensi_projek' => Dimensi_projek::orderBy('id')->get(),
		);
		$pdf = PDF::loadView('cetak.blank', $params, [], [
			'format' => 'A4',
			'margin_left' => 15,
			'margin_right' => 15,
			'margin_top' => 15,
			'margin_bottom' => 15,
			'margin_header' => 5,
			'margin_footer' => 5,
		]);
		$pdf->getMpdf()->defaultfooterfontsize=7;
		$pdf->getMpdf()->defaultfooterline=0;
		$general_title = strtoupper($get_siswa->peserta_didik->nama).' - '.$get_siswa->rombongan_belajar->nama;
		$pdf->getMpdf()->SetFooter($general_title.'|{PAGENO}|Dicetak dari '.config('app.name').' v.'.get_setting('app_version'));
		$rapor_p5bk = view('cetak.rapor_projek', $params);
		$pdf->getMpdf()->WriteHTML($rapor_p5bk);
		$pdf->getMpdf()->showImageErrors = true;
		return $pdf->stream($general_title.'-RAPOR-P5.pdf');
    }
    public function rapor_pelengkap(){
        return $this->rapor_cover();
    }
    public function rekap_remedial(){
        $data = [
            'pd' => Peserta_didik::with([
                'kelas' => function($query){
                    $query->with(['wali_kelas', 'jurusan_sp', 'semester', 'pembelajaran' => function($query){
                        $query->orderBy('no_urut');
                        $query->whereNotNull('kktp');
                        $query->withWhereHas('nilai', function($query){
                            $query->where('jenis_penilaian_id', 2);
                            //$query->where('angka', '<', 75);
                            //$query->whereIn('jenis_penilaian_id', [2, 3]);
                            $query->join('pembelajaran', function ($join) {
                                $join->on('nilai.pembelajaran_id', '=', 'pembelajaran.pembelajaran_id');
                                $join->on('nilai.angka', '<', 'pembelajaran.kktp');
                            });
                            $query->with('tp');
                            $query->whereHas('pd', function($query){
                                $query->where('peserta_didik.peserta_didik_id', request()->route('peserta_didik_id'));
                            });
                        });
                    }]);
                },
                'nilai' => function($query){
                    $query->where('jenis_penilaian_id', 2);
                    //$query->whereIn('jenis_penilaian_id', [2, 3]);
                    //$query->where('angka', '<', 75);
                    $query->with(['pembelajaran']);
                    $query->join('pembelajaran', function ($join) {
                        $join->on('nilai.pembelajaran_id', '=', 'pembelajaran.pembelajaran_id');
                        $join->on('nilai.angka', '<', 'pembelajaran.kktp');
                    });
                },
            ])->find(request()->route('peserta_didik_id')),
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
            'qrcode' => base64_encode(QrCode::format('svg')->size(150)->errorCorrection('H')->generate($ijin->pd->peserta_didik_id??'string')),
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
            $output = clean($ijin->pd->nama . ' - ' . $ijin->pd->kelas->nama) . '.pdf';
            //$pdf->getMpdf()->Output("$output", 'F');
            $pdf->stream($output); 
        } else {
            return view('cetak.cetak-perijinan', $data);
        }      
    }
    public function ekskul(){
        $params = [];
        /*$pdf = PDF::loadView('sertifikat.blank', $blank, [], [
            'orientation' => 'L',
            'default_font_size'    => '16',
            'custom_font_dir' => public_path('template-sertifikat/fonts/'), // don't forget the trailing slash!
            'custom_font_data' => $fonts,
            'default_font'         => 'myriadpro',
            'margin_top' => $sertifikat->margin_top,
            'margin_left' => $sertifikat->margin_left,
            'margin_right' => $sertifikat->margin_right,
            'format' => 'B4',
        ]);*/
        $fonts = [
            'chocolates' => [
                'R'  => 'chocolates-regular.ttf',    // regular font
                'B'  => 'chocolates-bold.ttf',       // optional: bold font
                'I'  => 'chocolates-italic.ttf',     // optional: italic font
                'BI' => 'chocolates-bold-italic.ttf' // optional: bold-italic font
                        ],
            'pinyonscript' => [
                'R'  => 'pinyonscript-regular.ttf',    // regular font
                'B'  => 'pinyonscript-regular.ttf',       // optional: bold font
                'I'  => 'pinyonscript-regular.ttf',     // optional: italic font
                'BI' => 'pinyonscript-regular.ttf' // optional: bold-italic font
            ],
        ];
        $pdf = PDF::loadView('cetak.blank', $params, [], [
			'format' => 'A4',
            'orientation' => 'L',
            'default_font_size'    => '12',
            'custom_font_dir' => public_path('fonts/'), // don't forget the trailing slash!
            'custom_font_data' => $fonts,
            'default_font'         => 'chocolates',
			'margin_left' => 15,
			'margin_right' => 15,
			'margin_top' => 15,
			'margin_bottom' => 15,
			'margin_header' => 5,
			'margin_footer' => 5,
		]);
        $pd = Peserta_didik::with([
            'ekskul' => function($query){
                $query->withWhereHas('materi_ekstra', function($query){
                    $query->with(['nilai_ekstra' => function($query){
                        $query->whereHas('anggota_rombel', function($query){
                            $query->where('peserta_didik_id', request()->route('peserta_didik_id'));
                        });
                    }]);
                });
                $query->withAvg([
                    'nilai_ekstra as rerata' => function($query){
                        $query->whereHas('anggota_rombel', function($query){
                            $query->where('peserta_didik_id', request()->route('peserta_didik_id'));
                        });
                    },
                ], 'angka');
                $query->with('wali_kelas');
                $query->where('rombongan_belajar.semester_id', semester_id());
            },
            'kelas' => function($query){
                $query->where('rombongan_belajar.semester_id', semester_id());
                $query->with('semester');
            }
        ])->find(request()->route('peserta_didik_id'));
        $semester = Semester::find($pd->kelas->semester_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(150)->errorCorrection('H')->generate($pd->peserta_didik_id??'string'));
        $kepala_sekolah = User::with('guru')->whereRoleIs('kepsek', $semester->nama)->first();
        $tanggal = Carbon::now()->translatedFormat('d F Y');
        $pagecount = $pdf->getMpdf()->setSourceFile('templates/ekskul.pdf');
        foreach($pd->ekskul as $index => $ekskul){
            if($index > 0){
                $pdf->getMpdf()->AddPage();
            }
            $tplIdx = $pdf->getMpdf()->importPage(1);
            $pdf->getMpdf()->useTemplate($tplIdx);
            $page_1 = view('cetak.page-1-ekskul', compact('pd', 'ekskul', 'qrcode', 'tanggal', 'kepala_sekolah'));
            $pdf->getMpdf()->WriteHTML($page_1);
            $pdf->getMpdf()->AddPage();
            $tplIdx = $pdf->getMpdf()->importPage(2);
            $pdf->getMpdf()->useTemplate($tplIdx);
            $page_2 = view('cetak.page-2-ekskul', compact('pd', 'ekskul', 'qrcode', 'tanggal', 'kepala_sekolah'));
            $pdf->getMpdf()->WriteHTML($page_2);
        }
        $title = 'nama siswa';
        $converted = Str::title('sertifikat ekstrakurikuler '.$title);
        return $pdf->stream($converted.'.pdf');
    }
    public function pkl(){
        $pd = Peserta_didik::with([
			'sekolah' => function($query){
				$query->select('sekolah_id', 'nama', 'kabupaten_id');
                $query->with(['kabupaten']);
			},
			'kelas' => function($query){
				$query->where('rombongan_belajar.semester_id', semester_id());
			},
			'pd_pkl' => function($query){
                $query->with(['praktik_kerja_lapangan']);
				/*$query->withWhereHas('praktik_kerja_lapangan', function($query){
					$query->where('pkl_id', request()->route('pkl_id'));
				});*/
			},
			'nilai_pkl' => function($query){
				$query->with(['tp']);
				$query->where('pkl_id', request()->route('pkl_id'));
			},
			'absensi_pkl' => function($query){
				$query->where('pkl_id', request()->route('pkl_id'));
			}
		])->find(request()->route('peserta_didik_id'));
        $semester = Semester::find(semester_id());
        $tanggal_rapor = Carbon::parse($semester->tanggal_cetak)->translatedFormat('d F Y');
        $data = [
        	'pd' => $pd,
            'tanggal_rapor' => $tanggal_rapor,
        ];
		$pdf = PDF::loadView('cetak.rapor-pkl', $data);
        $pdf->getMpdf()->defaultfooterfontsize=7;
		$pdf->getMpdf()->defaultfooterline=1;
		$pdf->getMpdf()->SetFooter($pd->nama.' - '. $pd->kelas->nama .' |{PAGENO}|Dicetak dari '.config('app.name').' v.'.get_setting('app_version'));
        $general_title = $pd->nama.' - '.$pd->kelas->nama;
        return $pdf->stream($general_title.'.pdf');
    }
}
