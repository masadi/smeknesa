<table border="1">
  <thead>
    <tr>
      <td colspan="4">REKAPITULASI REMEDIAL</td>
    </tr>
    <tr>
      <td colspan="4">SEMESTER {{strtoupper($semester->nama)}}</td>
    </tr>
    <tr>
        <th>No</th>
        <th>Kelas</th>
        <th>Wali Kelas</th>
        <th>Jumlah Siswa Remedial</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $item)
      <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->nama }}</td>
          <td>{{ $item->wali_kelas->nama }}</td>
          <td>{{ $item->jml_remedial }}</td>
      </tr>
    @endforeach
  </tbody>
</table>