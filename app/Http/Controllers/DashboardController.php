<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Semester;
use App\Models\User;
use App\Models\Guru;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;
use App\Models\Jurusan_sp;
use App\Models\Pembelajaran;
use App\Models\Team;
use App\Models\Jam;
use App\Models\Whatsapp;
use App\Models\Anggota_rombel;
use App\Models\Notifikasi;
use Carbon\Carbon;
use File;

class DashboardController extends Controller
{
    public function index(){
        if($this->loggedUser()->hasRole(['administrator', 'wakakur', 'wakasiswa', 'bk'], periode_aktif())){
            $data = [
                'statistics' => [
                    [
                        'title' => 'Guru',
                        'stats' => rupiah(Guru::count()),
                        'icon' => 'user-check-icon',
                        'color' => 'info',
                    ],
                    [
                        'title' => 'Peserta Didik',
                        'stats' => rupiah(Peserta_didik::whereHas('kelas', function($query){
                            $query->where('rombongan_belajar.semester_id', request()->semester_id);
                        })->count()),
                        'icon' => 'users-icon',
                        'color' => 'primary',
                    ],
                    [
                        'title' => 'Kelas',
                        'stats' => rupiah(Rombongan_belajar::where(function($query){
                            $query->where('semester_id', request()->semester_id);
                        })->count()),
                        'icon' => 'building-icon',
                        'color' => 'danger',
                    ],
                    [
                        'title' => 'Mata Pelajaran',
                        'stats' => rupiah(Pembelajaran::whereHas('rombongan_belajar', function($query){
                            $query->where('semester_id', request()->semester_id);
                        })->count()),
                        'icon' => 'list-check-icon',
                        'color' => 'success',
                    ],
                ],
                'pembelajaran' => $this->get_pembelajaran('Umum'),
                'tambahan' => $this->get_pembelajaran(['P5', 'PKL']),
            ];
        } elseif($this->loggedUser()->hasRole('guru', periode_aktif())){
            $data = [
                'statistics' => [
                    [
                        'title' => 'Siswa (L)',
                        'stats' => rupiah(Peserta_didik::where('jenis_kelamin', 'L')->whereHas('kelas', function($query){
                            $query->where('rombongan_belajar.semester_id', request()->semester_id);
                            $query->whereHas('pembelajaran', function($query){
                                $query->where('guru_id', $this->loggedUser()->guru_id);
                            });
                        })->count()),
                        'icon' => 'man-icon',
                        'color' => 'info',
                    ],
                    [
                        'title' => 'Siswa (P)',
                        'stats' => rupiah(Peserta_didik::where('jenis_kelamin', 'P')->whereHas('kelas', function($query){
                            $query->where('rombongan_belajar.semester_id', request()->semester_id);
                            $query->whereHas('pembelajaran', function($query){
                                $query->where('guru_id', $this->loggedUser()->guru_id);
                            });
                        })->count()),
                        'icon' => 'woman-icon',
                        'color' => 'primary',
                    ],
                    [
                        'title' => 'Total Siswa',
                        'stats' => rupiah(Peserta_didik::whereHas('kelas', function($query){
                            $query->where('rombongan_belajar.semester_id', request()->semester_id);
                            $query->whereHas('pembelajaran', function($query){
                                $query->where('guru_id', $this->loggedUser()->guru_id);
                            });
                        })->count()),
                        'icon' => 'users-icon',
                        'color' => 'danger',
                    ],
                    [
                        'title' => 'Nilai Rendah',
                        'stats' => rupiah(Peserta_didik::whereHas('kelas', function($query){
                            $query->where('rombongan_belajar.semester_id', request()->semester_id);
                            $query->whereHas('pembelajaran', function($query){
                                $query->where('guru_id', $this->loggedUser()->guru_id);
                            });
                        })->whereHas('nilai', function($query){
                            $query->where('angka', '<', 70);
                            $query->whereHas('pembelajaran', function($query){
                                $query->where('semester_id', request()->semester_id);
                                $query->where('guru_id', loggedUser()->guru_id);
                            });
                        })->count()),
                        'icon' => 'checklist-icon',
                        'color' => 'success',
                    ],
                ],
                'pembelajaran' => $this->get_pembelajaran('Umum'),
                'tambahan' => $this->get_pembelajaran(['P5', 'PKL']),
            ];
        } else {
            $data = [
                'periode_aktif' => periode_aktif(),
                'statistics' => []
            ];
        }
        return response()->json($data);
    }
    private function get_pembelajaran($jenis){
        return Pembelajaran::withWhereHas('mata_pelajaran', function($query) use ($jenis){
            if(is_array($jenis)){
                $query->whereIn('jenis', $jenis);
            } else {
                $query->where('jenis', $jenis);
            }
        })->withWhereHas('rombongan_belajar', function($query){
            $query->where('sekolah_id', request()->sekolah_id);
            $query->where('semester_id', request()->semester_id);
            $query->with('wali_kelas');
            $query->withCount('anggota_rombel');
        })->where('guru_id', request()->guru_id)->orderBy('mata_pelajaran_id')->get();
    }
    public function aplikasi(){
        $data = [
            'aplikasi' => [
                [
                    'title' => 'Versi Aplikasi',
                    'color' => 'danger',
                    'stats' => get_setting('app_version'),
                    'icon' => 'terminal-icon',
                ],
                [
                    'title' => 'Versi Database',
                    'color' => 'success',
                    'stats' => get_setting('db_version'),
                    'icon' => 'database-icon',
                ],
            ],
            'tanggal_whatsapp' => ($this->loggedUser()->hasRole('pengajar', periode_aktif())) ? get_setting('tanggal_whatsapp') : NULL,
            'tanggal_sekarang' => now()->format('d'),
        ];
        return response()->json($data);
    }
    public function grafik(){
        $data = Jurusan_sp::whereHas('rombongan_belajar', function($query){
            $query->where('semester_id', request()->semester_id);
        })->withCount([
            'anggota_rombel as pria' => function($query){
                $query->where('anggota_rombel.semester_id', request()->semester_id);
                $query->whereHas('peserta_didik', function($query){
                    $query->where('jenis_kelamin', 'L');
                });
            },
            'anggota_rombel as wanita' => function($query){
                $query->where('anggota_rombel.semester_id', request()->semester_id);
                $query->whereHas('peserta_didik', function($query){
                    $query->where('jenis_kelamin', 'P');
                });
            },
            'anggota_rombel as kelas_10' => function($query){
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
                $query->where('tingkat', 10);
            },
            'anggota_rombel as kelas_11' => function($query){
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
                $query->where('tingkat', 11);
            },
            'anggota_rombel as kelas_12' => function($query){
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
                $query->where('tingkat', 12);
            },
        ])->get();
        return response()->json($data);
    }
    public function pengembangan(){
        $data = [
            'file' => nl2br(File::get(base_path('pengembangan.txt')))
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function debug(){
        $a = Jurusan::with(['parrentRecursive' => function($query){
            $query->with(['childsRecursive']);
        }, 'childsRecursive' => function($query){
            $query->with(['parrentRecursive']);
        }])->where('jurusan_id', '47012650')->first();
        dd($a);
        $user = User::where('email', request()->route('email'))->first();
        foreach($user->allPermissions(['description as action', 'name as subject'], '2022/2023 Ganjil') as $perm){
            dump($perm->subject);
        }
        $team = Team::where('name', '2022/2023 Ganjil')->first();
        $user->roles = $user->roles()->wherePivot(config('laratrust.foreign_keys.team'), $team->id)->get()->implode('display_name', ', ');
        dd($user->roles);
    }
    public function get_kelas(){
        $data = [
            'rombel' => Rombongan_belajar::where('jurusan_sp_id', request()->jurusan_sp_id)->where('semester_id', request()->semester_id)->orderBy('tingkat')->orderBy('nama')->get(),
            'series' => [
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
            ],
        ];
        return response()->json($data);
    }
    public function get_mapel(){
        $data = [
            'mapel' => Pembelajaran::where('rombongan_belajar_id', request()->rombongan_belajar_id)->orderBy('mata_pelajaran_id')->get(),
            'series' => [
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
            ],
        ];
        return response()->json($data);
    }
    public function get_nilai(){
        $data = [
            'series' => [
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
            ],
        ];
        return response()->json($data);
    }
    public function get_chart(){
        $data = [
            'jurusan' => Jurusan_sp::whereHas('rombongan_belajar', function($query){
                $query->where('semester_id', request()->semester_id);
            })->orderBy('nama_jurusan_sp')->get(),
            'series' => [
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
            ],
        ];
        return response()->json($data);
    }
    private function loggedUser(){
        return auth()->user();
    }
    public function kirim_wa(){
        $jenis = request()->route('jenis');
        if($jenis == 'absen'){
            Artisan::call('kirim:absen');
        }
        if($jenis == 'nilai'){
            Artisan::call('kirim:nilai');
        }
    }
    public function kirim_whatsapp(){
        $pembelajaran = Pembelajaran::with(['guru' => function($query){
            $query->select('guru_id', 'nama');
        }])->where(function($query){
            $query->where('semester_id', request()->semester_id);
            $query->where('guru_id', loggedUser()->guru_id);
        })->get();
        $insert = 0;
        $today = Carbon::now()->translatedFormat('l');
        $whatsapp = Whatsapp::where('jenis', 'nilai')->first();
        $url = 'http://api-wa.erapor-smk.net/chats/send?id=wahyu';
        if($whatsapp){
            foreach($pembelajaran as $mapel){
                $data = Peserta_didik::withWhereHas('nilai', function($query) use ($mapel){
                    $query->where('angka', '<', 75);
                    $query->where('pembelajaran_id', $mapel->pembelajaran_id);
                })->withWhereHas('user', function($query){
                    $query->whereNotNull('wa_wali');
                })->get();
                foreach($data as $pd){
                    $description = $whatsapp->description;
                    $description = str_replace('{nama_siswa}', $pd->nama, $description);
                    $description = str_replace('{nilai_mapel}', $pd->nilai->implode('angka', ', '), $description);
                    $description = str_replace('{nama_mapel}', $mapel->nama_mata_pelajaran, $description);
                    $description = str_replace('{guru_mapel}', $mapel->guru->nama, $description);
                    $data_post = [
                        'number' => $pd->user->wa_wali,
                        'message' => $description,
                    ];
                    $response = Http::withOptions([
                        'verify' => false,
                    ])->post($url, $data_post);
                    $status = $response->object();
                    if($status->status){
                        $insert++;
                    }
                }
            }
        }
        if($whatsapp){
            if($insert){
                $data = [
                    'success' => TRUE,
                    'errors' => NULL,
                    'icon' => 'success',
                    'text' => 'Pesan Whatsapp berhasil terkirim ke '.$insert.' Wali Murid',
                    'title' => 'Berhasil',
                ];
            } else {
                $data = [
                    'success' => TRUE,
                    'errors' => NULL,
                    'icon' => 'error',
                    'text' => 'Tidak ada Pesan Whatsapp terkirim. Aplikasi mendekteksi tidak ada Siswa yang memiliki nilai dibawah SKM!',
                    'title' => 'Gagal',
                ];
            }    
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Pesan Whatsapp belum di atur. Silahkan hubungi Administrator!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function no_access(){
        abort(403, 'Akses tidak sah!');
    }
    public function notifikasi(){
        $this->setNotifikasi();
        $data = Notifikasi::where(function($query){
            $query->where('user_id', loggedUser()->user_id);
            $query->where('terbaca', FALSE);
        })->get();
        return response()->json(['notifications' => $data, 'test' => 1]);
    }
    private function setNotifikasi(){
        $data = [];
        if($this->loggedUser()->hasRole('walas', request()->periode_aktif)){
            $remedial = Anggota_rombel::whereHas('nilai_satuan', function($query){
                $query->where('jenis_penilaian_id', 2);
                $query->where('angka', '<', 75);
            })->whereHas('rombongan_belajar', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('semester_id', request()->semester_id);
            })->count();
            if($remedial){
                $data = [
                    'title' => $remedial.' Siswa memiki nilai dibawah SKM',
                    'subtitle' => 'Lihat Detail',
                    'route' => '/wali-kelas/remedial',//'penilaian-remedial'
                ];
            }
            $absen = Anggota_rombel::whereHas('rombongan_belajar', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('semester_id', request()->semester_id);
            })->whereHas('presensi', function($query){
                $query->where('absen', 'A');
                $query->whereBetween('tanggal', [request()->tanggal_mulai, request()->tanggal_selesai]);
            })->with(['peserta_didik' => function($query){
                $query->select('peserta_didik_id', 'nama');
            }])->withCount(['presensi as alpa' => function($query){
                $query->where('absen', 'A');
                $query->whereBetween('tanggal', [request()->tanggal_mulai, request()->tanggal_selesai]);
            }])->get();
            if($absen->count()){
                $output = [];
                foreach($absen as $a){
                    $output[] = [
                        'title' => $a->peserta_didik->nama,
                        'subtitle' => ' Tidak masuk sebanyak '.$a->alpa.' jam pelajaran tanpa keterangan, lihat detail',
                        'route' => '/wali-kelas/absensi-siswa',//'presensi-rekap-pd'
                    ];
                }
                $data = array_filter(array_merge([$data], $output));
            } else {
                $data = [$data];
            }
        }
        foreach($data as $notif){
            if($notif){
                Notifikasi::updateOrCreate([
                    'title' => $notif['title'],
                    'subtitle' => $notif['subtitle'],
                    'route' => $notif['route'],
                    'user_id' => loggedUser()->user_id,
                ]);
            }
        }
    }
    public function read_notif(){
        $data = Notifikasi::find(request()->id);
        $data->terbaca = TRUE;
        $data->save();
    }
    public function menu(){
        $data = [
            [
                'icon' => "home-icon",
                'route' => "homepage",
                'title' => "Beranda",
            ]
        ];
        return response()->json($data);
    }
}
