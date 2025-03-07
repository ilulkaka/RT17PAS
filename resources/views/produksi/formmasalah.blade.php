@extends('layout.app')

{{-- Customize layout sections --}}


@section('content_header_title', 'Informasi Masalah')


{{-- Content body: main page content --}}

@section('content_body')
<div class="card">
  <div class="card-header">
    <form action="{{url('/produksi/inputmasalah')}}" method="post" enctype="multipart/form-data">
      @csrf

      <div class="card card-secondary">
        <div class="card-header">
          <div class="col-12">
            <h3 class="card-title">Kartu Berbagi Informasi Permasalahan</h3>
          </div>
        </div>
        <div class="card-tools">
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-2">
          <label for="no_kartu">No Kartu</label>
          <input type="text" value="" class="form-control" name="no_kartu_1" id="no_kartu_1" disabled>
          <input type="hidden" value="" class="form-control" name="no_kartu" id="no_kartu">
        </div>
        <div class="form-group col-md-3">
          <label for="klasifikasi">Klasifikasi</label>
          <select name="klasifikasi" id="klasifikasi" class="form-control">
            <option selected>Choose...</option>
            <option value="NG">NG</option>
            <option value="Qualitas">Qualitas</option>
            <option value="Mesin_Rusak">Mesin Rusak</option>
            <option value="Barang_Habis">Barang Habis</option>
            <option value="Safety">Safety</option>
            <option value="Lain-lain">Lain - Lain</option>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label for="tanggal_ditemukan">Tanggal Ditemukan</label>
          <input type="date" class="form-control" name="tanggal_ditemukan" id="tanggal_ditemukan"
            placeholder="Tanggal ditemukan" required>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-2">
          <label for="lokasi">Lokasi</label>
          <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Dimana (Tempat Proses)">
        </div>
        <div class="form-group col-md-4">
          <label for="masalah">Masalah</label>
          <textarea class="form-control" name="masalah" id="masalah" cols="30" rows="4"
            placeholder="Apa Masalahnya (Tulis dengan ringkas)" required></textarea>
        </div>
        <div class="form-group col-md-4">
          <label for="penyebab">Penyebab</label>
          <textarea class="form-control" name="penyebab" id="penyebab" cols="30" rows="4"
            placeholder="Penyebab / Faktor yang dapat diperkirakan"></textarea>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="penyebab">Lampiran Gambar</label>
          <input type="file" class="form-control" id="lampiran" name="lampiran">


        </div>
      </div>

      <input type="submit" value="simpan" class="btn btn-primary">
    </form>
  </div>
</div>
<div class="card card-success">
  <div class="card-header">
    <div class="row">

      <h3 class="card-title">Grafik Permasalahan <i id="periode"></i></h3>
    </div>

    <div class="row align-center">
      <input type="date" class="form-control col-md-2" id="tgl1" value="{{date('Y-m').'-01'}}">
      <label for="" class="col-md-2 text-center">Sampai</label>
      <input type="date" class="form-control col-md-2" id="tgl2" value="{{date('Y-m-d')}}">
      <button class="btn btn-primary" id="btn_refresh"><i class="fa fa-sync"></i></button>
    </div>
  </div>
  <div class="card-body">
    <div>
      <canvas id="barChart"></canvas>
    </div>
  </div>
  <!-- /.card-body -->
</div>



<p>

<div class="card">
  <div class="card-header">
    <div class="card card-warning">
      <div class="card-header">
        <div class="row">

          <div class="col-12">
            <h3 class="card-title">List Masalah</h3>
          </div>
        </div>
        <div class="row align-center">



          <div class="row text-center">

            <input type="date" class="form-control col-md-4" id="tgl_awal" value="{{date('Y-m').'-01'}}">
            <label for="" class="col-md-2 text-center">Sampai</label>
            <input type="date" class="form-control col-md-4" id="tgl_akhir" value="{{date('Y-m-d')}}">
            <button class="btn btn-primary" id="btn_reload"><i class="fa fa-sync"></i></button>

          </div>


        </div>
      </div>
      <div class="card-tools">

      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap" id="tb_masalah">
        <thead>
          <tr>
            <th>id</th>
            <th>Tanggal ditemukan</th>
            <th>Informer</th>
            <th>No Kartu</th>
            <th>Klasifikasi</th>
            <th>Lokasi</th>
            <th>Masalah</th>
            <th>Progres</th>
            <th>Lampiran</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
    <div class="card-footer">

      <button class="btn btn-success" id="btn-excel">Download Excel</button>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>

</p>

<!-- Modal -->

<div class="modal fade" id="modal-img" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="img-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" alt="" id="img-lampiran" class="img-fluid" style="width:100%">
      </div>
    </div>
  </div>
</div>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
   
@endpush