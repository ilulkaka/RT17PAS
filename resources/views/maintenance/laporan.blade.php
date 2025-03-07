@extends('layout.app')
@section('plugins.Select2', true)


@section('content_header_title', 'Laporan')
@section('content_body')

@section('content')

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
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

    <div class="row">

        <div class="col col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h3 class="card-title">Grafik Jam Kerusakan <i id="periode"></i></h3>
                    </div>


                </div>

                <div class="card-body">
                    <div>
                        <canvas id="chartjam"></canvas>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">

                        <label for="" id="totaljam"></label>
                    </div>
                    <div class="row">

                        <label for="" id="totmenunggu"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Jam Kerusakan</h3>

                </div>
                <div class="card-body">
                    <div>
                        <canvas id="chartsasaran"></canvas>
                    </div>
                </div>

                <!-- /.card-body -->
            </div>

        </div>

    </div>
    <div class="row">
        <table>
            <tbody>
                <tr>
                    <td>
                        <button class="btn btn-secondary" id="btn-rekap">Rekap Jam kerusakan</button>
                    </td>
                    <td>
                        <button class="btn btn-primary" id="btn-target">Setting target</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="card col-md12">
            <div class="card-header">
                <h3>Daftar Jam Kerusakan</h3>

                <div class="row align-center">
                    <label for="" class="col-md-2 text-center">Periode</label>
                    <input type="month" class="form-control col-sm-4" id="tgl2" value="{{ date('Y-m') }}">
                    <button class="btn btn-primary" id="btn_refresh"><i class="fa fa-sync"></i></button>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table-hover text-nowrap" id="tb_kerusakan">
                        <thead>
                            <th>No Perbaikan</th>
                            <th>Departemen</th>
                            <th>No Mesin</th>
                            <th>Nama Mesin</th>
                            <th>Klasifikasi</th>
                            <th>Status</th>
                            <th>Tgl Rusak</th>
                            <th>Tgl Mulai Perbaikan</th>
                            <th>Tgl Selesai</th>
                            <th>Jam kerusakan</th>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success" id="btn-excel">Download Excel</button>
            </div>
        </div>

    </div>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-rekap"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rekap Jam Kerusakan Mesin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_rekap">

                        @csrf
                        <div class="row">
                            <div class="col col-md-2">
                                <label for="">Periode : </label>
                            </div>
                            <div class="col col-md-4">
                                <input type="date" class="form-control" name="tgl1" id="tgl1" required>
                            </div>

                            <div class="col col-md-4">
                                <input type="date" class="form-control" name="tgl2" id="tgl2" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-success" id="simpan_rekap">Simpan Rekap</button>
                    </form>
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
                            <th>No Mesin</th>
                            <th>Jam menunggu</th>
                            <th>Jam Rusak</th>
                            <th>Detail</th>
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
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-target"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Setting Target Sasaran Mutu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_target">

                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="">Periode : </label>
                            </div>
                            <div class="form-group col-md-4">
                                <input type="month" class="form-control" id="tgl1" name="tgl1"
                                    value="{{ date('Y-m') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <input type="month" class="form-control" id="tgl2" name="tgl2"
                                    value="{{ date('Y-m') }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="">Departemen :</label>
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control" name="departemen" id="">
                                    <option value="">Pilih departemen</option>
                                    @foreach ($dept as $k)
                                        <option value="{{ $k->DEPT_SECTION }}">{{ $k->DEPT_SECTION }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="">Nilai Target :</label>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="number" name="target" class="form-control">
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-success" id="simpan_target">Simpan Target</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal_dtlPerbaikan"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lblDetail"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">

                            <h3 class="profile-username text-center" id="no_masalah"><b> Detail Perbaikan.</b></h3>
                            <hr>

                            <!-- /.card-header -->

                            <dl class="row">
                                <dt class="col-sm-4">No Induk Mesin</dt>
                                <dt class="col-sm-0">:</dt>
                                <dd class="col-sm-7" id="dtl_noInduk"></dd>
                                <dt class="col-sm-4">Nama Mesin</dt>
                                <dt class="col-sm-0">:</dt>
                                <dd class="col-sm-7" id="dtl_namaMesin"></dd>
                                <dt class="col-sm-4">No Mesin</dt>
                                <dt class="col-sm-0">:</dt>
                                <dd class="col-sm-7" id="dtl_noMesin"></dd>
                                {{-- <dt class="col-sm-4">Type</dt>
                                <dt class="col-sm-0">:</dt>
                                <dd class="col-sm-7" id="d_tipe">Improvement</dd>
                                <dt class="col-sm-4">Category</dt>
                                <dt class="col-sm-0">:</dt>
                                <dd class="col-sm-7" id="d_kategori">Software
                                </dd>
                                <dt class="col-sm-4">Device No</dt>
                                <dt class="col-sm-0">:</dt>
                                <dd class="col-sm-7" id="d_dev_no">dashboard
                                </dd> --}}
                            </dl>

                            <hr>
                            <!-- /.card-body -->

                            <div class="row">
                                <div class="col col-md-12">

                                    <div class="card-body">
                                        <li class="list-group-item">
                                            <b style="color: red; font-size:18px"><i class="fas fa-bug mr-1"></i>
                                                Masalah</b>
                                            <a class="float-right"></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b id="dtl_masalah"></b>
                                        </li>

                                        <hr>
                                        <strong style="font-size: 18px"><i class="fas fa-vial mr-1"></i><u>
                                                Kondisi</u></strong>
                                        <p class="text-muted" id="dtl_kondisi">
                                        </p>

                                        <hr>
                                        <strong style="font-size: 18px"><i class="fas fa-user-md mr-1"></i><u>
                                                Tindakan</u></strong>
                                        <p class="text-muted" id="dtl_tindakan">
                                        </p>

                                        {{-- <li class="list-group-item">
                                            <b>Status </b>
                                            <a class="float-right btn btn-warning btn-xs">Open</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Progress </b>
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 0%">
                                                    <span> <strong>0.00%
                                                            Complete</strong></span>
                                                </div>
                                            </div>
                                        </li> --}}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


                </div>
            </div>
        </div>
    </div>

@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/frm_schedule.js') }}"></script>
@endpush
