<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Peserta_didik;
use App\Models\Anggota_rombel;
use App\Models\Guru;
use App\Models\Jurusan_sp;
use App\Models\Rombongan_belajar;
use App\Models\Sekolah;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PresensiController extends Controller
{
    public function index(){
        if(request()->data == 'pd'){
            $data = $this->absensi_pd();
        } else {
            $data = $this->absensi_guru();
        }
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id(), 'data_bulan' => $this->bulan(), 'bulan' => ($this->get_bulan()) ?? date('m')]);
    }
    public function rekap(){
        if(request()->data == 'pd'){
            $data = $this->rekap_pd();
        } else {
            $data = $this->rekap_guru();
        }
        return response()->json(['status' => 'success', 'data' => $data, 'tahun' => date('Y'), 'bulan' => ($this->get_bulan()) ?? date('m'), 'semester_id' => semester_id(), 'data_bulan' => $this->bulan()]);
    }
    private function bulan(){
        $period = now()->startOfMonth()->subMonths(11)->monthsUntil(now());
        $month = collect($period->map(function (Carbon $date){
            $bulan = [
                'angka' => $date->format('m'),
                'huruf' => $date->translatedFormat('F'),
            ];
            return $bulan;
        }));
        $sorted = $month->sortBy([
            ['angka', 'asc']
        ]);
        return $sorted->values()->all();
    }
    private function get_tanggal(){
        if($this->get_bulan()){
            $month = date('Y').'-'.$this->get_bulan();
            $startDate = Carbon::parse($month)->startOfMonth();
            $endDate = Carbon::parse($month)->endOfMonth();
        } else {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }
        $period = CarbonPeriod::create($startDate, $endDate);
        //$period = CarbonPeriod::create(now()->startOfMonth(), now()->endOfMonth());
        foreach($period as $date)
        {
        $dates[] = $date->format('d-m-Y');
        }
        return $dates;
    }
    private function kondisiAbsen(){
        return function($query){
            $query->whereHas('presensi', function ($query) {
                if($this->get_bulan()){
                    $query->whereMonth('tanggal', $this->get_bulan());
                } else {
                    $query->whereMonth('tanggal', Carbon::today()->format('m'));
                }
                if(request()->tanggal){
                    $query->whereDate('tanggal', request()->tanggal);
                }
            });
            if(request()->data == 'pd'){
                if(loggedUser()->hasRole('pd', periode_aktif())){
                    $query->where('peserta_didik_id', loggedUser()->peserta_didik_id);
                } else {
                    if(request()->tingkat){
                        $query->whereHas('rombongan_belajar', function($query){
                            $query->where('tingkat', request()->tingkat);
                        });
                    }
                    if(request()->rombongan_belajar_id){
                        $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                    }
                }
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', semester_id());
                    if(loggedUser()->hasRole('walas', request()->periode_aktif)){
                        $query->where('guru_id', request()->guru_id);
                    }
                });
            } else {
                $query->whereHas('jadwal', function ($query) {
                    $query->where('jadwal.semester_id', semester_id());
                });
                if(!loggedUser()->hasRole(['administrator', 'piket', 'wakakur'], periode_aktif())){
                    $query->where('guru_id', loggedUser()->guru_id);
                }
            }
        };
    }
    private function absensi_pd(){
        $date = date('Y').'-'.$this->get_bulan().'-'.date('d');
        $firstOfMonth = Carbon::create($date)->firstOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::create($date)->endOfMonth()->format('Y-m-d');
        $period = CarbonPeriod::between($firstOfMonth, $endOfMonth)->addFilter(function ($date) {
            return $date->isSunday() || $date->isSaturday();
        });
        $hari_libur = [];
        foreach ($period as $date) {
            $hari_libur[] = $date->format('Y-m-d');
        }
        return Peserta_didik::whereHas('anggota_rombel', $this->kondisiAbsen())->withCount([
            'presensi as H' => function($query) use ($hari_libur){
                $query->where('absen', 'H');
                $query->whereNotIn('tanggal', $hari_libur);
                if($this->get_bulan()){
                    $query->whereMonth('tanggal', $this->get_bulan());
                } else {
                    $query->whereMonth('tanggal', Carbon::today()->format('m'));
                }
                if(request()->tanggal){
                    $query->whereDate('tanggal', request()->tanggal);
                }
            },
            'presensi as A' => function($query) use ($hari_libur){
                $query->where('absen', 'A');
                $query->whereNotIn('tanggal', $hari_libur);
                if($this->get_bulan()){
                    $query->whereMonth('tanggal', $this->get_bulan());
                } else {
                    $query->whereMonth('tanggal', Carbon::today()->format('m'));
                }
                if(request()->tanggal){
                    $query->whereDate('tanggal', request()->tanggal);
                }
            },
            'presensi as S' => function($query) use ($hari_libur){
                $query->where('absen', 'S');
                $query->whereNotIn('tanggal', $hari_libur);
                if($this->get_bulan()){
                    $query->whereMonth('tanggal', $this->get_bulan());
                } else {
                    $query->whereMonth('tanggal', Carbon::today()->format('m'));
                }
                if(request()->tanggal){
                    $query->whereDate('tanggal', request()->tanggal);
                }
            },
            'presensi as I' => function($query) use ($hari_libur){
                $query->where('absen', 'I');
                $query->whereNotIn('tanggal', $hari_libur);
                if($this->get_bulan()){
                    $query->whereMonth('tanggal', $this->get_bulan());
                } else {
                    $query->whereMonth('tanggal', Carbon::today()->format('m'));
                }
                if(request()->tanggal){
                    $query->whereDate('tanggal', request()->tanggal);
                }
            },
            'presensi as D' => function($query) use ($hari_libur){
                $query->where('absen', 'D');
                $query->whereNotIn('tanggal', $hari_libur);
                if($this->get_bulan()){
                    $query->whereMonth('tanggal', $this->get_bulan());
                } else {
                    $query->whereMonth('tanggal', Carbon::today()->format('m'));
                }
                if(request()->tanggal){
                    $query->whereDate('tanggal', request()->tanggal);
                }
            },
        ])->withWhereHas('kelas', function($query){
            $query->where('rombongan_belajar.semester_id', semester_id());
            if(loggedUser()->hasRole('walas', request()->periode_aktif)){
                $query->where('guru_id', request()->guru_id);
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisiAbsen());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })->paginate(request()->per_page);
        return Anggota_rombel::where($this->kondisiAbsen())->withCount([
            'presensi as H' => function($query){
                $query->where('absen', 'H');
            },
            'presensi as A' => function($query){
                $query->where('absen', 'A');
            },
            'presensi as S' => function($query){
                $query->where('absen', 'S');
            },
            'presensi as I' => function($query){
                $query->where('absen', 'I');
            },
            'presensi as D' => function($query){
                $query->where('absen', 'D');
            },
        ])->with(['kelas' => function($query){
            $query->where('rombongan_belajar.semester_id', semester_id());
        }])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisiAbsen());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })->paginate(request()->per_page);
    }
    private function absensi_guru(){
        return Guru::where($this->kondisiAbsen())->withCount([
            'presensi as H' => function($query){
                $query->where('absen', 'H');
            },
            'presensi as A' => function($query){
                $query->where('absen', 'A');
            },
            'presensi as S' => function($query){
                $query->where('absen', 'S');
            },
            'presensi as I' => function($query){
                $query->where('absen', 'I');
            },
            'presensi as D' => function($query){
                $query->where('absen', 'D');
            },
        ])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisiAbsen());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })->paginate(request()->per_page);
    }
    public function get_hari(){
        $tanggal = Carbon::now();
        $data = [
            'tanggal' => $tanggal->format('Y-m-d'),
            'tanggal_str' => $tanggal->translatedFormat('l, j F Y'),
            'jumlah_jam' => ($tanggal->dayOfWeek == Carbon::FRIDAY) ? 6 : 11
        ];
        return response()->json($data);
    }
    public function simpan(){
        if(request()->aksi == 'pd'){
            foreach(request()->anggota_rombel_id as $key => $anggota_rombel_id){
                foreach(request()->jam[$key] as $jam){
                    Presensi::updateOrCreate(
                        [
                            'anggota_rombel_id' => $anggota_rombel_id,
                            'guru_id' => NULL,
                            'tanggal' => request()->tanggal,
                            'jam' => $jam,
                        ],
                        [
                            'absen' => request()->absensi[$key],
                            'user_id' => loggedUser()->user_id,
                        ]
                    );
                }
            }
        } else {
            foreach(request()->absensi as $request => $absen){
                $collection = Str::of($request)->explode('#');
                $anggota_rombel_id = NULL;
                $guru_id = NULL;
                if(request()->aksi == 'pd'){
                    $anggota_rombel_id = $collection[0];
                } else {
                    $guru_id = $collection[0];
                }
                $jam = $collection[1];
                Presensi::updateOrCreate(
                    [
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'guru_id' => $guru_id,
                        'tanggal' => request()->tanggal,
                        'jam' => $jam,
                    ],
                    [
                        'absen' => ($absen) ?? 'H',
                        'user_id' => loggedUser()->user_id,
                    ]
                );
            }
        }
        $aksi = (request()->aksi == 'pd') ? 'Siswa' : 'Guru';
        $data = [
            'icon' => 'success',
            'text' => 'Presensi '.$aksi.' berhasil disimpan',
            'title' => 'Berhasil',
        ];
        return response()->json($data);
    }
    private function rekap_pd(){
        return Peserta_didik::where($this->kondisiAbsen())->withCount([
            'presensi as H' => function($query){
                $query->where('absen', 'H');
            },
            'presensi as A' => function($query){
                $query->where('absen', 'A');
            },
            'presensi as S' => function($query){
                $query->where('absen', 'S');
            },
            'presensi as I' => function($query){
                $query->where('absen', 'I');
            },
            'presensi as D' => function($query){
                $query->where('absen', 'D');
            },
        ])->with([
            'kelas' => function($query){
                $query->where('rombongan_belajar.semester_id', semester_id());
            },
            'presensi' => function($query){
                $query->where('absen', '<>', 'H');
                $query->whereMonth('tanggal', $this->get_bulan());
            },
        ])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisiAbsen());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })->paginate(request()->per_page);
    }
    private function rekap_guru(){
        return Guru::where($this->kondisiAbsen())->withCount([
            'presensi as A' => function($query){
                $query->where('absen', 'A');
            },
            'presensi as S' => function($query){
                $query->where('absen', 'S');
            },
            'presensi as I' => function($query){
                $query->where('absen', 'I');
            },
            'presensi as D' => function($query){
                $query->where('absen', 'D');
            },
        ])->with(['presensi' => function($query){
            $query->where('absen', '<>', 'H');
            $query->whereMonth('tanggal', $this->get_bulan());
        }])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisiAbsen());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })->paginate(request()->per_page);
    }
    public function dashboard(){
        $data_bulan = array_merge([
            [
                'angka' => '',
                'huruf' => 'Semua'
            ]
            ], 
            $this->bulan());
        $data = [
            'jurusan' => Jurusan_sp::whereHas('rombongan_belajar', function($query){
                $query->where('semester_id', semester_id());
            })->orderBy('nama_jurusan_sp')->get(),
            'data_bulan' => $data_bulan,
            'bulan' => ($this->get_bulan()) ?? date('m'),
        ];
        return response()->json($data);
    }
    public function get_kelas(){
        $data = Rombongan_belajar::where('jurusan_sp_id', request()->jurusan_sp_id)->where('semester_id', semester_id())->orderBy('tingkat')->orderBy('nama')->get();
        return response()->json($data);
    }
    private function kondisiRekap($absen = NULL){
        return function($query) use ($absen){
            if(request()->rombongan_belajar_id){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                });
            } elseif(request()->jurusan_sp_id){
                $query->whereHas('anggota_rombel', function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('jurusan_sp_id', request()->jurusan_sp_id);
                    });
                });
            } else {
                $query->whereHas('anggota_rombel', function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('semester_id', semester_id());
                    });
                });
            }
            if($this->get_bulan()){
                $query->whereMonth('tanggal', $this->get_bulan());
            }
            if($absen){
                $query->where('absen', $absen);
            }
        };
    }
    public function get_rekap(){
        $data = [];
        $data_bulan = array_merge([
            [
                'angka' => '',
                'huruf' => 'Semua'
            ]
            ], 
            $this->bulan());
        if(request()->jurusan_sp_id){
            if(!request()->rombongan_belajar_id){
                $data['kelas'] = Rombongan_belajar::where('jurusan_sp_id', request()->jurusan_sp_id)->where('semester_id', semester_id())->orderBy('tingkat')->orderBy('nama')->get();
            }
        } else {
            $data['jurusan'] = Jurusan_sp::whereHas('rombongan_belajar', function($query){
                $query->where('semester_id', semester_id());
            })->orderBy('nama_jurusan_sp')->get();
        }
        $sekolah = Sekolah::select('sekolah_id')->withCount([
            'presensi_pd as Alpha' => function($query){
                $query->whereNotNull('anggota_rombel.anggota_rombel_id');
                $query->where($this->kondisiRekap('A'));
            },
            'presensi_pd as Sakit' => function($query){
                $query->whereNotNull('anggota_rombel.anggota_rombel_id');
                $query->where($this->kondisiRekap('S'));
            },
            'presensi_pd as Izin' => function($query){
                $query->whereNotNull('anggota_rombel.anggota_rombel_id');
                $query->where($this->kondisiRekap('I'));
            },
            'presensi_pd as Dispen' => function($query){
                $query->whereNotNull('anggota_rombel.anggota_rombel_id');
                $query->where($this->kondisiRekap('D'));
            },
            'presensi_pd as Total' => function($query){
                $query->whereNotNull('anggota_rombel.anggota_rombel_id');
                $query->where($this->kondisiRekap());
            },
        ])->first();
        $data['data_bulan'] = $data_bulan;
        $data['bulan'] = ($this->get_bulan()) ?? '';
        $data['presensi'] = [
            [
              'avatarColor' => 'error',
              'avatarIcon' => 'tabler-ban',
              'title' => 'Alpha',
              'count' => $sekolah->Alpha,
              'stats' => persen($sekolah->Izin, $sekolah->Total).'%',
              'statsColor' => 'success',
            ],
            [
              'avatarColor' => 'warning',
              'avatarIcon' => 'tabler-ban',
              'title' => 'Sakit',
              'count' => $sekolah->Sakit,
              'stats' => persen($sekolah->Sakit, $sekolah->Total).'%',
              'statsColor' => 'success',
            ],
            [
              'avatarColor' => 'success',
              'avatarIcon' => 'tabler-ban',
              'title' => 'Izin',
              'count' => $sekolah->Izin,
              'stats' => persen($sekolah->Izin, $sekolah->Total).'%',
              'statsColor' => 'success',
            ],
            [
              'avatarColor' => 'primary',
              'avatarIcon' => 'tabler-ban',
              'title' => 'Dispen',
              'count' => $sekolah->Dispen,
              'stats' => persen($sekolah->Dispen, $sekolah->Total).'%',
              'statsColor' => 'success',
            ],
        ];
        $data['series'] = [$sekolah->Alpha, $sekolah->Izin, $sekolah->Dispen, $sekolah->Sakit];
        $data['labels'] = ['Alpha', 'Izin', 'Dispen', 'Sakit'];
        return response()->json($data);
    }
    public function pd(){
        $data = Rombongan_belajar::where('semester_id', semester_id())->orderBy('tingkat')->orderBy('nama')->withCount([
            'anggota_rombel',
            'anggota_rombel as hadir' => function($query){
                $query->whereHas('presensi', function ($query) {
                    $query->where('absen', 'H');
                    if($this->get_bulan()){
                        $query->whereMonth('tanggal', $this->get_bulan());
                    } else {
                        $query->whereMonth('tanggal', Carbon::today()->format('m'));
                    }
                    if(request()->tanggal){
                        $query->whereDate('tanggal', request()->tanggal);
                    }
                });
            },
            'anggota_rombel as alpa' => function($query){
                $query->whereHas('presensi', function ($query) {
                    $query->where('absen', 'A');
                    if($this->get_bulan()){
                        $query->whereMonth('tanggal', $this->get_bulan());
                    } else {
                        $query->whereMonth('tanggal', Carbon::today()->format('m'));
                    }
                    if(request()->tanggal){
                        $query->whereDate('tanggal', request()->tanggal);
                    }
                });
            },
            'anggota_rombel as sakit' => function($query){
                $query->whereHas('presensi', function ($query) {
                    $query->where('absen', 'S');
                    if($this->get_bulan()){
                        $query->whereMonth('tanggal', $this->get_bulan());
                    } else {
                        $query->whereMonth('tanggal', Carbon::today()->format('m'));
                    }
                    if(request()->tanggal){
                        $query->whereDate('tanggal', request()->tanggal);
                    }
                });
            },
            'anggota_rombel as izin' => function($query){
                $query->whereHas('presensi', function ($query) {
                    $query->where('absen', 'I');
                    if($this->get_bulan()){
                        $query->whereMonth('tanggal', $this->get_bulan());
                    } else {
                        $query->whereMonth('tanggal', Carbon::today()->format('m'));
                    }
                    if(request()->tanggal){
                        $query->whereDate('tanggal', request()->tanggal);
                    }
                });
            },
            'anggota_rombel as dd' => function($query){
                $query->whereHas('presensi', function ($query) {
                    $query->where('absen', 'D');
                    if($this->get_bulan()){
                        $query->whereMonth('tanggal', $this->get_bulan());
                    } else {
                        $query->whereMonth('tanggal', Carbon::today()->format('m'));
                    }
                    if(request()->tanggal){
                        $query->whereDate('tanggal', request()->tanggal);
                    }
                });
            },
        ])->with(['wali_kelas' => function($query){
            $query->select('nama', 'guru_id');
        }])->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'data_bulan' => $this->bulan(), 'bulan' => ($this->get_bulan()) ?? date('m'), 'data_tanggal' => $this->get_tanggal(), 'tanggal' => request()->tanggal]);
    }
    public function detil(){
        if(request()->peserta_didik_id){
            $tanggal = Carbon::now();
            if(request()->tanggal){
                $tanggal = Carbon::createFromTimeStamp(request()->tanggal);
            }
            /*$start =  Carbon::createFromFormat('Y-m-d', $tanggal->format('Y-m-d'))->firstOfMonth()->format('Y-m-d');
            $end = Carbon::createFromFormat('Y-m-d', $tanggal->format('Y-m-d'))->endOfMonth()->format('Y-m-d');
            $period = CarbonPeriod::create($start, $end);
            $data_tanggal = [];
            foreach ($period as $date) {
                $data_tanggal[] = [
                    'date' => $date->format('Y-m-d'),
                    'str' => $date->translatedFormat('l, j F Y'),
                ];
            }*/
            $date = date('Y').'-'.$this->get_bulan().'-'.date('d');
            $firstOfMonth = Carbon::create($date)->firstOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::create($date)->endOfMonth()->format('Y-m-d');
            $period = CarbonPeriod::between($firstOfMonth, $endOfMonth)->addFilter(function ($date) {
                return $date->isMonday() || $date->isTuesday() || $date->isWednesday() || $date->isThursday() || $date->isFriday();
            });
            $data_tanggal = [];
            foreach ($period as $date) {
                $data_tanggal[] = [
                    'date' => $date->format('Y-m-d'),
                    'str' => $date->translatedFormat('l, j F Y'),
                ];
            }
            $data = [
                'pd' => Peserta_didik::with(['presensi' => function($query){
                    $query->where('semester_id', semester_id());
                    $query->orderBy('tanggal');
                    $query->whereMonth('tanggal', Str::padLeft($this->get_bulan(), 2, 0));
                }])->find(request()->peserta_didik_id),
                'data_bulan' => $this->bulan(),
                'data_tanggal' => $data_tanggal,
                'data_jam' => ($tanggal->dayOfWeek == Carbon::FRIDAY) ? 6 : 11,
            ];
        } else {
            $data = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->withCount([
                'presensi as H' => function($query){
                    $query->where('absen', 'H');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
                'presensi as A' => function($query){
                    $query->where('absen', 'A');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
                'presensi as S' => function($query){
                    $query->where('absen', 'S');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
                'presensi as I' => function($query){
                    $query->where('absen', 'I');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
                'presensi as D' => function($query){
                    $query->where('absen', 'D');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
            ])->orderBy('nama')->get();
        }
        return response()->json($data);
    }
    private function get_bulan(){
        return Str::padLeft(request()->bulan, 2, 0);
    }
}
