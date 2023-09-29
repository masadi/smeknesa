<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rombongan_belajar;
use App\Mail\SendWalas;
use Mail;

class KirimWalas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:walas';

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
        $data = Rombongan_belajar::where(function($query){
            $query->where('tingkat', '<>', 0);
            $query->where('semester_id', semester_id());
        })->withWhereHas('wali_kelas', function($query){
            $query->withWhereHas('pengguna');
        })->withWhereHas('pd', function($query){
            $query->whereHas('presensi', function($query){
                $query->whereTanggal(now()->format('Y-m-d'));
            });
            $query->withCount([
                'presensi as alpa' => function($query){
                    $query->where('absen', 'A');
                    $query->whereTanggal(now()->format('Y-m-d'));
                },
                'presensi as sakit' => function($query){
                    $query->where('absen', 'S');
                    $query->whereTanggal(now()->format('Y-m-d'));
                },
                'presensi as ijin' => function($query){
                    $query->where('absen', 'I');
                    $query->whereTanggal(now()->format('Y-m-d'));
                },
                'presensi as dispen' => function($query){
                    $query->where('absen', 'D');
                    $query->whereTanggal(now()->format('Y-m-d'));
                },
            ]);
        })->orderBy('tingkat')->get();
        foreach($data as $d){
            $mail_walas = $d->wali_kelas->pengguna->email;
            $mailWalas = [
                'nama_kelas' => $d->nama,
                'nama_guru' => $d->wali_kelas->nama,
                'data_siswa' => ($d->pd),
                'tanggal' => now()->translatedFormat('l, j F Y')
            ];
            Mail::to($mail_walas)->send(new SendWalas($mailWalas));
            Mail::to('chuzmukadar@gmail.com')->send(new SendWalas($mailWalas));
        }
        return Command::SUCCESS;
    }
}
