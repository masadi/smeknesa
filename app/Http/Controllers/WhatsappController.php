<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Whatsapp;
use App\Models\Peserta_didik;
use App\Jobs\KirimAbsenJob;
use Carbon\Carbon;

class WhatsappController extends Controller
{
    public function index(){
        $data = Whatsapp::orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('title', 'ilike', '%'.request()->q.'%');
            $query->where('description', 'ilike', '%'.request()->q.'%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function add(){
        $validator = Validator::make(request()->all(), 
            [
                'jenis' => ['required'],
                'title' => ['required'],
                'description' => ['required'],
            ],
            [
                'jenis.required' => 'Jenis Pesan tidak boleh kosong',
                'title.required' => 'Judul Pesan tidak boleh kosong',
                'description.required' => 'Isi Pesan tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
        if(request()->whatsapp_id){
            return $this->update();
        }
        $insert = Whatsapp::create([
            'jenis' => request()->jenis,
            'title' => request()->title,
            'description' => request()->description,
        ]);
        if($insert){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Pesan Whatsapp berhasil disimpan',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Pesan Whatsapp gagal disimpan. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function detil(){
        $data = Whatsapp::find(request()->id);
        return response()->json(['data' => $data]);
    }
    public function update(){
        $whatsapp = Whatsapp::find(request()->whatsapp_id);
        $whatsapp->jenis = request()->jenis;
        $whatsapp->title = request()->title;
        $whatsapp->description = request()->description;
        if($whatsapp->save()){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Pesan Whatsapp berhasil diperbaharui',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Pesan Whatsapp gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function hapus(){
        $whatsapp = Whatsapp::find(request()->whatsapp_id);
        if($whatsapp->delete()){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Pesan Whatsapp berhasil terhapus',
                'title' => 'Berhasil',
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Pesan Whatsapp gagal terhapus. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function kirim_pesan(){
        $whatsapp = Whatsapp::find(request()->whatsapp_id);
        $url = 'http://api-wa.erapor-smk.net/chats/send?id=wahyu';
        $data_post = [
            'receiver' => hp(request()->nomor_tujuan),
            'isGroup' => FALSE,
            'message' => [
                'text' => $whatsapp->description,
            ],
        ];
        $response = Http::withOptions([
            'verify' => false,
        ])->post($url, $data_post);
        $result = $response->object();
        if($result->success){
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'success',
                'text' => 'Pesan Whatsapp berhasil terkirim',
                'title' => 'Berhasil',
                'response' => $response->object(),
            ];
        } else {
            $data = [
                'success' => TRUE,
                'errors' => NULL,
                'icon' => 'error',
                'text' => 'Pesan Whatsapp gagal terkirim. Silahkan coba beberapa saat lagi!',
                'title' => 'Gagal',
                'response' => $response->object(),
            ];
        }
        return response()->json($data);
    }
    public function kirim_pesan_old(){
        $whatsapp = Whatsapp::find(request()->whatsapp_id);
        $api_key = '28fef083ce5be360c6c44748950c67e16d1e3fe4'; // API KEY Anda
        $id_device = '2309'; // ID DEVICE yang di SCAN (Sebagai pengirim)
        $url = 'https://api.watsap.id/send-message'; // URL API
        $no_hp = request()->nomor_tujuan;
        //'087864496339'; // No.HP yang dikirim (No.HP Penerima)
        $pesan = $whatsapp->description;
        //'😁 Halo Terimakasih 🙏'; // Pesan yang dikirim
        $data_post = [
            'id_device' => $id_device,
            'api-key' => $api_key,
            'no_hp'   => $no_hp,
            'pesan'   => $pesan
         ];
        $response = Http::withOptions([
            'verify' => false,
        ])->post('https://api.watsap.id/send-message', $data_post);
        return $response->object();
    }
    private function validasi(){
        $validator = Validator::make(request()->all(), 
            [
                'jenis' => ['required'],
                'title' => ['required'],
                'description' => ['required'],
            ],
            [
                'jenis.required' => 'Jenis Pesan tidak boleh kosong',
                'title.required' => 'Judul Pesan tidak boleh kosong',
                'description.required' => 'Isi Pesan tidak boleh kosong',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => FALSE,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
