<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Peserta_didik;
use Rap2hpoutre\FastExcel\FastExcel;
use Validator;
use File;

class UploadController extends Controller
{
    public function index(){
        $validator = Validator::make(request()->all(), 
            [
                'file' => 'required|mimes:xlsx',
            ],
            [
                'file.required' => 'File excel tidak boleh kosong',
                'file.mimes' => 'File excel harus berextensi .xlsx',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        /*$data = [
            'file_name' => request()->file->getClientOriginalName(),
            'generated_new_name' => time() . '.' . request()->file->getClientOriginalExtension(),
        ];
        return response()->json($data);*/
        if(request()->data == 'guru'){
            $text = ' Data Guru';
        } else {
            $text = ' Data Peserta Didik';
        }
        $upload_path = public_path('upload');
        $file_name = request()->file->getClientOriginalName();
        $generated_new_name = time() . '.' . request()->file->getClientOriginalExtension();
        request()->file->move($upload_path, $generated_new_name);
        $filepath = public_path('upload/'.$generated_new_name);
        $error = NULL;
        $inserted = 0;
        $aborted = 0;
        $collection = (new FastExcel)->import($filepath, function ($line) use (&$inserted, &$aborted, &$error){
            $insert = NULL;
            try {
                if(request()->data == 'guru'){
                    $insert = Guru::updateOrCreate([
                        'sekolah_id' => sekolah_id(),
                        'nama' => $line['NAMA LENGKAP'],
                        'nip' => $line['NIP'],
                        'nuptk' => $line['NUPTK'],
                        'jenis_kelamin' => $line['L/P'],
                        'email' => $line['EMAIL'],
                    ]);
                } else {
                    $insert = Peserta_didik::updateOrCreate([
                        'sekolah_id' => sekolah_id(),
                        'nama' => $line['NAMA LENGKAP'],
                        'nik' => $line['NIK'],
                        'nisn' => $line['NISN'],
                        'jenis_kelamin' => $line['L/P'],
                        'email' => $line['EMAIL'],
                    ]);
                }
            } catch (\Throwable $th) {
                $error = $th->getMessage();
            }
            
            if($insert){
                $inserted++;
            } else {
                $aborted++;
            }
        });
        File::delete($filepath);
        $inserted_text = $inserted . ' berhasil disimpan';
        $aborted_text = $aborted . ' gagal disimpan';
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
