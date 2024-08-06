@extends('layouts.cetak')
@section('content')
  @include('layouts.header')
  <table class="table">
    <tr>
      <td width="60%"></td>
      <td width="40%">Sampang, {{now()->translatedFormat('d F Y')}}</td>
    </tr>
    <tr>
      <td>
        <table class="table">
          <tr>
            <td>Nomor</td>
            <td>: 422/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/101.6.29.11/{{now()->format('Y')}}</td>
          </tr>
          <tr>
            <td>Sifat</td>
            <td>: Penting</td>
          </tr>
          <tr>
            <td>Lampiran</td>
            <td>: -</td>
          </tr>
          <tr>
            <td>Perihal</td>
            <td>: Panggilan Orang Tua</td>
          </tr>
        </table>
      </td>
      <td style="vertical-align: top; padding-left:0px;">
        <table class="table">
          <tr>
            <td>Kepada</td>
          </tr>
          <tr>
            <td>Yth. Bapak/Ibu Wali Murid</td>
          </tr>
          <tr>
            <td>Di</td>
          </tr>
          <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><u>TEMPAT</u></strong></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <br>
  <br>
  <br>
  <p>Dengan Hormat,</p>
  <p>Mengharap kehadiran Bapak/Ibu Wali Murid dari:</p>
  <table class="table">
    <tr>
      <td>Nama</td>
      <td>: {{$pd->nama}}</td>
    </tr>
    <tr>
      <td>Kelas</td>
      <td>: {{$pd->kelas->nama}}</td>
    </tr>
    <tr>
      <td>Hari/Tanggal</td>
      <td>: {{now()->addDays(1)->translatedFormat('d F Y')}}</td>
    </tr>
    <tr>
      <td>Pukul</td>
      <td>: 08.00 WIB</td>
    </tr>
    <tr>
      <td>Tempat</td>
      <td>: Ruang Bimbingan Konseling SMKN 1 Sampang</td>
    </tr>
    <tr>
      <td>Agenda</td>
      <td>: Koordinasi Perkembangan Peserta Didik</td>
    </tr>
  </table>
  <p>Mengingat pentingnya agenda tersebut, maka Kami mengharap kehadiran Bapak/Ibu. Atas perhatian dan kerjasamanya Kami sampaikan terima kasih.</p>
  <br>
  <br>
  <br>
  <br>
  <br>
  <table class="table">
    <tr>
      <td width="50%"></td>
      <td width="50%">Kepala Sekolah,</td>
    </tr>
    <tr>
      <td></td>
      <td>
        <br>
        <br>
        <br>
        <br>
        <strong><u>{{$user->guru?->nama}}</u></strong><br>
        NIP. {{$user->guru?->nip}}
      </td>
    </tr>
  </table>
@endsection
