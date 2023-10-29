<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Capaian_pembelajaran;
use App\Models\Tujuan_pembelajaran;
use App\Models\Praktik_kerja_lapangan;
use App\Models\Pd_pkl;
use App\Models\Nilai_pkl;
use App\Models\Absensi_pkl;
use App\Models\Dudi;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Peserta_didik;
use App\Models\Guru;

class PrakerinController extends Controller
{
    public function index(){
        $data = Praktik_kerja_lapangan::where(function($query){
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
            $query->has('nilai_pkl');
        })->with([
            'rombongan_belajar',
            'dudi',
            'guru'
        ])->withCount('pd_pkl')->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                $query->orWhereHas('wali_kelas', function($query){
                    $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                });
                $query->orWhereHas('jurusan_sp', function($query){
                    $query->where('nama_jurusan_sp', 'ILIKE', '%' . request()->q . '%');
                });
            });
            $query->orWhereHas('dudi', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                $query->orWhere('pimpinan', 'ILIKE', '%' . request()->q . '%');
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_pkl(){
        $data = Praktik_kerja_lapangan::where(function($query){
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
        })->orderBy('nama')->get();
        return response()->json($data);
    }
    public function get_pd_pkl(){
        $pkl = Praktik_kerja_lapangan::find(request()->pkl_id);
        $data = [
            'pkl' => $pkl,
            'data_siswa' => Peserta_didik::orderBy('nama')->withWhereHas('pd_pkl', function($query){
                $query->where('pkl_id', request()->pkl_id);
            })->with(['nilai_pkl' => function($query){
                $query->where('pkl_id', request()->pkl_id);
            }])->with(['absensi_pkl' => function($query){
                $query->where('pkl_id', request()->pkl_id);
            }])->get(),
            'data_tp' => Tujuan_pembelajaran::withWhereHas('cp', function($query) use ($pkl){
                $query->withWhereHas('pembelajaran', function($query) use ($pkl){
                    $query->where('rombongan_belajar_id', $pkl->rombongan_belajar_id);
                    $query->where('semester_id', request()->semester_id);
                    $query->where('guru_id', request()->guru_id);
                    $query->whereHas('mata_pelajaran', function($query){
                        $query->where('jenis', 'PKL');
                    });
                });
            })->orderBy('deskripsi')->get(),
        ];
        return response()->json($data);
    }
    public function list_cp(){
        $data = Capaian_pembelajaran::withCount(['tp'])->withWhereHas('pembelajaran', function($query){
            $query->where('semester_id', semester_id());
            $query->where('guru_id', loggedUser()->guru_id);
            $query->whereHas('mata_pelajaran', function($query){
                $query->where('jenis', 'PKL');
            });
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
    private function kondisiCp(){
        return function($query){
            $query->whereHas('pembelajaran', function($query){
                $query->where('semester_id', semester_id());
                $query->where('guru_id', loggedUser()->guru_id);
            });
        };
    }
    public function dudi(){
        $data = Dudi::orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->orWhere('pimpinan', 'ILIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_guru(){
        $data = Guru::whereHas('pembelajaran', function($query){
            $query->where('semester_id', request()->semester_id);
            $query->whereHas('mata_pelajaran', function($query){
                $query->where('jenis', 'PKL');
            });
        })->orderBy('nama')->get();
        return response()->json($data);
    }
    public function get_dudi(){
        $data = Dudi::orderBy('nama')->get();
        return response()->json($data);
    }
    public function get_rombel(){
        $data = Rombongan_belajar::whereHas('pembelajaran', function($query){
            $query->where('guru_id', request()->guru_id);
            $query->whereHas('mata_pelajaran', function($query){
                $query->where('jenis', 'PKL');
            });
        })->where('semester_id', request()->semester_id)->where('tingkat', 12)->orderBy('nama')->get();
        return response()->json($data);
    }
    public function get_mapel(){
        $data = Pembelajaran::where(function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->whereHas('mata_pelajaran', function($query){
                $query->where('jenis', 'PKL');
            });
        })->orderBy('mata_pelajaran_id')->get();
        return response()->json($data);
    }
    public function get_siswa(){
        $data = Peserta_didik::orderBy('nama')->whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->get();
        return response()->json($data);
    }
    public function simpan_data(){
        $insert = 0;
        $validator = Validator::make(request()->all(), 
            [
                'nama' => ['required'],
                'guru_id' => ['required'],
                'dudi_id' => ['required'],
                'instruktur' => ['required'],
                'tanggal_mulai' => ['required'],
                'tanggal_selesai' => ['required'],
                'rombongan_belajar_id' => ['required'],
                'pembelajaran_id' => ['required'],
            ],
            [
                'nama.required' => 'Judul Magang tidak boleh kosong',
                'guru_id.required' => 'Pembimbing tidak boleh kosong',
                'dudi_id.required' => 'DUDI tidak boleh kosong',
                'instruktur.required' => 'Nama Instruktur tidak boleh kosong',
                'tanggal_mulai.required' => 'Tanggal Mulai tidak boleh kosong',
                'tanggal_selesai.required' => 'Tanggal Selesai tidak boleh kosong',
                'rombongan_belajar_id.required' => 'Kelas tidak boleh kosong',
                'pembelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $pkl = Praktik_kerja_lapangan::create([
            'nama' => request()->nama,
            'guru_id' => request()->guru_id,
            'dudi_id' => request()->dudi_id,
            'instruktur' => request()->instruktur,
            'rombongan_belajar_id' => request()->rombongan_belajar_id,
            'pembelajaran_id' => request()->pembelajaran_id,
            'tanggal_mulai' => request()->tanggal_mulai,
            'tanggal_selesai' => request()->tanggal_selesai,
            'semester_id' => request()->semester_id,
        ]);
        foreach(request()->peserta_didik_id as $peserta_didik_id){
            Pd_pkl::create([
                'peserta_didik_id' => $peserta_didik_id,
                'pkl_id' => $pkl->pkl_id,
            ]);
            $insert++;
        }
        if($insert){
            $data = [
                'success' => TRUE,
                'icon' => 'success',
                'text' => 'Data Pembimbing berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => FALSE,
                'icon' => 'error',
                'text' => 'Data Pembimbing gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function update_data(){
        $insert = 0;
        $validator = Validator::make(request()->all(), 
            [
                'nama' => ['required'],
                'guru_id' => ['required'],
                'dudi_id' => ['required'],
                'instruktur' => ['required'],
                'tanggal_mulai' => ['required'],
                'tanggal_selesai' => ['required'],
                'rombongan_belajar_id' => ['required'],
                'pembelajaran_id' => ['required'],
            ],
            [
                'nama.required' => 'Judul Magang tidak boleh kosong',
                'guru_id.required' => 'Pembimbing tidak boleh kosong',
                'dudi_id.required' => 'DUDI tidak boleh kosong',
                'instruktur.required' => 'Nama Instruktur tidak boleh kosong',
                'tanggal_mulai.required' => 'Tanggal Mulai tidak boleh kosong',
                'tanggal_selesai.required' => 'Tanggal Selesai tidak boleh kosong',
                'rombongan_belajar_id.required' => 'Kelas tidak boleh kosong',
                'pembelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $pkl = Praktik_kerja_lapangan::find(request()->pkl_id);
        $pkl->nama = request()->nama;
        $pkl->guru_id = request()->guru_id;
        $pkl->dudi_id = request()->dudi_id;
        $pkl->instruktur = request()->instruktur;
        $pkl->rombongan_belajar_id = request()->rombongan_belajar_id;
        $pkl->tanggal_mulai = request()->tanggal_mulai;
        $pkl->tanggal_selesai = request()->tanggal_selesai;
        $pkl->save();
        foreach(request()->peserta_didik_id as $peserta_didik_id){
            Pd_pkl::updateOrCreate([
                'peserta_didik_id' => $peserta_didik_id,
                'pkl_id' => $pkl->pkl_id,
            ]);
            $insert++;
        }
        Pd_pkl::where('pkl_id', request()->pkl_id)->whereNotIn('peserta_didik_id', request()->peserta_didik_id)->delete();
        if($insert){
            $data = [
                'success' => TRUE,
                'icon' => 'success',
                'text' => 'Data Pembimbing berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => FALSE,
                'icon' => 'error',
                'text' => 'Data Pembimbing gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function simpan_nilai(){
        $insert = 0;
        foreach(request()->peserta_didik_id as $key => $nilai){
            $collection = Str::of($key)->explode('#');
            $tp_id = $collection->first();
            $peserta_didik_id = $collection->last();
            Nilai_pkl::updateOrCreate(
                [
                    'peserta_didik_id' => $peserta_didik_id,
                    'pkl_id' => request()->pkl_id,
                    'tp_id' => $tp_id,
                ],
                [
                    'nilai' => $nilai,
                    'deskripsi' => request()->deskripsi[$tp_id.'#'.$peserta_didik_id],
                ]
            );
            Pd_pkl::where(['peserta_didik_id' => $peserta_didik_id, 'pkl_id' => request()->pkl_id])->update(['catatan' => request()->catatan[$peserta_didik_id]]);
            $insert++;
        }
        if($insert){
            $data = [
                'success' => TRUE,
                'icon' => 'success',
                'text' => 'Data Nilai Magang berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => FALSE,
                'icon' => 'error',
                'text' => 'Data Nilai Magang gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function pembimbing(){
        $data = Praktik_kerja_lapangan::where(function($query){
            //$query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
        })->with([
            'rombongan_belajar',
            'dudi',
            'guru'
        ])->withCount('pd_pkl')->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                $query->orWhereHas('wali_kelas', function($query){
                    $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                });
                $query->orWhereHas('jurusan_sp', function($query){
                    $query->where('nama_jurusan_sp', 'ILIKE', '%' . request()->q . '%');
                });
            });
            $query->orWhereHas('dudi', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                $query->orWhere('pimpinan', 'ILIKE', '%' . request()->q . '%');
            });
            $query->orWhereHas('guru', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                $query->orWhere('nik', 'ILIKE', '%' . request()->q . '%');
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function detil(){
        /*
        ->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })
        */
        $data = [
            'data_siswa' => Peserta_didik::orderBy('nama')->withWhereHas('pd_pkl', function($query){
                $query->where('pkl_id', request()->pkl_id);
            })->get(),
            'data_dudi' => Dudi::orderBy('nama')->get(),
            'data_guru' => Guru::whereHas('pembelajaran', function($query){
                $query->where('semester_id', request()->semester_id);
                $query->whereHas('mata_pelajaran', function($query){
                    $query->where('jenis', 'PKL');
                });
            })->orderBy('nama')->get(),
            'data_rombel' => Rombongan_belajar::whereHas('pembelajaran', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->whereHas('mata_pelajaran', function($query){
                    $query->where('jenis', 'PKL');
                });
            })->where('semester_id', request()->semester_id)->where('tingkat', 12)->orderBy('nama')->get(),
            'data_pembelajaran' => Pembelajaran::where(function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->whereHas('mata_pelajaran', function($query){
                    $query->where('jenis', 'PKL');
                });
            })->orderBy('mata_pelajaran_id')->get(),
        ];
        return response()->json($data);
    }
    public function detil_nilai(){
        $pkl = Praktik_kerja_lapangan::find(request()->pkl_id);
        $data = [
            'pkl' => $pkl,
            'data_siswa' => Peserta_didik::orderBy('nama')->withWhereHas('pd_pkl', function($query){
                $query->where('pkl_id', request()->pkl_id);
            })->with(['nilai_pkl' => function($query){
                $query->where('pkl_id', request()->pkl_id);
            }])->get(),
            'data_tp' => Tujuan_pembelajaran::withWhereHas('cp', function($query) use ($pkl){
                $query->withWhereHas('pembelajaran', function($query) use ($pkl){
                    $query->where('pembelajaran_id', $pkl->pembelajaran_id);
                    /*$query->where('semester_id', $pkl->semester_id);
                    $query->where('guru_id', $pkl->guru_id);
                    $query->whereHas('mata_pelajaran', function($query){
                        $query->where('jenis', 'PKL');
                    });*/
                });
            })->orderBy('deskripsi')->get(),
        ];
        return response()->json($data);
    }
    public function absensi(){
        $data = Peserta_didik::withWhereHas('absensi_pkl', function($query){
            $query->whereHas('pkl', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', request()->semester_id);
                });
            });
        })->withWhereHas('kelas', function($query){
            $query->where('rombongan_belajar.semester_id', request()->semester_id);
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->orWhereHas('kelas', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function simpan_absensi(){
        $insert = 0;
        foreach(request()->sakit as $key => $sakit){
            Absensi_pkl::updateOrCreate(
                [
                    'peserta_didik_id' => $key,
                    'pkl_id' => request()->pkl_id,
                ],
                [
                    'sakit' => $sakit,
                    'izin' => request()->izin[$key],
                    'alpa' => request()->alpa[$key],
                ]
            );
            $insert++;
        }
        if($insert){
            $data = [
                'success' => TRUE,
                'icon' => 'success',
                'text' => 'Data Absensi Magang berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => FALSE,
                'icon' => 'error',
                'text' => 'Data Absensi Magang gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function hapus_absensi(){
        $find = Absensi_pkl::find(request()->id);
        if($find->delete()){
            $data = [
                'success' => TRUE,
                'icon' => 'success',
                'text' => 'Data Absensi Magang berhasil dihapus',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => FALSE,
                'icon' => 'error',
                'text' => 'Data Absensi Magang gagal dihapus. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function hapus_data(){
        $find = Praktik_kerja_lapangan::find(request()->id);
        if($find->delete()){
            $data = [
                'success' => TRUE,
                'icon' => 'success',
                'text' => 'Data Magang berhasil dihapus',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => FALSE,
                'icon' => 'error',
                'text' => 'Data Magang gagal dihapus. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
}
