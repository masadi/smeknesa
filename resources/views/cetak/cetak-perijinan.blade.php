<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}" />
    <title>Receipt example</title>
  </head>
  <body onload="window.print();">
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
    <p class="small text-center">Surat ini dicetak dari akun {{$user}}</p>
    <div class="dotted"></div>
  </body>
</html>