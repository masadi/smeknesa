<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kenaikan_kelas;
use App\Models\Anggota_rombel;

class GenerateLulus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:lulus';

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
        $data = Anggota_rombel::where(function($query){
            $query->where('semester_id', '20232');
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('tingkat', 12);
            });
        })->get();
        foreach($data as $d){
            //dd($d);
            Kenaikan_kelas::updateOrCreate(
                [
                    'sekolah_id' => sekolah_id(),
                    'anggota_rombel_id' => $d->anggota_rombel_id,
                ],
                [
                    'nama_kelas' => NULL,
                    'status' => 3,
                ]
            );
        }
        return Command::SUCCESS;
    }
}
