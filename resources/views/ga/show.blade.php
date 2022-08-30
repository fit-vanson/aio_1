@extends('layouts.master')

@section('title') @lang('translation.General') @endsection

@section('content')

    <!-- start page title -->
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="font-size-18">Thông tin chi tiết</h4>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div data-repeater-list="group-a">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Mã ID</label>
                                <input type="text" id="profileID" name="profileID" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div id="ga_detail">
                        </tbody></table>
                        <h4 class="card-title">Thông tin - <button type="button" class="btn btn-success waves-effect button" id="detail_ga_name">{{$ga_detail->ga_name}}</button></h4>
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                            <tr>
                                <th class="text-center">Gmail</th>
                                <th class="text-center">Mã APP-ADS</th>
                                <th class="text-center">Phương thức thanh toán</th>
                                <th class="text-center">Số điện thoại</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Địa chỉ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-nowrap">
                                    <button class="btn btn-light waves-effect button" id="detail_gmail">
                                        @if($ga_detail->gadev)<span>{{$ga_detail->gadev->gmail}}<span style="font-style: italic"> - {{$ga_detail->gadev->vpn_iplogin}}</span></span>@endif
                                        @if($ga_detail->gadev1)<p style="margin: auto" class="text-muted ">{{$ga_detail->gadev1->gmail}} - <span style="font-style: italic"> {{$ga_detail->gadev1->vpn_iplogin}}</span></p>@endif
                                        @if($ga_detail->gadev2)<p style="margin: auto" class="text-muted ">{{$ga_detail->gadev2->gmail}} - <span style="font-style: italic"> {{$ga_detail->gadev2->vpn_iplogin}}</span></p>@endif
                                    </button>
                                </td>
                                <td><button class="btn btn-light waves-effect button" id="profile_ngay_sinh">{{$ga_detail->app_ads}}</button></td>
                                <td><button class="btn btn-light waves-effect button" id="profile_sex">{{$ga_detail->payment}}</button></td>
                                <td><button class="btn btn-light waves-effect button" id="profile_cccd">{{$ga_detail->info_phone}}</button></td>
                                <td>
                                    <button class="btn btn-light waves-effect button" id="profile_ngay_cap">
                                        @if($ga_detail->status == 0 )  <span class="badge badge-dark">Chưa xử dụng</span> @endif
                                        @if($ga_detail->status == 1 )  <span class="badge badge-primary">Đang sử dụng</span> @endif
                                        @if($ga_detail->status == 2 )  <span class="badge badge-warning">Tụt Match Rate</span> @endif
                                        @if($ga_detail->status == 3 )  <span class="badge badge-danger">Disable</span> @endif
                                    </button>
                                </td>
                                <td><button class="btn btn-light waves-effect button" id="profile_add">{{$ga_detail->info_andress}}</button></td>
                                </tbody></table>
                        <br>
                        <h5 class="card-title-desc">Thông tin DEV</h5>
                        @if(count($ga_detail->dev)>0)
                        <h6 class="card-title-desc"> <img src="img/icon/google.png"> Ch-Play </h6>
                        <table class="table table-bordered table-striped mb-0">
                                <thead>
                                <tr>
                                    <th>Dev Name</th>
                                    <th>Gmail - Pass</th>
                                    <th>Profile Info</th>
                                    <th>Tổng app</th>
                                    <th>Trạng thái</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ga_detail->dev as $dev_chplay)
                                    <tr>
                                        <td>
                                            <span>{{$dev_chplay->dev_name}}</span>
                                            <p style="margin: auto" class="text-muted ">{{$dev_chplay->store_name}}</p>
                                        </td>
                                        <td>
                                            <span>{{$dev_chplay->gadev->gmail}} - {{$dev_chplay->pass ? $dev_chplay->pass : 'null' }}</span>
                                        </td>
                                        <td>
                                            @if($dev_chplay->thuoc_tinh ==1) <span class="badge badge-secondary">Cá nhân</span> @endif
                                            @if($dev_chplay->thuoc_tinh ==0) <span class="badge badge-success">Công ty</span>
                                                <p style="margin: auto" class="text-muted ">{{$dev_chplay->info_company}}</p>
                                                @endif
                                                <p style="margin: auto" class="text-muted ">{{$dev_chplay->info_andress}}</p>
                                        </td>
                                        <td>
                                            <span>{{count($dev_chplay->project)}}</span>

                                        </td>
                                        <td>
                                            @if($dev_chplay->status == 0 )  <span class="badge badge-dark">Chưa xử dụng</span> @endif
                                            @if($dev_chplay->status == 1 )  <span class="badge badge-primary">Đang phát triển</span> @endif
                                            @if($dev_chplay->status == 2 )  <span class="badge badge-warning">Đóng</span> @endif
                                            @if($dev_chplay->status == 3 )  <span class="badge badge-danger">Suspend</span> @endif

                                        </td>
                                    <tr>
                                @endforeach


                                </tbody>
                            </table>
                        @endif
                        @if(count($ga_detail->dev_amazon)>0)
                        <h6 class="card-title-desc"> <img src="img/icon/amazon.png"> Amazon </h6>
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Dev Name</th>
                                <th>Gmail - Pass</th>
                                <th>Profile Info</th>
                                <th>Tổng app</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ga_detail->dev_amazon as $dev_amazon)
                                <tr>
                                    <td>
                                        <span>{{$dev_amazon->amazon_dev_name}}</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_amazon->amazon_store_name}}</p>
                                    </td>
                                    <td>
                                        <span>{{$dev_amazon->gadev->gmail}} - {{$dev_amazon->amazon_pass ? $dev_amazon->amazon_pass : 'null' }}</span>
                                    </td>
                                    <td>
                                        @if($dev_amazon->amazon_attribute ==1) <span class="badge badge-secondary">Cá nhân</span> @endif
                                        @if($dev_amazon->amazon_attribute ==0) <span class="badge badge-success">Công ty</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_amazon->amazon_company}}</p>
                                        @endif
                                        <p style="margin: auto" class="text-muted ">{{$dev_amazon->amazon_add}}</p>
                                    </td>
                                    <td>
                                        <span>{{count($dev_amazon->project)}}</span>

                                    </td>
                                    <td>
                                        @if($dev_amazon->amazon_status == 0 )  <span class="badge badge-dark">Chưa xử dụng</span> @endif
                                        @if($dev_amazon->amazon_status == 1 )  <span class="badge badge-primary">Đang phát triển</span> @endif
                                        @if($dev_amazon->amazon_status == 2 )  <span class="badge badge-warning">Đóng</span> @endif
                                        @if($dev_amazon->amazon_status == 3 )  <span class="badge badge-danger">Suspend</span> @endif

                                    </td>
                                <tr>
                            @endforeach


                            </tbody>
                        </table>
                        @endif
                        @if(count($ga_detail->dev_samsung)>0)
                        <h6 class="card-title-desc"> <img src="img/icon/samsung.png"> Samsung </h6>
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Dev Name</th>
                                <th>Gmail - Pass</th>
                                <th>Profile Info</th>
                                <th>Tổng app</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ga_detail->dev_samsung as $dev_samsung)
                                <tr>
                                    <td>
                                        <span>{{$dev_samsung->samsung_dev_name}}</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_samsung->samsung_store_name}}</p>
                                    </td>
                                    <td>
                                        <span>{{$dev_samsung->gadev->gmail}} - {{$dev_samsung->samsung_pass ? $dev_amazon->samsung_pass : 'null' }}</span>
                                    </td>
                                    <td>
                                        @if($dev_samsung->samsung_attribute ==1) <span class="badge badge-secondary">Cá nhân</span> @endif
                                        @if($dev_samsung->samsung_attribute ==0) <span class="badge badge-success">Công ty</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_samsung->samsung_company}}</p>
                                        @endif
                                        <p style="margin: auto" class="text-muted ">{{$dev_samsung->samsung_add}}</p>
                                    </td>
                                    <td>
                                        <span>{{count($dev_samsung->project)}}</span>

                                    </td>
                                    <td>
                                        @if($dev_samsung->samsung_status == 0 )  <span class="badge badge-dark">Chưa xử dụng</span> @endif
                                        @if($dev_samsung->samsung_status == 1 )  <span class="badge badge-primary">Đang phát triển</span> @endif
                                        @if($dev_samsung->samsung_status == 2 )  <span class="badge badge-warning">Đóng</span> @endif
                                        @if($dev_samsung->samsung_status == 3 )  <span class="badge badge-danger">Suspend</span> @endif

                                    </td>
                                <tr>
                            @endforeach


                            </tbody>
                        </table>
                        @endif
                        @if(count($ga_detail->dev_xiaomi) >0)
                        <h6 class="card-title-desc"> <img src="img/icon/xiaomi.png"> Xiaomi </h6>
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Dev Name</th>
                                <th>Gmail - Pass</th>
                                <th>Profile Info</th>
                                <th>Tổng app</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ga_detail->dev_xiaomi as $dev_xiaomi)
                                <tr>
                                    <td>
                                        <span>{{$dev_xiaomi->xiaomi_dev_name}}</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_xiaomi->xiaomi_store_name}}</p>
                                    </td>
                                    <td>
                                        <span>{{$dev_xiaomi->gadev->gmail}} - {{$dev_xiaomi->pass ? $dev_xiaomi->pass : 'null' }}</span>
                                    </td>
                                    <td>
                                        @if($dev_xiaomi->xiaomi_attribute ==1) <span class="badge badge-secondary">Cá nhân</span> @endif
                                        @if($dev_xiaomi->xiaomi_attribute ==0) <span class="badge badge-success">Công ty</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_xiaomi->xiaomi_company}}</p>
                                        @endif
                                        <p style="margin: auto" class="text-muted ">{{$dev_xiaomi->xiaomi_add}}</p>
                                    </td>
                                    <td>
                                        <span>{{count($dev_xiaomi->project)}}</span>

                                    </td>
                                    <td>
                                        @if($dev_xiaomi->xiaomi_status == 0 )  <span class="badge badge-dark">Chưa xử dụng</span> @endif
                                        @if($dev_xiaomi->xiaomi_status == 1 )  <span class="badge badge-primary">Đang phát triển</span> @endif
                                        @if($dev_xiaomi->xiaomi_status == 2 )  <span class="badge badge-warning">Đóng</span> @endif
                                        @if($dev_xiaomi->xiaomi_status == 3 )  <span class="badge badge-danger">Suspend</span> @endif

                                    </td>
                                <tr>
                            @endforeach


                            </tbody>
                        </table>
                        @endif
                        @if(count($ga_detail->dev_oppo)>0)
                        <h6 class="card-title-desc"> <img src="img/icon/oppo.png"> Oppo </h6>
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Dev Name</th>
                                <th>Gmail - Pass</th>
                                <th>Profile Info</th>
                                <th>Tổng app</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ga_detail->dev_oppo as $dev_oppo)
                                <tr>
                                    <td>
                                        <span>{{$dev_oppo->oppo_dev_name}}</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_oppo->oppo_store_name}}</p>
                                    </td>
                                    <td>
                                        <span>{{$dev_oppo->gadev->gmail}} - {{$dev_oppo->pass ? $dev_oppo->pass : 'null' }}</span>
                                    </td>
                                    <td>
                                        @if($dev_oppo->oppo_attribute ==1) <span class="badge badge-secondary">Cá nhân</span> @endif
                                        @if($dev_oppo->oppo_attribute ==0) <span class="badge badge-success">Công ty</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_oppo->oppo_company}}</p>
                                        @endif
                                        <p style="margin: auto" class="text-muted ">{{$dev_oppo->oppo_add}}</p>
                                    </td>
                                    <td>
                                        <span>{{count($dev_oppo->project)}}</span>

                                    </td>
                                    <td>
                                        @if($dev_oppo->oppo_status == 0 )  <span class="badge badge-dark">Chưa xử dụng</span> @endif
                                        @if($dev_oppo->oppo_status == 1 )  <span class="badge badge-primary">Đang phát triển</span> @endif
                                        @if($dev_oppo->oppo_status == 2 )  <span class="badge badge-warning">Đóng</span> @endif
                                        @if($dev_oppo->oppo_status == 3 )  <span class="badge badge-danger">Suspend</span> @endif

                                    </td>
                                <tr>
                            @endforeach


                            </tbody>
                        </table>
                        @endif
                        @if(count($ga_detail->dev_vivo)>0)
                        <h6 class="card-title-desc"> <img src="img/icon/vivo.png"> Vivo </h6>
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Dev Name</th>
                                <th>Gmail - Pass</th>
                                <th>Profile Info</th>
                                <th>Tổng app</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ga_detail->dev_vivo as $dev_vivo)
                                <tr>
                                    <td>
                                        <span>{{$dev_vivo->vivo_dev_name}}</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_vivo->vivo_store_name}}</p>
                                    </td>
                                    <td>
                                        <span>{{$dev_vivo->ga_dev->gmail}} - {{$dev_vivo->pass ? $dev_vivo->pass : 'null' }}</span>
                                    </td>
                                    <td>
                                        @if($dev_vivo->vivo_attribute ==1) <span class="badge badge-secondary">Cá nhân</span> @endif
                                        @if($dev_vivo->vivo_attribute ==0) <span class="badge badge-success">Công ty</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_vivo->vivo_company}}</p>
                                        @endif
                                        <p style="margin: auto" class="text-muted ">{{$dev_vivo->vivo_add}}</p>
                                    </td>
                                    <td>
                                        <span>{{count($dev_vivo->project)}}</span>

                                    </td>
                                    <td>
                                        @if($dev_vivo->vivo_status == 0 )  <span class="badge badge-dark">Chưa xử dụng</span> @endif
                                        @if($dev_vivo->vivo_status == 1 )  <span class="badge badge-primary">Đang phát triển</span> @endif
                                        @if($dev_vivo->vivo_status == 2 )  <span class="badge badge-warning">Đóng</span> @endif
                                        @if($dev_vivo->vivo_status == 3 )  <span class="badge badge-danger">Suspend</span> @endif

                                    </td>
                                <tr>
                            @endforeach


                            </tbody>
                        </table>
                        @endif
                        @if(count($ga_detail->dev_huawei)>0)
                        <h6 class="card-title-desc"> <img src="img/icon/huawei.png"> Huawei </h6>
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Dev Name</th>
                                <th>Gmail - Pass</th>
                                <th>Profile Info</th>
                                <th>Tổng app</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ga_detail->dev_huawei as $dev_huawei)
                                <tr>
                                    <td>
                                        <span>{{$dev_huawei->huawei_dev_name}}</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_huawei->huawei_store_name}}</p>
                                    </td>
                                    <td>
                                        <span>{{$dev_huawei->gadev->gmail}} - {{$dev_huawei->pass ? $dev_huawei->pass : 'null' }}</span>
                                    </td>
                                    <td>
                                        @if($dev_huawei->huawei_attribute ==1) <span class="badge badge-secondary">Cá nhân</span> @endif
                                        @if($dev_huawei->huawei_attribute ==0) <span class="badge badge-success">Công ty</span>
                                        <p style="margin: auto" class="text-muted ">{{$dev_huawei->huawei_company}}</p>
                                        @endif
                                        <p style="margin: auto" class="text-muted ">{{$dev_huawei->huawei_add}}</p>
                                    </td>
                                    <td>
                                        <span>{{count($dev_huawei->project)}}</span>

                                    </td>
                                    <td>
                                        @if($dev_huawei->huawei_status == 0 )  <span class="badge badge-dark">Chưa xử dụng</span> @endif
                                        @if($dev_huawei->huawei_status == 1 )  <span class="badge badge-primary">Đang phát triển</span> @endif
                                        @if($dev_huawei->huawei_status == 2 )  <span class="badge badge-warning">Đóng</span> @endif
                                        @if($dev_huawei->huawei_status == 3 )  <span class="badge badge-danger">Suspend</span> @endif

                                    </td>
                                <tr>
                            @endforeach


                            </tbody>
                        </table>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- end row -->


