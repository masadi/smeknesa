@extends('layouts.cetak-ekskul')
@section('content')
  <div class="qrcode">
    <img src="data:image/svg+xml;base64, {!! $qrcode !!}" alt="qr">
    <!--img src="{{ asset('img/qr.svg') }}" alt="qr" width="150"-->
    &nbsp;&nbsp;NISN-{{$pd->nisn}}
  </div>
  <div class="top">&nbsp;</div>
  <div class="nomor_surat">NOMOR: {{$pd->kelas->nomor_sertifikat}}</div>
  <br>
  <br>
  <div class="baris_1">SERTIFIKAT INI DIBERIKAN KEPADA</div>
  <div class="nama">{{ucwords(strtolower($pd->nama))}}</div>
  <div class="kelas">SISWA KELAS {{$pd->kelas->nama}}</div>
  <div class="deskripsi">
    Telah mengikuti ekstrakurikuler <b>{{$pd->ekskul->nama}}</b>
    <br>dengan predikat <b>Sangat Baik</b> yang dilaksanakan di SMK Negeri 1 Sampang
    <br><b>{{($pd->kelas->semester->semester == 1) ? 'Semester Ganjil' : 'Semester Genap'}} - Tahun Pelajaran {{$pd->kelas->semester->tahun_ajaran_id}}-{{$pd->kelas->semester->tahun_ajaran_id + 1}}</b>
    <br>
    <br>
    <br>
    Sampang, {{$tanggal}}
    <br>Kepala Sekolah
    <br>
    <br>
    <br>
    <br>
    <br><b><u>{{($kepala_sekolah) ? $kepala_sekolah->nama : ''}}</u></b>
    <br>NIP. {{($kepala_sekolah) ? $kepala_sekolah->nip : ''}}
  </div>
@endsection
