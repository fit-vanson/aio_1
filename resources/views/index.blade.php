@extends('layouts.master')

@section('css')

    <!-- Sweet-Alert  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>



@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title">Trang chủ</h4>
    </div>
@endsection
@section('content')
    <?php
    $secretCode = auth()->user()->secret_code;
    if(!$secretCode)
    {
    ?>
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
    <?php }else{ ?>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/01.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Project</h5>
                        <h4 class="font-weight-medium font-size-24">{{$project}}</h4>
                        <?php
                        if($projectLastMonth != 0){
                            $diff = number_format(($projectInMonth - $projectLastMonth)/ $projectLastMonth *100,2);
                            if($diff > 0 ){
                        ?>
                        <div class="mini-stat-label bg-success">
                            <p class="mb-0">+ {{$diff}}%</p>
                        </div>
                        <?php }else{ ?>
                        <div class="mini-stat-label bg-danger">
                            <p class="mb-0">{{$diff}}%</p>
                        </div>
                        <?php }
                            }
                            ?>
                    </div>
                    <div class="pt-2">
                        <div class="float-right">
                            <p class="text-white-50">{{$projectInMonth}}</i></p>
                            <p class="text-white-50">{{$projectLastMonth}}</i></p>
                        </div>
                        <p class="text-white-50 mb-0 mt-1">In month</p>
                        <p class="text-white-50 mb-0 mt-1">Last month</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat text-white" style="background: #7baba3 !important;">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('img/icon/google.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">CHplay</h5>
                        <h4 class="font-weight-medium font-size-24">{{array_sum($Chplay_status)}} </h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-right">
                            <p class="text-white-50 mb-0 mt-1">{{$Chplay_status[1]}}</p>
                            <p class="text-white-50 mb-0 mt-1">{{$Chplay_status[6]}}</p>
                        </div>
                        <p class="text-white-50 mb-0 mt-1">Public</p>
                        <p class="text-white-50 mb-0 mt-1">Check</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat text-white" style="background: #0b7cd6 !important;">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('img/icon/amazon.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Amazon</h5>
                        <h4 class="font-weight-medium font-size-24">{{array_sum($Amazon_status)}} </h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-right">
                            <p class="text-white-50 mb-0 mt-1">{{@$Amazon_status[1]}}</p>
                            <p class="text-white-50 mb-0 mt-1">{{@$Amazon_status[6]}}</p>
                        </div>
                        <p class="text-white-50 mb-0 mt-1">Public</p>
                        <p class="text-white-50 mb-0 mt-1">Check</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat text-white" style="background: #c903ea !important;">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('img/icon/samsung.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Samsung</h5>
                        <h4 class="font-weight-medium font-size-24">{{array_sum($Samsung_status)}} </h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-right">
                            <p class="text-white-50 mb-0 mt-1">{{@$Samsung_status[1]}}</p>
                            <p class="text-white-50 mb-0 mt-1">{{@$Samsung_status[6]}}</p>
                        </div>
                        <p class="text-white-50 mb-0 mt-1">Public</p>
                        <p class="text-white-50 mb-0 mt-1">Check</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat text-white" style="background: #ff6445 !important;">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('img/icon/xiaomi.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Xiaomi</h5>
                        <h4 class="font-weight-medium font-size-24">{{array_sum($Xiaomi_status)}} </h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-right">
                            <p class="text-white-50 mb-0 mt-1">{{@$Xiaomi_status[1]}}</p>
                            <p class="text-white-50 mb-0 mt-1">{{@$Xiaomi_status[6]}}</p>
                        </div>
                        <p class="text-white-50 mb-0 mt-1">Public</p>
                        <p class="text-white-50 mb-0 mt-1">Check</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat text-white" style="background: #1acf7d !important;">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('img/icon/oppo.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Oppo</h5>
                        <h4 class="font-weight-medium font-size-24">{{array_sum($Oppo_status)}} </h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-right">
                            <p class="text-white-50 mb-0 mt-1">{{@$Oppo_status[1]}}</p>
                            <p class="text-white-50 mb-0 mt-1">{{@$Oppo_status[6]}}</p>
                        </div>
                        <p class="text-white-50 mb-0 mt-1">Public</p>
                        <p class="text-white-50 mb-0 mt-1">Check</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat text-white" style="background: #20b7fe !important;">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('img/icon/vivo.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Vivo</h5>
                        <h4 class="font-weight-medium font-size-24">{{array_sum($Vivo_status)}} </h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-right">
                            <p class="text-white-50 mb-0 mt-1">{{@$Vivo_status[1]}}</p>
                            <p class="text-white-50 mb-0 mt-1">{{@$Vivo_status[6]}}</p>
                        </div>
                        <p class="text-white-50 mb-0 mt-1">Public</p>
                        <p class="text-white-50 mb-0 mt-1">Check</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat text-white" style="background: #f65657 !important;">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('img/icon/huawei.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Huawei</h5>
                        <h4 class="font-weight-medium font-size-24">{{array_sum($Huawei_status)}} </h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-right">
                            <p class="text-white-50 mb-0 mt-1">{{@$Huawei_status[1]}}</p>
                            <p class="text-white-50 mb-0 mt-1">{{@$Huawei_status[6]}}</p>
                        </div>
                        <p class="text-white-50 mb-0 mt-1">Public</p>
                        <p class="text-white-50 mb-0 mt-1">Check</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

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
                                $('.container').hide();

                            }
                        },
                    });
                }

            });

        });

    </script>

@endsection


