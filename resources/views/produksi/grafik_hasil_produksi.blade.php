@section('plugins.Select2', true)
@section('plugins.Chartjs', true)
@extends('layout.app')
@section('content_header_title', 'Report Produksi')

@section('content_body')

    <div class="card shadow-sm p-2">
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

            <div class="col-md-2">
                <select name="selectline" id="selectline" class="form-control select2" required>
                    <option value="" disabled selected>Pilih Line</option>
                </select>
            </div>

            <div class="col-md-1">
                <button class="btn btn-cari w-100" id="btn_reload">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Finish Qty</h3>

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
                    <canvas id="chartpie"
                        style="min-height: 250px; height: 250px; max-height: 950px; max-width: 100%; display: block; width: 422px;"
                        width="422" height="250" class="chartjs-render-monitor"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">* / Jam</h3>

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
                    <canvas id="chartpie1"
                        style="min-height: 250px; height: 250px; max-height: 950px; max-width: 100%; display: block; width: 422px;"
                        width="422" height="250" class="chartjs-render-monitor"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header ">
                    <div class="d-flex justify-content-between">
                        <h3 id="judul_grafik" class="card-title">Detail Hasil Jam</h3>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered" id="tb_hasil_produksi">
                        <thead>
                            <tr>
                                <th>Operator</th>
                                <th>Qty Finish</th>
                                <th>Jam Kerja</th>
                                <th>Pcs /Jam</th>
                                <th>Cycle Total</th>
                                <th>Cycle /Jam</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="1" style="text-align:center; font-size: large;">T O T A L</th>
                                <th style="text-align:center; font-size: large;"></th>
                                <th style="text-align:center; font-size: large;"></th>
                                <th style="text-align:center; font-size: large;"></th>
                                <th style="text-align:center; font-size: large;"></th>
                                <th style="text-align:center; font-size: large;"></th>

                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 id="judul_grafik_1" class="card-title">Detail Hasil FCR</h3>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="tb_hasil_fcr">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>F-Mono</th>
                                <th>Cr</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="1" style="text-align:center; ">TOTAL</th>
                                <th style="text-align:center; font-size: large;"></th>
                                <th style="text-align:center; font-size: large;"></th>
                                <th style="text-align:center; font-size: large;"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop


{{-- Push extra CSS --}}
@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/selectline.js') }}"></script>
    <script src="{{ asset('js/grafik_hasil_produksi.js') }}"></script>
@endpush
