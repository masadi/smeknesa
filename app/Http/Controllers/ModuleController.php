<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BahanAjar;

class ModuleController extends Controller
{
    public function index(){
        $data = BahanAjar::with(['jurusan_sp', 'pembelajaran'])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('judul', 'ILIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function add_bahan_ajar(){
        request()->validate(
            [
                'pembelajaran_id' => 'required',
                'judul' => 'required',
                'tingkat' => 'required',
                'jurusan_sp_id' => 'required',
                'deskripsi' => 'required',
                'gambar' => 'required|mimes:jpg,jpeg,png',
                'file_pdf' => 'required|mimes:pdf',
            ],
            [
                'pembelajaran_id.required' => 'Nama Mata Pelajaran tidak boleh kosong',
                'judul.required' => 'Judul tidak boleh kosong',
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
                'jurusan_sp_id.required' => 'Jurusan tidak boleh kosong',
                'deskripsi.required' => 'Deskripsi tidak boleh kosong',
                'gambar.required' => 'Gambar Sampul tidak boleh kosong',
                'gambar.mimes' => 'Ekstensi yang diperbolehkan (jpg,jpeg,png)',
                'langkah_terpilih.*.required' => 'Langkah-langkah Kegiatan Pembelajaran tidak boleh kosong',
                'file_pdf.required' => 'Berkas PDF tidak boleh kosong',
                'file_pdf.mimes' => 'Berkas harus berekstensi .PDF',
            ],
        );
        $gambar = request()->gambar->store('public/images');
        $file_pdf = request()->file_pdf->store('public/file-pdf');
        $string_gambar = Str::of($gambar)->basename();
        $string_file_pdf = Str::of($file_pdf)->basename();
        $find = new BahanAjar;
        if(request()->id){
            $find = $find->find(request()->id);
        }
        $find->author = auth()->user()->user_id;
        $find->jurusan_sp_id = request()->jurusan_sp_id;
        $find->tingkat = request()->tingkat;
        $find->pembelajaran_id = request()->pembelajaran_id;
        $find->judul = request()->judul;
        $find->deskripsi = request()->deskripsi;
        $find->file_pdf = $string_file_pdf;
        $find->gambar = $string_gambar;
        $find->save();
        $data = [
            'icon' => 'success',
            'text' => 'Bahan Ajar berhasil disimpan',
            'title' => 'Berhasil',
            'request' => request()->all(),
        ];
        return response()->json($data);
    }
}
