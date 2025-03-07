@extends('layout.app')

{{-- Customize layout sections --}}


@section('content_header_title', 'Profile')


{{-- Content body: main page content --}}

@section('content_body')
    <div class="card card-primary card-outline">
        <div class="card-body box-profile">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="{{ asset('/assets/img/userweb.png') }}"
                    alt="User profile picture">
            </div>

            <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
            <h3 class="profile-username text-center">NIK : {{ Auth::user()->nik }}</h3>

            <div class="callout callout-info">
                <h4>Section</h4>

                <ul>
                    @foreach (Auth::user()->departments as $d)
                        <li>{{ $d->section }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="callout callout-info">
                <h4>Role & Permission</h4>

                <ul>
                    @foreach (Auth::user()->roles as $r)
                        <li>{{ $r->name }}
                            <ul>
                                @foreach ($r->permissions as $p)
                                    <li>{{ $p->name }}</li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>



            <a href="#" class="btn btn-block btn-update" id="btn-pass"><b>Ganti Password</b></a>
        </div>
        <!-- /.card-body -->
    </div>


    <!-- Modal ganti password-->
    <div class="modal fade" id="gntpass-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Ganti Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-gantipassword" method="POST">
                        @csrf

                        <input type="hidden" id="id-user" name="id-user">
                        <div class="row">
                            <div class="col col-md-4"><label>User Name</label></div>
                            <label class="col col-md-1" style="text-align: right">:</label>
                            <div class="col col-md-7">

                                <label id="edit-user">{{ Auth::user()->name }}</label>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 1%">
                            <div class="col col-md-4"><label>Password Lama</label></div>
                            <label class="col col-md-1" style="text-align: right">:</label>
                            <div class="col col-md-7">
                                <input type="password" id="edit-passlama" name="passlama" class="form-control" required>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 1%">
                            <div class="col col-md-4"><label>Password Baru</label></div>
                            <label class="col col-md-1" style="text-align: right">:</label>
                            <div class="col col-md-7">

                                <input type="password" name="newpass1" id="newpass1" class="form-control" required>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 1%">
                            <div class="col col-md-4"><label>Password Baru</label></div>
                            <label class="col col-md-1" style="text-align: right">:</label>
                            <div class="col col-md-7">

                                <input type="password" name="newpass2" id="newpass2" class="form-control" required>
                            </div>
                        </div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-close" data-dismiss="modal">Close</button>

                            <input type="submit" class="btn btn-update" id="btn-save" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop


{{-- Push extra scripts --}}

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#btn-pass").click(function() {
                $("#gntpass-modal").modal("toggle");
            });
            $("#form-gantipassword").submit(function(e) {
                e.preventDefault();
                var d = $(this).serialize();
                if ($("#newpass1").val() != $("#newpass2").val()) {
                    fireAlert("error", "Password baru tidak sama !");
                } else {
                    Swal.fire({
                        title: "Apakah anda akan merubah password?",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonText: "Update",
                        confirmButtonColor: "#d33",
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: APP_BACKEND + "user/update-pass",
                                type: 'POST',
                                dataType: 'json',
                                data: d,
                                beforeSend: function(xhr) {
                                    xhr.setRequestHeader("Authorization", "Bearer " +
                                        key);
                                },
                            }).done(function(resp) {
                                if (resp.success) {
                                    $("#gntpass-modal").modal("toggle");
                                    fireAlert("success", resp.message).then(function() {
                                        window.location.href =
                                            "{{ route('logout') }}"
                                    });
                                } else {
                                    fireAlert("error", resp.message);
                                }


                            });
                        }
                    });
                }

            });
        });
    </script>
@endpush
