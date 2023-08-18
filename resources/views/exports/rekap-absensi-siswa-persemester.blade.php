<table border="1">
  <thead>
    <tr>
      <td colspan="{{(count($period->toArray()) * 4) + 7}}">REKAP ABSENSI SISWA KELAS {{$rombel->nama}}</td>
    </tr>
    <tr>
      <td colspan="{{(count($period->toArray()) * 4) + 7}}">SEMESTER {{strtoupper($semester->nama)}}</td>
    </tr>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Nama Lengkap</th>
        <th rowspan="2">L/P</th>
        @foreach($period as $date)
          <th colspan="4">{{$date->translatedFormat('F')}}</th>
        @endforeach
        <th colspan="4">TOTAL</th>
    </tr>
    <tr>
      @foreach($period as $date)
        @foreach($presensi as $huruf)
          <th>{{ $huruf }}</th>
        @endforeach
      @endforeach
      @foreach($absensi as $huruf)
        <th>{{ $huruf }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach($siswa as $pd)
      <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $pd->nama }}</td>
          <td>{{ $pd->jenis_kelamin }}</td>
          @foreach($period as $date)
            @foreach($presensi as $huruf)
              <td>{{ getAbsenBulan($pd->presensi, $date->format('m'), $huruf) }}</td>
            @endforeach
          @endforeach
          @foreach($presensi as $huruf)
            <th>{{ jmlAbsen($pd->presensi, $huruf) }}</th>
          @endforeach
      </tr>
    @endforeach
  </tbody>
</table>