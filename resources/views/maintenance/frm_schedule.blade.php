@extends('layout.app')
@section('plugins.Select2', true)
@section('plugins.BsCustomFileInput', true)

{{-- Customize layout sections --}}

@section('content_header_title', 'Report Produksi')
@section('content_body')

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


    <div class="row">
        <div class="col-md-4">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-tools "></i> Schedule Mesin Tahunan</h3>
                </div>
                <form id="frm_sch">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="no_permintaan" id="no_permintaan">

                        <div class="form-group">
                            <b>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="r1" id="r_mt">
                                    <label class="form-check-label" for="r_mt" style="color: red;">Mesin Tahunan</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="r1" id="r_o">
                                    <label class="form-check-label" for="r_o" style="color: red;">Overhaul</label>
                                </div>
                            </b>
                            <input type="hidden" name="fil" id="fil" value="" required>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>No Induk Mesin</strong>
                                <select name="nomer_induk_mesin" id="nomer_induk_mesin"
                                    class="form-control rounded-0 select2" style="width: 100%;" required>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 2%">
                            <div class="col-md-12">
                                <strong>Nama Mesin / Alat</strong>
                                <input type="text" name="nama_mesin" id="nama_mesin" class="form-control rounded-0"
                                    readonly>
                            </div>
                        </div>
                        <input type="hidden" name="no_mesin" id="no_mesin">
                        <div class="row" style="margin-top: 2%">
                            <div class="col-md-6">
                                <strong>Schedule</strong>
                                <input type="date" name="s_schedule" id="s_schedule" class="form-control rounded-0"
                                    required>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 2%">
                            <div class="col-md-12">
                                <strong>Masalah</strong>
                                <input type="text" name="s_masalah" id="s_masalah" class="form-control rounded-0"
                                    value="Laporan pemeriksaan mesin tahunan" readonly>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 2%">
                            <div class="col-md-12">
                                <strong>Kondisi</strong>
                                <input type="text" id="s_kondisi" name="s_kondisi" class="form-control rounded-0"
                                    placeholder="Device Number" style="width:100%;" value="Perlu dilakukan pengecekan"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <label id="no_permintaan1"></label>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-tambah btn-flat float-right" id="btn_submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-success card-outline">

                <div class="card-header card-color-list">
                    <h3 class="card-title">List Schedule Pemeriksaan</h3>
                </div>

                <div class="card-body">
                    <div class="row" style="margin-top: -1%">
                        <div class="form-group col-md-2">
                            <strong for="beginning">Beginning</strong>
                            <input type="date" class="form-control" id="tgl_awal" value="{{ date('Y-m') . '-01' }}">
                        </div>
                        <div class="form-group col-md-2">
                            <strong>Ending</strong>
                            <input type="date" class="form-control" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <strong> Status : </strong>
                            <select name="status_sch" id="status_sch" class="form-control select2" value="Selesai">
                                <option value="All">All</option>
                                <option value="open">Open</option>
                                <option value="process">Process</option>
                                <option value="pending">Pending</option>
                                <option value="selesai">Selesai</option>
                                <option value="complete">Complete</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="reject">Reject</option>
                                <option value="TempReject">TempReject</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <strong> Jenis : </strong>
                            <select name="jenis" id="jenis" class="form-control select2" value="MesinTahunan">
                                <option value="MesinTahunan">Mesin Tahunan</option>
                                <option value="Overhaul">Overhaul</option>
                            </select>
                        </div>
                        <div class="col col-md-1">
                            <strong>Reload</strong>
                            <button class="btn btn-cari btn-flat" id="btn_reload" name="btn_reload"><i
                                    class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <hr style="margin-top: -2%">
                <div class="card-body table-responsive p-0" style="margin-top: -1%">
                    <table class="table table-hover text-nowrap" width="100%" id="l_schPemeriksaan">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Schedule</th>
                                <th>No Induk</th>
                                <th>Nama Mesin</th>
                                <th>No Mesin</th>
                                <th>Status</th>
                                <th>Selesai</th>
                                <th>Lampiran</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-5">
                    <h3 class="card-title float-left">Approve Pekerjaan
                    </h3>
                </div>

                <div class="col-md-2">
                    <strong>Beginning</strong>
                    <input type="date" name="apDate" id="apDate1" value={{ date('Y-m') . '-01' }}
                        class="form-control" required>
                </div>
                <div class="col-md-2">
                    <strong>Ending</strong>
                    <input type="date" name="apDate" id="apDate2" value={{ date('Y-m-d') }} class="form-control"
                        required>
                </div>
                <div class="col-md-2">
                    <strong>SHIFT</strong>
                    <select name="apShift" id="apShift" class="form-control select2" required>
                        <option value="">Select ....</option>
                        <option value="shift1">SHIFT 1</option>
                        <option value="shift2">SHIFT 2</option>
                        <option value="shift3">SHIFT 3</option>
                        <option value="NonShift">NON SHIFT</option>
                    </select>
                </div>
                <div class="col col-md-1">
                    <strong>Reload</strong>
                    <button class="btn btn-cari btn-flat" id="btn_reload2" name="btn_reload2"><i
                            class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="card-body table-responsive p-0" style="margin-top: -1%">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap" id="tb_aprHasilPekerjaan">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Inputor</th>
                                <th>Status</th>
                                <th>Approve</th>
                                <th>Tgl Approve</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="pending-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Pending Perbaikan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-pending">
                        @csrf
                        <input type="hidden" id="id-req" name="id-req">
                        <div class="row">
                            <div class="col col-md-3"><label>No. :</label></div>
                            <div class="col col-md-4">
                                <label id="no-perbaikan"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-md-3"><label>Departemen :</label></div>
                            <div class="col col-md-8">
                                <label id="dept"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-3"><label>Shift :</label></div>
                            <div class="col col-md-4">
                                <label id="shift"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-3"><label>No. Mesin :</label></div>
                            <div class="col col-md-4">
                                <label id="no-mesin"></label>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-3"><label>Nama Mesin :</label></div>

                            <div class="col col-md-8">
                                <label id="nama_mesin"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-md-3"><label>Masalah :</label></div>
                            <div class="col col-md-8">
                                <label id="masalah"></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col col-md-3"><label>Kondisi :</label></div>
                            <div class="col col-md-8">
                                <label id="kondisi"></label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col col-md-2"><label>Jadwal :</label></div>
                            <div class="form-group col-md-5">
                                <label for="">Dari :</label>
                                <input type="date" class="form-control" name="jadwal1" id="jadwal1" required>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="">Sampai :</label>
                                <input type="date" class="form-control" name="jadwal2" id="jadwal2" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3"><label>Keterangan :</label></div>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="keterangan" id="keterangan"
                                    placeholder="Keterangan">
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-update">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Report Pekerjaan (RP) -->
    <div class="modal fade" id="modal_rp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report Pekerjaan</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center" style="margin-top: -20px">
                    <div class="mt-4">
                        <div class="btn btn-default btn-success btn-lg btn-flat" id="btn_lp" style="color: green">
                            <i class="fas fa-heart fa-lg mr-2"></i>
                            List Pekerjaan, Tanggal <span style="font-weight: bold; color:red" id="tglPekerjaan"></span>
                        </div>
                    </div>
                </div>

                <div id="collapseLP" class="panel-collapse in collapse" style="">
                    <hr>
                    <div class="col-md-12">
                        {{-- <div class="row">
                        <div class="col-md-4">
                            <strong>Tanggal</strong>
                            <input type="date" name="dtlTgl" id="dtlTgl" value={{ date('Y-m-d') }}
                                class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <strong>SHIFT</strong>
                            <select name="dtlShift" id="dtlShift" class="form-control select2" required>
                                <option value="">Select ....</option>
                                <option value="shift1">SHIFT 1</option>
                                <option value="shift2">SHIFT 2</option>
                                <option value="shift3">SHIFT 3</option>
                                <option value="NonShift">NON SHIFT</option>
                            </select>
                        </div> 
                        <div class="col-md-1">
                            <strong>Refresh</strong>
                            <button class="btn btn-primary rounded-pill" id="refresh_reportPekerjaan"><i
                                    class="fa fa-sync"></i></button>
                        </div>
                    </div> --}}
                        {{-- <br> --}}
                        {{-- <div class="row"> --}}
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap" id="tb_hasilPerbaikan" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nama Mesin</th>
                                        <th>No</th>
                                        <th>Masalah</th>
                                        <th>Tindakan</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-6">
                                {{-- <div class="row"> --}}
                                <strong>Deskripsi Maintenance</strong>
                                <textarea name="lpDesk" id="lpDesk" cols="60" rows="10" class="form-control"></textarea>
                                {{-- </div> --}}
                            </div>
                            <div class="col-md-6">
                                {{-- <div class="row"> --}}
                                <strong>Deskripsi Utility</strong>
                                <textarea name="lpDesk_2" id="lpDesk_2" cols="60" rows="10" class="form-control"></textarea>
                                {{-- </div> --}}
                            </div>

                        </div>
                    </div>
                    <br>
                    <form id="frm_ap">
                        @csrf
                        <input type="hidden" name="dtlId" id="dtlId">
                        <input type="hidden" name="dtlStatusPekerjaan" id="dtlStatusPekerjaan">
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-update" value="Approve" id="btn_submit">
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Upload Document (UD) -->
    <div class="modal fade" id="modal_upload_document" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-upload"></i><b> Upload</b> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_ud" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="ud_id" id="ud_id">
                                <input type="text" name="ud_idPerbaikan" id="ud_idPerbaikan">
                                <input type="text" name="ud_status" id="ud_status">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label><i class="fas fa-certificate"> Upload Lampiran</i></label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file_ud_upload"
                                                name="file_ud_upload" required>
                                            <label class="custom-file-label" for="customFile" id="tt"
                                                name="tt" placeholder="Choose file">Choose
                                                file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-keluar btn-flat" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-update btn-flat" id="btn_submit">Save</button>
                        </div>
                    </form>
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
    <script src="{{ asset('js/select_no_induk_mesin.js') }}"></script>
    <script src="{{ asset('js/frm_schedule.js') }}"></script>
@endpush
