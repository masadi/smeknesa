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
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id(), 'data_bulan' => $this->bulan(), 'bulan' => (request()->bulan) ?? date('m')]);
    }
    public function rekap(){
        if(request()->data == 'pd'){
            $data = $this->rekap_pd();
        } else {
            $data = $this->rekap_guru();
        }
        return response()->json(['status' => 'success', 'data' => $data, 'tahun' => date('Y'), 'bulan' => (request()->bulan) ?? date('m'), 'semester_id' => semester_id(), 'data_bulan' => $this->bulan()]);
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
        if(request()->bulan){
            $month = date('Y').'-'.request()->bulan;
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
                if(request()->bulan){
                    $query->whereMonth('tanggal', request()->bulan);
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
        return Peserta_didik::whereHas('anggota_rombel', $this->kondisiAbsen())->withCount([
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
                    'absen' => $absen,
                    'user_id' => loggedUser()->user_id,
                ]
            );
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
                $query->whereMonth('tanggal', request()->bulan);
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
            $query->whereMonth('tanggal', request()->bulan);
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
            'bulan' => (request()->bulan) ?? date('m'),
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
            if(request()->bulan){
                $query->whereMonth('tanggal', request()->bulan);
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
        $data['bulan'] = (request()->bulan) ?? '';
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
        return response()->json($data);
    }
    public function pd(){
        $data = Rombongan_belajar::where('semester_id', semester_id())->orderBy('tingkat')->orderBy('nama')->withCount([
            'anggota_rombel',
            'anggota_rombel as hadir' => function($query){
                $query->whereHas('presensi', function ($query) {
                    $query->where('absen', 'H');
                    if(request()->bulan){
                        $query->whereMonth('tanggal', request()->bulan);
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
                    if(request()->bulan){
                        $query->whereMonth('tanggal', request()->bulan);
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
                    if(request()->bulan){
                        $query->whereMonth('tanggal', request()->bulan);
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
                    if(request()->bulan){
                        $query->whereMonth('tanggal', request()->bulan);
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
                    if(request()->bulan){
                        $query->whereMonth('tanggal', request()->bulan);
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
        return response()->json(['status' => 'success', 'data' => $data, 'data_bulan' => $this->bulan(), 'bulan' => (request()->bulan) ?? date('m'), 'data_tanggal' => $this->get_tanggal(), 'tanggal' => request()->tanggal]);
    }
    public function detil(){
        $data = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->withCount([
            'presensi as H' => function($query){
                $query->where('absen', 'H');
                $query->whereMonth('tanggal', request()->bulan);
            },
            'presensi as A' => function($query){
                $query->where('absen', 'A');
                $query->whereMonth('tanggal', request()->bulan);
            },
            'presensi as S' => function($query){
                $query->where('absen', 'S');
                $query->whereMonth('tanggal', request()->bulan);
            },
            'presensi as I' => function($query){
                $query->where('absen', 'I');
                $query->whereMonth('tanggal', request()->bulan);
            },
            'presensi as D' => function($query){
                $query->where('absen', 'D');
                $query->whereMonth('tanggal', request()->bulan);
            },
        ])->orderBy('nama')->get();
        return response()->json($data);
    }
}
