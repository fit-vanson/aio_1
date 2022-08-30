@extends('layouts.master')

@section('css')

    <!-- Sweet-Alert  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title">Cập nhật 2FA</h4>
    </div>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form id="2fa_enable" name="2fa_enable" class="form-horizontal">
                            <h2>Scan barcode</h2>
                            <p class="text-muted">
                                Scan the image above with the two-factor authentication app on your phone.
                            </p>
                            <p class="text-center">
                                <img src="{{ $qrCodeUrl }}" />
                            </p>
                            <h5>Enter the six-digit code from the application</h5>
                            <p class="text-muted">
                                After scanning the barcode image, the app will display a six-digit code that you can enter below.
                            </p>
                            <div class="form-group">
                                <input type="text" id="code" name="code" class="form-control" placeholder="123456" autocomplete="off" maxlength="6">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="saveBtn" value="create-2fa">Kích hoạt
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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

            $('#2fa_enable').on('submit',function (event){
                event.preventDefault();
                if($('#saveBtn').val() == 'create-2fa'){
                    $.ajax({
                        data: $('#2fa_enable').serialize(),
                        url: "{{ route('enable_2fa_setting') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
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
                }

            });

        });

    </script>

@endsection


