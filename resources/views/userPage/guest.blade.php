@extends('layout.app_guest')
@section('plugins.Select2', true)

{{-- Customize layout sections --}}

@section('content_header_title', 'Dashboard')
@section('content_body')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="saldo">0</h3>
                    <p>Saldo Akhir</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                {{-- <a href="{{ url('keuangan/rpt/list_lpj') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a> --}}
                <a href="{{ url('undermaintenance') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="pemasukan_bulan_ini">0</h3>
                    <p>Pemasukan bulan ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-luggage-cart"></i>
                </div>
                <a href="{{ url('undermaintenance') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="pengeluaran_bulan_ini">0</h3>
                    <p>Pengeluaran bulan ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-percent"></i>
                </div>
                <a href="{{ url('undermaintenance') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 id="warga_terdaftar">0</h3>
                    <p>Total Warga terdaftar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                {{-- <a href="{{ url('datas/list_warga') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a> --}}
                <a href="{{ url('undermaintenance') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Marquee -->
    <div class="col-md-12" style="margin-top: -15px">
        <marquee id="marqueeExpres" behavior="scroll" direction="left">
            <b>RT 17 PAS</b>
        </marquee>
    </div>

    <!-- Button List Iuran Warga -->
    <div class="row">
        <div class="col-md-6">
            <a href="{{ url('guest/list_iuran_warga') }}" class="btn btn-secondary btn-block rounded-0">
                <i class="fas fa-money-bill-wave mr-2"></i>
                List Iuran Warga
            </a>
        </div>
    </div>

@stop

{{-- Push extra CSS --}}
@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush


{{-- Push extra scripts --}}

@push('js')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
