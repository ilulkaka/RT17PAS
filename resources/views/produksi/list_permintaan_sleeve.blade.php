@section('plugins.Select2', true)
@extends('layout.app')

{{-- Customize layout sections --}}

@section('content_header_title', 'Report Produksi')
@section('content_body')

    <div class="card">
        <div class="card-header card-color-list">
            <div class="row">
                <div class="col-12">
                    <h3 class="card-title">List Permintaan Sleeve</h3>
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
                    <strong for="selectline">Status Permintaan</strong>
                    <select name="status_permintaan" id="status_permintaan" class="form-control select2" required>
                        <option value="Open">Open</option>
                        <option value="Proses">Proses</option>
                        <option value="Close">Close</option>
                        <option value="All">All</option>
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
            <table id="tb_permintaan_sleeve" class="table table-hover table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Barcode No</th>
                        <th>Tgl Request</th>
                        <th>Jenis</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Nouki</th>
                        <th>Keterangan</th>
                        <th>Opr Centrifugal</th>
                        <th>Qty Ok</th>
                        <th>Tgl Kirim</th>
                        <th style="width: 10%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <br>
        {{-- <div class="card-footer" style="margin-top:-1%">
            <button class="btn btn-excel" id="btn-excel"><i class="fas fa-file-excel"> Download Excel</i></button>
        </div> --}}
    </div>
@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/list_permintaan_sleeve.js') }}"></script>
@endpush
