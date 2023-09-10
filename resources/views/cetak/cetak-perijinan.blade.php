<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}" />
    <style>
      body { 
            font-family: Tahoma;
            font-size: 12px;
        }
        p {text-align: justify;}
        .table tbody tr th, .table tbody tr td, table tr td, table tr th {padding: 5px !important;line-height:1 !important;}
        hr.baris {
            margin: 0; color: #000; height: 1px; width: 90%; margin-bottom: 5px; margin-top: 5px;
        }
        .dotted {
            border-top: 1px dashed black !important;margin: 0px 10px 0px 10px;
        }
        .kotak {
            border: 1px dotted black !important;margin: 0 auto; padding: 5px;
        }
        p.mt-1 {margin-top: 10px !important;}
        table tr td.p0 {padding: 5px -1px !important;}
      @media print {
            @page {
                margin: 0 auto; /* imprtant to logo margin */
                size: 80mm 120mm; /* imprtant to set paper size */
                margin-top: 10px;
            }
            html,body{margin:0;padding:0}
            
            #printContainer {
                width: 80mm;
                margin: auto;
                /*padding: 10px;*/
                /*border: 2px dotted #000;*/
                text-align: justify;
            }
            h2 {font-size: 14px;}
           .text-center{text-align: center;}
        }
    </style>
    <title>Cetak Perizinan</title>
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