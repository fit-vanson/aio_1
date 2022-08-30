@extends('layouts.master')

@section('css')

<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />



<!-- Sweet-Alert  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>




@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Quản lý Template</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
        <a class="btn btn-success" href="javascript:void(0)" id="createNewTemplate"> Create New Template</a>
    </div>
</div>
@include('modals.template')
@endsection
@section('content')
    <?php
    $message =Session::get('message');
    if($message){
        echo  '<span class="splash-message" style="color:#2a75f3">'.$message.'</span>';
        Session::put('message',null);
    }
    ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
{{--                    <table class="table table-bordered data-table">--}}
                     <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Tên Template</th>
                            <th>Phân loại</th>
                            <th>Thông tin Template</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')
<!-- Required datatable js -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="plugins/datatables/dataTables.buttons.min.js"></script>
<script src="plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="plugins/datatables/jszip.min.js"></script>
<script src="plugins/datatables/pdfmake.min.js"></script>
<script src="plugins/datatables/vfs_fonts.js"></script>
<script src="plugins/datatables/buttons.html5.min.js"></script>
<script src="plugins/datatables/buttons.print.min.js"></script>
<script src="plugins/datatables/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="plugins/datatables/dataTables.responsive.min.js"></script>
<script src="plugins/datatables/responsive.bootstrap4.min.js"></script>

<!-- Datatable init js -->
<script src="assets/pages/datatables.init.js"></script>
<!-- Moment.js: -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js"></script>

