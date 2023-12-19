@extends('layouts.cetak')
@section('content')
<div class="text-center" id="cover_utama">
<br>
<br>
<br>
<img src="{{get_setting('logo') ? asset('storage/images/'.get_setting('logo')) : asset('img/logo/tutwuri.png')}}" style="max-height: 200px" />
<br>
<br>
<br>
<br>
<br>
<br>
<h3>RAPOR PESERTA DIDIK</h3>
<h3>SEKOLAH MENENGAH KEJURUAN</h3>
<h3>(SMK)</h3><br>
<br>
<br>
<br>
<br>
<br>
{{--dd($get_siswa)--}}
<div class="center" style="width:50%; float:left; padding:7px;">Nama Peserta Didik:</div>
<div class="center" style="border:#000000 1px solid; width:50%; float:left; padding:7px; text-align:center;"><strong>{{strtoupper($get_siswa->peserta_didik->nama)}}</strong></div>
<br>
<br>
<br>
<br>
<br>
<div class="center" style="width:50%; float:left; padding:7px;">NISN:</div>
<div class="center" style="border:#000000 1px solid; width:50%; float:left; padding:7px;"><strong>{{$get_siswa->peserta_didik->nisn}}</strong></div>
<div style="width:25%; float:left;">&nbsp;</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<h3>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN TEKNOLOGI<br>REPUBLIK INDONESIA</h3>
</div>
</div>
@endsection