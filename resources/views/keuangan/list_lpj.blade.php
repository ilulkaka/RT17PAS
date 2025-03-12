@extends('layout.app')
@section('plugins.Select2', true)

{{-- Customize layout sections --}}

@section('content_header_title', 'List Warga')
@section('content_body')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-color-list">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="card-title">List Pemasukan dan Pengeluaran</h3>
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
                        <div class="col col-md-3">
                            <strong for="selectline">Jenis</strong>
                            <select name="jenis" id="jenis" class="form-control select2">
                                <option value="All">All</option>
                                <option value="Masuk">Masuk</option>
                                <option value="Keluar">Keluar</option>
                            </select>
                        </div>
                        <div class="col col-md-1 d-flex align-items-end">
                            <button class="btn btn-cari w-100" id="btn_reload">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <div class="col col-md-1 d-flex align-items-end">
                            <button class="btn btn-danger rounded-0 w-100" id="btn_pdf">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <hr style="margin-top: 0%">
                <div class="card-body table-responsive p-0" style="margin-top: -1%">
                    <table id="tb_list_lpj" class="table table-bordered table-hover table-striped nowrap"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>PIC</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <br>
                {{-- <div class="card-footer" style="margin-top:-1%">
            <button class="btn btn-excel" id="btn-excel"><i class="fas fa-file-excel"> Download Excel</i></button>
        </div> --}}
            </div>
        </div>
    </div>
@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/list_lpj.js') }}"></script>
@endpush
