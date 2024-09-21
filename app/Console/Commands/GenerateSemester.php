<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rombongan_belajar;
use App\Models\Tahun_ajaran;
use App\Models\Semester;

class GenerateSemester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:semester';

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
        /*Rombongan_belajar::with(['anggota_rombel', 'pembelajaran'])->orderBy('rombongan_belajar_id')->chunk(200, function ($rombel) {
            foreach ($rombel as $data) {
                foreach($data->anggota_rombel as $anggota_rombel){
                    $anggota_rombel->semester_id = $data->semester_id;
                    $anggota_rombel->save();
                }
                foreach($data->pembelajaran as $pembelajaran){
                    $pembelajaran->semester_id = $data->semester_id;
                    $pembelajaran->save();
                }
            }
        });*/
        $tahun_ajaran = [
            [
                'tahun_ajaran_id' => 2024,
                'nama' => '2024/2025',
                'periode_aktif' => 1,
                'tanggal_mulai' => '2024-06-01',
                'tanggal_selesai' => '2025-07-07',
                'semester' => [
                    [
                        'semester_id' => '20241',
                        'nama' => '2024/2025 Ganjil',
                        'semester' => 1,
                        'periode_aktif' => 1,
                        'tanggal_mulai' => '2024-06-01',
                        'tanggal_selesai' => '2024-12-31',
                        'tanggal_cetak' => '2024-12-15',
                    ],
                    [
                        'semester_id' => '20242',
                        'nama' => '2024/2025 Genap',
                        'semester' => 2,
                        'periode_aktif' => 0,
                        'tanggal_mulai' => '2025-01-01',
                        'tanggal_selesai' => '2025-07-07',
                        'tanggal_cetak' => '2025-06-15',
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
        return Command::SUCCESS;
    }
}
