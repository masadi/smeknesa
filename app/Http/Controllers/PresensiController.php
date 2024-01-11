<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Presensi_jadwal;
use App\Models\Peserta_didik;
use App\Models\Anggota_rombel;
use App\Models\Guru;
use App\Models\Jurusan_sp;
use App\Models\Rombongan_belajar;
use App\Models\Sekolah;
use App\Models\Ijin;
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
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => request()->semester_id, 'data_bulan' => $this->bulan(), 'bulan' => ($this->get_bulan()) ?? date('m')]);
    }
    public function rekap(){
        if(request()->data == 'pd'){
            $data = $this->rekap_pd();
        } else {
            $data = $this->rekap_guru();
        }
        return response()->json(['status' => 'success', 'data' => $data, 'tahun' => date('Y'), 'bulan' => ($this->get_bulan()) ?? date('m'), 'semester_id' => request()->semester_id, 'data_bulan' => $this->bulan()]);
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
        foreach($period as $date){
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
                if(!loggedUser()->hasRole(['wakakur', 'wakasiswa'], request()->periode_aktif)){
                    if(loggedUser()->hasRole('walas', request()->periode_aktif)){
                        $query->whereHas('rombongan_belajar', function($query){
                            $query->where('semester_id', request()->semester_id);
                            $query->where('guru_id', request()->guru_id);
                        });
                    }
                }
                if(loggedUser()->hasRole(['bk'], request()->periode_aktif)){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->whereHas('kelas_bk', function($query){
                            $query->where('semester_id', request()->semester_id);
                            $query->where('guru_id', request()->tingkat);
                        });
                    });
                }
            } else {
                /*$query->whereHas('jadwal', function ($query) {
                    $query->where('jadwal.semester_id', request()->semester_id);
                });
                if(!loggedUser()->hasRole(['administrator', 'piket', 'wakakur'], periode_aktif())){
                    $query->where('guru_id', loggedUser()->guru_id);
                }*/
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
            $query->where('rombongan_belajar.semester_id', request()->semester_id);
            if(loggedUser()->hasRole('walas', request()->periode_aktif) && !loggedUser()->hasRole(['wakakur', 'wakasiswa'], request()->periode_aktif)){
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
            $query->where('rombongan_belajar.semester_id', request()->semester_id);
        }])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisiAbsen());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })->paginate(request()->per_page);
    }
    private function absensi_guru(){
        return Guru::where($this->kondisiAbsen())->withCount([
            'presensi as alpa' => function($query){
                $query->where('absen', 'A');
                $query->whereHas('presensi_jadwal', function($query){
                    $query->where('semester_id', request()->semester_id);
                });
            },
            'presensi as sakit' => function($query){
                $query->where('absen', 'S');
                $query->whereHas('presensi_jadwal', function($query){
                    $query->where('semester_id', request()->semester_id);
                });
            },
            'presensi as izin' => function($query){
                $query->where('absen', 'I');
                $query->whereHas('presensi_jadwal', function($query){
                    $query->where('semester_id', request()->semester_id);
                });
            },
        ])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisiAbsen());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })->paginate(request()->per_page);
    }
    public function get_hari(){
        $tanggal = Carbon::now();
        if(request()->tanggal){
            $tanggal = Carbon::createFromDate(request()->tanggal);
        }
        $data = [
            'tanggal' => $tanggal->format('Y-m-d'),
            'tanggal_str' => $tanggal->translatedFormat('l, j F Y'),
            'jumlah_jam' => ($tanggal->dayOfWeek == Carbon::FRIDAY) ? 6 : 11
        ];
        return response()->json($data);
    }
    public function hapus(){
        if(request()->tanggal){
            if(request()->rombongan_belajar_id){
                Presensi::whereHas('anggota_rombel', function($query){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                })->where('tanggal', request()->tanggal)->delete();
            } 
            if(request()->anggota_rombel_id) {
                Presensi::where('anggota_rombel_id', request()->anggota_rombel_id)->where('tanggal', request()->tanggal)->delete();
            }
            if(request()->guru_id) {
                Presensi::where('guru_id', request()->guru_id)->where('tanggal', request()->tanggal)->delete();
            }
            $data = [
                'icon' => 'success',
                'text' => 'Presensi berhasil dihapus',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Presensi gagal dihapus. Tanggal tidak boleh kosong!',
                'title' => 'Gagal',
            ];
        }
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
            foreach(request()->guru_id as $key => $guru_id){
                foreach(request()->jam[$key] as $jam){
                    $presensi = Presensi::updateOrCreate(
                        [
                            'anggota_rombel_id' => NULL,
                            'guru_id' => $guru_id,
                            'tanggal' => request()->tanggal,
                            'jam' => $jam,
                        ],
                        [
                            'absen' => request()->absensi[$key],
                            'user_id' => loggedUser()->user_id,
                        ]
                    );
                    foreach(request()->jadwal_id as $jadwal_id){
                        Presensi_jadwal::updateOrCreate([
                            'presensi_id' => $presensi->presensi_id,
                            'jadwal_id' => $jadwal_id,
                        ]);
                    }
                }
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
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
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
                $query->where('semester_id', request()->semester_id);
            })->orderBy('nama_jurusan_sp')->get(),
            'data_bulan' => $data_bulan,
            'bulan' => ($this->get_bulan()) ?? date('m'),
        ];
        return response()->json($data);
    }
    public function get_kelas(){
        $data = Rombongan_belajar::where('jurusan_sp_id', request()->jurusan_sp_id)->where('semester_id', request()->semester_id)->orderBy('tingkat')->orderBy('nama')->get();
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
                        $query->where('semester_id', request()->semester_id);
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
    public function get_rekap_guru(){
        $data_jurusan = Jurusan_sp::withWhereHas('pembelajaran', function($query){
            $query->where('pembelajaran.semester_id', request()->semester_id);
            $query->withWhereHas('guru', function($query){
                $query->withCount([
                    'presensi as alpa' => function($query){
                        $query->has('presensi_jadwal');
                        $query->where('absen', 'A');
                    },
                    'presensi as izin' => function($query){
                        $query->has('presensi_jadwal');
                        $query->where('absen', 'I');
                    },
                    'presensi as sakit' => function($query){
                        $query->has('presensi_jadwal');
                        $query->where('absen', 'S');
                    },
                ]);
            });
        })->orderBy('nama_jurusan_sp')->get();
        $alpha = 0;
        $izin = 0;
        $sakit = 0;
        $alpha_jurusan = [];
        $izin_jurusan = [];
        $sakit_jurusan = [];
        foreach($data_jurusan as $jurusan){
            $alpha = 0;
            $izin = 0;
            $sakit = 0;
            foreach($jurusan->pembelajaran as $pembelajaran){
                $alpha += $pembelajaran->guru->alpa;
                $izin += $pembelajaran->guru->izin;
                $sakit += $pembelajaran->guru->sakit;
            }
            $alpha_jurusan[] = $alpha;
            $izin_jurusan[] = $izin;
            $sakit_jurusan[] = $sakit;
        }
        $data = [
            'series' => [
                [
                'name' => 'ALPHA',
                'data' => $alpha_jurusan
                ], 
                [
                'name' => 'IZIN',
                'data' => $izin_jurusan
                ],
                [
                'name' => 'SAKIT',
                'data' => $sakit_jurusan
                ],
            ],
            'categories' => $data_jurusan->pluck('alias'),
            'data_jurusan' => $data_jurusan,
        ];
        return response()->json($data);
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
                $data['kelas'] = Rombongan_belajar::where('jurusan_sp_id', request()->jurusan_sp_id)->where('semester_id', request()->semester_id)->orderBy('tingkat')->orderBy('nama')->get();
            }
        } else {
            $data['jurusan'] = Jurusan_sp::whereHas('rombongan_belajar', function($query){
                $query->where('semester_id', request()->semester_id);
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
        $data = Rombongan_belajar::where('tingkat', '<>', 0)->where('semester_id', request()->semester_id)->orderBy('tingkat')->orderBy('nama')->withCount([
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
                $tanggal = Carbon::createFromDate(request()->tanggal);
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
                    $query->where('semester_id', request()->semester_id);
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
        if(request()->bulan){
            return Str::padLeft(request()->bulan, 2, 0);
        }
        return date('m');
    }
    public function get_rombel(){
        $tanggal = Carbon::now();
        if(request()->tanggal){
            $tanggal = Carbon::createFromDate(request()->tanggal);
        }
        $data = [
            'rombel' => Rombongan_belajar::where(function($query) use ($tanggal){
                $query->whereHas('jadwal', function($query) use ($tanggal){
                    $query->where('hari', $tanggal->translatedFormat('l'));
                });
                $query->where('semester_id', request()->semester_id);
            })->orderBy('nama')->get(),
            'tanggal' => $tanggal->format('Y-m-d'),
            'tanggal_str' => $tanggal->translatedFormat('l, j F Y'),
        ];
        return response()->json($data);
    }
    public function get_guru(){
        $tanggal = Carbon::createFromDate(request()->tanggal);
        /*
        Guru::orderBy('nama')->where(function($query) use ($tanggal) {
                $query->whereHas('jadwal', function($query) use ($tanggal){
                    $query->where('hari', $tanggal->translatedFormat('l'));
                    $query->where('jadwal.rombongan_belajar_id', request()->rombongan_belajar_id);
                });
            })->with([
                'presensi' => function($query) use ($tanggal){
                    $query->whereDate('tanggal', $tanggal);
                    $query->orderBy('jam');
                },
                'jadwal' => function($query) use ($tanggal){
                    $query->where('hari', $tanggal->translatedFormat('l'));
                    $query->with(['jam']);
                }
            ])->get()
        */
        $data = [
            'guru' => Guru::withWhereHas('jadwal', function($query) use ($tanggal){
                $query->where('hari', $tanggal->translatedFormat('l'));
                $query->where('jadwal.rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->withWhereHas('jam');
            })->with([
                'presensi' => function($query) use ($tanggal){
                    $query->whereDate('tanggal', $tanggal);
                    $query->orderBy('jam');
                }
            ])->orderBy('nama')->get(),
            'tanggal' => request()->tanggal,
            'hari' => $tanggal->translatedFormat('l'),
        ];
        return response()->json($data);
    }
    public function perijinan(){
        $data = Ijin::withWhereHas('pd', function($query){
            $query->where('semester_id', request()->semester_id);
            $query->with(['kelas' => function($query){
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
            }]);
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->orderBy('anggota_rombel_id')
        ->when(request()->q, function($query) {
            $query->whereHas('pd', function($query){
                $query->where('nama', 'ilike', '%'.request()->q.'%');
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_siswa(){
        $data = [
            'items' => Peserta_didik::withWhereHas('kelas', function($query){
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
            })->withWhereHas('anggota_rombel', function($query){
                $query->where('semester_id', request()->semester_id);
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('tingkat', '<>', 0);
                });
            })->when(request()->nama_siswa, function($query) {
                $query->where('nama', 'ilike', '%'.request()->nama_siswa.'%');
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function add_perijinan(){
        $validator = Validator::make(request()->all(), 
            [
                'anggota_rombel_id' => ['required'],
                'jam_ke' => ['required_if:jenis_ijin,jam'],
                'jenis_ijin' => ['required'],
                'pilihan_ijin' => ['required'],
                'tanggal_ijin' => ['required_if:jenis_ijin,jam'],
                'tanggal_mulai_ijin' => ['required_if:jenis_ijin,hari'],
                'tanggal_selesai_ijin' => ['required_if:jenis_ijin,hari'],
            ],
            [
                'anggota_rombel_id.required' => 'Siswa tidak boleh kosong',
                'jam_ke.required_if' => 'Jam tidak boleh kosong',
                'jenis_ijin.required' => 'Jenis Ijin tidak boleh kosong',
                'pilihan_ijin.required' => 'Pilihan Ijin tidak boleh kosong',
                'tanggal_ijin.required_if' => 'Tanggal Ijin tidak boleh kosong',
                'tanggal_mulai_ijin.required_if' => 'Tanggal Mulai Ijin tidak boleh kosong',
                'tanggal_selesai_ijin.required_if' => 'Tanggal Selesai Ijin tidak boleh kosong',
            ]
        );
 
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $insert = 0;
        $ijin = NULL;
        $period = [];
        if(request()->jenis_ijin == 'hari'){
            $startDate = Carbon::createFromDate(request()->tanggal_mulai_ijin);
            $endDate = Carbon::createFromDate(request()->tanggal_selesai_ijin);
            $period = CarbonPeriod::between($startDate, $endDate)->addFilter(function ($date) {
                return $date->isMonday() || $date->isTuesday() || $date->isWednesday() || $date->isThursday() || $date->isFriday();
            });
            if($period){
                $ijin = Ijin::updateOrCreate(
                    [
                        'anggota_rombel_id' => request()->anggota_rombel_id,
                        'guru_id' => request()->guru_id,
                        'tanggal_mulai' => request()->tanggal_mulai_ijin,
                        'tanggal_selesai' => request()->tanggal_selesai_ijin,
                        'jenis_ijin' => request()->jenis_ijin,
                        'pilihan_ijin' => request()->pilihan_ijin,
                    ],
                    [
                        'alasan' => request()->alasan,
                    ]
                );
                foreach($period as $date){
                    $insert++;
                    $data_jam = ($date->dayOfWeek == Carbon::FRIDAY) ? [1,2,3,4,5,6] : [1,2,3,4,5,6,7,8,9,10,11];
                    foreach($data_jam as $jam){
                        Presensi::updateOrCreate(
                            [
                                'anggota_rombel_id' => request()->anggota_rombel_id,
                                'tanggal' => $date->format('Y-m-d'),
                                'jam' => $jam,
                            ],
                            [
                                'absen' => request()->pilihan_ijin,
                                'user_id' => request()->user_id,
                                'ijin_id' => $ijin->ijin_id,
                            ]
                        );
                    }
                }
            }
        } else {
            $ijin = Ijin::updateOrCreate(
                [
                    'anggota_rombel_id' => request()->anggota_rombel_id,
                    'guru_id' => request()->guru_id,
                    'tanggal_mulai' => request()->tanggal_ijin,
                    'tanggal_selesai' => NULL,
                    'jenis_ijin' => request()->jenis_ijin,
                    'pilihan_ijin' => request()->pilihan_ijin,
                ],
                [
                    'alasan' => request()->alasan,
                ]
            );
            foreach(request()->jam_ke as $jam){
                $insert++;
                Presensi::updateOrCreate(
                    [
                        'anggota_rombel_id' => request()->anggota_rombel_id,
                        'tanggal' => request()->tanggal_ijin,
                        'jam' => $jam,
                    ],
                    [
                        'absen' => request()->pilihan_ijin,
                        'user_id' => request()->user_id,
                        'ijin_id' => $ijin->ijin_id,
                    ]
                );
            }
        }
        if($insert){
            $data = [
                'success' => TRUE,
                'icon' => 'success',
                'text' => 'Data Perijinan berhasil disimpan',
                'title' => 'Berhasil',
                'ijin' => $ijin,
            ];
        } else {
            $data = [
                'success' => FALSE,
                'icon' => 'error',
                'text' => 'Data Perijinan gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
                'period' => $period,
            ];
        }
        return response()->json($data);
    }
    public function get_jam(){
        $tanggal = Carbon::createFromDate(request()->tanggal_ijin);
        if($tanggal->dayOfWeek == Carbon::SATURDAY || $tanggal->dayOfWeek == Carbon::SUNDAY){
            $data = [];
        } else {
            $data = ($tanggal->dayOfWeek == Carbon::FRIDAY) ? [1,2,3,4,5,6] : [1,2,3,4,5,6,7,8,9,10,11];
        }
        return response()->json($data);
    }
}
