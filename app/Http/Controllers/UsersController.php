<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use App\Models\Guru;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;
use App\Models\Ekstrakurikuler;
use App\Models\Pembelajaran;

class UsersController extends Controller
{
    public function index()
    {
        $team = Team::where('name', request()->periode_aktif)->first();
        $where = function($query){
            $query->whereRoleIs(['guru', 'siswa', 'tu'], request()->periode_aktif);
            $query->where('sekolah_id', request()->sekolah_id);
        };
        $data = User::with(['roles' => function($query) use ($team){
            $query->wherePivot('team_id', $team->id);
        }])->where($where)->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) use ($where){
            $query->where($where);
            $query->where('name', 'ILIKE', '%' . request()->q . '%');
            $query->orWhere('nuptk', 'ILIKE', '%' . request()->q . '%');
            $query->where($where);
            $query->orWhere('nisn', 'ILIKE', '%' . request()->q . '%');
            $query->where($where);
            $query->orWhere('email', 'ILIKE', '%' . request()->q . '%');
            $query->where($where);
        })->when(request()->role_id, function($ptk) {
            if(request()->role_id !== 'all'){
                $ptk->whereRoleIs(request()->role_id, request()->periode_aktif);
            }
        })->paginate(request()->per_page);
        $roles = Role::whereNotIn('id', [1,2,6])->get();
        return response()->json(['status' => 'success', 'data' => $data, 'roles' => $roles]);
    }
    public function generate(){
        $function = 'generate_'.request()->aksi;
        $data = $this->{$function}();
        return response()->json($data);
    }
    private function generate_ptk(){
        $insert = 0;
        $data = Guru::where(function($query){
            $query->whereDoesntHave('ptk_keluar', function($query){
                $query->where('semester_id', request()->semester_id);
            });
            $query->where('sekolah_id', request()->sekolah_id);
            $query->whereNotNull('email');
        })->with(['bimbing_pd' => function($query){
            $query->whereHas('akt_pd', function($query){
                $query->whereHas('anggota_akt_pd', function($query){
                    $query->whereHas('siswa', function($query){
                        $query->whereHas('anggota_rombel', function($query){
                            $query->where('semester_id', request()->semester_id);
                        });
                    });
                });
            });
        }])->get();
        $jenis_tu = jenis_gtk('tendik');
		$asesor = jenis_gtk('asesor');
        $PembinaRole = Role::where('name', 'pembina_ekskul')->first();
        $p5Role = Role::where('name', 'guru-p5')->first();
        $WalasRole = Role::where('name', 'wali')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $pembimbingRole = Role::where('name', 'pembimbing')->first();
        if($data){
            foreach($data as $d){
                $insert++;
                $new_password = strtolower(Str::random(8));
                $user = User::where('guru_id', $d->guru_id)->first();
                $user_email = $this->check_email($d, 'guru_id');
                if($user){
                    //$user->email = $user_email;
                    $user->name = $d->nama_lengkap;
                    $user->save();
                } else {
                    $user = User::create([
                        'name' => $d->nama_lengkap,
						'email' => $user_email,
						'nuptk'	=> $d->nuptk,
						'password' => bcrypt($new_password),
						'last_sync'	=> now(),
						'sekolah_id'	=> request()->sekolah_id,
						'password_dapo'	=> md5($new_password),
						'guru_id'	=> $d->guru_id,
						'default_password' => $new_password,
                    ]);
                }
                $user->detachRole($adminRole, request()->periode_aktif);
                if($jenis_tu->contains($d->jenis_ptk_id)){
                    $role = Role::where('name', 'tu')->first();
                } elseif($asesor->contains($d->jenis_ptk_id)){
                    $role = Role::where('name', 'user')->first();
                } else {
                    $role = Role::where('name', 'guru')->first();
                }
                if(!$user->hasRole($role, request()->periode_aktif)){
                    $user->attachRole($role, request()->periode_aktif);
                }
                $find_rombel = Rombongan_belajar::where('guru_id', $d->guru_id)->where('semester_id', request()->semester_id)->where('jenis_rombel', 1)->first();
				if($find_rombel){
                    if(!$user->hasRole($WalasRole, request()->periode_aktif)){
                        $user->attachRole($WalasRole, request()->periode_aktif);
                    }
                } else {
                    $user->detachRole($WalasRole, request()->periode_aktif);
                }
                $find_mapel_p5 = Pembelajaran::where('guru_id', $d->guru_id)->where('semester_id', request()->semester_id)->where('mata_pelajaran_id', '200040000')->has('tema')->first();
                if($find_mapel_p5){
                    if(!$user->hasRole($p5Role, request()->periode_aktif)){
                        $user->attachRole($p5Role, request()->periode_aktif);
                    }
                } else {
                    $user->detachRole($p5Role, request()->periode_aktif);
                }
                $find_ekskul = Ekstrakurikuler::where('guru_id', $d->guru_id)->where('semester_id', request()->semester_id)->first();
                if($find_ekskul){
                    if(!$user->hasRole($PembinaRole, request()->periode_aktif)){
                        $user->attachRole($PembinaRole, request()->periode_aktif);
                    }
                } else {
                    $user->detachRole($PembinaRole, request()->periode_aktif);
                }
                if(request()->semester_id >= 20231){
                    if($d->bimbing_pd){
                        if(!$user->hasRole($pembimbingRole, request()->periode_aktif)){
                            $user->attachRole($pembimbingRole, request()->periode_aktif);
                        }
                    } else {
                        $user->detachRole($pembimbingRole, request()->periode_aktif);
                    }
                }
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Pengguna PTK berhasil diperbaharui',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Pengguna PTK gagal diperbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return $data;
    }
    private function generate_pd(){
        $insert = 0;
        $data = Peserta_didik::where(function($query){
            $query->whereDoesntHave('pd_keluar', function($query){
                $query->where('semester_id', request()->semester_id);
            });
            $query->where('sekolah_id', request()->sekolah_id);
        })->get();
        $role = Role::where('name', 'siswa')->first();
        $adminRole = Role::where('name', 'admin')->first();
        if($data){
            foreach($data as $d){
                $insert++;
                $new_password = strtolower(Str::random(8));
                $user = User::where('peserta_didik_id', $d->peserta_didik_id)->first();
                if(!$user){
                    $user_email = $this->check_email($d, 'peserta_didik_id');
                    $user = User::create([
                        'name' => $d->nama,
						'email' => $user_email,
						'nisn'	=> $d->nisn,
						'password' => bcrypt($new_password),
						'last_sync'	=> now(),
						'sekolah_id'	=> request()->sekolah_id,
						'password_dapo'	=> md5($new_password),
						'peserta_didik_id'	=> $d->peserta_didik_id,
						'default_password' => $new_password,
                    ]);
                } elseif(!$user->email){
                    $user_email = $this->check_email($d, 'peserta_didik_id');
                    $user->email = $user_email;
                    $user->save();
                }
                if(!$d->email){
                    $d->email = $user->email;
                    $d->save();
                }
                $user->detachRole($adminRole, request()->periode_aktif);
                if(!$user->hasRole($role, request()->periode_aktif)){
                    $user->attachRole($role, request()->periode_aktif);
                }
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Pengguna Peserta Didik berhasil diperbaharui',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Pengguna Peserta Didik gagal diperbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return $data;
    }
    public function detil(){
        $team = Team::where('name', request()->periode_aktif)->first();
        $user = User::with(['roles'])->find(request()->user_id);
        $user_roles = $user->rolesTeams;
        $roles = [];
        if($user->guru_id){
            $roles = Role::select('id as value', 'display_name as text')->find([7,8,9]);
        }
        $data = [
            'user' => $user,
            'roles' => $roles
        ];
        return response()->json($data);
    }
    public function update(){
        $user = User::find(request()->user_id);
        foreach(request()->akses as $akses){
            if(!$user->hasRole($akses, request()->periode_aktif)){
                $user->attachRole($akses, request()->periode_aktif);
            }
        }
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data Pengguna berhasil diperbaharui',
        ];
        return response()->json($data);
    }
    public function reset_password(){
        $user = User::find(request()->user_id);
        if(!$user->default_password){
            $user->default_password = strtolower(Str::random(8));
        }
        $user->password = bcrypt($user->default_password);
        if($user->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Password Pengguna berhasil direset',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Password Pengguna gagal direset. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function hapusAkses(){
        $user = User::find(request()->user_id);
        $user->detachRole(request()->role, request()->periode_aktif);
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Hak Akses berhasil dihapus',
        ];
        return response()->json($data);
    }
    private function check_email($user, $field){
        $loggedUser = auth()->user();
        $random = Str::random(8);
		$user->email = ($user->email != $loggedUser->email) ? $user->email : strtolower($random).'@erapor-smk.net';
		$user->email = strtolower($user->email);
        if($field == 'guru_id'){
            $find_user_email = User::where('email', $user->email)->where($field, '<>', $user->ptk_id)->first();
		} else {
            $find_user_email = User::where('email', $user->email)->where($field, '<>', $user->peserta_didik_id)->first();
		}
        $find_user_email = User::where('email', $user->email)->first();
		if($find_user_email){
			$user->email = strtolower($random).'@erapor-smk.net';
		}
        return $user->email;
    }
    public function profil(){
        return response()->json(auth()->user());
    }
    public function update_profile(){
        $user = auth()->user();
        if(request()->has('name')){
            request()->validate(
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
                    'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
                ],
                [
                    'name.required' => 'Nama Lengkap tidak boleh kosong!',
                    'email.required' => 'Email tidak boleh kosong!',
                    'email.email' => 'Email tidak valid!',
                    'email.unique' => 'Email sudah terdaftar di Database!',
                    'photo.mimes' => 'Foto harus berekstensi jpg/jpeg/png',
                    'photo.max' => 'Ukuran foto tidak boleh lebih dari 1 MB!',
                ],
            );
            $user->name = request()->name;
            //$user->email = request()->email;
            $user->email = request()->email;
            //profile-photos
            if(request()->photo){
                $photo = request()->photo->store('public/profile-photos');
                $user->profile_photo_path = 'profile-photos/'.basename($photo);
            }
            if($user->save()){
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Profil Pengguna berhasil diperbaharui',
                ];
            } else {
                $data = [
                    'icon' => 'danger',
                    'title' => 'Gagal!',
                    'text' => 'Profil Pengguna gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                ];
            }
        } else {
            $message = [
                //'current_password.required' => 'Kata sandi saat ini tidak boleh kosong',
                //'current_password.current_password' => 'Kata sandi salah',
                'password.required' => 'Kata sandi baru tidak boleh kosong',
                'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai dengan Kata sandi baru',
                'password_confirmation.required' => 'Konfirmasi kata sandi tidak boleh kosong',
            ];
            $rules = [
                //'current_password' => ['required', 'current_password'],
                'password' => [
                    'required',
                    'confirmed',
                ],
                'password_confirmation' => ['required'],
            ];
            $validator = Validator::make(request()->all(), $rules, $message)->validated();
            $user->password = Hash::make(request()->password);
            if($user->save()){
                if(request()->password == $user->username){
                    $ability = [
                        [
                            'action' => 'read',
                            'subject' => 'Web'
                        ]
                    ];
                } else {
                    if($user->hasRole('administrator')){
                        $ability = [
                            [
                                'action' => 'read',
                                'subject' => 'Web'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Administrator'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Referensi'
                            ]
                        ];
                    } elseif($user->hasRole('disprov')){
                        $ability = [
                            [
                                'action' => 'read',
                                'subject' => 'Web'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Disprov'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Blangko'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Referensi'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Fire'
                            ],
                        ];
                    }
                    if($user->hasRole('diskab')){
                        $ability = [
                            [
                                'action' => 'read',
                                'subject' => 'Web'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Diskab'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Blangko'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Referensi'
                            ]
                        ];
                    }
                    if($user->hasRole('sekolah')){
                        $ability = [
                            [
                                'action' => 'read',
                                'subject' => 'Web'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Sekolah'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Blangko'
                            ],
                            [
                                'action' => 'read',
                                'subject' => ($user->fire) ? 'Fire' : 'NotFire',
                            ],
                        ];
                    }
                    if($user->hasRole('verifikator')){ //alumni_aktif
                        $ability = [
                            [
                                'action' => 'read',
                                'subject' => 'Web'
                            ],
                            [
                                'action' => 'read',
                                'subject' => 'Verifikator'
                            ]
                        ];
                    }
                }
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Password Pengguna berhasil diperbaharui',
                    'ability' => $ability,
                ];
            } else {
                $data = [
                    'icon' => 'danger',
                    'title' => 'Gagal!',
                    'text' => 'Password Pengguna gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                ];
            }
        }
        return response()->json($data);
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Profil Pengguna berhasil diperbaharui',
        ];
        return response()->json($data);
    }
}
