@extends('layout.app')
@section('plugins.Chartjs', true)
@section('content_header_title', 'Report Lembur')

@section('content_body')

    <div class="card shadow-sm p-2">
        <div class="col-md-12">
            <div class="row align-items-end gy-3" style="margin-left: 0%">

                <div class="col-md-2">
                    <input type="date" class="form-control" id="tgl_awal" value="{{ date('Y-m') . '-01' }}">
                </div>

                <div class="col-md-1" style="text-align: center">
                    <label for="">Sampai</label>
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                </div>

                <div class="col-md-1">
                    <button class="btn btn-cari w-100" id="btn_reload">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col col-md-12">
            <div class="card">
                <div class="card-header card-color-list">
                    <h3 class="card-title">Grafik Lembur</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="chartlembur"
                        style="min-height: 250px; height: 250px; max-height: 950px; max-width: 100%; display: block; width: 422px;"
                        width="422" height="250" class="chartjs-render-monitor"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-color-list">
                    <h3 class="card-title">Data Lembur</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="tb_lembur">
                        <thead>
                            <tr>
                                <th>Section</th>
                                <th>Total Jam</th>
                                <th>Quota Lembur</th>
                                <th>Selisih</th>
                                <th>Member</th>
                                <th>Quota / Memb</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-color-list">
                    <h3 id="judul_grafik" class="card-title" style="color: green"><b><u> Planning Lembur</u></b>
                    </h3>
                </div>

                <div class="card-body">
                    <input type="month" name="periode" id="periode" class="form-control" value="{{ date('Y-m') }}">

                    <table class="table table-bordered" id="tb_cek_lembur">
                        <thead>
                            <tr>
                                <th>Section</th>
                                <th>1st</th>
                                <th>2nd</th>
                                <th>Act</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>


    {{-- ============= Modal Detail Lembur ================== --}}
    <div class="modal fade bd-example-modal-lg" id="detail-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header card-color-list">
                    <h5 class="card-title" id="ModalLongTitle">Detail Lembur</h5>

                    <span id="tgl-awal"></span>
                    <span>Sampai</span>
                    <span id="tgl-akhir"></span>
                </div>
                <br>
                <div class="card-body table-responsive p-0" style="margin-top: -1%">
                    <table id="tb_detail_lembur" class="table table-bordered table-hover table-striped nowrap display"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Section</th>
                                <th>Jabatan</th>
                                <th>Total</th>

                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <div>
                        <button class="btn btn-excel" id="btn-excel" disabled>Excel</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-keluar" id="btn-close" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ============= Modal Planning Lembur ================== --}}
    <div class="modal fade" id="modal_pl" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header card-color-list">
                    <h5 class="card-title" id="exampleModalLongTitle">Entry Planning Lembur </h5>
                </div>
                <form id="frm_pl">
                    <div class="modal-body">

                        <div class="input-group mb-3">
                            <input type="text" style="text-align: center" class="form-control rounded-0 col-md-12"
                                name="n_dept" id="n_dept" readonly>
                            {{-- <input type="text" class="form-control col-md-4" name="pl_jenis" id="pl_jenis" readonly> --}}

                            <input type="hidden" id="pl_periode" name="pl_periode">
                            <input type="hidden" id="pl_kode" name="pl_kode">
                            <input type="hidden" id="pl_sect" name="pl_sect">
                            <input type="hidden" id="pl_dept" name="pl_dept">
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>1st <br>1~15</label>
                                <input type="number" class="form-control rounded-0" name="pl_awal" id="pl_awal"
                                    required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>2nd <br>16~Akhir bulan</label>
                                <input type="number" class="form-control rounded-0" name="pl_akhir" id="pl_akhir"
                                    required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Total <br> 1st + 2nd</label>
                                <input type="number" id="pl_total" name="pl_total"
                                    style="text-align: center; border: none; color: blue;" class="form-control" disabled>
                            </div>
                        </div>
                        {{-- <strong>Direct Or Indirect</strong>
                        <select name="pl_direct" id="pl_direct" class="form-control select2" required>
                            <option value="">Select...</option>
                            <option value="Direct">Direct</option>
                            <option value="Indirect">Indirect</option>
                        </select> --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-keluar btn-flat" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-update btn-flat" id="pl_simpan" name="pl_simpan"
                                value="Simpan">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- ========== Modal Edit Target Lembur (ETL) --}}
    <div class="modal fade" id="modal_etl" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header card-color-list">
                    <h5 class="card-title" id="exampleModalLongTitle">Edit Planning Lembur </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="frm_etl">
                    <div class="modal-body">

                        <div class="input-group mb-3">
                            <input type="text" style="text-align: center" class="form-control rounded-0 col-md-12"
                                name="etl_section" id="etl_section" readonly>

                            <input type="hidden" id="etl_id" name="etl_id">
                            {{-- <input type="hidden" id="etl_kode" name="etl_kode"> --}}
                            <input type="hidden" id="etl_dept" name="etl_dept">
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>1st <br>1~15</label>
                                <input type="number" class="form-control rounded-0" name="etl_awal" id="etl_awal"
                                    readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>2nd <br>16~Akhir bulan</label>
                                <input type="number" class="form-control rounded-0" name="etl_akhir" id="etl_akhir"
                                    readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Total <br> 1st + 2nd</label>
                                <input type="number" id="etl_total" name="etl_total"
                                    style="text-align: center; border: none; color: blue;" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <input type="number" class="form-control rounded-0" name="etl_temp1" id="etl_temp1"
                                    required>
                            </div>
                            <div class="form-group col-md-4">
                                <input type="number" class="form-control rounded-0" name="etl_temp2" id="etl_temp2"
                                    required>
                            </div>
                            <div class="form-group col-md-4">
                                <input type="number" id="etl_total_temp" name="etl_total_temp"
                                    style="text-align: center; border: none; color: blue;" class="form-control" disabled>
                            </div>
                        </div>
                        <label for="" style="color: red; font-weight:bold; text-decoration:underline"
                            id="l_status" name="l_status"></label>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-keluar btn-flat" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-update btn-flat" id="etl_simpan" name="etl_simpan"
                                value="Simpan">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop


{{-- Push extra scripts --}}

@push('js')
    <script src="{{ asset('js/report_lembur.js') }}"></script>
@endpush
