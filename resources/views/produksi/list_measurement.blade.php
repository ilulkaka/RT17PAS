@extends('layout.app')
@section('plugins.Select2', true)
@section('plugins.BsCustomFileInput', true)
@section('content_header_title', 'List Measurement')

@section('content_body')


    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-outline" id="btn_approve" name="btn_approve"><u style="color: blue">
                        Approve</u></button>
                <button type="button" class="btn btn-outline btn-active" id="btn_production" name="btn_production"><u
                        style="color: blue">
                        Production</u></button>
            </div>
        </div>
        <div id="accordion">
            <div id="collapseApprove" class="collapse" style="">
                <div class="card">
                    <div class="card-header">
                        <div class="col-12">
                            <h3 class="card-title"><b style="color: brown;">Approve</b>
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered table-hover text-nowrap" id="tb_list_approve" width="100%">
                                <thead>
                                    <tr>
                                        <th>id_meas</th>
                                        <th>id_meas_st</th>
                                        <th style="width: min-content;"><input type="checkbox" id="selectAllApprove">
                                        </th>
                                        <th>No. Reg</th>
                                        <th>Nama alat ukur</th>
                                        <th>Ukuran</th>
                                        <th>Jenis</th>
                                        <th style="width: 2%">Warna Identifikasi</th>
                                        <th style="width: 1%">Tgl Penyerahan</th>
                                        <th style="width: 1%">Range cek</th>
                                        <th>Lokasi</th>
                                        <th>No. Rak</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-update btn-flat" id="btn_updSelectedApprove"
                            name="btn_updSelectedApprove"><u>
                                Update Selected</u></button>
                    </div>
                    <!-- /.card-body -->
                    <!-- /.card -->
                </div>
            </div>

            <div id="collapseProduction" class="collapse show" style="">
                <div class="card">
                    <div class="card-header">
                        <div class="col-12">
                            <h3 class="card-title"><b style="color: brown;">Production</b>
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered table-hover text-nowrap" id="tb_list_production"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>id_meas</th>
                                        <th>id_meas_st</th>
                                        <th>No. Reg</th>
                                        <th>Nama alat ukur</th>
                                        <th>Ukuran</th>
                                        <th>Jenis</th>
                                        <th style="width: 2%">Warna Identifikasi</th>
                                        <th style="width: 1%">Tgl Penyerahan</th>
                                        <th style="width: 1%">Range cek</th>
                                        <th>Tgl Kalibrasi</th>
                                        <th>Lokasi</th>
                                        <th>No. Rak</th>
                                        <th style="width: 2%">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Report Abnormal (RA) -->
    <div class="modal fade" id="modal_ra" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <form id="frm_ra">
                    @csrf
                    <div class="modal-body box-profile">
                        <h3 class="profile-username text-center" id="no_masalah"><b> Report Abnormalitas</b>
                        </h3>
                        <hr>
                        <input type="hidden" name="ra_idMeasST" id="ra_idMeasST">
                        <input type="hidden" name="ra_idMeas" id="ra_idMeas">
                        <input type="hidden" name="ra_tglPenyerahan" id="ra_tglPenyerahan">
                        <dl class="row">
                            <dt class="col-sm-3" style="margin-top: 5px">Nomer Registrasi</dt>
                            <dt class="col-sm-1" style="margin-top: 5px; text-align:right">: </dt>
                            <dd class="col-sm-8 d-flex align-items-start">
                                <input type="text" class="form-control align-self-start"
                                    style="font-size: 20px; color:blue; font-weight:bold" name="ra_noRegistrasi"
                                    id="ra_noRegistrasi" readonly>
                            </dd>

                            <dt class="col-sm-3" style="margin-top: 5px">Nama alat</dt>
                            <dt class="col-sm-1" style="margin-top: 5px; text-align:right">: </dt>
                            <dd class="col-sm-8 d-flex align-items-start">
                                <input type="text" class="form-control align-self-start" style="font-size: 18px"
                                    name="ra_namaAlat" id="ra_namaAlat" readonly>
                            </dd>

                            <dt class="col-sm-3" style="margin-top: 5px">Ukuran</dt>
                            <dt class="col-sm-1" style="margin-top: 5px; text-align:right">: </dt>
                            <dd class="col-sm-2 ">
                                <input type="text" class="form-control align-self-start" style="font-size: 18px"
                                    name="ra_ukuran" id="ra_ukuran" readonly>
                            </dd>
                            <dt class="col-sm-1" style="margin-top: 5px">Jenis: </dt>
                            <dd class="col-sm-2 d-flex align-items-start">
                                <input type="text" class="form-control align-self-start" style="font-size: 18px"
                                    name="ra_jenis" id="ra_jenis" readonly>
                            </dd>
                            <dt class="col-sm-1" style="margin-top: 5px; text-align:right">Kode: </dt>
                            <dd class="col-sm-2 d-flex align-items-start">
                                <input type="text" class="form-control align-self-start" style="font-size: 18px"
                                    name="ra_kode" id="ra_kode" readonly>
                            </dd>

                            <dt class="col-sm-3" style="margin-top: 5px">Departemen</dt>
                            <dt class="col-sm-1" style="margin-top: 5px; text-align:right">: </dt>
                            <dd class="col-sm-8 d-flex align-items-start">
                                <input type="text" class="form-control align-self-start" style="font-size: 18px"
                                    name="ra_section" id="ra_section" readonly>
                            </dd>
                            <dt class="col-sm-3" style="margin-top: 5px">Masalah</dt>
                            <dt class="col-sm-1" style="margin-top: 5px; text-align:right">: </dt>
                            <dd class="col-sm-8 d-flex align-items-start">
                                <textarea name="ra_masalah" id="ra_masalah" cols="60" rows="2" class="form-control" required></textarea>
                            </dd>
                            <dt class="col-sm-3" style="margin-top: 5px">Penyebab</dt>
                            <dt class="col-sm-1" style="margin-top: 5px; text-align:right">: </dt>
                            <dd class="col-sm-8 d-flex align-items-start">
                                <textarea name="ra_penyebab" id="ra_penyebab" cols="60" rows="3" class="form-control" required></textarea>
                            </dd>
                        </dl>
                    </div>
                    <div class="modal-footer" style="margin-top: -3%">
                        <button type="button" class="btn btn-keluar rounded-pill" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-update rounded-pill" id="btn_save">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop

{{-- Push extra CSS --}}
@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/list_measurement.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/list_measurement.js') }}"></script>
@endpush
