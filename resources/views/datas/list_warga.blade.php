@extends('layout.app')
@section('plugins.Select2', true)

{{-- Customize layout sections --}}

@section('content_header_title', 'List Warga')
@section('content_body')

    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-tambah" id="btn_tambah" name="btn_tambah" data-bs-toggle="modal"
                data-bs-target="#modal_tw"><i class="fas fa-user-plus"></i><u>
                    Tambah</u></button>
            <div class="card">
                <div class="card-header card-color-list">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="card-title">List Warga</h3>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- <div class="col-md-2">
                            <strong for="tgl_awal">Tanggal Awal</strong>
                            <input type="date" class="form-control" id="tgl_awal" value="{{ date('Y-m') . '-01' }}">
                        </div>
                        <div class="col-md-2">
                            <strong for="tgl_akhir">Tanggal Akhir</strong>
                            <input type="date" class="form-control" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                        </div> --}}
                        <div class="col col-md-3">
                            <strong for="selectline">Status Tinggal</strong>
                            <select name="status_tinggal" id="status_tinggal" class="form-control select2">
                                <option value="Stay">Stay</option>
                                <option value="Kontrak">Kontrak</option>
                                <option value="Singgah">Singgah</option>
                                <option value="Kos">Kos</option>
                                <option value="All">All</option>
                            </select>
                        </div>
                        <div class="col col-md-1 d-flex align-items-end">
                            <button class="btn btn-cari w-100" id="btn_reload">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <hr style="margin-top: 0%">
                <div class="card-body table-responsive p-0" style="margin-top: -1%">
                    <table id="tb_list_warga" class="table table-hover table-striped nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>No KK</th>
                                <th>No KTP</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Blok</th>
                                <th>JK</th>
                                <th>Status Tinggal</th>
                                <th>No Telp</th>
                                <th>Keterangan</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <br>
                {{-- <div class="card-footer" style="margin-top:-1%">
            <button class="btn btn-excel" id="btn-excel"><i class="fas fa-file-excel"> Download Excel</i></button>
        </div> --}}
            </div>
        </div>
    </div>

    <!-- Modal Tambah Warga (TW) -->
    <div class="modal fade" id="modal_tw" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="exampleModalLongTitle">Form Entry Data Warga</h4>
                </div>
                <form id="frm_fedw">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <strong>No KK</strong>
                                <input type="text" class="form-control rounded-0" name="no_kk" id="no_kk"
                                    placeholder="Nomer Kartu Keluarga" required pattern="\d+"
                                    title="Hanya angka yang diperbolehkan">
                            </div>
                            <div class="form-group col-md-12">
                                <strong>No KTP</strong>
                                <input type="text" class="form-control rounded-0" name="no_ktp" id="no_ktp"
                                    placeholder="Nomer Kartu Tanda Penduduk" required pattern="\d+"
                                    title="Hanya angka yang diperbolehkan">
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Nama Warga</strong>
                                <input type="text" class="form-control rounded-0" name="nama" id="nama"
                                    placeholder="Nama Warga" required>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Alamat</strong>
                                <textarea name="alamat" id="alamat" cols="60" rows="2" class="form-control rounded-0"
                                    placeholder="Alamat sesuai KTP" required></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Blok</strong>
                                <input type="text" class="form-control rounded-0" name="blok" id="blok"
                                    placeholder="Blok Rumah" maxlength="6" required>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Jenis Kelamin</strong>
                                <select name="jk" id="jk" class="form-control rounded-0" required>
                                    <option value="">Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Status Tinggal</strong>
                                <select name="status_tinggal" id="status_tinggal" class="form-control rounded-0"
                                    style="width: 100%;" required>
                                    <option value="">Pilih...</option>
                                    <option value="Stay">Stay</option>
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Kos">Kos</option>
                                    <option value="Singgah">Singgah</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>No Telp</strong>
                                <input type="text" class="form-control rounded-0" name="no_telp" id="no_telp"
                                    placeholder="No Telp">
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Keterangan</strong>
                                <textarea name="keterangan" id="keterangan" cols="60" rows="2" class="form-control rounded-0"
                                    placeholder="Isi keterangan jika ada." required></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-keluar btn-flat col-md-3" id="btn_close">Close</button>
                        <button type="submit" class="btn btn-update btn-flat col-md-3" id="btn_submit">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Warga (EW) -->
    <div class="modal fade" id="modal_ew" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title" id="exampleModalLongTitle">Edit Data Warga</h4>
                </div>
                <form id="frm_edw">
                    @csrf
                    <input type="hidden" name="e_id_warga" id="e_id_warga">
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <strong>No KK</strong>
                                <input type="text" class="form-control rounded-0" name="e_no_kk" id="e_no_kk"
                                    placeholder="Nomer Kartu Keluarga" required pattern="\d+"
                                    title="Hanya angka yang diperbolehkan">
                            </div>
                            <div class="form-group col-md-12">
                                <strong>No KTP</strong>
                                <input type="text" class="form-control rounded-0" name="e_no_ktp" id="e_no_ktp"
                                    placeholder="Nomer Kartu Tanda Penduduk" required pattern="\d+"
                                    title="Hanya angka yang diperbolehkan">
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Nama Warga</strong>
                                <input type="text" class="form-control rounded-0" name="e_nama" id="e_nama"
                                    placeholder="Nama Warga" required>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Alamat</strong>
                                <textarea name="e_alamat" id="e_alamat" cols="60" rows="2" class="form-control rounded-0"
                                    placeholder="Alamat sesuai KTP" required></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Blok</strong>
                                <input type="text" class="form-control rounded-0" name="e_blok" id="e_blok"
                                    placeholder="Blok Rumah" maxlength="6" required>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Jenis Kelamin</strong>
                                <select name="e_jk" id="e_jk" class="form-control rounded-0" required>
                                    <option value="">Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Status Tinggal</strong>
                                <select name="e_status_tinggal" id="e_status_tinggal" class="form-control rounded-0"
                                    style="width: 100%;" required>
                                    <option value="">Pilih...</option>
                                    <option value="Stay">Stay</option>
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Kos">Kos</option>
                                    <option value="Singgah">Singgah</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>No Telp</strong>
                                <input type="text" class="form-control rounded-0" name="e_no_telp" id="e_no_telp"
                                    placeholder="No Telp">
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Keterangan</strong>
                                <textarea name="e_keterangan" id="e_keterangan" cols="60" rows="2" class="form-control rounded-0"
                                    placeholder="Isi keterangan jika ada."></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-keluar" data-bs-dismiss="modal" aria-label="Close">Close
                        </button>
                        <button type="submit" class="btn btn-update btn-flat col-md-3" id="e_btn_submit">Save</button>
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
    <script src="{{ asset('js/list_warga.js') }}"></script>
@endpush
