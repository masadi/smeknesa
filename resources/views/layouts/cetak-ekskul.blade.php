<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            text-align: center;
            color: #1E3653;
        }
        .center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        div.qrcode {
            position: absolute;
            top: 60px;
            left: 60px;
            padding: 0px;
            width: 150px;
        }
        div.top {
            margin-bottom:70px;
        }
        div.top_2 {
            margin-bottom: 60px;
        }
        .nomor_surat {
            font-size: 18px;
            color: #1E3653;
        }
        .baris_1 {
            font-size: 20px;
            color: #1E3653;
            letter-spacing: 4px;
            font-weight: bold;
        }
        .nama {
            font-family: 'pinyonscript';
            font-size: 60px;
            color: #E1A730;
        }
        .kelas {
            font-size: 20px;
            color: #1E3653;
            font-weight: bold;
            font-style: italic;
            letter-spacing: 2px;
        }
        .deskripsi {
            font-size: 20px;
            color: #1E3653;
            letter-spacing: 2px;
        }
        .baris_2 {
            font-size: 25px;
            color: #1E3653;
        }
        table.table {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
        }
        table.table td {
            padding: 10px;
            font-size: 25px;
            color: #1E3653;
            border: 1px solid #1E3653;
        }
    </style>
    <title>Cetak Dokumen</title>
</head>
<body>
	@yield('content')
</body>
</html>
