<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan_sp;
use App\Models\Guru;
use App\Models\Kajur;
use App\Models\User;
use App\Models\Rombongan_belajar;
use App\Models\Kurikulum;
use App\Models\Mata_pelajaran;
use App\Models\Mapel_tingkat;
use Validator;

class EditorController extends Controller
{
    public function index(){
        if(request()->data == 'jurusan-sp'){
            return $this->getJurusan();
        }
    }
    public function update(){
        if(request()->data == 'jurusan-sp'){
            return $this->updateJurusan();
        }
        if(request()->data == 'rombel'){
            return $this->updateRombel();
        }
        if(request()->data == 'mapel'){
            return $this->updateMapel();
        }
        $data = [
            'success' => FALSE,
            'errors' => [
                'nama' => 'Permintaan tidak ditemukan'
            ],
            'icon' => 'danger',
            'text' => 'Permintaan tidak ditemukan',
            'title' => 'Gagal',
        ];
        return response()->json($data);
    }
    private function getJurusan(){
        $data = [
            'jurusan' => Jurusan_sp::with(['kajur'])->find(request()->id),
            'guru' => Guru::select('guru_id', 'nama', 'tanggal_lahir')->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function updateJurusan(){
        $jurusan = Jurusan_sp::find(request()->jurusan_sp_id);
        if($jurusan){
            $jurusan->nama_jurusan_sp = request()->nama_jurusan_sp;
            $jurusan->alias = request()->alias;
            $jurusan->save();
        }
        $kajur = Kajur::updateOrCreate(
            [
                'semester_id' => request()->semester_id,
                'jurusan_sp_id' => request()->jurusan_sp_id,
            ],
            [
                'guru_id' => request()->guru_id,
            ]
        );
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
                'icon' => 'danger',
                'text' => 'Data Sekolah gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function updateRombel(){
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
    public function updateMapel(){
        $validator = Validator::make(request()->all(), 
            [
                'nama' => ['required'],
                'tingkat' => ['required'],
                'jurusan_sp_id' => ['required'],
            ],
            [
                'nama.required' => 'Nama Mata Pelajaran tidak boleh kosong',
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
                'jurusan_sp_id.required' => 'Jurusan tidak boleh kosong',
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
        if($data->save()){
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
}
