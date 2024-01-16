<table border="1">
  <thead>
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
        <th>No</th>
        <th>Nama Siswa</th>
        <th>NISN</th>
        @foreach ($pembelajaran as $item)
          <th>{{$item->nama_mata_pelajaran}}</th>
        @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach($pd as $data)
      <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ strtoupper($data->nama) }}</td>
          <td>{{ $data->nisn }}</td>
          @foreach ($pembelajaran as $item)
            <td>
              {{getNilai($item->nilai->toArray(), $data->anggota_rombel->anggota_rombel_id)}}
            </td>
          @endforeach
      </tr>
    @endforeach
  </tbody>
</table>