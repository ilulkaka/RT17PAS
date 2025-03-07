@extends('produksi.template')
@section('head')
    <style>
        .card-no-border {
            border-radius: 0 !important;
        }

        .tengah {
            text-align: center;
        }

        .kiri {
            text-align: left;
        }

        .kanan {
            text-align: right;
        }

        .eloading {
            position: fixed;
            /* Sit on top of the page content */
            display: none;
            /* Hidden by default */
            width: 100%;
            /* Full width (cover the whole page) */
            height: 100%;
            /* Full height (cover the whole page) */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            /* Black background with opacity */
            z-index: 2;
            /* Specify a stack order in case you're using a different order for other elements */
            cursor: pointer;
            /* Add a pointer on hover */
        }

        .spiner {
            position: absolute;
            top: 50%;
            left: 50%;
            font-size: 50px;
            color: white;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
        }


        img {
            position: absolute;
            top: 50%;
            left: 50%;
            font-size: 50px;
            color: white;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
        }

        .swal2-custom-dialog {
            background-color: #eff3ef !important;
            /* Warna latar belakang */
            color: #ebf309 !important;
            /* Warna teks */
            font-size: 1.2em;
            /* Ukuran font */
            width: 300px !important;
            height: auto !important;
            /* Ubah dari fixed height ke auto */
        }

        .swal2-custom-title {
            color: #190abb !important;
            /* Warna teks judul */
            margin-bottom: 10px;
            /* Jarak bawah judul */
        }

        .swal2-custom-content {
            color: #555 !important;
            /* Warna teks konten */
            margin-bottom: 20px;
            /* Jarak bawah konten */
        }

        .swal2-custom-icon {
            width: 50px;
            /* Lebar ikon */
            height: 50px;
            /* Tinggi ikon */
            margin-top: 20px;
            /* Jarak atas ikon */
        }

        .select2-container--default .select2-selection--single {
            border-radius: 0 !important;
        }

        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
            /* tinggi elemen agar seimbang dengan input lain */
            border-radius: 0;
            /* Hilangkan sudut bulat */
            border: 1px solid #ced4da;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px);
            /* panah dropdown agar rata */
        }

        #btn_tambah:focus,
        #btn_simpan:focus {
            background-color: blue;
            color: white;
            outline: none;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endsection
