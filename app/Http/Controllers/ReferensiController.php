<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\Mata_pelajaran;
use App\Models\Mapel_tingkat;
use App\Models\Rombongan_belajar;
use App\Models\Rombel_trigger;
use App\Models\Pembelajaran;
use App\Models\Peserta_didik;
use App\Models\Jurusan_sp;
use App\Models\Anggota_rombel;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\Kurikulum;
use App\Models\Tahun_ajaran;
use App\Models\Semester;
use App\Models\Team;
use App\Models\Agama;
use App\Models\Pekerjaan;
use App\Models\Cita;
use App\Models\Pd_keluar;
use App\Models\Jenis_keluar;
use App\Models\Kenaikan_kelas;
use App\Models\Jadwal;
use App\Models\Jam;
use App\Models\Kelas_bk;
use App\Models\Ijin;
use App\Models\Dudi;
use App\Models\Capaian_pembelajaran;
use App\Models\Tujuan_pembelajaran;
use Carbon\Carbon;
use Indonesia;

class ReferensiController extends Controller
{
    private function loggedUser(){
        return auth()->user();
    }
    public function mata_pelajaran(){
        $data = Mata_pelajaran::with([
            'pembelajaran' => function($query){
                $query->withWhereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', semester_id());
                });
                $query->withWhereHas('guru', function($query){
                    $query->select('guru_id', 'nama');
                });
            },
            'mapel_tingkat.jurusan_sp'
        ])->where($this->kondisiMapel())->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisiMapel());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    private function kondisiMapel(){
        return function($query){
            if($this->loggedUser()->hasRole('walas', periode_aktif())){
                $query->whereHas('pembelajaran', function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                    });
                });
            }
            if($this->loggedUser()->hasRole('pd', periode_aktif())){
                $query->whereHas('pembelajaran', function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('semester_id', semester_id());
                        $query->whereHas('kelas', function($query){
                            $query->where('peserta_didik_id', $this->loggedUser()->peserta_didik_id);
                        });
                    });
                });
            }
        };
    }
    public function kelompok_mapel(){
        $data = Kelompok::with('nama_kurikulum')->orderBy(request()->sortby, request()->sortbydesc)->orderBy('nama_kelompok')
        ->when(request()->q, function($query) {
            $query->where('nama_kelompok', 'ilike', '%'.request()->q.'%');
            $query->whereHas('nama_kurikulum', function($query){
                $query->where('nama', 'ilike', '%'.request()->q.'%');
            });
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function jurusan(){
        $data = Jurusan_sp::with(['kajur', 'rombongan_belajar' => function($query){
            $query->where('semester_id', request()->semester_id);
        }])->withCount(['rombongan_belajar as jml_kelas' => function($query){
            $query->where('semester_id', request()->semester_id);
        }])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama_jurusan_sp', 'ilike', '%'.request()->q.'%');
            $query->orWhereHas('kajur', function($query){
                $query->where('nama', 'ilike', '%'.request()->q.'%');
            });
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => request()->semester_id, 'order' => request()->sortby]);
    }
    public function rombongan_belajar(){
        $data = Rombongan_belajar::with(['wali_kelas', 'jurusan_sp', 'semester'])->where(function($query){
            $query->where('tingkat', '<>', 0);
            $query->where('semester_id', semester_id());
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
            $query->where('semester_id', semester_id());
            $query->orWhereHas('jurusan_sp', function($query){
                $query->where('nama_jurusan_sp', 'ilike', '%'.request()->q.'%');
                $query->orWhere('alias', 'ilike', '%'.request()->q.'%');
            });
            $query->where('semester_id', semester_id());
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    public function peserta_didik(){
        if(request()->keluar){
            if(request()->keluar == 1){
                $data = Peserta_didik::with(['keluar', 'agama',])->whereHas('pd_keluar', function($query){
                    $query->where('semester_id', semester_id());
                })->orderBy(request()->sortby, request()->sortbydesc)
                ->when(request()->q, function($query) {
                    $query->where('nama', 'ilike', '%'.request()->q.'%');
                    $query->whereHas('pd_keluar', function($query){
                        $query->where('semester_id', semester_id());
                    });
                })
                ->paginate(request()->per_page);
            } else {
                $data = Peserta_didik::with([
                    'kelas' => function($query){
                        $query->where('rombongan_belajar.semester_id', semester_id());
                    },
                    'anggota_rombel' => function($query){
                        $query->where('semester_id', semester_id());
                    },
                    'agama',
                ])->whereHas('kelas', function($query){
                    $query->where('rombongan_belajar.semester_id', semester_id());
                    if($this->loggedUser()->hasRole('walas', periode_aktif())){
                        $query->where('guru_id', loggedUser()->guru_id);
                    } else {
                        $query->whereHas('pembelajaran', function($query){
                            $query->where('guru_id', loggedUser()->guru_id);
                        });
                    }
                })->withAvg([
                    'nilai as rerata' => function($query){
                        $query->where('jenis_penilaian_id', '<>', 1);
                    },
                ], 'angka')
                ->orderBy(request()->sortby, request()->sortbydesc)
                ->when(request()->q, function($query) {
                    $query->where('nama', 'ilike', '%'.request()->q.'%');
                    $query->whereHas('kelas', function($query){
                        $query->where('rombongan_belajar.semester_id', semester_id());
                        $query->whereHas('pembelajaran', function($query){
                            $query->where('guru_id', loggedUser()->guru_id);
                        });
                    });
                })
                ->paginate(request()->per_page);
            }
        } else {
            /*$data = Peserta_didik::with(['kelas' => function($query){
                $query->where('rombongan_belajar.semester_id', semester_id());
            }])->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($query) {
                $query->where('nama', 'ilike', '%'.request()->q.'%');
                $query->whereHas('anggota_rombel', function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('semester_id', semester_id());    
                    });
                });
            })
            ->paginate(request()->per_page);*/
            $data = Peserta_didik::with([
                'kelas' => function($query){
                    $query->where('rombongan_belajar.semester_id', semester_id());
                },
                'anggota_rombel' => function($query){
                    $query->where('semester_id', semester_id());
                },
                'agama'
            ])->whereHas('kelas', function($query){
                $query->where('rombongan_belajar.semester_id', semester_id());
                if($this->loggedUser()->hasRole('guru', periode_aktif())){
                    if($this->loggedUser()->hasRole('walas', periode_aktif())){
                        $query->where('guru_id', loggedUser()->guru_id);
                    } else {
                        $query->whereHas('pembelajaran', function($query){
                            $query->where('guru_id', loggedUser()->guru_id);
                        });
                    }
                }
            })->withAvg([
                'nilai as rerata' => function($query){
                    $query->where('jenis_penilaian_id', '<>', 1);
                },
            ], 'angka')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($query) {
                $query->where('nama', 'ilike', '%'.request()->q.'%');
                $query->whereHas('kelas', function($query){
                    $query->where('rombongan_belajar.semester_id', semester_id());
                    /*$query->whereHas('pembelajaran', function($query){
                        $query->where('guru_id', loggedUser()->guru_id);
                    });*/
                    if($this->loggedUser()->hasRole('guru', periode_aktif())){
                        if($this->loggedUser()->hasRole('walas', periode_aktif())){
                            $query->where('guru_id', loggedUser()->guru_id);
                        } else {
                            $query->whereHas('pembelajaran', function($query){
                                $query->where('guru_id', loggedUser()->guru_id);
                            });
                        }
                    }
                });
            })
            ->paginate(request()->per_page);
        }
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    public function pd_keluar(){
        $data = Peserta_didik::whereHas('pd_keluar', function($query){
            $query->where('semester_id', semester_id());
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
            $query->whereHas('pd_keluar', function($query){
                $query->where('semester_id', semester_id());
            });
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    public function pembelajaran(){
        $data = Pembelajaran::with([
            'guru',
        ])->where('rombongan_belajar_id', request()->rombongan_belajar_id)->orderBy('nama_mata_pelajaran')->get();
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $kelompok = Kelompok::where('kurikulum', $rombel->kurikulum_id)->orderBy('kelompok_id')->get();
        return response()->json(['status' => 'success', 'data' => $data, 'kelompok' => $kelompok]);
    }
    public function guru_mapel(){
        $data = Guru::select('guru_id', 'nama', 'tanggal_lahir')->where($this->kondisi())->orderBy('nama')->get();
        return response()->json($data);
    }
    public function guru_bk(){
        $data = Guru::withWhereHas('kelas_bk', function($query){
            $query->where('semester_id', semester_id());
            $query->with(['rombongan_belajar' => function($query){
                $query->withCount('anggota_rombel');
            }]);
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    public function get_kelas_bk(){
        $data = rombongan_belajar::where('tingkat', request()->tingkat)->orderBy('nama')->get();
        return response()->json($data);
    }
    public function perangkat_pembelajaran(){
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $data = [
            'guru' => Guru::select('guru_id', 'nama', 'tanggal_lahir')->where($this->kondisi())->orderBy('nama')->get(),
            'mapel' => Mata_pelajaran::where(function($query) use ($rombel){
                    $query->whereHas('mapel_tingkat', function($query) use ($rombel){
                        $query->where('jurusan_sp_id', $rombel->jurusan_sp_id);
                        $query->where('tingkat', $rombel->tingkat);
                    });
                    //$query->whereJsonContains('tingkat', [1 => $rombel->tingkat]);
                    //$query->orWhereJsonContains('tingkat', [2 => $rombel->tingkat]);
                    //$query->orWhereJsonContains('tingkat', [3 => $rombel->tingkat]);
                    //$query->orWhereJsonContains('tingkat', [4 => $rombel->tingkat]);
                })->with(['pembelajaran' => function($query){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                }])->orderBy('mata_pelajaran_id')->get(),
            'rombel' => $rombel,
            'kelompok' => Kelompok::where('kurikulum', $rombel->kurikulum_id)->orderBy('kelompok_id')->get(),
        ];
        return response()->json($data);
    }
    public function simpan_pembelajaran(){
        $mapel = Mata_pelajaran::find(request()->mata_pelajaran_id);
        if(request()->guru_id){
            $pembelajaran = Pembelajaran::where(function($query){
                $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->first();
            if(!$pembelajaran){
                $pembelajaran = new Pembelajaran;
                $pembelajaran->mata_pelajaran_id = request()->mata_pelajaran_id;
                $pembelajaran->rombongan_belajar_id = request()->rombongan_belajar_id;
                $pembelajaran->sekolah_id = sekolah_id();
                $pembelajaran->semester_id = semester_id();
            }
            $pembelajaran->nama_mata_pelajaran = $mapel->nama;
            $pembelajaran->guru_id = request()->guru_id;
            $pembelajaran->kelompok_id = request()->kelompok_id;
            $pembelajaran->no_urut = request()->no_urut;
            if($pembelajaran->save()){
                $data = [
                    'success' => TRUE,
                    'text' => 'Pembelajaran '.$pembelajaran->nama_mata_pelajaran.' berhasil diperbaharui',
                ];
            } else {
                $data = [
                    'success' => FALSE,
                    'text' => 'Pembelajaran '.$pembelajaran->nama_mata_pelajaran.' gagal diperbaharui',
                ];
            }
        } else {
            $data = [
                'success' => FALSE,
                'text' => 'Mata Pelajaran '.$mapel->nama.' tidak disimpan',
            ];
        }
        return response()->json($data);
    }
    public function hapus_pembelajaran(){
        Pembelajaran::where(function($query){
            $query->whereNotIn('guru_id', request()->guru_id);
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->orWhereNull('guru_id');
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->delete();
        $data = [
            'guru_id' => request()->guru_id,
            'success' => TRUE,
            'text' => 'Pembelajaran terhapus',
        ];
        return response()->json($data);
    }
    public function data_guru(){
        $data = Guru::where($this->kondisi())->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where($this->kondisi());
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function add_data(){
        $insert = NULL;
        $text = 'Tidak diketahui';
        if(request()->data == 'jurusan'){
            $text = 'Jurusan';
            $validator = Validator::make(request()->all(), 
                [
                    'nama_jurusan_sp' => ['required'],
                    'alias' => ['required'],
                    'guru_id' => ['required'],
                ],
                [
                    'nama_jurusan_sp.required' => 'Nama Jurusan tidak boleh kosong',
                    'alias.required' => 'Nama Singkatan tidak boleh kosong',
                    'guru_id.required' => 'Ketua Jurusan tidak boleh kosong',
                ]
            );
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => FALSE,
                    'errors' => $validator->errors(),
                ]);
            }
            $insert = Jurusan_sp::create([
                'sekolah_id' => sekolah_id(),
                'guru_id' => request()->guru_id,
                'semester_id' => semester_id(),
                'nama_konsentrasi' => request()->nama_konsentrasi,
                'nama_jurusan_sp' => request()->nama_jurusan_sp,
                'alias' => request()->alias,
            ]);
        }
        if(request()->data == 'rombel'){
            $text = 'Kelas';
            $validator = Validator::make(request()->all(), 
                [
                    'nama' => ['required'],
                    'tingkat' => ['required'],
                    'kurikulum_id' => ['required'],
                    'jurusan_sp_id' => ['required'],
                    'guru_id' => ['required'],
                ],
                [
                    'nama.required' => 'Nama Kelas tidak boleh kosong',
                    'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
                    'kurikulum_id.required' => 'Kurikulum tidak boleh kosong',
                    'jurusan_sp_id.required' => 'Jurusan tidak boleh kosong',
                    'guru_id.required' => 'Ketua Jurusan tidak boleh kosong',
                ]
            );
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => FALSE,
                    'errors' => $validator->errors(),
                ]);
            }
            $kur = Kurikulum::find(request()->kurikulum_id);
            $insert = Rombongan_belajar::create([
                'sekolah_id' => sekolah_id(),
                'guru_id' => request()->guru_id,
                'semester_id' => semester_id(),
                'jurusan_sp_id' => request()->jurusan_sp_id,
                'kurikulum_id' => request()->kurikulum_id,
                'kurikulum' => $kur->nama,
                'nama' => request()->nama,
                'tingkat' => request()->tingkat,
            ]);
        }
        if(request()->data == 'mapel'){
            $text = 'Mata Pelajaran';
            $validator = Validator::make(request()->all(), 
                [
                    'nama' => ['required'],
                    'tingkat' => ['required'],
                    'jurusan_sp_id' => ['required'],
                    'jenis' => ['required'],
                ],
                [
                    'nama.required' => 'Nama Mata Pelajaran tidak boleh kosong',
                    'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
                    'jurusan_sp_id.required' => 'Jurusan tidak boleh kosong',
                    'jenis.required' => 'Jenis Mapel tidak boleh kosong',
                ]
            );
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => FALSE,
                    'errors' => $validator->errors(),
                ]);
            }
            $insert = Mata_pelajaran::create([
                'nama' => request()->nama,
                'tingkat' => array_filter(array_values(request()->tingkat)),
                'jenis' => request()->jenis,
            ]);
            foreach(array_filter(array_values(request()->tingkat)) as $tingkat){
                foreach(array_filter(array_values(request()->jurusan_sp_id)) as $jurusan_sp_id){
                    Mapel_tingkat::updateOrCreate([
                        'mata_pelajaran_id' => $insert->mata_pelajaran_id,
                        'tingkat' => $tingkat,
                        'jurusan_sp_id' => $jurusan_sp_id
                    ]);
                }
            }
        }
        if(request()->data == 'kelompok'){
            $text = 'Kelompok Mapel';
            $validator = Validator::make(request()->all(), 
                [
                    'nama_kelompok' => ['required'],
                    //'kurikulum_id' => ['required'],
                ],
                [
                    'nama_kelompok.required' => 'Nama Kelompok Mapel tidak boleh kosong',
                    //'kurikulum_id.required' => 'Kurikulum tidak boleh kosong',
                ]
            );
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => FALSE,
                    'errors' => $validator->errors(),
                ]);
            }
            $insert = Kelompok::create([
                'nama_kelompok' => request()->nama_kelompok,
                'kurikulum' => (request()->kurikulum_id) ? request()->kurikulum_id : 0,
            ]);
        }
        if(request()->data == 'ekskul'){
            $text = 'Ekstrakurikuler';
            $validator = Validator::make(request()->all(), 
                [
                    'nama' => ['required'],
                    'guru_id' => ['required'],
                ],
                [
                    'nama.required' => 'Nama Kelas tidak boleh kosong',
                    'guru_id.required' => 'Ketua Jurusan tidak boleh kosong',
                ]
            );
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => FALSE,
                    'errors' => $validator->errors(),
                ]);
            }
            $kur = Kurikulum::find(request()->kurikulum_id);
            $insert = Rombongan_belajar::create([
                'sekolah_id' => sekolah_id(),
                'guru_id' => request()->guru_id,
                'semester_id' => semester_id(),
                'jurusan_sp_id' => NULL,
                'kurikulum_id' => NULL,
                'kurikulum' => '-',
                'nama' => request()->nama,
                'tingkat' => 0,
                'status' => request()->status,
                'nomor_sertifikat' => request()->nomor_sertifikat,
            ]);
        }
        if(request()->data == 'bk'){
            $text = 'Guru BK';
            $validator = Validator::make(request()->all(), 
                [
                    'guru_id' => ['required'],
                    'tingkat' => ['required'],
                    'rombongan_belajar_id' => ['required'],
                ],
                [
                    'guru_id.required' => 'Guru BK tidak boleh kosong',
                    'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
                    'rombongan_belajar_id.required' => 'Kelas tidak boleh kosong',
                ]
            );
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => FALSE,
                    'errors' => $validator->errors(),
                ]);
            }
            $user = User::where('guru_id', request()->guru_id)->first();
            if($user){
                if(!$user->hasRole('bk', request()->periode_aktif)){
                    $user->attachRole('bk', request()->periode_aktif);
                }
                foreach(request()->rombongan_belajar_id as $rombongan_belajar_id){
                    Kelas_bk::updateOrCreate([
                        'guru_id' => request()->guru_id,
                        'semester_id' => request()->semester_id,
                        'rombongan_belajar_id' => $rombongan_belajar_id,
                    ]);
                    $insert++;    
                }
            }
        }
        if(request()->data == 'dudi'){
            $text = 'DUDI';
            $validator = Validator::make(request()->all(), 
                [
                    'nama' => ['required'],
                    'pimpinan' => ['required'],
                    'nama_bidang_usaha' => ['required'],
                    'alamat_jalan' => ['required'],
                ],
                [
                    'nama.required' => 'Nama DUDI tidak boleh kosong',
                    'pimpinan.required' => 'Nama Pimpinan DUDI tidak boleh kosong',
                    'nama_bidang_usaha.required' => 'Nama Bidang Usaha tidak boleh kosong',
                    'alamat_jalan.required' => 'Alamat tidak boleh kosong',
                    
                ]
            );
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => FALSE,
                    'errors' => $validator->errors(),
                ]);
            }
            $insert = Dudi::create([
                'sekolah_id' => sekolah_id(),
                'nama' => request()->nama,
                'pimpinan' => request()->pimpinan,
                'nama_bidang_usaha' => request()->nama_bidang_usaha,
                'alamat_jalan' => request()->alamat_jalan,
            ]);
        }
        if($insert){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => $text.' berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => $text.' gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function hapus_data(){
        $get = NULL;
        $text = 'Data '. ucwords(request()->data);
        if(request()->data == 'semester'){
            $get = Semester::find(request()->id);
            $text = 'Data Periodik';
        } elseif(request()->data == 'jurusan'){
            $get = Jurusan_sp::find(request()->id);
            $text = 'Data Jurusan';
        } elseif(request()->data == 'guru'){
            $get = Guru::find(request()->id);
            $text = 'Data Guru';
        } elseif(request()->data == 'pembelajaran'){
            $get = Pembelajaran::find(request()->id);
        } elseif(request()->data == 'pd'){
            $get = Peserta_didik::find(request()->id);
        } elseif(request()->data == 'pd'){
            $get = Peserta_didik::find(request()->id);
        } elseif(request()->data == 'jadwal'){
            $get = Jadwal::find(request()->id);
        } elseif(request()->data == 'jadwal-mapel'){
            $get = Jadwal::where('pembelajaran_id', request()->id)->get();
        } elseif(request()->data == 'jam'){
            $get = Jam::find(request()->id);
        } elseif(request()->data == 'mapel'){
            $get = Mata_pelajaran::find(request()->id);
        } elseif(request()->data == 'rombel' || request()->data == 'ekskul'){
            $get = Rombongan_belajar::find(request()->id);
        } elseif(request()->data == 'kelompok'){
            $get = Kelompok::find(request()->id);
        } elseif(request()->data == 'bk'){
            $get = Kelas_bk::where('guru_id', request()->id)->where('semester_id', semester_id())->get();
        } elseif(request()->data == 'perijinan'){
            $get = Ijin::find(request()->id);
        } elseif(request()->data == 'cp'){
            $get = Capaian_pembelajaran::find(request()->id);
        } elseif(request()->data == 'tp'){
            $get = Tujuan_pembelajaran::find(request()->id);
        } elseif(request()->data == 'dudi'){
            $get = Dudi::find(request()->id);
        }
        
        if($get){
            $delete = NULL;
            if(is_a($get, 'Illuminate\Database\Eloquent\Collection')) {
                if(request()->data == 'jadwal-mapel'){
                    $delete = Jadwal::where('pembelajaran_id', request()->id)->delete();
                }
                if(request()->data == 'bk'){
                    $delete = Kelas_bk::where('guru_id', request()->id)->where('semester_id', semester_id())->delete();
                    $user = User::where('guru_id', request()->id)->first();
                    $user->detachRole('bk', periode_aktif());
                    $text = 'Guru BK';
                }
            } else {
                $delete = $get->delete();
            }
            if($delete){
                $data = [
                    'icon' => 'success',
                    'text' => $text.' berhasil dihapus',
                    'title' => 'Berhasil',
                ];    
            } else {
                $data = [
                    'icon' => 'error',
                    'text' => $text.' gagal dihapus. Silahkan coba beberapa saat lagi!',
                    'title' => 'Gagal',
                ];
            }
        } else {
            $data = [
                'icon' => 'error',
                'text' => $text.' gagal dihapus. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function detil_data(){
        if(request()->data == 'guru'){
            if(request()->edit){
                $guru = Guru::with('presensi')->find(request()->id);
                $data = [
                    'guru' => $guru,
                    'provinsi' => Indonesia::allProvinces(),
                    'kabupaten' => get_kabupaten($guru->provinsi_id),
                    'kecamatan' => get_kecamatan($guru->kabupaten_id),
                    'desa' => get_desa($guru->kecamatan_id),
                    'agama' => Agama::get(),
                ];
            } else {
                $data = Guru::with(['provinsi', 'kabupaten', 'kecamatan', 'desa', 'agama'])->find(request()->id);
            }
        } elseif(request()->data == 'pd'){
            if(request()->edit){
                $pd = Peserta_didik::find(request()->id);
                $data = [
                    'pd' => $pd,
                    'provinsi' => Indonesia::allProvinces(),
                    'kabupaten' => get_kabupaten($pd->provinsi_id),
                    'kecamatan' => get_kecamatan($pd->kabupaten_id),
                    'desa' => get_desa($pd->kecamatan_id),
                    'agama' => Agama::get(),
                    'pekerjaan' => Pekerjaan::get(),
                    'cita' => Cita::get(),
                ];
            } else {
                $data = Peserta_didik::withTrashed()->with(['desa', 'kecamatan', 'kabupaten', 'provinsi', 'agama', 'pekerjaan_ayah', 'pekerjaan_ibu'])->find(request()->id);
            }
        } elseif(request()->data == 'semester'){
            $data = Semester::find(request()->id);
        } elseif(request()->data == 'jurusan'){
            $data = [
                'jurusan' => Jurusan_sp::find(request()->id),
                'guru' => Guru::where('sekolah_id', request()->sekolah_id)->get(),
            ];
        } elseif(request()->data == 'jadwal'){
            $jadwal = Jadwal::with(['jam', 'pembelajaran'])->find(request()->id);
            $rombel = Rombongan_belajar::find($jadwal->rombongan_belajar_id);
            $jam = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
            if($jadwal->hari == 'Jumat'){
                $jam = [1, 2, 3, 4, 5, 6];
            }
            $data = [
                'jadwal' => $jadwal,
                'rombel' => [$rombel],
                'tingkat' => $rombel->tingkat,
                'jam' => $jadwal->jam->pluck('jam'),
                'data_jam' => $jam,
                'pembelajaran' => [Pembelajaran::find($jadwal->pembelajaran_id)],
            ];
        } elseif(request()->data == 'mapel'){
            $data = [
                'mapel' => Mata_pelajaran::with(['mapel_tingkat.jurusan_sp'])->find(request()->id),
                'jurusan' => Jurusan_sp::where('semester_id', semester_id())->orderBy('nama_jurusan_sp')->get(),
            ];
        } elseif(request()->data == 'rombel' || request()->data == 'ekskul'){
            $data = Rombongan_belajar::find(request()->id);
        } elseif(request()->data == 'kelompok'){
            $data = [
                'kelompok' => Kelompok::find(request()->id),
                'data_kurikulum' => Kurikulum::get(),
            ];
        } else {
            $data = [
                'success' => FALSE,
                'errors' => 'Query tidak ditemukan',
            ];
        }
        return response()->json($data);
    }
    private function kondisi(){
        return function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        };
    }
    public function get_guru(){
        
        if(request()->per_page){
            $data = Guru::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($query) {
                $query->where('nama', 'ilike', '%'.request()->q.'%');
            })->paginate(request()->per_page);
        } else {
            $tanggal = Carbon::now();
            if(request()->tanggal){
                $tanggal = Carbon::createFromDate(request()->tanggal);
            }
            /*
            
            $data = [
                'tanggal' => $tanggal->format('Y-m-d'),
                'tanggal_str' => $tanggal->translatedFormat('l, j F Y'),
                'jumlah_jam' => ($tanggal->dayOfWeek == Carbon::FRIDAY) ? 6 : 11
            ];
            */
            $data = [
                'guru' => Guru::orderBy('nama')->where(function($query) use ($tanggal) {
                        if(request()->aksi == 'absen'){
                            $query->whereHas('jadwal', function($query) use ($tanggal){
                                $query->where('hari', $tanggal->translatedFormat('l'));
                            });
                        }
                    })->with([
                        'presensi' => function($query) use ($tanggal){
                            $query->whereDate('tanggal', $tanggal);
                            $query->orderBy('jam');
                        },
                        'jadwal' => function($query) use ($tanggal){
                            $query->where('hari', $tanggal->translatedFormat('l'));
                            $query->with(['jam']);
                        }
                    ])->get(),
                'tanggal' => $tanggal->format('Y-m-d'),
                'tanggal_str' => $tanggal->translatedFormat('l, j F Y'),
            ];
        }
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    public function get_jurusan(){
        $data = Jurusan_sp::where(function($query){
            if(request()->tingkat){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', semester_id());
                    $query->where('tingkat', request()->tingkat);
                    if(request()->nilai){
                        $query->whereHas('pembelajaran', function($query){
                            $query->where('guru_id', loggedUser()->guru_id);
                        });
                    }
                });
            }
        })->orderBy('nama_jurusan_sp')->get();
        return response()->json($data);
    }
    public function get_rombel(){
        $data = Rombongan_belajar::where(function($query){
            if(request()->tingkat){
                $query->where('tingkat', request()->tingkat);
            }
            if(request()->nilai){
                if(!$this->loggedUser()->hasRole('administrator', periode_aktif())){
                    if($this->loggedUser()->hasRole('kajur', periode_aktif())){
                        $query->whereIn('jurusan_sp_id', $this->dataKajur());
                    } else {
                        $query->whereHas('pembelajaran', function($query){
                            $query->where('guru_id', $this->loggedUser()->guru_id);
                        });
                    }
                }
            }
            if(request()->aksi){
                if(request()->aksi == 'kenaikan'){
                    $rombel = Rombongan_belajar::find(request()->id);
                    $query->where('tingkat', ($rombel->tingkat + 1));
                    $query->where('jurusan_sp_id', $rombel->jurusan_sp_id);
                }
            }
            if(request()->nama){
                $query->where('nama', 'ilike', '%'.request()->nama.'%');
            }
            if(request()->data){
                if(request()->data == 'edit-bk'){
                    $query->whereHas('kelas_bk', function($query){
                        $query->where('guru_id', request()->guru_id);
                    });
                }
                if(request()->data == 'bk'){
                    $query->whereDoesntHave('kelas_bk', function($query){
                        $query->where('semester_id', request()->semester_id);
                    });
                }
            }
            $query->where('semester_id', semester_id());
        })->select('rombongan_belajar_id', 'nama', 'semester_id')->orderBy('nama')->get();
        return response()->json($data);
    }
    public function get_mapel(){
        $pembelajaran = Pembelajaran::where(function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereHas('mata_pelajaran', function($query){
                $query->where('jenis', 'Umum');
            });
            if(request()->nilai){
                if($this->loggedUser()->hasRole('pengajar', periode_aktif())){
                    if(request()->penilaian){
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                    } else {
                        if(!$this->loggedUser()->hasRole('wakakur', periode_aktif())){
                            $query->where('guru_id', $this->loggedUser()->guru_id);
                        }
                        if($this->loggedUser()->hasRole('kajur', periode_aktif())){
                            $query->whereHas('rombongan_belajar', function($query){
                                $query->whereIn('jurusan_sp_id', $this->dataKajur());
                            });
                        }
                    }
                }
            }
        })->select('pembelajaran_id', 'nama_mata_pelajaran', 'mata_pelajaran_id')->orderBy('mata_pelajaran_id')->get();
        $jam = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
        if(request()->hari == 'Jumat'){
            $jam = [1, 2, 3, 4, 5, 6];
        }
        if(request()->nilai){
            $data = $pembelajaran;
        } else {
            $data = [
                'pembelajaran' => $pembelajaran,
                'jam' => $jam,
            ];
        }
        return response()->json($data);
    }
    public function get_cp(){
        $data = Capaian_pembelajaran::where('mata_pelajaran_id', request()->mata_pelajaran_id)->orderBy('deskripsi')->get();
        return response()->json($data);
    }
    private function dataKajur(){
        return Kajur::select('jurusan_sp_id')->where(function($query){
            $query->where('guru_id', loggedUser()->guru_id);
            $query->where('semester_id', semester_id());
        })->get();
    }
    public function get_group_mapel(){
        $data = Mata_pelajaran::where(function($query){
            if(request()->data){
                if(request()->data == 'pkl'){
                    $query->where('jenis', 'PKL');
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('guru_id', request()->guru_id);
                        $query->where('semester_id', request()->semester_id);
                    });
                }
            } else {
                $query->where('jenis', 'Umum');
                $query->whereHas('pembelajaran', function($query){
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->where('guru_id', request()->guru_id);
                    $query->where('semester_id', request()->semester_id);
                });
            }
        })->orderBy('mata_pelajaran_id')->get();
        /*Pembelajaran::where(function($query){
            if(request()->data){
                if(request()->data == 'pkl'){
                    $query->whereHas('mata_pelajaran', function($query){
                        $query->where('jenis', 'PKL');
                    });
                }
            } else {
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                $query->whereHas('mata_pelajaran', function($query){
                    $query->where('jenis', 'Umum');
                });
            }
            $query->where('guru_id', $this->loggedUser()->guru_id);
            $query->where('semester_id', semester_id());
        })->groupBy('nama_mata_pelajaran')->groupBy('mata_pelajaran_id')
        ->select('nama_mata_pelajaran', 'mata_pelajaran_id')->orderBy('mata_pelajaran_id')->get();*/
        return response()->json($data);
    }
    public function get_siswa(){
        $data = [];
        if(request()->data){
            if(request()->data == 'kenaikan'){
                $data = [
                    'pd' => Peserta_didik::withWhereHas('kelas', function ($query) {
                        $query->where('guru_id', request()->guru_id);
                        $query->where('rombongan_belajar.semester_id', request()->semester_id);
                    })->withWhereHas('anggota_rombel', function ($query) {
                        $query->whereHas('rombongan_belajar', function($query){
                            $query->where('guru_id', request()->guru_id);
                            $query->where('semester_id', request()->semester_id);
                        });
                    })->with(['kenaikan_kelas'])->orderBy('nama')->get(),
                    'semester' => Semester::find(request()->semester_id),
                ];        
            }
            if(request()->data == 'nilai-ekskul'){
                $data = [
                    'pd' => Peserta_didik::withWhereHas('kelas', function ($query) {
                        $query->where('guru_id', request()->guru_id);
                        $query->where('rombongan_belajar.semester_id', request()->semester_id);
                    })->withWhereHas('anggota_rombel', function ($query) {
                        $query->whereHas('rombongan_belajar', function($query){
                            $query->where('guru_id', request()->guru_id);
                            $query->where('semester_id', request()->semester_id);
                        });
                    })->with(['anggota_ekskul' => function($query){
                        $query->where('semester_id', request()->semester_id);
                        $query->with(['rombongan_belajar', 'nilai_ekskul']);
                    }])->orderBy('nama')->get(),
                    'semester' => Semester::find(request()->semester_id),
                ];        
            }
            if(request()->data == 'presensi'){
                $data = [
                    'rombel' => Rombongan_belajar::find(request()->rombongan_belajar_id),
                    'pd' => Peserta_didik::withWhereHas('anggota_rombel', function ($query) {
                        $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                    })->with([
                        'presensi' => function($query){
                            $query->whereDate('tanggal', request()->tanggal);
                            $query->orderBy('jam');
                        },
                    ])->orderBy('nama')->get(),
                ];
            }
            if(request()->data == 'pkl'){
                $data = [
                    'pd' => Peserta_didik::whereHas('pd_pkl')->withWhereHas('kelas', function ($query) {
                        $query->where('rombongan_belajar.semester_id', request()->semester_id);
                    })->withWhereHas('anggota_rombel', function ($query) {
                        $query->whereHas('rombongan_belajar', function($query){
                            $query->where('semester_id', request()->semester_id);
                            $query->whereHas('jurusan_sp', function($query){
                                $query->where('guru_id', request()->guru_id);
                            });
                        });
                    })->orderBy('nama')->get(),
                    'semester' => Semester::find(request()->semester_id),
                ];        
            }
        } else {
            $data = Peserta_didik::withWhereHas('anggota_rombel', function ($query) {
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->with([
                'presensi' => function($query){
                    $query->whereDate('tanggal', request()->tanggal);
                    $query->orderBy('jam');
                },
            ])->orderBy('nama')->get();
        }
        return response()->json($data);
    }
    public function sekolah(){
        $sekolah = Sekolah::find(request()->sekolah_id);
        if(request()->isMethod('post')){
            request()->validate(
                [
                    'nama' => ['required'],
                    'npsn' => ['required'],
                    'alamat_jalan' => ['required'],
                    'kode_pos' => ['required'],
                    'provinsi_id' => ['required'],
                    'kabupaten_id' => ['required'],
                    'kecamatan_id' => ['required'],
                    'desa_id' => ['required'],
                    'email' => ['required', 'email'],
                    'website' => ['required'],
                ],
                [
                    'nama.required' => 'Nama Sekolah tidak boleh kosong!',
                    'npsn.required' => 'NPSN tidak boleh kosong!',
                    'alamat_jalan.required' => 'Alamat tidak boleh kosong!',
                    'kode_pos.required' => 'Kodepos tidak boleh kosong!',
                    'provinsi_id.required' => 'Provinsi tidak boleh kosong!',
                    'kabupaten_id.required' => 'Kabupaten/Kota tidak boleh kosong!',
                    'kecamatan_id.required' => 'Kecamatan tidak boleh kosong!',
                    'desa_id.required' => 'Desa/Kelurahan tidak boleh kosong!',
                    'email.required' => 'Email tidak boleh kosong!',
                    'email.email' => 'Email tidak valid!',
                    'website.required' => 'Website tidak boleh kosong!',
                ],
            );
            $sekolah->nama = request()->nama;
            $sekolah->npsn = request()->npsn;
            $sekolah->alamat_jalan = request()->alamat_jalan;
            $sekolah->kode_pos = request()->kode_pos;
            $sekolah->provinsi_id = request()->provinsi_id;
            $sekolah->kabupaten_id = request()->kabupaten_id;
            $sekolah->kecamatan_id = request()->kecamatan_id;
            $sekolah->desa_id = request()->desa_id;
            $sekolah->email = request()->email;
            $sekolah->website = request()->website;
            if(request()->logo){
                $logo = request()->logo->store('public/images');
                $sekolah->logo = 'images/'.basename($logo);
            }
            if($sekolah->save()){
                $data = [
                    'icon' => 'success',
                    'text' => 'Data Sekolah berhasil diperbaharui',
                    'title' => 'Berhasil',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'text' => 'Data Sekolah gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                    'title' => 'Gagal',
                ];
            }
        } else {
            $data = [
                'sekolah' => $sekolah,
                'provinsi' => Indonesia::allProvinces(),
                'kabupaten' => get_kabupaten($sekolah->provinsi_id),
                'kecamatan' => get_kecamatan($sekolah->kabupaten_id),
                'desa' => get_desa($sekolah->kecamatan_id),
                'isAdmin' => $this->loggedUser()->hasRole('administrator', periode_aktif())
            ];
        }
        return response()->json($data);
    }
    private function save_kajur($jurusan_sp_id){
        return Kajur::updateOrCreate(
            [
                'semester_id' => semester_id(),
                'jurusan_sp_id' => $jurusan_sp_id,
            ],
            [
                'guru_id' => request()->guru_id,
            ]
        );
    }
    private function get_sub($jurusan = null){
        if($jurusan){
            foreach($jurusan as $jur){
                $this->get_sub($jur->childsRecursive);
                $this->find($jur->jurusan_id);
            }
        }
    }
    private function find($jurusan_id){
        $find = Jurusan_sp::where('jurusan_id', $jurusan_id)->first();
        if($find){
            $this->save_kajur($find->jurusan_sp_id);
        }
    }
    public function set_ketua(){
        /*$jurusan_sp = Jurusan_sp::with(['jurusan' => function($query){
            $query->with(['parrentRecursive' => function($query){
                $query->with(['childsRecursive']);
            }, 'childsRecursive' => function($query){
                $query->with(['parrentRecursive']);
            }]);
        }])->find(request()->jurusan_sp_id);
        if($jurusan_sp->jurusan->parrentRecursive){
            $this->find($jurusan_sp->jurusan->parrentRecursive->jurusan_id);
            $this->get_sub($jurusan_sp->jurusan->parrentRecursive->childsRecursive);
            if($jurusan_sp->jurusan->parrentRecursive->parrentRecursive){
                $this->find($jurusan_sp->jurusan->parrentRecursive->parrentRecursive->jurusan_id);
                $this->get_sub($jurusan_sp->jurusan->parrentRecursive->parrentRecursive->childsRecursive);
                if($jurusan_sp->jurusan->parrentRecursive->parrentRecursive->parrentRecursive){
                    $this->find($jurusan_sp->jurusan->parrentRecursive->parrentRecursive->parrentRecursive->jurusan_id);
                    $this->get_sub($jurusan_sp->jurusan->parrentRecursive->parrentRecursive->parrentRecursive->childsRecursive);
                }
            }
        }*/
        $kajur = $this->save_kajur(request()->jurusan_sp_id);
        if($kajur){
            $user = User::where('guru_id', request()->guru_id)->first();
            if($user){
                if(!$user->hasRole('kajur', periode_aktif())){
                    $user->attachRole('kajur', periode_aktif());
                }
            }
            $data = [
                'icon' => 'success',
                'text' => 'Data Sekolah berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Data Sekolah gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function wilayah(){
        if(request()->provinsi_id){
            $data = get_kabupaten(request()->provinsi_id);
        } elseif(request()->kabupaten_id){
            $data = get_kecamatan(request()->kabupaten_id);
        } elseif(request()->kecamatan_id){
            $data = get_desa(request()->kecamatan_id);
        } else {
            $data = all_provinsi();
        }
        return response()->json($data);
    }
    public function anggota_rombel(){
        $data = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->with(['agama'])->orderBy('nama')->get();
        return response()->json($data);
    }
    public function anggota_ekskul(){
        $data = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->orderBy('nama')->get();
        return response()->json($data);
    }
    public function get_semester(){
        $data = Semester::with(['tahun_ajaran'])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    public function add_semester(){
        /**/
        $validator = Validator::make(request()->all(), 
            [
                'tahun_ajaran_id' => ['required', 'unique:App\Models\Tahun_ajaran', 'digits:4', 'integer', 'min:2000', 'max:'.(date('Y')+1)],
                'tanggal_mulai_ganjil' => ['required'],
                'tanggal_selesai_ganjil' => ['required'],
                'tanggal_cetak_ganjil' => ['required'],
                'tanggal_mulai_genap' => ['required'],
                'tanggal_selesai_genap' => ['required'],
                'tanggal_cetak_genap' => ['required'],
            ],
            [
                'tahun_ajaran_id.required' => 'Tahun Pelajaran tidak boleh kosong',
                'tahun_ajaran_id.unique' => 'Tahun Pelajaran terdeteksi existing',
                'tahun_ajaran_id.digits' => 'Tahun Pelajaran harus terdiri dari 4 digit',
                'tahun_ajaran_id.integer' => 'Tahun Pelajaran harus berupa angka',
                'tahun_ajaran_id.min' => 'Tahun Pelajaran minimal Tahun 2000',
                'tahun_ajaran_id.max' => 'Tahun Pelajaran maksimal Tahun '.(date('Y')+1),
                'tanggal_mulai_ganjil.required' => 'Tanggal Mulai Semester Ganjil tidak boleh kosong',
                'tanggal_selesai_ganjil.required' => 'Tanggal Selesai Semester Ganjil tidak boleh kosong',
                'tanggal_cetak_ganjil.required' => 'Tanggal Cetak Rapor Semester Ganjil tidak boleh kosong',
                'tanggal_mulai_genap.required' => 'Tanggal Mulai Semester Genap tidak boleh kosong',
                'tanggal_selesai_genap.required' => 'Tanggal Selesai Semester Genap tidak boleh kosong',
                'tanggal_cetak_genap.required' => 'Tanggal Cetak Rapor Semester Genap tidak boleh kosong',
            ]
        );
 
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $insert = Tahun_ajaran::create([
            'tahun_ajaran_id' => request()->tahun_ajaran_id,
            'nama' => request()->tahun_ajaran_id.'/'.(request()->tahun_ajaran_id + 1),
            'periode_aktif' => 1,
            'tanggal_mulai' => request()->tanggal_mulai_ganjil,
            'tanggal_selesai' => request()->tanggal_selesai_genap,
        ]);
        if($insert){
            $ganjil = Semester::create([
                'semester_id' => request()->tahun_ajaran_id.'1',
                'tahun_ajaran_id' => request()->tahun_ajaran_id,
                'nama' => $insert->nama.' Ganjil',
                'semester' => 1,
                'periode_aktif' => 0,
                'tanggal_mulai' => request()->tanggal_mulai_ganjil,
                'tanggal_selesai' => request()->tanggal_selesai_ganjil,
                'tanggal_cetak' => request()->tanggal_cetak_ganjil,
            ]);
            $genap = Semester::create([
                'semester_id' => request()->tahun_ajaran_id.'2',
                'tahun_ajaran_id' => request()->tahun_ajaran_id,
                'nama' => $insert->nama.' Genap',
                'semester' => 2,
                'periode_aktif' => 0,
                'tanggal_mulai' => request()->tanggal_mulai_genap,
                'tanggal_selesai' => request()->tanggal_selesai_genap,
                'tanggal_cetak' => request()->tanggal_cetak_genap,
            ]);
            $team_ganjil = Team::updateOrcreate([
                'name' => $ganjil->nama,
                'display_name' => $ganjil->nama,
                'description' => $ganjil->nama,
            ]);
            $team_genap = Team::updateOrcreate([
                'name' => $genap->nama,
                'display_name' => $genap->nama,
                'description' => $genap->nama,
            ]);
            if(!$this->loggedUser()->hasRole('administrator', $team_ganjil->name)){
                $this->loggedUser()->attachRole('administrator', $team_ganjil->name); 
            }
            if(!$this->loggedUser()->hasRole('administrator', $team_genap->name)){
                $this->loggedUser()->attachRole('administrator', $team_genap->name); 
            }
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data Periodik berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data Periodik gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function update_semester(){
        $insert = Semester::find(request()->semester_id);
        $insert->tanggal_mulai = request()->tanggal_mulai;
        $insert->tanggal_selesai = request()->tanggal_selesai;
        $insert->tanggal_cetak = request()->tanggal_cetak;
        if($insert->save()){
            $data = [
                'icon' => 'success',
                'text' => 'Data Periodik berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Data Periodik gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function lanjutkan(){
        /*if(request()->aksi == 'lanjutkan'){
            $next_semester_id = semester_id();
        } else {
            $next_semester_id = semester_id() + 1;
        }*/
        $next_semester_id = semester_id();
        $d = Rombongan_belajar::with(['anggota_rombel', 'pembelajaran'])->find(request()->rombongan_belajar_id);
        $insert = Rombongan_belajar::where('semester_id', $next_semester_id)->where('nama', $d->nama)->first();
        if(!$insert){
            $insert = Rombongan_belajar::create([
                'rombongan_belajar_id' => Str::uuid(),
                'sekolah_id' => $d->sekolah_id,
                'semester_id' => $next_semester_id,
                'jurusan_id' => $d->jurusan_id,
                'jurusan_sp_id' => $d->jurusan_sp_id,
                'kurikulum_id' => $d->kurikulum_id,
                'nama' => $d->nama,
                'guru_id' => $d->guru_id,
                'tingkat' => (request()->aksi == 'lanjutkan') ? $d->tingkat : $d->tingkat + 1,
                'tahun' => $d->tahun,
            ]);
            foreach($d->anggota_rombel as $anggota){
                $find = Anggota_rombel::where('peserta_didik_id', $anggota->peserta_didik_id)->where('rombongan_belajar_id', $insert->rombongan_belajar_id)->first();
                if(!$find){
                    Anggota_rombel::create([
                        'anggota_rombel_id' => Str::uuid(),
                        'sekolah_id' => $anggota->sekolah_id,
                        'semester_id' => $next_semester_id,
                        'rombongan_belajar_id' => $insert->rombongan_belajar_id,
                        'peserta_didik_id' => $anggota->peserta_didik_id,
                    ]);
                }
                foreach($d->pembelajaran as $pembelajaran){
                    $find = Pembelajaran::where('rombongan_belajar_id', $insert->rombongan_belajar_id)->where('mata_pelajaran_id', $pembelajaran->mata_pelajaran_id)->first();
                    if(!$find){
                        Pembelajaran::create([
                            'pembelajaran_id' => Str::uuid(),
                            'sekolah_id' => $pembelajaran->sekolah_id,
                            'semester_id' => $next_semester_id,
                            'rombongan_belajar_id' => $insert->rombongan_belajar_id,
                            'guru_id' => $pembelajaran->guru_id,
                            'mata_pelajaran_id' => $pembelajaran->mata_pelajaran_id,
                            'nama_mata_pelajaran' => $pembelajaran->nama_mata_pelajaran,
                            'last_sync' => now(),
                            'kelompok_id' => $pembelajaran->kelompok_id,
                            'no_urut' => $pembelajaran->no_urut,
                        ]);
                    }
                }
            }
        }
        Rombel_trigger::updateOrCreate([
            'id_rombel_lama' => request()->rombongan_belajar_id,
            'id_rombel_baru' => $insert->rombongan_belajar_id,
        ]);
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Rombongan Belajar berhasil dilanjutkan semester',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Rombongan Belajar gagal dilanjutkan semester. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function get_data(){
        if(request()->data == 'agama'){
            $data = Agama::get();
        } elseif(request()->data == 'jurusan'){
            $data = Jurusan_sp::where('semester_id', semester_id())->orderBy('nama_jurusan_sp')->get();
        } elseif(request()->data == 'kurikulum'){
            $data = Kurikulum::orderBy('kurikulum_id')->get();
        } elseif(request()->data == 'mapel'){
            $data = Mata_pelajaran::where(function($query){
                $query->whereJsonContains('tingkat', [1 => request()->tingkat]);
                $query->orWhereJsonContains('tingkat', [2 => request()->tingkat]);
                $query->orWhereJsonContains('tingkat', [3 => request()->tingkat]);
                $query->orWhereJsonContains('tingkat', [4 => request()->tingkat]);
            })->with(['pembelajaran' => function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            }])->orderBy('mata_pelajaran_id')->get();
            //$data = Mata_pelajaran::where('tingkat', request()->tingkat)->orderBy('mata_pelajaran_id')->get();
            //->where('preferences->dining->meal', 'salad')
        } elseif(request()->data == 'rombel'){
            if(request()->rombongan_belajar_id){
                $data = Rombongan_belajar::find(request()->rombongan_belajar_id);
            }
            if(request()->guru_id){
                $data = Rombongan_belajar::where('guru_id', request()->guru_id)->where('semester_id', request()->semester_id)->first();
            }
        } elseif(request()->data == 'guru'){
            $data = Guru::select('guru_id', 'nama', 'tanggal_lahir')->where($this->kondisi())->orderBy('nama')->get();
        } elseif(request()->data == 'pd_akhir'){
            $data = Peserta_didik::where(function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereIn('tingkat', [12, 13]);
                    $query->where('rombongan_belajar.semester_id', semester_id());
                });
            })->with([
                'keluar' => function($query){
                    $query->where('semester_id', semester_id());
                },
                'kelas' => function($query){
                    $query->whereIn('tingkat', [12, 13]);
                    $query->where('rombongan_belajar.semester_id', semester_id());
                }
            ])->orderBy('nama')->get();
        } elseif(request()->data == 'pd_rombel'){
            $data = Peserta_didik::where(function($query){
                $query->whereHas('kelas', function($query){
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                    $query->where('rombongan_belajar.semester_id', semester_id());
                });
            })->with([
                'kenaikan_kelas' => function($query){
                    $query->where('semester_id', semester_id());
                },
                'kelas' => function($query){
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                    $query->where('rombongan_belajar.semester_id', semester_id());
                },
                'anggota_rombel' => function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                        $query->where('semester_id', semester_id());
                    });
                }
            ])->orderBy('nama')->get();
        } elseif(request()->data == 'jadwal'){
            $data = [
                'jadwal' => Jadwal::with(['jam.jadwal.pembelajaran'])->where('rombongan_belajar_id', request()->rombongan_belajar_id)->get(),
                'pembelajaran' => Pembelajaran::withCount('jam')->where('rombongan_belajar_id', request()->rombongan_belajar_id)->get(),    
            ];
        } elseif(request()->data == 'kelompok'){
            $data = [
                'kelompok' => Kelompok::find(request()->kelompok_id),
                'data_kurikulum' => Kurikulum::get(),    
            ];
        } else {
            $data = [];
        }
        return response()->json($data);
    }
    public function update_data(){
        if(request()->data == 'guru'){
            return $this->update_guru();
        }
        if(request()->data == 'pd'){
            return $this->update_pd();
        }
        if(request()->data == 'jurusan'){
            return $this->update_jurusan();
        }
        if(request()->data == 'rombel'){
            return $this->update_rombel();
        }
        if(request()->data == 'mapel'){
            return $this->updateMapel();
        }
        if(request()->data == 'kktp'){
            return $this->updateKktp();
        }
        if(request()->data == 'kelompok'){
            return $this->updateKelompok();
        }
        if(request()->data == 'edit-bk'){
            return $this->updateKelasBk();
        }
        if(request()->data == 'ekskul'){
            return $this->updateEkskul();
        }
        if(request()->data == 'dudi'){
            return $this->updateDudi();
        }
        return response()->json([
            'success' => FALSE,
            'errors' => 'Query tidak ditemukan',
        ]);
    }
    private function updateKelasBk(){
        $validator = Validator::make(request()->all(), 
            [
                'rombongan_belajar_id' => ['required'],
            ],
            [
                'rombongan_belajar_id.required' => 'Jurusan tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        Kelas_bk::whereNotIn('rombongan_belajar_id', request()->rombongan_belajar_id)->where(function($query){
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
        })->delete();
        $data = [
            'success' => TRUE,
            'errors' => NULL,
            'icon' => 'success',
            'text' => 'Kelas BK berhasil diperbaharui',
            'title' => 'Berhasil',
        ];
        return response()->json($data);
    }
    private function updateMapel(){
        $validator = Validator::make(request()->all(), 
            [
                'nama' => ['required'],
                'tingkat' => ['required'],
                'jurusan_sp_id' => ['required'],
                'jenis' => ['required'],
            ],
            [
                'nama.required' => 'Nama Mata Pelajaran tidak boleh kosong',
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
                'jurusan_sp_id.required' => 'Jurusan tidak boleh kosong',
                'jenis.required' => 'Jenis Mapel tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $data = Mata_pelajaran::find(request()->mata_pelajaran_id);
        $data->nama = request()->nama;
        $data->tingkat = array_filter(array_values(request()->tingkat));
        $data->jenis = request()->jenis;
        if($data->save()){
            Pembelajaran::where('mata_pelajaran_id', request()->mata_pelajaran_id)->update(['nama_mata_pelajaran' => request()->nama]);
            Mapel_tingkat::where('mata_pelajaran_id', request()->mata_pelajaran_id)->delete();
            foreach(array_filter(array_values(request()->tingkat)) as $tingkat){
                foreach(array_filter(array_values(request()->jurusan_sp_id)) as $jurusan_sp_id){
                    Mapel_tingkat::updateOrCreate([
                        'mata_pelajaran_id' => request()->mata_pelajaran_id,
                        'tingkat' => $tingkat,
                        'jurusan_sp_id' => $jurusan_sp_id
                    ]);
                }
            }
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data Mata Pelajaran berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data Pelajaran gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function updateKktp(){
        $validator = Validator::make(request()->all(), 
            [
                'kktp' => ['required'],
            ],
            [
                'kktp.required' => 'KKTP tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $data = Mata_pelajaran::find(request()->mata_pelajaran_id);
        $data->kktp = request()->kktp;
        if($data->save()){
            Pembelajaran::where('mata_pelajaran_id', request()->mata_pelajaran_id)->where('semester_id', semester_id())->update(['kktp' => request()->kktp]);
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data Mata Pelajaran berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data Pelajaran gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function updateKelompok(){
        $validator = Validator::make(request()->all(), 
            [
                'nama_kelompok' => ['required'],
                //'kurikulum_id' => ['required'],
            ],
            [
                'nama_kelompok.required' => 'Nama Mata Pelajaran tidak boleh kosong',
                //'kurikulum_id.required' => 'Kurikulum tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $data = Kelompok::find(request()->id);
        $data->nama_kelompok = request()->nama_kelompok;
        $data->kurikulum = (request()->kurikulum_id) ?? 0;
        if($data->save()){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data Kelompok Mapel berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data Kelompok Mapel gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function updateEkskul(){
        $validator = Validator::make(request()->all(), 
            [
                'nama' => ['required'],
                'guru_id' => ['required'],
                'status' => ['required'],
            ],
            [
                'nama.required' => 'Nama Ekstrakurikuler tidak boleh kosong',
                'guru_id.required' => 'Guru Pembina tidak boleh kosong',
                'status.required' => 'Status tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $data = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $data->guru_id = request()->guru_id;
        $data->nama = request()->nama;
        $data->status = request()->status;
        $data->nomor_sertifikat = request()->nomor_sertifikat;
        if($data->save()){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data Ekstrakurikuler berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data Ekstrakurikuler gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function updateDudi(){
        $validator = Validator::make(request()->all(), 
            [
                'nama' => ['required'],
                'pimpinan' => ['required'],
                'nama_bidang_usaha' => ['required'],
                'alamat_jalan' => ['required'],
            ],
            [
                'nama.required' => 'Nama DUDI tidak boleh kosong',
                'pimpinan.required' => 'Nama Pimpinan DUDI tidak boleh kosong',
                'nama_bidang_usaha.required' => 'Nama Bidang Usaha tidak boleh kosong',
                'alamat_jalan.required' => 'Alamat tidak boleh kosong',
                
            ]        
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $data = Dudi::find(request()->dudi_id);
        $data->nama = request()->nama;
        $data->pimpinan = request()->pimpinan;
        $data->nama_bidang_usaha = request()->nama_bidang_usaha;
        $data->alamat_jalan = request()->alamat_jalan;
        if($data->save()){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data DUDI berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data DUDI gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function update_rombel(){
        $validator = Validator::make(request()->all(), 
            [
                'nama' => ['required'],
                'tingkat' => ['required'],
                'kurikulum_id' => ['required'],
                'jurusan_sp_id' => ['required'],
                'guru_id' => ['required'],
            ],
            [
                'nama.required' => 'Nama Kelas tidak boleh kosong',
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
                'kurikulum_id.required' => 'Kurikulum tidak boleh kosong',
                'jurusan_sp_id.required' => 'Jurusan tidak boleh kosong',
                'guru_id.required' => 'Ketua Jurusan tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $kur = Kurikulum::find(request()->kurikulum_id);
        $data = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $data->guru_id = request()->guru_id;
        $data->jurusan_sp_id = request()->jurusan_sp_id;
        $data->kurikulum_id = request()->kurikulum_id;
        $data->kurikulum = $kur->nama;
        $data->nama = request()->nama;
        $data->tingkat = request()->tingkat;
        if($data->save()){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data Kelas berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data Kelas gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function update_jurusan(){
        $validator = Validator::make(request()->all(), 
            [
                'jurusan_sp_id' => ['required'],
                'nama_jurusan_sp' => ['required'],
                'alias' => ['required', Rule::unique('jurusan_sp')->ignore(request()->jurusan_sp_id, 'jurusan_sp_id'),],
                'guru_id' => ['required'],
            ],
            [
                'jurusan_sp_id.required' => 'ID Jurusan tidak boleh kosong',
                'nama_jurusan_sp.required' => 'Nama Jurusan tidak boleh kosong',
                'alias.required' => 'Nama Singkatan tidak boleh kosong',
                'alias.unique' => 'Nama Singkatan terdeteksi existing',
                'guru_id.required' => 'Tempat Lahir tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $find = Jurusan_sp::find(request()->jurusan_sp_id);
        $find->nama_konsentrasi = request()->nama_konsentrasi;
        $find->nama_jurusan_sp = request()->nama_jurusan_sp;
        $find->alias = request()->alias;
        $find->guru_id = request()->guru_id;
        if($find->save()){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data Jurusan berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data Jurusan gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function update_pd(){
        $validator = Validator::make(request()->all(), 
            [
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore(request()->peserta_didik_id, 'peserta_didik_id'),
                ],
                'peserta_didik_id' => ['required'],
                /*'nama' => ['required'],
                'nik' => [Rule::unique('peserta_didik')->ignore(request()->peserta_didik_id, 'peserta_didik_id'),],
                'tempat_lahir' => ['required'],
                'tanggal_lahir' => 'required|date',
                //'agama_id' => ['required'],
                'alamat_jalan' => ['required'],
                //'rt' => ['required'],
                //'rw' => ['required'],
                //'jenis_kelamin' => ['required'],
                //'provinsi_id' => ['required'],
                //'kabupaten_id' => ['required'],
                //'kecamatan_id' => ['required'],
                //'desa_id' => ['required'],
                'no_hp' => ['required'],
                //'sekolah_asal' => ['required'],
                //'diterima' => ['required', 'date'],
                //'diterima_kelas' => ['required'],
                //'nama_ayah' => ['required'],
                //'nama_ibu' => ['required'],
                //'kerja_ayah' => ['required'],
                //'kerja_ibu' => ['required'],*/
                'cita' => ['required'],
            ],
            [
                'peserta_didik_id.required' => 'ID Peserta Didik tidak boleh kosong',
                'nama.required' => 'Nama Lengkap tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email telah terdaftar',
                /*'nik.required' => 'NIK tidak boleh kosong',
                'nik.unique' => 'NIK terdektsi existing',
                'tempat_lahir.required' => 'Tempat Lahir tidak boleh kosong',
                'tanggal_lahir.required' => 'Tanggal Lahir tidak boleh kosong',
                'tanggal_lahir.date' => 'Tanggal Lahir tidak valid',
                'agama_id.required' => 'Agama tidak boleh kosong',
                'alamat_jalan.required' => 'Alamat tidak boleh kosong',
                'rt.required' => 'RT tidak boleh kosong',
                'rw.required' => 'RW tidak boleh kosong',
                'jenis_kelamin.required' => 'Jenis Kelamin tidak boleh kosong',
                'provinsi_id.required' => 'Provinsi tidak boleh kosong',
                'kabupaten_id.required' => 'Kabupaten/Kota tidak boleh kosong',
                'kecamatan_id.required' => 'Kecamatan tidak boleh kosong',
                'desa_id.required' => 'Desa/Kelurahan tidak boleh kosong',
                'no_hp.required' => 'Nomor HP tidak boleh kosong',
                'sekolah_asal.required' => 'Sekolah Asal tidak boleh kosong',
                'diterima.required' => 'Tanggal Diterima tidak boleh kosong',
                'diterima.date' => 'Tanggal Diterima tanggal tidak valid',
                'diterima_kelas.required' => 'Diterima Dikelas tidak boleh kosong',
                'nama_ayah.required' => 'Nama Ayah tidak boleh kosong',
                'nama_ibu.required' => 'Nama Ibu tidak boleh kosong',
                'kerja_ayah.required' => 'Pekerjaan Ayah tidak boleh kosong',
                'kerja_ibu.required' => 'Pekerjaan Ibu tidak boleh kosong',*/
                'cita.required' => 'Cita-cita tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $find = Peserta_didik::find(request()->peserta_didik_id);
        $find->nama = request()->nama;
        $find->nisn = request()->nisn;
        $find->nik = request()->nik;
        $find->no_induk = request()->no_induk;
        $find->tempat_lahir = request()->tempat_lahir;
        $find->tanggal_lahir = request()->tanggal_lahir;
        $find->agama_id = request()->agama_id;
        $find->alamat_jalan = request()->alamat_jalan;
        $find->rt = request()->rt;
        $find->rw = request()->rw;
        $find->email = request()->email;
        $find->jenis_kelamin = request()->jenis_kelamin;
        $find->provinsi_id = request()->provinsi_id;
        $find->kabupaten_id = request()->kabupaten_id;
        $find->kecamatan_id = request()->kecamatan_id;
        $find->desa_id = request()->desa_id;
        $find->no_hp = request()->no_hp;
        $find->sekolah_asal = request()->sekolah_asal;
        $find->diterima = request()->diterima;
        $find->diterima_kelas = request()->diterima_kelas;
        $find->nama_ayah = request()->nama_ayah;
        $find->nama_ibu = request()->nama_ibu;
        $find->kerja_ayah = request()->kerja_ayah;
        $find->kerja_ibu = request()->kerja_ibu;
        $find->cita = request()->cita;
        /*if(request()->photo){
            $photo = request()->photo->store('public/images');
            $find->photo = basename($photo);
        }*/
        if($find->save()){
            $user = User::find(request()->user_id);
            $user->name = request()->nama;
            $user->email = request()->email;
            $user->whatsapp = request()->no_hp;
            $user->save();
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data Peserta Didik berhasil diperbaharui!',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data Peserta Didik gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function update_guru(){
        $validator = Validator::make(request()->all(), 
            [
                'guru_id' => ['required'],
                'nama' => ['required'],
                'nik' => ['required', Rule::unique('guru')->ignore(request()->guru_id, 'guru_id'),],
                'nuptk' => [Rule::unique('guru')->ignore(request()->guru_id, 'guru_id')],
                'nip' => [Rule::unique('guru')->ignore(request()->guru_id, 'guru_id')],
                'tempat_lahir' => ['required'],
                'tanggal_lahir' => 'required|date',
                'agama_id' => ['required'],
                'alamat_jalan' => ['required'],
                'rt' => ['required'],
                'rw' => ['required'],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('guru')->ignore(request()->guru_id, 'guru_id'),
                ],
                'jenis_kelamin' => ['required'],
                'provinsi_id' => ['required'],
                'kabupaten_id' => ['required'],
                'kecamatan_id' => ['required'],
                'desa_id' => ['required'],
                'no_hp' => ['required'],
            ],
            [
                'guru_id.required' => 'ID Guru tidak boleh kosong',
                'nama.required' => 'Nama Lengkap tidak boleh kosong',
                'nik.required' => 'NIK tidak boleh kosong',
                'nik.unique' => 'NIK terdeteksi existing',
                'nuptk.unique' => 'NUPTK terdeteksi existing',
                'nip.unique' => 'NIP terdeteksi existing',
                'tempat_lahir.required' => 'Tempat Lahir tidak boleh kosong',
                'tanggal_lahir.required' => 'Tanggal Lahir tidak boleh kosong',
                'tanggal_lahir.date' => 'Tanggal Lahir tidak valid',
                'agama_id.required' => 'Agama tidak boleh kosong',
                'alamat_jalan.required' => 'Alamat tidak boleh kosong',
                'rt.required' => 'RT tidak boleh kosong',
                'rw.required' => 'RW tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email telah terdaftar',
                'jenis_kelamin.required' => 'Jenis Kelamin tidak boleh kosong',
                'provinsi_id.required' => 'Provinsi tidak boleh kosong',
                'kabupaten_id.required' => 'Kabupaten/Kota tidak boleh kosong',
                'kecamatan_id.required' => 'Kecamatan tidak boleh kosong',
                'desa_id.required' => 'Desa/Kelurahan tidak boleh kosong',
                'no_hp.required' => 'Nomor HP tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $find = Guru::with('pengguna')->find(request()->guru_id);
        $find->nama = request()->nama;
        $find->nuptk = request()->nuptk;
        $find->nip = request()->nip;
        $find->nik = request()->nik;
        $find->tempat_lahir = request()->tempat_lahir;
        $find->tanggal_lahir = request()->tanggal_lahir;
        $find->agama_id = request()->agama_id;
        $find->alamat_jalan = request()->alamat_jalan;
        $find->rt = request()->rt;
        $find->rw = request()->rw;
        $find->email = request()->email;
        $find->jenis_kelamin = request()->jenis_kelamin;
        $find->provinsi_id = request()->provinsi_id;
        $find->kabupaten_id = request()->kabupaten_id;
        $find->kecamatan_id = request()->kecamatan_id;
        $find->desa_id = request()->desa_id;
        $find->no_hp = request()->no_hp;
        if($find->save()){
            if($find->pengguna){
                $find->pengguna->email = request()->email;
                $find->pengguna->save();
            }
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Data Guru berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Data Guru gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function non_anggota(){
        $data = Peserta_didik::where(function($query){
            $query->whereDoesntHave('anggota_rombel', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('tingkat', '<>', 0);
                    $query->where('semester_id', semester_id());
                });
            });
            $query->whereDoesntHave('pd_keluar', function($query){
                $query->where('semester_id', semester_id());
            });
        })->when(request()->filter_nama, function($query) {
            $query->where('nama', 'ilike', '%'.request()->filter_nama.'%');
        })->orderBy('nama')->get();
        return response()->json($data);
    }
    public function non_anggota_ekskul(){
        $data = Peserta_didik::where(function($query){
            $query->whereDoesntHave('anggota_rombel', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('tingkat', 0);
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                    $query->where('semester_id', semester_id());
                });
            });
            $query->whereDoesntHave('pd_keluar', function($query){
                $query->where('semester_id', semester_id());
            });
        })->withWhereHas('kelas', function($query){
            $query->where('rombongan_belajar.semester_id', semester_id());
        })->when(request()->filter_nama, function($query) {
            $query->where('nama', 'ilike', '%'.request()->filter_nama.'%');
        })->orderBy('nama')->get();
        return response()->json($data);
    }
    public function set_anggota(){
        if(request()->data == 'masukkan'){
            $insert = Anggota_rombel::updateOrCreate([
                'peserta_didik_id' => request()->peserta_didik_id,
                'rombongan_belajar_id' => request()->rombongan_belajar_id,
                'sekolah_id' => sekolah_id(),
                'semester_id' => semester_id(),
            ]);
        } else {
            $insert = Anggota_rombel::where(function($query){
                $query->where('peserta_didik_id', request()->peserta_didik_id);
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->delete();
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Peserta Didik berhasil di'. request()->aksi,
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Peserta Didik gagal di'. request()->aksi .'. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function proses_kelulusan(){
        $jenis_keluar = $this->jenis_keluar('Lulus');
        $insert = 0;
        $pd_id = [];
        foreach(request()->data_siswa as $peserta_didik_id){
            $pd_id[] = $peserta_didik_id;
            $pd_keluar = Pd_keluar::updateOrCreate(
                [
                    'peserta_didik_id' => $peserta_didik_id,
                    'sekolah_id' => sekolah_id(),
                    'semester_id' => semester_id(),
                ],
                [
                    'jenis_keluar_id' => $jenis_keluar->id,
                ]
            );
            if($pd_keluar){
                $insert++;
            }
        }
        if($pd_id){
            Pd_keluar::where(function($query) use ($pd_id, $jenis_keluar){
                $query->whereNotIn('peserta_didik_id', $pd_id);
                $query->where('sekolah_id', sekolah_id());
                $query->where('semester_id', semester_id());
                $query->where('jenis_keluar_id', $jenis_keluar->id);
            })->delete();
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Proses Kelulusan Bersama berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Proses Kelulusan Bersama gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function jenis_keluar($nama){
        return Jenis_keluar::where('nama', $nama)->first();
    }
    public function info_kelulusan(){
        $validator = Validator::make(request()->all(), 
            [
                'nisn' => ['required'],
            ],
            [
                'nisn.required' => 'NISN tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $data = [
            'success' => TRUE,
            'errors' => NULL,
            'nisn' => request()->nisn,
            'pd' => Peserta_didik::where(function($query){
                $query->where('nisn', request()->nisn);
                $query->whereHas('keluar', function($query){
                    $query->where('semester_id', semester_id());
                });
            })->with([
                'keluar' => function($query){
                    $query->where('semester_id', semester_id());
                },
                'kelas' => function($query){
                    $query->whereIn('tingkat', [12, 13]);
                    $query->where('rombongan_belajar.semester_id', semester_id());
                }
            ])->first(),
        ];
        return response()->json($data);
    }
    public function prepare_data(){
        $rombel = Rombongan_belajar::where(function($query){
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
        })->first();
        $data = [
            'semester_id' => request()->semester_id,
            'rombongan_belajar_id' => ($rombel) ? $rombel->rombongan_belajar_id : NULL,
            'bulan' => Str::padLeft(request()->bulan, 2, 0),
            'request' => request()->all(),
        ];
        return response()->json($data);
    }
    public function simpan_data(){
        $data = [
            'icon' => 'error',
            'text' => 'Query simpan tidak ditemukan!',
            'title' => 'Gagal',
        ];
        if(request()->data == 'kenaikan'){
            $insert = 0;
            foreach(request()->status_kenaikan as $anggota_rombel_id => $status){
                $naik = Kenaikan_kelas::updateOrCreate(
                    [
                        'sekolah_id' => sekolah_id(),
                        'anggota_rombel_id' => $anggota_rombel_id,
                    ],
                    [
                        'nama_kelas' => request()->nama_kelas[$anggota_rombel_id],
                        'status' => $status,
                    ]
                );
                if($naik){
                    $insert++;
                }
            }
            if($insert){
                $data = [
                    'icon' => 'success',
                    'text' => 'Proses Kenaikan Kelas berhasil disimpan',
                    'title' => 'Berhasil',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'text' => 'Proses Kenaikan Kelas Bersama gagal disimpan. Silahkan coba beberapa saat lagi!',
                    'title' => 'Gagal',
                ];
            }
        }
        return response()->json($data);        
    }
    public function ekstrakurikuler(){
        $data = Rombongan_belajar::with(['wali_kelas'])->where(function($query){
            $query->where('tingkat', 0);
            $query->where('semester_id', semester_id());
        })->withCount('anggota_rombel')->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
            $query->where('semester_id', semester_id());
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    public function capaian_pembelajaran(){
        $data = Capaian_pembelajaran::withWhereHas('pembelajaran', function($query){
            $query->where('semester_id', request()->semester_id);
        })->withCount('tp')->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('deskripsi', 'ilike', '%'.request()->q.'%');
            $query->orWhereHas('pembelajaran', function($query){
                $query->where('semester_id', request()->semester_id);
                $query->where('nama_mata_pelajaran', 'ilike', '%'.request()->q.'%');
            });
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_pembelajaran(){
        $data = Mata_pelajaran::whereHas('mapel_tingkat', function($query){
            $query->where('tingkat', request()->tingkat);
        })->whereHas('pembelajaran', function($query){
            $query->where('semester_id', request()->semester_id);
        })->orderBy('mata_pelajaran_id')->get();
        return response()->json($data);
    }
    public function add_cp(){
        $insert = NULL;
        $validator = Validator::make(request()->all(), 
            [
                'tingkat' => ['required'],
                'mata_pelajaran_id' => ['required'],
                'deskripsi' => ['required'],
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
                'mata_pelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong',
                'deskripsi.required' => 'Deskripsi Capaian Pembelajaran tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $pembelajaran = Pembelajaran::where('mata_pelajaran_id', request()->mata_pelajaran_id)->whereHas('rombongan_belajar', function($query){
            $query->where('semester_id', request()->semester_id);
            $query->where('tingkat', request()->tingkat);
        })->get();
        foreach($pembelajaran as $mapel){
            $insert = Capaian_pembelajaran::create([
                'deskripsi' => request()->deskripsi,
                'guru_id' => $mapel->guru_id,
                'mata_pelajaran_id' => request()->mata_pelajaran_id,
            ]);
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Capaian Pembelajaran berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Capaian Pembelajaran gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function update_cp(){
        $insert = NULL;
        $validator = Validator::make(request()->all(), 
            [
                'cp_id' => ['required'],
                'deskripsi' => ['required'],
            ],
            [
                'cp_id.required' => 'ID Capaian Pembelajaran tidak ditemukan',
                'deskripsi.required' => 'Deskripsi Capaian Pembelajaran tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $cp = Capaian_pembelajaran::find(request()->cp_id);
        $cp->deskripsi = request()->deskripsi;
        if($cp->save()){
            $data = [
                'icon' => 'success',
                'text' => 'Capaian Pembelajaran berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Capaian Pembelajaran gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
}
