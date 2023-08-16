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
                'action' => 'read',
                'subject' => 'Web',
                'roles' => ['administrator', 'guru', 'kepsek', 'pengajar', 'pd', 'piket', 'wakakur', 'walas', 'kajur', 'wakahumas', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Beranda',
                'roles' => ['administrator', 'guru', 'kepsek', 'pengajar', 'pd', 'piket', 'wakakur', 'walas', 'kajur', 'wakahumas', 'wakasiswa', 'bk'],
            ],
            [
                'action' => 'read',
                'subject' => 'Profile',
                'roles' => ['administrator', 'guru', 'kepsek', 'pengajar', 'pd', 'piket', 'wakakur', 'walas', 'kajur', 'wakahumas', 'wakasiswa', 'bk'],
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
                'roles' => ['pengajar', 'pd', 'wakakur', 'walas', 'kajur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Modul',
                'roles' => ['pengajar', 'pd', 'wakakur'],
            ],
            [
                'action' => 'read',
                'subject' => 'Kenaikan',
                'roles' => ['walas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Kelulusan',
                'roles' => ['kepsek', 'pd', 'wakakur', 'kajur', 'bk'],
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
                'roles' => ['administrator', 'pd', 'walas', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Monitoring',
                'roles' => ['administrator'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Nilai',
                'roles' => ['administrator', 'pd', 'wakakur', 'walas', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Magang_Sertifikat',
                'roles' => ['administrator', 'pd', 'walas', 'kajur', 'wakahumas'],
            ],
            [
                'action' => 'read',
                'subject' => 'Tatib_Sekolah',
                'roles' => ['administrator', 'pd', 'wakasiswa', 'bk'],
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
                if(!$r->hasPermission($permission->name)){
                    $r->attachPermission($permission);
                }
            }
        }
        return Command::SUCCESS;
    }
}
