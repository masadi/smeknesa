@extends('layouts.cetak-perijinan')
@section('content')
  <h2 id="slogan" style="margin-top:0" class="text-center">Surat Ijin</h2>
  <p class="text-center"><img src="data:image/svg+xml;base64, {!! $qrcode !!}"></p>
  <p class="text-center">NISN: {{$ijin->pd->nisn}}</p>
  <div class="dotted"></div>
  <table>
    <tr>
      <td>Nama</td>
      <td>:</td>
      <td>{{$ijin->pd->nama}}</td>
    </tr>
    <tr>
      <td>Kelas</td>
      <td>:</td>
      <td>{{$ijin->pd->kelas->nama}}</td>
    </tr>
    <tr>
      <td>Izin</td>
      <td>:</td>
      <td>{{$ijin->pilihan}}{{($ijin->alasan) ? " - $ijin->alasan" : ''}}</td>
    </tr>
    <tr>
      <td>Tanggal</td>
      <td>:</td>
      <td>{{$ijin->tanggal_cetak}} {!! ($ijin->jenis_ijin == 'jam') ? '<br>Jam '.$ijin->presensi->pluck('jam')->implode(',') : '' !!}</td>
    </tr>
  </table>
  <div class="kotak text-center">[A:{{$ijin->pd->A}}][I:{{$ijin->pd->I}}][S:{{$ijin->pd->S}}][D:{{$ijin->pd->D}}]</div>
  <br>
  @if ($user)
  <p class="small text-center">Surat ini dicetak dari akun {{$user}}</p>
  @else
  <p class="small text-center">Surat ini dicetak laman perijinan mandiri</p>
  @endif
  <div class="dotted"></div>
@endsection
