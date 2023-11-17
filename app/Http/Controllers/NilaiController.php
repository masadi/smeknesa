<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use App\Models\Capaian_pembelajaran;
use App\Models\Tujuan_pembelajaran;
use App\Models\Nilai;
use App\Models\Peserta_didik;
use App\Models\Jenis_penilaian;
use App\Models\Penilaian;
use App\Models\Sekolah;
use App\Models\Deskripsi_mapel;
use App\Models\Jurusan_sp;
use Validator;

class NilaiController extends Controller
{
    public function index(){
        /*$data = Pembelajaran::withCount([
            'cp' => function($query){
                //$query->where('guru_id', loggedUser()->guru_id);
                $query->has('nilai_tp');
            },
            'anggota_rombel' => function($query){
                $query->has('nilai_satuan');
                //$query->groupBy('anggota_rombel.anggota_rombel_id');
                //$query->groupBy('anggota_rombel.rombongan_belajar_id');
                //$query->select('anggota_rombel.anggota_rombel_id', 'anggota_rombel.rombongan_belajar_id');
            }
        ])->where($this->kondisiMapel())->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama_mata_pelajaran', 'ilike', '%'.request()->q.'%');
            $query->where($this->kondisiMapel());
        })
        ->when(request()->tingkat, function($query) {
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('tingkat', request()->tingkat);
            });
            $query->where($this->kondisiMapel());
        })
        ->when(request()->jurusan_sp_id, function($query) {
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('jurusan_sp_id', request()->jurusan_sp_id);
            });
            $query->where($this->kondisiMapel());
        })
        ->when(request()->rombongan_belajar_id, function($query) {
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })
        ->paginate(request()->per_page);*/
        $data = Penilaian::with('jenis_penilaian')->withCount('anggota_rombel')->withWhereHas('pembelajaran', function($query){
            $query->where($this->kondisiMapel());
            $query->with('rombongan_belajar');
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
            $query->whereHas('pembelajaran', function($query){
                $query->where($this->kondisiMapel());
            });
            $query->orWhereHas('pembelajaran', function($query){
                $query->where('nama_mata_pelajaran', 'ilike', '%'.request()->q.'%');
                $query->where($this->kondisiMapel());
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    private function kondisiCp(){
        return function($query){
            $query->whereHas('pembelajaran', function($query){
                $query->where('semester_id', semester_id());
                $query->where('guru_id', loggedUser()->guru_id);
            });
        };
    }
    public function list_cp(){
        $data = Capaian_pembelajaran::where(function($query){
            $query->where('guru_id', loggedUser()->guru_id);
        })->withCount(['tp'])->withWhereHas('pembelajaran', function($query){
            $query->whereHas('mata_pelajaran', function($query){
                $query->where('jenis', 'Umum');
            });
            $query->where('semester_id', semester_id());
            if(loggedUser()->hasRole('pengajar', periode_aktif())){
                $query->where('guru_id', loggedUser()->guru_id);
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('deskripsi', 'ilike', '%'.request()->q.'%');
            $query->where($this->kondisiCp());
            $query->orWhereHas('pembelajaran', function($query){
                $query->where('nama_mata_pelajaran', 'ilike', '%'.request()->q.'%');
            });
            $query->where($this->kondisiCp());
        })
        ->when(request()->tingkat, function($query) {
            $query->whereHas('pembelajaran', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('tingkat', request()->tingkat);
                });
            });
            $query->where($this->kondisiCp());
        })
        ->when(request()->jurusan_sp_id, function($query) {
            $query->whereHas('pembelajaran', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jurusan_sp_id', request()->jurusan_sp_id);
                });
            });
            $query->where($this->kondisiCp());
        })
        ->when(request()->rombongan_belajar_id, function($query) {
            $query->whereHas('pembelajaran', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            });
            $query->where($this->kondisiCp());
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    private function kondisiMapel(){
        return function($query){
            $query->has('nilai');
            if(loggedUser()->hasRole('pengajar', periode_aktif())){
                if(!loggedUser()->hasRole('wakakur', periode_aktif())){
                    $query->where('guru_id', loggedUser()->guru_id);
                }
            }
        };
    }
    public function add_cp(Request $request){
        $request['desk_tp'] = json_decode($request['desk_tp'], true);
        $request->validate(
            [
                'mata_pelajaran_id' => 'required',
                'cp_id' => 'required',
                //'desk_tp.*'=> 'required',
            ],
            [
                'mata_pelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong!',
                'cp_id.required' => 'Capaian Pembelajaran tidak boleh kosong!',
                //'desk_tp.*.required' => 'Tujuan Pembelajaran tidak boleh kosong!',
            ]
        );
        /*request()->validate(
            [
                'mata_pelajaran_id' => 'required',
                'cp_id' => 'required',
                'desk_tp.*' => 'required',
            ],
            [
                'mata_pelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong!',
                'cp_id.required' => 'Capaian Pembelajaran tidak boleh kosong!',
                'desk_tp.*.required' => 'Tujuan Pembelajaran tidak boleh kosong!',
            ]
        );*/
        foreach(array_filter($request['desk_tp']) as $tp){
            Tujuan_pembelajaran::create([
                'cp_id' => request()->cp_id,
                'deskripsi' => $tp,
            ]);
        }
        $data = [
            'icon' => 'success',
            'text' => 'Data Tujuan Pembelajaran berhasil disimpan',
            'title' => 'Berhasil',
        ];
        return response()->json($data);
    }
    public function update_cp(){
        request()->validate(
            [
                'desk_cp' => 'required',
            ],
            [
                'desk_cp.required' => 'Capaian Pembelajaran tidak boleh kosong!',
            ]
        );
        $cp = Capaian_pembelajaran::find(request()->cp_id);
        $cp->deskripsi = request()->desk_cp;
        if($cp->save()){
            $data = [
                'icon' => 'success',
                'text' => 'Data CP berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Data CP gagal diperbaharui. Silahkan coba beberapa saat lagi',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function update_tp(){
        request()->validate(
            [
                'deskripsi' => 'required',
            ],
            [
                'deskripsi.required' => 'Deskripsi Tujuan Pembelajaran tidak boleh kosong!',
            ]
        );
        $tp = Tujuan_pembelajaran::find(request()->tp_id);
        $tp->deskripsi = request()->deskripsi;
        if($tp->save()){
            $data = [
                'icon' => 'success',
                'text' => 'Data TP berhasil diperbaharui',
                'title' => 'Berhasil',
                'cp' => Capaian_pembelajaran::find(request()->cp_id),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Data TP gagal diperbaharui. Silahkan coba beberapa saat lagi',
                'title' => 'Gagal',
                'cp' => Capaian_pembelajaran::find(request()->cp_id),
            ];
        }
        return response()->json($data);
    }
    public function simpan(){
        request()->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'pembelajaran_id' => 'required',
                'jenis_id' => 'required',
                //'cp_id' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Kelas tidak boleh kosong!',
                'pembelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong!',
                'jenis_id.required' => 'Jenis Penilaian tidak boleh kosong!',
                //'cp_id.required' => 'Capaian Pembelajaran tidak boleh kosong!',
            ]
        );
        /*$data = [
            'icon' => 'success',
            'text' => 'Nilai berhasil disimpan',
            'title' => 'Berhasil',
            'request' => request()->all(),
        ];
        return response()->json($data);*/
        $set_nilai = [];
        foreach(request()->angka as $key => $nilai){
            $collection = Str::of($key)->explode('#');
            $tp_id = $collection->first();
            $anggota_rombel_id = $collection->last();
            $set_nilai[$anggota_rombel_id][$tp_id] = $nilai;
        }
        $asesmen = [];
        foreach(request()->nama_asesmen as $key => $nama_asesmen){
            if($nama_asesmen || request()->cp_id){
                $asesmen[$key] = Penilaian::updateOrCreate([
                    'jenis_penilaian_id' => request()->jenis_id,
                    'pembelajaran_id' => request()->pembelajaran_id,
                    'nama' => $nama_asesmen,
                ]);
            }
        }
        $nama_penilaian = NULL;
        foreach($set_nilai as $anggota_rombel_id => $data_tp){
            foreach($data_tp as $tp_id => $nilai){
                if($nilai){
                    if(request()->cp_id){
                        $penilaian = Penilaian::updateOrCreate([
                            'jenis_penilaian_id' => request()->jenis_id,
                            'pembelajaran_id' => request()->pembelajaran_id,
                            'nama' => $tp_id,
                        ]);
                        Nilai::updateOrCreate(
                            [
                                'anggota_rombel_id' => $anggota_rombel_id,
                                'tp_id' => $tp_id,
                                'penilaian_id' => $penilaian->penilaian_id,
                                'pembelajaran_id' => request()->pembelajaran_id,
                                'jenis_penilaian_id' => request()->jenis_id,
                            ],
                            [
                                'angka' => $nilai
                            ]
                        );
                    } else {
                        Nilai::updateOrCreate(
                            [
                                'anggota_rombel_id' => $anggota_rombel_id,
                                'tp_id' => NULL,
                                'penilaian_id' => $asesmen[$tp_id]->penilaian_id,
                                'pembelajaran_id' => request()->pembelajaran_id,
                                'jenis_penilaian_id' => request()->jenis_id,
                            ],
                            [
                                'angka' => $nilai
                            ]
                        );
                    }
                }
            }
        }
        $data = [
            'icon' => 'success',
            'text' => 'Nilai berhasil disimpan',
            'title' => 'Berhasil',
        ];
        return response()->json($data);
    }
    public function rekap(){
        $data_tp = [];
        $diagnosis = [];
        $sumatif = [];
        if(request()->is_pd){
            $data = Pembelajaran::where(function($query){
                $query->where('semester_id', semester_id());
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                $query->whereHas('rombongan_belajar', function($query){
                    $query->whereHas('anggota_rombel', function($query){
                        $query->where('peserta_didik_id', loggedUser()->peserta_didik_id);
                    });
                });
            })->withAvg([
                'all_nilai as nilai_formatif' => function($query){
                    $query->where('jenis_penilaian_id', 2);
                    $query->whereHas('pd', function($query){
                        $query->where('anggota_rombel.peserta_didik_id', loggedUser()->peserta_didik_id);
                        $query->where('semester_id', semester_id());
                    });
                },
                'all_nilai as nilai_sumatif' => function($query){
                    $query->where('jenis_penilaian_id', 3);
                    $query->whereHas('pd', function($query){
                        $query->where('anggota_rombel.peserta_didik_id', loggedUser()->peserta_didik_id);
                        $query->where('semester_id', semester_id());
                    });
                },
            ], 'angka')->orderBy('mata_pelajaran_id')->get();
        } else {
            $data = Peserta_didik::with(['nilai' => function($query){
                $query->where('pembelajaran_id', request()->pembelajaran_id);
            }])->where(function($query){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                });
            })->orderBy('nama')->get();
            $data_tp = Tujuan_pembelajaran::where(function($query){
                $query->whereHas('cp', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('pembelajaran_id', request()->pembelajaran_id);
                    });
                    $query->where('guru_id', loggedUser()->guru_id);
                });
                $query->whereHas('nilai', function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                });
            })->orderBy('created_at')->get();
            $diagnosis = Penilaian::whereHas('jenis_penilaian', function($query){
                $query->where('nama', 'Assesment Diagnosis');
            })->whereHas('pembelajaran', function($query){
                $query->where('pembelajaran_id', request()->pembelajaran_id);
            })->whereHas('nilai', function($query){
                $query->where('pembelajaran_id', request()->pembelajaran_id);
            })->orderBy('created_at')->get();
            $sumatif = Penilaian::whereHas('jenis_penilaian', function($query){
                $query->where('nama', 'Assesment Sumatif');
            })->whereHas('pembelajaran', function($query){
                $query->where('pembelajaran_id', request()->pembelajaran_id);
            })->whereHas('nilai', function($query){
                $query->where('pembelajaran_id', request()->pembelajaran_id);
            })->orderBy('created_at')->get();
        }
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id(), 'data_tp' => $data_tp, 'diagnosis' => $diagnosis, 'sumatif' => $sumatif]);
    }
    public function detil(){
        $data = Capaian_pembelajaran::with(['nilai' => function($query){
            $query->whereHas('pd', function($query){
                $query->where('peserta_didik.peserta_didik_id', loggedUser()->peserta_didik_id);
            });
        }])->where(function($query){
            $query->where('pembelajaran_id', request()->pembelajaran_id);
        })->get();
        return response()->json($data);
    }
    public function get_cp(){
        $data = [
            'cp' => Capaian_pembelajaran::where(function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                    $query->where('guru_id', loggedUser()->guru_id);
                });
                $query->where('guru_id', loggedUser()->guru_id);
                $query->has('tp');
            })->get(),
            'jenis' => Jenis_penilaian::get(),
        ];
        return response()->json($data);
    }
    public function get_tp(){
        $data = Tujuan_pembelajaran::where('cp_id', request()->cp_id)->orderBy('created_at')->get();
        return response()->json(['data_tp' => $data]);
    }
    public function get_siswa(){
        /*$data = Peserta_didik::where(function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            });
        })->select('peserta_didik_id', 'nama', 'nisn', 'tanggal_lahir')->with(['anggota_rombel' => function($query){
        }])->get();*/
        $penilaian = Penilaian::where(function($query){
            $query->where('jenis_penilaian_id', request()->jenis_id);
            $query->where('pembelajaran_id', request()->pembelajaran_id);
        })->orderBy('created_at')->get();
        $data = [
            'siswa' => Peserta_didik::withWhereHas('anggota_rombel', function ($query) {
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                })->with(['nilai' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                    /*$query->whereHas('pembelajaran', function($query){
                        $query->where('pembelajaran_id', request()->pembelajaran_id);
                        $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                    });
                    */
                    //$query->whereDate('tanggal', Carbon::today());
                    //$query->orderBy('jam');
                    $query->where('jenis_penilaian_id', request()->jenis_id);
                }])->orderBy('nama')->get(),
            'data_tp' => Tujuan_pembelajaran::where('cp_id', request()->cp_id)->orderBy('created_at')->get(),
            'penilaian' => $penilaian,
            'jumlah_form' => ($penilaian->count()) ? $penilaian->count() : 2,
        ];
        return response()->json($data);
    }
    public function chart_jurusan(){
        $data_jurusan = Jurusan_sp::whereHas('anggota_rombel', function($query){
            $query->where('anggota_rombel.semester_id', semester_id());
        })->withCount([
            'anggota_rombel as nilai_0' => function($query){
                $query->whereHas('nilai_satuan', function($query){
                    $query->where('angka', '<', 70);
                });
            },
            'anggota_rombel as nilai_70' => function($query){
                $query->whereHas('nilai_satuan', function($query){
                    $query->whereBetween('angka', [69, 86]);
                });
            },
            'anggota_rombel as nilai_85' => function($query){
                $query->whereHas('nilai_satuan', function($query){
                    $query->where('angka', '>', 85);
                });
            },
        ])->orderBy('nama_jurusan_sp')->get();
        $nilai_0_jurusan = [];
        $nilai_70_jurusan = [];
        $nilai_85_jurusan = [];
        $nilai_0 = 0;
        $nilai_70 = 0;
        $nilai_85 = 0;
        foreach($data_jurusan as $jurusan){
            $nilai_0_jurusan[] = $jurusan->nilai_0;
            $nilai_70_jurusan[] = $jurusan->nilai_70;
            $nilai_85_jurusan[] = $jurusan->nilai_85;
        }
        $data = [
            'series' => [
                [
                'name' => '>0',
                'data' => $nilai_0_jurusan
                ], 
                [
                'name' => '>70',
                'data' => $nilai_70_jurusan
                ],
                [
                'name' => '>85',
                'data' => $nilai_85_jurusan
                ],
            ],
            'categories' => $data_jurusan->pluck('alias'),
        ];
        return response()->json($data);
    }
    public function chart(){
        $sekolah = Sekolah::withCount([
            'peserta_didik' => function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                });
            },
            'peserta_didik as pd_0' => function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                });
                $query->whereHas('nilai', function($query){
                    $query->where('angka', '<', 70);
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                });
            },
            'peserta_didik as pd_70' => function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                });
                $query->whereHas('nilai', function($query){
                    $query->whereBetween('angka', [69, 80]);
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                });
            },
            'peserta_didik as pd_80' => function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                });
                $query->whereHas('nilai', function($query){
                    $query->whereBetween('angka', [79, 90]);
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                });
            },
            'peserta_didik as pd_90' => function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                });
                $query->whereHas('nilai', function($query){
                    $query->where('angka', '>', 89);
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('semester_id', semester_id());
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                });
            },
        ])->first();
        $data = [
            'series' => [$sekolah->pd_0, $sekolah->pd_70, $sekolah->pd_80, $sekolah->pd_90],
            'labels' => ['>=0', '>=70', '>=80', '>=90'],
        ];
        return response()->json($data);
    }
    public function nilai_pd(){
        $data = Pembelajaran::withAvg([
            'nilai as rerata' => function($query){
                $query->where('jenis_penilaian_id', '<>', 1);
                $query->whereHas('pd', function($query){
                    $query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                    $query->where('anggota_rombel.semester_id', semester_id());
                });
            },
        ], 'angka')
        ->withWhereHas('pd', function($query){
            $query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
            $query->where('anggota_rombel.semester_id', semester_id());
        })
        ->with([
            'cp' => function($query){
                $query->where('guru_id', loggedUser()->guru_id);
                $query->with(['tp' => function($query){
                    $query->with(['nilai' => function($query){
                        $query->whereHas('pd', function($query){
                            $query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                            $query->where('anggota_rombel.semester_id', semester_id());
                        });
                    }]);
                    $query->whereHas('nilai', function($query){
                        $query->whereHas('pd', function($query){
                            $query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                            $query->where('anggota_rombel.semester_id', semester_id());
                        });
                    });
                }]);
            },
            'deskripsi_tercapai' => function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereHas('pd', function($query){
                        $query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                        $query->where('anggota_rombel.semester_id', semester_id());
                    });
                });
            },
            'deskripsi_belum_tercapai' => function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereHas('pd', function($query){
                        $query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                        $query->where('anggota_rombel.semester_id', semester_id());
                    });
                });
            },
        ])->where(function($query){
            $query->where('semester_id', semester_id());
            if(request()->deskripsi){
                $query->where('guru_id', loggedUser()->guru_id);
            }
            $query->whereHas('kelas', function($query){
                $query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                $query->where('anggota_rombel.semester_id', semester_id());
            });
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
        })->orderBy('no_urut')->get();
        return response()->json($data);
    }
    public function get_desc(){
        $data = Peserta_didik::where(function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            });
        })->with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->with([
                'all_nilai' => function($query){
                    $query->where('jenis_penilaian_id', 2);
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                    $query->with(['tp']);
                },
                'deskripsi_tercapai' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
                'deskripsi_belum_tercapai' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
            ]);
            $query->withAvg([
                'all_nilai as rerata' => function($query){
                    $query->where('jenis_penilaian_id', '<>', 1);
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
            ], 'angka');
        }])->orderBy('nama')->get();
        return response()->json($data);
        $data = Pembelajaran::withAvg([
            'nilai as rerata' => function($query){
                $query->where('jenis_penilaian_id', '<>', 1);
                $query->whereHas('pd', function($query){
                    //$query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                    $query->where('anggota_rombel.semester_id', semester_id());
                });
            },
        ], 'angka')
        ->withWhereHas('pd', function($query){
            //$query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
            $query->where('anggota_rombel.semester_id', semester_id());
        })
        ->with([
            'cp' => function($query){
                $query->where('guru_id', loggedUser()->guru_id);
                $query->with(['tp' => function($query){
                    $query->with(['nilai' => function($query){
                        $query->whereHas('pd', function($query){
                            //$query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                            $query->where('anggota_rombel.semester_id', semester_id());
                        });
                    }]);
                    $query->whereHas('nilai', function($query){
                        $query->whereHas('pd', function($query){
                            //$query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                            $query->where('anggota_rombel.semester_id', semester_id());
                        });
                    });
                }]);
            },
            'deskripsi_mapel' => function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereHas('pd', function($query){
                        //$query->where('anggota_rombel.anggota_rombel_id', request()->anggota_rombel_id);
                        $query->where('anggota_rombel.semester_id', semester_id());
                    });
                });
            },
        ])->find(request()->pembelajaran_id);
    }
    public function deskripsi(){
        $insert = NULL;
        foreach(request()->deskripsi_tercapai as $anggota_rombel_id => $deskripsi){
            $insert[] = Deskripsi_mapel::updateOrCreate(
                [
                    'anggota_rombel_id' => $anggota_rombel_id,
                    'pembelajaran_id' => request()->pembelajaran_id,
                    'tercapai' => 1,
                ],
                [
                    'deskripsi' => $deskripsi,
                ]
            );
        }
        foreach(request()->deskripsi_belum_tercapai as $anggota_rombel_id => $deskripsi){
            $insert[] = Deskripsi_mapel::updateOrCreate(
                [
                    'anggota_rombel_id' => $anggota_rombel_id,
                    'pembelajaran_id' => request()->pembelajaran_id,
                    'tercapai' => 0,
                ],
                [
                    'deskripsi' => $deskripsi,
                ]
            );
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Capaian Kompetensi berhasil disimpan',
                'title' => 'Berhasil',
                'insert' => $insert,
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Capaian Kompetensi gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
                'insert' => $insert,
            ];
        }
        return response()->json($data);
    }
    public function reset_deskripsi(){
        $insert = Deskripsi_mapel::where('pembelajaran_id', request()->pembelajaran_id)->delete();
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Capaian Kompetensi berhasil direset',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Capaian Kompetensi gagal direset. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    private function kondisiKenaikan(){
        return function($query){
            if(request()->status){
                $query->whereHas('kenaikan_kelas', function($query){
                    $query->where('semester_id', semester_id());
                });
            } else {
                $query->whereHas('keluar', function($query){
                    $query->where('semester_id', semester_id());
                    $query->where('nama', 'Lulus');
                });
            }
        };
    }
    private function relasi(){
        if(request()->status){
            $with = [
                'kenaikan_kelas' => function($query){
                    $query->where('semester_id', semester_id());
                },
            ];
        } else {
            $with = [
                'keluar' => function($query){
                    $query->where('semester_id', semester_id());
                },
                'kelas' => function($query){
                    $query->whereIn('tingkat', [12, 13]);
                    $query->where('rombongan_belajar.semester_id', semester_id());
                }
            ];
        }
        return $with;
    }
    public function kenaikan(){
        $data = Peserta_didik::with($this->relasi())->where($this->kondisiKenaikan())->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
            $query->where($this->kondisiKenaikan());
            $query->orWhere('nisn', 'ilike', '%'.request()->q.'%');
            $query->where($this->kondisiKenaikan());
        })
        ->when(request()->jurusan_sp_id, function($query) {
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('jurusan_sp_id', request()->jurusan_sp_id);
            });
            $query->where($this->kondisiKenaikan());
        })
        ->when(request()->rombongan_belajar_id, function($query) {
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'request' => request()->status]);        
    }
    public function detil_data(){
        $data = request()->data;
        $result = ['data' => []];
        if($data == 'nilai-mapel'){
            $result = ['data' => Penilaian::with(['jenis_penilaian'])->withCount('nilai')->where('pembelajaran_id', request()->id)->get()];
        }
        if($data == 'cp'){
            $result = ['data' => Capaian_pembelajaran::with(['tp'])->find(request()->id)];
        }
        return response()->json($result);
    }
    public function peserta_didik(){
        $data = Peserta_didik::withWhereHas('kelas', function($query){
            $query->where('rombongan_belajar.semester_id', request()->semester_id);
            $query->where('guru_id', request()->guru_id);
            $query->with('jurusan_sp');
        })->withWhereHas('anggota_rombel', function($query){
            $query->where('semester_id', request()->semester_id);
            $query->whereHas('nilai_satuan', function($query){
                $query->where('jenis_penilaian_id', 2);
                $query->where('angka', '<', 75);
            });
        })->orderBy('nama')->get();
        return response()->json(['pd' => $data, 'request' => request()->all()]);
    }
    public function hapus_penilaian(){
        $find = Penilaian::find(request()->penilaian_id);
        if($find){
            if($find->delete()){
                $data = [
                    'icon' => 'success',
                    'text' => 'Penilaian berhasil dihapus',
                    'title' => 'Berhasil',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'text' => 'Penilaian gagal dihapus. Silahkan coba beberapa saat lagi!',
                    'title' => 'Gagal',
                ];
            }
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Penilaian tidak ditemukan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function detil_nilai(){
        $penilaian = Penilaian::with('jenis_penilaian')->find(request()->id);
        $data = [
            'penilaian' => $penilaian,
            'tp' => ($penilaian->jenis_penilaian_id == 2) ? Tujuan_pembelajaran::find($penilaian->nama) : NULL,
            'data_siswa' => Peserta_didik::orderBy('nama')->withWhereHas('anggota_rombel', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->whereHas('penilaian', function($query){
                            $query->where('penilaian_id', request()->id);
                        });
                    });
                });
                $query->withWhereHas('nilai', function($query){
                    $query->where('penilaian_id', request()->id);
                });
            })->get(),
        ];
        return response()->json($data);
    }
    public function update(){
        $insert = NULL;
        foreach(request()->nilai as $anggota_rombel_id => $angka){
            $insert = Nilai::where('anggota_rombel_id', $anggota_rombel_id)->where('penilaian_id', request()->penilaian_id)->update(['angka' => $angka]);
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Nilai berhasil diperbaharui!',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Nilai gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
}
