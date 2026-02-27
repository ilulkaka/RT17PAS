@extends('layout.app')
@section('plugins.Select2', true)

{{-- Customize layout sections --}}

@section('content_header_title', 'Iuran Warga')
@section('content_body')

    <a href="#" id="add_iuran" name="add_iuran"><i class="fas fa-plus"> Add Iuran </i></a>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-color-list">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="card-title">List Iuran Warga</h3>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row align-items-end g-2">

                        <div class="col-lg-2 col-md-3 col-sm-3 mb-2">
                            <label><strong>Periode:</strong></label>
                            <select class="form-control" id="periode" name="periode">
                                @for ($i = date('Y'); $i >= 2024; $i--)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-3 mb-2">
                            <label><strong>BLOK:</strong></label>
                            <select class="form-control select2" id="selectblok" name="selectblok">
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-3 mb-2 d-flex">
                            <button class="btn btn-cari mr-2 w-100" id="btn_reload">
                                <i class="fa fa-search"></i>
                            </button>

                            <button class="btn btn-danger w-100 ml-2" id="btn_pdf">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </div>

                    </div>
                </div>

                <hr style="margin-top: 0%">
                <div class="card-body table-responsive p-0" style="padding-top: -2%">
                    <table id="tb_list_iuran" class="table table-bordered table-hover table-striped nowrap"
                        style="width:100%">
                    </table>
                </div>
                <br>
                {{-- <div class="card-footer" style="margin-top:-1%">
            <button class="btn btn-excel" id="btn-excel"><i class="fas fa-file-excel"> Download Excel</i></button>
        </div> --}}
            </div>
        </div>
    </div>

    <!-- Modal Add Iuran -->
    <div class="modal fade" id="modal_add_iuran" tabindex="-1" role="dialog" aria-labelledby="periodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="frmAddIuran">
                <!-- Jika Laravel, jangan lupa csrf -->
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="periodeModalLabel">Add Iuran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="periode_awal">Tgl Bayar</label>
                            <input type="date" class="form-control" name="tgl_bayar" id="tgl_bayar"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="periode_awal">Blok</label>
                            <input type="text" class="form-control" name="blok" id="blok" placeholder="A00/00"
                                maxlength="6" required>
                        </div>
                        <div class="form-group">
                            <label for="periode_awal">Periode Awal</label>
                            <input type="month" class="form-control" name="periode_awal" id="periode_awal" required>
                        </div>
                        <div class="form-group">
                            <label for="periode_akhir">Periode Akhir</label>
                            <input type="month" class="form-control" name="periode_akhir" id="periode_akhir" required>
                        </div>
                        <div class="form-group">
                            <label for="periode_akhir">Nominal</label>
                            <input type="number" class="form-control" name="nominal" id="nominal" value="15000"
                                required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-update" id="btn_submit">Simpan</button>
                        <button type="button" class="btn btn-keluar" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Iuran Warga-->
    <div class="modal fade" id="modal_detail_iuran_warga" tabindex="-1" role="dialog"
        aria-labelledby="modalShikakariLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalShikakariLabel">Detail Iuran Blok <b id="diw_blok"></b> Periode <b
                            id="diw_periode"></b></h5>
                </div>

                <!-- Filter Section -->
                <div class="modal-body">
                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table id="tb_detail_iuran_warga" class="table table-striped table-hover text-nowrap align-middle"
                            width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Id</th>
                                    <th>Tgl Bayar</th>
                                    <th>Jumlah</th>
                                    <th>Periode</th>
                                    <th>Petugas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-keluar" data-bs-dismiss="modal">Close</button>
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
    <script src="{{ asset('js/frm_iuran_warga.js') }}"></script>
    <script src="{{ asset('js/selectblok.js') }}"></script>
@endpush
