@extends('layouts.cetak')
@section('content')
  @include('layouts.kop')
  <table class="table">
    <tr>
      <td width="20%">Nama Siswa</td>
      <td width="60%">: {{$pd->nama}}</td>
      <td width="20%" class="text-center" rowspan="5">
        <img src="{{'/storage/qrcodes/'.clean($pd->nama.'-'.$pd->nisn)}}.svg" alt="QrCode" width="100">
      </td>
    </tr>
    <tr>
      <td>NIS/NISN</td>
      <td>: {{$pd->nis.'/'.$pd->nisn}}</td>
    </tr>
    <tr>
      <td>Program Keahlian</td>
      <td>: {{$pd->kelas->jurusan_sp->nama_jurusan_sp}}</td>
    </tr>
    <tr>
      <td>Kelas</td>
      <td>: {{$pd->kelas->nama}}</td>
    </tr>
    <tr>
      <td>Semester</td>
      <td>: {{$pd->kelas->semester->nama}}</td>
    </tr>
  </table>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th class="text-center">Mata Pelajaran</th>
        <th class="text-center">Tujuan Pembelajaran</th>
        <th class="text-center">Nilai Awal</th>
        <th class="text-center">Nilai Remedial</th>
        <th class="text-center">Tanda Tangan</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($pd->kelas->pembelajaran as $item)
      <tr>
        <td class="text-center" rowspan="{{$item->nilai->count()}}">{{$loop->iteration}}</td>
        <td rowspan="{{$item->nilai->count()}}">{{$item->nama_mata_pelajaran}}</td>
        @foreach ($item->nilai as $nilai)
        <td>{{$nilai->tp->deskripsi}}</td>
        <td class="text-center">{{$nilai->angka}}</td>
        <td></td>
        <td></td>
        @endforeach
      </tr>
      @endforeach
    </tbody>
  </table>
  <p>Batas terakhir pengumpulan kertas remedial ini Tanggal {{get_setting('tanggal_remedial', $pd->kelas->semester->semester_id)}}</p>
  <table class="table table-bordered">
    <tr>
      <td colspan="2" class="text-right">Sampang, {{now()->translatedFormat('j F Y')}}</td>
    </tr>
    <tr>
      <td class="text-center" width="50%">
        <p>Ketua Program Keahlian</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>
          <strong>{{$pd->kelas->jurusan_sp->kajur->nama}}</strong><br>
          NIP. {{$pd->kelas->jurusan_sp->kajur->nip}}
        </p>
      </td>
      <td class="text-center" width="50%">
        <p>Wali Kelas</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>
          <strong>{{$pd->kelas->wali_kelas->nama}}</strong><br>
          NIP. {{$pd->kelas->wali_kelas->nip}}
        </p>
      </td>
    </tr>
  </table>
  <p>
    <strong>NB.</strong><br>
    Dokumen ini disimpan di wali kelas sebagai lampiran rapor.
  </p>
@endsection
