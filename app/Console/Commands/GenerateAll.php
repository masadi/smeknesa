<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agama;

class GenerateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = ["Islam", "Kristen", "Katholik", "Hindu", "Budha", "Khonghucu", "Kepercayaan kpd Tuhan YME", "Tidak diisi", "lainnya"];
        foreach($data as $d){
            Agama::updateOrCreate(['nama' => $d]);
        }
    }
}
