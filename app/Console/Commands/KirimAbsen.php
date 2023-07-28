<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Whatsapp;
use App\Models\Peserta_didik;
use Carbon\Carbon;

class KirimAbsen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:absen';

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
        $data = Peserta_didik::withWhereHas('presensi', function($query){
            $query->whereDate('tanggal', Carbon::today());
            $query->where('absen', 'A');
        })->withWhereHas('user', function($query){
            $query->whereNotNull('whatsapp');
        })->get();
        $url = 'http://api-wa.erapor-smk.net/chats/send?id=wahyu';
        foreach($data as $pd){
            $today = Carbon::now()->translatedFormat('l');
            $whatsapp = Whatsapp::where('jenis', 'absen')->first();
            $description = $whatsapp->description;
            $description = str_replace('{nama_hari_ini}', $today, $description);
            $description = str_replace('{tanggal_hari_ini}', now()->format('d-m-Y'), $description);
            $description = str_replace('{nama_siswa}', $pd->nama, $description);
            $description = str_replace('{jam_bolos}', $pd->presensi->implode('jam', ', '), $description);
            /*$data_post = [
                'number' => $pd->user->whatsapp,
                'message' => $description,
            ];*/
            $data_post = [
                'receiver' => hp($pd->user->whatsapp),
                'isGroup' => FALSE,
                'message' => [
                    'text' => $description,
                ],
            ];
            $response = Http::withOptions([
                'verify' => false,
            ])->post($url, $data_post);
            dump($response->object());
        }
        return Command::SUCCESS;
    }
}
