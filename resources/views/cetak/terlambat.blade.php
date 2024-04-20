@extends('layouts.cetak-perijinan')
@section('content')
  <h2 id="slogan" style="margin-top:0" class="text-center">Surat Keterlambatan</h2>
  <p class="text-center"><img src="data:image/svg+xml;base64, {!! $qrcode !!}"></p>
  <p class="text-center">{{$data->tanggal_terlambat}}</p>
  <div class="dotted"></div>
  <table>
    <tr>
      <td>Nama</td>
      <td>:</td>
      <td>{{$data->pd->nama}}</td>
    </tr>
    <tr>
      <td>NISN</td>
      <td>:</td>
      <td>{{$data->pd->nisn}}</td>
    </tr>
    <tr>
      <td>Kelas</td>
      <td>:</td>
      <td>{{$data->pd->kelas->nama}}</td>
    </tr>
    <tr>
      <td>Terlambat</td>
      <td>:</td>
      <td>{{$data->pd->terlambat_count}}x | {{ $semester->tahun_ajaran_id }} {{substr($semester->nama,10)}}</td>
    </tr>
  </table>
  <div class="dotted"></div>
@endsection