@section('content')


    @if (Session::has('alert-success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('alert-success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif(Session::has('alert-danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('alert-danger') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form method="post" id="formbarcode" style="margin-top: -1%">
        {{ csrf_field() }}
        <div class="card card-secondary rounded-pill">
            <div class="card-header rounded-0">
                <div class="row align-items-center">
                    <div class="col-12 col-sm-5 d-flex align-items-center">
                        <h3 class="card-title" style="font-size: 40px; text-align: left;">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="collapsed"
                                aria-expanded="false">
                                {{ $line[0]->nama_line }} <br>
                                <h3 style="font-size: 26px; ">{{ $shift }}</h3>
                            </a>
                        </h3>
                        <input type="hidden" id="idline" name="idline" value="{{ $line[0]->kode_line }}">
                        <input type="hidden" id="shift" name="shift" value="{{ $shift }}">
                    </div>
                    <div class="col-md-7 d-flex justify-content-center align-items-center">
                        <table class="table table-bordered" width="30%">
                            <thead>
                                <tr>
                                    <th style="width: 60px; font-size:16px; padding: 3px; text-align:center"></th>
                                    <th style="width: 8px; font-size:16px; padding: 3px; text-align:center">NON SHIFT</th>
                                    <th style="width: 8px; font-size:16px; padding: 3px; text-align:center">SHIFT-1</th>
                                    <th style="width: 8px; font-size:16px; padding: 3px; text-align:center">SHIFT-2</th>
                                    <th style="width: 8px; font-size:16px; padding: 3px; text-align:center">SHIFT-3</th>
                                    <th style="width: 8px; font-size:16px; padding: 3px; text-align:center">TOTAL</th>
                                    <th style="width: 8px; font-size:16px; padding: 3px; text-align:center">TARGET</th>
                                    <th
                                        style="width: 8px; font-size:16px; color:black; padding: 3px; text-align:center; background-color: white;">
                                        DIFF</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width:60px; padding: 1px; text-align:center">TOT QTY</td>
                                    <td id="nonshift" style="padding: 1px; text-align:center"></td>
                                    <td id="shift1" style="padding: 1px; text-align:center"></td>
                                    <td id="shift2" style="padding: 1px; text-align:center"></td>
                                    <td id="shift3" style="padding: 1px; text-align:center"></td>
                                    <td id="totalshift" style="padding: 1px; text-align:center"></td>
                                    <td id="targetharian" style="padding: 1px; text-align:center"></td>
                                    <td id="diffharian"
                                        style="padding: 1px; text-align:center; background-color: white; font-weight:bold">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:60px; padding: 1px; text-align:center">TOT PAS</td>
                                    <td id="pas-nonshift" style="padding: 1px; text-align:center"></td>
                                    <td id="pas-shift1" style="padding: 1px; text-align:center"></td>
                                    <td id="pas-shift2" style="padding: 1px; text-align:center"></td>
                                    <td id="pas-shift3" style="padding: 1px; text-align:center"></td>
                                    <td id="pas-totalshift" style="padding: 1px; text-align:center"></td>
                                    <td id="targetharian" style="padding: 1px; text-align:center"></td>
                                    <td id="diffharian"
                                        style="padding: 1px; text-align:center; background-color: white; font-weight:bold">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- <p></p>
                <div id="accordion">
                    <div class="card card-primary">
                        <div id="collapseOne" class="panel-collapse in collapse"
                            style="font-family: 'Courier New', Courier, monospace;">
    
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="position-relative p-3 bg-info" style="height: 200px">
                                        <div class="ribbon-wrapper">
                                            <div class="ribbon bg-danger">
                                                Hasil %
                                            </div>
                                        </div>
                                        Rekap Hasil <br>
                                        <small></small>
                                        <h6>Rata - Rata Pcs/lot = <b>{{ $rataratalot }}</b> Pcs</h6>
    
                                        <div class="row invoice-info">
                                            <div class="col-sm-3 invoice-col">
                                                <font style="color:gold;"><b><u>Prosentase All Shift</u></b></font>
                                                <address>
                                                    <h6>F = <b>{{ $prosentaseF }}</b> %</h6>
                                                    <h6>CR = <b>{{ $prosentaseCR }}</b> %</h6>
                                                </address>
                                            </div>
                                            <div class="col-sm-3 invoice-col">
                                                <font style="color:gold;"><b><u>Comp/OIL</u></b></font>
                                                <address>
                                                    <h6>Comp-F = <b>{{ $compf }}</b></h6>
                                                    <h6>Comp-CR = <b>{{ $compcr }}</b></h6>
                                                    <h6>OIL-F = <b>{{ $oilf }}</b></h6>
                                                    <h6>OIL-CR = <b>{{ $oilcr }}</b></h6>
                                                </address>
                                            </div>
    
                                            <div class="col-sm-3 invoice-col">
                                                <font style="color:gold;"><b><u>SHAPE</u></b></font>
                                                <address>
                                                    <h6>Non Uchi = <b>{{ $t1bf }}</b></h6>
                                                    <h6>Uchicatto = <b>{{ $t1ic }}</b></h6>
                                                </address>
                                            </div>
    
                                            <div class="col-sm-3 invoice-col">
                                                <font style="color:gold;"><b><u>PROSES</u></b></font>
                                                <address>
                                                    <h6>Reguler = <b>{{ $reg }}</b></h6>
                                                    <h6>Proses 2x = <b>{{ $proses2x }}</b></h6>
                                                    <h6>Tenaoshi = <b>{{ $tenaoshi }}</b></h6>
                                                </address>
                                            </div>
    
                                        </div>
                                    </div>
                                </div>
    
                                <div class="col-sm-4">
                                    <div class="position-relative p-3 bg-info" style="height: 200px">
                                        <div class="ribbon-wrapper">
                                            <div class="ribbon bg-danger">
                                                Hasil %
                                            </div>
                                        </div>
                                        Rekap Hasil <br>
                                        <small></small>
                                        <br>
                                        <div class="row invoice-info">
                                            <div class="col-sm-6 invoice-col">
                                                <font style="color:gold;"><b><u>Pcs</u></b></font>
                                                <address>
                                                    <h6>SHIFT 1 = <b>{{ $s1 }}</b> Pcs</h6>
                                                    <h6>SHIFT 2 = <b>{{ $s2 }}</b> Pcs</h6>
                                                    <h6>SHIFT 3 = <b>{{ $s3 }}</b> Pcs</h6>
                                                    <h6>NON SHIFT = <b>{{ $nonshift }}</b> Pcs</h6>
                                                </address>
                                            </div>
                     
                                            <div class="col-sm-6 invoice-col">
                                                <font style="color:gold;"><b><u>PAS / Cycle</u></b></font>
                                                <address>
                                                    <h6><b>{{ $pas1 }}</b></h6>
                                                    <h6><b>{{ $pas2 }}</b></h6>
                                                    <h6><b>{{ $pas3 }}</b></h6>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                            </div>
    
                        </div>
                    </div>
                </div> --}}
            {{-- <button type="button" id="b_test" name="b_test">test toas</button> --}}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-1">
                        <strong for="sel">NIK</strong>
                        <select name="sel" id="sel"
                            class="form-control select2 rounded-0 @error('nik') is-invalid @enderror" style="width: 100%;"
                            required>
                            <option value="">NIK</option>
                            @foreach ($nomerinduk as $i)
                                <option value="{{ $i->nama }}">{{ $i->nik }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <strong for="nama">Nama Operator</strong>
                        <input type="hidden" name="nik" id="nik">
                        <input type="hidden" class="form-control @error('nama')is-invalid @enderror" name="operator"
                            id="operator" placeholder="Nama Operator">
                        <input type="text" class="form-control rounded-0 @error('nama')is-invalid @enderror"
                            name="operator1" id="operator1" placeholder="Nama Operator" disabled>
                    </div>
                    {{-- @if ($line[0]->kode_line == 190 || $line[0]->kode_line == 230 || $line[0]->kode_line == 240 || $line[0]->kode_line == 250 || $line[0]->kode_line == 260)
                        <div class="form-group col-md-3"><strong>Remark</strong>
                            <select name="remark" id="remark" class="form-control" required>
                                <option value="Reguler">Reguler</option>
                                <option value="Proses2x">Proses 2x</option>
                                <option value="Proses3x">Proses 3x</option>
                                <option value="Proses4x">Proses 4x</option>
                                <option value="Proses5x">Proses 5x</option>
                                <option value="Proses6x">Proses 6x</option>
                                <option value="Proses7x">Proses 7x</option>
                                <option value="Proses8x">Proses 8x</option>
                                <option value="Tenaoshi">Tenaoshi</option>
                            </select>
                        </div>
                    @elseif ($line[0]->kode_line == 280)
                        <div class="form-group col-md-3"><strong>Remark</strong>
                            <select name="remark" id="remark" class="form-control" required>
                                <option value="Proses1k">Proses 1k</option>
                                <option value="Proses2k">Proses 2k</option>
                                <option value="Proses3k">Proses 3k</option>
                                <option value="Proses4k">Proses 4k</option>
                                <option value="Proses5k">Proses 5k</option>
                                <option value="Proses1s">Proses 1s</option>
                                <option value="Proses2s">Proses 2s</option>
                                <option value="Proses3s">Proses 3s</option>
                                <option value="Proses4s">Proses 4s</option>
                                <option value="Proses5s">Proses 5s</option>
                                <option value="Tenaoshi">Tenaoshi</option>
                            </select>
                        </div>
                    @else
                    @endif --}}
                    <div class="form-group col-md-1"><strong>Remark</strong>
                        {{-- <select name="remark" id="remark" class="form-control" required>
                                <option value="Reguler">Reguler</option>
                                <option value="Proses2x">Proses 2x</option>
                                <option value="Tenaoshi">Tenaoshi</option>
                            </select> --}}
                        <input type="text" name="remark" id="remark" class="form-control rounded-0"
                            value="Reguler" readonly>
                    </div>
                </div>
                <p></p>
                <div class="row" style="margin-top: -1%">
                    <div class="col col-md-6">
                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <div class="row">
                                    @if (
                                        $line[0]->kode_line == 300 ||
                                            $line[0]->kode_line == 310 ||
                                            $line[0]->kode_line == 320 ||
                                            $line[0]->kode_line == 400)
                                        <div class="form-group col-md-2">
                                            <strong for="tempat_kejadian">No Urut</strong>
                                            <input type="text" class="form-control rounded-0" name="no_urut"
                                                id="no_urut" placeholder="No Urut Proses" required>
                                        </div>
                                    @elseif ($line[0]->kode_line == 100)
                                        <div class="form-group col-md-2">
                                            <strong>Process Type</strong>
                                            <select type="text" class="form-control select2" name="no_urut"
                                                id="no_urut" placeholder="No Urut Proses" required>
                                                <option value="">Select Type...</option>
                                                <option value="Tepar">Tepar</option>
                                                <option value="Baito Langsung">Baito Langsung</option>
                                                <option value="Baito Bolak-Balik">Baito Bolak-balik</option>
                                            </select>
                                        </div>
                                    @elseif ($line[0]->kode_line == 280)
                                        <div class="form-group col-md-2">
                                            <strong>Process Type</strong>
                                            <select type="text" class="form-control select2" name="no_urut"
                                                id="no_urut" placeholder="No Urut Proses" required>
                                                <option value="">Select Type...</option>
                                                <option value="Kari">Kari</option>
                                                <option value="Shiage">Shiage</option>
                                            </select>
                                        </div>
                                    @endif
                                    <div class="form-group col-md-2">
                                        <strong for="tempat_kejadian">No Mesin</strong>
                                        <input type="text" onkeypress="return hanyaAngka(event)"
                                            class="form-control rounded-0" name="no_meja" id="no_meja" maxlength="2"
                                            placeholder="No Mesin" required>
                                    </div>

                                    <script>
                                        function hanyaAngka(evt) {
                                            var charCode = (evt.which) ? evt.which : event.keyCode
                                            if (charCode > 31 && (charCode < 48 || charCode > 57))

                                                return false;
                                            return true;
                                        }
                                    </script>

                                    <div class="form-group col-md-3">
                                        <strong for="tgl_kejadian">Tanggal Proses</strong>
                                        <input type="date" class="form-control rounded-0" value="{{ $tgl }}"
                                            name="tgl_proses1" id="tgl_proses1" placeholder="Tanggal Kejadian"
                                            disabled="true">
                                        <input type="hidden" class="form-control rounded-0" value="{{ $tgl }}"
                                            name="tgl_proses" id="tgl_proses" placeholder="Tanggal Kejadian">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <strong for="tempat_kejadian">Barcode No</strong>
                                        <input type="text" class="form-control rounded-0" name="barcodeno"
                                            id="barcodeno" placeholder="Barcode No" required>
                                    </div>
                                    @if ($line[0]->kode_line == 20)
                                        <div class="form-group col-md-3">
                                            <label id="lbl_futatsuwari" name="lbl_futatsuwari"
                                                style="margin-top: 17%; color:red"></label>
                                        </div>
                                    @endif

                                    @if (
                                        $line[0]->kode_line == 51 ||
                                            $line[0]->kode_line == 52 ||
                                            $line[0]->kode_line == 53 ||
                                            $line[0]->kode_line == 54 ||
                                            $line[0]->kode_line == 55 ||
                                            $line[0]->kode_line == 56)
                                        <div class="form-group col-md-2">
                                            <strong>Haba Awal</strong>
                                            <input type="number" class="form-control rounded-0" name="ukuran_haba_awal"
                                                id="ukuran_haba_awal">
                                        </div>
                                    @endif
                                    @if (
                                        $line[0]->kode_line == 40 ||
                                            $line[0]->kode_line == 51 ||
                                            $line[0]->kode_line == 52 ||
                                            $line[0]->kode_line == 53 ||
                                            $line[0]->kode_line == 54 ||
                                            $line[0]->kode_line == 55 ||
                                            $line[0]->kode_line == 56 ||
                                            $line[0]->kode_line == 60)
                                        <div class="form-group col-md-2">
                                            @if (
                                                $line[0]->kode_line == 51 ||
                                                    $line[0]->kode_line == 52 ||
                                                    $line[0]->kode_line == 53 ||
                                                    $line[0]->kode_line == 54 ||
                                                    $line[0]->kode_line == 55 ||
                                                    $line[0]->kode_line == 56)
                                                <strong>Haba Finish</strong>
                                            @else
                                                <strong>Ukuran Haba</strong>
                                            @endif
                                            <input type="number" class="form-control rounded-0" name="ukuran_haba"
                                                id="ukuran_haba">
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <strong>Part No</strong>
                                        <input type="text" class="form-control rounded-0" name="partno"
                                            id="partno" placeholder="Part No">
                                        <input type="hidden" class="form-control rounded-0" name="partno1"
                                            id="partno1">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <strong for="tempat_kejadian">Lot No</strong>
                                        <input type="text" class="form-control rounded-0" name="lotno"
                                            id="lotno" placeholder="Lot No">
                                        <input type="hidden" class="form-control rounded-0" name="lotno1"
                                            id="lotno1">
                                    </div>

                                    {{-- @if ($line[0]->kode_line == 320) --}}
                                    <div class="form-group col-md-2">
                                        <strong>In Qty</strong>
                                        <input type="number" class="form-control rounded-0"
                                            style="color: blue; font-size:18px; font-weight:bold" name="incoming_qty"
                                            id="incoming_qty" value="0" readonly>
                                    </div>
                                    {{-- @endif --}}

                                    @if ($line[0]->kode_line == 20)
                                        <div class="form-group col-md-2">
                                            <strong for="tempat_kejadian">Finish Qty</strong>
                                            <input type="number" class="form-control rounded-0"
                                                style="color: green; font-size:18px; font-weight:bold" name="finish_qty"
                                                id="finish_qty" value="0" readonly>
                                            <input type="hidden" class="form-control rounded-0" name="finish_qty1"
                                                id="finish_qty1" value="0">
                                        </div>
                                    @else
                                        <div class="form-group col-md-2">
                                            <strong for="tempat_kejadian">Finish Qty</strong>
                                            <input type="number" class="form-control rounded-0"
                                                style="color: green; font-size:18px; font-weight:bold" name="finish_qty"
                                                id="finish_qty">
                                            <input type="hidden" class="form-control rounded-0" name="finish_qty1"
                                                id="finish_qty1">
                                        </div>
                                    @endif
                                    <div class="form-group col-md-2">
                                        <strong for="tempat_kejadian">Reject</strong>
                                        <input type="number" class="form-control rounded-0"
                                            style="color: red; font-size:18px; font-weight:bold" name="reject"
                                            id="reject" value="0" readonly>
                                        <input type="hidden" class="form-control rounded-0" name="reject1"
                                            id="reject1" value="0" readonly>
                                    </div>
                                </div>

                                <div id="notification"></div>

                                @if ($line[0]->kode_line == 20)
                                    <div class="row">
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <strong>Tanegata</strong>

                                                <input type="text" class="form-control rounded-0" name="tanegata"
                                                    id="tanegata" readonly required>
                                            </div>
                                        </div>
                                        <div class="float-left col-md-3">
                                            <div class="form-group">
                                                <strong>Cast No</strong>
                                                <input type="text" class="form-control rounded-0" name="cast_no"
                                                    id="cast_no" readonly required>
                                            </div>
                                        </div>
                                        <div class="float-left col-md-3">
                                            <div class="form-group">
                                                <strong>Barashi Opr</strong>
                                                <select name="moulding_opr" id="moulding_opr"
                                                    class="form-control select2" style="width: 100%;" required>
                                                    <option value="">Moulding Opr</option>
                                                    @foreach ($oprmoulding as $o)
                                                        <option value="{{ $o->nama }}">{{ $o->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <strong>Omo (Pcs)</strong>
                                                <input type="number" class="form-control rounded-0" name="omogata"
                                                    id="omogata" min="0" readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="float-left col-md-2">
                                                <div class="form-group">
                                                    <strong>Piles (Pcs)</strong>
                                                    <input type="number" class="form-control" name="piles" id="piles"
                                                        min="0">
                                                </div>
                                            </div> --}}
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <strong>Yama (17)</strong>
                                                <input type="number" class="form-control rounded-0" name="yama_17"
                                                    id="yama_17" min="0" readonly>
                                            </div>
                                        </div>
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <strong>Yama (15)</strong>
                                                <input type="number" class="form-control rounded-0" name="yama_15"
                                                    id="yama_15" min="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    @if (
                                        $line[0]->kode_line == 70 ||
                                            $line[0]->kode_line == 100 ||
                                            $line[0]->kode_line == 110 ||
                                            $line[0]->kode_line == 120 ||
                                            $line[0]->kode_line == 131 ||
                                            $line[0]->kode_line == 130 ||
                                            $line[0]->kode_line == 180 ||
                                            $line[0]->kode_line == 160 ||
                                            $line[0]->kode_line == 190 ||
                                            $line[0]->kode_line == 202 ||
                                            $line[0]->kode_line == 204)
                                        <div class="float-left form-group col-md-4">
                                            <div class="form-group">
                                                <strong>Dandoriman</strong>
                                                <select name="dandoriman" id="dandoriman" class="form-control select2"
                                                    style="width: 100%;" required>
                                                    <option value="">Dandoriman</option>
                                                    @foreach ($deptdandori as $d)
                                                        <option value="{{ $d->nama }}">{{ $d->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    @if (
                                        $line[0]->kode_line == 51 ||
                                            $line[0]->kode_line == 70 ||
                                            $line[0]->kode_line == 100 ||
                                            $line[0]->kode_line == 110 ||
                                            $line[0]->kode_line == 120 ||
                                            $line[0]->kode_line == 131 ||
                                            $line[0]->kode_line == 130 ||
                                            $line[0]->kode_line == 180 ||
                                            $line[0]->kode_line == 160 ||
                                            $line[0]->kode_line == 172 ||
                                            $line[0]->kode_line == 174 ||
                                            $line[0]->kode_line == 190 ||
                                            $line[0]->kode_line == 202 ||
                                            $line[0]->kode_line == 204)
                                        <div class="float-left col-md-3">
                                            <div class="form-group">
                                                <strong>Dandori</strong>
                                                <select name="dandori" id="dandori" class="form-control select2"
                                                    required>
                                                    <option value="">Select ...</option>
                                                    <option value="None">None</option>
                                                    <option value="SEMI">SEMI</option>
                                                    <option value="FULL">FULL</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($line[0]->kode_line == 140 || $line[0]->kode_line == 150)
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <strong>Shafto Panjang</strong>
                                                <input type="number" class="form-control rounded-0" name="shafto_pjg"
                                                    id="shafto_pjg" min="0" value="0">
                                            </div>
                                        </div>
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <strong>Shafto Pendek</strong>
                                                <input type="number" class="form-control rounded-0" name="shafto_pdk"
                                                    id="shafto_pdk" min="0" value="0">
                                            </div>
                                        </div>
                                    @endif
                                    @if (
                                        $line[0]->kode_line == 40 ||
                                            $line[0]->kode_line == 50 ||
                                            $line[0]->kode_line == 51 ||
                                            $line[0]->kode_line == 52 ||
                                            $line[0]->kode_line == 53 ||
                                            $line[0]->kode_line == 54 ||
                                            $line[0]->kode_line == 55 ||
                                            $line[0]->kode_line == 56 ||
                                            $line[0]->kode_line == 100 ||
                                            $line[0]->kode_line == 110 ||
                                            $line[0]->kode_line == 120 ||
                                            $line[0]->kode_line == 172 ||
                                            $line[0]->kode_line == 174 ||
                                            $line[0]->kode_line == 190)
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <strong>Cycle / PAS</strong>
                                                <input type="number" class="form-control rounded-0" name="cycle"
                                                    id="cycle" value="0">
                                            </div>
                                        </div>
                                    @endif
                                    @if (
                                        $line[0]->kode_line == 40 ||
                                            $line[0]->kode_line == 50 ||
                                            $line[0]->kode_line == 51 ||
                                            $line[0]->kode_line == 52 ||
                                            $line[0]->kode_line == 53 ||
                                            $line[0]->kode_line == 54 ||
                                            $line[0]->kode_line == 55 ||
                                            $line[0]->kode_line == 56 ||
                                            $line[0]->kode_line == 100 ||
                                            $line[0]->kode_line == 110 ||
                                            $line[0]->kode_line == 120 ||
                                            $line[0]->kode_line == 140 ||
                                            $line[0]->kode_line == 150 ||
                                            $line[0]->kode_line == 172 ||
                                            $line[0]->kode_line == 174 ||
                                            $line[0]->kode_line == 190)
                                        <div class="float-left col-md-3">
                                            <div class="form-group">
                                                <strong>Total Cycle / PAS</strong>
                                                <input type="hidden" class="form-control rounded-0" name="total_cycle"
                                                    id="total_cycle">
                                                <input type="number" class="form-control rounded-0" name="total_cycle1"
                                                    id="total_cycle1" disabled>
                                            </div>
                                        </div>
                                    @endif
                                    @if (
                                        $line[0]->kode_line == 70 ||
                                            $line[0]->kode_line == 160 ||
                                            $line[0]->kode_line == 202 ||
                                            $line[0]->kode_line == 204 ||
                                            $line[0]->kode_line == 210 ||
                                            $line[0]->kode_line == 320 ||
                                            $line[0]->kode_line == 400)
                                        <div class="float-left col-md-3">
                                            <div class="form-group">
                                                <strong>Total Cycle / PAS</strong>
                                                <input type="number" class="form-control rounded-0" name="total_cycle"
                                                    id="total_cycle" value="0">
                                            </div>
                                        </div>
                                    @endif
                                    @if ($line[0]->kode_line == 51 || $line[0]->kode_line == 172 || $line[0]->kode_line == 174)
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <strong>Dressing</strong>
                                                <input type="number" class="form-control rounded-0" name="dressing"
                                                    id="dressing" value="0" required>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                {{-- @if ($line[0]->kode_line == 30)
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <label>Tanegata</label>
    
                                                <input type="text" class="form-control" name="tanegata" id="tanegata"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="float-left col-md-3">
                                            <div class="form-group">
                                                <label>Cast No</label>
                                                <input type="text" class="form-control" name="cast_no" id="cast_no"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="float-left col-md-3">
                                            <div class="form-group">
                                                <label>Moulding Opr</label>
                                                <select name="moulding_opr" id="moulding_opr" class="form-control select2"
                                                    style="width: 100%;" required>
                                                    <option value="">Opr Moulding</option>
                                                    @foreach ($oprmoulding as $o)
                                                        <option value="{{ $o->nama }}">{{ $o->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="float-left col-md-2">
                                            <div class="form-group">
                                                <label>Moulding No</label>
                                                <input type="number" class="form-control" name="moulding_no"
                                                    id="moulding_no" min="1" max="10" required>
                                            </div>
                                        </div>
                                    @endif --}}
                                <div class="float-right">
                                    @if ($line[0]->kode_line < 320)
                                        <br>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col col-md-3">
                        <div class="card card-danger card-outline">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <strong for="kode_ng">Kode NG</strong>
                                        <select name="kode_ng" id="kode_ng" class="form-control select2"
                                            style="width: 100%;">
                                            <option value="">Kode NG</option>
                                            @foreach ($masterng as $m)
                                                <option value="{{ $m->type_ng }}">{{ $m->kode_ng }}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <div class="form-group col-md-6">
                                        <strong for="nama">Type NG</strong>
                                        <input type="hidden" class="form-control rounded-0" name="ng1"
                                            id="ng1">
                                        <input type="text" class="form-control rounded-0" name="type_ng"
                                            id="type_ng" placeholder="Type NG" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <strong for="tempat_kejadian">NG Qty</strong>
                                        <input type="number" class="form-control rounded-0" name="ng_qty"
                                            id="ng_qty">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" col-md-9">
                                        <label for="">Sisa NG : </label>
                                        <label for="diffrjt" id="diffrjt" style="color: red;">
                                            0
                                        </label>
                                    </div>
                                    <div class=" col-md-3">
                                        <button type="button" class="btn btn-danger btn-flat float-right"
                                            id="btn_tambah">Tambah</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-success card-outline">
                            <div class="card-body">
                                <div class="row">
                                    {{-- <div class="form-group col-md-4">
                                        <strong for="nama">Jenis WIP</strong>
                                        <select name="jenis_wip" id="jenis_wip" class="form-control select2">
                                            <option value="">Pilih Jenis WIP ...</option>
                                            <option value="Partial">Partial</option>
                                            <option value="Finish">Finish</option>
                                        </select>
                                    </div> --}}
                                    <div class="form-group col-md-12">
                                        <strong for="">Next Proses</strong>
                                        <select name="next_proses" id="next_proses" class="form-control select2">
                                            <option value="">Pilih Next Proses...</option>
                                            {{-- @foreach ($next_proses as $next)
                                                <option value="{{ $next->kode_line }}">{{ $next->nama_line }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="button" value="Simpan"
                                            class="form-control btn btn-success btn-flat" id="btn_simpan"
                                            name="btn_simpan">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col col-md-3">
                        <table class="table table-bordered" id="tb_ng">
                            <thead>
                                <tr>
                                    <th style="width: 90px">NG Code</th>
                                    <th>NG Type</th>
                                    <th style="width: 80px">NG Qty</th>
                                    <th style="width: 80px">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total :</th>
                                    <th colspan="2" id="qty">QTY</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </form>

    <div id="loadingscreen" class="eloading">
        <div id="text" class="spiner"><i class="fas fa-spinner fa-spin"></i></div>
    </div>

    <div id="loadingscreen1" class="eloading">
        <p><img src="{{ asset('/assets/img/mobile-check.gif') }}" class="center-block img-circle" /></p>
    </div>


    <div class="card card-warning rounded-pill">
        <div class="card-header rounded-pill">
            <div class="row">
                <div class="col-12">
                    {{-- <h3 class="card-title">Hasil Produksi PerHari</h3> --}}
                    <span class="float-right">
                        <button class="btn btn-sm" id="btn-shikakaricamu">
                            <font color="blue"><i class="fas fa-list-ol mr-1"></i>
                                <u> Shikakari {{ $line[0]->nama_line }}</u>
                            </font>
                        </button>
                        <button class="btn btn-sm" id="btn-reportoperator">
                            <font color="blue"><i class="fas fa-file-csv mr-1"></i>
                                <u> Report Operator</u>
                            </font>
                        </button>
                        <button class="btn btn-sm" id="btn-deletehasil">
                            <font color="red"><i class="fas fa-trash mr-1"></i>
                                <u><b> Delete </b></u>
                            </font>
                        </button>
                        {{-- @if ($line[0]->kode_line > 320)
                                    <button class="btn btn-sm" id="btn-rekapoperator">
                                        <font color="blue"><i class="fas fa-list-ol mr-1"></i>
                                            <u> Rekap Operator</u>
                                        </font>
                                    </button>
                                @endif 
                                @if ($line[0]->kode_line == 320)
                                    <button class="btn btn-sm" id="btn-ceklot">
                                        <font color="blue"><i class="fas fa-check-double mr-1"></i>
                                            <u> Cek Input Vs Prones</u>
                                        </font>
                                    </button>
                                @endif --}}
                    </span>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="tb_hasil_produksi">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Part No</th>
                            <th>Lot No</th>
                            <th>Incoming</th>
                            <th>Finish Qty</th>
                            <th>NG Qty</th>
                            <th>Operator</th>
                            <th>Shift</th>
                            <th>No Mesin</th>
                            <th>Cycle</th>
                            @if ($line[0]->kode_line == 30)
                                <th>Opr moulding</th>
                            @else
                                <th>Dressing</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div> --}}

    <!-- /.card-body -->
    {{-- </div> --}}
    <!-- /.card -->
    {{-- </div> --}}

    <!--Modal NG-->
    <div class="modal fade bd-example-modal-lg" id="modal-NG" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detaillist">Detail NG</h5>
                </div>
                <div class="modal-body">

                    <table class="table" id="t_detail_NG">
                        <thead>
                            <tr>
                                <th>NG Code</th>
                                <th>NG Type</th>
                                <th>NG Qty</th>
                            </tr>
                        </thead>
                    </table>

                </div>
                <div class="modal-footer">
                    <div class="col col-md-3">
                        <button type="button" class="btn btn-secondary" id="btn-close-list">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--Modal Rekap Operator-->
    <div class="modal fade bd-example-modal-lg" id="modal-rekapoperator" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detaillist">Rekap</h5>
                </div>
                <form id="modal-opr">
                    <div class="modal-body">
                        <div class="row align-left">
                            <label for="" class="col-md-2 text-left">Start </label>
                            <input type="date" class="form-control col-md-4" id="tgl1"
                                value="{{ date('Y-m') . '-01' }}">
                            <label for="" class="col-md-2 text-center">Sampai</label>
                            <input type="date" class="form-control col-md-4" id="tgl2"
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <p></p>
                        <div class="row align-left">
                            <div class="col-md-2 text-left"><label>Operator</label></div>
                            <div class="col col-md-4">
                                <select name="opr" id="opr"
                                    class="form-control select2 @error('line_proses') is-invalid @enderror"
                                    style="width: 100%;" required>
                                    <option value="All">All</option>
                                    {{-- @foreach ($opr as $o)
                                        <option value="{{ $o->operator }}">{{ $o->operator }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-flat" id="btn-process">Process</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal List Rekap-->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-listrekap"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rekap Operator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive p-0">
                    <div class="container">

                        <table id="tb_listrekap" class="table table-bordered table-striped dataTable">
                            <thead>
                                <th>Operator</th>
                                <th>Finish Qty</th>
                                <th>NG Qty</th>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="keluar" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cek Lot Prones-->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-ceklot"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cek Lot No System Vs Prones System</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive p-0">
                    <div class="container">

                        <table id="tb_ceklot" class="table table-bordered table-striped dataTable">
                            <thead>
                                <th>Part No</th>
                                <th>Lot No</th>
                                <th>Status</th>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="keluar" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cek Lot Prones-->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-ceklot-OK"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <font style="text-align: right; font-family: 'Courier New', Courier, monospace;">Cek
                            Lot No System Vs Prones System</font>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive p-0">
                    <div class="container">
                        <thead>
                            <th>
                                <font
                                    style="text-align: right; color: blue; font-family: 'Courier New', Courier, monospace;">
                                    Data OK .</font>
                            </th>
                        </thead>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="keluar" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal lapor NG-->
    {{-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-lapor"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <font style="text-align: right; font-family: 'Courier New', Courier, monospace;">Qty Finish kurang
                            dari Plan, Segera Lapor PPIC .</font>
                    </h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="keluar" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Modal Report operator-->
    <div class="modal fade" id="modal-reportoperator" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Report Line / Operator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col col-md-12">
                        <input type="date" class="col-md-4" id="tgl_awal" value="{{ date('Y-m-d') }}">
                        <label for="" class="col-md-2 text-center">Sampai</label>
                        <input type="date" class="col-md-4" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                    </div>
                    <br>
                    <div class="row">
                        <div class="col col-md-3"><label>Line</label></div>
                        <label>:</label>
                        <div class="col col-md-7">
                            <select name="kode_line" id="kode_line"
                                class="form-control select2 @error('kode_line') is-invalid @enderror" style="width: 100%;"
                                required>
                                @foreach ($line as $l)
                                    <option value="{{ $l->kode_line }}">{{ $l->nama_line }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <p>

                    </p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success btn-flat" id="btn-preview">Preview</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal List CAMU-->
    <div class="modal fade bd-example-modal" tabindex="-1" role="dialog" id="modal-shikakaricamu"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">SHIKAKARI {{ $line[0]->nama_line }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                {{-- @if ($line[0]->kode_line == '131')
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-8">
                                <strong>Line</strong>
                                <select name="kode_gsm" id="kode_gsm" class="form-control rounded-0 select2" required>
                                    <option value="All">Pilih Line . .</option>
                                    <option value="130">KARI</option>
                                    <option value="180">SHIAGE</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <strong> Reload </strong>
                                <button class="btn btn-primary btn-flat" id="btn_reload_kg"><i
                                        class="fa fa-sync"></i></button>
                            </div>
                        </div>
                    </div>
                    <hr>
                @endif --}}

                <div class="modal-body table-responsive p-0">
                    <div class="container">
                        <table id="tb_shikakaricamu" class="table table-bordered table-striped dataTable text-nowrap">
                            <thead>
                                <th>Part No</th>
                                <th>Lot No</th>
                                <th>Qty</th>
                                <th>Tgl In</th>
                                <th>TAG</th>
                                <th>Warna</th>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align:center; ">TOTAL</th>
                                    <th style="text-align:center; font-size: large;"></th>
                                    <th style="text-align:center; font-size: large;"></th>
                                    <th style="text-align:center; font-size: large;"></th>
                                    <th style="text-align:center; font-size: large;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-left mr-auto" style="font-size: 18px; color:red"><u>
                            Total Expres : <b id="jml" name="jml">0</b></u>
                    </div>
                    <button type="button" class="btn btn-secondary" id="keluar" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <!--Modal Next Process (NP)-->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal_np"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <font style="text-align: right; font-family: 'Courier New', Courier, monospace;">Qty Finish kurang
                            dari Plan, Segera Lapor PPIC .</font>
                    </h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="keluar" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_delete_hasil">
                        <div class="col col-md-12">
                            @csrf
                            <div class="row">
                                <div class="col col-md-3"><label>Barcode No</label></div>
                                <label class="col col-md-1">:</label>
                                <div class="col col-md-8">
                                    <input type="text" name="d_barcode" id="d_barcode" class="form-control">
                                </div>
                            </div>
                        </div>
                        <p></p>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger btn-flat" id="btn-delete-hasil">Deleted</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingscreen" class="eloading">
        <div id="text" class="spiner"><i class="fa fa-spinner fa-spin"></i></div>
    </div>

@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(function() {

            $('.select2').select2({
                theme: 'bootstrap4'
            })
        });

        $(document).ready(function() {
            var key = localStorage.getItem('produksi_token');
            var line = $("#kode_line").val();
            var shift = $("#shift").val();
            var tgl = $("#tgl_proses").val();
            get_hasil_shift(tgl, shift, line, key);

            $("#sel").select2('focus');

            // const Toast = Swal.mixin({
            //     toast: true,
            //     position: 'center',
            //     showConfirmButton: false,
            //     timer: 7000,
            //     customClass: {
            //         container: 'swal2-toast' // Menggunakan kelas CSS yang sudah dibuat
            //     }
            // });

            var fg = 0;

            $('#b_test').click(function() {
                Swal.fire({
                    title: '<div class="swal2-custom-title">Judul</div>',
                    html: '<div class="swal2-custom-content">Ini adalah konten.</div>',
                    showConfirmButton: true,
                    customClass: {
                        popup: 'swal2-custom-dialog',
                        title: 'swal2-custom-title',
                        content: 'swal2-custom-content',
                        icon: 'swal2-custom-icon'
                    }
                });
            });

            //$("#pcs").html(resp.sum);
            $("#btn_simpan").prop('disabled', true);
            partno.disabled = true;
            lotno.disabled = true;
            //loadingon();
            var totng = 0;
            var sisang = 0;

            /*var workresult = $('#tb_hasil_produksi').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/api/hasilproduksi',
                    type: "POST",
                    headers: {
                        "token_req": key
                    },
                    data: function(d) {
                        d.shift = $("#shift").val();
                        d.line = $("#idline").val();
                        d.tgl = $("#tgl_proses").val();
                    }
                },
                columnDefs: [{

                        targets: [0],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: [10],
                        data: null,
                        render: function(data, type, row, meta) {
                            if (data.line_proses == 30) {
                                return data.moulding_opr;
                            } else {
                                return data.dressing;
                            }
                        }
                    },
                    {
                        targets: [11],
                        data: null,
                        //defaultContent: "<button class='btn btn-success'>Complited</button>"
                        defaultContent: "<button class='btn btn-success btn-sm btn-flat'>Detail NG</button><button class='btn btn-danger btn-sm btn-flat'>Hapus</button>"
                    },
                ],

                columns: [{
                        data: 'id_hasil_produksi',
                        name: 'id_hasil_produksi'
                    },
                    {
                        data: 'part_no',
                        name: 'part_no'
                    },
                    {
                        data: 'lot_no',
                        name: 'lot_no'
                    },
                    {
                        data: 'incoming_qty',
                        name: 'incoming_qty'
                    },
                    {
                        data: 'finish_qty',
                        name: 'finish_qty'
                    },
                    {
                        data: 'ng_qty',
                        name: 'ng_qty'
                    },
                    {
                        data: 'operator',
                        name: 'operator'
                    },
                    {
                        data: 'shift',
                        name: 'shift'
                    },
                    {
                        data: 'no_mesin',
                        name: 'no_mesin'
                    },
                    {
                        data: 'total_cycle',
                        name: 'total_cycle',
                        render: $.fn.dataTable.render.number(',', '.', 0, '')
                    },
                    //{ data: 'dressing', name: 'dressing' },
                ],
            });*/

            $("#sel").on('select2:select', function() {
                var nik = $(this).children("option:selected").html();
                var operator = $(this).children("option:selected").val();

                $("#nik").val(nik);
                $("#operator").val(operator);
                $("#operator1").val(operator);

                var idline = $("#idline").val();
                if (idline == 300 || idline == 310 || idline == 320 || idline == 400 || idline == 100 ||
                    idline == 280) {
                    $("#no_urut").focus();
                } else {
                    $("#no_meja").focus();
                }
            });

            $("#remark").change(function(e) {
                //var code = e.keyCode || e.which;
                var idline = $("#idline").val();

                if (idline == 131 || idline == 130 || idline == 180) {
                    $("#no_meja").focus();
                }

            });

            $("#kode_ng").on('select2:select', function() {
                var kodeng = $(this).children("option:selected").html();
                var typeng = $(this).children("option:selected").val();
                $("#ng1").val(kodeng);
                $("#type_ng").val(typeng);

                $("#ng_qty").focus();
            });

            $("#kode_ng").keydown(function(event) {
                if (event.keyCode >= 51) {
                    $("#ng_qty").focus();
                }
            });

            $("#no_urut").on('select2:select', function() {
                $("#no_meja").focus();
            });

            $("#no_urut").change(function(event) {
                if ($("#no_urut").val() == null || $("#no_urut").val() == '') {
                    alert('Please Input your Process type .')
                } else {
                    $("#no_meja").val('');
                    $("#no_meja").focus();
                }
            });

            $("#no_meja").keypress(function(event) {
                if (event.keyCode === 13) {
                    $("#barcodeno").focus();
                }
            });

            $("#ukuran_haba_awal").keypress(function(event) {
                if (event.keyCode === 13) {
                    $("#ukuran_haba").focus();
                }
            });

            $("#ukuran_haba").keypress(function(event) {
                var idline = $("#idline").val();
                if (event.keyCode === 13) {
                    if (idline == 40 || idline == 52 || idline == 53 || idline == 54 || idline == 55 ||
                        idline == 56) {
                        $("#cycle").val('');
                        $("#cycle").focus();
                    } else if (idline == 51) {
                        $("#dandori").focus();
                    } else {
                        $("#finish_qty").focus();
                    }
                }
            });

            // Function to check quantities
            function checkQuantities() {
                var qty_finish = parseFloat($("#finish_qty").val());
                var qty_in = parseFloat($("#incoming_qty").val());
                var idline = $("#idline").val();

                var ignoredLines = ['20', '320'];

                if (ignoredLines.includes(idline)) {
                    $("#notification").html("");
                    $("#reject").prop('readonly', false);
                } else {
                    if (qty_finish > qty_in) {
                        $("#notification").html(
                            "<div class='alert alert-danger'>Finish Quantity cannot be greater than Incoming Quantity. (" +
                            qty_finish + " > " + qty_in + ")</div>"
                        );
                        $("#reject").prop('readonly', true);
                        $("#kode_ng").prop('disabled', true);
                    } else if (isNaN(qty_finish)) {
                        $("#reject").prop('readonly', true);
                        $("#kode_ng").prop('disabled', true);
                    } else {
                        $("#notification").html("");
                        // $("#reject").prop('disabled', false);
                        $("#kode_ng").prop('disabled', false);

                        $("#reject").val(qty_in - qty_finish);
                    }
                }
                $("#finish_qty1").val($("#finish_qty").val());
            }

            // Attach the function to the input change event
            $("#finish_qty, #incoming_qty").on("input", function() {
                checkQuantities();
            });

            $("#finish_qty").keypress(function(event) {
                var idline = $("#idline").val();
                var qty_finish = Number($("#finish_qty").val());
                var qty_in = Number($("#incoming_qty").val());

                if (event.keyCode === 13) {
                    if ($("#finish_qty").val() === null || $("#finish_qty").val() === '') {
                        alert("Qty Finish tidak boleh Null !");
                        return;
                    }

                    // if (qty_finish < fg) {
                    //     //alert('Lapor PPIC, Qty Finish kurang dari Plan .');
                    //     // $('#modal-lapor').modal('show');
                    //     // $("#reject").val('');
                    //     // $("#reject").focus();
                    //     $("#kode_ng").select2('focus');
                    // } else {
                    //     if (idline == 30) {
                    //         $("#reject").val('');
                    //         $("#reject").focus();
                    //     } else if (idline == 70) {
                    //         $("#dandoriman").select2('focus');
                    //     } else {
                    //         $("#finish_qty1").val(qty_finish);
                    //         $("#reject").val('');
                    //         $("#reject").focus();
                    //     }
                    // }
                    if (idline == 320) {
                        if (qty_finish == qty_in) {
                            $("#finish_qty1").val($("#finish_qty").val());

                            $("#next_proses").attr('disabled', false);
                            $("#next_proses").select2(); // Select2 diinisialisasi

                            let newOption = new Option("Finish Good", 9909, true, true);
                            $("#next_proses").append(newOption).trigger('change');

                            $("#next_proses").on('select2:open', function() {
                                $(this).select2(
                                    "close"
                                ); // Menutup dropdown jika pengguna mencoba untuk mengubah pilihan
                            });

                            $("#btn_simpan").prop('disabled', false);
                            $("#btn_simpan").focus();
                        } else if (qty_finish == 0) {
                            $("#reject").val(qty_in);
                            $("#reject").prop('readonly', true);
                            $("#kode_ng").select2('focus');

                            var d = $("#reject").val();
                            d = Number(d);
                            if (d - totng < 0) {
                                alert('Qty NG salah');
                                $("#reject").val(sisang + totng);
                            } else {
                                sisang = d;
                                $("#diffrjt").html(sisang);
                            }

                        } else {
                            $("#reject").prop('readonly', false);
                            $("#reject").focus();
                            // $("#kode_ng").select2('focus');
                            $("#finish_qty1").val($("#finish_qty").val());
                        }
                    } else {
                        if (qty_finish == qty_in) {
                            $("#next_proses").attr('disabled', false);
                            $("#next_proses").select2('focus');

                            $("#finish_qty1").val($("#finish_qty").val());
                        } else if (qty_finish > qty_in) {
                            alert("Qty Finish lebih besar dari Qty In .");
                            return;
                        } else {
                            $("#kode_ng").select2('focus');
                            $("#finish_qty1").val($("#finish_qty").val());

                            var d = $("#reject").val();
                            d = Number(d);
                            if (d - totng < 0) {
                                alert('Qty NG salah');
                                $("#reject").val(sisang + totng);
                            } else {
                                sisang = d;
                                $("#diffrjt").html(sisang);
                            }
                        }
                    }
                }
            });


            $("#finish_qty").change(function(event) {
                var idline = $("#idline").val();
                var qty_finish = $("#finish_qty").val();
                var cycle = $("#cycle").val();

                if (idline == 51 || idline == 52 || idline == 53 || idline == 54 || idline ==
                    55 ||
                    idline == 56 || idline == 170) {
                    if (cycle == null || cycle == '' || cycle == '0') {
                        $("#cycle").val('0');
                        $("#total_cycle").val('0');
                        $("#total_cycle1").val('0');
                    } else {
                        $("#total_cycle").val(qty_finish * cycle);
                        $("#total_cycle1").val(qty_finish * cycle);
                    }
                } else if (idline == 100 || idline == 110 || idline == 120 || idline == 190) {
                    $("#total_cycle").val(qty_finish / cycle);
                    $("#total_cycle1").val(qty_finish / cycle);
                } else if (idline == 50) {
                    if (cycle == null || cycle == '' || cycle == '0') {
                        $("#cycle").val('0');
                        $("#total_cycle").val('0');
                        $("#total_cycle1").val('0');
                    } else {
                        $("#total_cycle").val((qty_finish / cycle) / 2);
                        $("#total_cycle1").val((qty_finish / cycle) / 2);
                    }
                }
            });

            $("#ng_qty").keypress(function(event) {
                if (event.keyCode === 13) {
                    $("#btn_tambah").focus();
                }
            });

            $("#reject").on('focus', function() {
                var qty_finish = $("#finish_qty").val();
                $("#finish_qty1").val(qty_finish);
                if ($("#incoming_qty").val() <= 0) {
                    alert("Please Input Incoming Qty .");
                    $("#incoming_qty").focus();
                }
            });

            $("#reject").keypress(function(event) {
                var idline = $("#idline").val();
                var qty_finish = $("#finish_qty").val();
                var cycle = $("#cycle").val();
                var reject = $("#reject").val();
                var totcycle = (Number(qty_finish) + Number(reject)) / Number(cycle);

                if (event.keyCode == 13) {
                    if ($("#reject").val() == '' || $("#reject").val() == null) {
                        alert('Please Input your Reject Qty .');
                        $("#reject").val('0');
                        $("#reject").focus();
                    }
                    // if (idline == 40) {
                    //     if ($("#reject").val() == 0) {
                    //         $("#total_cycle").val(totcycle);
                    //         $("#total_cycle1").val(totcycle);
                    //         // $("#btn_simpan").prop('disabled', false);
                    //         // $("#btn_simpan").focus();
                    //         $("#jenis_wip").attr('disabled', false);
                    //         $("#jenis_wip").select2('focus');
                    //     } else if (idline == 40) {
                    //         $("#total_cycle").val(totcycle);
                    //         $("#total_cycle1").val(totcycle);
                    //         $("#kode_ng").select2('focus');
                    //         // $("#btn_simpan").prop('disabled', true);
                    //     }
                    // } else 
                    if (idline == '320' || idline == '400') {
                        if ($("#reject").val() == 0) {
                            $("#next_proses").attr('disabled', false);
                            $("#next_proses").select2(); // Select2 diinisialisasi

                            let newOption = new Option("Finish Good", 9909, true, true);
                            $("#next_proses").append(newOption).trigger('change');

                            $("#next_proses").on('select2:open', function() {
                                $(this).select2(
                                    "close"
                                ); // Menutup dropdown jika pengguna mencoba untuk mengubah pilihan
                            });

                            $("#btn_simpan").prop('disabled', false);
                            $("#btn_simpan").focus();
                            // $("#jenis_wip").attr('disabled', false);
                            // $("#jenis_wip").select2('focus');
                        } else {
                            $("#kode_ng").select2('focus');
                            // $("#btn_simpan").prop('disabled', true);
                        }
                    } else {
                        if ($("#reject").val() == 0) {
                            // $("#btn_simpan").prop('disabled', false);
                            // $("#btn_simpan").focus();
                            // $("#jenis_wip").attr('disabled', false);
                            $("#next_proses").attr('disabled', false);
                            $("#next_proses").select2('focus');
                        } else {
                            $("#next_proses").attr('disabled', true);
                            $("#kode_ng").select2('focus');
                            // $("#btn_simpan").prop('disabled', true);
                        }
                    }
                    //$("#reject1").val(reject);
                }
            });
            /*
                        $("#tanegata").on('select2:select', function() {
                            $("#cast_no").focus();
                        });
            */
            $("#tanegata").keypress(function(event) {
                if (event.keyCode === 13) {
                    $("#cast_no").focus();
                }
            });

            $("#cast_no").keypress(function(event) {
                if (event.keyCode === 13) {
                    if ($("#cast_no").val() == '') {
                        alert(' Masukkan Cast No .');
                    } else {
                        $("#moulding_opr").select2('focus');
                    }
                }
            });

            $("#moulding_opr").on('select2:select', function() {
                var part = $("#partno").val();
                var partSS = part.substring(0, 2);

                // if ($("#cast_no").val() == '') {
                //     alert(' Masukkan Cast No .');
                //     $("#cast_no").focus();
                // } else if (partSS == 'SS') {
                //     $("#yama_15").prop('readonly', true);
                //     $("#yama_17").prop('readonly', true);
                //     $("#reject").focus();
                // } else {
                //     // $("#moulding_no").focus();
                //     $("#yama_15").prop('readonly', false);
                //     $("#yama_17").prop('readonly', false);
                //     $("#yama_17").focus();
                // }

                $("#next_proses").attr('disabled', false);
                $("#next_proses").select2('focus');
            });

            // $("#piles").keypress(function(event) {
            //     $("#yama_17").focus();
            // })

            $("#yama_17").keypress(function(event) {
                $("#yama_15").focus();
            })

            $("#yama_15").keypress(function(event) {
                var om = $("#omogata").val();
                // var pil = $("#piles").val();
                var ya_17 = $("#yama_17").val();
                var ya_15 = $("#yama_15").val();
                var futat = $("#lbl_futatsuwari").html();

                if (event.keyCode === 13) {
                    if (om == null || om == '') {
                        alert('Masukkan Pcs Omogata .');
                        $("#omogata").focus();
                        // } else if (pil == null || pil == '') {
                        //     alert('Masukkan Pcs Piles .');
                        //     $("#piles").focus();
                    } else if (ya_17 == null || ya_17 == '' || ya_15 == null || ya_15 == '') {
                        alert('Masukkan Pcs Yama .');
                        $("#yama").focus();
                    } else {
                        if (futat == 'FUTATSUWARI') {
                            var y17 = om * ya_17 * 17;
                            var y15 = om * ya_15 * 15;
                            var yy = ((om * ya_17 * 17) + (om * ya_15 * 15)) * 2
                            $("#finish_qty").val(yy);
                            $("#finish_qty1").val(yy);
                            $("#reject").focus();
                        } else {
                            var y17 = om * ya_17 * 17;
                            var y15 = om * ya_15 * 15;
                            var yy = (om * ya_17 * 17) + (om * ya_15 * 15)
                            $("#finish_qty").val(yy);
                            $("#finish_qty1").val(yy);
                            $("#reject").focus();
                        }
                    }
                }
            })

            $("#moulding_no").keypress(function(event) {
                var qty_finish = $("#finish_qty").val();
                if (event.keyCode === 13) {
                    if ($('#moulding_no').val() >= 11) {
                        alert('Moulding Nomer Max sampai 10');
                        $('#moulding_no').val('');
                    } else if ($('#moulding_no').val() <= '0') {
                        alert('Masukkan Nomer Moulding .');
                    } else {
                        $("#finish_qty1").val(qty_finish);
                        $("#reject").val('');
                        $("#reject").focus();
                    }
                }
            });

            $("#dandoriman").on('select2:select', function() {
                $("#dandori").select2('focus');
            });

            $("#dandori").on('select2:select', function() {
                var idline = $("#idline").val();

                if ($("#dandori").val() == '') {
                    alert('select dandori .');
                } else if (idline == 70 || idline == 160 || idline == 202 || idline == 204) {
                    $("#total_cycle").val('');
                    $("#total_cycle").focus();
                } else if (idline == 131 || idline == 130 || idline == 180) {
                    $("#finish_qty").val('');
                    $("#finish_qty").focus();
                } else {
                    $("#cycle").val('');
                    $("#cycle").focus();
                }
            });

            $("#cycle").keypress(function(event) {
                var idline = $("#idline").val();
                if (event.keyCode === 13) {
                    if (idline == 40 || idline == 50 || idline == 52 || idline == 53 ||
                        idline == 54 ||
                        idline == 55 || idline == 56 || idline == 100 || idline == 110 ||
                        idline == 120 ||
                        idline == 190) {
                        $("#finish_qty").focus();
                    } else if (idline == 51 || idline == 170) {
                        $("#dressing").val('');
                        $("#dressing").focus();
                    } else {
                        $("#reject").focus();
                    }
                }
            });

            $("#cycle").change(function(event) {
                var idline = $("#idline").val();
                var qty_finish = $("#finish_qty").val();
                var cycle = $("#cycle").val();

                if (idline == 51 || idline == 52 || idline == 53 || idline == 54 || idline ==
                    55 ||
                    idline == 56 || idline == 170) {
                    $("#total_cycle").val(qty_finish * cycle);
                    $("#total_cycle1").val(qty_finish * cycle);
                } else if (idline == 100 || idline == 110 || idline == 120 || idline == 190) {
                    $("#total_cycle").val(qty_finish / cycle);
                    $("#total_cycle1").val(qty_finish / cycle);
                } else if (idline == 50) {
                    $("#total_cycle").val((qty_finish / cycle) / 2);
                    $("#total_cycle1").val((qty_finish / cycle) / 2);
                }
            });

            $("#total_cycle").keypress(function(event) {
                var idline = $("#idline").val();
                if (event.keyCode === 13) {
                    if (idline == 70) {
                        $("#finish_qty").val('');
                        $("#finish_qty").focus();
                    } else if (idline == 160 || idline == 202 || idline == 204) {
                        if ($("#total_cycle").val() == null || $("#total_cycle").val() == '') {
                            alert('Total Cycle harus diisi .')
                        } else {
                            $("#finish_qty").focus();
                        }
                    }
                }
            });

            $("#shafto_pjg").keypress(function(event) {
                var idline = $("#idline").val();
                var pjg = Number($("#shafto_pjg").val());
                var pdk = Number($("#shafto_pdk").val());
                if (event.keyCode === 13) {
                    if (idline == 140 || idline == 150) {
                        if ($("#shafto_pjg").val() == '') {
                            $("#shafto_pjg").val('0');
                            $("#shafto_pdk").val('');
                            $("#shafto_pdk").focus();
                            $("#total_cycle").val(pjg + pdk);
                            $("#total_cycle1").val(pjg + pdk);
                        } else {
                            $("#shafto_pdk").val('');
                            $("#shafto_pdk").focus();
                            $("#total_cycle").val(pjg + pdk);
                            $("#total_cycle1").val(pjg + pdk);
                        }
                    }
                }
            });

            $("#shafto_pjg").change(function(event) {
                var idline = $("#idline").val();
                var pjg = Number($("#shafto_pjg").val());
                var pdk = Number($("#shafto_pdk").val());

                if (idline == 140 || idline == 150) {
                    $("#total_cycle").val(pjg + pdk);
                    $("#total_cycle1").val(pjg + pdk);
                }
            });

            $("#shafto_pdk").keypress(function(event) {
                var idline = $("#idline").val();
                var pjg = Number($("#shafto_pjg").val());
                var pdk = Number($("#shafto_pdk").val());
                if (event.keyCode === 13) {
                    if (idline == 150 || idline == 140) {
                        if ($("#shafto_pdk").val() == '') {
                            $("#shafto_pdk").val('0');
                            $("#finish_qty").val('');
                            $("#finish_qty").focus();
                            $("#total_cycle").val(pjg + pdk);
                            $("#total_cycle1").val(pjg + pdk);
                        } else {
                            $("#finish_qty").val('');
                            $("#finish_qty").focus();
                            $("#total_cycle").val(pjg + pdk);
                            $("#total_cycle1").val(pjg + pdk);
                        }
                    }
                }
            });

            $("#shafto_pdk").change(function(event) {
                var idline = $("#idline").val();
                var pjg = $("#shafto_pjg").val();
                var pdk = $("#shafto_pdk").val();
                var pjg1 = Number(pjg);
                var pdk1 = Number(pdk);

                if (idline == 150 || idline == 140) {
                    $("#total_cycle").val(pjg1 + pdk1);
                    $("#total_cycle1").val(pjg1 + pdk1);
                }
            });

            $("#dressing").keypress(function(event) {
                if (event.keyCode === 13) {
                    if ($("#dressing").val() == '') {
                        $("#finish_qty").focus();
                        $("#dressing").val('0');
                    } else {
                        $("#finish_qty").focus();
                    }
                }
            });

            $("#incoming_qty").keypress(function() {
                if (event.keyCode === 13) {
                    if ($("#incoming_qty").val() > 0) {
                        $("#reject").focus();
                    } else {
                        alert("Please Input Incoming Qty .");
                        $("#incoming_qty").focus();
                    }
                }
            });

            $("#btn_simpan").click(function() {
                //e.preventDefault();
                var datas = $("#formbarcode").serializeArray();
                var nik = $(".select_op").val();
                var diff = $("#diffrjt").html();

                if ($("#sel").val() == '' || $("#sel").val() == null) {
                    alert('NIK belum dipilih !');
                } else if ($("#barcodeno").val() == '' || $("#barcodeno").val() == null) {
                    alert('Barcode belum terisi !');
                } else if ($("#finish_qty").val() == '' || $("#finish_qty").val() == null || $(
                        "#finish_qty1").val() == '' || $("#finish_qty1").val() == null) {
                    alert('Qty finish belum terisi !');
                } else if ($("#reject").val() == '' || $("#reject").val() == null || $(
                        "#reject1").val() ==
                    '' || $("#reject1").val() == null) {
                    alert('Qty Reject belum terisi !');
                } else if (Number(diff) != 0) {
                    alert('Qty NG belum diisi !');
                } else {
                    $("#btn_simpan").prop('disabled', true);
                    loadingon();
                    $.ajax({
                            type: "POST",
                            url: APP_URL + "/api/update_laporan_produksi",
                            headers: {
                                "token_req": key,
                            },
                            data: datas,
                            async: false,
                            dataType: "json",
                        })
                        .done(function(resp) {
                            loadingoff();
                            $("#btn_simpan").prop('disabled', false);
                            if (resp.success) {
                                // Toast.fire({
                                //     type: 'success',
                                //     title: resp.message,
                                // })

                                Swal.fire({
                                    title: '<div class="swal2-custom-title">' +
                                        resp.lotno + '</div>',
                                    html: '<div class="swal2-custom-content">' +
                                        resp.message + '</div>',
                                    showConfirmButton: false,
                                    type: 'success',
                                    timer: 2000,
                                    customClass: {
                                        popup: 'swal2-custom-dialog',
                                        title: 'swal2-custom-title',
                                        content: 'swal2-custom-content',
                                        icon: 'swal2-custom-icon'
                                    }
                                });

                                setTimeout(() => {
                                    window.location.reload();
                                    $("#sel").select2('focus');
                                }, 2000);
                            } else {
                                /*Toast.fire({
                                    type: 'error',
                                    title: resp.message,
                                })*/
                                alert(resp.message);
                                location.reload();
                            }
                        })
                        .fail(function() {
                            $("#error").html(
                                "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                            );
                        });
                }

            });


            $('#btn_tambah').click(function() {
                //var newrow = '<tr><td><input type ="hidden" name="part_code[]" value="' + data.kode_ng + '" />' + data.kode_ng + '</td><td><input type ="hidden" name="part_name[]" value="' + data.type_ng + ' ' + data.type_ng + '" />' + data.type_ng + '</td><td><input type ="hidden" name="part_qty[]" value="' + qty + '" />' + qty + '</td><td><button type="button" class="btnSelect btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td></tr>';
                var finish_qty = $("#finish_qty").val();
                var idline = $("#idline").val();
                var nilai = $("#ng1").val();
                var type = $("#type_ng").val();
                var ng = $("#ng_qty").val();
                var diff = $("#diffrjt").html();
                var reject = $("#reject").val();
                var cek = Number(reject) - (totng + Number(ng));
                var codes = [];
                var sama = false;
                codes = $("input[name='kode_ng[]']").map(function() {
                    return this.value;
                }).get();

                for (r in codes) {
                    if (codes[r] == nilai) {
                        sama = true;
                    }
                }




                if (!type) {
                    alert('Type NG Belum diisi .');
                } else if (!ng) {
                    alert('Harap masukkan Qty NG .')
                } else if (cek < 0) {
                    alert('Qty Reject Lebih .');
                    $('#ng_qty').val('');
                } else {

                    if (!sama) {
                        totng = totng + Number(ng);
                        sisang = sisang - Number(ng);
                        var baris_baru =
                            '<tr><td><input type ="hidden" name="kode_ng[]" value="' + nilai +
                            '" />' + nilai +
                            '</td><td><input type ="hidden" name="type_ng[]" value="' +
                            type + '" />' + type +
                            '</td><td><input type ="hidden" name="qty_ng[]" value="' + ng +
                            '" />' + ng +
                            '</td><td><button type="button" class="btnSelect btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td></tr>';
                        $('#tb_ng tbody').append(baris_baru);
                        $("#tb_use").append(baris_baru);
                        $('#type_ng').val('');
                        $('#ng_qty').val('');
                        $("#qty").html(totng);
                        $("#diffrjt").html(sisang);
                        $("#kode_ng").select2('focus');
                        if (sisang == 0) {
                            if (finish_qty == 0) {
                                if (idline == 320) {
                                    $("#next_proses").attr('disabled', false);
                                    $("#next_proses").select2(); // Select2 diinisialisasi

                                    let newOption = new Option("REJECT", 9959, true, true);
                                    $("#next_proses").append(newOption).trigger('change');

                                    $("#next_proses").on('select2:open', function() {
                                        $(this).select2(
                                            "close"
                                        ); // Menutup dropdown jika pengguna mencoba untuk mengubah pilihan
                                    });
                                    $("#btn_simpan").prop('disabled', false);
                                    $("#btn_simpan").focus();
                                } else {
                                    $("#next_proses").select2();
                                    $("#next_proses").prop('disabled', false).val(320).trigger(
                                        'change.select2');
                                    $("#next_proses").on('select2:open', function() {
                                        $(this).select2(
                                            "close"
                                        );
                                    });

                                    $("#btn_simpan").prop('disabled', false);
                                    $("#btn_simpan").focus();
                                }
                            } else if (idline == '320' || idline == '400') {
                                $("#next_proses").attr('disabled', false);
                                $("#next_proses").select2(); // Select2 diinisialisasi

                                let newOption = new Option("Finish Good", 9909, true, true);
                                $("#next_proses").append(newOption).trigger('change');

                                $("#next_proses").on('select2:open', function() {
                                    $(this).select2(
                                        "close"
                                    ); // Menutup dropdown jika pengguna mencoba untuk mengubah pilihan
                                });

                                $("#btn_simpan").prop('disabled', false);
                                $("#btn_simpan").focus();
                            } else {
                                // $("#btn_simpan").prop('disabled', false);
                                // $("#btn_simpan").focus();
                                // $("#jenis_wip").attr('disabled', false);
                                $("#next_proses").attr('disabled', false);
                                $("#next_proses").select2('focus');
                            }
                        }
                    } else {
                        alert("NG Sudah ada !")
                    }


                }




                //$("#tb_ng tfoot").append('<tr><th colspan="2">Total :</th><th>' + totng + '</th><th>' + + '</th><th>' + + '</th></tr>')
            });


            $('#tb_ng').on('click', '.btnSelect', function() {
                var currentRow = $(this).closest("tr");
                var col3 = currentRow.find("td:eq(2)").text(); // get current row 3rd TD
                var te = $("#diffrjt").html();
                sisang = Number(col3) + sisang;
                totng = totng - Number(col3);
                $("#diffrjt").html(sisang);
                currentRow.remove();
                $("#qty").html(totng);
                //$("#tb_ng tfoot").empty();
                //$("#tb_ng tfoot").append('<tr><th colspan="2">Total :</th><th>' + totng + '</th><th>' + + '</th><th>' + + '</th></tr>')

            });

            //$("#barcodeno").change(function () {
            $("#barcodeno").keypress(function() {
                if ($("#no_meja").val() == '' || $("#no_meja").val() == null) {
                    alert('Please Input your Machine Number .')
                } else if (event.keyCode === 13) {
                    var key = localStorage.getItem('produksi_token');
                    var dept = "{{ Session::get('dept') }}";
                    var title = $("#title").val();
                    var barcode_no = $("#barcodeno").val();
                    var kodeline = $("#idline").val();
                    var remark = $("#remark").val();
                    $("#barcodeno").prop('disabled', true);
                    loadingon();
                    // alert(title);
                    $.ajax({
                            type: "POST",
                            url: APP_URL + "/api/produksi/getbarcode",
                            headers: {
                                "token_req": key,
                            },
                            data: {
                                "barcode_no": barcode_no,
                                "title": title,
                                "kodeline": kodeline,
                                "remark": remark,
                            },

                            dataType: "json",
                        })
                        .done(function(resp) {
                            loadingoff();
                            $("#barcodeno").prop('disabled', false);
                            if (resp.message == 'Kensa' && kodeline == '320') {

                                $("#partno").val(resp.codekensa[0].i_item_cd);
                                $("#lotno").val(resp.codekensa[0].i_seiban);
                                $("#finish_qty").val(resp.codekensa[0].i_acp_qty);
                                $("#reject").val(resp.rjt);
                                $("#partno1").val(resp.codekensa[0].i_item_cd);
                                $("#lotno1").val(resp.codekensa[0].i_seiban);
                                $("#finish_qty1").val(resp.codekensa[0].i_acp_qty);
                                $("#reject1").val(resp.rjt);
                                $("#diffrjt").html(resp.rjt);
                                sisang = resp.rjt;
                                // finish_qty.disabled = true;
                                //reject.disabled = true;
                                //$("#kode_ng").select2('focus');
                                $("#incoming_qty").val(resp.qty_in);
                                $("#finish_qty").focus();
                            } else if (resp.message == 'Produksi' && kodeline != '320') {

                                $("#partno").val(resp.codekensa[0].i_item_cd);
                                $("#partno1").val(resp.codekensa[0].i_item_cd);
                                $("#lotno").val(resp.codekensa[0].i_seiban);
                                $("#lotno1").val(resp.codekensa[0].i_seiban);
                                $("#ukuran_haba").val(resp.b);
                                $("#finish_qty").val(resp.codekensa[0].i_acp_qty);
                                fg = Number(resp.fg);
                                $("#tanegata").val(resp.codekensa[0].mld_no);
                                $("#cast_no").val(resp.codekensa[0].chrg_no);
                                $("#omogata").val(resp.omo);
                                $("#incoming_qty").val(resp.qty_in);
                                if (kodeline == '40') {
                                    $("#ukuran_haba").focus();
                                } else if (kodeline == 20) {
                                    if (resp.codekensa[0].remark == 'FUTATSUWARI') {
                                        $("#lbl_futatsuwari").html(resp.codekensa[0]
                                            .remark);
                                        $("#finish_qty1").val(resp.codekensa[0].i_acp_qty);
                                        $("#finish_qty").val(resp.codekensa[0].i_acp_qty);
                                        $("#yama_17").val(resp.yama_17);
                                        $("#yama_15").val(resp.yama_15);
                                    } else {
                                        $("#finish_qty1").val(resp.codekensa[0].i_acp_qty);
                                        $("#finish_qty").val(resp.codekensa[0].i_acp_qty);
                                        $("#lbl_futatsuwari").html('');
                                        $("#yama_17").val(resp.yama_17);
                                        $("#yama_15").val(resp.yama_15);
                                    }
                                    $("#moulding_opr").select2('focus');

                                } else if (kodeline == 50) {
                                    $("#cycle").val('');
                                    $("#cycle").focus();
                                } else if (kodeline == 51 || kodeline == 52 || kodeline ==
                                    53 ||
                                    kodeline == 54 || kodeline == 55 || kodeline == 56) {
                                    $("#ukuran_haba_awal").focus();
                                } else if (kodeline == '60') {
                                    $("#ukuran_haba").val('');
                                    $("#ukuran_haba").focus();
                                } else if (kodeline == '70' || kodeline == '100' || kodeline == '110' ||
                                    kodeline ==
                                    '120' || kodeline == '131' || kodeline == '130' ||
                                    kodeline ==
                                    '180' || kodeline == '160' || kodeline ==
                                    '190' || kodeline == '202' || kodeline == '204'
                                ) {
                                    //$("#finish_qty").focus();
                                    $("#dandoriman").select2('focus');
                                } else if (kodeline == '140' || kodeline == '150') {
                                    $("#shafto_pjg").val('');
                                    $("#shafto_pjg").focus();
                                } else if (kodeline == '160') {
                                    $("#total_cycle").val('');
                                    $("#total_cycle").focus();
                                } else if (kodeline == '170') {
                                    $("#dandori").focus();
                                } else {
                                    $("#finish_qty").focus();
                                }

                            } else {
                                alert(resp.message);
                                $("#partno").val('');
                                $("#lotno").val('');
                                $("#finish_qty").val('');
                                $("#reject").val('');
                                $("#partno1").val('');
                                $("#lotno1").val('');
                                $("#finish_qty1").val('');
                                $("#incoming_qty").val('');
                                //$("#reject1").val('');
                            }

                            var select = $('#next_proses');
                            select.empty(); // Kosongkan opsi yang ada

                            // opsi default
                            select.append('<option value="">Pilih Next Proses...</option>');

                            // opsi yang belum terpakai
                            resp.next_proses.forEach(function(item) {
                                select.append('<option value="' + item.kode_line +
                                    '">' + item
                                    .nama_line + '</option>');
                            });

                        })
                        .fail(function() {
                            $("#error").html(
                                "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                            );
                        });
                }
            });

            $("#reject").change(function() {
                var d = $("#reject").val();
                d = Number(d);
                if (d - totng < 0) {
                    alert('Qty NG salah');
                    $("#reject").val(sisang + totng);
                } else {
                    sisang = d;
                    $("#diffrjt").html(sisang);
                }

            });

            $('#tb_hasil_produksi').on('click', '.btn-success', function() {
                var data = workresult.row($(this).parents('tr')).data();
                get_details_ng(data, key);
                $("#detaillist").html('Detail NG :  ' + data.part_no + '  -> ' + data.lot_no);
                $('#modal-NG').modal('show');
            });

            $("#btn-close-list").click(function() {
                $('#modal-NG').modal('hide');
            });


            $('#tb_hasil_produksi').on('click', '.btn-danger', function() {
                var data = workresult.row($(this).parents('tr')).data();
                $("#id_hasil_produksi").val(data.id_hasil_produksi);
                $("#lot_no").val(data.lot_no);
                var conf = confirm("Apakah Part No  " + data.part_no + "dengan No. " + data
                    .lot_no +
                    " akan dihapus?");
                if (conf) {
                    $.ajax({
                            type: "POST",
                            url: APP_URL + "/api/hapus/hasilproduksi",
                            headers: {
                                "token_req": key
                            },
                            data: {
                                "id_hasil_produksi": data.id_hasil_produksi,
                                'line_proses': data.line_proses
                            },
                            dataType: "json",
                        })
                        .done(function(resp) {
                            if (resp.success) {
                                alert(resp.message);
                                // window.location = window.location;
                                workresult.ajax.reload();
                            } else {
                                alert(resp.message);
                            }
                        })
                        .fail(function() {
                            $("#error").html(
                                "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                            );

                        });
                }
            });

            $("#btn-deletehasil").click(function() {
                $('#modal_delete').modal('show');
            });

            $("#frm_delete_hasil").submit(function(e) {
                e.preventDefault()
                // var datas = $(this).serialize();
                var kode_line = $("#kode_line").val();
                var shift = $("#shift").val();
                var tgl = $("#tgl_proses").val();
                var barcode_no = $("#d_barcode").val();
                $.ajax({
                        type: "POST",
                        url: APP_URL + "/api/hapus/hasilproduksi",
                        headers: {
                            "token_req": key
                        },
                        // data: {
                        //     "id_hasil_produksi": data.id_hasil_produksi,
                        //     'line_proses': data.line_proses
                        // },
                        data: {
                            'line_proses': kode_line,
                            'shift': shift,
                            'tgl': tgl,
                            'barcode_no': barcode_no
                        },
                        dataType: "json",
                    })
                    .done(function(resp) {
                        if (resp.success) {
                            alert(resp.message);
                            window.location = window.location;
                            // workresult.ajax.reload();
                        } else {
                            alert(resp.message);
                        }
                    })
                    .fail(function() {
                        $("#error").html(
                            "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                        );

                    });
            })


            $("#btn-rekapoperator").click(function() {
                $('#modal-rekapoperator').modal('show');
            });

            $("#btn-ceklot").click(function() {
                //$('#modal-ceklot-OK').modal('show');
                get_ceklot();
            });

            $("#btn-reportoperator").click(function() {
                var kode_line = $("#kode_line").val();
                var shift = $("#shift").val();
                var tgl = $("#tgl_proses").val();

                if (kode_line == '20') { //MOULDING
                    window.location.href = APP_URL + "/laporan/lap_moulding/" + tgl + "/" +
                        kode_line +
                        "/" + shift;
                } else if (kode_line == '30' || kode_line == '40') { //Shot Kensa + Naigaiken
                    window.location.href = APP_URL + "/laporan/lap_shotkensa/" + tgl + "/" +
                        kode_line +
                        "/" + shift;
                } else if (kode_line == '50') { //FUTATSUWARI
                    window.location.href = APP_URL + "/laporan/lap_futatsuwari/" + tgl + "/" +
                        kode_line +
                        "/" +
                        shift;
                } else if (kode_line == '60' || kode_line == '51' || kode_line ==
                    '52' || kode_line ==
                    '53' || kode_line ==
                    '54' || kode_line ==
                    '55' || kode_line ==
                    '56') { //Sozaikensa + DDG + Besley + Besley 1 - 4
                    window.location.href = APP_URL + "/laporan/lap_sozai/" + tgl + "/" +
                        kode_line + "/" +
                        shift;
                } else if (kode_line == '70') { //KAMU
                    //window.open(APP_URL + "/laporan/lap_kamu", '_self');
                    window.location.href = APP_URL + "/laporan/lap_kamu/" + tgl + "/" +
                        kode_line + "/" +
                        shift;
                } else if (kode_line == '100') { //KAV
                    window.location.href = APP_URL + "/laporan/lap_kav/" + tgl + "/" +
                        kode_line + "/" +
                        shift;
                } else if (kode_line == '131') { //KAMU
                    window.location.href = APP_URL + "/laporan/lap_gsm/" + tgl + "/" +
                        kode_line + "/" +
                        shift;
                } else if (kode_line == '90' || kode_line == '110' || kode_line ==
                    '120' || kode_line == '130' || kode_line == '180' || kode_line == '202' ||
                    kode_line ==
                    '204' || kode_line == '210' || kode_line == '222' || kode_line == '224' ||
                    kode_line ==
                    '150' || kode_line == '160' || kode_line ==
                    '172' || kode_line == '174' || kode_line == '230' || kode_line == '292' ||
                    kode_line ==
                    '294' || kode_line == '140' || kode_line ==
                    '165' || kode_line ==
                    '191'
                ) { //KOKUIN || KAV || ODM || OBB || K-LP || C-LP || Atari kensa || MEKKI || NAISHI || DV-9 || UCHIKATTO || F-6 || setting mekki || KAKUCHOU || MADOBARITORI
                    window.location.href = APP_URL + "/laporan/lap_atari/" + tgl + "/" +
                        kode_line + "/" +
                        shift;
                } else if (kode_line == '300' || kode_line == '310' || kode_line == '320' ||
                    kode_line ==
                    '400') { //Reezaa maaku || Nukitori Kensa || Gaikan Kensa || Pengemasan
                    window.location.href = APP_URL + "/laporan/lap_gaikan/" + tgl + "/" +
                        kode_line + "/" +
                        shift;
                } else if (kode_line == 240 || kode_line == 250 || kode_line == 260 ||
                    kode_line == 270 ||
                    kode_line == 280 || kode_line == 282 || kode_line == 284) { //NAIMENTORI
                    window.location.href = APP_URL + "/laporan/lap_naimen/" + tgl + "/" +
                        kode_line + "/" +
                        shift;
                } else {
                    window.location.href = APP_URL + "/undermaintenance";
                }
            });

            $('#modal-rekapoperator').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
            })

            $("#btn-process").click(function() {
                var tgl1 = $("#tgl1").val();
                var tgl2 = $("#tgl2").val();
                var opr = $("#opr").val();
                get_detail_listrekap();
                $('#modal-listrekap').modal('show');
                $(this).find('form').trigger('reset');
            });

            // $("#keluar").click(function () {
            //     location.reload(true);
            // });


            $("#btn-shikakaricamu").click(function() {
                var kode_line = $("#kode_line").val();
                var shift = $("#shift").val();
                var tgl = $("#tgl_proses").val();
                get_detail_shikakaricamu();
                $('#modal-shikakaricamu').modal('show');
            });

            $("#jenis_wip").attr('disabled', true);
            $("#next_proses").attr('disabled', true);

            $("#jenis_wip").on('select2:select', function() {
                var jw = $("#jenis_wip").val();

                if (jw == null || jw == '') {
                    $("#next_proses").val('').trigger("change");
                    $("#next_proses").attr('disabled', true);
                    $("#btn_simpan").prop('disabled', true);
                    alert("Pilih jenis WIP .");
                } else if (jw == 'Partial') {
                    $("#next_proses").val('').trigger("change");
                    $("#next_proses").attr('disabled', true);
                    $("#btn_simpan").prop('disabled', false);
                    $("#btn_simpan").focus();
                } else {
                    $("#next_proses").select2('focus');
                    $("#next_proses").attr('disabled', false);
                    $("#btn_simpan").prop('disabled', true);
                }
            });

            $("#next_proses").on('select2:select', function() {
                var np = $("#next_proses").val();

                if (np == null || np == '') {
                    alert("Pilih Next Proses .");
                } else {
                    $("#btn_simpan").prop('disabled', false);
                    $("#btn_simpan").focus();
                }
            });


        });

        function get_detail_listrekap() {
            var key = localStorage.getItem('produksi_token');
            var listrekap1 = $('#tb_listrekap').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/api/produksi/listrekap',
                    type: "POST",
                    headers: {
                        "token_req": key
                    },
                    data: function(d) {
                        d.tgl1 = $("#tgl1").val();
                        d.tgl2 = $("#tgl2").val();
                        d.opr = $("#opr").val();
                    }
                },
                columnDefs: [{

                        targets: [1],
                        data: 'total',
                    },
                    {
                        targets: [2],
                        data: 'totalng',
                    },
                ],
                columns: [{
                    data: 'operator',
                    name: 'operator'
                }, ],
            });
        }

        function get_ceklot() {
            $('#loadingscreen1').show();
            var key = localStorage.getItem('produksi_token');
            //var $tgl = date('Y-m-d');
            $.ajax({
                    url: APP_URL + "/api/produksi/ceklot",
                    method: "POST",
                    //data: { "tgl": tgl },
                    dataType: "json",
                    headers: {
                        "token_req": key
                    },
                })

                .done(function(resp) {
                    var label = [];
                    var value = [];
                    var value2 = [];
                    var part = 0;
                    var lot = 0;

                    $('#loadingscreen1').hide();
                    if (resp.success) {
                        $('#modal-ceklot').modal('show');

                        $("#tb_ceklot tbody").empty();
                        $("#tb_ceklot tfoot").empty();

                        for (var i in resp.ceklot) {

                            var newrow = '<tr><td><name="part_no[]" value="/>' + resp.ceklot[i].part_no +
                                ' </td><td><name="lot_no[]" value="/>' + resp.ceklot[i].lot_no +
                                ' </td><td><name="status[]" value="/>' + "Not Completed" + ' </td></tr>';
                            $('#tb_ceklot tbody').append(newrow);
                        }
                    } else {
                        //alert("Tidak ada data .");
                        $('#modal-ceklot-OK').modal('show');
                    }
                })
                .fail(function() {
                    $("#error").html(
                        "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                    );

                });
        }

        function get_detail_shikakaricamu() {
            var key = localStorage.getItem('produksi_token');
            // var kode_line = $("#kode_line").val();
            var shikakaricamu = $('#tb_shikakaricamu').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/api/produksi/shikakaricamu',
                    type: "POST",
                    headers: {
                        "token_req": key
                    },
                    data: function(d) {
                        d.proses2 = $("#kode_line").val();
                        // d.kode_gsm = $("#kode_gsm").val();
                    },
                    dataSrc: function(json) {
                        $('#jml').text(json.jmlExpres);
                        return json.data; // Return the data for DataTables to use
                    }
                },
                columnDefs: [{

                    //targets: [1],
                    //data: 'total',
                }, ],
                columns: [{
                        data: 'part_no',
                        name: 'part_no'
                    },
                    {
                        data: 'lot_no',
                        name: 'lot_no'
                    },
                    {
                        data: 'qty_in',
                        name: 'qty_in'
                    },
                    {
                        data: 'tgl_in',
                        name: 'tgl_in'
                    },
                    {
                        data: 'tag',
                        name: 'tag'
                    },
                    {
                        data: 'warna_tag',
                        name: 'warna_tag'
                    },
                ],

                fnRowCallback: function(nRow, data, iDisplayIndex, iDisplayIndexFull) {
                    if (data.tag == "EXPRES") {
                        var warnaTag = data.warna_tag.toLowerCase();
                        if (warnaTag == 'merah') {
                            $('td', nRow).css('background-color', 'red');
                            $('td', nRow).css('color', 'white');
                        } else if (warnaTag == 'biru') {
                            $('td', nRow).css('background-color', 'blue');
                            $('td', nRow).css('color', 'white');
                        } else if (warnaTag == 'hijau') {
                            $('td', nRow).css('background-color', 'green');
                            $('td', nRow).css('color', 'white');
                        } else if (warnaTag == 'kuning') {
                            $('td', nRow).css('background-color', 'yellow');
                            $('td', nRow).css('color', 'black');
                        } else if (warnaTag == 'ungu') {
                            $('td', nRow).css('background-color', 'purple');
                            $('td', nRow).css('color', 'white');
                        } else if (warnaTag == 'pink') {
                            $('td', nRow).css('background-color', 'pink');
                            $('td', nRow).css('color', 'black');
                        }
                    }
                },

                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    Totalqty = api
                        .column(2, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    $(api.column(2).footer()).html(
                        Totalqty.toLocaleString("en-US")
                    );

                }
            });


            $("#btn_reload_kg").click(function() {
                shikakaricamu.ajax.reload();
            });
        }

        function get_details_ng(data, key) {
            var key = localStorage.getItem('produksi_token');

            var detail_ng = $('#t_detail_NG').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                searching: false,
                ordering: false,
                ajax: {
                    url: APP_URL + '/api/detail_ng',
                    type: "POST",
                    headers: {
                        "token_req": key
                    },
                    data: {
                        "id_hasil_produksi": data
                    },
                },
                columns: [{
                        data: 'ng_code',
                        name: 'ng_code'
                    },
                    {
                        data: 'ng_type',
                        name: 'ng_type'
                    },
                    {
                        data: 'ng_qty',
                        name: 'ng_qty'
                    },

                ]
            });
        }

        function get_hasil_shift(tgl, shift, line, key) {
            $("#nonshift").html('Loading...');
            $("#shift1").html('Loading...');
            $("#shift2").html('Loading...');
            $("#shift3").html('Loading...');
            $.ajax({
                    url: APP_URL + "/api/produksi/getHasilShift",
                    method: "POST",
                    headers: {
                        "token_req": key
                    },
                    data: {
                        'tgl': tgl,
                        'line': line,
                        'shift': shift
                    },
                    dataType: "json",
                })

                .done(function(resp) {
                    if (resp.success) {

                        $("#totalshift").html(resp.totalshift.toLocaleString());
                        // $("#shiftnow").html(resp.shiftnow.toLocaleString('id-ID'));
                        $("#nonshift").html(resp.nonshift.toLocaleString());
                        $("#shift1").html(resp.shift1.toLocaleString());
                        $("#shift2").html(resp.shift2.toLocaleString());
                        $("#shift3").html(resp.shift3.toLocaleString());
                        $("#targetharian").html(resp.targetharian.toLocaleString());
                        $("#diffharian").html(resp.diffharian.toLocaleString());

                        // Mengubah warna teks diffharian berdasarkan nilainya
                        var diffharian = parseFloat(resp.diffharian);
                        if (diffharian < 0) {
                            $("#diffharian").css("color", "red");
                        } else {
                            $("#diffharian").css("color", "green");
                        }

                        $("#pas-nonshift").html(resp.p_nonshift.toLocaleString());
                        $("#pas-shift1").html(resp.p_shift1.toLocaleString());
                        $("#pas-shift2").html(resp.p_shift2.toLocaleString());
                        $("#pas-shift3").html(resp.p_shift3.toLocaleString());
                        $("#pas-totalshift").html(resp.p_totalshift.toLocaleString());
                    }
                })
                .fail(function() {
                    $("#error").html(
                        "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                    );

                });
        }

        function loadingon() {
            document.getElementById("loadingscreen").style.display = "block";
        }

        function loadingoff() {
            document.getElementById("loadingscreen").style.display = "none";
        }
    </script>
@endsection
