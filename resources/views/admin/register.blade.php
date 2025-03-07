@section('plugins.Select2', true)
@extends('layout.app')

{{-- Customize layout sections --}}


@section('content_header_title', 'User Registration')


{{-- Content body: main page content --}}

@section('content_body')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"> Department Register</i>
                    </h3>
                    <button class="btn btn-tambah float-right" id="btn-add-dept"><i class="fas fa-plus"></i></button>
                </div><!-- /.card-header -->

                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover text-nowrap" id="tb_dept">
                        <thead>
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th>Dept</th>
                                <th>Group</th>
                                <th>Section</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"> Permissions Register</i>

                    </h3>
                    <button class="btn btn-tambah float-right" id="btn-add-permit"><i class="fas fa-plus"></i></button>
                </div><!-- /.card-header -->

                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover text-nowrap" id="tb_permission">
                        <thead>
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th>Name</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- /.card-body -->
                <br>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"> Role Register</i>
                    </h3>
                    <button class="btn btn-tambah float-right" id="btn-add-role"><i class="fas fa-plus"></i></button>
                </div><!-- /.card-header -->

                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover text-nowrap" id="tb_role">
                        <thead>
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th>Name</th>
                                <th>Permissions</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- /.card-body -->
                <br>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="deptModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 card-title" id="deptModalLabel">Tambah Department</h1>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="" id="frm-dept">
                        <input type="hidden" name="id_dept" id="id_dept">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Departemen</label>
                            <div class="col-sm-9">
                                <input type="text" name="dept" class="form-control" id="dept"
                                    placeholder="Nama Department" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Group</label>
                            <div class="col-sm-9">
                                <input type="text" name="group" class="form-control" id="group"
                                    placeholder="Nama Group" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Section</label>
                            <div class="col-sm-9">
                                <input type="text" name="section" class="form-control" id="section"
                                    placeholder="Nama Section" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis</label>
                            <div class="col-sm-6">
                                <select name="jenis" id="jenis" class="form-control">
                                    <option value="">Select...</option>
                                    <option value="DIRECT">DIRECT</option>
                                    <option value="INDIRECT">INDIRECT</option>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-keluar" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-update btn-update">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 card-title" id="roleModalLabel">Tambah Role</h1>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="" id="frm-role">
                        <input type="hidden" name="id_role" id="id_role">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" name="nama" class="form-control" id="inputrole"
                                    placeholder="Nama Role" required>
                            </div>
                        </div>
                        <div class="row justify-content-md-center">
                            <label for="">Permissions</label>
                        </div>
                        <div class="row">
                            <select name="permit[]" id="permit" multiple="multiple"
                                class="select2 select2-hidden-accessible" data-placeholder="Pilih Permission"
                                style="width: 100%;" data-bs-select2-id="7" tabindex="-1" aria-hidden="true">

                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-keluar" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-update">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="permitModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 card-title" id="permitModalLabel">Tambah Permission</h1>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="" id="frm-permit">
                        <input type="hidden" name="id_permit" id="id_permit">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" name="nama" class="form-control" id="inputpermit"
                                    placeholder="Nama Permission" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-keluar" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-update">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('js/register.js') }}"></script>
@endpush
