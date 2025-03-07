@section('plugins.Select2', true)
@extends('layout.app')

@section('content_header_title', 'Request Jigu / Part')

@section('content_body')

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif(Session::has('alert-danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('alert-danger') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Entry Repair Jigu</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div class="card-body">
            <form id="inp_req" action="{{ route('produksi/menu_request_jigu/inp_req_jigu') }}"
                enctype="multipart/form-data" method="POST">

                @csrf
                <div class="row">
                    <div class="form-group col-md-1">
                        <strong for="tujuan">Tujuan</strong>
                        <input type="text" name="tujuan" id="tujuan" class="form-control" value="TCH" readonly>
                        {{-- <select name="tujuan" id="tujuan" class="form-control" required>
                            <option value="">Choose...</option>
                            <option value="TCH">TCH</option>
                            <option value="QA">QA</option>
                        </select> --}}
                    </div>
                    <div class="form-group col-md-3">
                        <strong for="permintaan">Permintaan</strong>
                        <input type="hidden" nama="permintaan" id="permintaan">
                        <input type="hidden" name="unik" id="unik">
                        <select name="permintaan1" id="permintaan1" class="form-control select2" required>
                            <option value="">Choose...</option>
                            <option value="KR">REPAIR</option>
                            <option value="KG">PEMBUATAN GAMBAR</option>
                            <option value="KP">PEMBUATAN JIGU / SPARE PART</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <strong for="no_laporan">No Laporan</strong>
                        <input type="text" class="form-control" name="no_laporan_1" id="no_laporan_1" disabled>
                        <input type="hidden" name="no_laporan" id="no_laporan">
                    </div>
                    <div class="form-group col-md-2">
                        <strong for="jenis_item">Jenis Item</strong>
                        <select name="jenis_item" id="jenis_item" class="form-control select2" required>
                            <option value="">Choose...</option>
                            <option value="Jigu">Jigu</option>
                            <option value="Spare Part">Spare Part</option>
                            <option value="Gambar">Gambar</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <strong for="nouki">Nouki</strong>
                        <input type="date" class="form-control" name="nouki" id="nouki" placeholder="nouki"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <strong for="nama_mesin">Nama Mesin</strong>
                        <select name="nama_mesin" id="nama_mesin" class="form-control select2" required></select>
                    </div>
                    <div class="form-group col-md-3">
                        <strong for="nama_item">Nama item</strong>
                        <input type="text" class="form-control" name="nama_item" id="nama_item" required>
                    </div>
                    <div class="form-group col-md-2">
                        <strong for="ukuran">Ukuran</strong>
                        <input type="text" class="form-control" name="ukuran" id="ukuran">
                    </div>
                    <div class="form-group col-md-1">
                        <strong for="qty">Qty</strong>
                        <input type="number" class="form-control" name="qty" id="qty" required>
                    </div>
                    <div class="form-group col-md-2">
                        <strong for="satuan">Satuan</strong>
                        <select name="satuan" id="satuan" class="form-control select2">
                            <option selected>Choose...</option>
                            <option>Pcs</option>
                            <option>Set</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <strong for="alasan">Alasan</strong>
                        <textarea name="alasan" id="alasan" cols="60" rows="2" class="form-control" required></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <strong for="permintaan_perbaikan">Permintaan Perbaikan</strong>
                        <textarea name="permintaan_perbaikan" id="permintaan_perbaikan" cols="60" rows="2" class="form-control"
                            required></textarea>
                    </div>
                </div>
                <hr style="margin-top: -0%">
                <button id="btn_simpan" type="submit" value="Simpan"
                    class="btn btn-outline btn-tambah float-right"></button>
            </form>
        </div>
    </div>


    {{-- List Repair Jigu --}}
    <div class="card">
        <div class="card-header card-color-list">
            <div class="row">
                <div class="col-12">
                    <h3 class="card-title">List Repair Jigu</h3>
                    <h3 class="card-title float-right" id="notif_reqJigu" name="notif_reqJigu" style="color: blue">
                        notif_reqJigu</h3>
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
                <div class="col-md-2">
                    <strong for="selectline">Status Permintaan</strong>
                    <select name="status_permintaan" id="status_permintaan" class="form-control select2" required>
                        <option value="">Pilih Status...</option>
                        <option value="All">All</option>
                        <option value="Open">Open</option>
                        <option value="Proses">Proses</option>
                        <option value="Waiting User">Waiting User</option>
                        <option value="Close">Close</option>
                        <option value="Tolak">Tolak</option>
                    </select>
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
            <table id="tb_repair_jigu" class="table table-hover table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Tgl Req</th>
                        <th>No Laporan</th>
                        <th>Permintaan</th>
                        <th>Jenis</th>
                        <th>Mesin</th>
                        <th>Nama Item</th>
                        <th>Ukuran</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th>Permintaan Perbaikan</th>
                        <th>Nouki</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <br>
        <div class="card-footer" style="margin-top:-1%">
            <button class="btn btn-excel" id="btn-excel"><i class="fas fa-file-excel"> Download Excel</i></button>
        </div>
        <!-- /.card-body -->
        <!-- /.card -->
    </div>


    <!-- Modal Detail Req Jigu (DRJ)-->
    <div class="modal fade" id="modal_drj" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title card-title">Detail Request Jigu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <strong for="" class="col-md-1">
                                        No Laporan :
                                        <input type="text" name="drj_noLaporan" id="drj_noLaporan" class="col-md-2"
                                            disabled>
                                    </strong>
                                    <strong for="" class="col-md-1">
                                        Permintaan :
                                        <input type="text" name="drj_permintaan" id="drj_permintaan" class="col-md-3"
                                            disabled>
                                    </strong>
                                    <strong for="" class="col-md-1">
                                        Jenis :
                                        <input type="text" name="drj_jenisItem" id="drj_jenisItem" class="col-md-3"
                                            disabled>
                                    </strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <strong for="" class="col-md-1" style="margin-left: -7px;">
                                        Nama Mesin :
                                        <input type="text" name="drj_namaMesin" id="drj_namaMesin" class="col-md-5"
                                            disabled>
                                    </strong>
                                    <strong for="" class="col-md-1"> Item :
                                        <input type="text" name="drj_item" id="drj_item" class="col-md-5" disabled>
                                    </strong>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <strong for=""> Ukuran :
                                        <input type="text" name="drj_ukuran" id="drj_ukuran" style="margin-left: 1%"
                                            disabled>
                                    </strong>

                                    <strong for="" style="margin-left: 1%"> Qty :
                                        <input type="text" name="drj_qty" id="drj_qty" style="margin-left: 1%"
                                            class="col-md-1" disabled>
                                    </strong>
                                    <strong for="" style="margin-left: 1%"> Satuan :
                                        <input type="text" name="drj_satuan" id="drj_satuan" style="margin-left: 1%"
                                            class="col-md-1" disabled>
                                    </strong>
                                    <strong for="" style="margin-left: 1%"> Nouki :
                                        <input type="text" name="drj_nouki" id="drj_nouki" style="margin-left: 1%"
                                            class="col-md-2" disabled>
                                    </strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <strong for="" style="margin-left: 1%"> Alasan :
                                        <input type="text" name="drj_alasan" id="drj_alasan" style="margin-left: 1%"
                                            class="col-md-11" disabled>
                                    </strong>
                                    <strong for="" style="margin-left: 1%"> Permintaan Perbaikan :
                                        <input type="text" name="drj_permintaanPerbaikan" id="drj_permintaanPerbaikan"
                                            style="margin-left: 1%" class="col-md-9" disabled>
                                    </strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <strong for="" style="margin-left: 1%"> Tindakan Perbaikan :
                                        <input type="text" name="drj_tindakanPerbaikan" id="drj_tindakanPerbaikan"
                                            style="margin-left: 1%" class="col-md-6" disabled>
                                    </strong>
                                    <strong for="" style="margin-left: 1%"> Opr Tech :
                                        <input type="text" name="drj_oprTech" id="drj_oprTech"
                                            style="margin-left: 1%" class="col-md-2" disabled>
                                    </strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <strong for="" style="margin-left: 1%"> Selesai Tech :
                                        <input type="text" name="drj_tglSelesaiTech" id="drj_tglSelesaiTech"
                                            style="margin-left: 1%" class="col-md-3" disabled>
                                    </strong>
                                    <strong for="" style="margin-left: 1%"> Qty Selesai :
                                        <input type="text" name="drj_qtySelesai" id="drj_qtySelesai"
                                            style="margin-left: 1%" class="col-md-1" disabled>
                                    </strong>
                                    <strong for="" style="margin-left: 1%"> Material :
                                        <input type="text" name="drj_material" id="drj_material"
                                            style="margin-left: 1%" class="col-md-2" disabled>
                                    </strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <strong for="" style="margin-left: 1%"> Status Permintaan :
                                        <input type="text" name="drj_status" id="drj_status"
                                            style="margin-left: 1%; text-size:20px" class="col-md-4" disabled>
                                    </strong>
                                </div>
                            </div>
                            <hr>

                            <form id="frm_drj">
                                @csrf
                                <input type="hidden" name="drj_idPermintaan" id="drj_idPermintaan">
                                <div class="row">
                                    <strong class="col-md-6" style="text-align: right">User Terima : </strong>
                                    <div class="col-md-6 d-flex ml-auto">
                                        <input type="number" name="drj_userTerima" id="drj_userTerima"
                                            class="form-control rounded-0" placeholder="Jumlah Terima" required>
                                        <button type="submit" id="btn_drj"
                                            class="form-control btn-update rounded-0 ml-2">Update</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-keluar" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('js/repair_jigu.js') }}"></script>
@endpush
