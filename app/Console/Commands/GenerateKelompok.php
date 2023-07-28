<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Kelompok;
use App\Models\Kurikulum;

class GenerateKelompok extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:kelompok';

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
        /*Rombongan_belajar::withWhereHas('kurikulum')->orderBy('rombongan_belajar_id')->chunk(200, function ($data) {
            foreach($data as $d){
                if($this->kurikulum($d->kurikulum->nama_kurikulum, 'REV')){
                    $kurikulum = 2017;
                } elseif ($this->kurikulum($d->kurikulum->nama_kurikulum, 'KTSP')) {
                    $kurikulum = 2006;
                } elseif ($this->kurikulum($d->kurikulum->nama_kurikulum, 'Pusat')) {
                    $kurikulum = 2021;
                } elseif ($this->kurikulum($d->kurikulum->nama_kurikulum, 'Merdeka')) {
                    $kurikulum = 2022;
                } else {
                    $kurikulum = 2013;
                }
                $d->tahun = $kurikulum;
                $d->save();
            }
        });*/
        $data = (new FastExcel)->import('public/templates/kelompok.xlsx', function ($line) {
            Kelompok::updateOrCreate(
                [
                    'kelompok_id' => $line['kelompok_id'],
                ],
                [
                    'nama_kelompok' => $line['nama_kelompok'],
                    'kurikulum' => $line['kurikulum'],
                ]
            );
            if($line['nama_kurikulum']){
                Kurikulum::updateOrCreate([
                    'kurikulum_id' => $line['kurikulum'],
                    'nama' => $line['nama_kurikulum'],
                ]);
            }
        });
        return Command::SUCCESS;
    }
    private function kurikulum($nama_kurikulum, $kurikulum){
        $contains = Str::contains($nama_kurikulum, $kurikulum);
        return $contains;
    }
}
