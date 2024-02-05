<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Guru;
use App\Models\User;
use App\Mail\SendWaka;
use Carbon\Carbon;
use Mail;

class KirimWaka extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:waka';

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
        $data = Guru::whereHas('presensi', function ($query) {
            $query->where('tanggal', Carbon::today()->format('Y-m-d'));
        })->withCount([
            'presensi as alpa' => function($query){
                $query->where('absen', 'A');
                $query->where('tanggal', Carbon::today()->format('Y-m-d'));
            },
            'presensi as sakit' => function($query){
                $query->where('absen', 'S');
                $query->where('tanggal', Carbon::today()->format('Y-m-d'));
            },
            'presensi as izin' => function($query){
                $query->where('absen', 'I');
                $query->where('tanggal', Carbon::today()->format('Y-m-d'));
            },
        ])->orderBy('nama')->get();
        if($data->count()){
            $users = User::whereRoleIs('wakakur', periode_aktif())->get();
            foreach($users as $user){
                $send_data = [
                    'nama_guru' => $user->name,
                    'data_guru' => $data,
                    'tanggal' => now()->translatedFormat('l, j F Y'),
                ];
                Mail::to($user->email)->send(new SendWaka($send_data));
                Mail::to('chuzmukadar@gmail.com')->send(new SendWaka($send_data));
            }
        }
        return Command::SUCCESS;
    }
}
