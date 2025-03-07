@extends('layout.app')

{{-- Customize layout sections --}}


@section('content_header_title', 'Request Jigu / Part')


{{-- Content body: main page content --}}

@section('content_body')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">Request Jigu</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ url('undermaintenance') }}" class="text-decoration-none">
                                <div class="card-menu shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <!-- Icon Section -->
                                        <div class="me-3">
                                            <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="fas fa-drum-steelpan fa-2x"></i>
                                            </div>
                                        </div>
                                        <!-- Content Section -->
                                        <div>
                                            <p class="mb-0 text-dark">Jigu</p>
                                            <h5 class="mb-0 text-dark text-muted">Request</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ url('produksi/menu_request_jigu/frm_repair_jigu') }}" class="text-decoration-none">
                                <div class="card-menu shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <!-- Icon Section -->
                                        <div class="me-3">
                                            <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="fas fa-drum-steelpan fa-2x"></i>
                                            </div>
                                        </div>
                                        <!-- Content Section -->
                                        <div>
                                            <p class="mb-0 text-dark">Part</p>
                                            <h5 class="mb-0 text-dark text-muted">Repair Jigu</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">Data Jigu</h5>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ url('undermaintenance') }}" class="text-decoration-none">
                                <div class="card-menu shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <!-- Icon Section -->
                                        <div class="me-3">
                                            <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="far fa-registered fa-2x"></i>
                                            </div>
                                        </div>
                                        <!-- Content Section -->
                                        <div>
                                            <p class="mb-0 text-dark">Register</p>
                                            <h5 class="mb-0 text-dark text-muted">No Induk</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ url('undermaintenance') }}" class="text-decoration-none">
                                <div class="card-menu shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <!-- Icon Section -->
                                        <div class="me-3">
                                            <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="fas fa-crutch fa-2x"></i>
                                            </div>
                                        </div>
                                        <!-- Content Section -->
                                        <div>
                                            <p class="mb-0 text-dark">Jigu</p>
                                            <h5 class="mb-0 text-dark text-muted">Daichou</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title ">Measurement</h5>
                </div>

                <div class="card-body">
                    <div class="col-md-6">
                        <a href="{{ url('produksi/menu_request_jigu/list_measurement') }}" class="text-decoration-none">
                            <div class="card-menu shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <!-- Icon Section -->
                                    <div class="me-3">
                                        <div class="rounded-circle bg-indigo text-white d-flex justify-content-center align-items-center"
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-ruler-combined fa-2x"></i>
                                        </div>
                                    </div>
                                    <!-- Content Section -->
                                    <div>
                                        <p class="mb-0 text-dark">Measurement</p>
                                        <h5 class="mb-0 text-dark text-muted">Datas</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

{{-- Push extra scripts --}}

@push('js')
@endpush
