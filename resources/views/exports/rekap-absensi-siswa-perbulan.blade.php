<table>
  <thead>
    <tr>
      <td colspan="{{($hari * count($presensi)) + 7}}">REKAP ABSENSI SISWA KELAS {{$rombel->nama}}: {{($hari * count($presensi)) + 4}}</td>
    </tr>
    <tr>
      <td colspan="{{($hari * count($presensi)) + 7}}">SEMESTER {{strtoupper($semester->nama)}}</td>
    </tr>
    <tr>
      <td colspan="{{($hari * count($presensi)) + 7}}">Di Download pada: {{$today}}</td>
    </tr>
    <tr>
        <th rowspan="3">No</th>
        <th rowspan="3">Nama Lengkap</th>
        <th rowspan="3">L/P</th>
        <th colspan="{{$hari * count($presensi)}}">{{$bulan->translatedFormat('F Y')}}</th>
        <th rowspan="2" colspan="4">TOTAL</th>
    </tr>
    <tr>
      @foreach($period as $date)
        <th colspan="4">{{$date->format('j')}}</th>
      @endforeach
    </tr>
    <tr>
      @foreach($period as $date)
        @foreach($presensi as $huruf)
          <th>{{ $huruf }}</th>
        @endforeach
      @endforeach
      @foreach($presensi as $huruf)
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
              <td>{{ getAbsen($pd->presensi, $date->format('j'), $huruf) }}</td>
            @endforeach
          @endforeach
          @foreach($presensi as $huruf)
            <th>{{ jmlAbsen($pd->presensi, $huruf) }}</th>
          @endforeach
      </tr>
    @endforeach
  </tbody>
</table>