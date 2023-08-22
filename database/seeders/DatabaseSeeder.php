<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use App\Models\Permission;
use App\Models\Tahun_ajaran;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Jenis_penilaian;
use App\Models\Sekolah;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\VillagesSeeder;
use Laravolt\Indonesia\Seeds\DistrictsSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::whereNotNull('email')->delete();
        $roles = [
            [
                'name' => 'administrator',
                'display_name' => 'Administrator',
                'description' => 'Administrator',
                'create' => true,
            ],
            [
                'name' => 'kepsek',
                'display_name' => 'Kepala Sekolah',
                'description' => 'Kepala Sekolah',
                'create' => false,
            ],
            [
                'name' => 'kajur',
                'display_name' => 'Ketua Jurusan',
                'description' => 'Ketua Jurusan',
                'create' => false,
            ],
            [
                'name' => 'wakakur',
                'display_name' => 'Waka Kurikulum',
                'description' => 'Waka Kurikulum',
                'create' => false,
            ],
            [
                'name' => 'wakahumas',
                'display_name' => 'Waka Humas',
                'description' => 'Waka Humas',
                'create' => false,
            ],
            [
                'name' => 'wakasiswa',
                'display_name' => 'Waka Kesiswaan',
                'description' => 'Waka Kesiswaan',
                'create' => false,
            ],
            [
                'name' => 'bk',
                'display_name' => 'Guru BP/BK',
                'description' => 'Guru BP/BK',
                'create' => false,
            ],
            [
                'name' => 'walas',
                'display_name' => 'Wali Kelas',
                'description' => 'Wali Kelas',
                'create' => false,
            ],
            [
                'name' => 'guru',
                'display_name' => 'Guru',
                'description' => 'Guru',
                'create' => false,
            ],
            [
                'name' => 'pengajar',
                'display_name' => 'Guru Mata Pelajaran',
                'description' => 'Guru Mata Pelajaran',
                'create' => false,
            ],
            [
                'name' => 'piket',
                'display_name' => 'Guru Piket',
                'description' => 'Guru Piket',
                'create' => false,
            ],
            [
                'name' => 'pd',
                'display_name' => 'Peserta Didik',
                'description' => 'Peserta Didik',
                'create' => false,
            ]
        ];
        $tahun_ajaran = [
            [
                'tahun_ajaran_id' => 2023,
                'nama' => '2023/2024',
                'periode_aktif' => 1,
                'tanggal_mulai' => '2023-06-01',
                'tanggal_selesai' => '2024-07-07',
                'semester' => [
                    [
                        'semester_id' => '20231',
                        'nama' => '2023/2024 Ganjil',
                        'semester' => 1,
                        'periode_aktif' => 1,
                        'tanggal_mulai' => '2023-06-01',
                        'tanggal_selesai' => '2023-12-31',
                        'tanggal_cetak' => '2023-12-15',
                    ],
                    [
                        'semester_id' => '20232',
                        'nama' => '2023/2024 Genap',
                        'semester' => 2,
                        'periode_aktif' => 0,
                        'tanggal_mulai' => '2024-01-01',
                        'tanggal_selesai' => '2024-07-07',
                        'tanggal_cetak' => '2024-06-15',
                    ]
                ]
            ]
        ];
        foreach($tahun_ajaran as $tahun){
            $new = Tahun_ajaran::updateOrCreate(
                [
                    'tahun_ajaran_id' => $tahun['tahun_ajaran_id'],
                ],
                [
                    'nama' => $tahun['nama'],
                    'periode_aktif' => $tahun['periode_aktif'],
                    'tanggal_mulai' => $tahun['tanggal_mulai'],
                    'tanggal_selesai' => $tahun['tanggal_selesai'],
                ],
            );
            foreach($tahun['semester'] as $semester){
                $new_semester = Semester::updateOrCreate(
                    [
                        'semester_id' => $semester['semester_id'],
                    ],
                    [
                        'tahun_ajaran_id' => $tahun['tahun_ajaran_id'],
                        'nama' => $semester['nama'],
                        'semester' => $semester['semester'],
                        'periode_aktif' => $semester['periode_aktif'],
                        'tanggal_mulai' => $semester['tanggal_mulai'],
                        'tanggal_selesai' => $semester['tanggal_selesai'],
                        'tanggal_cetak' => $semester['tanggal_cetak'],
                    ],
                );
                $team = Team::updateOrCreate([
                    'name' => $new_semester->nama,
                    'display_name' => $new_semester->nama,
                    'description' => $new_semester->nama,
                ]);
            }
        }
        $all_team = Team::get();
        foreach($roles as $role){
            $new = Role::firstOrCreate(
                [
                    'name' => $role['name'],
                ],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                ]
            );
            if($role['create']){
                $user = User::updateOrCreate(
                    [
                        'email' => $role['name'].'@smkn1sampang.sch.id',
                    ],
                    [
                        //'username' => $role['name'],
                        'name' => $role['display_name'],
                        'password' => bcrypt('12345678')
                    ]
                );
                foreach($all_team as $team){
                    if(!$user->hasRole($role['name'], $team->name)){
                        $user->attachRole($new, $team->name); 
                    }
                }
            }
        }
        $data = [
            [
                'key' => 'app_name',
                'value' => 'SMEKNESA',
            ],
            [
                'key' => 'app_version',
                'value' => '1.0.0',
            ],
            [
                'key' => 'db_version',
                'value' => '1.0',
            ],
            [
                'key' => 'semester_id',
                'value' => '20231',
            ],
            [
                'key' => 'periode_aktif',
                'value' => '2023/2024 Ganjil',
            ]
        ];
        foreach($data as $d){
            Setting::updateOrCreate(
                [
                    'key' => $d['key'],
                ],
                [
                    'value' => $d['value'],
                ]
            );
        }
        $data = ['Assesment Diagnosis', 'Assesment Formatif', 'Assesment Sumatif'];
        foreach($data as $d){
            Jenis_penilaian::updateOrCreate(['nama' => $d]);
        }
        $abilities = [
            [
                'action' => 'read',
                'subject' => 'Web',
                'roles' => ['administrator', 'guru', 'kepsek', 'pengajar', 'pd', 'piket', 'wakakur', 'walas', 'kajur', 'wakahumas', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Beranda',
                'roles' => ['administrator', 'guru', 'kepsek', 'pengajar', 'piket', 'wakakur', 'walas', 'kajur', 'wakahumas', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Profile',
                'roles' => ['administrator', 'guru', 'kepsek', 'pengajar', 'piket', 'wakakur', 'walas', 'kajur', 'wakahumas', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Ref_Sekolah',
                'roles' => ['administrator', 'kepsek', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Ref_Mapel',
                'roles' => ['administrator', 'pengajar', 'wakakur', 'kajur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Ref_Jurusan',
                'roles' => ['administrator', 'wakakur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Ref_Kelas',
                'roles' => ['administrator', 'wakakur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Remedial',
                'roles' => ['wakakur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Ref_Pd',
                'roles' => ['administrator', 'wakakur', 'walas', 'kajur', 'wakasiswa'],
            ],
            [
                'action' => 'read',
                'subject' => 'Ref_Pd_Keluar',
                'roles' => ['administrator', 'wakasiswa', 'bk', 'tu'],
            ],
            [
                'action' => 'read',
                'subject' => 'Ref_Guru',
                'roles' => ['administrator', 'kepsek', 'wakakur', 'wakahumas', 'wakasiswa'],
            ],
            [
                'action' => 'read',
                'subject' => 'Ref_Periodik',
                'roles' => ['administrator'],
            ],
            [
                'action' => 'read',
                'subject' => 'System',
                'roles' => ['administrator'],
            ],
            [
                'action' => 'read',
                'subject' => 'Presensi_Guru',
                'roles' => ['piket'],
            ],
            [
                'action' => 'read',
                'subject' => 'Presensi_Pd',
                'roles' => ['piket'],
            ],
            [
                'action' => 'read',
                'subject' => 'Presensi_Rekap_Guru',
                'roles' => ['walas', 'wakakur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Presensi_Rekap_Pd',
                'roles' => ['walas', 'wakakur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Akun',
                'roles' => ['administrator'],
            ],
            [
                'action' => 'read',
                'subject' => 'Jadwal',
                'roles' => ['administrator', 'piket', 'wakakur', 'walas', 'kajur', 'wakahumas', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Perencanaan',
                'roles' => ['pengajar', 'wakakur', 'walas', 'kajur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Penilaian',
                'roles' => ['pengajar', 'wakakur', 'kajur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Rekap_Nilai',
                'roles' => ['pengajar', 'wakakur', 'walas', 'kajur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Modul',
                'roles' => ['pengajar', 'wakakur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Kenaikan',
                'roles' => ['walas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Kelulusan',
                'roles' => ['kepsek', 'wakakur', 'kajur', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Dudi',
                'roles' => ['administrator', 'kepsek', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Pembimbing',
                'roles' => ['administrator', 'walas', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Permohonan',
                'roles' => ['administrator', 'walas', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Absensi',
                'roles' => ['administrator', 'walas', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Monitoring',
                'roles' => ['administrator'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Nilai',
                'roles' => ['administrator', 'wakakur', 'walas', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Sertifikat',
                'roles' => ['administrator', 'walas', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Sekolah',
                'roles' => ['administrator', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Pelanggaran',
                'roles' => ['administrator', 'walas', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Rekap',
                'roles' => ['administrator', 'walas', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Bukti',
                'roles' => ['administrator', 'walas', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Ortu',
                'roles' => ['administrator', 'walas', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Pernyataan',
                'roles' => ['administrator', 'walas', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Peringatan',
                'roles' => ['administrator', 'walas', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Mundur',
                'roles' => ['administrator', 'walas', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Whatsapp',
                'roles' => ['administrator'],
            ],
            [
                'action' => 'read',
                'subject' => 'Hak_Akses',
                'roles' => ['administrator'],
            ],
            [
                'action' => 'read',
                'subject' => 'Wali',
                'roles' => ['walas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Profile_Pd',
                'roles' => ['pd'],
            ],
            [
                'action' => 'read',
                'subject' => 'Rekapitulasi',
                'roles' => ['wakakur', 'wakasiswa'],
            ],
        ];
        Permission::truncate();
        foreach($abilities as $ab){
            $permission = Permission::updateOrCreate([
                'name' => $ab['subject'],
                'display_name' => $ab['subject'],
                'description' => $ab['action'],
            ]);
            foreach($ab['roles'] as $role){
                $r = Role::where('name', $role)->first();
                $r->attachPermission($permission);
            }
        }
        //20528658
        $response = Http::get('http://sync.erapor-smk.net/api/v7/sekolah/20528658');
        if($response->status() == 200){
            $data = $response->object();
            $e = $data->data;
            Sekolah::updateOrCreate(
                [
                    'sekolah_id' => $e->sekolah_id,
                ],
                [
                    'npsn' => $e->npsn,
                    'nama' => $e->nama,
                    'nss' => $e->nss,
                    'alamat_jalan' => $e->alamat_jalan,
                    'kode_pos' => $e->kode_pos,
                    'lintang' => $e->lintang,
                    'bujur' => $e->bujur,
                    'no_telp' => $e->no_telp,
                    'no_fax' => $e->no_fax,
                    'email' => $e->email,
                    'website' => $e->website,
                    'bentuk_pendidikan_id' => $e->bentuk_pendidikan_id,
                    'status_sekolah' => $e->status_sekolah,
                ]
            );
        }
        $this->call([
            ProvincesSeeder::class,
            CitiesSeeder::class,
            DistrictsSeeder::class,
            VillagesSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
