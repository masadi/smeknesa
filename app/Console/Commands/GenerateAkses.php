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
                'akses' => ['Web', 'Beranda', 'Profile', 'Ref_Sekolah', 'Ref_Guru', 'Ref_Mapel', 'Ref_Jurusan', 'Ref_Kelas', 'Ref_Pd', 'Ref_Pd_Keluar', 'System', 'Akun', 'Jadwal', 'Magang_Dudi', 'Magang_Pembimbing', 'Magang_Permohonan', 'Magang_Absensi', 'Magang_Monitoring', 'Magang_Nilai', 'Magang_Sertifikat', 'Tatib_Sekolah', 'Tatib_Pelanggaran', 'Tatib_Rekap', 'Tatib_Bukti', 'Tatib_Ortu', 'Tatib_Pernyataan', 'Tatib_Peringatan', 'Tatib_Mundur', 'Whatsapp', 'Hak_Akses', 'Guru_BK'],
                'action' => 'read',
            ],
            [
                'role' => 'guru',
                'akses' => ['Web', 'Beranda', 'Profile'],
                'action' => 'read',
            ],
            [
                'role' => 'kepsek',
                'akses' => ['Ref_Sekolah', 'Ref_Guru', 'Ref_Mapel', 'Kelulusan', 'Magang_Dudi'],
                'action' => 'read',
            ],
            [
                'role' => 'pengajar',
                'akses' => ['Perencanaan', 'Penilaian', 'Rekap_Nilai', 'Modul'],
                'action' => 'read',
            ],
            [
                'role' => 'pd',
                'akses' => ['Profile_Pd'],
                'action' => 'read',
            ],
            [
                'role' => 'piket',
                'akses' => ['Ref_Mapel', 'Presensi_Guru', 'Presensi_Pd', 'Jadwal', 'Rekap_Absen_Siswa', 'Rekap_Absen_Guru'],
                'action' => 'read',
            ],
            [
                'role' => 'wakakur',
                'akses' => ['Ref_Mapel', 'Ref_Jurusan', 'Ref_Kelas', 'Remedial', 'Ref_Pd', 'Ref_Guru', 'Rekap_Absen_Guru', 'Jadwal', 'Perencanaan', 'Penilaian', 'Rekap_Nilai', 'Modul', 'Kelulusan', 'Magang_Nilai'],
                'action' => 'read',
            ],
            [
                'role' => 'walas',
                'akses' => ['Ref_Mapel', 'Ref_Pd', 'Rekap_Absen_Siswa', 'Jadwal', 'Kenaikan', 'Wali'],
                'action' => 'read',
            ],
            [
                'role' => 'kajur',
                'akses' => ['Ref_Sekolah', 'Ref_Mapel', 'Ref_Pd', 'Jadwal', 'Kelulusan', 'Magang_Dudi', 'Magang_Pembimbing', 'Magang_Permohonan', 'Magang_Absensi', 'Magang_Nilai', 'Magang_Sertifikat', 'Tatib_Pelanggaran', 'Tatib_Rekap', 'Tatib_Bukti', 'Tatib_Ortu', 'Tatib_Pernyataan', 'Tatib_Peringatan', 'Tatib_Mundur'],
                'action' => 'read',
            ],
            [
                'role' => 'wakahumas',
                'akses' => ['Ref_Mapel', 'Ref_Guru', 'Jadwal', 'Magang_Dudi', 'Magang_Pembimbing', 'Magang_Permohonan', 'Magang_Absensi', 'Magang_Nilai', 'Magang_Sertifikat'],
                'action' => 'read',
            ],
            [
                'role' => 'wakasiswa',
                'akses' => ['Ref_Sekolah', 'Ref_Mapel', 'Ref_Pd', 'Ref_Guru', 'Rekap_Absen_Siswa', 'Jadwal', 'Tatib_Sekolah', 'Tatib_Pelanggaran', 'Tatib_Rekap', 'Tatib_Bukti', 'Tatib_Ortu', 'Tatib_Pernyataan', 'Tatib_Peringatan', 'Tatib_Mundur'],
                'action' => 'read',
            ],
            [
                'role' => 'bk',
                'akses' => ['Ref_Sekolah', 'Rekap_Absen_Siswa', 'Jadwal', 'Kelulusan', 'Tatib_Sekolah', 'Tatib_Pelanggaran', 'Tatib_Rekap', 'Tatib_Bukti', 'Tatib_Ortu', 'Tatib_Pernyataan', 'Tatib_Peringatan', 'Tatib_Mundur'],
                'action' => 'read',
            ],
            [
                'role' => 'super',
                'akses' => ['manage'],
                'action' => 'all',
            ],
        ];
        Permission::truncate();
        foreach($abilities as $ab){
            $r = Role::where('name', $ab['role'])->first();
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
        $abilities = [
            [
                'action' => 'read',
                'subject' => 'Ref_Sekolah',
                'roles' => ['administrator', 'kepsek', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Ref_Mapel',
                'roles' => ['administrator', 'kepsek', 'piket', 'wakakur', 'walas', 'kajur', 'wakahumas', 'wakasiswa'],
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
                'roles' => ['administrator', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Permohonan',
                'roles' => ['administrator', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Absensi',
                'roles' => ['administrator', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Monitoring',
                'roles' => ['administrator'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Nilai',
                'roles' => ['administrator', 'wakakur', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Sertifikat',
                'roles' => ['administrator', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Sekolah',
                'roles' => ['administrator', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Pelanggaran',
                'roles' => ['administrator', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Rekap',
                'roles' => ['administrator', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Bukti',
                'roles' => ['administrator', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Ortu',
                'roles' => ['administrator', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Pernyataan',
                'roles' => ['administrator', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Peringatan',
                'roles' => ['administrator', 'kajur', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Mundur',
                'roles' => ['administrator', 'kajur', 'wakasiswa', 'bk'],
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
            [
                'action' => 'read',
                'subject' => 'Guru_BK',
                'roles' => ['administrator'],
            ],
        ];
    }
}
