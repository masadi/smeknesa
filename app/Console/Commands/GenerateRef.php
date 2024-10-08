<?php

namespace App\Console\Commands;

use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Console\Command;
use App\Models\Jenis_keluar;
use App\Models\Pekerjaan;
use App\Models\Cita;
use App\Models\Capaian_pembelajaran;
use App\Models\Pembelajaran;

class GenerateRef extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:ref';

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
      $ref_cita = [
        [
          'id' => 1,
          'nama' => 'Bekerja', 
           
        ],
        [
          'id' => 2,
          'nama' => 'Kuliyah',
        ],
        [
          'id' => 3,
          'nama' => 'Wirausaha', 
        ],
        [
          'id' => 4,
          'nama' => 'Polisi/TNI', 
        ],
        [
          'id' => 5,
          'nama' => 'Lainnya',
        ],
      ];
      foreach($ref_cita as $cita){
        Cita::updateOrCreate(
          [
          'cita_id' => $cita['id'],
          ],
          [
            'nama' => $cita['nama'],
          ]
        );
      }
        $pekerjaan = [
            [
              'id' => 1,
              'nama' => 'Tidak bekerja',
            ],
            [
              'id' => 2,
              'nama' => 'Nelayan'
            ],
            [
              'id' => 3,
              'nama' => 'Petani'
            ],
            [
              'id' => 4,
              'nama' => 'Peternak'
            ],
            [
              'id' => 5,
              'nama' => 'PNS/TNI/Polri'
            ],
            [
              'id' => 6,
              'nama' => 'Karyawan Swasta'
            ],
            [
              'id' => 7,
              'nama' => 'Pedagang Kecil'
            ],
            [
              'id' => 8,
              'nama' => 'Pedagang Besar'
            ],
            [
              'id' => 9,
              'nama' => 'Wiraswasta'
            ],
            [
              'id' => 10,
              'nama' => 'Wirausaha'
            ],
            [
              'id' => 11,
              'nama' => 'Buruh'
            ],
            [
              'id' => 12,
              'nama' => 'Pensiunan'
            ],
            [
              'id' => 13,
              'nama' => 'Tenaga Kerja Indonesia'
            ],
            [
              'id' => 14,
              'nama' => 'Karyawan BUMN'
            ],
            [
              'id' => 90,
              'nama' => 'Tidak dapat diterapkan'
            ],
            [
              'id' => 98,
              'nama' => 'Sudah Meninggal'
            ],
            [
              'id' => 99,
              'nama' => 'Lainnya'
            ],
        ];
        foreach($pekerjaan as $kerja){
            Pekerjaan::firstOrCreate([
                'pekerjaan_id' => $kerja['id'],
                'nama' => $kerja['nama'],
            ]);
        }
        $jenis_keluar = ['Lulus', 'Mutasi', 'Dikeluarkan', 'Mengundurkan diri', 'Putus Sekolah', 'Wafat', 'Hilang'];
        foreach($jenis_keluar as $jenis){
            Jenis_keluar::firstOrCreate([
                'nama' => $jenis
            ]);
        }
        (new FastExcel)->import(public_path('templates/ref_cp_1.xlsx'), function ($line) {
          $pembelajaran = Pembelajaran::whereHas('rombongan_belajar', function($query) use ($line){
            $query->where('tingkat', $line['kelas']);
          })->where('mata_pelajaran_id', $line['mata_pelajaran_id'])->first();
          Capaian_pembelajaran::create([
            'mata_pelajaran_id' => $line['mata_pelajaran_id'],
            'deskripsi' => $line['deskripsi'],
            'guru_id' => ($pembelajaran) ? $pembelajaran()->guru_id : NULL,
          ]);
        });
        (new FastExcel)->import(public_path('templates/ref_cp_2.xlsx'), function ($line) {
        });
        return Command::SUCCESS;
    }
}
