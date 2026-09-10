@extends('layout.app')
@section('plugins.Select2', true)

{{-- Customize layout sections --}}

@section('content_header_title', 'Iuran Warga')
@section('content_body')

    {{-- <a href="#" id="add_iuran" name="add_iuran"><i class="fas fa-plus"> Add Iuran </i></a>
    <br> --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-color-list">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="card-title">List Iuran Warga</h3>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row align-items-end g-2">

                        <div class="col-lg-2 col-md-3 col-sm-3 mb-2">
                            <label><strong>Periode:</strong></label>
                            <select class="form-control" id="periode" name="periode">
                                @for ($i = date('Y'); $i >= 2024; $i--)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-3 mb-2">
                            <label><strong>BLOK:</strong></label>
                            <select class="form-control select2" id="selectblok" name="selectblok">
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-3 mb-2 d-flex">
                            <button class="btn btn-cari mr-2 w-100" id="btn_reload">
                                <i class="fa fa-search"></i>
                            </button>

                            {{-- <button class="btn btn-danger w-100 ml-2" id="btn_pdf" name="btn_pdf">
                                <i class="fas fa-file-pdf"></i>
                            </button> --}}
                        </div>

                    </div>
                </div>

                <hr style="margin-top: 0%">
                <div class="card-body table-responsive p-0" style="padding-top: -2%">
                    <table id="tb_list_iuran" class="table table-bordered table-hover table-striped nowrap"
                        style="width:100%">
                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>
@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/list_iuran_warga.js') }}"></script>
    <script src="{{ asset('js/selectblok_warga.js') }}"></script>
@endpush
