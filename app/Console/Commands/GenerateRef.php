<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jenis_keluar;

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
        $jenis_keluar = ['Lulus', 'Mutasi', 'Dikeluarkan', 'Mengundurkan diri', 'Putus Sekolah', 'Wafat', 'Hilang'];
        foreach($jenis_keluar as $jenis){
            Jenis_keluar::firstOrCreate([
                'nama' => $jenis
            ]);
        }
        return Command::SUCCESS;
    }
}
