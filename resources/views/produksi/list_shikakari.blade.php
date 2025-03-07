@section('plugins.Select2', true)
@section('plugins.Chartjs', true)
@section('plugins.BsCustomFileInput', true)
@extends('layout.app')
@section('content_header_title', 'Report Produksi')

@section('content_body')

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

    <div class="row">
        <div class="col col-md-12">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-color-list">
                            <h3 class="card-title"> List Shikakari </h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                        class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row" style="margin-top: -1%">
                                {{-- <label> Line</label> --}}
                                <div class="col col-md-3">
                                    <strong>Line</strong>
                                    <select name="selectline" id="selectline" class="form-control select2 "
                                        style="width: 100%;" required>
                                    </select>
                                </div>

                                <div class="col col-md-2">
                                    <strong>Tag</strong>
                                    <select name="selectTag" id="selectTag" class="form-control select2 "
                                        style="width: 100%;" required>
                                        <option value="All">All</option>
                                        <option value="EXPRES">Express</option>
                                        <option value="">Reguler</option>
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    <strong>Warna</strong>
                                    <select name="selectwarna" id="selectwarna" class="form-control select2"
                                        style="width: 100%;" required>
                                    </select>
                                </div>
                                <div class="col col-md-1">
                                    <strong>Reload</strong>
                                    <button class="btn btn-cari btn-flat" id="btn_reload"><i
                                            class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top: 0%">
                        <div class="card-body table-responsive p-0" style="margin-top: -1%">
                            <table class="table table-bordered table-hover text-nowrap" id="tb_detail_shikakari"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Proses Sebelum</th>
                                        <th>Shikakari</th>
                                        <th>Tgl In</th>
                                        <th>Part No</th>
                                        <th>Lot No</th>
                                        <th>Qty</th>
                                        <th>Tag</th>
                                        <th>Tag Warna</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <!-- /.card-body -->
                        <br>
                        <div class="card-footer" style="margin-top:-1%">
                            @if (Auth::user()->hasDepartment('PPIC') || Auth::user()->hasDepartment('Admin'))
                                <button class="btn btn-update" id="btn_upload">
                                    <i class="fa fa-upload"> Update Expres</i>
                                </button>
                            @endif
                            <button class="btn btn-excel" id="btn_excel"><i class="fas fa-file-excel"> Download
                                    Excel</i></button>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Expres-->
    <div class="modal fade" id="modal_expres" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Update data Expres</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="upd_expres" action="{{ route('ppic.upd_expres') }}" enctype="multipart/form-data"
                        method="POST">
                        @csrf
                        <div class="form-group col-md-12">
                            <br>
                            <strong>File Expres</strong>
                            <div class="form-group">
                                <!-- <label for="customFile">Custom File</label> -->
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file_expres" name="file_expres"
                                        required>
                                    <label class="custom-file-label" for="customFile" placeholder="if any attactment">Choose
                                        file</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <input type="button" class="btn btn-keluar btn-flat" data-bs-dismiss="modal" value="Close">
                            <input type="submit" class="btn btn-update btn-flat" id="btn_upd_exp" name="btn_upd_exp"
                                value="Update">
                            <span class="float-right">
                                <a href="{{ url('/PDF/Template Upload Expres.xlsx') }}" class="link-black text-sm"
                                    target="_blank">
                                    <i class="fa fa-download"></i> Template Excel
                                </a>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Next Process (UNP)-->
    <div id="modal_unp" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="unp_lotno" class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_unp">
                        @csrf
                        <input type="hidden" name="unp_id" id="unp_id">
                        <input type="hidden" name="f_partno" id="f_partno">
                        <input type="hidden" name="f_lotno" id="f_lotno">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Proses Sebelum</strong>
                                    <input type="text" name="unp_sebelum" id="unp_sebelum" class="form-control"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Shikakari</strong>
                                    <select name="unp_shikakari" id="unp_shikakari" class="form-control select2"
                                        required>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-keluar" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-update">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

{{-- Push extra CSS --}}
@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/selectline.js') }}"></script>
    <script src="{{ asset('js/selectwarna.js') }}"></script>
    <script src="{{ asset('js/list_shikakari.js') }}"></script>
@endpush
