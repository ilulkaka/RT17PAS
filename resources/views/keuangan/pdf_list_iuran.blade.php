<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>List Iuran Warga {{ $periode }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 30px 30px 30px 30px;

            footer: page-footer;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* =========================
           HEADER
        ========================= */

        .header {
            width: 100%;
            margin-bottom: 12px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        /* Logo */

        .logo-wrapper {
            width: 100px;
            text-align: left;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        /* Judul */

        .header-title {
            text-align: center;
        }

        .header-title h2 {
            margin: 0;
            font-size: 30px;
            font-weight: bold;
        }

        .header-title h3 {
            margin: 4px 0 0;
            font-size: 15px;
            font-weight: normal;
        }

        /* Spacer kanan agar judul benar-benar tengah */

        .header-right {
            width: 100px;
        }


        /* =========================
           INFORMASI
        ========================= */

        .info {
            width: 100%;
            margin-bottom: 8px;
        }

        .info table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            padding: 2px 4px;
        }


        /* =========================
           TABLE IURAN
        ========================= */

        .table-iuran {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table-iuran th,
        .table-iuran td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            vertical-align: middle;
            font-size: 14px;
        }

        .table-iuran thead th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 16px;
        }

        /* =========================
           ZEBRA ROW
        ========================= */

        .table-iuran tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .table-iuran tbody tr:nth-child(even) {
            background-color: #e0e0e0;
        }


        /* Kolom blok */

        .kolom-blok {
            width: 75px;
        }

        /* Kolom bulan */

        .kolom-bulan {
            width: 60px;
        }

        .blok {
            text-align: left !important;
            padding-left: 6px !important;
            font-weight: bold;
        }

        .nominal {
            text-align: right !important;
            padding-right: 6px !important;
        }

        .kosong {
            text-align: center !important;
            color: #555;
        }


        /* =========================
           FOOTER
        ========================= */

        .footer {
            position: fixed;

            left: 0;
            right: 0;
            bottom: -30px;

            height: 25px;

            border-top: 1px solid #999;

            font-size: 8px;
            color: #555;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            border: none;
            padding-top: 5px;
        }

        .footer-left {
            text-align: left;
        }

        .footer-right {
            text-align: right;
        }

        .page-number:after {
            content: "Halaman " counter(page) " dari " counter(pages);
        }


        /* =========================
           KETERANGAN
        ========================= */

        .keterangan {
            margin-top: 10px;
            font-size: 10px;
        }

        .tanggal-cetak {
            margin-top: 5px;
            font-size: 10px;
        }
    </style>

</head>

<body>

    {{-- =========================
         HEADER
    ========================= --}}

    <div class="header">

        <table class="header-table">

            <tr>

                {{-- LOGO KIRI --}}
                <td class="logo-wrapper">

                    {{-- <img src="{{ public_path('img/RT17_Logo.png') }}" class="logo"> --}}
                    <img src="{{ public_path('assets/img/RT17_Logo.png') }}" alt="logo"
                        style="width: auto; height: auto;">
                </td>


                {{-- JUDUL --}}
                <td class="header-title">

                    <h2>
                        RT 17 PAS
                    </h2>

                    <h3>
                        LIST IURAN WARGA
                    </h3>

                </td>


                {{-- SPACER KANAN --}}
                <td class="header-right">
                </td>

            </tr>

        </table>

    </div>


    {{-- =========================
         INFORMASI
    ========================= --}}

    <div class="info">

        <table>

            <tr>

                <td style="font-size: 16px">

                    <strong>Blok :</strong>

                    {{ $blok == 'All' ? 'Semua Blok' : $blok }}

                </td>


                <td style="text-align: right; font-size: 16px">

                    <strong>Periode :</strong>

                    {{ $periode }}

                </td>

            </tr>

        </table>

    </div>


    {{-- =========================
         TABEL IURAN
    ========================= --}}

    <table class="table-iuran">

        <thead>

            <tr>

                <th class="kolom-blok">
                    Blok
                </th>


                @for ($bulan = 1; $bulan <= 12; $bulan++)
                    <th class="kolom-bulan">

                        {{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}-{{ $periode }}

                    </th>
                @endfor

            </tr>

        </thead>


        <tbody>

            @forelse ($listBlok as $itemBlok)

                <tr>

                    {{-- BLOK --}}

                    <td class="blok">

                        {{ $itemBlok }}

                    </td>


                    {{-- BULAN --}}

                    @for ($bulan = 1; $bulan <= 12; $bulan++)
                        @php

                            $nominal = $iuranMap[$itemBlok][$bulan] ?? null;

                        @endphp


                        @if ($nominal !== null)
                            <td class="nominal">

                                {{ number_format($nominal, 0, ',', '.') }}

                            </td>
                        @else
                            <td class="kosong">

                                -

                            </td>
                        @endif
                    @endfor

                </tr>


            @empty

                <tr>

                    <td colspan="13">

                        Tidak ada data iuran
                        untuk periode {{ $periode }}.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================
         KETERANGAN
    ========================= --}}

    <div class="keterangan">

        <strong>Keterangan:</strong>

        Tanda <strong>-</strong>
        berarti belum terdapat pembayaran
        pada bulan tersebut.

    </div>


    {{-- =========================
         TANGGAL CETAK
    ========================= --}}

    <div class="tanggal-cetak">

        Dicetak pada:
        {{ date('d-m-Y H:i') }}

    </div>


    {{-- =========================
         FOOTER
    ========================= --}}

    {{-- <div class="footer">

        <table class="footer-table">

            <tr>

                <td class="footer-left">

                    RT 17 PAS - List Iuran Warga

                </td>

                <td class="footer-right">

                    <span class="page-number"></span>

                </td>

            </tr>

        </table>

    </div> --}}

</body>

</html>