<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            displayLength: 50,
            ajax: {
                url: "{{ route('template.getIndex') }}",
                type: "post"
            },
            columns: [
                {data: 'logo'},
                {data: 'template'},
                {data: 'category'},
                {data: 'script'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
            "columnDefs": [
                { "orderable": false, "targets": [0,2,3] }
            ],
            order:[1,'asc']

        });

        $('#createNewTemplate').click(function () {
            $('#saveBtn').val("create-template");
            $('#template_id').val('');
            $('#templateForm').trigger("reset");
            $('#modelHeading').html("Thêm mới Template");
            $('#ajaxModel').modal('show');
            $('.input_buildinfo_console').hide();
            $('.input_api').hide();
        });
        $('#templateForm').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#templateForm")[0]);
            if($('#saveBtn').val() == 'create-template'){
                $.ajax({
                    data: formData,
                    url: "{{ route('template.create') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#templateForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#templateForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-template'){
                $.ajax({
                    data: formData,
                    url: "{{ route('template.update') }}",
                    type: "post",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#templateForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#templateForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });

            }

        });


        $(document).on('click','.deleteTemplate', function (data){
            var template_id = $(this).data("id");
            swal({
                    title: "Bạn có chắc muốn xóa?",
                    text: "Your will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Xác nhận xóa!",
                    closeOnConfirm: false
                },
                function(){
                    $.ajax({
                        type: "get",
                        url: "{{ asset("template/delete") }}/" + template_id,
                        success: function (data) {
                            table.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                    swal("Đã xóa!", "Your imaginary file has been deleted.", "success");
                });
        });

        $(document).on('click','.checkDataTemplate', function (data){
            var id = $(this).data("id");
            swal({
                    title: "Bạn có chắc muốn check Data?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Xác nhận!",
                    closeOnConfirm: false
                },
                function(){
                    $.ajax({
                        type: "get",
                        url: "{{ asset("project/checkData") }}/" +id,
                        success: function (data) {
                            table.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                    swal("OK!", '', "success");
                });
        });

    });
</script>

<script>
    function editTemplate(id) {
        $.get('{{asset('template/edit')}}/'+id,function (data) {
            if(data.ads != null){
                var ads = jQuery.parseJSON(data.ads);
                if(ads.ads_id !=null){
                    $("#Check_ads_id").prop('checked', true);
                }else{
                    $("#Check_ads_id").prop('checked', false);
                }

                if(ads.ads_banner !=null){
                    $("#Check_ads_banner").prop('checked', true);
                }else{
                    $("#Check_ads_banner").prop('checked', false);
                }

                if(ads.ads_inter!=null){
                    $("#Check_ads_inter").prop('checked', true);
                }else{
                    $("#Check_ads_inter").prop('checked', false);
                }

                if(ads.ads_reward !=null){
                    $("#Check_ads_reward").prop('checked', true);
                }else{
                    $("#Check_ads_reward").prop('checked', false);
                }

                if(ads.ads_native !=null){
                    $("#Check_ads_native").prop('checked', true);
                }else{
                    $("#Check_ads_native").prop('checked', false);
                }

                if(ads.ads_open !=null){
                    $("#Check_ads_open").prop('checked', true);
                }else{
                    $("#Check_ads_open").prop('checked', false);
                }

                if(ads.ads_start !=null){
                    $("#Check_ads_start").prop('checked', true);
                }else{
                    $("#Check_ads_start").prop('checked', false);
                }

                if(ads.ads_banner_huawei !=null){
                    $("#Check_ads_banner_huawei").prop('checked', true);
                }else{
                    $("#Check_ads_banner_huawei").prop('checked', false);
                }

                if(ads.ads_native_huawei !=null){
                    $("#Check_ads_native_huawei").prop('checked', true);
                }else{
                    $("#Check_ads_native_huawei").prop('checked', false);
                }

                if(ads.ads_reward_huawei !=null){
                    $("#Check_ads_reward_huawei").prop('checked', true);
                }else{
                    $("#Check_ads_reward_huawei").prop('checked', false);
                }

                if(ads.ads_inter_huawei !=null){
                    $("#Check_ads_inter_huawei").prop('checked', true);
                }else{
                    $("#Check_ads_inter_huawei").prop('checked', false);
                }

                if(ads.ads_splash_huawei !=null){
                    $("#Check_ads_splash_huawei").prop('checked', true);
                }else{
                    $("#Check_ads_splash_huawei").prop('checked', false);
                }

                if(ads.ads_roll_huawei !=null){
                    $("#Check_ads_roll_huawei").prop('checked', true);
                }else{
                    $("#Check_ads_roll_huawei").prop('checked', false);
                }

            } else {
                $("#Check_ads_id").prop('checked', false);
                $("#Check_ads_banner").prop('checked', false);
                $("#Check_ads_inter").prop('checked', false);
                $("#Check_ads_reward").prop('checked', false);
                $("#Check_ads_native").prop('checked', false);
                $("#Check_ads_open").prop('checked', false);
                $("#Check_ads_start").prop('checked', false);

                $("#Check_ads_roll_huawei").prop('checked', false);
                $("#Check_ads_banner_huawei").prop('checked', false);
                $("#Check_ads_inter_huawei").prop('checked', false);
                $("#Check_ads_reward_huawei").prop('checked', false);
                $("#Check_ads_native_huawei").prop('checked', false);
                $("#Check_ads_splash_huawei").prop('checked', false);
            }
            if(data.logo) {
                $("#avatar").attr("src","../uploads/template/"+data.template+"/thumbnail/"+data.logo);
            }else {
                $("#avatar").attr("src","img/logo.png");
            }

            $('#template_id').val(data.id);
            $('#template').val(data.template);
            $('#template_name').val(data.template_name);
            $('#ver_build').val(data.ver_build);
            $('#script_copy').val(data.script_copy);
            $('#script_img').val(data.script_img);
            $('#script_svg2xml').val(data.script_svg2xml);
            $('#script_file').val(data.script_file);
            $('#permissions').val(data.permissions);
            $('#policy1').val(data.policy1);
            $('#policy2').val(data.policy2);
            $('#note').val(data.note);
            $('#link').val(data.link);
            $('#package').val(data.package);
            $('#convert_aab').val(data.convert_aab);
            $('#startus').val(data.startus);
            $('#link_store_vietmmo').val(data.link_store_vietmmo);
            $('#Chplay_category').val(data.Chplay_category);
            $('#Amazon_category').val(data.Amazon_category);
            $('#Samsung_category').val(data.Samsung_category);
            $('#Xiaomi_category').val(data.Xiaomi_category);
            $('#Oppo_category').val(data.Oppo_category);
            $('#Vivo_category').val(data.Vivo_category);
            $('#Huawei_category').val(data.Huawei_category);

            $('#modelHeading').html("Edit");
            $('#saveBtn').val("edit-template");
            $('#ajaxModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        })
    }




</script>



@endsection






