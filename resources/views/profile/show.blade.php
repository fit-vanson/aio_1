@extends('layouts.master')

@section('title') @lang('translation.General') @endsection

@section('content')

    <!-- start page title -->
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="font-size-18">Profile Fake</h4>
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
                    <div id="profile">
                        <p>Nhập mã Profile</p>
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


