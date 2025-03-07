@extends('layout.app')
@section('plugins.Select2', true)

{{-- Customize layout sections --}}

@section('content_header_title', 'Report Produksi')
@section('content_body')

    <div class="row">
        <div class="col-md-12">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="card-title" id="exampleModalLongTitle"><b> Form Entry Komposisi</b> </h5>
                </div>
                <form id="frm_fek">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="fek_id" id="fek_id">
                        <input type="hidden" name="fek_fil" id="fek_fil">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <strong for="nama">Tgl Cek</strong>
                                <input type="date" name="fek_tglCek" id="fek_tglCek" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <strong for="nama">Operator QC</strong>
                                {{-- fek_oprQc --}}
                                <select name="select_nik_casting" id="select_nik_casting"
                                    class="form-control select2 @error('nik') is-invalid @enderror rounded-0"
                                    style="width: 100%;" required>
                                    <option value="">NIK</option>
                                </select>

                            </div>
                            <div class="form-group col-md-3">
                                <strong for="nama">Operator Melting</strong>
                                {{-- fek_oprMelting --}}
                                <select name="select_nik_casting2" id="select_nik_casting2"
                                    class="form-control select2 @error('nik') is-invalid @enderror rounded-0"
                                    style="width: 100%;" required>
                                    <option value="">NIK</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <strong for="nama">Nomer Cast</strong>
                                <select name="fek_castNo" id="fek_castNo" class="form-control select2" required>
                                    <option value="">Pilih Nomer Cast</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col col-md-2">
                                <strong class="col-md-1">C</strong>
                                <input type="number" class="form-control rounded-0" name="fek_c" id="fek_c"
                                    step="0.0001" required>
                            </div>
                            <div class="col col-md-1">
                                <strong class="col-md-1">Si</strong>
                                <input type="number" class="form-control rounded-0" name="fek_si" id="fek_si"
                                    step="0.0001" required>
                            </div>
                            <div class="col col-md-1">
                                <strong class="col-md-1">Mn</strong>
                                <input type="number" class="form-control rounded-0" name="fek_mn" id="fek_mn"
                                    step="0.0001" required>
                            </div>
                            <div class="col col-md-1">
                                <strong class="col-md-1">P</strong>
                                <input type="number" class="form-control rounded-0" name="fek_p" id="fek_p"
                                    step="0.0001" required>
                            </div>
                            <div class="col col-md-1">
                                <strong class="col-md-1">S</strong>
                                <input type="number" class="form-control rounded-0" name="fek_s" id="fek_s"
                                    step="0.0001" required>
                            </div>
                            <div class="col col-md-1">
                                <strong class="col-md-1">B</strong>
                                <input type="number" class="form-control rounded-0" name="fek_b" id="fek_b"
                                    step="0.0001" required>
                            </div>
                            <div class="col col-md-1">
                                <strong class="col-md-1">Cu</strong>
                                <input type="number" class="form-control rounded-0" name="fek_cu" id="fek_cu"
                                    step="0.0001" required>
                            </div>
                            <div class="col col-md-1">
                                <strong class="col-md-1">Sn</strong>
                                <input type="number" class="form-control rounded-0" name="fek_sn" id="fek_sn"
                                    step="0.0001" required>
                            </div>
                            <div class="col col-md-1">
                                <strong class="col-md-1">Ni</strong>
                                <input type="number" class="form-control rounded-0" name="fek_ni" id="fek_ni"
                                    step="0.0001" required>
                            </div>
                            <div class="col col-md-2">
                                <strong class="col-md-1">Cr</strong>
                                <input type="number" class="form-control rounded-0" name="fek_cr" id="fek_cr"
                                    step="0.0001" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-md-2 float-right">
                            <button type="submit" class="form-control btn-update rounded-0 " id="btn_submit"> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="card-title" id="exampleModalLongTitle"><b>Data Komposisi</b></h5>
                </div>

                <div class="card-body">
                    <div class="row" style="margin-top: -1%">
                        <div class="col col-md-3">
                            <strong>Tanggal Awal</strong>
                            <input type="date" class="form-control" id="tgl_awal" name="tgl_awal"
                                value="{{ date('Y-m') . '-01' }}">
                        </div>
                        <div class="col col-md-3">
                            <strong>Tanggal Akhir</strong>
                            <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir"
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <strong>Lot No</strong>
                            <input type="text" name="fek_lotNo" id="fek_lotNo" class="form-control me-4"
                                placeholder="Lot No.">
                        </div>
                        <div class="col col-md-1">
                            <strong>Reload</strong>
                            <button class="btn btn-cari btn-flat" id="btn_reload" name="btn_reload"><i
                                    class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <hr style="margin-top: 0%">

                <div class="card-body table-responsive p-0" style="margin-top: -1%">
                    <table class="table table-hover text-nowrap" width="100%" id="tb_komposisi">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Tgl Proses</th>
                                <th>Opr QC</th>
                                <th>Opr Melting</th>
                                <th>Lot No</th>
                                <th>Cast No</th>
                                <th>C</th>
                                <th>Si</th>
                                <th>Mn</th>
                                <th>P</th>
                                <th>S</th>
                                <th>B</th>
                                <th>Cu</th>
                                <th>Sn</th>
                                <th>Ni</th>
                                <th>Cr</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <br>
                {{-- <button class="btn btn-success float-right btn-flat" id="btn_excel"><i class="fas fa-file-excel"></i>
                    Excel</button> --}}

            </div>
        </div>
    </div>
@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/opr_casting.js') }}"></script>
    <script src="{{ asset('js/frm_komposisi.js') }}"></script>
@endpush
