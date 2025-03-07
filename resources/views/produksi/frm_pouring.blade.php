@section('plugins.Select2', true)
@extends('layout.app')

{{-- Customize layout sections --}}

@section('content_header_title', 'Report Produksi')
@section('content_body')

    <div class="row">
        <div class="col-md-4">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="card-title" id="exampleModalLongTitle"><b> Form Entry Hasil Pouring</b> </h5>
                </div>
                <form id="frm_fehp">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <strong for="sel">NIK</strong>
                                <select name="select_nik_casting" id="select_nik_casting"
                                    class="form-control select2 @error('nik') is-invalid @enderror rounded-0"
                                    style="width: 100%;" required>
                                    <option value="">NIK</option>
                                </select>
                            </div>
                            <div class="form-group col-md-8">
                                <strong for="nama">Nama Operator</strong>
                                <input type="hidden" class="form-control @error('nama')is-invalid @enderror"
                                    name="nama_operator" id="nama_operator" placeholder="Nama Operator" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col col-md-12">
                                <strong class="col-md-1">Scan Barcode</strong>
                                <input type="text" class="form-control  rounded-0" name="fehp_sb" id="fehp_sb"
                                    placeholder="Scan Barcode" required>
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <div class="col-md-4">
                                <strong class="col-md-1">No.Cast</strong>
                                <input type="text" class="form-control  rounded-0" name="fehp_nocas" id="fehp_nocas"
                                    placeholder="Nomer Casting" required>
                            </div>
                            <div class="col-md-4">
                                <strong class="col-md-1">Part No</strong>
                                <input type="text" class="form-control  rounded-0" name="fehp_part" id="fehp_part"
                                    placeholder="Part No" readonly>
                            </div>
                            <div class="col-md-4">
                                <strong class="col-md-1">Lot No</strong>
                                <input type="text" class="form-control  rounded-0" name="fehp_lot" id="fehp_lot"
                                    placeholder="Lot No" readonly>
                            </div>
                        </div>
                        <p></p>
                        <div class="form-row">
                            <div class="col col-md-3">
                                <strong class="col-md-1">Leadle No</strong>
                                <input type="number" class="form-control  rounded-0" name="fehp_leadle" id="fehp_leadle"
                                    placeholder="" required>
                            </div>
                            <div class="col col-md-3">
                                <strong class="col-md-1">Mesin No</strong>
                                <input type="number" class="form-control  rounded-0" name="fehp_mesin" id="fehp_mesin"
                                    placeholder="" required>
                            </div>
                            <div class="col col-md-3">
                                <strong class="col-md-1">Yama</strong>
                                <input type="number" class="form-control  rounded-0" name="fehp_yama" id="fehp_yama"
                                    placeholder="" required>
                            </div>
                            <div class="col col-md-3">
                                <strong class="col-md-1">Defect</strong>
                                <input type="number" class="form-control  rounded-0" name="fehp_defect" id="fehp_defect"
                                    placeholder="" required>
                            </div>
                        </div>
                        <p></p>
                        <div class="row">
                            {{-- Keterangan --}}
                            <div class="col col-md-3">
                                <strong class="col-md-1">Ringkasan</strong>
                                <select name="fehp_ket" id="fehp_ket" class="form-control select2 rounded-0" required>
                                    <option value="">Pilih...</option>
                                    <option value="E.H">E.H</option>
                                    <option value="E">E</option>
                                    <option value="H">H</option>
                                </select>
                            </div>
                            <div class="col col-md-9">
                                <strong class="col-md-1">Keterangan</strong>
                                <textarea name="fehp_ket1" id="fehp_ket1" class="form-control" cols="30" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-between">
                                <button type="button" class="form-control rounded-0 col-md-3" id=""
                                    name=""><b style="color: blue"> </b></button>
                                <button type="button" class="form-control rounded-0 col-md-3" id=""
                                    name=""><b style="color: blue"> </b></button>
                                <button type="button" class="form-control rounded-0 col-md-3" id=""
                                    name=""><b style="color: blue"></b></button>
                                <button type="submit" class="form-control btn-update rounded-0 col-md-3" id="btn_update"
                                    name="btn_update"><b>Update</b></button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>


        {{-- ====== Inquery ====== --}}
        <div class="col-md-8">
            <div class="card card-success card-outline">
                <div class="card-header card-color-list">
                    <h5 class="card-title card-color" id="exampleModalLongTitle"><b> Inquery Hasil Pouring</b> </h5>
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
                        <div class="col col-md-1">
                            <strong>Reload</strong>
                            <button class="btn btn-cari btn-flat" id="btn_reload" name="btn_reload"><i
                                    class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <hr style="margin-top: 0%">
                <div class="card-body table-responsive p-0" style="margin-top: -1%">
                    <table class="table  table-hover  table-striped nowrap" style="width:100%" id="tb_hasil_pouring">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Mikrostruktur</th>
                                <th>Barcode No</th>
                                <th>Tgl Proses</th>
                                <th>Time</th>
                                <th>Part No</th>
                                <th>Lot No</th>
                                <th>Casting No</th>
                                <th>Leadle</th>
                                <th>Mesin</th>
                                <th>Yama</th>
                                <th style="color:red">Defect</th>
                                <th>Ringkasan</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="card-footer">
                    <button class="btn btn-excel" id="btn_excel"><i class="fas fa-file-excel"></i>
                        Excel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pouring (EP) -->
    <div class="modal fade" id="modal_edit_pouring" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title" id="exampleModalLongTitle"><b>Edit Hasil Pouring</b> </h4>
                </div>
                <form id="frm_ep">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-md-6">
                                <strong><i class="fas fa-file-prescription"> Part No</i></strong>
                                <input type="hidden" id="ed_id_pouring" name="ed_id_pouring"
                                    class="form-control rounded-0">
                                <input type="text" id="ed_partno" name="ed_partno" class="form-control rounded-0"
                                    readonly>
                                <p>
                                </p>
                                <strong padding-top="20%"><i class="fas fa-file-signature"> Cast No</i>
                                </strong>
                                <input type="text" id="ed_castno" name="ed_castno"
                                    class="form-control rounded-0 col-md-12">
                            </div>
                            <div class="col col-md-6">
                                <strong><i class="fas fa-location-arrow"> Lot No</i></strong>
                                <input type="text" id="ed_lotno" name="ed_lotno" class="form-control rounded-0"
                                    readonly>
                            </div>
                        </div>
                        </p>
                        <hr style="color: blue; background-color:blue">
                        <div class="row">
                            <div class="col col-md-3">
                                <strong>Leadle No</strong>
                                <input type="Number" id="ed_leadleno" name="ed_leadleno"
                                    class="form-control rounded-0">
                            </div>
                            <div class="col col-md-3">
                                <strong>Mesin No</strong>
                                <input type="Number" id="ed_mesinno" name="ed_mesinno" class="form-control rounded-0">
                            </div>
                            <div class="col col-md-3">
                                <strong>Yama</strong>
                                <input type="Number" id="ed_yama" name="ed_yama" class="form-control rounded-0">
                            </div>
                            <div class="col col-md-3">
                                <strong style="color: red">Defect</strong>
                                <input type="Number" id="ed_defect" name="ed_defect" class="form-control rounded-0"
                                    style="color: red; border-color:red">
                            </div>
                        </div>
                        <hr style="color: blue; background-color:blue">
                        {{-- Keterangan --}}
                        <div class="row">
                            <div class="col col-md-3">
                                <strong> Ringkasan</strong>
                                <select name="ed_keterangan" id="ed_keterangan" class="form-control rounded-0">
                                    <option value="">Pilih ...</option>
                                    <option value="E.H">E.H</option>
                                    <option value="E">E</option>
                                    <option value="H">H</option>
                                </select>
                            </div>
                            <div class="col col-md-9">
                                <strong><i class="fas fa-bookmark"> Keterangan</i></strong>
                                <textarea name="ed_keterangan1" id="ed_keterangan1" cols="120" rows="2" class="form-control rounded-0"></textarea>
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

    <!-- Modal Insert Microstruktur (IM) -->
    <div class="modal fade" id="modal_insert_mikrostruktur" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="exampleModalLongTitle"><b>Insert Mikrostruktur</b> </h4>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                </div>
                <div class="modal-body">
                    <form id="frm_im">
                        @csrf
                        <input type="hidden" name="im_opr" id="im_opr">
                        <input type="hidden" name="im_ringkasan" id="im_ringkasan">
                        <div class="row">
                            <div class="col col-md-6">
                                <strong><i class="fas fa-quote-left"> Scan Barcode</i></strong>
                                <input type="text" id="im_scan_barcode" name="im_scan_barcode"
                                    class="form-control rounded-0" readonly>
                            </div>
                            <div class="col col-md-2">
                                <strong><i class="fas fa-quote-left"> Tgl Proses</i></strong>
                                <input type="text" id="im_tgl_proses" name="im_tgl_proses"
                                    class="form-control rounded-0" readonly>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col col-md-4">
                                <strong> Part No</strong>
                                <input type="hidden" name="im_id" id="im_id">
                                <input type="text" id="im_part_no" name="im_part_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-3">
                                <strong> Lot No</strong>
                                <input type="text" id="im_lot_no" name="im_lot_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-3">
                                <strong> Cast No</strong>
                                <input type="text" id="im_cast_no" name="im_cast_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-2">
                                <strong> Leadle No</strong>
                                <input type="text" id="im_leadle_no" name="im_leadle_no"
                                    class="form-control rounded-0" readonly>
                            </div>
                        </div>
                        <p></p>
                        <div class="row">
                            <div class="col col-md-2">
                                <strong> Mikrostruktur</strong>
                                <input type="text" id="im_mikrostruktur" name="im_mikrostruktur"
                                    class="form-control rounded-0" required>
                            </div>
                            <div class="col col-md-2">
                                <strong> Ketetapan</strong>
                                <select name="im_ketetapan" id="im_ketetapan" class="form-control select2 rounded-0"
                                    required>
                                    <option value="">Pilih...</option>
                                    <option value="OK">OK</option>
                                    <option value="NG">NG</option>
                                </select>
                            </div>
                            <div class="col col-md-8">
                                <strong><i class="fas fa-bookmark"> Keterangan</i></strong>
                                <textarea name="im_keterangan" id="im_keterangan" cols="120" rows="2" class="form-control rounded-0"
                                    readonly></textarea>
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
    <script src="{{ asset('js/frm_pouring.js') }}"></script>
    <script src="{{ asset('js/opr_casting.js') }}"></script>
@endpush
