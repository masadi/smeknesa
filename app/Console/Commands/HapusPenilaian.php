<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penilaian;

class HapusPenilaian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hapus:penilaian';

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
        $data = Penilaian::whereNull('nama')->with('nilai')->get();
        foreach($data as $d){
            $d->nama = $d->nilai->tp_id;
            $d->save();
        }
        Penilaian::doesntHave('nilai')->delete();
        return Command::SUCCESS;
    }
}
