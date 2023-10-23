<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Guru;
use App\Models\Peserta_didik;
use App\Models\Role;
use App\Models\Team;
use App\Models\Semester;
use App\Models\Jadwal;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Agama;
use App\Models\Pekerjaan;
use App\Models\Cita;
use App\Models\Jurusan_sp;
use Indonesia;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate(
            [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
            ],
            [
                'email.required' => 'Email tidak boleh kosong',
                'email.exists' => 'Email tidak terdaftar',
                'password.required' => 'Password tidak boleh kosong'
            ]
        );

        $credentials = request(['email','password']);
        if(!Auth::attempt($credentials))
        {
            return response()->json([
                'user' => NULL,
                'errors' => [
                    'password' => ['Password salah!'],
                ]
            ],401);
        }
        $semester = Semester::find(semester_id());
        if(!$semester){
            $semester = Semester::where('periode_aktif', 1)->first();
        }
        //$team = Team::where('name', $semester->nama)->first();
        $team = Team::firstOrCreate([
            'name' => $semester->nama,
            'display_name' => $semester->nama,
            'description' => $semester->nama,
        ]);
        $user = $request->user();
        if($user->sekolah_id && !$user->peserta_didik_id && !$user->guru_id){
            if(!$user->hasRole('administrator', $semester->nama)){
                $user->attachRole('administrator', $team);
            }
        }
        $user->last_login_at = date('Y-m-d H:i:s');
        if(!$user->sekolah_id){
            $sekolah = Sekolah::first();
            $user->sekolah_id = $sekolah->sekolah_id;
        }
        $user->save();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;
        $user->avatar = NULL;
        $user->role = $user->roles()->wherePivot(config('laratrust.foreign_keys.team'), $team->id)->get()->implode('display_name', ', ');
        //$user->roles->unique()->implode('display_name', ', ');
        $user->roles = $user->roles()->wherePivot(config('laratrust.foreign_keys.team'), $team->id)->get();
        //$user->roles;
        $user->ability = $user->allPermissions(['description as action', 'name as subject'], $semester->nama);
        $user->semester = $semester;
        /*$abilities = [];
        foreach($user->ability as $ability){
            $output['action'] = $ability->action;
            $output['subject'] = $ability->subject;
            $abilities[] = $output;
        }*/
        $user->accessToken = $token;
        $detil = NULL;
        if($user->peserta_didik_id) {
          $detil = Peserta_didik::with(['desa', 'kecamatan', 'kabupaten', 'provinsi', 'agama', 'pekerjaan_ayah', 'pekerjaan_ibu'])->find($user->peserta_didik_id);
        }
        if($user->ptk_id) {
          $detil = Guru::find($user->ptk_id);
        }
        $user->detil = $detil;
        return response()->json([
            'userData' => $user,
        ]);
        return response()->json([
            'user' => $user,
            'userAbilities' => $user->ability,
            'accessToken' =>$token,
            'token_type' => 'Bearer',
            'semester_id' => ($semester) ? $semester->semester_id : NULL,
            'periode_aktif' => ($semester) ? $semester->nama : NULL,
        ]);
    }
    public function user(Request $request)
    {
        $profile = NULL;
        if($request->user()->hasRole('guru', periode_aktif())){
            $profile = Guru::find($request->user()->guru_id);
        }
        if($request->user()->hasRole('pd', periode_aktif())){
            $profile = Peserta_didik::find($request->user()->peserta_didik_id);
        }
        $jadwal = [];
        if($request->user()->hasRole(['guru', 'pd'], periode_aktif())){
            $jadwal = Jadwal::with([
                'pembelajaran' => function($query){
                    $query->where('semester_id', semester_id());
                },
                'guru',
                'kelas',
                'jam',
                'presensi_jadwal',
            ])->withCount([
                'presensi_jadwal as alpha' => function($query){
                    $query->where('absen', 'A');
                },
                'presensi_jadwal as sakit' => function($query){
                    $query->where('absen', 'S');
                },
                'presensi_jadwal as izin' => function($query){
                    $query->where('absen', 'I');
                },
            ])->where(function($query) use ($request){
                $query->has('jam');
                $query->whereHas('pembelajaran', function($query) use ($request){
                    $query->where('semester_id', semester_id());
                    if($request->user()->guru_id){
                        $query->where('guru_id', $request->user()->guru_id);
                    } else {
                        $query->whereHas('anggota_rombel', function($query) use ($request){
                            $query->where('peserta_didik_id', $request->user()->peserta_didik_id);
                        });
                    }
                });
            })->get();
        }
        return response()->json([
            'user' => $request->user(),
            'role' => $request->user()->roles->unique()->pluck('display_name')->toArray(),
            'profile' => $profile,
            'guru_id' => $request->user()->guru_id,
            'peserta_didik_id' => $request->user()->peserta_didik_id,
            'jadwal' => $jadwal,
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    public function semester(){
        $data = [
            'semester' => Semester::whereHas('tahun_ajaran', function($query){
                $query->where('periode_aktif', 1);
            })->orderBy('semester_id', 'DESC')->get(),
            'aktif' => Semester::find(get_setting('semester_id')),
        ];
        return response()->json($data);
    }
    public function update_user(){
        $user = request()->user();
        $user->name = request()->name;
        $user->email = request()->email;
        $user->whatsapp = request()->no_hp;
        $user->wa_wali = request()->wa_wali;
        if($user->save()){
            $get = NULL;
            if(request()->guru_id){
                $get = Guru::find(request()->guru_id);
                $get->nuptk = request()->nuptk;
                $get->nip = request()->nip;
                $get->no_hp = request()->no_hp;
            }
            if(request()->peserta_didik_id){
                $get = Peserta_didik::find(request()->peserta_didik_id);
                $get->no_induk = request()->no_induk;
                $get->nisn = request()->nisn;
                $get->nama_ayah = request()->nama_ayah;
                $get->nama_ibu = request()->nama_ibu;
                $get->kerja_ayah = request()->kerja_ayah;
                $get->kerja_ibu = request()->kerja_ibu;
                $get->no_hp = request()->no_hp;
                $get->sekolah_asal = request()->sekolah_asal;
                $get->diterima = request()->diterima;
                $get->diterima_kelas = request()->diterima_kelas;
                $get->cita = request()->cita;
            }
            if($get){
                $get->nama = request()->name;
                $get->nik = request()->nik;
                $get->jenis_kelamin = request()->jenis_kelamin;
                $get->tempat_lahir = request()->tempat_lahir;
                $get->tanggal_lahir = request()->tanggal_lahir;
                $get->agama_id = request()->agama_id;
                $get->alamat_jalan = request()->alamat_jalan;
                $get->rt = request()->rt;
                $get->rw = request()->rw;
                $get->provinsi_id = request()->provinsi_id;
                $get->kabupaten_id = request()->kabupaten_id;
                $get->kecamatan_id = request()->kecamatan_id;
                $get->desa_id = request()->desa_id;
                $get->kode_pos = request()->kode_pos;
                $get->email = request()->email;
                $get->save();
            }
            $data = [
                'icon' => 'success',
                'text' => 'Profil Pengguna berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'danger',
                'text' => 'Profil Pengguna gagal diperbaharui',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function generate(){
        $all_role = ['guru', 'pengajar', 'pd', 'walas', 'instruktur', 'projek', 'pembimbing', 'kajur'];
        if(request()->jenis == 'ptk'){
            User::whereRoleIs('guru', request()->periode_aktif)->whereDoesntHave('guru')->delete();
            //$query->whereRoleIs(['guru', 'pd'], request()->periode_aktif);
            Guru::orderBy('guru_id')->chunk(200, function ($data) use ($all_role){
                foreach ($data as $d) {
                    $new_password = strtolower(Str::random(8));
                    $user = User::where('guru_id', $d->guru_id)->first();
                    if(!$user){
                        $user_email = $this->check_email($d, 'guru_id');
                        $user = User::create([
                            'name' => $d->nama,
                            'email' => $user_email,
                            'password' => bcrypt($new_password),
                            'default_password' => $new_password,
                            'sekolah_id'	=> $d->sekolah_id,
                            'guru_id'	=> $d->guru_id,
                        ]);
                    } else {
                        $user->guru_id = $d->guru_id;
                        $user->name = $d->nama;
                        $user->save();
                    }
                    $user->detachRoles($all_role, request()->periode_aktif);
                    $user->attachRole('guru', request()->periode_aktif);
                    $find = Rombongan_belajar::where('guru_id', $d->guru_id)->where('semester_id', request()->semester_id)->where('tingkat', '<>', 0)->first();
                    if($find){
                        $user->attachRole('walas', request()->periode_aktif);
                    }
                    $find = Pembelajaran::whereHas('mata_pelajaran', function($query){
                        $query->where('jenis', 'Umum');
                    })->where('guru_id', $d->guru_id)->where('semester_id', request()->semester_id)->first();
                    if($find){
                        $user->attachRole('pengajar', request()->periode_aktif);
                    }
                    $find = Pembelajaran::whereHas('mata_pelajaran', function($query){
                        $query->where('jenis', 'P5');
                    })->where('guru_id', $d->guru_id)->where('semester_id', request()->semester_id)->first();
                    if($find){
                        $user->attachRole('projek', request()->periode_aktif);
                    }
                    $find = Pembelajaran::whereHas('mata_pelajaran', function($query){
                        $query->where('jenis', 'PKL');
                    })->where('guru_id', $d->guru_id)->where('semester_id', request()->semester_id)->first();
                    if($find){
                        $user->attachRole('pembimbing', request()->periode_aktif);
                    }
                    $find = Rombongan_belajar::where('guru_id', $d->guru_id)->where('semester_id', request()->semester_id)->where('tingkat', 0)->first();
                    if($find){
                        $user->attachRole('instruktur', request()->periode_aktif);
                    }
                    $find = Jurusan_sp::whereHas('rombongan_belajar', function($query){
                        $query->where('semester_id', request()->semester_id);
                    })->where('guru_id', $d->guru_id)->first();
                    if($find){
                        $user->attachRole('kajur', request()->periode_aktif);
                    }
                }
            });
            $data = [
                'icon' => 'success',
                'text' => 'Pengguna Guru berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            User::whereRoleIs('pd', request()->periode_aktif)->whereDoesntHave('pd')->delete();
            Peserta_didik::doesntHave('pengguna')->whereHas('anggota_rombel', function($query){
                $query->where('semester_id', semester_id());
            })->orderBy('peserta_didik_id')->chunk(200, function ($data){
                foreach ($data as $d) {
                    $new_password = strtolower(Str::random(8));
                    $user = User::where('peserta_didik_id', $d->peserta_didik_id)->first();
                    if(!$user){
                        $user_email = $this->check_email($d, 'peserta_didik_id');
                        $user = User::create([
                            'name' => $d->nama,
                            'email' => $user_email,
                            'password' => bcrypt($new_password),
                            'default_password' => $new_password,
                            'sekolah_id'	=> $d->sekolah_id,
                            'peserta_didik_id'	=> $d->peserta_didik_id,
                        ]);
                    } else {
                        $user->peserta_didik_id = $d->peserta_didik_id;
                        $user->name = $d->nama_lengkap;
                        $user->save();
                    }
                    if(!$user->hasRole('pd', request()->periode_aktif)){
                        $user->attachRole('pd', request()->periode_aktif);
                    }
                }
            });
            $data = [
                'icon' => 'success',
                'text' => 'Pengguna Peserta Didik berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        }
        return response()->json($data);
    }
    private function check_email($user, $field){
        $loggedUser = auth()->user();
        $random = Str::random(8);
		$user->email = ($user->email != $loggedUser->email) ? $user->email : strtolower($random).'@smkn1sampang.sch.id';
		$user->email = strtolower($user->email);
        if($field == 'guru_id'){
            $find_user_email = User::where('email', $user->email)->where($field, '<>', $user->ptk_id)->first();
		} else {
            $find_user_email = User::where('email', $user->email)->where($field, '<>', $user->peserta_didik_id)->first();
		}
        $find_user_email = User::where('email', $user->email)->first();
		if($find_user_email){
			$user->email = strtolower($random).'@smkn1sampang.sch.id';
		}
        return $user->email;
    }
    public function list(){
        $team = Team::where('name', request()->periode_aktif)->first();
        $data = User::with(['roles' => function($query) use ($team){
            $query->wherePivot('team_id', $team->id);
        }])->where($this->kondisi())->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('name', 'ilike', '%'.request()->q.'%');
            $query->where($this->kondisi());
            $query->orWhere('email', 'ilike', '%'.request()->q.'%');
            $query->where($this->kondisi());
        })->when(request()->role_id, function($query) {
            $query->whereRoleIs(request()->role_id, request()->periode_aktif);
        })
        ->paginate(request()->per_page);
        $roles = Role::select('name', 'display_name')->whereNotIn('name', ['administrator', 'guru', 'super'])->get();
        return response()->json(['status' => 'success', 'data' => $data, 'roles' => $roles]);
    }
    public function detil(){
        $team = Team::where('name', periode_aktif())->first();
        $user = User::with(['roles' => function($query) use ($team){
            $query->wherePivot('team_id', $team->id);
        }])->find(request()->user_id);
        $data = [
            'user' => $user,
            //'roles' => ($user->hasRole('pd', periode_aktif())) ? Role::select('name', 'display_name')->whereIn('name', ['pd'])->get() : Role::select('name', 'display_name')->whereNotIn('name', ['administrator', 'guru'])->get(),
            'roles' => Role::select('id', 'name', 'display_name')->where(function($query) use ($user){
                if($user->hasRole('pd', periode_aktif())){
                    $query->whereIn('name', ['pd']);
                } else {
                    $query->whereNotIn('name', ['administrator', 'guru', 'pd', 'walas', 'instruktur', 'projek', 'pembimbing']);
                }
            })->get(),
            'akses' => $user->hasRole('guru', periode_aktif()),
        ];
        return response()->json($data);
    }
    private function kondisi(){
        return function($query){
            $query->whereRoleIs(['guru', 'pd'], request()->periode_aktif);
        };
    }
    public function hapus(){
        if(User::where('user_id', request()->user_id)->delete()){
            $data = [
                'icon' => 'success',
                'text' => 'Pengguna berhasil dihapus',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Pengguna gagal dihapus. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);        
    }
    public function reset_password(){
        $user = User::find(request()->user_id);
        $user->password = bcrypt($user->default_password);
        if($user->save()){
            $data = [
                'icon' => 'success',
                'text' => 'Password Pengguna berhasil direset',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'danger',
                'text' => 'Password Pengguna gagal direset. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data); 
    }
    public function foto(Request $request){
        $request->validate([
            'foto' => 'required|mimes:jpg,jpeg,png',
        ]);
        $foto = $request->foto->store('public/images');
        $generated_new_name = basename($foto);
        $user = User::with(['guru', 'pd'])->find(request()->user_id);
        $user->photo = $generated_new_name;
        $user->save();
        if($user->guru){
            $user->guru->photo = $generated_new_name;
            $user->guru->save();
        }
        if($user->pd){
            $user->pd->photo = $generated_new_name;
            $user->pd->save();
        }
        $data = [
            'icon' => 'success',
            'text' => 'Foto Profil berhasil diperbaharui',
            'title' => 'Berhasil',
            'foto' => $generated_new_name,
        ];
        return response()->json($data); 
    }
    public function ganti_password(){
        request()->validate([
            'password' => 'required|confirmed',
        ]);
        $user = request()->user();
        $user->password = bcrypt(request()->password);
        if($user->save()){
            $data = [
                'icon' => 'success',
                'text' => 'Password Anda berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'Password gagal diperbaharui. Silahkan coba beberapa saat lagi',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function update_role(){
        $user = User::find(request()->user_id);
        $roles = Role::whereNotIn('name', array_filter(request()->hak_akses))->get();
        //$user->attachRole($guruRole, request()->periode_aktif);
        $user->attachRoles(array_filter(request()->hak_akses), periode_aktif());
        $user->detachRoles($roles, periode_aktif());
        $data = [
            'icon' => 'success',
            'text' => 'Hak akses pengguna berhasil diperbaharui',
            'title' => 'Berhasil',
        ];
        return response()->json($data);
    }
    public function profile_pd(){
      $pd = Peserta_didik::with(['desa', 'kecamatan', 'kabupaten', 'provinsi', 'agama', 'pekerjaan_ayah', 'pekerjaan_ibu'])->find(request()->peserta_didik_id);
      $data = [
        'detil' => $pd,
        'provinsi' => Indonesia::allProvinces(),
        'kabupaten' => get_kabupaten($pd->provinsi_id),
        'kecamatan' => get_kecamatan($pd->kabupaten_id),
        'desa' => get_desa($pd->kecamatan_id),
        'agama' => Agama::get(),
        'pekerjaan' => Pekerjaan::get(),
        'cita' => Cita::get(),
        'suggestedPages' => [
          [
            'avatar' => '/img/avatars/12-small.png',
            'username' => 'Peter Reed',
            'subtitle' => 'Company',
            'favorite' => false,
          ],
        ],
        'twitterFeeds' => [
            [
              'imgUrl' => '/img/avatars/5-small.png',
              'title' => 'Gertrude Stevens',
              'id' => 'tiana59 ',
              'tags' => '#design #fasion',
              'desc' => 'I love cookie chupa chups sweet tart apple pie ⭐️ chocolate bar.',
              'favorite' => false,
            ],
            [
              'imgUrl' => '/img/avatars/12-small.png',
              'title' => 'Lura Jones',
              'id' => 'tiana59 ',
              'tags' => '#vuejs #code #coffeez',
              'desc' => 'Halvah I love powder jelly I love cheesecake cotton candy. 😍',
              'favorite' => true,
            ],
            [
              'imgUrl' => '/img/avatars/1-small.png',
              'title' => 'Norman Gross',
              'id' => 'tiana59 ',
              'tags' => '#sketch #uiux #figma',
              'desc' => 'Candy jelly beans powder brownie biscuit. Jelly marzipan oat cake cake.',
              'favorite' => false,
            ],
          ],
          'suggestions' => [
            [
              'avatar' => '/img/portrait/small/avatar-s-9.jpg',
              'name' => 'Peter Reed',
              'mutualFriend' => '6 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-6.jpg',
              'name' => 'Harriett Adkins',
              'mutualFriend' => '3 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-7.jpg',
              'name' => 'Juan Weaver',
              'mutualFriend' => '1 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-8.jpg',
              'name' => 'Claudia Chandler',
              'mutualFriend' => '16 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-1.jpg',
              'name' => 'Earl Briggs',
              'mutualFriend' => '4 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-10.jpg',
              'name' => 'Jonathan Lyons',
              'mutualFriend' => '25 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-9.jpg',
              'name' => 'Peter Reed',
              'mutualFriend' => '6 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-6.jpg',
              'name' => 'Harriett Adkins',
              'mutualFriend' => '3 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-7.jpg',
              'name' => 'Juan Weaver',
              'mutualFriend' => '1 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-8.jpg',
              'name' => 'Claudia Chandler',
              'mutualFriend' => '16 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-1.jpg',
              'name' => 'Earl Briggs',
              'mutualFriend' => '4 Mutual Friends',
            ],
            [
              'avatar' => '/img/portrait/small/avatar-s-10.jpg',
              'name' => 'Jonathan Lyons',
              'mutualFriend' => '25 Mutual Friends',
            ],
            [
                'avatar' => '/img/portrait/small/avatar-s-9.jpg',
                'name' => 'Peter Reed',
                'mutualFriend' => '6 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-6.jpg',
                'name' => 'Harriett Adkins',
                'mutualFriend' => '3 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-7.jpg',
                'name' => 'Juan Weaver',
                'mutualFriend' => '1 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-8.jpg',
                'name' => 'Claudia Chandler',
                'mutualFriend' => '16 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-1.jpg',
                'name' => 'Earl Briggs',
                'mutualFriend' => '4 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-10.jpg',
                'name' => 'Jonathan Lyons',
                'mutualFriend' => '25 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-9.jpg',
                'name' => 'Peter Reed',
                'mutualFriend' => '6 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-6.jpg',
                'name' => 'Harriett Adkins',
                'mutualFriend' => '3 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-7.jpg',
                'name' => 'Juan Weaver',
                'mutualFriend' => '1 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-8.jpg',
                'name' => 'Claudia Chandler',
                'mutualFriend' => '16 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-1.jpg',
                'name' => 'Earl Briggs',
                'mutualFriend' => '4 Mutual Friends',
              ],
              [
                'avatar' => '/img/portrait/small/avatar-s-10.jpg',
                'name' => 'Jonathan Lyons',
                'mutualFriend' => '25 Mutual Friends',
              ],
          ],
          'polls' => [
            [
              'name' => 'RDJ',
              'result' => '82%',
              'votedUser' => [
                [
                  'img' => '/img/portrait/small/avatar-s-12.jpg',
                  'username' => 'Tonia Seabold',
                ],
                [
                  'img' => '/img/portrait/small/avatar-s-5.jpg',
                  'username' => 'Carissa Dolle',
                ],
                [
                  'img' => '/img/portrait/small/avatar-s-9.jpg',
                  'username' => 'Kelle Herrick',
                ],
                [
                  'img' => '/img/portrait/small/avatar-s-10.jpg',
                  'username' => 'Len Bregantini',
                ],
                [
                  'img' => '/img/portrait/small/avatar-s-11.jpg',
                  'username' => 'John Doe',
                ],
              ],
            ],
            [
              'name' => 'Chris Hemswort',
              'result' => '67%',
              'votedUser' => [
                [
                  'img' => '/img/portrait/small/avatar-s-9.jpg',
                  'username' => 'Tonia Seabold',
                ],
                [
                  'img' => '/img/portrait/small/avatar-s-1.jpg',
                  'username' => 'Carissa Dolle',
                ],
                [
                  'img' => '/img/portrait/small/avatar-s-8.jpg',
                  'username' => 'Jonathan Lyons',
                ],
              ],
            ],
          ],
        ];
    return response()->json($data);
  }
}
