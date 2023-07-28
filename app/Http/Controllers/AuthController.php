<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\Mst_wilayah;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use Validator;
use Storage;

class AuthController extends Controller
{
    public function username()
    {
        $login = request()->input('username');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';        
        request()->merge([$field => $login]);
        return $field;
        //return 'username';
    }
    public function register(Request $request)
    {
        $npsn = [
            '20606817', 
            '70009413', 
            '20613916', 
            '10300825', 
            '20606899', 
            '20606247', 
            '20607928', 
            '20607928', 
            '20613916', 
            '20606247', 
            '20606905', 
            '20600458', 
            '69734356',
            '20522636',
            '20536293',
        ];
        if(!in_array(request()->username, $npsn)){
            return response()->json([
                'errors'=> [
                    'username' => ['NPSN tidak terdaftar untuk melakukan uji coba!'],
                ],
            ]);
        }
        $request->validate(
            [
                'username' => 'required',
                'email'=>'required|string|unique:users',
                'password'=>'required|string',
            ],
            [
                'name.required' => 'Nama tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Email telah terdaftar',
                'password.required' => 'Password tidak boleh kosong'
            ]
        );
        try {
            $data_sync = [
                'npsn' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
            ];
            $response = Http::post('http://sync.erapor-smk.net/api/v7/register', $data_sync);
            $data = $response->object();
            if($response->successful()){
                return $this->create_user($data, $request->email, $request->password);
            } else {
                return response()->json([
                    'error'=> TRUE,
                    'message' => $data->message
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'error'=> TRUE,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function login(Request $request)
    {
        $login = request()->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $namaField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'Email' : 'NPSN';
        request()->merge([$field => $login]);
        $request->validate(
            [
                $field => 'required|string|exists:users,'.$field ,
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ],
            [
                $field.'.required' => $namaField.' tidak boleh kosong',
                $field.'.exists' => $namaField.' tidak terdaftar',
                'password.required' => 'Password tidak boleh kosong'
            ]
        );
        $credentials = request([$field,'password']);
        //return response()->json($credentials);
        if(!Auth::attempt($credentials)){
            return response()->json([
                'user' => NULL,
                'message' => [
                    'password' => 'Password salah!',
                ]
            ],422);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;
        $user = $this->loggedUser($user);
        return response()->json([
            'accessToken' =>$token,
            'user' => $user,
            'token_type' => 'Bearer',
        ]);
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
        'message' => 'Successfully logged out'
        ]);

    }
    private function loggedUser($user){
        $semester = Semester::find(request()->semester_id);
        if($user->sekolah_id && !$user->peserta_didik_id && !$user->guru_id){
            $team = Team::updateOrCreate([
                'name' => $semester->nama,
                'display_name' => $semester->nama,
                'description' => $semester->nama,
            ]);
            if(!$user->hasRole('admin', $semester->nama)){
                $user->attachRole('admin', $team);
            }
        }
        if($user->hasRole('admin', $semester->nama)){
            $user->ability = [
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
                    'subject' => 'Rombel'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Akses'
                ]
            ];
        } elseif($user->hasRole('guru', $semester->nama)){
            $waka = [];
            $wali = [];
            $kaprog = [];
            $projek = [];
            $internal = [];
            $pembina_ekskul = [];
            $pembimbing = [];
            if($user->hasRole('waka', $semester->nama)){ 
                $waka = [
                    [
                        'action' => 'read',
                        'subject' => 'Waka'
                    ],
                    [
                        'action' => 'read',
                        'subject' => 'Wali'
                    ],
                    [
                        'action' => 'read',
                        'subject' => 'Rombel'
                    ],
                ];
            }
            if($user->hasRole('wali', $semester->nama)){
                if($semester->semester == 1){
                    $wali = [
                        [
                            'action' => 'read',
                            'subject' => 'Password_pd',
                        ],
                        [
                            'action' => 'read',
                            'subject' => 'Wali'
                        ],
                    ];
                } else {
                    $wali = [
                        [
                            'action' => 'read',
                            'subject' => 'Password_pd',
                        ],
                        [
                            'action' => 'read',
                            'subject' => 'Wali'
                        ],
                        [
                            'action' => 'read',
                            'subject' => 'Kenaikan'
                        ],
                    ];
                }
                if($semester->tahun_ajaran_id < '2023'){
                    $wali = array_merge($wali, [
                        [
                            'action' => 'read',
                            'subject' => 'Wali_pkl',
                        ],
                    ]);
                }
            }
            if($user->hasRole('kaprog', $semester->nama)){ 
                $kaprog = [
                    [
                        'action' => 'read',
                        'subject' => 'Kaprog'
                    ],
                ];
            }
            if($user->hasRole('guru-p5', $semester->nama)){ 
                $projek = [
                    [
                        'action' => 'read',
                        'subject' => 'Projek'
                    ],
                ];
            }
            if($user->hasRole('internal', $semester->nama)){ 
                $internal = [
                    [
                        'action' => 'read',
                        'subject' => 'Internal'
                    ],
                ];
            }
            if($user->hasRole('pembina_ekskul', $semester->nama)){ 
                $internal = [
                    [
                        'action' => 'read',
                        'subject' => 'Ekskul'
                    ],
                ];
            }
            if($user->hasRole('pembimbing', $semester->nama) && $semester->tahun_ajaran_id >= '2023'){ 
                $pembimbing = [
                    [
                        'action' => 'read',
                        'subject' => 'Pkl'
                    ],
                ];
            }
            $guru = [
                [
                    'action' => 'read',
                    'subject' => 'Web'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Guru'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Akses'
                ]
            ];
            $user->ability = array_filter(array_merge($guru, $waka, $wali, $kaprog, $projek, $internal, $pembimbing));
        } elseif($user->hasRole('siswa', $semester->nama)){ 
            $user->ability = [
                [
                    'action' => 'read',
                    'subject' => 'Web'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Siswa'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Akses'
                ],
            ];
        } else { 
            $user->ability = [
                [
                    'action' => 'read',
                    'subject' => 'Web'
                ]
            ];
        }
        if($user->allPermissions('display_name', $semester->nama)->count()){
            $user->role = $user->allPermissions('display_name', $semester->nama)->implode('display_name', ', ');
            $user->roles = $user->allPermissions('name', $semester->nama)->pluck('name')->toArray();
            $user->check = 'ada';
        } else {
            $user->role = $user->roles->unique()->implode('display_name', ', ');
            $user->roles = $user->roles->unique()->pluck('name')->toArray();
            $user->check = 'ga ada';
        }
        $user->sekolah = $user->sekolah;
        $user->semester = $semester;
        return $user;
    }
    public function semester(){
        $data = [
            'semester' => Semester::whereHas('tahun_ajaran', function($query){
                $query->where('periode_aktif', 1);
              })->orderBy('semester_id', 'DESC')->get(),
              'semester_id' => Semester::where('periode_aktif', 1)->first()->semester_id,
        ];
        return response()->json($data);
    }
    private function create_user($data, $email, $password){
        if(!$data->data){
            return response()->json([
                'error'=> TRUE,
                'message' => $data->message,
                'data' => $data,
            ]);
        }
        $set_data = $data->data->sekolah;
        if($set_data->bentuk_pendidikan_id == '15'){
            $get_kode_wilayah = $set_data->wilayah;
            $kode_wilayah = $set_data->kode_wilayah;
            $kecamatan = '-';
            $kabupaten = '-';
            $provinsi = '-';
            if($get_kode_wilayah){
                $kode_wilayah = $get_kode_wilayah->kode_wilayah;
                if($get_kode_wilayah->parrent_recursive){
                    $kecamatan = $get_kode_wilayah->parrent_recursive->nama;
                    if($get_kode_wilayah->parrent_recursive->parrent_recursive){
                        $kabupaten = $get_kode_wilayah->parrent_recursive->parrent_recursive->nama;
                        if($get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive){
                            $provinsi = $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->nama;
                            Mst_wilayah::updateOrCreate(
                                [
                                    'kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->kode_wilayah,
                                ],
                                [
                                    'nama' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->nama,
                                    'id_level_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->id_level_wilayah,
                                    'mst_kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->mst_kode_wilayah,
                                    'negara_id' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->negara_id,
                                    'last_sync' => now(),
                                ]
                            );
                        }
                        Mst_wilayah::updateOrCreate(
                            [
                                'kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->kode_wilayah,
                            ],
                            [
                                'nama' => $get_kode_wilayah->parrent_recursive->parrent_recursive->nama,
                                'id_level_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->id_level_wilayah,
                                'mst_kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->mst_kode_wilayah,
                                'negara_id' => $get_kode_wilayah->parrent_recursive->parrent_recursive->negara_id,
                                'last_sync' => now(),
                            ]
                        );
                    }
                    Mst_wilayah::updateOrCreate(
                        [
                            'kode_wilayah' => $get_kode_wilayah->parrent_recursive->kode_wilayah,
                        ],
                        [
                            'nama' => $get_kode_wilayah->parrent_recursive->nama,
                            'id_level_wilayah' => $get_kode_wilayah->parrent_recursive->id_level_wilayah,
                            'mst_kode_wilayah' => $get_kode_wilayah->parrent_recursive->mst_kode_wilayah,
                            'negara_id' => $get_kode_wilayah->parrent_recursive->negara_id,
                            'last_sync' => now(),
                        ]
                    );
                }
                Mst_wilayah::updateOrCreate(
                    [
                        'kode_wilayah' => $get_kode_wilayah->kode_wilayah,
                    ],
                    [
                        'nama' => $get_kode_wilayah->nama,
                        'id_level_wilayah' => $get_kode_wilayah->id_level_wilayah,
                        'mst_kode_wilayah' => $get_kode_wilayah->mst_kode_wilayah,
                        'negara_id' => $get_kode_wilayah->negara_id,
                        'last_sync' => now(),
                    ]
                );
            }
            $sekolah = Sekolah::updateOrCreate(
                ['sekolah_id' => $set_data->sekolah_id],
                [
                    'npsn' 					=> $set_data->npsn,
                    'nss' 					=> $set_data->nss,
                    'nama' 					=> $set_data->nama,
                    'alamat' 				=> $set_data->alamat_jalan,
                    'desa_kelurahan'		=> $set_data->desa_kelurahan,
                    'kode_wilayah'			=> $kode_wilayah,
                    'kecamatan' 			=> $kecamatan,
                    'kabupaten' 			=> $kabupaten,
                    'provinsi' 				=> $provinsi,
                    'kode_pos' 				=> $set_data->kode_pos,
                    'lintang' 				=> $set_data->lintang,
                    'bujur' 				=> $set_data->bujur,
                    'no_telp' 				=> $set_data->nomor_telepon,
                    'no_fax' 				=> $set_data->nomor_fax,
                    'email' 				=> $set_data->email,
                    'website' 				=> $set_data->website,
                    'status_sekolah'		=> $set_data->status_sekolah,
                    'last_sync'				=> now(),
                ]
            );
            $semester = Semester::where('periode_aktif', 1)->first();
            $user = User::create([
                'sekolah_id' => $sekolah->sekolah_id,
                'name' => 'Administrator',
                'email' => $email,
                'password' => bcrypt($password),
            ]);
            $adminRole = Role::where('name', 'admin')->first();
            $team = Team::updateOrCreate([
                'name' => $semester->nama,
                'display_name' => $semester->nama,
                'description' => $semester->nama,
            ]);
            $user->attachRole($adminRole, $team);
            return response()->json([
                'error'=> FALSE,
                'message' => 'Register berhasil'
            ]);
        } else {
            return response()->json([
                'error'=> TRUE,
                'message' => 'Jenjang Sekolah Salah'
            ]);
        }
    }
}
