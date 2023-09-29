<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rombongan_belajar;
use App\Mail\SendBk;
use Mail;

class KirimBk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:bk';

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
        })->withWhereHas('kelas_bk', function($query){
            $query->withWhereHas('guru', function($query){
                $query->withWhereHas('pengguna');
            });
        })->withWhereHas('pd', function($query){
            $query->withWhereHas('alpa', function($query){
                $query->whereBetween('tanggal', [now()->subDays(8)->format('Y-m-d'), now()->format('Y-m-d')]);
                //$query->where('absen', 'A');
                //$query->selectRaw('count(tanggal) as numberofreservations, tanggal');
                //$query->groupBy('tanggal');
                //$query->havingRaw('numberofreservations >= 3');
                //$query->whereTanggal(now()->format('Y-m-d'));
            });
            $query->with([
                'ijin' => function($query){
                    $query->whereBetween('tanggal', [now()->subDays(8)->format('Y-m-d'), now()->format('Y-m-d')]);
                },
                'sakit' => function($query){
                    $query->whereBetween('tanggal', [now()->subDays(8)->format('Y-m-d'), now()->format('Y-m-d')]);
                },
                'dispen' => function($query){
                    $query->whereBetween('tanggal', [now()->subDays(8)->format('Y-m-d'), now()->format('Y-m-d')]);
                }
            ]);
        })->orderBy('tingkat')->get();
        foreach($data as $d){
            $count = 0;
            $data_siswa = [];
            foreach($d->pd as $pd){
                $alpa = collect($pd->alpa->toArray());
                $ijin = collect($pd->ijin->toArray());
                $sakit = collect($pd->sakit->toArray());
                $dispen = collect($pd->dispen->toArray());
                $a = $alpa->groupBy('tanggal');
                $i = $ijin->groupBy('tanggal');
                $s = $sakit->groupBy('tanggal');
                $dd = $dispen->groupBy('tanggal');
                if($a->count() >= 3){
                    $data_siswa[] = [
                        'nama' => $pd->nama,
                        'alpa' => $a->count(),
                        'ijin' => $i->count(),
                        'sakit' => $s->count(),
                        'dispen' => $dd->count(),
                    ];
                }
            }
            if(count($data_siswa)){
                $mail_bk = $d->kelas_bk->guru->pengguna->email;
                $mailBk = [
                    'nama_kelas' => $d->nama,
                    'nama_guru' => $d->kelas_bk->guru->pengguna->name,
                    'data_siswa' => $data_siswa,
                    'tanggal' => now()->translatedFormat('l, j F Y')
                ];
                Mail::to($mail_bk)->send(new SendBk($mailBk));
                Mail::to('chuzmukadar@gmail.com')->send(new SendBk($mailBk));
            }
        }
        return Command::SUCCESS;
    }
}
