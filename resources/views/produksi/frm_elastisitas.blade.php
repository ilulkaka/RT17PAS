@section('plugins.Select2', true)
@extends('layout.app')

{{-- Customize layout sections --}}

@section('content_header_title', 'Report Produksi')
@section('content_body')
    {{-- <div class="row">
        <div class="col col-md-2">
            <button type="button" class="btn btn-outline" id="btn_insert_microstruktur" name="btn_insert_microstruktur"><u
                    style="color: blue">
                    Add Microstructure</u></button>
        </div>
    </div> --}}
    <div class="card">
        <div class="card-header card-color-list">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="card-title"><b style="color: brown; font-size:30px">Elastisitas</b> </h1>
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
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-cari w-100" id="btn_reload">
                        <i class="fa fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </div>
        <hr style="margin-top: 0%">
        <div class="card-body table-responsive p-0" style="margin-top: -1%">
            <table class="table table-bordered table-hover table-striped text-nowrap" id="tb_elastisitas">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Barcode<br>No</th>
                        <th>Tgl Proses</th>
                        <th>Leadle<br>No</th>
                        <th>Periksa</th>
                        <th>Ketetapan</th>
                        <th>Part No</th>
                        <th>Lot No</th>
                        <th>Cast No</th>
                        <th>Mohan <br> Camu</th>
                        <th>E1</th>
                        <th>E2</th>
                        <th>E3</th>
                        <th>E4</th>
                        <th>E5</th>
                        <th>AVG (E)</th>
                        <th>H1</th>
                        <th>H2</th>
                        <th>H3</th>
                        <th>H4</th>
                        <th>Mikrostruktur</th>
                        <th>Ka1</th>
                        <th>Ka2</th>
                        <th>Ringkasan</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                        <th>Row</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-secondary btn-flat" id="btn-print" disabled>Print PDF</button>
        <button type="button" class="btn btn-success btn-flat" id="btn-excel" disabled>Download Excel</button>
    </div>
    </div>

    <!-- Modal Insert Microstruktur (IM) -->
    <div class="modal fade" id="modal_insert_microstruktur" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="exampleModalLongTitle"><b>Insert Mikrostruktur</b> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_im">
                        @csrf
                        <div class="row">
                            <div class="col col-md-6">
                                <strong><i class="fas fa-quote-left"> Scan Barcode</i></strong>
                                <input type="text" id="im_scan_barcode" name="im_scan_barcode"
                                    class="form-control rounded-0">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col col-md-4">
                                <strong> Part No</strong>
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
                                <input type="text" id="im_leadle_no" name="im_leadle_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                        </div>
                        <p></p>
                        <div class="row">
                            <div class="col col-md-2">
                                <strong> Mikrostruktur</strong>
                                <input type="text" id="im_mikrostruktur" name="im_mikrostruktur"
                                    class="form-control rounded-0">
                            </div>
                            <div class="col col-md-2">
                                <strong> Ketetapan</strong>
                                <select name="im_ketetapan" id="im_ketetapan" class="form-control rounded-0">
                                    <option value="">Please Choose...</option>
                                    <option value="OK">OK</option>
                                    <option value="NG">NG</option>
                                </select>
                            </div>
                            <div class="col col-md-8">
                                <strong><i class="fas fa-bookmark"> Keterangan</i></strong>
                                <textarea name="im_keterangan" id="im_keterangan" cols="120" rows="2" class="form-control rounded-0"></textarea>
                            </div>
                        </div>
                        <p>

                        </p>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary btn-flat" id="btn_save_im">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Hardness (UH) -->
    <div class="modal fade" id="modal_update_hardness" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="exampleModalLongTitle"><b>Update Hardness</b> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="frm_uh">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-md-7">
                                <strong> Part No</strong>
                                <input type="hidden" name="uh_id" id="uh_id">
                                <input type="text" id="uh_part_no" name="uh_part_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-5">
                                <strong> Lot No</strong>
                                <input type="text" id="uh_lot_no" name="uh_lot_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-3">
                                <strong> Hardness 1</strong>
                                <input type="number" id="uh_h1" name="uh_h1" class="form-control rounded-0"
                                    required>
                            </div>
                            <div class="col col-md-3">
                                <strong> Hardness 2</strong>
                                <input type="number" id="uh_h2" name="uh_h2" class="form-control rounded-0"
                                    required>
                            </div>
                            <div class="col col-md-3">
                                <strong> Hardness 3</strong>
                                <input type="number" id="uh_h3" name="uh_h3" class="form-control rounded-0"
                                    required>
                            </div>
                            <div class="col col-md-3">
                                <strong> Hardness 4</strong>
                                <input type="number" id="uh_h4" name="uh_h4" class="form-control rounded-0"
                                    required>
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

    <!-- Modal Edit Hardness (EH) -->
    <div class="modal fade" id="modal_edit_hardness" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="exampleModalLongTitle"><b>Edit Hardness</b> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="frm_eh">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-md-7">
                                <strong> Part No</strong>
                                <input type="hidden" name="eh_id" id="eh_id">
                                <input type="text" id="eh_part_no" name="eh_part_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-5">
                                <strong> Lot No</strong>
                                <input type="text" id="eh_lot_no" name="eh_lot_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col col-md-3">
                                <strong> Hardness 1</strong>
                                <input type="number" id="eh_h1" name="eh_h1" class="form-control rounded-0">
                            </div>
                            <div class="col col-md-3">
                                <strong> Hardness 2</strong>
                                <input type="number" id="eh_h2" name="eh_h2" class="form-control rounded-0">
                            </div>
                            <div class="col col-md-3">
                                <strong> Hardness 3</strong>
                                <input type="number" id="eh_h3" name="eh_h3" class="form-control rounded-0">
                            </div>
                            <div class="col col-md-3">
                                <strong> Hardness 4</strong>
                                <input type="number" id="eh_h4" name="eh_h4" class="form-control rounded-0">
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

    <!-- Modal Update Elastisitas (UE) -->
    <div class="modal fade" id="modal_update_elastisitas" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="exampleModalLongTitle"><b>Update Elastisitas</b> </h4>
                </div>
                <form id="frm_ue">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-md-6">
                                <strong> Part No</strong>
                                <input type="hidden" name="ue_id" id="ue_id">
                                <input type="text" id="ue_part_no" name="ue_part_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-4">
                                <strong> Lot No</strong>
                                <input type="text" id="ue_lot_no" name="ue_lot_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-2">
                                <strong> Diameter</strong>
                                <input type="text" id="ue_diameter" name="ue_diameter" class="form-control rounded-0"
                                    readonly>
                            </div>
                        </div>
                        <p></p>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px"></th>
                                    <th style="width: 250px; text-align:center">1</th>
                                    <th style="width: 250px; text-align:center">2</th>
                                    <th style="width: 250px; text-align:center">3</th>
                                    <th style="width: 250px; text-align:center">4</th>
                                    <th style="width: 250px; text-align:center">5</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b> B </b></td>
                                    <td><input type="number" name="ue_b1" id="ue_b1"
                                            class="form-control rounded-0" step="0.001" required></td>
                                    <td><input type="number" name="ue_b2" id="ue_b2"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_b3" id="ue_b3"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_b4" id="ue_b4"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_b5" id="ue_b5"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b> T </b></td>
                                    <td><input type="number" name="ue_t1" id="ue_t1"
                                            class="form-control rounded-0" step="0.001" required></td>
                                    <td><input type="number" name="ue_t2" id="ue_t2"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_t3" id="ue_t3"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_t4" id="ue_t4"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_t5" id="ue_t5"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b> W </b></td>
                                    <td><input type="number" name="ue_w1" id="ue_w1"
                                            class="form-control rounded-0" step="0.001" required></td>
                                    <td><input type="number" name="ue_w2" id="ue_w2"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_w3" id="ue_w3"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_w4" id="ue_w4"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_w5" id="ue_w5"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b> E </b></td>
                                    <td><input type="number" name="ue_e1" id="ue_e1"
                                            class="form-control rounded-0" step="0.001" readonly></td>
                                    <td><input type="number" name="ue_e2" id="ue_e2"
                                            class="form-control rounded-0" step="0.001" readonly></td>
                                    <td><input type="number" name="ue_e3" id="ue_e3"
                                            class="form-control rounded-0" step="0.001" readonly></td>
                                    <td><input type="number" name="ue_e4" id="ue_e4"
                                            class="form-control rounded-0" step="0.001" readonly> </td>
                                    <td><input type="number" name="ue_e5" id="ue_e5"
                                            class="form-control rounded-0" step="0.001" readonly> </td>
                                </tr>

                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                                <tr>

                                    <th style="width: 250px; text-align:center">S</th>
                                    <th style="width: 250px; text-align:center">Kakucho 1</th>
                                    <th style="width: 250px; text-align:center">Kakucho 2</th>
                                    <th style="width: 250px; text-align:center">AVG Elastisitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td><input type="number" name="ue_s" id="ue_s"
                                            class="form-control rounded-0" step="0.001" required></td>
                                    <td><input type="number" name="ue_kak1" id="ue_kak1"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_kak2" id="ue_kak2"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ue_avg" id="ue_avg"
                                            class="form-control rounded-0" step="0.001" readonly>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-keluar btn-flat" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-update btn-flat" id="btn_submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Elastisitas (EE) -->
    <div class="modal fade" id="modal_edit_elastisitas" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="exampleModalLongTitle"><b>Edit Elastisitas</b> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="frm_ee">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-md-6">
                                <strong> Part No</strong>
                                <input type="hidden" name="ee_id" id="ee_id">
                                <input type="text" id="ee_part_no" name="ee_part_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-4">
                                <strong> Lot No</strong>
                                <input type="text" id="ee_lot_no" name="ee_lot_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-2">
                                <strong> Diameter</strong>
                                <input type="text" id="ee_diameter" name="ee_diameter" class="form-control rounded-0"
                                    readonly>
                            </div>
                        </div>
                        <p></p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px"></th>
                                    <th style="width: 250px; text-align:center">1</th>
                                    <th style="width: 250px; text-align:center">2</th>
                                    <th style="width: 250px; text-align:center">3</th>
                                    <th style="width: 250px; text-align:center">4</th>
                                    <th style="width: 250px; text-align:center">5</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b> B </b></td>
                                    <td><input type="number" name="ee_b1" id="ee_b1"
                                            class="form-control rounded-0" step="0.001" required></td>
                                    <td><input type="number" name="ee_b2" id="ee_b2"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_b3" id="ee_b3"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_b4" id="ee_b4"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_b5" id="ee_b5"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b> T </b></td>
                                    <td><input type="number" name="ee_t1" id="ee_t1"
                                            class="form-control rounded-0" step="0.001" required></td>
                                    <td><input type="number" name="ee_t2" id="ee_t2"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_t3" id="ee_t3"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_t4" id="ee_t4"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_t5" id="ee_t5"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b> W </b></td>
                                    <td><input type="number" name="ee_w1" id="ee_w1"
                                            class="form-control rounded-0" step="0.001" required></td>
                                    <td><input type="number" name="ee_w2" id="ee_w2"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_w3" id="ee_w3"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_w4" id="ee_w4"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_w5" id="ee_w5"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b> E </b></td>
                                    <td><input type="number" name="ee_e1" id="ee_e1"
                                            class="form-control rounded-0" step="0.001" readonly></td>
                                    <td><input type="number" name="ee_e2" id="ee_e2"
                                            class="form-control rounded-0" step="0.001" readonly></td>
                                    <td><input type="number" name="ee_e3" id="ee_e3"
                                            class="form-control rounded-0" step="0.001" readonly></td>
                                    <td><input type="number" name="ee_e4" id="ee_e4"
                                            class="form-control rounded-0" step="0.001" readonly> </td>
                                    <td><input type="number" name="ee_e5" id="ee_e5"
                                            class="form-control rounded-0" step="0.001" readonly> </td>
                                </tr>

                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                                <tr>

                                    <th style="width: 250px; text-align:center">S</th>
                                    <th style="width: 250px; text-align:center">Kakucho 1</th>
                                    <th style="width: 250px; text-align:center">Kakucho 2</th>
                                    <th style="width: 250px; text-align:center">AVG Elastisitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td><input type="number" name="ee_s" id="ee_s"
                                            class="form-control rounded-0" step="0.001" required></td>
                                    <td><input type="number" name="ee_kak1" id="ee_kak1"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_kak2" id="ee_kak2"
                                            class="form-control rounded-0" step="0.001" required>
                                    </td>
                                    <td><input type="number" name="ee_avg" id="ee_avg"
                                            class="form-control rounded-0" step="0.001" readonly>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-keluar btn-flat" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-update btn-flat" id="btn_submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Update Mohan (uh) -->
    <div class="modal fade" id="modal_update_mohan" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="exampleModalLongTitle"><b>Update Mohan</b> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_um">
                        @csrf
                        <div class="row">
                            <div class="col col-md-7">
                                <strong> Part No</strong>
                                <input type="text" id="um_part_no" name="um_part_no" class="form-control rounded-0"
                                    readonly>
                            </div>
                            <div class="col col-md-5">
                                <strong> Mohan Camu</strong>
                                <input type="number" id="um_camu" name="um_camu" class="form-control rounded-0"
                                    step="0.001" required>
                            </div>
                        </div>
                        <p>

                        </p>
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
    <script src="{{ asset('js/frm_elastisitas.js') }}"></script>
@endpush
