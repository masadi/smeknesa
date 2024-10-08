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
<table class="table" class="table table-bordered">
	<thead>
		<tr>
			<th style="vertical-align:middle;width: 2px;" style="vertical-align: middle;">No</th>
			<th style="vertical-align:middle;width: 200px;">Mata Pelajaran</th>
			<th style="vertical-align:middle;width: 100px;" style="vertical-align: middle;" class="text-center">Nilai Akhir</th>
			<th style="vertical-align: middle;" class="text-center">Capaian Kompetensi</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$all_pembelajaran = array();
	$get_pembelajaran = [];
	$set_pembelajaran = $get_siswa->rombongan_belajar->pembelajaran;//()->whereNotNull('kelompok_id')->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
	foreach($set_pembelajaran as $pembelajaran){
		if(in_array($pembelajaran->mata_pelajaran_id, mapel_agama())){
			if(filter_pembelajaran_agama($get_siswa->peserta_didik->agama->nama, $pembelajaran->mata_pelajaran->nama)){
				$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
			}
		} else {
			$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
		}
		//$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
	}
	?>
	@foreach($get_pembelajaran as $pembelajaran)
	<?php
	$nilai_akhir = ($pembelajaran->rerata) ? ceil($pembelajaran->rerata) : 0;
	$all_pembelajaran[$pembelajaran->kelompok->nama_kelompok][] = array(
		'capaian_kompetensi' => array_filter([$pembelajaran->deskripsi_tercapai, $pembelajaran->deskripsi_belum_tercapai]),
		'nama_mata_pelajaran'	=> $pembelajaran->nama_mata_pelajaran,
		'nilai_akhir'	=> $nilai_akhir,
	);
	$i=1;
	?>
	@endforeach
	@foreach($all_pembelajaran as $kelompok => $data_pembelajaran)
	<tr>
		<td colspan="4" class="strong"><strong style="font-size: 13px;">{{$kelompok}}</strong></td>
	</tr>
	@foreach($data_pembelajaran as $pembelajaran)
	<?php $pembelajaran = (object) $pembelajaran; ?>
		{{--
		<tr>
			<td class="text-center" rowspan="{{count($pembelajaran->capaian_kompetensi) + 1}}">{{$i++}}</td>
			<td rowspan="{{count($pembelajaran->capaian_kompetensi) + 1}}">{{$pembelajaran->nama_mata_pelajaran}}</td>
			<td rowspan="{{count($pembelajaran->capaian_kompetensi) + 1}}" class="text-center">{{$pembelajaran->nilai_akhir}}</td>
		</tr>
		<tr>
			@foreach ($pembelajaran->capaian_kompetensi as $capaian_kompetensi)
			<td>{{$capaian_kompetensi->deskripsi}}</td>
			@endforeach
		</tr>
		--}}
		<tr>
			<td class="text-center">{{$i++}}</td>
			<td>{{$pembelajaran->nama_mata_pelajaran}}</td>
			<td class="text-center">{{$pembelajaran->nilai_akhir}}</td>
			<td>
			{!! collect($pembelajaran->capaian_kompetensi)->implode('deskripsi', '<br>') !!}
			</td>
		</tr>
	@endforeach
	@endforeach
	</tbody>
</table>
@endsection