<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta_didik;
use App\Models\Ijin;
use App\Models\Presensi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PerijinanController extends Controller
{
    public function index(){
        $data = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->where('semester_id', get_setting('semester_id'));
            $query->withWhereHas('rombongan_belajar', function($query){
                $query->where('tingkat', '<>', 0);
            });
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['data' => $data, 'semester_id' => get_setting('semester_id')]);
    }
    public function store(){
        request()->validate(
            [
                'anggota_rombel_id' => ['required'],
                'tanggal_mulai' => ['required'],
                'tanggal_selesai' => ['required'],
            ],
            [
                'anggota_rombel_id.required' => 'Peserta Didik tidak boleh kosong!',
                'tanggal_mulai.required' => 'Mulai Tanggal tidak boleh kosong!',
                'tanggal_selesai.required' => 'Sampai Tanggal tidak boleh kosong!',
            ],
        );
        $startDate = Carbon::createFromDate(request()->tanggal_mulai);
        $endDate = Carbon::createFromDate(request()->tanggal_selesai);
        $period = CarbonPeriod::between($startDate, $endDate)->addFilter(function ($date) {
            return $date->isMonday() || $date->isTuesday() || $date->isWednesday() || $date->isThursday() || $date->isFriday();
        });
        $ijin = NULL;
        $insert = 0;
        if($period){
            //S: Sakit
            //I: Ijin
            //D: Dispen
            $ijin = Ijin::updateOrCreate(
                [
                    'anggota_rombel_id' => request()->anggota_rombel_id,
                    'tanggal_mulai' => request()->tanggal_mulai,
                    'tanggal_selesai' => request()->tanggal_selesai,
                    'jenis_ijin' => 'hari',
                    'pilihan_ijin' => request()->pilihan_ijin,
                ],
                [
                    'alasan' => request()->alasan,
                ]
            );
            foreach($period as $date){
                $insert++;
                $data_jam = ($date->dayOfWeek == Carbon::FRIDAY) ? [1,2,3,4,5,6] : [1,2,3,4,5,6,7,8,9,10,11];
                foreach($data_jam as $jam){
                    Presensi::updateOrCreate(
                        [
                            'anggota_rombel_id' => request()->anggota_rombel_id,
                            'tanggal' => $date->format('Y-m-d'),
                            'jam' => $jam,
                        ],
                        [
                            'absen' => request()->pilihan_ijin,
                            //'user_id' => request()->user_id,
                            'ijin_id' => $ijin->ijin_id,
                        ]
                    );
                }
            }
        }
        $data = [
            'icon' => 'success',
            'text' => 'Perijinan berhasil disimpan',
            'title' => 'Berhasil',
            'ijin' => $ijin,
            'semester_id' => get_setting('semester_id'),
        ];
        return response()->json($data);
    }
}
