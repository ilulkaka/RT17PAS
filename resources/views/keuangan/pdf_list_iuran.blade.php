<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>List Iuran Warga {{ $periode }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 15px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* =========================
           HEADER
        ========================= */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header h3 {
            margin: 3px 0 0;
            font-size: 13px;
            font-weight: normal;
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
            padding: 5px 3px;
            text-align: center;
            vertical-align: middle;
        }

        .table-iuran thead th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 8px;
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
        .keterangan {
            margin-top: 10px;
            font-size: 8px;
        }

        .tanggal-cetak {
            margin-top: 5px;
            font-size: 8px;
        }
    </style>

</head>

<body>

    {{-- =========================
         HEADER
    ========================= --}}
    <div class="header">

        <h2>RT 17 PAS</h2>

        <h3>
            LIST IURAN WARGA
        </h3>

    </div>


    {{-- =========================
         INFORMASI
    ========================= --}}
    <div class="info">

        <table>

            <tr>

                <td>
                    <strong>Blok :</strong>
                    {{ $blok == 'All' ? 'Semua Blok' : $blok }}
                </td>

                <td style="text-align: right;">
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

                        Tidak ada data iuran untuk periode {{ $periode }}.

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

        Tanda <strong>-</strong> berarti belum terdapat pembayaran
        pada bulan tersebut.

    </div>


    <div class="tanggal-cetak">

        Dicetak pada:
        {{ date('d-m-Y H:i') }}

    </div>

</body>

</html>
