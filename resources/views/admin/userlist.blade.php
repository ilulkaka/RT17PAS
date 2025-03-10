@section('plugins.Select2', true)
@extends('layout.app')

{{-- Customize layout sections --}}


@section('content_header_title', 'User Lists')


{{-- Content body: main page content --}}

@section('content_body')

    <div class="card">
        <div class="card-header">
            <div class="card-header ui-sortable-handle">
                <h3 class="card-title">
                    <i class="fas fa-user"> List User</i>
                </h3>
                <button class="btn btn-tambah float-right" id="btn-add-user"><i class="fas fa-plus"></i></button>
            </div><!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="tb_user">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>NIK</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer">

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 card-title" id="userModalLabel">Tambah User</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="" id="frm-user">
                        <input type="hidden" name="id_user" id="id_user">
                        {{-- <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">NIK</label>
                            <div class="col-sm-10">
                                <input type="text" name="nik" class="form-control" id="nikuser"
                                    placeholder="NIK User" required>
                            </div>
                        </div> --}}
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" name="nama" class="form-control" id="inputuser"
                                    placeholder="Nama User" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" name="email" class="form-control" id="emailuser"
                                    placeholder="Email User" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" class="form-control" id="passuser"
                                    placeholder="Password User" required>
                            </div>
                            <button type="button" class="btn btn-primary" id="btn-reset">Reset</button>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Status User</label>
                            <div class="custom-control custom-switch col-sm-3">
                                <input type="checkbox" name="status" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">ON</label>
                            </div>
                        </div>
                        <div class="row justify-content-md-center">
                            <label for="">Department</label>
                        </div>
                        <div class="form-group row">
                            <select name="department[]" id="department" multiple="multiple"
                                class="select2 select2-hidden-accessible" data-placeholder="Pilih Department"
                                style="width: 100%;" data-select2-id="7" tabindex="-1" aria-hidden="true" required>
                            </select>

                            </select>

                        </div>
                        <div class="row justify-content-md-center">
                            <label for="">Role</label>
                        </div>
                        <div class="form-group row">
                            <select name="role[]" id="role" multiple="multiple"
                                class="select2 select2-hidden-accessible" data-placeholder="Pilih Role" style="width: 100%;"
                                data-select2-id="7" tabindex="-1" aria-hidden="true" required>

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
@stop


@push('css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('js/userlist.js') }}"></script>
@endpush
