@extends('layout.app')

{{-- Customize layout sections --}}

@section('content_header_title', 'Report Produksi')
@section('content_body')


    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">FOUNDRY Application Buttons</h3>
        </div>
        <div class="card-body">
            <p>Add the classes <code>.btn.btn-app</code> to an <code>&lt;a&gt;</code> tag to achieve the following:</p>
            <a href="#" class="btn btn-app" id="btn_entry_pouring" style="color: black; font-size:14px">
                <i class="fas fa-snowflake"></i> Entry Hasil Pouring
            </a>
            <a href="#" class="btn btn-app" id="btn_checksheet_elastisitas" style="color: black; font-size:14px">
                <i class="fas fa-cookie-bite"></i> Checksheet Elastisitas
            </a>
            <a href="#" class="btn btn-app" id="btn_permintaan_sleeve" style="color: black; font-size:14px"><span
                    class="badge badge-danger" id="m_notif_sleeve" name="m_notif_sleeve"></span><i class="fas fa-bullseye">
                </i> Sleeve Request
            </a>
            <a href="#" class="btn btn-app" id="btn_komposisi" style="color: black; font-size:14px"><i
                    class="fas fa-braille">
                </i> Komposisi
            </a>
            <a href="#" class="btn btn-app" style="color: black; font-size:14px">
                <!--<span class="badge bg-warning">3</span>-->
            </a>
            <a href="#" class="btn btn-app" style="color: black; font-size:14px">
            </a>
            <a href="#" class="btn btn-app" style="color: black; font-size:14px">
                <!--<span class="badge bg-success">300</span>-->
            </a>
            <a href="#" class="btn btn-app" style="color: black; font-size:14px">
                <!--<span class="badge bg-success">300</span>-->
            </a>
            <a href="#" class="btn btn-app" style="color: black; font-size:14px">
                <!--<span class="badge bg-purple">891</span>-->
            </a>
            <a href="#" class="btn btn-app" style="color: black; font-size:14px">
                <!--<span class="badge bg-purple">891</span>-->
            </a>
            <a href="#" class="btn btn-app" style="color: black; font-size:14px">
                <!--<span class="badge bg-teal">67</span>-->

            </a>
        </div>
        <!-- /.card-body -->
    </div>
@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/frm_foundry.js') }}"></script>
@endpush
