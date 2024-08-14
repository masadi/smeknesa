<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Peserta_didik;
use App\Models\Ijin;
use App\Models\Presensi;
use App\Models\Terlambat;
use App\Models\Jam_terlambat;
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
            $query->where('nama1', 'ILIKE', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['data' => $data, 'semester_id' => get_setting('semester_id')]);
    }
    public function store(){
        request()->validate(
            [
                'anggota_rombel_id' => ['required'],
                'jam_ke' => ['required_if:jenis_ijin,jam'],
                'jenis_ijin' => ['required_if:pilihan_ijin,I'],
                'pilihan_ijin' => ['required'],
                'tanggal_mulai' => ['required_if:jenis_ijin,hari'],
                'tanggal_selesai' => ['required_if:jenis_ijin,hari'],
            ],
            [
                'anggota_rombel_id.required' => 'Siswa tidak boleh kosong',
                'jam_ke.required_if' => 'Jam tidak boleh kosong',
                'jenis_ijin.required_if' => 'Jenis Ijin tidak boleh kosong',
                'pilihan_ijin.required' => 'Pilihan Ijin tidak boleh kosong',
                'tanggal_mulai.required_if' => 'Tanggal Mulai Ijin tidak boleh kosong',
                'tanggal_selesai.required_if' => 'Tanggal Selesai Ijin tidak boleh kosong',
            ],
            /*[
                'anggota_rombel_id' => ['required'],
                'tanggal_mulai' => ['required'],
                'tanggal_selesai' => ['required_if:pilihan_ijin,T'],
            ],
            [
                'anggota_rombel_id.required' => 'Peserta Didik tidak boleh kosong!',
                'tanggal_mulai.required' => 'Mulai Tanggal tidak boleh kosong!',
                'tanggal_selesai.required_if' => 'Sampai Tanggal tidak boleh kosong!',
            ],*/
        );
        $insert = 0;
        $ijin = NULL;
        $terlambat = NULL;
        if(request()->pilihan_ijin == 'T'){
            $terlambat = Terlambat::create([
                'anggota_rombel_id' => request()->anggota_rombel_id,
                'keterangan' => request()->alasan,
                'tanggal' => request()->tanggal_mulai,
            ]);
            foreach(request()->jam_ke as $jam){
                Jam_terlambat::create(
                    [
                        'terlambat_id' => $terlambat->terlambat_id,
                        'jam' => $jam,
                    ]
                );
            }
        } elseif(request()->jenis_ijin == 'hari'){
            $startDate = Carbon::createFromDate(request()->tanggal_mulai);
            $endDate = Carbon::createFromDate(request()->tanggal_selesai);
            $period = CarbonPeriod::between($startDate, $endDate)->addFilter(function ($date) {
                return $date->isMonday() || $date->isTuesday() || $date->isWednesday() || $date->isThursday() || $date->isFriday();
            });
            $ijin = NULL;
            if($period){
                //S: Sakit
                //I: Ijin
                //D: Dispen
                $ijin = Ijin::create(
                    [
                        'anggota_rombel_id' => request()->anggota_rombel_id,
                        'tanggal_mulai' => request()->tanggal_mulai,
                        'tanggal_selesai' => request()->tanggal_selesai,
                        'jenis_ijin' => 'hari',
                        'pilihan_ijin' => request()->pilihan_ijin,
                        'alasan' => request()->alasan,
                    ]
                );
                foreach($period as $date){
                    $insert++;
                    $data_jam = ($date->dayOfWeek == Carbon::FRIDAY) ? [1,2,3,4,5,6] : [1,2,3,4,5,6,7,8,9,10,11];
                    foreach($data_jam as $jam){
                        Presensi::create(
                            [
                                'anggota_rombel_id' => request()->anggota_rombel_id,
                                'tanggal' => $date->format('Y-m-d'),
                                'jam' => $jam,
                                'absen' => request()->pilihan_ijin,
                                //'user_id' => request()->user_id,
                                'ijin_id' => $ijin->ijin_id,
                            ]
                        );
                    }
                }
            }
        } else {
            $ijin = Ijin::create(
                [
                    'anggota_rombel_id' => request()->anggota_rombel_id,
                    'guru_id' => request()->guru_id,
                    'tanggal_mulai' => request()->tanggal_mulai,
                    'tanggal_selesai' => NULL,
                    'jenis_ijin' => request()->jenis_ijin,
                    'pilihan_ijin' => request()->pilihan_ijin,
                    'alasan' => request()->alasan,
                ]
            );
            foreach(request()->jam_ke as $jam){
                $insert++;
                Presensi::create(
                    [
                        'anggota_rombel_id' => request()->anggota_rombel_id,
                        'tanggal' => request()->tanggal_mulai,
                        'jam' => $jam,
                        'absen' => request()->pilihan_ijin,
                        'ijin_id' => $ijin->ijin_id,
                    ]
                );
            }
        }
        $data = [
            'icon' => 'success',
            'text' => 'Perijinan berhasil disimpan',
            'title' => 'Berhasil',
            'ijin' => $ijin,
            'terlambat' => $terlambat,
            'semester_id' => get_setting('semester_id'),
        ];
        return response()->json($data);
    }
    public function get_jam(){
        $tanggal = Carbon::createFromDate(request()->tanggal_mulai);
        if($tanggal->dayOfWeek == Carbon::SATURDAY || $tanggal->dayOfWeek == Carbon::SUNDAY){
            $data = [];
        } else {
            $data = ($tanggal->dayOfWeek == Carbon::FRIDAY) ? [1,2,3,4,5,6] : [1,2,3,4,5,6,7,8,9,10,11];
        }
        return response()->json($data);
    }
    private function bulan(){
        $period = now()->startOfMonth()->subMonths(11)->monthsUntil(now());
        $month = collect($period->map(function (Carbon $date){
            $bulan = [
                'angka' => $date->format('m'),
                'huruf' => $date->translatedFormat('F'),
            ];
            return $bulan;
        }));
        $sorted = $month->sortBy([
            ['angka', 'asc']
        ]);
        return $sorted->values()->all();
    }
    private function get_bulan(){
        if(request()->bulan){
            return Str::padLeft(request()->bulan, 2, 0);
        }
        return date('m');
    }
    public function detil(){
        if(request()->peserta_didik_id){
            $tanggal = Carbon::now();
            if(request()->tanggal){
                $tanggal = Carbon::createFromDate(request()->tanggal);
            }
            /*$start =  Carbon::createFromFormat('Y-m-d', $tanggal->format('Y-m-d'))->firstOfMonth()->format('Y-m-d');
            $end = Carbon::createFromFormat('Y-m-d', $tanggal->format('Y-m-d'))->endOfMonth()->format('Y-m-d');
            $period = CarbonPeriod::create($start, $end);
            $data_tanggal = [];
            foreach ($period as $date) {
                $data_tanggal[] = [
                    'date' => $date->format('Y-m-d'),
                    'str' => $date->translatedFormat('l, j F Y'),
                ];
            }*/
            $date = date('Y').'-'.$this->get_bulan().'-'.date('d');
            $firstOfMonth = Carbon::create($date)->firstOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::create($date)->endOfMonth()->format('Y-m-d');
            $period = CarbonPeriod::between($firstOfMonth, $endOfMonth)->addFilter(function ($date) {
                return $date->isMonday() || $date->isTuesday() || $date->isWednesday() || $date->isThursday() || $date->isFriday();
            });
            $data_tanggal = [];
            foreach ($period as $date) {
                $data_tanggal[] = [
                    'date' => $date->format('Y-m-d'),
                    'str' => $date->translatedFormat('l, j F Y'),
                ];
            }
            $data = [
                'pd' => Peserta_didik::with(['presensi' => function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->orderBy('tanggal');
                    $query->whereMonth('tanggal', Str::padLeft($this->get_bulan(), 2, 0));
                }])->find(request()->peserta_didik_id),
                'data_bulan' => $this->bulan(),
                'data_tanggal' => $data_tanggal,
                'data_jam' => ($tanggal->dayOfWeek == Carbon::FRIDAY) ? 6 : 11,
            ];
        } else {
            $data = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->withCount([
                'presensi as H' => function($query){
                    $query->where('absen', 'H');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
                'presensi as A' => function($query){
                    $query->where('absen', 'A');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
                'presensi as S' => function($query){
                    $query->where('absen', 'S');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
                'presensi as I' => function($query){
                    $query->where('absen', 'I');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
                'presensi as D' => function($query){
                    $query->where('absen', 'D');
                    $query->whereMonth('tanggal', $this->get_bulan());
                },
            ])->orderBy('nama')->get();
        }
        return response()->json($data);
    }
}
