<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Nilai;
use App\Models\Capaian_pembelajaran;
use App\Models\Tujuan_pembelajaran;
use App\Models\Penilaian;

class ResetNilai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:nilai';

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
        Nilai::truncate();
        Penilaian::truncate();
        Tujuan_pembelajaran::truncate();
        Capaian_pembelajaran::truncate();
        return Command::SUCCESS;
    }
}
