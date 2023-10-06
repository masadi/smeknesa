<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Capaian_pembelajaran;
use App\Models\Praktik_kerja_lapangan;
use App\Models\Pd_pkl;
use App\Models\Dudi;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Guru;

class PrakerinController extends Controller
{
    public function index(){
        $data = Praktik_kerja_lapangan::where(function($query){
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
        })->with([
            'rombongan_belajar',
            'dudi'
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
                $query->orWhereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%' . request()->q . '%');
                });
            });
            $query->orWhereHas('dudi', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                $query->orwWhere('pimpinan', 'ILIKE', '%' . request()->q . '%');
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
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
    public function dudi(){
        $data = Dudi::orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->orwWhere('pimpinan', 'ILIKE', '%' . request()->q . '%');
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
                'guru_id' => ['required'],
                'dudi_id' => ['required'],
                'instruktur' => ['required'],
                'tanggal_mulai' => ['required'],
                'tanggal_selesai' => ['required'],
                'rombongan_belajar_id' => ['required'],
            ],
            [
                'guru_id.required' => 'Pembimbing tidak boleh kosong',
                'dudi_id.required' => 'DUDI tidak boleh kosong',
                'instruktur.required' => 'Nama Instruktur tidak boleh kosong',
                'tanggal_mulai.required' => 'Tanggal Mulai tidak boleh kosong',
                'tanggal_selesai.required' => 'Tanggal Selesai tidak boleh kosong',
                'rombongan_belajar_id.required' => 'Kelas tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $pkl = Praktik_kerja_lapangan::create([
           'guru_id' => request()->guru_id,
           'dudi_id' => request()->dudi_id,
           'instruktur' => request()->instruktur,
           'rombongan_belajar_id' => request()->rombongan_belajar_id,
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
                'guru_id' => ['required'],
                'dudi_id' => ['required'],
                'instruktur' => ['required'],
                'tanggal_mulai' => ['required'],
                'tanggal_selesai' => ['required'],
                'rombongan_belajar_id' => ['required'],
            ],
            [
                'guru_id.required' => 'Pembimbing tidak boleh kosong',
                'dudi_id.required' => 'DUDI tidak boleh kosong',
                'instruktur.required' => 'Nama Instruktur tidak boleh kosong',
                'tanggal_mulai.required' => 'Tanggal Mulai tidak boleh kosong',
                'tanggal_selesai.required' => 'Tanggal Selesai tidak boleh kosong',
                'rombongan_belajar_id.required' => 'Kelas tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $pkl = Praktik_kerja_lapangan::find(request()->pkl_id);
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
                $query->orWhereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%' . request()->q . '%');
                });
            });
            $query->orWhereHas('dudi', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                $query->orwWhere('pimpinan', 'ILIKE', '%' . request()->q . '%');
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function detil(){
        $data = [
            'data_siswa' => Peserta_didik::orderBy('nama')->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->with(['pd_pkl' => function($query){
                $query->where('pkl_id', request()->pkl_id);
            }])->get(),
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
        ];
        return response()->json($data);
    }
}
