<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Jadwal;
use App\Models\Jam;
use Validator;

class JadwalController extends Controller
{
    public function index(){
        $data = Jadwal::with([
            'pembelajaran' => function($query){
                $query->where('semester_id', semester_id());
            },
            'guru',
            'kelas',
            'jam'
        ])->where($this->kondisiMapel())->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->whereHas('pembelajaran', function($query){
                $query->where('nama_mata_pelajaran', 'ilike', '%'.request()->q.'%');
            });
            $query->where($this->kondisiMapel());
            $query->orWhereHas('guru', function($query){
                $query->where('nama', 'ilike', '%'.request()->q.'%');
            });
            $query->where($this->kondisiMapel());
            $query->orWhere('hari', 'ilike', '%'.request()->q.'%');
            $query->where($this->kondisiMapel());
        })
        ->when(request()->tingkat, function($query) {
            $query->whereHas('kelas', function($query){
                $query->where('tingkat', request()->tingkat);
            });
            $query->where($this->kondisiMapel());
        })
        ->when(request()->jurusan_sp_id, function($query) {
            $query->whereHas('kelas', function($query){
                $query->where('jurusan_sp_id', request()->jurusan_sp_id);
            });
            $query->where($this->kondisiMapel());
        })
        ->when(request()->rombongan_belajar_id, function($query) {
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data, 'semester_id' => semester_id()]);
    }
    private function kondisiMapel(){
        return function($query){
            $query->whereHas('pembelajaran', function($query){
                $query->where('semester_id', semester_id());
                if(loggedUser()->hasRole('walas', periode_aktif())){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('guru_id', loggedUser()->guru_id);
                    });
                }
                if(loggedUser()->hasRole('pd', periode_aktif())){
                    $query->whereHas('anggota_rombel', function($query){
                        $query->where('peserta_didik_id', loggedUser()->peserta_didik_id);
                    });
                }
            });
        };
    }
    public function tambah(){
        $validator = Validator::make(request()->all(), 
            [
                'tingkat' => ['required'],
                'rombongan_belajar_id' => ['required'],
                'hari' => [ 'required'],
                'pembelajaran_id' => ['required'],
                'jam' => ['required']
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong',
                'hari.required' => 'Tingkat Kelas tidak boleh kosong',
                'pembelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong',
                'jam.required' => 'Jam tidak boleh kosong',
            ],
        );
 
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $insert = 0;
        $jam_ke = [];
        if(request()->jadwal_id){
            $jadwal = Jadwal::find(request()->jadwal_id);
            Jam::where('jadwal_id', $jadwal->jadwal_id)->delete();
        } else {
            $jadwal = Jadwal::create([
                'pembelajaran_id' => request()->pembelajaran_id,
                'hari' => request()->hari,
                'semester_id' => semester_id(),
                'rombongan_belajar_id' => request()->rombongan_belajar_id,
            ]);    
        }
        foreach(array_filter(request()->jam) as $jam){
            $jam_ke[] = $jam;
            if($jadwal){
                Jam::create([
                    'jadwal_id' => $jadwal->jadwal_id,
                    'jam' => $jam,
                ]);
                $insert++;
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Jadwal Mengajar berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Jadwal Mengajar gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function add(){
        $validator = Validator::make(request()->all(), 
            [
                'pembelajaran_id' => ['required'],
                'hari' => [ 'required'],
                'jam' => ['required']
            ],
            [
                'pembelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong',
                'hari.required' => 'Tingkat Kelas tidak boleh kosong',
                'jam.required' => 'Jam tidak boleh kosong',
            ],
        );
 
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $insert = 0;
        $jam_ke = [];
        if(request()->jadwal_id){
            $jadwal = Jadwal::find(request()->jadwal_id);
            Jam::where('jadwal_id', $jadwal->jadwal_id)->delete();
        } else {
            $jadwal = Jadwal::updateorCreate([
                'pembelajaran_id' => request()->pembelajaran_id,
                'hari' => request()->hari,
                'semester_id' => semester_id(),
                'rombongan_belajar_id' => request()->rombongan_belajar_id,
            ]);    
        }
        foreach(array_filter(request()->jam) as $jam){
            $jam_ke[] = $jam;
            if($jadwal){
                Jam::updateorCreate([
                    'jadwal_id' => $jadwal->jadwal_id,
                    'jam' => $jam,
                ]);
                $insert++;
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'text' => 'Jadwal Mengajar berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Jadwal Mengajar gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function jam(){
        $data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
        if(request()->hari == 'Jumat'){
            $data = [1, 2, 3, 4, 5, 6];
        }
        return response()->json($data);
    }
}
