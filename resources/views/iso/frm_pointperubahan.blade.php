@extends('layout.app')

{{-- Customize layout sections --}}


@section('content_header_title', 'Point Perubahan')


{{-- Content body: main page content --}}

@section('content_body')
<div class="col-lg-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill"
                        href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home"
                        aria-selected="false">Point Perubahan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill"
                        href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile"
                        aria-selected="false">List</a>
                </li>
               
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane active fade show" id="custom-tabs-three-home" role="tabpanel"
                    aria-labelledby="custom-tabs-three-home-tab">
                    <form action="{{ url('/iso/ins_perubahan') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-success card-outline">
                                    <strong style="padding-left: 3%"><u>Informasi Perubahan Internal</u></strong>
                                    <div class="card-body box-profile">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <strong for="tgl_ditemukan">Tgl Perubahan</strong>
                                                <input type="datetime-local" class="form-control"
                                                    name="ipi_tglperubahan" id="ipi_tglperubahan"
                                                    placeholder="Tanggal perubahan" required>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <strong>SHIFT</strong>
                                                <select name="ipi_shift" id="ipi_shift" class="form-control select2"
                                                    style="width: 100%;" required>
                                                    <option value="">SHIFT...</option>
                                                    <option value="SHIFT 1">SHIFT 1</option>
                                                    <option value="SHIFT 2">SHIFT 2</option>
                                                    <option value="SHIFT 3">SHIFT 3</option>
                                                    <option value="NON SHIFT">NON SHIFT</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <strong>Lokasi</strong>
                                                <select name="ipi_lokasi" id="ipi_lokasi" class="form-control select2"
                                                    style="width: 100%;" required>
                                                    <option value="">Lokasi...</option>
                                                   {{-- @foreach ($result as $s)
                                                        <option value="{{ $s->result }}">{{ $s->result }}
                                                        </option>
                                                    @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <strong>Perubahan 4 M</strong>
                                                <select name="ipi_perubahan4m" id="ipi_perubahan4m"
                                                    class="form-control select2" style="width: 100%;" required>
                                                    <option value="">Perubahan 4 M...</option>
                                                    {{--
                                                    @foreach ($perubahan4m as $p)
                                                        <option value="{{ $p->perubahan_4m }}">
                                                            {{ $p->perubahan_4m }}
                                                        </option>
                                                    @endforeach
                                                    --}}
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <strong>Desk Perubahan 4 M</strong>
                                                <select name="ipi_deskperubahan4m" id="ipi_deskperubahan4m"
                                                    class="form-control select2" style="width: 100%;" required>
                                                    <option value="Rencana">Rencana</option>
                                                    <option value="Toppatsu">Toppatsu "Tiba-tiba"</option>
                                                    <option value="Beda">Beda dari biasanya</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <strong>Deskripsi Point</strong>
                                                <div class="list-group">
                                                    <div class="list-group-item">
                                                        <p id="detail"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card card-success card-outline">
                                    <div class="card-body box-profile">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <strong>Deskripsi Perubahan</strong>
                                                <textarea class="form-control" name="ipi_deskperubahan" id="ipi_deskperubahan" cols="30" rows="3"
                                                    required></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <strong>Tindakan atas Perubahan</strong>
                                                <textarea class="form-control" name="ipi_tindakanperubahan" id="ipi_tindakanperubahan" cols="30"
                                                    rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <strong>Penanggung Jawab Perubahan</strong>
                                                <input type="text" name="ipi_penanggungjawab"
                                                    id="ipi_penanggungjawab" class="form-control rounded-0"
                                                    value={{ Auth::user()->name }} readonly>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <strong>Target Penyelesaian</strong>
                                                <input type="date" name="ipi_target" id="ipi_target"
                                                    class="form-control rounded-0" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <strong>Lapor Perubahan</strong>
                                                <div class="col-sm-12">
                                                    <!-- radio -->
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <div class="row">
                                                                <input class="form-check-input" type="radio"
                                                                    name="r_perlu" id="r_perlu" required>
                                                                <label class="form-check-label">Perlu</label>
                                                                <label for="" class="col-md-1"> </label>
                                                                <select name="ipi_lapor" id="ipi_lapor"
                                                                    class="form-control rounded-0 col-md-12" required>
                                                                    <option value="">Lapor Ke...</option>
                                                                    <option value="Maintenance">Maintenance
                                                                    </option>
                                                                    <option value="Technical">Technical</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-check">
                                                            <div class="row">
                                                                <input class="form-check-input" type="radio"
                                                                    name="r_tidakperlu" id="r_tidakperlu" required>
                                                                <label class="form-check-label">Tidak Perlu</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="card card-danger card-outline">
                                    <strong style="padding-left: 3%"><u>Engineering Change Request
                                            (ECR)</u></strong>
                                    <div class="card-body box-profile">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <strong>Isi Perubahan</strong>
                                                <textarea class="form-control" name="ecr_isiperubahan" id="ecr_isiperubahan" cols="30" rows="2"
                                                    placeholder="" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom: -20px;">
                                            <div class="form-group col-md-4">
                                                <strong>Sifat Perubahan</strong>
                                                <div class="col-sm-12">
                                                    <!-- radio -->
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <div class="row">
                                                                <input class="form-check-input" type="radio"
                                                                    name="r_sementara" id="r_sementara">
                                                                <label class="form-check-label">Sementara</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check">
                                                            <div class="row">
                                                                <input class="form-check-input" type="radio"
                                                                    name="r_tetap" id="r_tetap">
                                                                <label class="form-check-label">Tetap</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <strong>Aspek Lingkungan / K3</strong>
                                                <div class="col-sm-12">
                                                    <!-- radio -->
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <div class="row">
                                                                <input class="form-check-input" type="radio"
                                                                    name="r_berdampak" id="r_berdampak">
                                                                <label class="form-check-label">Berdampak</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check">
                                                            <div class="row">
                                                                <input class="form-check-input" type="radio"
                                                                    name="r_tidak" id="r_tidak">
                                                                <label class="form-check-label">Tidak</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <strong>Apabila berdampak akan
                                                    mengakibatkan</strong>
                                                <textarea class="form-control" name="ecr_dampak" id="ecr_dampak" cols="30" rows="2" placeholder=""
                                                    required></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <strong>Keperluan / alasan perubahan</strong>
                                                <textarea class="form-control" name="ecr_keperluan" id="ecr_keperluan" cols="30" rows="2"
                                                    placeholder="" required></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <strong>Data sebelum perubahan</strong>
                                                <textarea class="form-control" name="ecr_sebelum" id="ecr_sebelum" cols="30" rows="2" placeholder=""
                                                    required></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <strong>Dokument terkait</strong>
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                            id="file_dok" name="file_dok[]" multiple>
                                                        <label class="custom-file-label" for="file_dok"
                                                            data-browse="Choose files">Choose files</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="padding-top: -2%">
                        <input type="submit" value="Simpan" class="btn btn-success rounded-pill float-right">
                    </form>
                </div>
                <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel"
                    aria-labelledby="custom-tabs-three-profile-tab">

                    <div class="row" style="margin-top: -1%">
                        <div class="form-group col-md-2">
                            @csrf
                            <label for="beginning">Beginning</label>
                            <input type="date" class="form-control" id="tgl_awal"
                                value="{{ date('Y-m') . '-01' }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Ending</label>
                            <input type="date" class="form-control" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label> Status : </label>
                            <select name="status_perubahan" id="status_perubahan" class="form-control select2"
                                value="All">
                                <option value="All">All</option>
                                <option value="Open">Open</option>
                                <option value="Close">Close</option>
                                <option value="Evaluasi">Evaluasi</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label> Jenis : </label>
                            <select name="jenis_perubahan" id="jenis_perubahan" class="form-control select2"
                                value="All">
                                <option value="All">All</option>
                                <option value="Perlu">ECR</option>
                                <option value="Tidak">Non ECR</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label> Reload </label>
                            <br>
                            <button class="btn btn-primary rounded-pill" id="btn_reload"><i
                                    class="fa fa-sync"></i></button>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" style="width: 100%" id="tb_point_perubahan">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>No Reg</th>
                                    <th>Tgl Inform</th>
                                    <th>Tgl Perubahan</th>
                                    <th>Lokasi</th>
                                    <th>4M</th>
                                    <th>Point</th>
                                    <th>PJ</th>
                                    <th>Deskripsi <br> Perubahan</th>
                                    <th>Tindakan <br> atas Perubahan</th>
                                    <th>Shift</th>
                                    <th>Target <br> Selesai</th>
                                    <th>Aktual <br> Selesai</th>
                                    <th>Hasil Evaluasi</th>
                                    <th>Lapor</th>
                                    <th>To</th>
                                    <th>ECR</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
               
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@stop

{{-- Push extra CSS --}}

@push('css')
    <style>
        .btn:hover {
            color: darkred;
            background-color: white !important
        }
    </style>
@endpush

{{-- Push extra scripts --}}

@push('js')
   
@endpush