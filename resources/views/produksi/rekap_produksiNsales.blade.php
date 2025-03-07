@section('plugins.Select2', true)
@section('plugins.daterangepicker', true)
@section('plugins.moment', true)
@extends('layout.app')
@section('content_header_title', 'Report Produksi')

@section('content_body')
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header card-color-list">
                    <h3 class="card-title">Target {{ Date('F Y') }}
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th style="font-size: 18px">Desc</th>
                                <th style="font-size: 18px">Periode</th>
                                <th style="font-size: 18px">Target</th>
                                <th style="font-size: 18px">Actual</th>
                                <th style="font-size: 18px">Diff</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="desc_produksi"></td>
                                <td>{{ date('M-Y') }}</td>
                                <td id="target_produksi"></td>
                                <td id="aktual_produksi"></td>
                                <td id="diff_produksi"></td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td id="desc_sales"></td>
                                <td>{{ date('M-Y') }}</td>
                                <td id="target_sales"></td>
                                <td id="aktual_sales"></td>
                                <td id="diff_sales"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row">

        <div class="col-md-7">
            <div class="card card-primary card-outline">
                <div class="card-header card-color-list">
                    <h3 class="card-title">Hasil Produksi </h3>
                </div>


                <!-- /.card-header -->
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            {{-- <div class="form-group"> --}}
                            <strong>Date range:</strong>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right" id="daterange" name="daterange"
                                    required>
                                <button type="button" class="form-control btn-cari col-md-3 btn-flat" name="btn_find"
                                    id="btn_find"><i class='fa fa-search'> Find</i></button>
                            </div>
                            <!-- /.input group -->
                            {{-- </div> --}}
                        </div>
                    </div>
                    <br>
                    <table class="table table-bordered" id="tb_hasil_produksi">
                        <thead>
                            <tr>
                                {{-- <th>Kode Proses</th> --}}
                                <th style="font-size: 18px">Nama Proses</th>
                                <th style="font-size: 18px">Qty</th>
                                <th style="font-size: 18px">Target</th>
                                <th style="font-size: 18px">Diff</th>
                            </tr>
                        </thead>
                        <tbody id="rekapHasilProduksi">

                        </tbody>
                        {{-- <tfoot id="rekapHasilProduksi">

                        </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header card-color-list">
                    <h3 class="card-title">Hasil Grouping </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Date range:</strong>
                            <div class="input-group d-flex">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control col-md-8" id="daterangeGrouping"
                                    name="daterangeGrouping" required>
                                <select name="selectline" id="selectline" class="form-control col-md-2 select2" required>
                                </select>
                                <button type="button" class="form-control btn-cari col-md-2 btn-flat"
                                    name="btn_findGrouping" id="btn_findGrouping">
                                    <i class='fa fa-search'> Find</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- /.card-header -->
                    <table class="table table-bordered" id="tb_grouping">
                        <thead>
                            <tr>
                                {{-- <th>Kode Proses</th> --}}
                                <th style="font-size: 18px">Grouping</th>
                                <th style="font-size: 18px">Qty</th>
                                <th style="font-size: 18px">Target</th>
                                <th style="font-size: 18px">Diff</th>
                            </tr>
                        </thead>
                        <tbody id="rekapHasilProduksiGrouping">

                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- /.card -->
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header card-color-list">
                    <h3 class="card-title">Hasil Sales per <b>{{ '01-' . Date('M-Y') }}</b> Sampai
                        <b>{{ Date('d-M-Y') }}</b>
                    </h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th style="font-size: 18px">Customer</th>
                                <th style="font-size: 18px">Qty</th>
                                <th style="font-size: 18px">Uom</th>
                                <th style="font-size: 18px">Amount</th>
                                <th style="font-size: 18px">Curr</th>
                            </tr>
                        </thead>
                        <tbody id="hasil_allSales">

                        </tbody>
                        {{-- <tbody>
                            @foreach ($salesproduksi ?? '' as $sales)
                                <tr>
                                    <td>{{ $sales->ofcl_nm }}</td>
                                    <td>{{ number_format($sales->qty, 0) }}</td>
                                    <td>{{ $sales->unit_cd }}</td>
                                    <td>{{ number_format($sales->amt, 0) }}</td>
                                    <td>{{ $sales->cur_cd }}</td>
                                </tr>
                            @endforeach
                        </tbody> --}}
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header card-color-list">
                    <h3 class="card-title">Group By Currency per <b>{{ '01-' . Date('M-Y') }}</b>
                        Sampai
                        <b>{{ Date('d-M-Y') }}</b>
                    </h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th style="font-size: 18px">Curr</th>
                                <th style="font-size: 18px">Qty</th>
                                <th style="font-size: 18px">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="hasil_amount">

                        </tbody>
                        {{-- <tbody>
                            @foreach ($totalamount ?? '' as $salesamt)
                                <tr>
                                    <td>{{ $salesamt->cur_cd }}</td>
                                    <td>{{ number_format($salesamt->qty, 0) }}</td>
                                    <td>{{ number_format($salesamt->amt, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody> --}}
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
    <script src="{{ asset('js/rekap_produksiNsales.js') }}"></script>
@endpush
