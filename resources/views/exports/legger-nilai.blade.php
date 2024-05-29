<table border="1">
  <thead>
    @if ($nilai_pkl)
      <tr>
        <td colspan="4">LEGGER NILAI KELAS {{$rombongan_belajar->nama}}</td>
      </tr>
      <tr>
        <td colspan="4">SEMESTER {{strtoupper($rombongan_belajar->semester->nama)}}</td>
      </tr>
      <tr>
        <td colspan="4">Di Download pada:  {{$today}}</td>
      </tr>
      <tr>
        <td colspan="4"></td>
      </tr>
    @else
      <tr>
        <td colspan="{{$pembelajaran->count() + 3}}">LEGGER NILAI KELAS {{$rombongan_belajar->nama}}</td>
      </tr>
      <tr>
        <td colspan="{{$pembelajaran->count() + 3}}">SEMESTER {{strtoupper($rombongan_belajar->semester->nama)}}</td>
      </tr>
      <tr>
        <td colspan="{{$pembelajaran->count() + 3}}">Di Download pada:  {{$today}}</td>
      </tr>
      <tr>
        <td colspan="{{$pembelajaran->count() + 3}}"></td>
      </tr>
    @endif
    <tr>
        <th>No</th>
        <th>Nama Siswa</th>
        <th>NISN</th>
        @if ($nilai_pkl)
          <th>{{ $nilai_pkl->praktik_kerja_lapangan->pembelajaran->nama_mata_pelajaran }}</th>
        @else
          @foreach ($pembelajaran as $item)
            <th>{{$item->nama_mata_pelajaran}}</th>
          @endforeach
        @endif
    </tr>
  </thead>
  <tbody>
    @foreach($pd as $data)
      <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ strtoupper($data->nama) }}</td>
          <td>{{ $data->nisn }}</td>
          @if ($nilai_pkl)
          <td>{{ round($data->avg_nilai_pkl) }}</td>
          @else
            @foreach ($pembelajaran as $item)
            <td>
              {{getNilai($item->nilai->toArray(), $data->anggota_rombel->anggota_rombel_id)}}
            </td>
            @endforeach
          @endif
      </tr>
    @endforeach
  </tbody>
</table>