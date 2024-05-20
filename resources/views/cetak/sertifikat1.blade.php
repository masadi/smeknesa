@extends('layouts.cetak_sertifikat_1')
@section('content')
<div class="text-center" id="cover_utama">
	<img src="/css/images/logo.png" border="0" width="100" />
	<h2>SERTIFIKAT UJI KOMPETENSI</h2>
	<h3><i>CERTIFICATE OF COMPETENCY ASSESSMENT</i></h3>
	<br />
	Nomor : {{date('Y', strtotime($rencana_ukk->tanggal_sertifikat)).$rencana_ukk->sekolah->npsn.$siswa->peserta_didik->nisn.str_pad($count_penilaian_ukk, 5, 0, STR_PAD_LEFT)}}
<br><br />
Dengan ini menyatakan bahwa,<br />
<i>This is to certify that</i>
<br>
<h1>{{strtoupper($siswa->peserta_didik->nama)}}</h1>
NISN: {{$siswa->peserta_didik->nisn}}
<br>
<br>
Telah mengikuti Uji Kompetensi Keahlian<br />
<i>has taken the competency test</i>
<br>
<br>
pada Konsentrasi Keahlian<br />
<i>in Competency of</i>
<br>
<br>
<h2 style="color:#000000;">{{$rencana_ukk->paket_ukk->jurusan_sp->nama_jurusan_sp}}</h2>
<h3 style="color:#000000;">{{($rencana_ukk->paket_ukk->jurusan_sp->nama_jurusan_en) ? $rencana_ukk->paket_ukk->jurusan_sp->nama_jurusan_en : ''}}</h3>
<br>
pada Judul Penugasan<br />
<i>on Assignment</i>
<br>
<h4 style="padding-bottom:-10px;"><strong>{{$rencana_ukk->paket_ukk->nama_paket_id}}</strong></h4>
<h4><strong><i>{{$rencana_ukk->paket_ukk->nama_paket_en}}</i></strong></h4>
<br>
dengan predikat<br />
<i>with achievement level</i>
<br>
<h4 style="padding-bottom:-10px;"><b>{{($rencana_ukk->nilai_ukk) ? keterangan_ukk($rencana_ukk->nilai_ukk->nilai) : '-'}}</b></h4>
<h4><strong><i>{{($rencana_ukk->nilai_ukk) ? keterangan_ukk($rencana_ukk->nilai_ukk->nilai, 'EN') : '-'}}</i></strong></h4>
<br>
Sertifikat ini berlaku untuk : 3 (tiga) Tahun<br />
<i>This certificate is valid for : 3 (three) Years</i>
<br>
<br />
{{$rencana_ukk->sekolah->kabupaten?->name}},
{{Carbon\Carbon::parse($rencana_ukk->tanggal_sertifikat)->translatedFormat('d F Y')}} 
<table width="100%">
  <tr>
    <td style="width:40%" class="text-center">
		Atas nama {{$rencana_ukk->sekolah->nama}}<br>
		<i>On behalf of {{$rencana_ukk->sekolah->nama}}</i>
<br>
<br>
<br>
<br>
<br>
		<p><b>{{$rencana_ukk->sekolah?->user?->name}}</b></p>
		<p>Kepala Sekolah</p>
		<p><i>School Principal</i></p>
	</td>
	<td style="width:20%"></td>
    <td style="width:40%" class="text-center">{{$rencana_ukk->asesor?->dudi?->nama}}<br>
<br>
<br>
<br>
<br>
<br>
<p><b>{{$rencana_ukk->asesor?->nama}}</b></p>
		<p>Penguji Eksternal</p>
		<p><i>External Assessor</i></p>
</td>
  </tr>
</table>
</div>
@endsection