@section('plugins.Select2', true)
@section('plugins.Chartjs', true)
@extends('layout.app')

{{-- Customize layout sections --}}

@section('content_header_title', 'Dashboard')

{{-- Content body: main page content --}}

@section('content_body')
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="saldo">0</h3>

                    <p>Saldo</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <a href="{{ url('undermaintenance') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="pemasukan_bulan_ini">0</h3>

                    <p>Pemasukan bulan ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-luggage-cart"></i>
                </div>
                <a href="{{ url('undermaintenance') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <!-- <h3>Detail</h3> -->
                    <h3 id="pengeluaran_bulan_ini">0</h3>

                    <p>Pengeluaran bulan ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-percent"></i>
                </div>
                <a href="{{ url('undermaintenance') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 id="warga_terdaftar"></h3>

                    <p>Total Warga terdaftar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ url('datas/list_warga') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="col-md-12" style="margin-top: -15px">
        <marquee id="marqueeExpres" behavior="scroll" direction="left">
            <b>
                RT 17 PAS
            </b>
        </marquee>
    </div>
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card card-info">

                <div class="card-header">
                    <h5 class="card-title" style="font-size: 22px">Laporan Pencapaian Produksi, <b style="color:yellow">
                            {{ date('M-Y') }}</b>
                    </h5>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" id="col1"
                            style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size:18px"><u>
                                DataTable </u>
                        </button>
                        <button type="button" class="btn btn-tool" id="col2"
                            style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size:18px"><u>
                                Grafik </u>
                        </button>
                    </div>
                </div>

                <div id="collapsePencapaianTarget" class="panel-collapse in collapse" style="">

                    <div id="loadingLPP"
                        style="display: none; text-align: center; margin-top:3%; margin-bottom:3%; font-weight:bold; font-size:30px; font-family:'Courier New', Courier, monospace">
                        <i class="fas fa-spinner fa-spin"></i> Proses Data...
                    </div>

                    <div class="card-body table-responsive p-0" style="height: 500px; position: relative;" id="lpp">
                        <div class="div1">

                            <table class="table table-bordered text-nowrap transparent-table">
                                <thead>
                                    <tr id="table-header-row">
                                        <th>Line Proses</th>
                                        <th>
                                            Daily
                                            <select value="{{ date('m') }}" id="daily_target_select"
                                                onchange="updateDailyTarget()">
                                                <!-- The options will be populated dynamically via JavaScript -->
                                            </select>
                                        </th>
                                        <!-- Days of the current month will be populated dynamically here -->
                                    </tr>
                                </thead>
                                <tbody id="data-table-body">
                                    <!-- Table rows will be populated dynamically via JavaScript -->
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <!-- collapse 2 -->
                <div id="collapseHasilProduksi" class="panel-collapse in collapse" style="">
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="card-header">
                                <div class="row">
                                    <input type="hidden" class="form-control col-md-2" id="tgl1"
                                        value="{{ date('Y-m') . '-01' }}">
                                    <input type="hidden" class="form-control col-md-2" id="tgl2"
                                        value="{{ date('Y-m-d') }}">
                                    <div class="col col-md-1 text-center"><label> Line</label></div>


                                    <div class="col-md-2">
                                        <select name="selectline" id="selectline" class="form-control select2">


                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="chart_hasilProduksi"
                            style="min-height: 250px; height: 331px; max-height: 950px; max-width: 100%; display: block; width: 560px;"
                            width="560" height="331" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">

                <div class="card-header">
                    <h3 class="card-title"><b> Grafik Shikakari </b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>


                <div class="card-body">
                    <div class="input-group">
                        <input type="hidden" class="form-control col-sm-2" id="tglAwal" value="{{ date('Y-m-d') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary float-right" id="btn_refresh"><i
                                    class="fa fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>

                    <div id="loadingMessage"
                        style="display: none; text-align: center; margin-top:3%; margin-bottom:3%; font-weight:bold; font-size:30px; font-family:'Courier New', Courier, monospace">
                        <i class="fas fa-spinner fa-spin"></i> Proses Data...
                    </div>
                    <canvas id="chart_shikakari"
                        style="min-height: 250px; height: 331px; max-height: 950px; max-width: 100%; display: block; width: 560px;"
                        width="560" height="331" class="chartjs-render-monitor"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h5 class="card-title">Annual Recap SS And HHKY</h5>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-6">
                            <p class="text-center">
                                <strong>Level of risk, {{ date('Y') }}</strong>
                                <input type="hidden" id="tahun" name="tahun" value="{{ date('Y') }}">
                            </p>
                            <div class="col col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="chartpie"
                                            style="min-height: 250px; height: 250px; max-height: 950px; max-width: 100%; display: block; width: 950px;"
                                            width="950" height="650" class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row" id="list_hhky">
                                    <div class="col-sm-3 col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage text-success"><i
                                                    class="fas fa-caret-up"></i>
                                                0%</span>
                                            <h5 class="description-header" id="hh_in">0</h5>
                                            <span class="description-text">Hiyarihatto IN</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage text-warning"><i
                                                    class="fas fa-caret-left"></i>
                                                0%</span>
                                            <h5 class="description-header" id="hh_close">0</h5>
                                            <span class="description-text">Hiyarihatto Close</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage text-success"><i
                                                    class="fas fa-caret-up"></i>
                                                0%</span>
                                            <h5 class="description-header" id="ky_in">0</h5>
                                            <span class="description-text">Kiken Yochi IN</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="description-block">
                                            <span class="description-percentage text-danger"><i
                                                    class="fas fa-caret-down"></i>
                                                0%</span>
                                            <h5 class="description-header" id="ky_close">0</h5>
                                            <span class="description-text">Kiken Yochi Close</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col col-md-6">
                            <p class="text-center">
                                <strong>SS Goal Completion {{ date('Y') }}</strong>
                            </p>
                                                       <!-- /.progress-group -->
                            <!-- /.progress-group -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <!-- BAR CHART -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Jam Kerusakan <i id="periode"></i></h3>

                </div>
                <div class="card-body">
                    <div>
                        <canvas id="chartjam"></canvas>
                    </div>
                </div>
                <div class="card-footer">
                    <label for="" id="totaljam"></label>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
        <div class="col-md-4">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Daftar Mesin Stop <i id="periode"></i></h3>

                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="tb_mesinoff">
                        <thead>
                            <tr>
                                <th>Nomer Induk Mesin</th>
                                <th>Nama Mesin</th>
                                <th>No Mesin</th>
                                <th>Tanggal Rusak</th>
                                <th>Masalah</th>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan ditambahkan di sini oleh JavaScript -->
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">List Schedule Pemeriksaan</h3>
                </div>
                <div class="card-body">
                    <div class="row" style="margin-top: -1%">
                        <div class="form-group col-md-4">
                            <strong for="beginning">Beginning</strong>
                            <input type="date" class="form-control" id="tgl_awal"
                                value="{{ date('Y-m') . '-01' }}">
                        </div>
                        <div class="form-group col-md-4">
                            <strong>Ending</strong>
                            <input type="date" class="form-control" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <strong> Status : </strong>
                            <select name="status_sch" id="status_sch" class="form-control" value="open">
                                <option value="open">Open</option>
                                <option value="process">Process</option>
                                <option value="pending">Pending</option>
                                <option value="selesai">Selesai</option>
                                <option value="complete">Complete</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="reject">Reject</option>
                                <option value="TempReject">TempReject</option>
                                <option value="All">All</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <strong> Reload </strong>
                            <br>
                            <button class="btn btn-primary" id="btn_reloadStatusSch"><i class="fa fa-sync"></i></button>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table-hover text-nowrap table-striped" id="l_schPemeriksaan" width="100%">
                            <thead>
                                <th>Id</th>
                                <th>Schedule</th>
                                <th>No Induk</th>
                                <th>Nama Mesin</th>
                                <th>Status</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-detail"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labeldetail"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <table class="table table-sm" id="tb_detail">
                        <thead>
                            <th>No Induk Mesin</th>
                            <th>Nama Mesin</th>
                            <th>Jam Rusak</th>

                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-hhky"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lbl_hhky">Jenis</h5>
                    <h5 class="modal-title" id="lbl_hhky1"> Dept</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="table-responsive col-md-12">
                    <table class="table table-responsive text-nowrap" id="tb_lokasi_hhky">
                        <thead>
                            <th>Id hhky</th>
                            <th>Tempat Kejadian</th>
                            <th style="width: max-content;">Bagian</th>
                            <th>Masalah</th>
                            <th>Level Resiko</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal list ss -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal_ls"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lbl_dept"></h5>
                    <input type="hidden" name="i_dept" id="i_dept">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-responsive text-nowrap" style="width: 100%" id="tb_ls">
                            <thead>
                                <th>NIK</th>
                                <th>Operator</th>
                                <th>Count SS</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_info_update" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content bg-info">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="myModalLabel">Update</h4>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" name="n_deskripsi" id="n_deskripsi" cols="30" rows="10" readonly></textarea>
                </div>
                <div class="modal-footer justify-content-between bg-info">
                    <p class="float-left" id="t_error"><i></i></p>
                    <button id="btn_close" name="btn_close" type="button"
                        class="btn btn-danger rounded-pill">Close</button>
                </div>
            </div>
        </div>
    </div> --}}
@stop

{{-- Push extra CSS --}}
@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush


{{-- Push extra scripts --}}

@push('js')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
