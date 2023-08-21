<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Peserta_didik;
use File;

class GenerateQr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:qr';

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
        $folder = storage_path('app/public/qrcodes');
        if (!File::isDirectory($folder)) {
            //MAKA FOLDER TERSEBUT AKAN DIBUAT
            File::makeDirectory($folder, 0777, true, true);
        }
        Peserta_didik::orderBy('peserta_didik_id')->whereNotNull('nisn')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $content = 'Nama: '.$user->nama."\n";
                $content .= 'NISN: '.$user->nisn;
                if(!File::exists(storage_path('app/public/qrcodes/'.clean($user->nama.'-'.$user->nisn).'.svg'))){
                    $this->info('File baru');
                    QrCode::size(250)->generate($content, storage_path('app/public/qrcodes/'.clean($user->nama.'-'.$user->nisn).'.svg'));
                } else {
                    $this->error('Sudah ada');
                }
            }
        });
        return Command::SUCCESS;
    }
}
