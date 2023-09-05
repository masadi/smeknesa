<table border="1">
  <thead>
    <tr>
      <td colspan="{{$totalValue}}">REKAP NILAI MAPEL {{$nama_mata_pelajaran}} KELAS {{$nama}}</td>
    </tr>
    <tr>
      <td colspan="{{$totalValue}}">SEMESTER {{strtoupper($semester->nama)}}</td>
    </tr>
    <tr>
      <td colspan="{{$totalValue}}">Di Download pada:  {{$today}}</td>
    </tr>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Nama Siswa</th>
        <th rowspan="2">NISN</th>
        @if($data_diagnosis->count())
          <th colspan="{{$data_diagnosis->count()}}">Assesment Diagnosis</th>
        @endif
        @if($data_tp->count())
          <th colspan="{{$data_tp->count()}}">Assesment Formatif</th>
          <th rowspan="2">NILAI AKHIR <br/>FORMATIF</th>
        @endif
        @if($data_sumatif->count())
          <th colspan="{{$data_sumatif->count()}}">Assesment Sumatif</th>
          <th rowspan="2">NILAI AKHIR <br/>SUMATIF</th>
        @endif
    </tr>
    <tr>
      @if($data_diagnosis->count())
        @foreach ($data_diagnosis as $diagnosis)
        <th>AD {{$loop->iteration + 1}}</th>
        @endforeach
      @endif
      @if($data_tp->count())
        @foreach ($data_tp as $tp)
        <th>TP {{$loop->iteration + 1}}</th>
        @endforeach
      @endif
      @if($data_sumatif->count())
        @foreach ($data_sumatif as $sumatif)
        <th>AS {{$loop->iteration + 1}}</th>
        @endforeach
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach($data_siswa as $data)
      <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ strtoupper($data->nama) }}</td>
          <td>{{ $data->nisn }}</td>
          @if($data_diagnosis->count())
            @foreach ($data_diagnosis as $diagnosis)
            <td>
              {{showNilaiNama($diagnosis->penilaian_id, $data->nilai)}}
            </td>
            @endforeach
          @endif
          @if($data_tp->count())
            @foreach ($data_tp as $tp)
            <td>
              {{ showNilaiTp($tp->tp_id, $data->nilai) }}
            </td>
            @endforeach
            <td>
              {{ ceil(findAverageAge(nilaiTp($data->nilai, $data_tp))) }}
            </td>
          @endif
          @if($data_sumatif->count())
            @foreach ($data_sumatif as $sumatif)
            <td>
              {{ showNilaiNama($sumatif->penilaian_id, $data->nilai) }}
            </td>
            @endforeach
            <td>
              {{ ceil(findAverageAge(nilaiAsesmen($data->nilai, $data_sumatif))) }}
            </td>
          @endif
      </tr>
    @endforeach
  </tbody>
</table>