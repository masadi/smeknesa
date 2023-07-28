<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mata_pelajaran;
use App\Models\Mapel_tingkat;

class GenerateMapel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:mapel';

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
        $data = Mata_pelajaran::get();
        foreach($data as $d){
            foreach($d->tingkat as $tingkat){
                Mapel_tingkat::updateOrCreate([
                    'mata_pelajaran_id' => $d->mata_pelajaran_id,
                    'tingkat' => $tingkat,
                ]);
            }
            $this->info($d->nama.' selesai di proses');
        }
        return Command::SUCCESS;
    }
}
