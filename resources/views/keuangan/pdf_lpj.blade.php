<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan LPJ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div style="text-align: center;">
        <img src="{{ public_path('assets/img/RT17_Logo.png') }}" alt="logo"
            style="float: left; width: 80px; height: auto; margin-right: 15px;">
        <br>
        <h2 style="margin: 0;">Laporan Keuangan RT.17 RW.10</h2>
        <h3 style="margin: 2;">Perum Pasuruan Anggun Sejahtera</h3>
    </div>
    <br>
    <br>
    <p style="text-align: left; margin-bottom:-1%"><strong>Periode:</strong> {{ $tgl_awal }} - {{ $tgl_akhir }}
    </p>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Masuk (Rp)</th>
                <th>Keluar (Rp)</th>
                <th>Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataTransaksi as $row)
                <tr>
                    <td>{{ date('d-m-Y', strtotime($row['tgl_transaksi'])) }}</td>
                    <td style="text-align: left">{{ $row['deskripsi'] }}</td>
                    <td style="text-align: right">{{ $row['masuk'] }}</td>
                    <td style="text-align: right">{{ $row['keluar'] }}</td>
                    <td style="text-align: right"><strong>{{ $row['saldo'] }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
