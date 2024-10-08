<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Nilai_ekskul;
use App\Exports\AbsensiSiswa;
use App\Exports\RekapRemedial;
use App\Exports\RekapNilai;
use App\Exports\AbsensiGuru;
use App\Exports\LeggerNilai;
use App\Models\Peserta_didik;
use App\Models\Tujuan_pembelajaran;
use App\Models\Penilaian;
use App\Models\Guru;
use App\Models\Paket_ukk;
use App\Models\BahanAjar;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function rekap_absensi_siswa(){
        $semester_id = request()->route('semester_id');
        $rombongan_belajar_id = request()->route('rombongan_belajar_id');
        $rombel = Rombongan_belajar::find($rombongan_belajar_id);
        $bulan = request()->route('bulan');
        $jenis = request()->route('jenis');
        $file = 'REKAP ABSENSI KELAS '.$rombel->nama.' PERBULAN.xlsx';
        if($jenis == 2){
            $file = 'REKAP ABSENSI KELAS '.$rombel->nama.' PERSEMESTER.xlsx';
        }
        return Excel::download(new AbsensiSiswa($semester_id, $rombongan_belajar_id, $bulan, $jenis), $file);
    }
    public function rekap_absensi_guru(){
        $bulan = request()->route('bulan') + 1;
        $padded = Str::padLeft($bulan, 2, '0');
        $data = Guru::with([
            'presensi' => function($query) use ($padded){
                $query->has('presensi_jadwal');
                $query->whereMonth('tanggal', $padded);
            },
        ])->orderBy('nama')->get();
        $nama = strtoupper(Carbon::create(date('Y').'-'.$padded.'-'.date('d'))->translatedFormat('F'));
        return Excel::download(new AbsensiGuru($data, $padded, $nama), 'REKAP ABSENSI GURU PERBULAN '.$nama.'.xlsx');
    }
    public function rekap_remedial(){
        $semester_id = request()->route('semester_id');
        return Excel::download(new RekapRemedial($semester_id), 'REKAPITULASI REMEDIAL.xlsx');
    }
    public function password_pd(){
        $rombel = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
        $users = User::whereHas('pd', function($query){
            $query->whereHas('kelas', function($query){
                $query->where('rombongan_belajar.rombongan_belajar_id', request()->route('rombongan_belajar_id'));
            });
        })->orderBy('name')->get();
        $list = [];
        foreach($users as $key => $user){
            $list[] = [
                'NO' => $key + 1,
                'NAMA SISWA' => $user->name,
                'EMAIL' => $user->email,
                'PASSWORD' => $user->status_password,
            ];
        }
        return (new FastExcel(collect($list)))->download('PASSWORD PD KELAS '.$rombel->nama.'.xlsx');
    }
    public function nilai_ekskul(){
        $rombel = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
        $ekskul = Rombongan_belajar::where(function($query) use ($rombel){
            $query->where('tingkat', 0);
            $query->where('semester_id', $rombel->semester_id);
        })->withWhereHas('pd', function($query) use ($rombel){
            $query->whereHas('kelas', function($query) use ($rombel){
                $query->where('rombongan_belajar.rombongan_belajar_id', $rombel->rombongan_belajar_id);
            });
        })->orderBy('nama')->get();
        /*
        $query->with(['anggota_ekskul' => function($query) use ($rombel){
            $query->where('semester_id', $rombel->semester_id);
            $query->with(['nilai_ekskul']);
        }]);
        */
        $collection = [];
        foreach($ekskul as $eks){
            foreach($eks->pd as $pd){
                $nilai_ekskul = Nilai_ekskul::where('peserta_didik_id', $pd->peserta_didik_id)->where('rombongan_belajar_id', $eks->rombongan_belajar_id)->first();
                $collection[$eks->nama][] = [
                    'Ekskul_ID' => $eks->rombongan_belajar_id,
                    'PD_ID' => $pd->peserta_didik_id,
                    'nama' => $pd->nama,
                    'nisn' => $pd->nisn,
                    'ekskul' => $eks->nama,
                    'nilai' => ($nilai_ekskul) ? $nilai_ekskul->nilai : '',
                    'deskripsi' => ($nilai_ekskul) ? $nilai_ekskul->deskripsi : '',
                ];
            }
        }
        $sheets = new SheetCollection($collection);
        return (new FastExcel($sheets))->download('Nilai Ekstrakurikuler Kelas '.$rombel->nama.'.xlsx');
    }
    public function rekap_nilai(){
        $pembelajaran = Pembelajaran::with(['rombongan_belajar', 'semester'])->find(request()->route('pembelajaran_id'));
        $file = 'REKAP NILAI MAPEL '.$pembelajaran->nama_mata_pelajaran.' KELAS '.$pembelajaran->rombongan_belajar->nama.'.xlsx';
        $data = [
            'nama_mata_pelajaran' => $pembelajaran->nama_mata_pelajaran,
            'nama' => $pembelajaran->rombongan_belajar->nama,
            'semester' => $pembelajaran->semester,
            'pd' => Peserta_didik::with(['nilai' => function($query){
                $query->where('pembelajaran_id', request()->route('pembelajaran_id'));
            }])->where(function($query) use ($pembelajaran){
                $query->whereHas('anggota_rombel', function($query) use ($pembelajaran){
                    $query->where('rombongan_belajar_id', $pembelajaran->rombongan_belajar_id);
                });
            })->orderBy('nama')->get(),
            'data_tp' => Tujuan_pembelajaran::where(function($query) use ($pembelajaran){
                $query->whereHas('cp', function($query) use ($pembelajaran){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('pembelajaran_id', request()->route('pembelajaran_id'));
                    });
                    $query->where('guru_id', $pembelajaran->guru_id);
                });
                $query->whereHas('nilai', function($query){
                    $query->where('pembelajaran_id', request()->route('pembelajaran_id'));
                });
            })->orderBy('created_at')->get(),
            'diagnosis' => Penilaian::whereHas('jenis_penilaian', function($query){
                $query->where('nama', 'Assesment Diagnosis');
            })->whereHas('pembelajaran', function($query){
                $query->where('pembelajaran_id', request()->route('pembelajaran_id'));
            })->whereHas('nilai', function($query){
                $query->where('pembelajaran_id', request()->route('pembelajaran_id'));
            })->orderBy('created_at')->get(),
            'sumatif' => Penilaian::whereHas('jenis_penilaian', function($query){
                $query->where('nama', 'Assesment Sumatif');
            })->whereHas('pembelajaran', function($query){
                $query->where('pembelajaran_id', request()->route('pembelajaran_id'));
            })->whereHas('nilai', function($query){
                $query->where('pembelajaran_id', request()->route('pembelajaran_id'));
            })->orderBy('created_at')->get(),
        ];
        return Excel::download(new RekapNilai($pembelajaran->pembelajaran_id, $pembelajaran->rombongan_belajar_id, $pembelajaran->guru_id, $data), $file);
    }
    public function legger(){
        $rombongan_belajar_id = request()->route('rombongan_belajar_id');
        $rombel = Rombongan_belajar::find($rombongan_belajar_id);
        $file = 'LEGGER NILAI KELAS '.$rombel->nama.'.xlsx';
        return Excel::download(new LeggerNilai(request()->route('rombongan_belajar_id')), $file);
    }
    public function template_unit_ukk(){
        $list = collect([
            [
                'No' => 1,
                'Kode Unit' => 'Contoh Kode Unit', 
                'Nama Unit Kompetensi' => 'Contoh Nama Unit Kompetensi' 
            ],
        ]);
        $paket = Paket_ukk::find(request()->route('paket_ukk_id'));
        $nama = clean($paket->nama_paket_id);
        return (new FastExcel($list))->download($nama.'.xlsx');
    }
    public function unduh_modul($id){
        $find = BahanAjar::with('pembelajaran')->find($id);
        $find->download_count = $find->download_count + 1;
        $find->save();
        $name = 'Bahan Ajar '.$find->pembelajaran->nama_mata_pelajaran.' Kelas '.$find->tingkat;
        $pathToFile= public_path(). "/storage/file-pdf/".$find->file_pdf;
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($pathToFile, $name, $headers);
    }
}
