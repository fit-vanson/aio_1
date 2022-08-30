

@extends('layouts.master-blank')
@section('css')

    <!-- Sweet-Alert  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
@endsection

@section('content')
    <div class="home-btn d-none d-sm-block">
        <a class="text-danger" href="{{route('logout')}}"><i class="mdi mdi-power text-danger"></i> Logout</a>

        <a href="{{route('index')}}" class="text-dark"><i class="fas fa-home h2"></i></a>
    </div>

    <div class="wrapper-page">
        <div class="card overflow-hidden account-card mx-3">
            <div class="bg-primary p-4 text-white text-center position-relative">
                <h4 class="font-20 m-b-5">Two-factor authentication !</h4>
                <p class="text-white-50 mb-4">authentication to continue.</p>
                <a href="index" class="logo logo-admin"><img src="{{asset('public/backend/assets/images/logo-sm.png')}}" height="60" alt="logo"></a>
            </div>

            <div class="account-card-content">
                <form id="2fa_verify" name="2fa_verify" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label" for="otp">
                            <b>Authentication code</b>
                        </label>
                        <input type="text" id='code' name="code" class="form-control" placeholder="123456" autocomplete="off" maxlength="6" id="otp">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary w-md waves-effect waves-light">Verify</button>

                    </div>
                    <p class="text-muted">
                        Open the two-factor authentication app on your device to view your authentication code and verify your identity.
                    </p>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#2fa_verify').on('submit',function (event){
                event.preventDefault();

                $.ajax({
                    data: $('#2fa_verify').serialize(),
                    url: "{{ route('two_face.verify') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#code").notify(
                                    data.errors[count],"error"
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            window.location.href  = "{{ route('index') }}";
                        }
                    },
                });
            });

        });

    </script>

@endsection

