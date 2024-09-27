<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;
class GenerateAkses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:akses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $abilities = [
            [
                'role' => 'administrator',
                'display_name' => 'Administrator',
                'akses' => ['Web', 'Beranda', 'Profile', 'Ref_Periodik', 'Ref_Sekolah', 'Ref_Guru', 'Ref_Mapel', 'Ref_Jurusan', 'Ref_Kelas', 'Ref_Pd', 'Ref_Pd_Keluar', 'System', 'Akun', 'Jadwal', 'Magang_Dudi', 'Magang_Pembimbing', 'Magang_Permohonan', 'Magang_Monitoring', 'Tatib_Sekolah', 'Tatib_Pelanggaran', 'Tatib_Rekap', 'Tatib_Bukti', 'Tatib_Ortu', 'Tatib_Pernyataan', 'Tatib_Peringatan', 'Tatib_Mundur', 'Whatsapp', 'Hak_Akses', 'Guru_BK', 'Ref_Dudi'],
                'action' => 'read',
            ],
            [
                'role' => 'guru',
                'display_name' => 'Guru',
                'akses' => ['Web', 'Beranda', 'Profile'],
                'action' => 'read',
            ],
            [
                'role' => 'kepsek',
                'display_name' => 'Kepala Sekolah',
                'akses' => ['Ref_Sekolah', 'Ref_Guru', 'Ref_Mapel', 'Magang_Dudi'],
                'action' => 'read',
            ],
            [
                'role' => 'pengajar',
                'display_name' => 'Guru Mata Pelajaran',
                'akses' => ['Perencanaan', 'Penilaian', 'Rekap_Nilai', 'Modul'],
                'action' => 'read',
            ],
            [
                'role' => 'pd',
                'display_name' => 'Peserta Didik',
                'akses' => ['Profile_Pd'],
                'action' => 'read',
            ],
            [
                'role' => 'piket',
                'display_name' => 'Guru Piket',
                'akses' => ['Ref_Mapel', 'Presensi_Guru', 'Presensi_Pd', 'Jadwal', 'Rekap_Absen_Siswa'],
                'action' => 'read',
            ],
            [
                'role' => 'wakakur',
                'display_name' => 'Waka Kurikulum',
                'akses' => ['Ref_Mapel', 'Ref_Jurusan', 'Ref_Kelas', 'Remedial', 'Ref_Pd', 'Ref_Guru', 'Rekap_Absen_Guru', 'Jadwal', 'Perencanaan', 'Penilaian', 'Rekap_Nilai', 'Modul', 'Magang_Nilai', 'Monitoring'],
                'action' => 'read',
            ],
            [
                'role' => 'walas',
                'display_name' => 'Wali Kelas',
                'akses' => ['Ref_Mapel', 'Ref_Pd', 'Rekap_Absen_Siswa', 'Jadwal', 'Kenaikan', 'Wali'],
                'action' => 'read',
            ],
            [
                'role' => 'kajur',
                'display_name' => 'Ketua Jurusan',
                'akses' => ['Ref_Sekolah', 'Ref_Mapel', 'Ref_Pd', 'Jadwal', 'Magang_Absensi', 'Magang_Nilai', 'Tatib_Pelanggaran', 'Tatib_Rekap', 'Tatib_Bukti', 'Tatib_Ortu', 'Tatib_Pernyataan', 'Tatib_Peringatan', 'Tatib_Mundur', 'Kajur'],
                'action' => 'read',
            ],
            [
                'role' => 'wakahumas',
                'display_name' => 'Waka Humas',
                'akses' => ['Ref_Mapel', 'Ref_Guru', 'Jadwal', 'Magang_Absensi', 'Magang_Nilai', 'Post'],
                'action' => 'read',
            ],
            [
                'role' => 'wakasiswa',
                'display_name' => 'Waka Kesiswaan',
                'akses' => ['Ref_Sekolah', 'Ref_Mapel', 'Ref_Pd', 'Ref_Guru', 'Rekap_Absen_Siswa', 'Rekap_Absen_Guru', 'Jadwal', 'Tatib_Sekolah', 'Tatib_Pelanggaran', 'Tatib_Rekap', 'Tatib_Bukti', 'Tatib_Ortu', 'Tatib_Pernyataan', 'Tatib_Peringatan', 'Tatib_Mundur'],
                'action' => 'read',
            ],
            [
                'role' => 'bk',
                'display_name' => 'Guru BP/BK',
                'akses' => ['Rekap_Absen_Siswa', 'Jadwal', 'Tatib_Sekolah', 'Tatib_Pelanggaran', 'Tatib_Rekap', 'Tatib_Bukti', 'Tatib_Ortu', 'Tatib_Pernyataan', 'Tatib_Peringatan', 'Tatib_Mundur', 'Penilaian_Sikap', 'Perijinan'],
                'action' => 'read',
            ],
            [
                'role' => 'instruktur',
                'display_name' => 'Instruktur Ekskul',
                'akses' => ['Instruktur'],
                'action' => 'read',
            ],
            [
                'role' => 'super',
                'display_name' => 'Super Admin',
                'akses' => ['manage'],
                'action' => 'all',
            ],
            [
                'role' => 'projek',
                'display_name' => 'Koord P5',
                'akses' => ['Projek'],
                'action' => 'read',
            ],
            [
                'role' => 'pembimbing',
                'display_name' => 'Pembimbing PKL',
                'akses' => ['Magang_Absensi', 'Magang_Nilai'],
                'action' => 'read',
            ],
        ];
        Permission::truncate();
        foreach($abilities as $ab){
            $r = Role::firstOrCreate(
                [
                    'name' => $ab['role'],
                ],
                [
                    'display_name' => $ab['display_name'],
                    'description' => $ab['display_name'],
                ]
            );
            foreach($ab['akses'] as $perm){
                $permission = Permission::updateOrCreate([
                    'name' => $perm,
                    'display_name' => $perm,
                    'description' => 'read',
                ]);
                if(!$r->hasPermission($permission->name)){
                    $r->attachPermission($permission);
                }
            }
            
        }
        return Command::SUCCESS;
    }
}
