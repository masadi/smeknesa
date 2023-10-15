@extends('layouts.cetak-ekskul')
@section('content')
<div class="top_2">&nbsp;</div>
<div class="baris_2">PEMOGRAMAN WEBSITE</div>
<br>
<table class="table">
  <tr>
    <td style="width: 90%">
      @foreach ($pd->ekskul->materi_ekstra as $item)
        {{$loop->iteration}}. {{$item->deskripsi}}<br>
      @endforeach
    </td>
    <td style="width: 10%" class="center">
      @foreach ($pd->ekskul->materi_ekstra as $item)
        {{($item->nilai_ekstra) ? $item->nilai_ekstra->angka : ''}}<br>
      @endforeach
    </td>
  </tr>
  <tr>
    <td class="center bold">Rata-rata Nilai</td>
    <td class="center bold">{{round($pd->ekskul->rerata)}}</td>
  </tr>
</table>
<br>
<br>
<table style="width: 100%">
  <tr>
    <td style="width: 50%" class="center">
      <img src="data:image/svg+xml;base64, {!! $qrcode !!}" alt="qr">
      <br>
      &nbsp;&nbsp;NISN-{{$pd->nisn}}
    </td>
    <td style="width: 50%; font-size:25px;" class="center">
      INSTRUKTUR
      <br>
      <br>
      <br>
      <b><u>{{$pd->ekskul->wali_kelas->nama}}</u></b>
    </td>
  </tr>
</table>
@endsection
