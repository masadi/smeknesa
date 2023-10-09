@extends('layouts.cetak-p5')
@section('content')
<table>
	<tr>
		<td style="width: 70%; font-size:25px; line-height:1.5; vertical-align:top">RAPOR PROJEK PENGUATAN PROFIL PELAJAR PANCASILA</td>
		<td style="width: 30%" class="text-right">
			<img src="{{($get_siswa->rombongan_belajar->sekolah && $get_siswa->rombongan_belajar->sekolah->logo_sekolah) ? url('/storage'.config('erapor.storage').'/images/'.$get_siswa->rombongan_belajar->sekolah->logo_sekolah) : url('/img/logo/tutwuri.png')}}" height="75" alt="logo" />
		</td>
	</tr>
</table>
<br>
<table>
	<tr>
		<th style="width: 20%;font-weight:bold;" class="text-right">Nama Sekolah</th>
		<th style="width: 45%;font-weight:normal;">{{$get_siswa->rombongan_belajar->sekolah->nama}}</th>
		<th style="width: 20%;font-weight:bold;" class="text-right">Kelas</th>
		<th style="width: 15%;font-weight:normal;">{{$get_siswa->rombongan_belajar->nama}}</th>
	</tr>
	<tr>
		<th style="font-weight:bold;" class="text-right">Program Keahlian</th>
		<th style="font-weight:normal;">{{$get_siswa->rombongan_belajar->jurusan_sp->nama_jurusan_sp}}</th>
		<th style="font-weight:bold;" class="text-right">Fase</th>
		<th style="font-weight:normal;">{{($get_siswa->rombongan_belajar->tingkat == 10) ? 'E' : 'F'}}</th>
	</tr>
	<tr>
		<th style="font-weight:bold;" class="text-right">Nama Peserta Didik</th>
		<th style="font-weight:normal;">{{strtoupper($get_siswa->peserta_didik->nama)}}</th>
		<th style="font-weight:bold;" class="text-right">Tahun Pelajaran</th>
		<th style="font-weight:normal;">{{$semester->tahun_ajaran_id}}/{{$semester->tahun_ajaran_id + 1}}</th>
	</tr>
	<tr>
		<th style="font-weight:bold;" class="text-right">NISN</th>
		<th style="font-weight:normal;">{{$get_siswa->peserta_didik->nisn}}</th>
		<th style="font-weight:normal;"></th>
		<th style="font-weight:normal;"></th>
	</tr>
</table>
<table class="table" style="margin-top: 7px; margin-bottom:-20px;">
	@foreach ($rencana_projek as $item)
	<tr>
		<td class="strong" style="padding: 1px;"><strong>Projek Profil {{$loop->iteration}} | {{$item->nama}}</strong></td>
	</tr>
	<tr>
		<td style="padding: 1px; text-align:justify;">{{$item->deskripsi}}</td>
	</tr>
	<tr>
		<td style="padding: 5px;">&nbsp;</td>
	</tr>
	@endforeach
</table>
<table>
	<tr>
		@foreach ($opsi_projek as $opsi)
		<td style="width: 10px">
			<div class="badge bg-{{$opsi->warna}}">&nbsp;&nbsp;&nbsp;&nbsp;</div>
		</td>
		<td>
			<strong class="strong">{{$opsi->nama}}</strong><br>
			{{$opsi->deskripsi}}
		</td>
		@endforeach
	</tr>
</table>
@foreach ($rencana_projek as $rencana)
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>{{$loop->iteration}}. {{$rencana->nama}}</th>
				@foreach ($opsi_projek as $opsi)
				<th style="width: 50px;" class="text-center" style="vertical-align: middle;">{{$opsi->kode}}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			<?php
			$nilai_projek = [];
			foreach ($rencana->elemen_rencana_projek as $item){
				$nilai_projek[$item->dimensi_projek->nama][] = [
					'elemen' => $item->elemen_projek->parent->nama,
					'sub_elemen' => $item->elemen_projek->nama,
					'nilai_projek' => $item->nilai_projek,
				];
			}
			?>
			@foreach ($nilai_projek as $nama_projek => $nilai)
			<tr>
				<th colspan="5"><strong class="strong">{{$nama_projek}}</strong></th>
			</tr>
			@foreach ($nilai as $item)
			<tr>
				<td><span style="font-weight:bold;">{{$item['elemen']}}.</span> {{$item['sub_elemen']}}</td>
				@foreach ($opsi_projek as $opsi)
				<td class="text-center strong">{!! ($item['nilai_projek'] && $item['nilai_projek']->opsi_id == $opsi->id) ? '√' : '' !!}</td>
				@endforeach
			</tr>
			@endforeach
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5" class="active"><strong>Catatan Proses</strong></th>
			</tr>
			<tr>
				<td colspan="5">{{($rencana->catatan_projek) ? $rencana->catatan_projek->catatan : '-'}}</td>
			</tr>
		</tfoot>
	</table>
@endforeach
@endsection