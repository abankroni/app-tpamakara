<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Trial Class</h1>
    </div>
    <div class="content">
        <p><strong>Nama Lengkap:</strong> {{ $laporan->pendaftaranTrialClass->nama_lengkap }}</p>
        <p><strong>Tanggal Pelaksanaan:</strong> {{ $laporan->tanggal_pelaksanaan }}</p>
        <h3>Detail Laporan:</h3>
        <p><strong>Aspek Motorik:</strong> {!! $laporan->aspek_motorik !!}</p>
        <p><strong>Aspek Kognitif:</strong> {!! $laporan->aspek_kognitif !!}</p>
        <p><strong>Aspek Sosial Emosi:</strong> {!! $laporan->aspek_sosial_emosi !!}</p>
        <p><strong>Aspek Kemandirian:</strong> {!! $laporan->aspek_kemandirian !!}</p>
    </div>
</body>
</html>
