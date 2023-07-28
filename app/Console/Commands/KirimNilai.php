<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Whatsapp;
use App\Models\Peserta_didik;
use Carbon\Carbon;

class KirimNilai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:nilai';

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
        $data = Peserta_didik::withWhereHas('nilai', function($query){
            $query->where('angka', '<', 75);
        })->withWhereHas('user', function($query){
            $query->whereNotNull('wa_wali');
        })->get();
        $url = 'http://api-wa.erapor-smk.net/chats/send?id=wahyu';
        foreach($data as $pd){
            $today = Carbon::now()->translatedFormat('l');
            $whatsapp = Whatsapp::where('jenis', 'nilai')->first();
            $description = $whatsapp->description;
            $description = str_replace('{nama_siswa}', $pd->nama, $description);
            $description = str_replace('{nilai_mapel}', $pd->presensi->implode('jam', ','), $description);
            $description = str_replace('{nama_mapel}', 'nama_mapel', $description);
            $description = str_replace('{guru_mapel}', 'guru_mapel', $description);
            /*$data_post = [
                'number' => $pd->user->wa_wali,
                'message' => $description,
            ];*/
            $data_post = [
                'receiver' => hp($pd->user->wa_wali),
                'isGroup' => FALSE,
                'message' => [
                    'text' => $description,
                ],
            ];
            $response = Http::withOptions([
                'verify' => false,
            ])->post($url, $data_post);
        }
        return Command::SUCCESS;
    }
}
