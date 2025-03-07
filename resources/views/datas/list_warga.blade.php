@section('plugins.Select2', true)
@extends('layout.app')

{{-- Customize layout sections --}}

@section('content_header_title', 'Report Produksi')
@section('content_body')

    <div class="row">
        <div class="col-md-4">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="card-title" id="exampleModalLongTitle"><b> Form Entry Data Warga</b> </h5>
                </div>
                <form id="frm_fedw">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <strong for="nama">Nama Warga</strong>
                                <input type="text" class="form-control @error('nama')is-invalid @enderror" name="nama"
                                    id="nama" placeholder="Nama Warga" required>
                            </div>
                            <div class="form-group col-md-12">
                                <strong for="nama">Blok</strong>
                                <input type="text" class="form-control @error('nama')is-invalid @enderror" name="blok"
                                    id="blok" placeholder="Blok Rumah" required>
                            </div>
                            <div class="form-group col-md-12">
                                <strong for="sel">Jenis Kelamin</strong>
                                <select name="jk" id="jk"
                                    class="form-control select2 @error('nik') is-invalid @enderror rounded-0"
                                    style="width: 100%;" required>
                                    <option value="">Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <strong for="sel">Status Tinggal</strong>
                                <select name="status_tinggal" id="status_tinggal"
                                    class="form-control select2 @error('nik') is-invalid @enderror rounded-0"
                                    style="width: 100%;" required>
                                    <option value="">Pilih...</option>
                                    <option value="Stay">Stay</option>
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Kos">Kos</option>
                                    <option value="Singgah">Singgah</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>No Telp</strong>
                                <input type="text" class="form-control" name="no_telp" id="no_telp"
                                    placeholder="No Telp">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="form-control btn-update rounded-0" id="btn_submit"
                                    name="btn_submit"><b>Update</b></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-color-list">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="card-title">List Warga</h3>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- <div class="col-md-2">
                            <strong for="tgl_awal">Tanggal Awal</strong>
                            <input type="date" class="form-control" id="tgl_awal" value="{{ date('Y-m') . '-01' }}">
                        </div>
                        <div class="col-md-2">
                            <strong for="tgl_akhir">Tanggal Akhir</strong>
                            <input type="date" class="form-control" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                        </div> --}}
                        <div class="col-md-2">
                            <strong for="selectline">Status Tinggal</strong>
                            <select name="status_tinggal" id="status_tinggal" class="form-control select2">
                                <option value="Stay">Stay</option>
                                <option value="Kontrak">Kontrak</option>
                                <option value="Singgah">Singgah</option>
                                <option value="Kos">Kos</option>
                                <option value="All">All</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-cari w-100" id="btn_reload">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <hr style="margin-top: 0%">
                <div class="card-body table-responsive p-0" style="margin-top: -1%">
                    <table id="tb_list_warga" class="table table-hover table-striped nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Nama</th>
                                <th>Blok</th>
                                <th>JK</th>
                                <th>Status Tinggal</th>
                                <th>No Telp</th>
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
        </div>
    </div>
@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/list_warga.js') }}"></script>
@endpush
