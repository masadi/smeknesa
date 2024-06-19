@extends('layouts.cetak')
@section('content')
<table border="0" width="100%">
	<tr>
    	<td style="width: 20%;padding-top:5px; padding-bottom:5px; padding-left:0px;">Nama Peserta Didik</td>
		<td style="width: 50%">: {{strtoupper($get_siswa->peserta_didik->nama)}}</td>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;width: 15%">Kelas</td>
		<td style="width: 15%">: {{$get_siswa->rombongan_belajar->nama}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Nomor Induk/NISN</td>
		<td>: {{$get_siswa->peserta_didik->no_induk.' / '.$get_siswa->peserta_didik->nisn}}</td>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Fase</td>
		<td>: {{($get_siswa->rombongan_belajar->tingkat == 10) ? 'E' : 'F'}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Sekolah</td>
		<td>: {{$get_siswa->rombongan_belajar->sekolah->nama}}</td>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Semester</td>
		<td>: {{substr($get_siswa->rombongan_belajar->semester->nama,10)}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Alamat</td>
		<td>: {{$get_siswa->peserta_didik->sekolah->alamat_jalan}}</td>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Tahun Pelajaran</td>
		<td>: {{str_replace('/','-',substr($get_siswa->rombongan_belajar->semester->nama,0,9))}}</td>
	</tr>
</table>
<br />
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width: 2px; vertical-align: middle;" class="text-center">No</th>
			<th style="width: 200px; vertical-align: middle;" class="text-center">Ekstrakurikuler</th>
			<th style="vertical-align: middle;" class="text-center">Keterangan</th>
		</tr>
	</thead>
	<tbody>
		@forelse ($ekstrakurikuler as $item)
		<tr>
			<td class="text-center">{{$loop->iteration}}</td>
			<td>{{$item->nama}}</td>
			<td>Melaksanakan kegiatan ekstrakurikuler {{$item->nama}} dengan {{predikat_ekstra($item->nilai_ekstra->avg('angka'))}}</td>
		</tr>
		@empty
		<td colspan="3">&nbsp;</td>
		@endforelse
	</tbody>
</table>
<br />
<table class="table table-bordered" style="width: 300px;">
	<tr>
		<th colspan="2">Ketidakhadiran</th>
	</tr>
	<tr>
		<td width="100">Sakit</td>
		<td> : {{(jmlAbsen($get_siswa->peserta_didik->presensi, 'S')) ? jmlAbsen($get_siswa->peserta_didik->presensi, 'S') : 0}} hari</td>
	</tr>
	<tr>
		<td>Izin</td>
		<td width="100"> : {{(jmlAbsen($get_siswa->peserta_didik->presensi, 'I')) ? jmlAbsen($get_siswa->peserta_didik->presensi, 'I') : 0}} hari</td>
	</tr>
	<tr>
		<td>Tanpa Keterangan</td>
		<td> : {{(jmlAbsen($get_siswa->peserta_didik->presensi, 'A')) ? jmlAbsen($get_siswa->peserta_didik->presensi, 'A') : 0}} hari</td>
	</tr>
</table>
<br />
<?php
if($get_siswa->rombongan_belajar->semester->semester == 2){
	if($get_siswa->rombongan_belajar->rombel_empat_tahun){
		$text_status = 'Kenaikan Kelas';
		$not_yet = 'Belum dilakukan kenaikan kelas';
	} elseif($get_siswa->rombongan_belajar->tingkat >= 12 ){
		$text_status = 'Status Kelulusan';
		$not_yet = 'Belum dilakukan kelulusan';
	} else {
		$text_status = 'Kenaikan Kelas';
		$not_yet = 'Belum dilakukan kenaikan kelas';
	}
} else {
	$text_status = '';
	$not_yet = '';
}
?>
@if($get_siswa->rombongan_belajar->semester->semester == 2)
<table width="100%" class="table table-bordered">
	<tr>
		<th>Status Kelulusan</th>
	</tr>
	<tr>
		<td style="padding:10px;">
			@if($get_siswa->kenaikan_kelas)
			@if($get_siswa->kenaikan_kelas->status == 3)
			LULUS
			@else
			{{status_kenaikan($get_siswa->kenaikan_kelas->status)}} {{$get_siswa->kenaikan_kelas->nama_kelas}}
			@endif
			@else
			{{$not_yet}}
			@endif
		</td>
	</tr>
</table>
<br>
@endif
<br>
<table width="100%">
	<tr>
	  <td style="width:40%">
		  <p>Orang Tua/Wali</p><br>
  <br>
  <br>
  <br>
  <br>
  <br>
		  <p>...................................................................</p>
	  </td>
	  <td style="width:20%"></td>
	  <td style="width:40%"><p>{{($get_siswa->peserta_didik->sekolah->kabupaten) ? str_replace('KABUPATEN ','',$get_siswa->peserta_didik->sekolah->kabupaten->name) : '-'}}, {{$tanggal_rapor}}<br>Wali Kelas</p><br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <p>
  <u>{{$get_siswa->rombongan_belajar->wali_kelas->nama_lengkap }}</u><br />
  NIP. {{$get_siswa->rombongan_belajar->wali_kelas->nip}}
  </td>
	</tr>
  </table>
  <table width="100%" style="margin-top:10px;">
	<tr>
	  <td style="width:100%;text-align:center;">
		  <p>Mengetahui,<br>Kepala Sekolah</p>
	  <br>
  <br>
  <br>
  <br>
  <br>
  <p><u>{{($kepala_sekolah) ? $kepala_sekolah->guru->nama : '-'}}</u><br />
  NIP. {{($kepala_sekolah) ? $kepala_sekolah->guru->nip : ''}}
  </p>
	  </td>
	</tr>
  </table>
{{--



--}}
@endsection