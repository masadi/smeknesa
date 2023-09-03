<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Mata_pelajaran;

class SedotMapel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sedot:mapel';

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
        $data = Mata_pelajaran::orderBy('mata_pelajaran_id')->get();
        $lists = [];
        foreach($data as $d){
            $lists[] = [
                'mata_pelajaran_id' => $d->mata_pelajaran_id,
                'nama' => $d->nama,
            ];
        }
        $list = collect($lists);
        (new FastExcel($list))->export('ref_mapel.xlsx');
    }
}
