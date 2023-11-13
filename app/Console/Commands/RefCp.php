<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Capaian_pembelajaran;
class RefCp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ref:cp';

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
        $data = Capaian_pembelajaran::doesntHave('nilai_tp')->orderBy('mata_pelajaran_id')->get();
        $duplicates = $data->unique('deskripsi');
        dump($duplicates->count());
        $cp_id = [];
        foreach($duplicates as $ganda){
            $cp_id[] = $ganda->cp_id;
        }
        if($cp_id){
            $a = Capaian_pembelajaran::whereNotIn('cp_id', $cp_id)->delete();
            dd($a);
        }
        return Command::SUCCESS;
    }
}
