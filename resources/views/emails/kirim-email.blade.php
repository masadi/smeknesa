<!DOCTYPE html>

<html>
<head>
  <title>smkn1sampang.sch.id</title>
</head>
<body>
  <p>Assalamualaikum Bpk/Ibu <strong>{{$mailData['nama_guru']}}</strong>,
    Berikut kami laporkan absensi di kelas {{$mailData['nama_kelas']}} pada {{$mailData['tanggal']}}.</p>
    @foreach ($mailData['data_siswa'] as $item)
    {{$item->nama}} A [{{$item->alpa}}] Jam | I [{{$item->ijin}}] Jam | S [{{$item->sakit}}] Jam | D [{{$item->dadah}}] Jam <br>
    @endforeach
    <p>Untuk rekapitulasi lengkap absensi bisa di download di sistem akademik dengan menggunakan akun masing-masing.
    Jika ditemukan ada ketidak sesuaian data mohon menyampaikan ke guru Piket.
    Waalaikumsalam Warahmatullahi Wabarakatuh</p>
    
    <p>Contact Admin <br>
    Administrator 	: <a href="https://wa.me/6287897760006">0878-977-60006</a> <br>
    Email 			: smeknesa@gmail.com <br>
    Sistem Akademik	: https://smkn1sampang.sch.id</p>
</body>
</html>