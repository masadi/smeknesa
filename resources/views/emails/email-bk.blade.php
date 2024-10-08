<!DOCTYPE html>
<html>
<head>
<title>Rekap absensi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
</style>
<body style="max-width: 450px; margin: auto; padding-top: 0%; border: 1px solid beige;">
<h2 style="background-color: #00916e; color: azure; border-bottom : 4px solid rgb(255, 0, 132); border-radius: 10px 10px 0px 0px;padding: 25px;font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;"><center>Rekap Absensi Siswa</center></h2>
<p style="font-size:medium; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-left: 10px;padding-right: 10px;">Hallo {{$mailData['nama_guru']}}! </p>
<p style="padding-left: 10px; padding-right: 10px; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"><i>Assalamualaikum Wr Wb</i><br>

Berikut kami laporkan rekap absensi siswa dengan <strong style="color: red;"> Alpha (A) lebih dari 3 Hari </strong> sampai pada tanggal {{$mailData['tanggal']}}</p>
<hr>
<p style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"></p>
<table>
    @foreach ($mailData['data_siswa'] as $item)
    <tr>
      <td>{{$item['nama']}}	</td>
      <td>{{$mailData['nama_kelas']}}	</td>
      <td>[A={{$item['alpa']}}jam, I={{$item['ijin']}}jam, S={{$item['sakit']}}jam, D={{$item['dispen']}}]</td>
    </tr>
    @endforeach
</table>
<hr>
<p style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-left: 10px; padding-right: 10px;">Untuk rekap absensi lengkap bisa di download di sistem akademik dengan menggunakan akun masing-masing di link berikut :</p>
<br><center><a href="https://smkn1sampang.sch.id" style="border: 1px solid rgb(255, 0, 149); font: white; background-color: rgb(255, 0, 149); padding: 7px 15px 13px 15px; border-radius: 8px; color: azure; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-decoration: none; "><strong>Download Rekap Absensi</strong></a></center>
<br><p style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-left: 10px; padding-right: 10px;">Jika ada ketidak sesuaian data bisa menghubungi <a href="https:wa.me/62818208955">Guru Piket</a><br><i>Wassalamualaikum Wr Wb</i></p>
</body>

<footer style="background-color: rgba(250, 247, 242, 0.689); padding: 10px; text-align: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; border-top: 3px solid rgb(179, 179, 179);">
    <p>Copyright &copy; <a style="text-decoration: none; padding-bottom: 0px;" href="https://smkn1sampang.sch.id"><strong> SMKN 1 SAMPANG</strong></a></p>
<hr>
    <P style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: small; color: brown;">Jalan Suhada 11 A Sampang | Email : smeknesa@gmail.com</P>
  </footer>
</body>
</html>