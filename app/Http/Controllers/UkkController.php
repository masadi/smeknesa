<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Paket_ukk;
use App\Models\Unit_ukk;
use App\Models\Rencana_ukk;
use App\Models\Nilai_ukk;
use App\Models\Jurusan_sp;
use App\Models\Kurikulum;
use App\Models\Guru;
use App\Models\Asesor;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;

class UkkController extends Controller
{
    public function index(){
        $data = Paket_ukk::withCount('unit_ukk')->withWhereHas('jurusan_sp', function($query){
            $query->where('guru_id', request()->user()->guru_id);
        })->with(['kurikulum'])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nomor_paket', 'ilike', '%'.request()->q.'%');
            $query->orWhere('nama_paket_id', 'ilike', '%'.request()->q.'%');
            $query->orWhere('nama_paket_en', 'ilike', '%'.request()->q.'%');
            $query->orWhereHas('jurusan_sp', function($query){
                $query->where('nama_jurusan_sp', 'ilike', '%'.request()->q.'%');
            });
        })
        ->paginate(request()->per_page);
        return response()->json([
            'status' => 'success', 
            'data' => $data, 
            'semester_id' => request()->semester_id,
            'user' => request()->user(),
        ]);
    }
    public function get_jurusan(){
        $data = Jurusan_sp::where(function($query){
            $query->where('guru_id', request()->user()->guru_id);
        })->orderBy('nama_jurusan_sp')->get();
        return response()->json($data);
    }
    public function get_kurikulum(){
        $data = Kurikulum::whereHas('rombongan_belajar', function($query){
            $query->where('jurusan_sp_id', request()->jurusan_sp_id);
        })->orderBy('nama')->get();
        return response()->json($data);
    }
    public function get_paket(){
        $data = Paket_ukk::with(['jurusan_sp', 'kurikulum', 'unit_ukk'])->find(request()->paket_ukk_id);
        return response()->json($data);
    }
    public function get_paket_ukk(){
        $data = Paket_ukk::whereHas('jurusan_sp', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            });
        })->orderBy('created_at')->get();
        return response()->json($data);
    }
    public function status_ukk(){
        $find = Paket_ukk::find(request()->paket_ukk_id);
        $find->status = (request()->status) ? 0 : 1;
        $text = (request()->status) ? 'nonaktifkan' : 'aktifkan';
        if($find->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Paket UKK berhasil di '.$text,
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Paket UKK gagal di '.$text.'. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function update(){
        $find = Paket_ukk::find(request()->paket_ukk_id);
        $find->nama_paket_id = request()->nama_paket_id;
        $find->nama_paket_en = request()->nama_paket_en;
        $find->nomor_paket = request()->nomor_paket;
        $find->status = request()->status;
        $find->save();
        if(request()->has('unit_ukk')){
            foreach(request()->unit_ukk as $item){
                Unit_ukk::where('unit_ukk_id', $item['unit_ukk_id'])->update([
                    'kode_unit' => $item['kode_unit'],
                    'nama_unit' => $item['nama_unit'],
                ]);
            }
        }
        return response()->json(['icon' => 'success', 'title' => 'Success', 'text' => "Data Paket UKK berhasil diperbaharui."]);
    }
    public function destroy()
    {
        if(request()->data == 'paket-ukk'){
            $find = Paket_ukk::find(request()->id);
            $text = 'Paket UKK';
        } elseif(request()->data == 'rencana-ukk'){
            $find = Rencana_ukk::find(request()->id);
            $text = 'Rencana Penilaian UKK';
        } else {
            $find = Unit_ukk::find(request()->id);
            $text = 'Unit UKK';
        }
        if($find ){
            $find->delete();
            return response()->json(['icon' => 'success', 'title' => 'Berhasil', 'text' => "$text berhasil dihapus."]);
        }else{
            return response()->json(['icon' => 'error', 'title' => 'Gagal', 'text' => "$text tidak ditemukan."]);
        }
    }
    public function rencana(){
        $data = Rencana_ukk::withWhereHas('paket_ukk', function($query){
            $query->withWhereHas('jurusan_sp', function($query){
                $query->where('guru_id', request()->user()->guru_id);
            });
        })->with(['guru_internal', 'asesor'])->withCount('pd')->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->whereHas('paket_ukk', function($query){
                $query->where('nomor_paket', 'ilike', '%'.request()->q.'%');
                $query->orWhere('nama_paket_id', 'ilike', '%'.request()->q.'%');
                $query->orWhere('nama_paket_en', 'ilike', '%'.request()->q.'%');
                $query->orWhereHas('jurusan_sp', function($query){
                    $query->where('nama_jurusan_sp', 'ilike', '%'.request()->q.'%');
                });
            });
        })
        ->paginate(request()->per_page);
        return response()->json([
            'data' => $data, 
        ]);
    }
    public function get_penguji(){
        $data = [
            'guru' => Guru::orderBy('nama')->get(),
            'asesor' => Asesor::orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function get_siswa(){
        $rencana_ukk = Rencana_ukk::where(function($query){
            $query->where('sekolah_id', request()->sekolah_id);
            $query->where('internal', request()->internal);
            $query->where('asesor_id', request()->asesor_id);
            $query->where('tanggal_sertifikat', request()->tanggal_sertifikat);
        })->withWhereHas('paket_ukk', function($query){
            $query->where('paket_ukk_id', request()->paket_ukk_id);
        })->first();
        $data = [
            'pd' => Peserta_didik::with([
                'nilai_ukk' => function($query) use ($rencana_ukk){
                    if($rencana_ukk){
                        $query->where('rencana_ukk_id', $rencana_ukk->rencana_ukk_id);
                    }
                }
            ])->withWhereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->orderBy('nama')->get(),
            'rencana_ukk' => $rencana_ukk,
        ];
        return response()->json($data);
    }
    public function store(){
        request()->validate(
            [
                'tingkat' => ['required'],
                'rombongan_belajar_id' => ['required'],
                'internal' => ['required'],
                'asesor_id' => ['required'],
                'tanggal_sertifikat' => ['required'],
                'paket_ukk_id' => ['required'],
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Kelas tidak boleh kosong!',
                'internal.required' => 'Penguji Internal tidak boleh kosong!',
                'asesor_id.required' => 'Penguji Eksternal tidak boleh kosong!',
                'tanggal_sertifikat.required' => 'Tanggal Sertifikat tidak boleh kosong!',
                'paket_ukk_id.required' => 'Paket Kompetensi tidak boleh kosong!',
            ],
        );
        $new = Rencana_ukk::create([
            'sekolah_id' => request()->sekolah_id,
            'semester_id' => request()->semester_id,
            'paket_ukk_id' => request()->paket_ukk_id,
            'internal' => request()->internal,
            'asesor_id' => request()->asesor_id,
            'tanggal_sertifikat' => request()->tanggal_sertifikat,
        ]);
        $deleted = [];
        foreach(request()->siswa_dipilih as $uuid){
            $segments = Str::of($uuid)->split('/[\s#]+/');
            $peserta_didik_id = $segments->first();
            $anggota_rombel_id = $segments->last();
            $deleted[] = $anggota_rombel_id;
            Nilai_ukk::firstOrCreate(
                [
                'rencana_ukk_id'		=> $new->rencana_ukk_id,
                'anggota_rombel_id'		=> $anggota_rombel_id,
                'peserta_didik_id'		=> $peserta_didik_id,
                ],
                [
                'sekolah_id' 			=> request()->sekolah_id,
                'nilai'					=> 0,
                ]
            );
        }
        if($deleted){
            Nilai_ukk::where('rencana_ukk_id', $new->rencana_ukk_id)->whereNotIn('anggota_rombel_id', $deleted)->delete();
        }
        return response()->json(['icon' => 'success', 'title' => 'Berhasil', 'text' => 'Rencana Penilaian UKK berhasil disimpan']);
    }
    public function show(){
        $rencana_ukk = Rencana_ukk::with(['guru_internal', 'asesor', 'paket_ukk', 'pd' => function($query){
            $query->orderBy('nama');
            $query->with(['nilai_ukk' => function($query){
                $query->where('rencana_ukk_id', request()->id);
            }]);
        }])->find(request()->id);
        $data = [
            'rencana_ukk' => $rencana_ukk,
            'rombongan_belajar' => Rombongan_belajar::whereHas('anggota_rombel', function($query){
                $query->whereHas('nilai_ukk', function($query){
                    $query->where('rencana_ukk_id', request()->id);
                });
            })->first(),
        ];
        return response()->json($data);
    }
    public function get_rencana(){
        $rencana_ukk = [];
        $get = Rencana_ukk::where(function($query){
            $query->where('sekolah_id', request()->sekolah_id);
            $query->where('semester_id', request()->semester_id);
        })->withWhereHas('paket_ukk', function($query){
            $query->withWhereHas('jurusan_sp', function($query){
                $query->where('guru_id', request()->user()->guru_id);
            });
        })->get();
        foreach($get as $val){
            $rencana_ukk[] = [
                'rencana_ukk_id' => $val->rencana_ukk_id,
                'nama' => $val->paket_ukk->nama_paket_id,
            ];
        }
        $data = [
            'rencana_ukk' => $rencana_ukk,
            'data_siswa' => (request()->rencana_ukk_id) ? Peserta_didik::withWhereHas('nilai_ukk', function($query){
                $query->where('rencana_ukk_id', request()->rencana_ukk_id);
            })->orderBy('nama')->get() : [],
        ];
        return response()->json($data);
    }
    public function siswa_ukk(){
        $data = [
            'rencana_ukk' => (request()->paket_ukk_id) ? Rencana_ukk::withWhereHas('paket_ukk', function($query){
                $query->where('paket_ukk_id', request()->paket_ukk_id);
            })->first() : NULL,
            'data_siswa' => Peserta_didik::withWhereHas('nilai_ukk', function($query){
                $query->where('rencana_ukk_id', request()->rencana_ukk_id);
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function simpan_nilai_ukk(){
        request()->validate(
            [
                'rencana_ukk_id' => 'required',
            ],
            [
                'rencana_ukk_id.required' => 'Paket Kompetensi tidak boleh kosong!',
            ]
        );
        $insert = 0;
        foreach(request()->nilai as $uuid => $nilai_ukk){
            $insert++;
            $segments = Str::of($uuid)->split('/[\s#]+/');
            Nilai_ukk::updateOrCreate(
                [
                    'sekolah_id' => request()->sekolah_id,
                    'rencana_ukk_id' => request()->rencana_ukk_id,
                    'anggota_rombel_id' => $segments->last(),
                    'peserta_didik_id' => $segments->first(),
                ],
                [
                    'nilai' => $nilai_ukk,
                ]
            );
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Nilai UKK berhasil disimpan',
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nilai UKK gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function update_rencana(){
        request()->validate(
            [
                'tingkat' => ['required'],
                'rombongan_belajar_id' => ['required'],
                'internal' => ['required'],
                'asesor_id' => ['required'],
                'tanggal_sertifikat' => ['required'],
                'paket_ukk_id' => ['required'],
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Kelas tidak boleh kosong!',
                'internal.required' => 'Penguji Internal tidak boleh kosong!',
                'asesor_id.required' => 'Penguji Eksternal tidak boleh kosong!',
                'tanggal_sertifikat.required' => 'Tanggal Sertifikat tidak boleh kosong!',
                'paket_ukk_id.required' => 'Paket Kompetensi tidak boleh kosong!',
            ],
        );
        $find = Rencana_ukk::find(request()->rencana_ukk_id);
        $find->paket_ukk_id = request()->paket_ukk_id;
        $find->internal = request()->internal;
        $find->asesor_id = request()->asesor_id;
        $find->tanggal_sertifikat = request()->tanggal_sertifikat;
        $deleted = [];
        foreach(request()->siswa_dipilih as $uuid){
            $segments = Str::of($uuid)->split('/[\s#]+/');
            $peserta_didik_id = $segments->first();
            $anggota_rombel_id = $segments->last();
            $deleted[] = $anggota_rombel_id;
            Nilai_ukk::firstOrCreate(
                [
                'rencana_ukk_id'		=> request()->rencana_ukk_id,
                'anggota_rombel_id'		=> $anggota_rombel_id,
                'peserta_didik_id'		=> $peserta_didik_id,
                ],
                [
                'sekolah_id' 			=> request()->sekolah_id,
                'nilai'					=> 0,
                ]
            );
        }
        if($deleted){
            Nilai_ukk::where('rencana_ukk_id', request()->rencana_ukk_id)->whereNotIn('anggota_rombel_id', $deleted)->delete();
        }
        $find->save();
        return response()->json(['icon' => 'success', 'title' => 'Berhasil', 'text' => 'Rencana Penilaian UKK berhasil diperbaharui']);
        $insert = 1;
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Rencanan Penilaian UKK berhasil diperbaharui',
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencanan Penilaian UKK gagal diperbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
}
