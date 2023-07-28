<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modul;
use App\Models\Modul_langkah_pembelajaran;
use App\Models\Modul_materi;
use App\Models\Modul_pembelajaran;
use App\Models\Modul_sarpras;
use App\Models\Modul_projek;
use App\Models\Modul_mapel;
use App\Models\Ref_modul_model_pembelajaran;
use App\Models\Ref_modul_projek;
use App\Models\Ref_modul_sarpras;
use App\Models\Ref_langkah_pembelajaran;
use App\Models\Pembelajaran;

class ModulController extends Controller
{
    public function index(){
        $data = Modul::with([
            /*'guru' => function($query){
                $query->select('guru_id', 'nama', 'tanggal_lahir');
            },*/
            'mapel' => function($query){
                //
            }
        ])->where($this->kondisiModul())->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('kata_kunci', 'ilike', '%'.request()->q.'%');
            $query->where($this->kondisiModul());
            $query->orWhere('tujuan_pembelajaran', 'ilike', '%'.request()->q.'%');
            $query->where($this->kondisiModul());
            $query->orWhereHas('pembelajaran', function($query){
                $query->where('nama_mata_pelajaran', 'ilike', '%'.request()->q.'%');
            });
            $query->where($this->kondisiModul());
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    private function kondisiModul(){
        return function($query){
            $query->whereHas('mapel', function($query){
                $query->where('guru_id', loggedUser()->guru_id);
            });
        };
    }
    public function referensi(){
        $data = [
            'ref_model' => Ref_modul_model_pembelajaran::get(),
            'ref_projek' => Ref_modul_projek::get(),
            'ref_sarpras' => Ref_modul_sarpras::get(),
            'ref_langkah' => Ref_langkah_pembelajaran::with(['sub'])->whereNull('langkah_id')->orderBy('id')->get(),
        ];
        return response()->json($data);
    }
    public function simpan(){
        request()->validate(
            [
                'pembelajaran_id' => 'required',
                'alokasi_waktu' => 'required',
                'jumlah_pertemuan' => 'required',
                'kata_kunci' => 'required',
                'projek_terpilih.*' => 'required',
                'sarpras_terpilih.*' => 'required',
                'model_terpilih.*' => 'required',
                'karakteristik_pd' => 'required',
                'tujuan_pembelajaran' => 'required',
                'materi_ajar.*' => 'required',
                'langkah_terpilih.*' => 'required',
                'berkas' => 'required|mimes:pdf',
            ],
            [
                'pembelajaran_id.required' => 'Nama Mata Pelajaran tidak boleh kosong',
                'alokasi_waktu.required' => 'Alokasi Waktu tidak boleh kosong',
                'jumlah_pertemuan.required' => 'Jumlah Pertemuan tidak boleh kosong',
                'kata_kunci.required' => 'Kata Kunci tidak boleh kosong',
                'projek_terpilih.*.required' => 'Profil Pelajar Pancasila tidak boleh kosong',
                'sarpras_terpilih.*.required' => 'Sarana Prasarana tidak boleh kosong',
                'model_terpilih.*.required' => 'Model Pembelajaran tidak boleh kosong',
                'karakteristik_pd.required' => 'Karakteristik Peserta Didik tidak boleh kosong',
                'tujuan_pembelajaran.required' => 'Tujuan Pembelajaran tidak boleh kosong',
                'materi_ajar.*.required' => 'Materi Ajar tidak boleh kosong',
                'langkah_terpilih.*.required' => 'Langkah-langkah Kegiatan Pembelajaran tidak boleh kosong',
                'berkas.required' => 'File Materi Pembelajaran tidak boleh kosong',
                'berkas.mimes' => 'File Materi Pembelajaran harus berekstensi .PDF',
            ],
        );
        $upload_path = public_path('upload');
        $file_name = request()->berkas->getClientOriginalName();
        $generated_new_name = time() . '.' . request()->berkas->getClientOriginalExtension();
        $modul = Modul::create([
            'guru_id' => loggedUser()->guru_id,
            'alokasi_waktu' => request()->alokasi_waktu,
            'jumlah_pertemuan' => request()->jumlah_pertemuan,
            'kata_kunci' => request()->kata_kunci,
            'karakteristik_pd' => request()->karakteristik_pd,
            'tujuan_pembelajaran' => request()->tujuan_pembelajaran,
            'berkas' => $generated_new_name,
        ]);
        foreach(array_filter(json_decode(request()->langkah_terpilih)) as $langkah){
            Modul_langkah_pembelajaran::create([
                'modul_id' => $modul->modul_id,
                'langkah_id' => $langkah->id,
            ]);
        }
        foreach(array_filter(json_decode(request()->materi_ajar)) as $materi_ajar){
            Modul_materi::create([
                'modul_id' => $modul->modul_id,
                'materi_ajar' => $materi_ajar,
            ]);
        }
        foreach(array_filter(json_decode(request()->model_terpilih)) as $model_pembelajaran_id){
            Modul_pembelajaran::create([
                'modul_id' => $modul->modul_id,
                'model_pembelajaran_id' => $model_pembelajaran_id,
            ]);
        }
        foreach(array_filter(json_decode(request()->sarpras_terpilih)) as $sarpras_id){
            Modul_sarpras::create([
                'modul_id' => $modul->modul_id,
                'sarpras_id' => $sarpras_id,
            ]);
        }
        foreach(array_filter(json_decode(request()->projek_terpilih)) as $projek_id){
            Modul_projek::create([
                'modul_id' => $modul->modul_id,
                'projek_id' => $projek_id,
            ]);
        }
        foreach(array_filter(json_decode(request()->pembelajaran_id)) as $pembelajaran_id){
            Modul_mapel::create([
                'modul_id' => $modul->modul_id,
                'pembelajaran_id' => $pembelajaran_id,
            ]);
        }
        $data = [
            'icon' => 'success',
            'text' => 'Modul Pembelajaran berhasil disimpan',
            'title' => 'Berhasil',
            'request' => request()->all(),
            'langkah_terpilih' => array_filter(json_decode(request()->langkah_terpilih)),
        ];
        return response()->json($data);
    }
    public function cari_mapel(){
        $data = Pembelajaran::with(['rombongan_belajar' => function($query){
            $query->select('rombongan_belajar_id', 'nama');
        }])->where(function($query){
            $query->where('guru_id', loggedUser()->guru_id);
            $query->whereNull('induk_pembelajaran_id');
            $query->where('semester_id', semester_id());
            $query->where('nama_mata_pelajaran', 'ilike', '%'.request()->q.'%');
        })->orderBy('mata_pelajaran_id')->get();
        return response()->json($data);
    }
}
