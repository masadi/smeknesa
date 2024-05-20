<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesor;
use App\Models\Dudi;

class AsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Asesor::with(['dudi'])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ilike', '%'.request()->q.'%');
            $query->orWhereHas('dudi', function($query){
                $query->where('nama', 'ilike', '%'.request()->q.'%');
            });
        })
        ->paginate(request()->per_page);
        return response()->json([
            'status' => 'success', 
            'data' => $data, 
        ]);
    }
    public function dudi(){
        return response()->json(Dudi::orderBy('nama')->get());
    }
    public function store(Request $request)
    {
        request()->validate(
            [
                'nama' => ['required'],
                'dudi_id' => ['required'],
            ],
            [
                'nama.required' => 'Nama Asesor tidak boleh kosong!',
                'dudi_id.required' => 'DUDI tidak boleh kosong!',
            ],
        );
        Asesor::create([
            'nama' => $request->nama,
            'dudi_id' => $request->dudi_id,
        ]);
        return response()->json(['icon' => 'success', 'title' => 'Berhasil', 'text' => 'Data Asesor berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [
            'asesor' => Asesor::with(['dudi'])->find($id),
            'dudi' => Dudi::orderBy('nama')->get(),
        ];
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        request()->validate(
            [
                'nama' => ['required'],
                'dudi_id' => ['required'],
            ],
            [
                'nama.required' => 'Nama Asesor tidak boleh kosong!',
                'dudi_id.required' => 'DUDI tidak boleh kosong!',
            ],
        );
        $find = Asesor::find($id);
        $find->nama = $request->nama;
        $find->dudi_id = $request->dudi_id;
        $find->save();
        return response()->json(['icon' => 'success', 'title' => 'Berhasil', 'text' => 'Data Asesor berhasil disimpan']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = Asesor::find($id);
        if($find ){
            $find->delete();
            return response()->json(['icon' => 'success', 'title' => 'Berhasil', 'text' => "Data Asesor berhasil dihapus."]);
        }else{
            return response()->json(['icon' => 'error', 'title' => 'Gagal', 'text' => "Data Asesor tidak ditemukan."]);
        }
    }
}
