<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Guru;
use App\Models\Peserta_didik;
use App\Models\Nilai_ekskul;
use App\Models\Anggota_rombel;
use App\Models\Paket_ukk;
use App\Models\Unit_ukk;
use Rap2hpoutre\FastExcel\FastExcel;
use Validator;
use File;

class UploadController extends Controller
{
    public function index(){
        $validator = Validator::make(request()->all(), 
            [
                'file' => 'required|mimes:xlsx',
                'jurusan_sp_id' => Rule::requiredIf(request()->has('jurusan_sp_id')),
                'kurikulum_id' => Rule::requiredIf(request()->has('kurikulum_id')),
            ],
            [
                'file.required' => 'File excel tidak boleh kosong',
                'file.mimes' => 'File excel harus berextensi .xlsx',
                'jurusan_sp_id.required' => 'Jurusan tidak boleh kosong',
                'kurikulum_id.required' => 'Kurikulum excel tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        $upload_path = request()->file->store('public/excel');
        $string = Str::of($upload_path)->basename();
        $filepath = public_path('storage/excel/'.$string);
        $error = NULL;
        $inserted = 0;
        $aborted = 0;
        $text = ' Unknow';
        if(request()->data == 'guru'){
            $text = ' Data Guru';
            $collection = (new FastExcel)->import($filepath, function ($line) use (&$inserted, &$aborted, &$error){
                $insert = NULL;
                try {
                    $insert = Guru::updateOrCreate([
                        'sekolah_id' => sekolah_id(),
                        'nama' => $line['NAMA LENGKAP'],
                        'nip' => $line['NIP'],
                        'nuptk' => $line['NUPTK'],
                        'jenis_kelamin' => $line['L/P'],
                        'email' => $line['EMAIL'],
                    ]);
                } catch (\Throwable $th) {
                    $error = $th->getMessage();
                }
                
                if($insert){
                    $inserted++;
                } else {
                    $aborted++;
                }
            });
        } 
        if(request()->data == 'pd'){
            $text = ' Data Peserta Didik';
            $collection = (new FastExcel)->import($filepath, function ($line) use (&$inserted, &$aborted, &$error){
                $insert = NULL;
                try {
                    $insert = Peserta_didik::updateOrCreate([
                        'sekolah_id' => sekolah_id(),
                        'nama' => $line['NAMA LENGKAP'],
                        'nik' => $line['NIK'],
                        'nisn' => $line['NISN'],
                        'jenis_kelamin' => $line['L/P'],
                        'email' => $line['EMAIL'],
                    ]);
                } catch (\Throwable $th) {
                    $error = $th->getMessage();
                }
                
                if($insert){
                    $inserted++;
                } else {
                    $aborted++;
                }
            });
        }
        if(request()->data == 'nilai-ekskul'){
            $text = ' Nilai Eksktrakurikuler';
            $sheets = (new FastExcel)->importSheets($filepath);
            foreach($sheets as $sheet){
                foreach($sheet as $nilai){
                    $anggota = Anggota_rombel::where('peserta_didik_id', $nilai['PD_ID'])->where('rombongan_belajar_id', $nilai['Ekskul_ID'])->first();
                    if($anggota){
                        Nilai_ekskul::updateOrCreate(
                            [
                                'anggota_rombel_id' => $anggota->anggota_rombel_id,
                                'rombongan_belajar_id' => $nilai['Ekskul_ID'],
                                'peserta_didik_id' => $nilai['PD_ID'],
                            ],
                            [
                                'nilai' => $nilai['nilai'],
                                'deskripsi' => $nilai['deskripsi'],
                            ]
                        );
                        $inserted++;
                    } else {
                        $aborted++;
                    }
                }
            }
        }
        if(request()->data == 'paket-ukk'){
            $text = ' Data Paket UKK';
            $collection = (new FastExcel)->import($filepath, function ($line) use (&$inserted, &$aborted, &$error){
                $insert = NULL;
                try {
                    $insert = Paket_ukk::updateOrCreate([
                        'sekolah_id' => sekolah_id(),
                        'jurusan_sp_id' => request()->jurusan_sp_id,
                        'kurikulum_id' => request()->kurikulum_id,
                        'nomor_paket' => $line['Nomor Paket'],
                        'nama_paket_id' => $line['Judul Paket (ID)'],
                        'nama_paket_en' => $line['Judul Paket (EN)'],
                    ]);
                } catch (\Throwable $th) {
                    $error = $th->getMessage();
                }
                
                if($insert){
                    $inserted++;
                } else {
                    $aborted++;
                }
            });
        }
        if(request()->data == 'unit-ukk'){
            $text = ' Data Unit UKK';
            $collection = (new FastExcel)->import($filepath, function ($line) use (&$inserted, &$aborted, &$error){
                $insert = NULL;
                try {
                    $insert = Unit_ukk::updateOrCreate([
                        'paket_ukk_id' => request()->paket_ukk_id,
                        'kode_unit' => $line['Kode Unit'],
                        'nama_unit' => $line['Nama Unit Kompetensi'],
                    ]);
                } catch (\Throwable $th) {
                    $error = $th->getMessage();
                }
                
                if($insert){
                    $inserted++;
                } else {
                    $aborted++;
                }
            });
        }
        File::delete($filepath);
        $inserted_text = $inserted . $text. ' berhasil disimpan';
        $aborted_text = $aborted . $text. ' gagal disimpan';
        if($error){
            $data = [
                'icon' => 'error',
                'text' => $error,
                'title' => 'Gagal',
            ];    
        } else {
            $data = [
                'icon' => 'success',
                'text' => 'Import excel berhasil di proses<br>'. $inserted_text .'<br>'. $aborted_text,
                'title' => 'Berhasil',
            ];    
        }
        return response()->json($data);
    }
}
