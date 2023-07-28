<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rombongan_belajar;

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
        Rombongan_belajar::with(['anggota_rombel', 'pembelajaran'])->orderBy('rombongan_belajar_id')->chunk(200, function ($rombel) {
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
        });
        return Command::SUCCESS;
    }
}
