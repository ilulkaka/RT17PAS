<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RT 17 PAS</title>

    <!-- Core CSS - Include with every page -->
    <link href="{{ asset('/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/plugins/fontawesome-free/css/all.min.css') }}">







</head>

<body>
    <div
        style="background-image: url('{{ asset('/assets/img/background-login.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 100vh; width: 100vw; display: flex; justify-content: center; align-items: center;">
        <!-- Optional content centered within the full-screen background -->
        <div style="text-align: center; background-color: rgba(255, 255, 255, 0.5); padding: 20px; ">


            <div class="text-center mb-4">
                <img src="{{ asset('/assets/img/RT17_Logo.png') }}" style="height: 150px; width: 150px;"
                    class="img-circle">
                <h3>Perum Pasuruan Anggun Sejahtera</h3>
                <h3 class="panel-title" style="padding-top:10px;padding-bottom:10px;"><b>Login</b></h3>
            </div>
            <hr>

            <div class="row">
                <div class="col-md-12">
                    <div id="error"></div>
                    @if (Session::has('alert'))
                        <div class="alert alert-danger">
                            <div>{{ Session::get('alert') }}</div>
                        </div>
                    @endif
                    <form role="form" id="login-form" method="post">
                        @csrf
                        <fieldset>

                            <div class="form-group row">
                                <strong style="text-align:left">NIK</strong>
                                <div class="col-md-12">
                                    <input class="form-control @error('username') is-invalid @enderror rounded-0"
                                        placeholder="NIK" name="nik" type="text" id="nik"
                                        value="{{ old('username') }}" autofocus required>
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top: 3%">
                                <strong style="text-align:left">Password</strong>
                                <div class="col-md-12">
                                    <input class="form-control @error('password') is-invalid @enderror rounded-0"
                                        placeholder="Password" name="password" type="password" id="pass"
                                        value="{{ old('password') }}" required>
                                </div>
                            </div>
                            <hr>
                            <br>
                            <div class="footer d-flex justify-content-between">
                                <button type="button" class="btn btn-lg btn-warning col-md-6 rounded-0" id="btn-guest"
                                    name="btn-guest" disabled>
                                    <i class="fa fa-user"></i> Guest
                                </button>
                                <button type="submit" class="btn btn-lg btn-success col-md-6 rounded-0" id="btn-login">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </button>
                            </div>
                            <br>

                            <div id="error">
                                <!--error message-->
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Core Scripts - Include with every page -->
    <script src="{{ asset('/assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- <script src="{{ asset('/assets/plugins/bootstrap/js/tether.min.js') }}"></script> -->
    <script src="{{ asset('/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}
    </script>






</body>

</html>

<script src="{{ asset('js/login.js') }}"></script>
