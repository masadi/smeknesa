<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\Semester;
use App\Models\Guru;
use App\Models\Team;
use Validator;
use File;

class PengaturanController extends Controller
{
    public function index(){
        $tanggal_penilaian = get_setting('tanggal_penilaian', request()->semester_id);
        $data = [
            'app_name' => get_setting('app_name'),
            'app_version' => get_setting('app_version'),
            'semester_id' =>  get_setting('semester_id'),
            'semester' => Semester::whereHas('tahun_ajaran', function($query){
                $query->where('periode_aktif', 1);
            })->orderBy('semester_id', 'DESC')->get(),
            'tanggal_whatsapp' => get_setting('tanggal_whatsapp'),
            'tanggal_penilaian' => $tanggal_penilaian,
            'deadline' => tanggalIndo($tanggal_penilaian),
            'logo' => get_setting('logo') ? asset('storage/images/'.get_setting('logo')) : asset('img/logo/tutwuri.png')
        ];
        return response()->json($data);
    }
    public function pengguna(){
        $data = Setting::all();
        return response()->json($data);
    }
    public function umum(){
        $semester = Semester::find(request()->semester_id);
        $semester->periode_aktif = 1;
        $$semester->save();
        Setting::where('key', 'app_name')->update(['value' => request()->app_name]);
        Setting::where('key', 'app_version')->update(['value' => request()->app_version]);
        Setting::where('key', 'semester_id')->update(['value' => request()->semester_id]);
        Setting::where('key', 'periode_aktif')->update(['value' => $semester->nama]);
        Semester::where('semester_id', '<>', request()->semester_id)->update(['periode_aktf' => 0]);
        if(request()->tanggal_whatsapp){
            Setting::updateOrCreate(
                ['key' => 'tanggal_whatsapp'],
                ['value' => request()->tanggal_whatsapp]
            );
        }
        if(request()->tanggal_penilaian){
            Setting::updateOrCreate(
                ['key' => 'tanggal_penilaian'],
                [
                    'semester_id' => request()->semester_id,
                    'value' => request()->tanggal_penilaian,
                ]
            );
        }
        if(request()->logo){
            if (!File::isDirectory(storage_path('app/public/images'))) {
                File::makeDirectory(storage_path('app/public/images'));
            }
            $logo = request()->logo->store('public/images');
            Setting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => basename($logo)]
            );
        }
        $data = [
            'icon' => 'success',
            'text' => 'Pengaturan umum berhasil disimpan',
            'title' => 'Berhasil',
        ];
        return response()->json($data);
    }
    public function guru_mapel(){
        if (request()->isMethod('post')) {
            $user = User::where('guru_id', request()->guru_id)->first();
            if(!$user->hasRole('pengajar', periode_aktif())){
                if($user->attachRole('pengajar', periode_aktif())){
                    $data = [
                        'success' => TRUE,
                        'text' => 'Pemberian akses Guru Mata Pelajaran ke Guru '.$user->name.' berhasil',
                    ];
                } else {
                    $data = [
                        'success' => FALSE,
                        'text' => 'Pemberian akses Guru Mata Pelajaran ke Guru '.$user->name.' gagal. Silahkan coba beberapa saat lagi!',
                    ];
                }
            } else {
                $data = [
                    'success' => FALSE,
                    'text' => 'Guru atas nama '.$user->name.' sudah memiliki akses Guru Mata Pelajaran',
                ];
            }
        } else {
            /*
            $data = Guru::select('guru_id', 'nama', 'tempat_lahir', 'tanggal_lahir', 'nip')->has('pengguna')->with(['pengguna' => function($query) use ($team){
                $query->with(['roles' => function($query) use ($team){
                    $query->where('name', 'pengajar');
                    $query->wherePivot('team_id', $team->id);
                }]);
            }])->orderBy('nama')->get();*/
            Guru::select('guru_id', 'nama', 'tanggal_lahir')->has('pengguna')->with([
                'pengguna',
                'rombongan_belajar' => function($query){
                    $query->where('semester_id', request()->semester_id);
                },
                'pembelajaran' => function($query){
                    $query->where('semester_id', request()->semester_id);
                },
            ])->orderBy('guru_id')->chunk(200, function ($guru) {
                foreach($guru as $g){
                    $user = $g->pengguna;
                    if($g->rombongan_belajar){
                        if(!$user->hasRole('walas', periode_aktif())){
                            $user->attachRole('walas', periode_aktif());
                        }
                    }
                    if($g->pembelajaran->count()){
                        if(!$user->hasRole('pengajar', periode_aktif())){
                            $user->attachRole('pengajar', periode_aktif());
                        }
                    }
                }
            });
            $data = [
                'icon' => 'success',
                'text' => 'Akses Guru Mata Pelajaran berhasil dibuat',
                'title' => 'Berhasil',
            ];
        }
        return response()->json($data);
    }
    public function remedial(){
        if (request()->isMethod('post')) {
            $validator = Validator::make(request()->all(), 
                [
                    'tanggal_remedial' => ['required'],
                    'status_remedial' => ['required'],
                ],
                [
                    'tanggal_remedial.required' => 'Tanggal Remedial tidak boleh kosong',
                    'status_remedial.required' => 'Status Remedial tidak boleh kosong',
                ]
            );
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => FALSE,
                    'errors' => $validator->errors(),
                ]);
            }
            $semester = Semester::find(get_setting('semester_id'));
            $semester->tanggal_remedial = request()->tanggal_remedial;
            $semester->status_remedial = request()->status_remedial;
            if($semester->save()){
                $data = [
                    'icon' => 'success',
                    'text' => 'Pengaturan Remedial berhasil disimpan',
                    'title' => 'Berhasil',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'text' => 'Pengaturan Remedial gagal disimpan. Silahkan coba beberapa saat lagi',
                    'title' => 'Gagal',
                ];
            }
        } else {
            $data = [
                'semester' => Semester::find(get_setting('semester_id')),
            ];
        }
        return response()->json($data);
    }
}
