@section('plugins.Select2', true)
@extends('layout.app')
@section('content_header_title', 'Report Produksi')

@section('content_body')

    {{-- <div class="card card-primary"> --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    <h3 class="card-title" style="font-size:x-large">Approve Jam Operator</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <strong for="tgl_awal">Tanggal Awal</strong>
                    <input type="date" class="form-control" id="tgl_awal" value="{{ date('Y-m') . '-01' }}">
                </div>
                <div class="col-md-2">
                    <strong for="tgl_akhir">Tanggal Akhir</strong>
                    <input type="date" class="form-control" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <strong for="selectline">Line</strong>
                    <select name="selectline" id="selectline" class="form-control select2" required>
                    </select>
                </div>
                <div class="col-md-2">
                    <strong for="selectshift">Shift</strong>
                    <select name="selectshift" id="selectshift" class="form-control select2" style="width: 100%;" required>
                        <option value="">Pilih Shift...</option>
                        <option value="All">All</option>
                        <option value="SHIFT1">SHIFT 1</option>
                        <option value="SHIFT2">SHIFT 2</option>
                        <option value="SHIFT3">SHIFT 3</option>
                        <option value="NONSHIFT">NON SHIFT</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <strong for="selectshift">Status</strong>
                    <select name="selectstatus" id="selectstatus" class="form-control select2" style="width: 100%;"
                        required>
                        <option value="">Pilih Status...</option>
                        <option value="All">All</option>
                        <option value="Approve">Approve</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-cari w-100" id="btn_reload">
                        <i class="fa fa-search"></i> Cari
                    </button>
                </div>
            </div>

            <hr>

            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="tb_approve_jam_operator" width="100%">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Tgl Proses</th>
                            <th>Operator</th>
                            <th>Line</th>
                            <th>Shift</th>
                            <th>Jam Total</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Approved</th>
                            <th>Tgl Approve</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <!--Modal Edit Jam Kerja Operator-->
    <div class="modal fade" id="modal-editjamopr" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title card-title" id="exampleModalLongTitle-1">Edit Jam Kerja Operator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-jamkerjaoperator">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id_jam_kerja" name="id_jam_kerja">
                        <div class="row">
                            <div class="col col-md-3"><label>Tgl Proses</label></div>
                            <label>:</label>
                            <div class="col col-md-4">
                                <label id="e-tgljamkerja" name="e-tgljamkerja"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-md-3"><label>Operator</label></div>
                            <label>:</label>
                            <div class="col col-md-6">
                                <label id="e-operator" name="e-operator"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-3"><label>Shift</label></div>
                            <label>:</label>
                            <div class="col col-md-6">
                                <label id="e-shift" name="e-shift"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col col-md-3"><label>Jam Total</label></div>
                            <label>:</label>
                            <div class="col col-md-3">
                                <input type="number" id="e-jamtotal" name="e-jamtotal" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col col-md-3"><label>Keterangan</label></div>
                            <label>:</label>
                            <div class="col col-md-8">
                                <textarea id="e-keterangan" name="e-keterangan" class="form-control" placeholder="Keterangan" cols="30"
                                    rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-close btn-flat" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-update btn-flat" value="Update">
                    </div>
                </form>
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
    <script src="{{ asset('js/list_report_produksi.js') }}"></script>
    <script src="{{ asset('js/approve_jam_operator.js') }}"></script>
    <script src="{{ asset('js/selectline.js') }}"></script>
@endpush
