@extends('layout.app')

{{-- Customize layout sections --}}


@section('content_header_title', 'Report Produksi')


{{-- Content body: main page content --}}

@section('content_body')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">PRODUCTION REPORT</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ url('/produksi/report_produksi/hasil_produksi') }}" class="text-decoration-none">
                                <div class="card-menu shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <!-- Icon Section -->
                                        <div class="me-3">
                                            <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="far fa-list-alt fa-2x"></i>
                                            </div>
                                        </div>
                                        <!-- Content Section -->
                                        <div>
                                            <p class="mb-0 text-dark">Detail</p>
                                            <h5 class="mb-0 text-dark text-muted">Hasil Produksi</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ url('produksi/report_produksi/approve_jam_operator') }}"
                                class="text-decoration-none">
                                <div class="card-menu shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <!-- Icon Section -->
                                        <div class="me-3">
                                            <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="far fa-clock fa-2x"></i>
                                            </div>
                                        </div>
                                        <!-- Content Section -->
                                        <div>
                                            <p class="mb-0 text-dark">Approve</p>
                                            <h5 class="mb-0 text-dark text-muted">Jam Kerja Operator</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ url('produksi/report_produksi/grafik_hasil_produksi') }}"
                                class="text-decoration-none">
                                <div class="card-menu shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <!-- Icon Section -->
                                        <div class="me-3">
                                            <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="fas fa-chart-bar fa-2x"></i>
                                            </div>
                                        </div>
                                        <!-- Content Section -->
                                        <div>
                                            <p class="mb-0 text-dark">Grafik Performance</p>
                                            <h5 class="mb-0 text-dark text-muted">Hasil Operator Produksi</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ url('produksi/report_produksi/rekap_produksiNsales') }}"
                                class="text-decoration-none">
                                <div class="card-menu shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <!-- Icon Section -->
                                        <div class="me-3">
                                            <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="fab fa-r-project fa-2x"></i>
                                            </div>
                                        </div>
                                        <!-- Content Section -->
                                        <div>
                                            <p class="mb-0 text-dark">Detail</p>
                                            <h5 class="mb-0 text-dark text-muted">Dashboard & MCFrame</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ url('produksi/report_produksi/list_shikakari') }}" class="text-decoration-none">
                                <div class="card-menu shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <!-- Icon Section -->
                                        <div class="me-3">
                                            <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="far fa-file-alt fa-2x"></i>
                                            </div>
                                        </div>
                                        <!-- Content Section -->
                                        <div>
                                            <p class="mb-0 text-dark">Detail</p>
                                            <h5 class="mb-0 text-dark text-muted">Shikakari by LOT</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                    <div class="clearfix hidden-md-up"></div>
                </div>
            </div>
        </div>

    </div>
@stop

{{-- Push extra CSS --}}


{{-- Push extra scripts --}}

@push('js')
@endpush