@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('input[name=profileID]').on('change', function() {
                var id = $('#profileID').val();
                $.ajax({
                    url: "{{ route('profile.show') }}?profileID="+id,
                    type: "get",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.error){
                            $('#profile').html(data.error);
                        }
                        if(data.success){
                            var html = '';
                            var company = '<table class="table table-bordered table-striped mb-0">'+
                                '<thead>'+
                                '<tr>'+
                                '<th class="text-center">MST</th>'+
                                '<th class="text-center">Tên Công ty Tiếng Anh</th>'+
                                '<th class="text-center">Tên Công ty Tiếng Việt</th>'+
                                '<th class="text-center">Ngày thành lập</th>'+
                                '<th class="text-center">Địa chỉ</th>'+
                                '</tr>'+
                                '</thead>'+
                                '<tbody>';
                            data.profile.company.forEach(function(element) {
                                company +=
                                    '<tr>'+
                                    '<td><button type="button" class="btn btn-light waves-effect button" id="mst">'+element.mst+'</button></td>'+
                                    '<td><button type="button" class="btn btn-light waves-effect button" id="name_en">'+element.name_en+'</button></td>'+
                                    '<td><button type="button" class="btn btn-light waves-effect button" id="name_vi">'+element.name_vi+'</button></td>'+
                                    '<td><button type="button" class="btn btn-light waves-effect button" id="ngay_thanh_lap">'+element.ngay_thanh_lap+'</button></td>'+
                                    '<td><button type="button" class="btn btn-light waves-effect button" id="dia_chi">'+element.dia_chi+'</button></td>'+
                                    '<tr>'
                                ;
                            });
                            company += ' </tbody></table>';
                            html += '<h4 class="card-title">Thông tin cá nhân - <button type="button" class="btn btn-success waves-effect button" id="profile_name">'+data.profile.profile_name +'</button></h4>'+
                                '<table class="table table-bordered table-striped mb-0">'+
                                    '<thead>'+
                                    '<tr>'+
                                        '<th class="text-center">Họ và tên</th>'+
                                        '<th class="text-center">Ngày sinh</th>'+
                                        '<th class="text-center">Giới tính</th>'+
                                        '<th class="text-center">Số CCCD</th>'+
                                        '<th class="text-center">Ngày cấp</th>'+
                                        '<th class="text-center">Địa chỉ</th>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>'+
                                    '<tr>'+
                                        '<td class="text-nowrap"><button class="btn btn-light waves-effect button" id="profile_ho_va_ten">'+data.profile.profile_ho_va_ten +'</button></td>'+
                                        '<td><button class="btn btn-light waves-effect button" id="profile_ngay_sinh">'+data.profile.profile_ngay_sinh +'</button></td>'+
                                        '<td><button class="btn btn-light waves-effect button" id="profile_sex">'+data.profile.profile_sex +'</button></td>'+
                                        '<td><button class="btn btn-light waves-effect button" id="profile_cccd">'+data.profile.profile_cccd +'</button></td>'+
                                        '<td><button class="btn btn-light waves-effect button" id="profile_ngay_cap">'+data.profile.profile_ngay_cap +'</button></td>'+
                                        '<td><button class="btn btn-light waves-effect button" id="profile_add">'+data.profile.profile_add +'</button></td>'+
                                '</tbody></table>'+
                                '<br>'+
                                '<h5 class="card-title-desc">Thông tin công ty</h5>'+ company
                                '<br>';
                            $('#profile').html(html);
                        }
                    },
                });
            });

            $(document).on("click", ".button", function(){
                var copyText = this.innerText;
                var textarea = document.createElement('textarea');
                textarea.id = 'temp_element';
                textarea.style.height = 0;
                document.body.appendChild(textarea);
                textarea.value = copyText;
                var selector = document.querySelector('#temp_element')
                selector.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            });
        });


    </script>
@endsection


