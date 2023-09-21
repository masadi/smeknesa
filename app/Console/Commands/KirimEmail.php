<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\Mail\KirimEmail as SendEmail;
use App\Models\Rombongan_belajar;

class KirimEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:email';

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
                'presensi as dadah' => function($query){
                    $query->where('absen', 'D');
                    $query->whereTanggal(now()->format('Y-m-d'));
                },
            ]);
        })->with(['kelas_bk' => function($query){
            $query->withWhereHas('guru', function($query){
                $query->withWhereHas('pengguna');
            });
        }])->orderBy('tingkat')->get();
        foreach($data as $d){
            $mail_walas = $d->wali_kelas->pengguna->email;
            $mail_bk = ($d->kelas_bk) ? $d->kelas_bk->guru->pengguna->email : NULL;
            if($mail_bk){
                if($mail_bk == $mail_walas){
                    $this->mailBk($d);
                } else {
                    $this->mailWalas($d);
                    $this->mailBk($d);
                }
            } else {
                $this->mailWalas($d);
            }
            $mailWalas = [
                'nama_kelas' => $d->nama,
                'nama_guru' => $d->wali_kelas->nama,
                'data_siswa' => ($d->pd),
                'tanggal' => now()->translatedFormat('l, j F Y')
            ];
            Mail::to('chuzmukadar@gmail.com')->send(new SendEmail($mailWalas));
        }
        return Command::SUCCESS;
    }
    private function mailWalas($d){
        return [
            'nama_kelas' => $d->nama,
            'nama_guru' => $d->wali_kelas->nama,
            'data_siswa' => $d->pd,
            'tanggal' => now()->translatedFormat('l, j F Y')
        ];
    }
    private function mailBk($d){
        return [
            'nama_kelas' => $d->nama,
            'nama_guru' => $d->kelas_bk->guru->nama,
            'data_siswa' => ($d->pd),
            'tanggal' => now()->translatedFormat('l, j F Y')
        ];
    }
}
