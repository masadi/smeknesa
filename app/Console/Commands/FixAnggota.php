<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rombongan_belajar;

class FixAnggota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:anggota';

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
        $data = Rombongan_belajar::withWHereHas('anggota_rombel', function($query){
            $query->whereNull('semester_id');
        })->get();
        foreach($data as $d){
            foreach($d->anggota_rombel as $ang){
                $ang->semester_id = $d->semester_id;
                $ang->save();
                $this->info('save semester_id');
            }
        }
        return Command::SUCCESS;
    }
}
