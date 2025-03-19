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
    @php
        function formatToFloat($value)
        {
            return (float) str_replace(',', '.', str_replace('.', '', $value));
        }
    @endphp

    <table border="1">
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
            @php
                $totalMasuk = 0;
                $totalKeluar = 0;
                $totalSaldo = 0;
            @endphp

            @foreach ($dataTransaksi as $row)
                @php
                    $masuk = formatToFloat($row['masuk']);
                    $keluar = formatToFloat($row['keluar']);
                    $saldo = formatToFloat($row['saldo']);

                    $totalMasuk += $masuk;
                    $totalKeluar += $keluar;
                    $totalSaldo = $saldo;
                @endphp
                <tr>
                    <td>{{ date('d-m-Y', strtotime($row['tgl_transaksi'])) }}</td>
                    <td style="text-align: left">{{ $row['deskripsi'] }}</td>
                    <td style="text-align: right">{{ number_format($masuk, 2, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($keluar, 2, ',', '.') }}</td>
                    <td style="text-align: right"><strong>{{ number_format($saldo, 2, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: center"><strong>T o t a l :</strong></td>
                <td style="text-align: right"><strong>{{ number_format($totalMasuk, 2, ',', '.') }}</strong></td>
                <td style="text-align: right"><strong>{{ number_format($totalKeluar, 2, ',', '.') }}</strong></td>
                <td style="text-align: right"><strong>{{ number_format($totalSaldo, 2, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Logo di bawah tabel -->
    <div style="text-align: right; margin-top: 20px; margin-right: 40px;">
        <p>Mengetahui,</p>
        <img src="{{ public_path('assets/img/mengetahui.png') }}" alt="logo"
            style="width: 70px; height: auto; margin-top:-10px">
    </div>
</body>

</html>
