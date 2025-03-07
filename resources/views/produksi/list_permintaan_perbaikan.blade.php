@extends('layout.app')
@section('plugins.Select2', true)
@section('plugins.moment', true)
{{-- Customize layout sections --}}


@section('content_header_title', 'Perbaikan')


{{-- Content body: main page content --}}

@section('content_body')

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Entry Permintaan Perbaikan</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div class="card-body">
            <form id="frm_permintaan_perbaikan_mesin">
                @csrf
                <div class="form-group">
                    <label class="me-3">Pilih Kategori:</label>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori" id="r_mesin" value="r_mesin"
                            checked="true">
                        <label class="form-check-label" for="r_mesin">Mesin</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori" id="r_non_mesin" value="r_non_mesin">
                        <label class="form-check-label" for="r_non_mesin">Non Mesin</label>
                    </div>
                </div>
                <hr>


                <div class="row">
                    <div class="col-md-7">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <strong for="shift">Shift</strong>
                                <select name="shift" id="shift" class="form-control select2" required>
                                    <option value="">Pilih Shift...</option>
                                    <option value="NonShift">Non Shift</option>
                                    <option value="shift1">Shift 1</option>
                                    <option value="shift2">Shift 2</option>
                                    <option value="shift3">Shift 3</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <strong for="no-permintaan">No Permintaan</strong>
                                <input type="text" name="no_permintaan" id="no_permintaan" class="form-control" readonly>
                            </div>


                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <strong>No Induk Mesin</strong>
                                <select id="nomer_induk_mesin" name="nomer_induk_mesin" class="form-control select2"
                                    style="width: 100%;" required>
                                    <option>---Pilih Nomer Mesin----</option>
                                </select>
                            </div>

                            <div class="form-group col-md-7">
                                <strong for="mesin">Nama Mesin</strong>
                                <input type="text" class="form-control @error('mesin') is-invalid @enderror"
                                    name="nama_mesin" id="nama_mesin" placeholder="Nama Mesin/Alat" required readonly>
                            </div>
                            <div class="form-group col-md-1">
                                <strong for="no_mesin">No</strong>
                                <input type="text" class="form-control" name="no_mesin" id="no_mesin" readonly>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <strong for="inputmasalah">Masalah</strong>
                                <textarea name="masalah" class="form-control @error('masalah') is-invalid @enderror" id="masalah" cols="30"
                                    rows="3" placeholder="Bagian yang bermasalah" required></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <strong for="inputkondisi">Kondisi</strong>
                                <textarea name="kondisi" class="form-control @error('kondisi') is-invalid @enderror" id="kondisi" cols="30"
                                    rows="3" placeholder="Kondisi Mesin yang bermasalah"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 align-self-center">
                        <table style="background-color: blanchedalmond; text-align:center">
                            <th> Apakah Kerusakan Mesin Berdampak terhadap target produksi & perlu lapor PPIC ?</th>
                            <tr>
                                <td> <input type="radio" name="ppic" id="inlineRadio1" value="N" checked>
                                    <label class="form-check-label" for="inlineRadio1">Tidak</label>
                                </td>
                            </tr>
                            <tr>
                                <td> <input type="radio" name="ppic" id="inlineRadio2" value="Y">
                                    <label class="form-check-label" for="inlineRadio2">Ya</label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr style="margin-top: -0%">
                <button type="submit" class="btn btn-update" id="btn_simpan">Simpan</button>
            </form>
        </div>
    </div>

    {{-- List Permintaan Perbaikan --}}
    <div class="card">
        <div class="card-header card-color-list">
            <div class="row">
                <div class="col-12">
                    <h3 class="card-title">List Permintaan Perbaikan</h3>
                    <h3 class="card-title float-right" id="notif_permintaan_perbaikan" name="notif_permintaan_perbaikan"
                        style="color: blue">
                        notif_permintaan_perbaikan</h3>
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
                        <option value="open">Open</option>
                        <option value="process">Proses</option>
                        <option value="selesai">Waiting User</option>
                        <option value="complete">Close</option>
                        <option value="pending">Pending</option>
                        <option value="reject">Reject</option>
                        <option value="TempReject">Temporary Reject</option>
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
            <table id="tb_permintaan_perbaikan" class="table table-hover table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tanggal</th>
                        <th>Maintenance</th>
                        <th>Dept</th>
                        <th>Shift</th>
                        <th>No. Request</th>
                        <th>Nama Mesin</th>
                        <th>No. <br> Mesin</th>
                        <th>No. Induk Mesin</th>
                        <th>Masalah</th>
                        <th>Kondisi</th>
                        <th>Tindakan</th>
                        <th>Tgl Selesai</th>
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
    </div>


    <!-- Modal Edit Permintaan Perbaikan (EPP) -->
    <div class="modal fade" id="modal_epp" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title card-title" id="exampleModalLongTitle">Edit Permintaan Perbaikan</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id-req">
                    <div class="row">
                        <div class="col col-md-4"><label>No.</label></div>
                        <div class="col col-md-8">:
                            <label id="no-perbaikan"></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-md-4"><label>Departemen</label></div>
                        <div class="col col-md-8">:
                            <label id="dept"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-4"><label>Shift</label></div>
                        <div class="col col-md-8">:
                            <label id="edit-shift"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-4"><label>No. Mesin</label></div>
                        <div class="col col-md-8">:
                            <label id="edit_no_mesin"></label>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col col-md-4"><label>Shift :</label></div>
                        <div class="col col-md-4">
                            <select name="edit-shift" id="edit-shift" class="form-control" required>
                                <option value="">Pilih Shift...</option>
                                <option value="NonShift">Non Shift</option>
                                <option value="shift1">Shift 1</option>
                                <option value="shift2">Shift 2</option>
                                <option value="shift3">Shift 3</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-4"><label>No. Mesin :</label></div>
                        <div class="col col-md-4">

                            <select id="edit_no_mesin" class="form-control select2" style="width: 100%;" required>
                                <option>-------Pilih Nomer Mesin--------</option>
                                
                            @foreach ($mesin as $y)
                                <option value="{{ $y->item . ' ' . $y->spesifikasi }}">{{ $y->item_code }}</option>
                            @endforeach
                       
                            </select>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col col-md-4"><label>Nama Mesin</label></div>
                        <input type="hidden" name="edit_no_induk" id="edit_no_induk">
                        <div class="col col-md-8">:
                            <label id="edit_nama_mesin"></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-md-4"><label>Masalah</label></div>
                        <div class="col col-md-8">:
                            <textarea name="edit-masalah" id="edit-masalah" class="form-control" cols="60" rows="3">
            </textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-4"><label>Kondisi</label></div>
                        <div class="col col-md-8">:
                            <textarea name="edit-kondisi" id="edit-kondisi" class="form-control" cols="60" rows="3">
            </textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-keluar" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-update" id="btn-update">Update</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Modal Info ===== --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalinfo"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Keterangan</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">

                                <h4 id="setatus"></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <label for="">Sampai : </label>
                                <h4 id="schd"></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p id="desk">

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi User (KU) --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal_conf"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-primary">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Perbaikan Selesai</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <input type="hidden" name="conf_id" id="conf_id">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <p>Dengan melakukan konfirmasi permintaan ini berarti permintaan anda dinyatakan selesai,
                                    lakukan
                                    pemeriksaan kondisi hasil perbaikan dengan seksama.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="icheck-danger d-inline" style="background-color:cornflowerblue">
                                    <input type="checkbox" id="checkboxPrimary1" required>
                                    <label for="checkboxPrimary1">Saya sudah memeriksa hasil perbaikan</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p id="desk">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-light" id="btn_save">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Info Reject --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalInfoReject"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Keterangan Reject</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="frm_reject">
                    @csrf
                    <div class="modal-body">
                        <div class="container">
                            <input type="hidden" name="idPerbaikan" id="idPerbaikan">
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea name="keteranganReject" id="keteranganReject" cols="100" rows="4" class="form-control"
                                        style="font-size: 20px" readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-keluar col-md-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="form-control btn-update col-md-2">Ok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop


@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="{{ asset('js/select_no_induk_mesin.js') }}"></script>
    <script src="{{ asset('js/list_permintaan_perbaikan.js') }}"></script>
@endpush
