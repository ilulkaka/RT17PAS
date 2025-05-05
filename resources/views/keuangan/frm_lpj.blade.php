@extends('layout.app')
@section('plugins.Select2', true)

{{-- Customize layout sections --}}

@section('content_header_title', 'List Warga')
@section('content_body')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="card-title">Form Entry Laporan Keuangan</h4>
                </div>
                <form id="frm_felk">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <strong>Tgl Transaksi</strong>
                                <input type="date" class="form-control rounded-0" name="tgl_transaksi" id="tgl_transaksi"
                                    required>
                            </div>
                            {{-- <div class="form-group col-md-12">
                                <strong>No KTP</strong>
                                <input type="text" class="form-control rounded-0" name="no_ktp" id="no_ktp"
                                    placeholder="Nomer Kartu Tanda Penduduk" required pattern="\d+"
                                    title="Hanya angka yang diperbolehkan">
                            </div> --}}
                            <div class="form-group col-md-12">
                                <strong>Deskripsi</strong>
                                <input class="form-control rounded-0" list="list_deskripsi" id="deskripsi" name="deskripsi"
                                    placeholder="Ketik atau pilih...">
                                <datalist id="list_deskripsi">
                                    <option value="Iuran Warga">
                                    <option value="Gaji Penarik Sampah">
                                    <option value="Ongkos Potong Rumput">
                                </datalist>
                            </div>

                            <div class="form-group col-md-12">
                                <strong>Jenis</strong>
                                <select name="jenis" id="jenis" class="form-control rounded-0" required>
                                    <option value="">Pilih...</option>
                                    <option value="Masuk">Masuk</option>
                                    <option value="Keluar">Keluar</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Nominal</strong>
                                <input type="number" class="form-control rounded-0" name="nominal" id="nominal" required>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>PIC</strong>
                                <input type="text" class="form-control rounded-0" name="pic" id="pic">
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Keterangan</strong>
                                <textarea name="keterangan" id="keterangan" cols="60" rows="2" class="form-control rounded-0"
                                    placeholder="Isi keterangan jika ada."></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-between gap-2">
                        <button type="button" class="btn btn-keluar col-md-6" id="btn_list_lpj">List Keuangan</button>
                        <button type="submit" class="btn btn-update btn-flat col-md-6" id="btn_submit">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/frm_lpj.js') }}"></script>
@endpush
