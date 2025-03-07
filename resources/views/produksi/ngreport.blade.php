@section('plugins.Chartjs', true)
@extends('layout.app')
{{-- Customize layout sections --}}


@section('content_header_title', 'NG Report')


{{-- Content body: main page content --}}

@section('content_body')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-color-list">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Success Rate by Type</h3>
                    </div>

                </div>
                <div class="card-body">


                    <div class="position-relative mb-4">

                        <canvas id="type_mn" height="400" style="display: block; width: 723px; height: 400px;"
                            width="723" class="chartjs-render-monitor"></canvas>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-color-list">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Success Rate by Material</h3>

                    </div>

                </div>
                <div class="card-body">


                    <div class="position-relative mb-4">

                        <canvas id="material_mn" height="400" style="display: block; width: 723px; height: 400px;"
                            width="723" class="chartjs-render-monitor"></canvas>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">

                    <div class="card card-secondary">
                        <div class="card-header">
                            <div class="col-6">
                                <h3 class="card-title">Success Rate</h3>
                            </div>
                        </div>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="tanggalawal">Tanggal Awal</label>
                            <input type="date" class="form-control" value="{{ date('Y-m') . '-01' }}" name="tanggalawal"
                                id="tanggalawal" placeholder="Tanggal ditemukan" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggalakhir">Tanggal Akhir</label>
                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}" name="tanggalakhir"
                                id="tanggalakhir" placeholder="Tanggal ditemukan" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="prosesawal">Proses Awal</label>
                            <select name="v_name" id="prosesawal" class="form-control" style="width: 100%;" required>
                                <option value="10">Furnace</option>
                                {{--
                                @foreach ($location as $value => $i)
                                    <option value="{{ $i->line_proses }}"
                                        @if ($i->line_proses == '10') {{ 'selected' }} @endif>{{ $i->nama_line }}
                                    </option>
                                @endforeach
                                --}}
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="prosesakhir">Proses Akhir</label>
                            <select name="v_name" id="prosesakhir" class="form-control" style="width: 100%;" required>
                                {{--
                                @foreach ($location as $i)
                                    <option value="{{ $i->line_proses }}"
                                        @if ($i->line_proses == '320') {{ 'selected' }} @endif>{{ $i->nama_line }}
                                    </option>
                                @endforeach
                                --}}
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success" id="btn-letsgo">Run</button>

                </div>
            </div>



        </div>


        <div class="col-md-8">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="far fa-heart"></i></span>

                <div class="info-box-content">

                    <dl class="row align-items-center">
                        <dt class="col-md-4">
                            <h4>Success Rate All</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <h3 id="successrate">0 % </h3>

                                </div>
                                <div class="col-md-0">
                                    <h3> / </h3>
                                </div>
                                <div class="col-md-4">
                                    <h3 style="text-align: right" id="t_all"> 0 %</h3>
                                </div>
                            </div>
                        </dt>
                        <div class="col-md-8">


                            <div class="info-box bg-warning">

                                <span class="info-box-icon"><i class="fas fa-tag"></i></span>

                                <div class="info-box-content col-md-12">
                                    <dl class="row">
                                        <dt class="col-sm-4">
                                            <h4><u> Type</u></h4>
                                        </dt>
                                        <dd class="col-sm-3">
                                            <h4 style="text-align: center"><u> Actual</u></h4>
                                        </dd>
                                        <dd class="col-sm-0">
                                            <h3></h3>
                                        </dd>
                                        <dd class="col-sm-3">
                                            <h4 style="text-align: center"><u> Target</u></h4>
                                        </dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-sm-4">
                                            <a href="" class="srate" id="comp-f">
                                                <h4>Comp F</h4>
                                            </a>
                                        </dt>
                                        <dd class="col-sm-3">
                                            <h3 id="compf" style="text-align: center">0 %</h3>
                                        </dd>
                                        <dd class="col-sm-0">
                                            <h3>/</h3>
                                        </dd>
                                        <dd class="col-sm-3">
                                            <h3 id="t_compf" style="text-align: center">0 %</h3>
                                        </dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-sm-4">
                                            <a href="" class="srate" id="comp-cr">
                                                <h4>Comp Cr</h4>
                                            </a>
                                        </dt>
                                        <dd class="col-sm-3">
                                            <h3 id="compcr" style="text-align: center">0 %</h3>
                                        </dd>
                                        <dd class="col-sm-0">
                                            <h3>/</h3>
                                        </dd>
                                        <dd class="col-sm-3">
                                            <h3 id="t_compcr" style="text-align: center">0 %</h3>
                                        </dd>
                                    </dl>
                                </div>



                            </div>

                            <div class="info-box" style="background-color: beige">
                                <span class="info-box-icon" style="color: black"><i class="fas fa-tag"></i></span>

                                <div class="info-box-content">
                                    <dl class="row">
                                        <dt class="col-sm-4">

                                            <a href="" class="srate" id="oil-f">
                                                <h4>OIL F</h4>
                                            </a>
                                        </dt>
                                        <dd class="col-sm-3">
                                            <h3 id="oilf" style="color: black; text-align: center">0 %</h3>
                                        </dd>
                                        <dd class="col-sm-0">
                                            <h3 style="color: black">/</h3>
                                        </dd>
                                        <dd class="col-sm-3">
                                            <h3 id="t_oilf" style="text-align: center; color:black">0 %</h3>
                                        </dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-sm-4">
                                            <a href="" class="srate" id="oil-cr">
                                                <h4>OIL Cr</h4>
                                            </a>
                                        </dt>
                                        <dd class="col-sm-3">
                                            <h3 id="oilcr" style="color: black; text-align: center">0 %</h3>
                                        </dd>
                                        <dd class="col-sm-0">
                                            <h3 style="color: black">/</h3>
                                        </dd>
                                        <dd class="col-sm-3">
                                            <h3 id="t_oilcr" style="text-align: center; color:black">0 %</h3>
                                        </dd>
                                    </dl>
                                </div>
                            </div>


                        </div>
                    </dl>


                </div>
            </div>
        </div>



    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Monitoring NG</h3>
                        <button class="btn btn-outline-info" id="btn_conf"><i class="fa fa-cog"></i></button>
                    </div>

                </div>
                <div class="card-body">


                    <div class="position-relative mb-4">

                        <canvas id="monitoring_ng" height="400" style="display: block; width: 723px; height: 400px;"
                            width="723" class="chartjs-render-monitor"></canvas>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">TOP 10 NG</h3>
                    <input type="hidden" name="total_ng" id="total_ng">
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" id="tb_ng">
                        <thead>
                            <tr>
                                <th style="width: 40px">No. </th>
                                <th style="width: 130px">Jenis NG</th>

                                <th style="width: 40px">Qty</th>
                                <th style="width: 60px">% total Prod</th>
                                {{-- <th style="width: 60px">% total NG</th> --}}
                            </tr>
                        </thead>
                        <tbody id="tbodyNG">

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">TOP 10 Successrate ter rendah</h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover text-nowrap" id="tb_peritem">
                        <thead>
                            <tr>

                                <th>No.</th>
                                <th>Part No</th>
                                <th>Lot No</th>
                                <th>Start Qty</th>
                                <th>Finish Qty</th>
                                <th>Success Rate</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyItem">

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>


        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 id="judul_grafik" class="card-title">Grafik NG</h3>

                    </div>

                </div>
                <div class="card-body">


                    <div class="position-relative mb-4">

                        <canvas id="ng-chart" height="400" style="display: block; width: 723px; height: 800px;"
                            width="723" class="chartjs-render-monitor"></canvas>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Success Rate Gaikan Kensa</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">

                        <table class="table table-bordered" id="tb_kensa">
                            <thead>
                                <tr>

                                    <th>Process</th>

                                    <th style="text-align:center;">Qty</th>
                                    <th>Lot</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Production</td>
                                    <td id="j_pro" style="text-align:center;">0</td>
                                    <td rowspan="4" style="vertical-align : middle;text-align:center;" id="j_lot">
                                        0</td>
                                </tr>
                                <tr>
                                    <td>CAMU Qty</td>
                                    <td id="j_camu" style="text-align:center;">0</td>

                                </tr>
                                <tr>
                                    <td>Incoming Kensa</td>
                                    <td id="j_inc" style="text-align:center;">0</td>

                                </tr>
                                <tr>
                                    <td>Finish Kensa</td>
                                    <td id="j_finish" style="text-align:center;">0</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">

                        <table class="table table-bordered">
                            <thead>
                                <tr>

                                    <th>Process</th>

                                    <th>Success Rate</th>
                                    <th>NG</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Zoukei - CAMU</td>
                                    <td id="z-c">0</td>
                                    <td id="n_z-c">0</td>
                                </tr>
                                <tr>
                                    <td>CAMU - Kensa</td>
                                    <td id="c-k">0</td>
                                    <td id="n_c-k">0</td>
                                </tr>
                                <tr>
                                    <td>Kensa - FG</td>
                                    <td id="k-f">0</td>
                                    <td id="n_k-f">0</td>
                                </tr>
                                <tr>
                                    <td>CAMU - FG</td>
                                    <td id="c-f">0</td>
                                    <td id="n_c-f">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="mdgroup" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="GroupTitle">Type NG list</h5>
                </div>
                <div class="modal-body">

                    <div class="position-relative mb-4">

                        <canvas id="chart_group" height="400" style="display: block; width: 723px; height: 800px;"
                            width="723" class="chartjs-render-monitor"></canvas>
                    </div>

                    {{-- <div class="row"> --}}
                    <div id="accordion">

                        <div class="card">
                            <div id="collapseOne" class="panel-collapse in collapse" style="">

                                <div class="card-body table-responsive p-0">
                                    <table id="tb_item" class="table table-bordered table-striped text-nowrap">
                                        <thead>
                                            <th>id</th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1">Part Number<span class="dataTables_sort_icon"></span></th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1">Lot No<span class="dataTables_sort_icon"></span></th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1">Proses<span class="dataTables_sort_icon"></span></th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1">Operator<span class="dataTables_sort_icon"></span></th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1">No Mesin<span class="dataTables_sort_icon"></span></th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1">Tgl Proses<span class="dataTables_sort_icon"></span></th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1">NG Qty<span class="dataTables_sort_icon"></span></th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1">Persentase<span class="dataTables_sort_icon"></span></th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1">NG Name<span class="dataTables_sort_icon"></span></th>

                                        </thead>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- </div> --}}

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_close">Close</button>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal_config" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Setting Parameter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="frm_setting">
                        <div class="form-row">
                            <div class="form-group col-md-2"><label>Line :</label></div>
                            <div class="form-group col-md-6">
                                <select name="kode_line" id="kode_line" class="form-control"
                                    data-placeholder="Pilih Line" style="width: 100%;">
                                    <option value="">---Line---</option>
                                    {{--
                                    @foreach ($location as $value => $i)
                                        <option value="{{ $i->line_proses }}">{{ $i->nama_line }}</option>
                                    @endforeach
                                    --}}
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2"><label>Periode :</label></div>
                            <div class="form-group col-md-6">
                                <select name="periode" id="periode" class="form-control"
                                    data-placeholder="Pilih periode" style="width: 100%;">
                                    <option value="">---periode---</option>
                                    <option value="dayly">Dayly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2"><label>Jenis NG :</label></div>
                            <div class="form-group col-md-6">
                                <select name="" id="jenis_ng" class="form-control select2"
                                    data-placeholder="Pilih NG" style="width: 100%;">
                                    <option value="">---pilih NG---</option>
                                    {{--
                                    @foreach ($jenis_ng as $o)
                                        <option value="{{ $o->kode_ng }}">{{ $o->type_ng }}</option>
                                    @endforeach
                                    --}}
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <table class="table table-bordered" id="tb_jenis">
                                <thead>
                                    <tr>
                                        <th>Jenis NG</th>
                                        <th style="width: 20px">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail NG -->
    <div class="modal fade" id="modal_detail_ng" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="card-body">
                    <input type="hidden" style="text-align: right" class="form-control float-right" name="lbl_ngType"
                        id="lbl_ngType">
                    <label for="" class="form-control float-right"></label>
                    <div class="card-body table-responsive p-0">
                        <table id="tb_top10NG" class="table table-bordered table-striped text-nowrap">
                            <thead>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Tgl Proses<span class="dataTables_sort_icon"></span></th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Part Number<span class="dataTables_sort_icon"></span></th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Lot No<span class="dataTables_sort_icon"></span></th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Proses<span class="dataTables_sort_icon"></span></th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Operator<span class="dataTables_sort_icon"></span></th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">No Mesin<span class="dataTables_sort_icon"></span></th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">NG Name<span class="dataTables_sort_icon"></span></th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">NG Qty<span class="dataTables_sort_icon"></span></th>

                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-flat" id="btn_excelTop10NG">Excel</button>
                    <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingscreen" class="eloading">
        <div id="text" class="spiner"><i class="fa fa-spinner fa-spin"></i></div>
    </div>
@stop

{{-- Push extra CSS --}}

@push('css')
    <style>
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

        /* Custom Sort Icons */
        .dataTables_wrapper .dataTables_sort_icon {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-left: 5px;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .dataTables_wrapper .sorting_asc .dataTables_sort_icon {
            background-image: url('{{ asset('/assets/plugins/images/sort_asc.png') }}');
        }

        .dataTables_wrapper .sorting_desc .dataTables_sort_icon {
            background-image: url('{{ asset('/assets/plugins/images/sort_desc.png') }}');
        }
    </style>
@endpush

{{-- Push extra CSS --}}
{{-- @push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush --}}

{{-- Push extra scripts --}}
@push('js')
    {{-- <script src="{{ asset('js/selectline.js') }}"></script> --}}
    <script src="{{ asset('js/ng_report.js') }}"></script>
@endpush
