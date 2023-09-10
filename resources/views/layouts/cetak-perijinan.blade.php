<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cetak Dokumen</title>
    <!-- Styles -->
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}" />
    <style>
        body { 
            font-family: Tahoma;
            font-size: 12px;
        }
        p {text-align: justify;}
        .table tbody tr th, .table tbody tr td, table tr td, table tr th {padding: 5px !important;line-height:1 !important;}
        hr.baris {
            margin: 0; color: #000; height: 1px; width: 90%; margin-bottom: 5px; margin-top: 5px;
        }
        .dotted {
            border-top: 1px dashed black !important;margin: 0px 10px 0px 10px;
        }
        .kotak {
            border: 1px dotted black !important;margin: 0 auto; width: 50%; padding: 5px;
        }
        p.mt-1 {margin-top: 10px !important;}
        table tr td.p0 {padding: 5px -1px !important;}
        p.small{font-size: 8px;}
        @media print {
            @page {
                margin: 0 auto; /* imprtant to logo margin */
                sheet-size: 300px 108mm; /* imprtant to set paper size */
                margin-top: 10px;
            }
            html {
                direction: rtl;
            }
            html,body{margin:0;padding:0}
            
            #printContainer {
                width: 300px;
                margin: auto;
                /*padding: 10px;*/
                /*border: 2px dotted #000;*/
                text-align: justify;
            }
            h2 {font-size: 14px;}
           .text-center{text-align: center;}
        }
    </style>
</head>
<body onload="window.print();">
    <div id='printContainer'>
	    @yield('content')
    </div>
</body>
</html>
