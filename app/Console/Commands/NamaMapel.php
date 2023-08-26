<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mata_pelajaran;
use App\Models\Pembelajaran;

class NamaMapel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nama:mapel';

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
        $mapel = Mata_pelajaran::get();
        foreach($mapel as $m){
            Pembelajaran::where('mata_pelajaran_id', $m->mata_pelajaran_id)->update(['nama_mata_pelajaran' => $m->nama]);
        }
        return Command::SUCCESS;
    }
}
