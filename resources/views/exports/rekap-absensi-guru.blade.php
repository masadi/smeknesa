<table>
  <thead>
    <tr>
      <td colspan="{{($hari * count($presensi)) + 2}}">REKAP ABSENSI GURU PER BULAN {{$nama}}</td>
    </tr>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Nama Guru</th>
        @foreach ($period as $date)
          <th colspan="3">{{$date->format('j')}}</th>
        @endforeach
    </tr>
    <tr>
      @foreach($period as $date)
        @foreach($presensi as $huruf)
          <th>{{ $huruf }}</th>
        @endforeach
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach($data as $item)
      <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->nama }}</td>
          @foreach($period as $date)
            @foreach($presensi as $huruf)
            <td>{{ getAbsen($item->presensi, $date->format('j'), $huruf) }}</td>
            @endforeach
          @endforeach
      </tr>
    @endforeach
  </tbody>
</table>
