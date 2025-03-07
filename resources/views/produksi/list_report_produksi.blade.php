@section('plugins.Select2', true)
@extends('layout.app')
@section('content_header_title', 'Report Produksi')

@section('content_body')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    <h3 class="card-title">Hasil Produksi</h3>
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
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-cari w-100" id="btn_reload">
                        <i class="fa fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </div>
        <hr style="margin-top: 0%">
        <div class="card-body table-responsive p-0" style="margin-top: -1%">
            <table id="tb_detail_hasil_produksi" class="table table-hover table-striped nowrap display" style="width:100%">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Proses</th>
                        <th>Tgl Proses</th>
                        <th>Part No</th>
                        <th>Lot No</th>
                        <th>Shape</th>
                        <th>Haba</th>
                        <th>Incoming</th>
                        <th>Finish Qty</th>
                        <th>NG Qty</th>
                        <th>Prosentase</th>
                        <th>Operator</th>
                        <th>Shift</th>
                        <th>No Mesin</th>
                        <th>Cycle</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <br>
        <div class="card-footer" style="margin-top:-1%">
            <button class="btn btn-excel" id="btn-excel"><i class="fas fa-file-excel"> Download Excel</i></button>
        </div>
        <!-- /.card-body -->
        <!-- /.card -->
    </div>

    <div class="modal fade bd-example-modal-lg" id="modal-NG" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title card-title" id="detaillist">Detail NG</h5>
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
                    <div class="col col-md-3 float-right">
                        <button type="button" class="btn btn-keluar" id="btn-close-list">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- 
    <div id="spinner-overlay" class="spinner-overlay d-none">
        <div class="text-center">
            <div class="spinner-border text-light" style="width: 5rem; height: 5rem;" role="status"></div>
            <div class="mt-3 text-light">Process...</div>
        </div>
    </div> --}}


@stop

{{-- Push extra CSS --}}
@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/list_report_produksi.js') }}"></script>
    <script src="{{ asset('js/selectline.js') }}"></script>
@endpush
