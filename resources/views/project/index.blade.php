@extends('layouts.master')

@section('css')

    <link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Sweet-Alert  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <!-- Select2 Js  -->
    <link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />


    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">


    <style>
        .dataTables_wrapper .button-items {
            margin-bottom: 5px;
        }

    </style>


@endsection

{{--@section('breadcrumb')--}}


{{--<div class="col-sm-6">--}}
{{--    @can('project-add')--}}
{{--    <div class="button-items console_status_button float-left">--}}
{{--        <button type="button" class="btn btn-success waves-effect waves-light" id="buildandcheck">Build and Check</button>--}}
{{--        <button type="button" class="btn btn-success waves-effect waves-light" id="dev_status">Update Dev and Status</button>--}}
{{--        <button type="button" class="btn btn-success waves-effect waves-light" id="change_keystore">Keystore</button>--}}
{{--        <button type="button" class="btn btn-success waves-effect waves-light" id="change_sdk">Sdk</button>--}}
{{--    </div>--}}
{{--    @endcan--}}
{{--</div>--}}
{{--@can('project-add')--}}
{{--<div class="col-sm-3">--}}
{{--    <div class="float-right">--}}
{{--        <a class="btn btn-success" href="javascript:void(0)" id="createNewProject"> Create New Project</a>--}}
{{--    </div>--}}
{{--</div>--}}
{{--@endcan--}}


{{--@endsection--}}
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered dt-responsive data-table"
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
{{--                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">--}}

                        <thead>
                        <tr>
                            <th >ID</th>
                            <th>Logo</th>
                            <th >Mã Project</th>
                            <th>Package</th>
                            <th>Trạng thái Ứng dụng | Policy</th>
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


    <div class="modal fade bd-example-modal-xl" id="showMess" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeadingPolicy"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="message-full"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('modals.project')
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
    <script src="plugins/select2/js/select2.min.js"></script>



    <script src="plugins/tinymce/tinymce.min.js"></script>
    <!--Summernote js-->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script src="assets/pages/form-editors.int.js"></script>



    <!-- form repeater -->
    <script src="assets/libs/jquery-repeater/jquery-repeater.min.js"></script>

    <!-- form repeater init js -->
    <script src="assets/js/pages/form-repeater.int.js"></script>

    <script>
        $("#template").select2({});
        $("#ma_da").select2({});
        $("#buildinfo_store_name_x").select2({});
        $("#buildinfo_keystore").select2({});
    </script>


    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = window.location.href;
            var hash = url.substring(url.indexOf('?')+1);
            $.fn.dataTable.ext.errMode = 'none';
            var table = $('.data-table').DataTable({
                displayLength: 50,
                lengthMenu: [5, 10, 25, 50, 75, 100],
                orderCellsTop: true,
                fixedHeader: true,
                // processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('project.getIndex')}}?"+hash,
                    type: "post"
                },
                columns: [
                    {data: 'ngocphandang_project.created_at', name: 'ngocphandang_project.created_at'},
                    {data: 'logo', name: 'logo',orderable: false},
                    {data: 'projectname', name: 'projectname'},
                    {data: 'Chplay_package', name: 'Chplay_package'},
                    {data: 'status', name: 'status',orderable: false},
                    {data: 'action', name: 'action',className: "text-center", orderable: false, searchable: false},
                ],
                dom:
                    '<"d-flex justify-content-between mx-2 row mt-75"' +
                    // '<" col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
                    '<"button-items"B>'+
                    '<"col-sm-12 col-lg-4 ps-xl-75 ps-0"<" d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-3"l>' +
                    '<"col-sm-12 col-md-3"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',

                buttons: [
                    {
                        text: 'Add New',
                        className: 'btn-success',
                        attr: {
                            'id' : 'createNewProject',
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    },
                    {
                        text: 'Buil and Check',
                        className: 'btn btn-success ',
                        attr: {
                            'id' : 'buildandcheck',
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    },
                    {
                        text: 'Status',
                        className: 'btn btn-success',
                        attr: {
                            'id' : 'dev_status',
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    },
                    {
                        text: 'Keystore',
                        className: 'btn btn-success',
                        attr: {
                            'id' : 'change_keystore',
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    },
                    {
                        text: 'SDK',
                        className: 'btn btn-success',
                        attr: {
                            'id' : 'change_sdk',
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],
                columnDefs: [
                    {
                        "targets": [ 0 ],
                        "visible": false,
                        "searchable": false
                    }
                ],
                deferRender:    true,
                scrollY:       '78vh',
                scroller: true,
                scrollCollapse: true,
                order: [[ 2, 'desc' ]]
            });


            $('#createNewProject').click(function () {
                $('#saveBtn').val("create-project");
                $('#project_id').val('');
                $('#p_buildinfo_keystore').text('');
                $("#avatar").attr("src","img/logo.png");
                $('#projectForm2').trigger("reset");
                $('#template').select2();
                $('#ma_da').select2();
                $('#buildinfo_keystore').select2();
                $('#Chplay_keystore_profile').select2();
                $('#Amazon_keystore_profile').select2();
                $('#Samsung_keystore_profile').select2();
                $('#Xiaomi_keystore_profile').select2();
                $('#Oppo_keystore_profile').select2();
                $('#Vivo_keystore_profile').select2();
                $('#Huawei_keystore_profile').select2();
                $('#Chplay_buildinfo_store_name_x').select2();
                $('#Amazon_buildinfo_store_name_x').select2();
                $('#Samssung_buildinfo_store_name_x').select2();
                $('#Xiaomi_buildinfo_store_name_x').select2();
                $('#Oppo_buildinfo_store_name_x').select2();
                $('#Vivo_buildinfo_store_name_x').select2();
                $('#Huawei_buildinfo_store_name_x').select2();
                $('#modelHeading').html("Thêm mới Project");
                $('#ajaxModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
                $('.market_chplay').hide()
                $('.market_amazon').hide()
                $('.market_samsung').hide()
                $('.market_xiaomi').hide()
                $('.market_oppo').hide()
                $('.market_vivo').hide()
                $('.market_huawei').hide()
                $('#Chplay_ads_id').hide();
                $('#Chplay_ads_banner').hide();
                $('#Chplay_ads_inter').hide();
                $('#Chplay_ads_reward').hide();
                $('#Chplay_ads_native').hide();
                $('#Chplay_ads_open').hide();
                $('#Chplay_ads_start').hide();

                $('#Amazon_ads_id').hide();
                $('#Amazon_ads_banner').hide();
                $('#Amazon_ads_inter').hide();
                $('#Amazon_ads_reward').hide();
                $('#Amazon_ads_native').hide();
                $('#Amazon_ads_open').hide();
                $('#Amazon_ads_start').hide();

                $('#Xiaomi_ads_id').hide();
                $('#Xiaomi_ads_banner').hide();
                $('#Xiaomi_ads_inter').hide();
                $('#Xiaomi_ads_reward').hide();
                $('#Xiaomi_ads_native').hide();
                $('#Xiaomi_ads_open').hide();
                $('#Xiaomi_ads_start').hide();

                $('#Samsung_ads_id').hide();
                $('#Samsung_ads_banner').hide();
                $('#Samsung_ads_inter').hide();
                $('#Samsung_ads_reward').hide();
                $('#Samsung_ads_native').hide();
                $('#Samsung_ads_open').hide();
                $('#Samsung_ads_start').hide();

                $('#Oppo_ads_id').hide();
                $('#Oppo_ads_banner').hide();
                $('#Oppo_ads_inter').hide();
                $('#Oppo_ads_reward').hide();
                $('#Oppo_ads_native').hide();
                $('#Oppo_ads_open').hide();
                $('#Oppo_ads_start').hide();

                $('#Vivo_ads_id').hide();
                $('#Vivo_ads_banner').hide();
                $('#Vivo_ads_inter').hide();
                $('#Vivo_ads_reward').hide();
                $('#Vivo_ads_native').hide();
                $('#Vivo_ads_open').hide();
                $('#Vivo_ads_start').hide();

                $('#Huawei_ads_id').hide();
                $('#Huawei_ads_banner').hide();
                $('#Huawei_ads_inter').hide();
                $('#Huawei_ads_reward').hide();
                $('#Huawei_ads_native').hide();
                $('#Huawei_ads_open').hide();
                $('#Huawei_ads_start').hide();

                $('.a_chplay').hide();
                $('.a_amazon').hide();
                $('.a_samsung').hide();
                $('.a_xiaomi').hide();
                $('.a_oppo').hide();
                $('.a_vivo').hide();
                $('.a_huawei').hide();
            });

            $('#projectForm2').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#projectForm2")[0]);
                if($('#saveBtn').val() == 'create-project'){
                    $.ajax({
                        // data: $('#projectForm2').serialize(),
                        data: formData,
                        url: "{{ route('project.create') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#projectForm2").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#projectForm2').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#saveBtn').val() == 'edit-project'){
                    $.ajax({
                        // data: $('#projectForm2').serialize(),
                        data: formData,
                        url: "{{ route('project.update') }}",
                        type: "post",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#projectForm2").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#projectForm2').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                // table.draw();
                            }
                        },
                    });
                }
            });

            $('#parttimeprojectForm').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#parttimeprojectForm")[0]);
                $.ajax({
                    data: formData,
                    url: "{{ route('project.updatePart') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#parttimeprojectForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#parttimeprojectForm').trigger("reset");
                            $('#ajaxPartTimeModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            });

            $('#projectQuickForm').on('submit',function (event){
                event.preventDefault();
                if($('#saveQBtn').val() == 'quick-edit-project'){
                    $.ajax({
                        data: $('#projectQuickForm').serialize(),
                        url: "{{ route('project.updateQuick') }}",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#projectQuickForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#projectQuickForm').trigger("reset");
                                $('#ajaxQuickModel').modal('hide');
                                // table.draw();
                            }
                        },
                    });

                }

            });

            $('#EditDesEN').on('submit',function (event){
                event.preventDefault();
                $.ajax({
                    data: $('#EditDesEN').serialize(),
                    url: "{{ route('project.updateDesEN') }}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#EditDesEN").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#EditDesEN').trigger("reset");
                            $('#editDesEN').modal('hide');
                            // table.draw();
                        }
                    },
                });
            });
            $('#EditDesVN').on('submit',function (event){
                event.preventDefault();
                $.ajax({
                    data: $('#EditDesVN').serialize(),
                    url: "{{ route('project.updateDesVN') }}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#EditDesVN").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#EditDesVN').trigger("reset");
                            $('#editDesVN').modal('hide');
                            // table.draw();
                        }
                    },
                });
            });

            $(document).on('click','.deleteProject', function (data){
                var project_id = $(this).data("id");
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
                            url: "{{ asset("project/delete") }}/" + project_id,
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

            $(document).on('click','.fakeProject', function (data){
                var project_id = $(this).data("id");

                $('#modelFakeHeading').html("Fake Image Project");
                $('#saveBtn').val("fake-project");
                $('#fakeProjectModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });

                $.ajax({
                    type: "get",
                    url: "{{ asset("project/fake") }}/" + project_id,
                    success: function (data) {
                        console.log(data)
                        $("#avatar_fake").attr("src","/uploads/project/"+data.projectname+"/thumbnail/"+data.logo);
                        $("#project_id_fake").val(data.projectid);
                        $("#title_app_fake").val(data.title_app);
                        $("#buildinfo_vernum_fake").val(data.buildinfo_vernum);
                        $("#buildinfo_verstr_fake").val(data.buildinfo_verstr);
                        $("#buildinfo_app_name_x_fake").val(data.buildinfo_app_name_x);
                        $("#Chplay_package_fake").val(data.Chplay_package);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            });


            $("a.dashboard").on("click",function(){
                var  form = $('#fakeprojectForm').serialize();
                var img = $('#avatar_fake').attr('src');
                window.open('/fakeimage?action=dashboard&'+form+'&img='+img,'_blank');
            });


            {{--$(document).on('click','.dashboard', function (data){--}}
            {{--    var  form = $('#fakeprojectForm').serialize();--}}
            {{--    /--}}

            {{--    --}}{{--$.ajax({--}}
            {{--    --}}{{--    type: "get",--}}
            {{--    --}}{{--    url: "{{ asset("project/fake") }}/" + project_id,--}}
            {{--    --}}{{--    success: function (data) {--}}

            {{--    --}}{{--        $("#avatar_fake").attr("src","../uploads/project/"+data.projectname+"/thumbnail/"+data.logo);--}}
            {{--    --}}{{--        $("#project_id_fake").val(data.projectid);--}}
            {{--    --}}{{--        $("#title_app_fake").val(data.title_app);--}}
            {{--    --}}{{--        $("#buildinfo_vernum_fake").val(data.buildinfo_vernum);--}}
            {{--    --}}{{--        $("#buildinfo_verstr_fake").val(data.buildinfo_verstr);--}}
            {{--    --}}{{--        $("#buildinfo_app_name_x_fake").val(data.buildinfo_app_name_x);--}}
            {{--    --}}{{--        $("#Chplay_package_fake").val(data.Chplay_package);--}}
            {{--    --}}{{--    },--}}
            {{--    --}}{{--    error: function (data) {--}}
            {{--    --}}{{--        console.log('Error:', data);--}}
            {{--    --}}{{--    }--}}
            {{--    --}}{{--});--}}

            {{--});--}}

            $(document).on('click','.showLog_Project', e=>{
                const row = table.row(e.target.closest('tr'));
                const rowData = row.data();
                $('#modelHeadingPolicy').html(rowData.name_projectname);
                $('#showMess').modal('show');
                $('.message-full').html(rowData.log);

            });

            $('#buildandcheck').on('click', function () {
                $('#buildandcheckModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            });

            $('#dev_status').on('click', function () {

                $('#Chplay_buildinfo_store_name_x2').select2();
                $('#Amazon_buildinfo_store_name_x2').select2();
                $('#Samsung_buildinfo_store_name_x2').select2();
                $('#Xiaomi_buildinfo_store_name_x2').select2();
                $('#Oppo_buildinfo_store_name_x2').select2();
                $('#Vivo_buildinfo_store_name_x2').select2();
                $('#Huawei_buildinfo_store_name_x2').select2();

                $('#dev_statusModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            });

            $('#change_keystore').on('click', function () {

                $('#changeKeystoreMultiple').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            });

            $('#change_sdk').on('click', function () {

                $('#changeSdkMultiple').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            });

            $('#buildcheckForm button').click(function (event){
                event.preventDefault();
                var data = $('textarea#buildinfo_vernum').val()
                var myArray = data.split("\n");
                if($(this).attr("value") == "build"){
                    $.ajax({
                        data: {data: myArray},
                        url: "{{ route('project.updateBuildCheck')}}?buildinfo_console=1",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#buildcheckForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#buildcheckForm').trigger("reset");
                                $('#buildandcheckModel').modal('hide');
                                $('textarea#buildinfo_vernum').html('')
                                table.draw();
                            }
                        },
                    });
                }
                if($(this).attr("value") == "check"){
                    $.ajax({
                        data: {data: myArray},
                        url: "{{ route('project.updateBuildCheck')}}?buildinfo_console=4",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#buildcheckForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#buildcheckForm').trigger("reset");
                                $('#buildandcheckModel').modal('hide');
                                $('textarea#buildinfo_vernum').html('')
                                table.draw();
                            }
                        },
                    });

                }

            });

            $('#dev_statusForm button').click(function (event){
                event.preventDefault();
                $.ajax({
                    data: $('#dev_statusForm').serialize(),
                    url: "{{ route('project.updateDev_status')}}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)
                        if(data.errors){
                            $.notify(data.errors, "error");
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#dev_statusForm').trigger("reset");
                            $('#dev_statusModel').modal('hide');
                            table.draw();
                        }
                    },
                });



            });

            $('#changeKeystoreMultipleForm button').click(function (event){
                event.preventDefault();
                $.ajax({
                    data: $('#changeKeystoreMultipleForm').serialize(),
                    url: "{{ route('project.changeKeystoreMultiple')}}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)
                        if(data.errors){
                            $.notify(data.errors, "error");
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#changeKeystoreMultipleForm').trigger("reset");
                            $('#changeKeystoreMultiple').modal('hide');
                            table.draw();
                        }
                    },
                });



            });

            $('#changeSdkMultipleForm button').click(function (event){
                event.preventDefault();
                $.ajax({
                    data: $('#changeSdkMultipleForm').serialize(),
                    url: "{{ route('project.changeSdkMultiple')}}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)
                        if(data.errors){
                            $.notify(data.errors, "error");
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#changeSdkMultipleForm').trigger("reset");
                            $('#changeSdkMultiple').modal('hide');
                            table.draw();
                        }
                    },
                });
            });


        });

        function getIndex(item){
            let index = $(item).val();
            let myArray = index.split("\n");
            let data = {
                projectname : myArray
            }
            let text = "";
            $.ajax({
                type: 'get',
                url: '{{asset('project/check_build')}}',
                data: data,
                success: function (data) {
                    data.forEach(element =>{
                        text += element.projectname + " | " + element.buildinfo_vernum + " | " + element.buildinfo_verstr +"\n";
                    });
                    $("textarea#buildinfo_vernum").html(text)
                },
            });
        }


    </script>
    <script>
        function editProject(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {
                var ads = JSON.parse(data[4].ads);
                var Chplay_ads = '';
                var Amazon_ads = '';
                var Samsung_ads = '';
                var Xiaomi_ads = '';
                var Oppo_ads = '';
                var Vivo_ads = '';
                var Huawei_ads = '';
                var keystore = keystore_chplay = keystore_amazon =  keystore_samsung = keystore_xiaomi =  keystore_oppo =  keystore_vivo =  keystore_huawei =  '';
                if(data[0].Chplay_ads) {
                    Chplay_ads = data[0].Chplay_ads;
                    Chplay_ads = JSON.parse(Chplay_ads);
                }
                if(data[0].Amazon_ads){
                    Amazon_ads = data[0].Amazon_ads;
                    Amazon_ads = JSON.parse(Amazon_ads);
                }
                if(data[0].Samsung_ads) {
                    Samsung_ads = data[0].Samsung_ads;
                    Samsung_ads = JSON.parse(Samsung_ads);
                }
                if(data[0].Xiaomi_ads) {
                    Xiaomi_ads = data[0].Xiaomi_ads;
                    Xiaomi_ads = JSON.parse(Xiaomi_ads);
                }
                if(data[0].Oppo_ads) {
                    Oppo_ads = data[0].Oppo_ads;
                    Oppo_ads = JSON.parse(Oppo_ads);
                }
                if(data[0].Vivo_ads) {
                    Vivo_ads = data[0].Vivo_ads;
                    Vivo_ads = JSON.parse(Vivo_ads);
                }

                if(data[0].Huawei_ads) {
                    Huawei_ads = data[0].Huawei_ads;
                    Huawei_ads = JSON.parse(Huawei_ads);
                }
                if(data[0].logo) {
                    $("#avatar").attr("src","../uploads/project/"+data[0].projectname+"/thumbnail/"+data[0].logo);
                }else {
                    $("#avatar").attr("src","img/logo.png");
                }

                if(data[4].Chplay_category !=null){
                    $('.market_chplay').show()
                    $('.a_chplay').show()
                    $('#Chplay_package').val(data[0].Chplay_package);
                    if(data[0].Chplay_package == null){
                        $('#Chplay_package').attr("placeholder", data[4].package);
                    }
                    $('#Chplay_ads_id').val(Chplay_ads.ads_id);
                    $('#Chplay_ads_banner').val(Chplay_ads.ads_banner);
                    $('#Chplay_ads_inter').val(Chplay_ads.ads_inter);
                    $('#Chplay_ads_reward').val(Chplay_ads.ads_reward);
                    $('#Chplay_ads_native').val(Chplay_ads.ads_native);
                    $('#Chplay_ads_open').val(Chplay_ads.ads_open);
                    $('#Chplay_ads_start').val(Chplay_ads.ads_start);
                    $('#Chplay_ads_roll_huawei').val(Chplay_ads.ads_roll_huawei);
                    $('#Chplay_ads_banner_huawei').val(Chplay_ads.ads_banner_huawei);
                    $('#Chplay_ads_inter_huawei').val(Chplay_ads.ads_inter_huawei);
                    $('#Chplay_ads_reward_huawei').val(Chplay_ads.ads_reward_huawei);
                    $('#Chplay_ads_native_huawei').val(Chplay_ads.ads_native_huawei);
                    $('#Chplay_ads_splash_huawei').val(Chplay_ads.ads_splash_huawei);
                }else{
                    $('.market_chplay').hide()
                    $('.a_chplay').hide()
                    $('#Chplay_package').val('');
                    $('#Chplay_ads_id').val('');
                    $('#Chplay_ads_banner').val('');
                    $('#Chplay_ads_inter').val('');
                    $('#Chplay_ads_reward').val('');
                    $('#Chplay_ads_native').val('');
                    $('#Chplay_ads_open').val('');
                    $('#Chplay_ads_start').val('');
                    $('#Chplay_ads_roll_huawei').val('');
                    $('#Chplay_ads_banner_huawei').val('');
                    $('#Chplay_ads_inter_huawei').val('');
                    $('#Chplay_ads_reward_huawei').val('');
                    $('#Chplay_ads_native_huawei').val('');
                    $('#Chplay_ads_splash_huawei').val('');
                }

                if(data[4].Amazon_category !=null){
                    $('.market_amazon').show()
                    $('.a_amazon').show()
                    $('#Amazon_package').val(data[0].Amazon_package);
                    if(data[0].Amazon_package == null){
                        $('#Amazon_package').attr("placeholder", data[4].package);
                    }
                    $('#Amazon_ads_id').val(Amazon_ads.ads_id);
                    $('#Amazon_ads_banner').val(Amazon_ads.ads_banner);
                    $('#Amazon_ads_inter').val(Amazon_ads.ads_inter);
                    $('#Amazon_ads_reward').val(Amazon_ads.ads_reward);
                    $('#Amazon_ads_native').val(Amazon_ads.ads_native);
                    $('#Amazon_ads_open').val(Amazon_ads.ads_open);
                    $('#Amazon_ads_start').val(Amazon_ads.ads_start);
                    $('#Amazon_ads_roll_huawei').val(Amazon_ads.ads_roll_huawei);
                    $('#Amazon_ads_banner_huawei').val(Amazon_ads.ads_banner_huawei);
                    $('#Amazon_ads_inter_huawei').val(Amazon_ads.ads_inter_huawei);
                    $('#Amazon_ads_reward_huawei').val(Amazon_ads.ads_reward_huawei);
                    $('#Amazon_ads_native_huawei').val(Amazon_ads.ads_native_huawei);
                    $('#Amazon_ads_splash_huawei').val(Amazon_ads.ads_splash_huawei);

                }else{
                    $('.market_amazon').hide()
                    $('.a_amazon').hide()
                    $('#Amazon_ads_id').val('');
                    $('#Amazon_ads_banner').val('');
                    $('#Amazon_ads_inter').val('');
                    $('#Amazon_ads_reward').val('');
                    $('#Amazon_ads_native').val('');
                    $('#Amazon_ads_open').val('');
                    $('#Amazon_ads_start').val('');
                    $('#Amazon_ads_roll_huawei').val('');
                    $('#Amazon_ads_banner_huawei').val('');
                    $('#Amazon_ads_inter_huawei').val('');
                    $('#Amazon_ads_reward_huawei').val('');
                    $('#Amazon_ads_native_huawei').val('');
                    $('#Amazon_ads_splash_huawei').val('');


                }
                if(data[4].Samsung_category !=null){
                    $('.market_samsung').show()
                    $('.a_samsung').show()
                    $('#Samsung_package').val(data[0].Samsung_package);
                    if(data[0].Samsung_package == null){
                        $('#Samsung_package').attr("placeholder", data[4].package);
                    }
                    $('#Samsung_ads_id').val(Samsung_ads.ads_id);
                    $('#Samsung_ads_banner').val(Samsung_ads.ads_banner);
                    $('#Samsung_ads_inter').val(Samsung_ads.ads_inter);
                    $('#Samsung_ads_native').val(Samsung_ads.ads_native);
                    $('#Samsung_ads_reward').val(Samsung_ads.ads_reward);
                    $('#Samsung_ads_open').val(Samsung_ads.ads_open);
                    $('#Samsung_ads_start').val(Samsung_ads.ads_start);
                    $('#Samsung_ads_roll_huawei').val(Samsung_ads.ads_roll_huawei);
                    $('#Samsung_ads_banner_huawei').val(Samsung_ads.ads_banner_huawei);
                    $('#Samsung_ads_inter_huawei').val(Samsung_ads.ads_inter_huawei);
                    $('#Samsung_ads_reward_huawei').val(Samsung_ads.ads_reward_huawei);
                    $('#Samsung_ads_native_huawei').val(Samsung_ads.ads_native_huawei);
                    $('#Samsung_ads_splash_huawei').val(Samsung_ads.ads_splash_huawei);

                }else{
                    $('.market_samsung').hide()
                    $('.a_samsung').hide()
                    $('#Samsung_ads_id').val('');
                    $('#Samsung_ads_banner').val('');
                    $('#Samsung_ads_inter').val('');
                    $('#Samsung_ads_native').val('');
                    $('#Samsung_ads').val('');
                    $('#Samsung_ads_open').val('');
                    $('#Samsung_ads_start').val('');
                    $('#Samsung_ads_roll_huawei').val('');
                    $('#Samsung_ads_banner_huawei').val('');
                    $('#Samsung_ads_inter_huawei').val('');
                    $('#Samsung_ads_reward_huawei').val('');
                    $('#Samsung_ads_native_huawei').val('');
                    $('#Samsung_ads_splash_huawei').val('');
                }
                if(data[4].Xiaomi_category !=null){
                    $('.market_xiaomi').show()
                    $('.a_xiaomi').show()
                    $('#Xiaomi_package').val(data[0].Xiaomi_package);
                    $('#Xiaomi_package').val(data[0].Xiaomi_package);
                    if(data[0].Xiaomi_package == null){
                        $('#Xiaomi_package').attr("placeholder", data[4].package);
                    }
                    $('#Xiaomi_ads_id').val(Xiaomi_ads.ads_id);
                    $('#Xiaomi_ads_banner').val(Xiaomi_ads.ads_banner);
                    $('#Xiaomi_ads_inter').val(Xiaomi_ads.ads_inter);
                    $('#Xiaomi_ads_reward').val(Xiaomi_ads.ads_reward);
                    $('#Xiaomi_ads_native').val(Xiaomi_ads.ads_native);
                    $('#Xiaomi_ads_open').val(Xiaomi_ads.ads_open);
                    $('#Xiaomi_ads_start').val(Xiaomi_ads.ads_start);
                    $('#Xiaomi_ads_roll_huawei').val(Xiaomi_ads.ads_roll_huawei);
                    $('#Xiaomi_ads_banner_huawei').val(Xiaomi_ads.ads_banner_huawei);
                    $('#Xiaomi_ads_inter_huawei').val(Xiaomi_ads.ads_inter_huawei);
                    $('#Xiaomi_ads_reward_huawei').val(Xiaomi_ads.ads_reward_huawei);
                    $('#Xiaomi_ads_native_huawei').val(Xiaomi_ads.ads_native_huawei);
                    $('#Xiaomi_ads_splash_huawei').val(Xiaomi_ads.ads_splash_huawei);
                }else{
                    $('.market_xiaomi').hide()
                    $('.a_xiaomi').hide()
                    $('#Xiaomi_ads_id').val('');
                    $('#Xiaomi_ads_banner').val('');
                    $('#Xiaomi_ads_inter').val('');
                    $('#Xiaomi_ads_reward').val('');
                    $('#Xiaomi_ads_native').val('');
                    $('#Xiaomi_ads_open').val('');
                    $('#Xiaomi_ads_start').val('');
                    $('#Xiaomi_ads_roll_huawei').val('');
                    $('#Xiaomi_ads_banner_huawei').val('');
                    $('#Xiaomi_ads_inter_huawei').val('');
                    $('#Xiaomi_ads_reward_huawei').val('');
                    $('#Xiaomi_ads_native_huawei').val('');
                    $('#Xiaomi_ads_splash_huawei').val('');
                }
                if(data[4].Oppo_category !=null){
                    $('.market_oppo').show()
                    $('.ma_oppo').show()
                    $('#Oppo_package').val(data[0].Oppo_package);
                    if(data[0].Oppo_package == null){
                        $('#Oppo_package').attr("placeholder", data[4].package);
                    }
                    $('#Oppo_ads_id').val(Oppo_ads.ads_id);
                    $('#Oppo_ads_banner').val(Oppo_ads.ads_banner);
                    $('#Oppo_ads_inter').val(Oppo_ads.ads_inter);
                    $('#Oppo_ads_reward').val(Oppo_ads.ads_reward);
                    $('#Oppo_ads_native').val(Oppo_ads.ads_native);
                    $('#Oppo_ads_open').val(Oppo_ads.ads_open);
                    $('#Oppo_ads_start').val(Oppo_ads.ads_start);
                    $('#Oppo_ads_roll_huawei').val(Oppo_ads.ads_roll_huawei);
                    $('#Oppo_ads_banner_huawei').val(Oppo_ads.ads_banner_huawei);
                    $('#Oppo_ads_inter_huawei').val(Oppo_ads.ads_inter_huawei);
                    $('#Oppo_ads_reward_huawei').val(Oppo_ads.ads_reward_huawei);
                    $('#Oppo_ads_native_huawei').val(Oppo_ads.ads_native_huawei);
                    $('#Oppo_ads_splash_huawei').val(Oppo_ads.ads_splash_huawei);
                }else{
                    $('.market_oppo').hide()
                    $('.a_oppo').hide()
                    $('#Oppo_ads_id').val('');
                    $('#Oppo_ads_banner').val('');
                    $('#Oppo_ads_inter').val('');
                    $('#Oppo_ads_reward').val('');
                    $('#Oppo_ads_native').val('');
                    $('#Oppo_ads_open').val('');
                    $('#Oppo_ads_start').val('');
                    $('#Oppo_ads_roll_huawei').val('');
                    $('#Oppo_ads_banner_huawei').val('');
                    $('#Oppo_ads_inter_huawei').val('');
                    $('#Oppo_ads_reward_huawei').val('');
                    $('#Oppo_ads_native_huawei').val('');
                    $('#Oppo_ads_splash_huawei').val('');
                }
                if(data[4].Vivo_category !=null){
                    $('.market_vivo').show()
                    $('.a_vivo').show()
                    $('#Vivo_package').val(data[0].Vivo_package);
                    if(data[0].Vivo_package == null){
                        $('#Vivo_package').attr("placeholder", data[4].package);
                    }
                    $('#Vivo_ads_id').val(Vivo_ads.ads_id);
                    $('#Vivo_ads_banner').val(Vivo_ads.ads_banner);
                    $('#Vivo_ads_inter').val(Vivo_ads.ads_inter);
                    $('#Vivo_ads_reward').val(Vivo_ads.ads_reward);
                    $('#Vivo_ads_native').val(Vivo_ads.ads_native);
                    $('#Vivo_ads_open').val(Vivo_ads.ads_open);
                    $('#Vivo_ads_start').val(Vivo_ads.ads_start);
                    $('#Vivo_ads_roll_huawei').val(Vivo_ads.ads_roll_huawei);
                    $('#Vivo_ads_banner_huawei').val(Vivo_ads.ads_banner_huawei);
                    $('#Vivo_ads_inter_huawei').val(Vivo_ads.ads_inter_huawei);
                    $('#Vivo_ads_reward_huawei').val(Vivo_ads.ads_reward_huawei);
                    $('#Vivo_ads_native_huawei').val(Vivo_ads.ads_native_huawei);
                    $('#Vivo_ads_splash_huawei').val(Vivo_ads.ads_splash_huawei);

                }else{
                    $('.market_vivo').hide()
                    $('.a_vivo').hide()
                    $('#Vivo_ads_id').val('');
                    $('#Vivo_ads_banner').val('');
                    $('#Vivo_ads_inter').val('');
                    $('#Vivo_ads_reward').val('');
                    $('#Vivo_ads_native').val('');
                    $('#Vivo_ads_open').val('');
                    $('#Vivo_ads_start').val('');
                    $('#Vivo_ads_roll_huawei').val('');
                    $('#Vivo_ads_banner_huawei').val('');
                    $('#Vivo_ads_inter_huawei').val('');
                    $('#Vivo_ads_reward_huawei').val('');
                    $('#Vivo_ads_native_huawei').val('');
                    $('#Vivo_ads_splash_huawei').val('');
                }
                if(data[4].Huawei_category !=null){
                    $('.market_huawei').show()
                    $('.a_huawei').show()
                    $('#Huawei_package').val(data[0].Huawei_package);
                    if(data[0].Huawei_package == null){
                        $('#Huawei_package').attr("placeholder", data[4].package);
                    }
                    $('#Huawei_ads_id').val(Huawei_ads.ads_id);
                    $('#Huawei_ads_banner').val(Huawei_ads.ads_banner);
                    $('#Huawei_ads_inter').val(Huawei_ads.ads_inter);
                    $('#Huawei_ads_reward').val(Huawei_ads.ads_reward);
                    $('#Huawei_ads_native').val(Huawei_ads.ads_native);
                    $('#Huawei_ads_open').val(Huawei_ads.ads_open);
                    $('#Huawei_ads_start').val(Huawei_ads.ads_start);
                    $('#Huawei_ads_roll_huawei').val(Huawei_ads.ads_roll_huawei);
                    $('#Huawei_ads_banner_huawei').val(Huawei_ads.ads_banner_huawei);
                    $('#Huawei_ads_inter_huawei').val(Huawei_ads.ads_inter_huawei);
                    $('#Huawei_ads_reward_huawei').val(Huawei_ads.ads_reward_huawei);
                    $('#Huawei_ads_native_huawei').val(Huawei_ads.ads_native_huawei);
                    $('#Huawei_ads_splash_huawei').val(Huawei_ads.ads_splash_huawei);

                }else{
                    $('.market_huawei').hide()
                    $('.a_huawei').hide()
                    $('#Huawei_ads_id').val('');
                    $('#Huawei_ads_banner').val('');
                    $('#Huawei_ads_inter').val('');
                    $('#Huawei_ads_reward').val('');
                    $('#Huawei_ads_native').val('');
                    $('#Huawei_ads_open').val('');
                    $('#Huawei_ads_start').val('');
                    $('#Huawei_ads_roll_huawei').val('');
                    $('#Huawei_ads_banner_huawei').val('');
                    $('#Huawei_ads_inter_huawei').val('');
                    $('#Huawei_ads_reward_huawei').val('');
                    $('#Huawei_ads_native_huawei').val('');
                    $('#Huawei_ads_splash_huawei').val('');
                }
                if(ads !=null){
                    if(ads.ads_id !=null){
                        $('#Chplay_ads_id').show();
                        $('#Amazon_ads_id').show();
                        $('#Samsung_ads_id').show();
                        $('#Xiaomi_ads_id').show();
                        $('#Oppo_ads_id').show();
                        $('#Vivo_ads_id').show();
                        $('#Huawei_ads_id').show();
                    }else {
                        $('#Chplay_ads_id').hide();
                        $('#Amazon_ads_id').hide();
                        $('#Samsung_ads_id').hide();
                        $('#Xiaomi_ads_id').hide();
                        $('#Oppo_ads_id').hide();
                        $('#Vivo_ads_id').hide();
                        $('#Huawei_ads_id').hide();
                    }
                    if(ads.ads_banner!=null){
                        $('#Chplay_ads_banner').show();
                        $('#Amazon_ads_banner').show();
                        $('#Samsung_ads_banner').show();
                        $('#Xiaomi_ads_banner').show();
                        $('#Oppo_ads_banner').show();
                        $('#Vivo_ads_banner').show();
                        $('#Huawei_ads_banner').show();
                    }else {
                        $('#Chplay_ads_banner').hide();
                        $('#Amazon_ads_banner').hide();
                        $('#Samsung_ads_banner').hide();
                        $('#Xiaomi_ads_banner').hide();
                        $('#Oppo_ads_banner').hide();
                        $('#Vivo_ads_banner').hide();
                        $('#Huawei_ads_banner').hide();
                    }
                    if(ads.ads_inter !=null){
                        $('#Chplay_ads_inter').show();
                        $('#Amazon_ads_inter').show();
                        $('#Samsung_ads_inter').show();
                        $('#Xiaomi_ads_inter').show();
                        $('#Oppo_ads_inter').show();
                        $('#Huawei_ads_inter').show();
                        $('#Vivo_ads_inter').show();
                    }else {
                        $('#Chplay_ads_inter').hide();
                        $('#Amazon_ads_inter').hide();
                        $('#Samsung_ads_inter').hide();
                        $('#Xiaomi_ads_inter').hide();
                        $('#Oppo_ads_inter').hide();
                        $('#Vivo_ads_inter').hide();
                        $('#Huawei_ads_inter').hide();
                    }
                    if(ads.ads_reward !=null){
                        $('#Chplay_ads_reward').show();
                        $('#Amazon_ads_reward').show();
                        $('#Samsung_ads_reward').show();
                        $('#Xiaomi_ads_reward').show();
                        $('#Oppo_ads_reward').show();
                        $('#Vivo_ads_reward').show();
                        $('#Huawei_ads_reward').show();
                    }else {
                        $('#Chplay_ads_reward').hide();
                        $('#Amazon_ads_reward').hide();
                        $('#Samsung_ads_reward').hide();
                        $('#Xiaomi_ads_reward').hide();
                        $('#Oppo_ads_reward').hide();
                        $('#Vivo_ads_reward').hide();
                        $('#Huawei_ads_reward').hide();
                    }
                    if(ads.ads_native !=null){
                        $('#Chplay_ads_native').show();
                        $('#Amazon_ads_native').show();
                        $('#Samsung_ads_native').show();
                        $('#Xiaomi_ads_native').show();
                        $('#Oppo_ads_native').show();
                        $('#Vivo_ads_native').show();
                        $('#Huawei_ads_native').show();
                    }else {
                        $('#Chplay_ads_native').hide();
                        $('#Amazon_ads_native').hide();
                        $('#Samsung_ads_native').hide();
                        $('#Xiaomi_ads_native').hide();
                        $('#Oppo_ads_native').hide();
                        $('#Vivo_ads_native').hide();
                        $('#Huawei_ads_native').hide();
                    }
                    if(ads.ads_open !=null){
                        $('#Chplay_ads_open').show();
                        $('#Amazon_ads_open').show();
                        $('#Samsung_ads_open').show();
                        $('#Xiaomi_ads_open').show();
                        $('#Oppo_ads_open').show();
                        $('#Vivo_ads_open').show();
                        $('#Huawei_ads_open').show();

                    }else {
                        $('#Chplay_ads_open').hide();
                        $('#Amazon_ads_open').hide();
                        $('#Samsung_ads_open').hide();
                        $('#Xiaomi_ads_open').hide();
                        $('#Oppo_ads_open').hide();
                        $('#Vivo_ads_open').hide();
                        $('#Huawei_ads_open').hide();
                    }

                    if(ads.ads_start !=null){
                        $('.ads_start').show();
                        $('#Chplay_ads_start').show();
                        $('#Amazon_ads_start').show();
                        $('#Samsung_ads_start').show();
                        $('#Xiaomi_ads_start').show();
                        $('#Oppo_ads_start').show();
                        $('#Huawei_ads_start').show();
                        $('#Vivo_ads_start').show();
                    }else {
                        $('.ads_start').hide();

                        $('#Chplay_ads_start').hide();
                        $('#Amazon_ads_start').hide();
                        $('#Samsung_ads_start').hide();
                        $('#Xiaomi_ads_start').hide();
                        $('#Oppo_ads_start').hide();
                        $('#Vivo_ads_start').hide();
                        $('#Huawei_ads_start').hide();
                    }

                    if(ads.ads_banner_huawei !=null){
                        $('#Chplay_ads_banner_huawei').show();
                        $('#Amazon_ads_banner_huawei').show();
                        $('#Samsung_ads_banner_huawei').show();
                        $('#Xiaomi_ads_banner_huawei').show();
                        $('#Oppo_ads_banner_huawei').show();
                        $('#Vivo_ads_banner_huawei').show();
                        $('#Huawei_ads_banner_huawei').show();
                    }else {
                        $('#Chplay_ads_banner_huawei').hide();
                        $('#Amazon_ads_banner_huawei').hide();
                        $('#Samsung_ads_banner_huawei').hide();
                        $('#Xiaomi_ads_banner_huawei').hide();
                        $('#Oppo_ads_banner_huawei').hide();
                        $('#Vivo_ads_banner_huawei').hide();
                        $('#Huawei_ads_banner_huawei').hide();
                    }

                    if(ads.ads_native_huawei !=null){
                        $('#Chplay_ads_native_huawei').show();
                        $('#Amazon_ads_native_huawei').show();
                        $('#Samsung_ads_native_huawei').show();
                        $('#Xiaomi_ads_native_huawei').show();
                        $('#Oppo_ads_native_huawei').show();
                        $('#Vivo_ads_native_huawei').show();
                        $('#Huawei_ads_native_huawei').show();
                    }else {
                        $('#Chplay_ads_native_huawei').hide();
                        $('#Amazon_ads_native_huawei').hide();
                        $('#Samsung_ads_native_huawei').hide();
                        $('#Xiaomi_ads_native_huawei').hide();
                        $('#Oppo_ads_native_huawei').hide();
                        $('#Vivo_ads_native_huawei').hide();
                        $('#Huawei_ads_native_huawei').hide();
                    }

                    if(ads.ads_reward_huawei !=null){
                        $('#Chplay_ads_reward_huawei').show();
                        $('#Amazon_ads_reward_huawei').show();
                        $('#Samsung_ads_reward_huawei').show();
                        $('#Xiaomi_ads_reward_huawei').show();
                        $('#Oppo_ads_reward_huawei').show();
                        $('#Vivo_ads_reward_huawei').show();
                        $('#Huawei_ads_reward_huawei').show();
                    }else {
                        $('#Chplay_ads_reward_huawei').hide();
                        $('#Amazon_ads_reward_huawei').hide();
                        $('#Samsung_ads_reward_huawei').hide();
                        $('#Xiaomi_ads_reward_huawei').hide();
                        $('#Oppo_ads_reward_huawei').hide();
                        $('#Vivo_ads_reward_huawei').hide();
                        $('#Huawei_ads_reward_huawei').hide();
                    }

                    if(ads.ads_inter_huawei !=null){
                        $('#Chplay_ads_inter_huawei').show();
                        $('#Amazon_ads_inter_huawei').show();
                        $('#Samsung_ads_inter_huawei').show();
                        $('#Xiaomi_ads_inter_huawei').show();
                        $('#Oppo_ads_inter_huawei').show();
                        $('#Vivo_ads_inter_huawei').show();
                        $('#Huawei_ads_inter_huawei').show();
                    }else {
                        $('#Chplay_ads_inter_huawei').hide();
                        $('#Amazon_ads_inter_huawei').hide();
                        $('#Samsung_ads_inter_huawei').hide();
                        $('#Xiaomi_ads_inter_huawei').hide();
                        $('#Oppo_ads_inter_huawei').hide();
                        $('#Vivo_ads_inter_huawei').hide();
                        $('#Huawei_ads_inter_huawei').hide();
                    }

                    if(ads.ads_splash_huawei!=null){
                        $('#Chplay_ads_splash_huawei').show();
                        $('#Amazon_ads_splash_huawei').show();
                        $('#Samsung_ads_splash_huawei').show();
                        $('#Xiaomi_ads_splash_huawei').show();
                        $('#Oppo_ads_splash_huawei').show();
                        $('#Vivo_ads_splash_huawei').show();
                        $('#Huawei_ads_splash_huawei').show();
                    }else {
                        $('#Chplay_ads_splash_huawei').hide();
                        $('#Amazon_ads_splash_huawei').hide();
                        $('#Samsung_ads_splash_huawei').hide();
                        $('#Xiaomi_ads_splash_huawei').hide();
                        $('#Oppo_ads_splash_huawei').hide();
                        $('#Vivo_ads_splash_huawei').hide();
                        $('#Huawei_ads_splash_huawei').hide();
                    }

                    if(ads.ads_roll_huawei !=null){
                        $('#Chplay_ads_roll_huawei').show();
                        $('#Amazon_ads_roll_huawei').show();
                        $('#Samsung_ads_roll_huawei').show();
                        $('#Xiaomi_ads_roll_huawei').show();
                        $('#Oppo_ads_roll_huawei').show();
                        $('#Vivo_ads_roll_huawei').show();
                        $('#Huawei_ads_roll_huawei').show();
                    }else {
                        $('#Chplay_ads_roll_huawei').hide();
                        $('#Amazon_ads_roll_huawei').hide();
                        $('#Samsung_ads_roll_huawei').hide();
                        $('#Xiaomi_ads_roll_huawei').hide();
                        $('#Oppo_ads_roll_huawei').hide();
                        $('#Vivo_ads_roll_huawei').hide();
                        $('#Huawei_ads_roll_huawei').hide();
                    }
                }else {
                    $('#Chplay_ads_id').hide();
                    $('#Amazon_ads_id').hide();
                    $('#Samsung_ads_id').hide();
                    $('#Xiaomi_ads_id').hide();
                    $('#Oppo_ads_id').hide();
                    $('#Vivo_ads_id').hide();
                    $('#Huawei_ads_id').hide();

                    $('#Chplay_ads_banner').hide();
                    $('#Amazon_ads_banner').hide();
                    $('#Samsung_ads_banner').hide();
                    $('#Xiaomi_ads_banner').hide();
                    $('#Oppo_ads_banner').hide();
                    $('#Vivo_ads_banner').hide();
                    $('#Huawei_ads_banner').hide();

                    $('#Chplay_ads_inter').hide();
                    $('#Amazon_ads_inter').hide();
                    $('#Samsung_ads_inter').hide();
                    $('#Xiaomi_ads_inter').hide();
                    $('#Oppo_ads_inter').hide();
                    $('#Vivo_ads_inter').hide();
                    $('#Huawei_ads_inter').hide();

                    $('#Chplay_ads_reward').hide();
                    $('#Amazon_ads_reward').hide();
                    $('#Samsung_ads_reward').hide();
                    $('#Xiaomi_ads_reward').hide();
                    $('#Oppo_ads_reward').hide();
                    $('#Vivo_ads_reward').hide();
                    $('#Huawei_ads_reward').hide();

                    $('#Chplay_ads_native').hide();
                    $('#Amazon_ads_native').hide();
                    $('#Samsung_ads_native').hide();
                    $('#Xiaomi_ads_native').hide();
                    $('#Oppo_ads_native').hide();
                    $('#Vivo_ads_native').hide();
                    $('#Huawei_ads_native').hide();

                    $('#Chplay_ads_open').hide();
                    $('#Amazon_ads_open').hide();
                    $('#Samsung_ads_open').hide();
                    $('#Xiaomi_ads_open').hide();
                    $('#Oppo_ads_open').hide();
                    $('#Vivo_ads_open').hide();
                    $('#Huawei_ads_open').hide();

                    $('#Chplay_ads_start').hide();
                    $('#Amazon_ads_start').hide();
                    $('#Samsung_ads_start').hide();
                    $('#Xiaomi_ads_start').hide();
                    $('#Oppo_ads_start').hide();
                    $('#Vivo_ads_start').hide();
                    $('#Huawei_ads_start').hide();
                }
                if(ads.ads_id != null || ads.ads_banner != null || ads.ads_inter != null|| ads.ads_reward != null||ads.ads_native != null||ads.ads_open != null )
                {
                    $('.ads_admod').show();
                }else{
                    $('.ads_admod').hide() ;
                }

                if(ads.ads_id_huawei != null || ads.ads_banner_huawei != null || ads.ads_inter_huawei != null|| ads.ads_reward_huawei != null||ads.ads_native_huawei != null||ads.ads_open_huawei != null )
                {
                    $('.ads_huawei').show();
                }else{
                    $('.ads_huawei').hide() ;
                }

                if(data[18]) {
                    keystore = data[18].SHA_256_keystore;
                }
                if(data[19]) {
                    keystore_chplay = data[19].SHA_256_keystore;
                }
                if(data[20]) {
                    keystore_amazon = data[20].SHA_256_keystore;
                }
                if(data[21]) {
                    keystore_samsung = data[21].SHA_256_keystore;
                }
                if(data[22]) {
                    keystore_xiaomi = data[22].SHA_256_keystore;
                }
                if(data[23]) {
                    keystore_oppo = data[23].SHA_256_keystore;
                }
                if(data[24]) {
                    keystore_vivo = data[24].SHA_256_keystore;
                }
                if(data[25]) {
                    keystore_huawei = data[25].SHA_256_keystore;
                }

                $('#project_id').val(data[0].projectid);
                $('#projectname').val(data[0].projectname);
                $('#template').val(data[0].template);
                $('#template').select2();
                $('#ma_da').val(data[0].ma_da);
                $('#ma_da').select2();
                $('#title_app').val(data[0].title_app);
                $('#buildinfo_vernum').val(data[0].buildinfo_vernum);
                $('#buildinfo_verstr').val(data[0].buildinfo_verstr);
                $('#buildinfo_app_name_x').val(data[0].buildinfo_app_name_x);
                $('#buildinfo_keystore').val(data[0].buildinfo_keystore);
                $('#p_buildinfo_keystore').text(keystore);
                $('#buildinfo_keystore').select2();
                $('#buildinfo_sdk').val(data[0].buildinfo_sdk);
                $('#buildinfo_link_policy_x').val(data[0].buildinfo_link_policy_x);
                $('#buildinfo_link_youtube_x').val(data[0].buildinfo_link_youtube_x);
                $('#buildinfo_link_fanpage').val(data[0].buildinfo_link_fanpage);
                $('#buildinfo_api_key_x').val(data[0].buildinfo_api_key_x);
                $('#buildinfo_link_website').val(data[0].buildinfo_link_website);
                $('#link_store_vietmmo').val(data[0].link_store_vietmmo);

                $('#Chplay_buildinfo_store_name_x').val(data[0].Chplay_buildinfo_store_name_x);
                $('#Chplay_buildinfo_store_name_x').select2();
                $('#Chplay_buildinfo_link_store').val(data[0].Chplay_buildinfo_link_store);
                $('#Chplay_buildinfo_link_app').val(data[0].Chplay_buildinfo_link_app);
                $('#Chplay_buildinfo_email_dev_x').val(data[0].Chplay_buildinfo_email_dev_x);
                $('#Chplay_status').val(data[0].Chplay_status);
                $('#Chplay_policy').val(data[0].Chplay_policy);
                $('#Chplay_sdk').val(data[0].Chplay_sdk);
                $('#Chplay_keystore_profile').val(data[0].Chplay_keystore_profile);
                $('#Chplay_keystore_profile').select2();
                $('#p_buildinfo_keystore_chplay').text(keystore_chplay);

                $('#Amazon_buildinfo_store_name_x').val(data[0].Amazon_buildinfo_store_name_x);
                $('#Amazon_buildinfo_store_name_x').select2();
                $('#Amazon_buildinfo_link_store').val(data[0].Amazon_buildinfo_link_store);
                $('#Amazon_buildinfo_link_app').val(data[0].Amazon_buildinfo_link_app);
                $('#Amazon_buildinfo_email_dev_x').val(data[0].Amazon_buildinfo_email_dev_x);
                $('#Amazon_status').val(data[0].Amazon_status);
                $('#Amazon_policy').val(data[0].Amazon_policy);
                $('#Amazon_sdk').val(data[0].Amazon_sdk);
                $('#Amazon_keystore_profile').val(data[0].Amazon_keystore_profile);
                $('#Amazon_keystore_profile').select2();
                $('#p_buildinfo_keystore_amazon').text(keystore_amazon);



                $('#Samsung_buildinfo_store_name_x').val(data[0].Samsung_buildinfo_store_name_x);
                $('#Samssung_buildinfo_store_name_x').select2();
                $('#Samsung_buildinfo_link_store').val(data[0].Samsung_buildinfo_link_store);
                $('#Samsung_buildinfo_link_app').val(data[0].Samsung_buildinfo_link_app);
                $('#Samsung_buildinfo_email_dev_x').val(data[0].Samsung_buildinfo_email_dev_x);
                $('#Samsung_status').val(data[0].Samsung_status);
                $('#Samsung_policy').val(data[0].Samsung_policy);
                $('#Samsung_sdk').val(data[0].Samsung_sdk);
                $('#Samsung_keystore_profile').val(data[0].Samsung_keystore_profile);
                $('#Samsung_keystore_profile').select2();
                $('#p_buildinfo_keystore_samsung').text(keystore_samsung);

                $('#Xiaomi_buildinfo_store_name_x').val(data[0].Xiaomi_buildinfo_store_name_x);
                $('#Xiaomi_buildinfo_store_name_x').select2();
                $('#Xiaomi_buildinfo_link_store').val(data[0].Xiaomi_buildinfo_link_store);
                $('#Xiaomi_buildinfo_link_app').val(data[0].Xiaomi_buildinfo_link_app);
                $('#Xiaomi_buildinfo_email_dev_x').val(data[0].Xiaomi_buildinfo_email_dev_x);
                $('#Xiaomi_status').val(data[0].Xiaomi_status);
                $('#Xiaomi_policy').val(data[0].Xiaomi_policy);
                $('#Xiaomi_sdk').val(data[0].Xiaomi_sdk);
                $('#Xiaomi_keystore_profile').val(data[0].Xiaomi_keystore_profile);
                $('#Xiaomi_keystore_profile').select2();
                $('#p_buildinfo_keystore_xiaomi').text(keystore_xiaomi);


                $('#Oppo_buildinfo_store_name_x').val(data[0].Oppo_buildinfo_store_name_x);
                $('#Oppo_buildinfo_store_name_x').select2();
                $('#Oppo_buildinfo_link_store').val(data[0].Oppo_buildinfo_link_store);
                $('#Oppo_buildinfo_link_app').val(data[0].Oppo_buildinfo_link_app);
                $('#Oppo_buildinfo_email_dev_x').val(data[0].Oppo_buildinfo_email_dev_x);
                $('#Oppo_status').val(data[0].Oppo_status);
                $('#Oppo_policy').val(data[0].Oppo_policy);
                $('#Oppo_sdk').val(data[0].Oppo_sdk);
                $('#Oppo_keystore_profile').val(data[0].Oppo_keystore_profile);
                $('#Oppo_keystore_profile').select2();
                $('#p_buildinfo_keystore_oppo').text(keystore_oppo);


                $('#Vivo_buildinfo_store_name_x').val(data[0].Vivo_buildinfo_store_name_x);
                $('#Vivo_buildinfo_store_name_x').select2();
                $('#Vivo_buildinfo_link_store').val(data[0].Vivo_buildinfo_link_store);
                $('#Vivo_buildinfo_link_app').val(data[0].Vivo_buildinfo_link_app);
                $('#Vivo_buildinfo_email_dev_x').val(data[0].Vivo_buildinfo_email_dev_x);
                $('#Vivo_status').val(data[0].Vivo_status);
                $('#Vivo_policy').val(data[0].Vivo_policy);
                $('#Vivo_sdk').val(data[0].Vivo_sdk);
                $('#Vivo_keystore_profile').val(data[0].Vivo_keystore_profile);
                $('#Vivo_keystore_profile').select2();
                $('#p_buildinfo_keystore_vivo').text(keystore_vivo);

                $('#Huawei_buildinfo_store_name_x').val(data[0].Huawei_buildinfo_store_name_x);
                $('#Huawei_buildinfo_store_name_x').select2();
                $('#Huawei_buildinfo_link_store').val(data[0].Huawei_buildinfo_link_store);
                $('#Huawei_appId').val(data[0].Huawei_appId);
                $('#Huawei_buildinfo_link_app').val(data[0].Huawei_buildinfo_link_app);
                $('#Huawei_buildinfo_email_dev_x').val(data[0].Huawei_buildinfo_email_dev_x);
                $('#Huawei_status').val(data[0].Huawei_status);
                $('#Huawei_policy').val(data[0].Huawei_policy);
                $('#Huawei_sdk').val(data[0].Huawei_sdk);
                $('#Huawei_keystore_profile').val(data[0].Huawei_keystore_profile);
                $('#Huawei_keystore_profile').select2();
                $('#p_buildinfo_keystore_huawei').text(keystore_huawei);

                if(data[2] == null){
                    $('#chplay_dev_ga').text('Không có '+ ' | '+ data[10])
                }else {
                    $('#chplay_dev_ga').text(data[2].store_name+ ' | '+ data[10])
                }

                if(data[5] == null){
                    $('#amazon_dev_ga').text('Không có '+ ' | '+ data[11])
                }else {
                    $('#amazon_dev_ga').text(data[5].amazon_store_name+ ' | '+ data[11])
                }

                if(data[6] == null){
                    $('#samsung_dev_ga').text('Không có '+ ' | '+ data[12])
                }else {
                    $('#samsung_dev_ga').text(data[6].samsung_store_name+ ' | '+ data[12])
                }

                if(data[7] == null){
                    $('#xiaomi_dev_ga').text('Không có '+ ' | '+ data[13])
                }else {
                    $('#xiaomi_dev_ga').text(data[7].xiaomi_store_name+ ' | '+ data[13])
                }

                if(data[8] == null){
                    $('#oppo_dev_ga').text('Không có '+ ' | '+ data[14])
                }else {
                    $('#oppo_dev_ga').text(data[8].oppo_store_name+ ' | '+ data[14])
                }

                if(data[9] == null){
                    $('#vivo_dev_ga').text('Không có '+ ' | '+ data[15])
                }else {
                    $('#vivo_dev_ga').text(data[9].vivo_store_name+ ' | '+ data[15])
                }
                if(data[16] == null){
                    $('#huawei_dev_ga').text('Không có '+ ' | '+ data[17])
                }else {
                    $('#huawei_dev_ga').text(data[16].huawei_store_name+ ' | '+ data[17])
                }

                $('#modelHeading').html("Edit Project");
                $('#saveBtn').val("edit-project");
                $('#ajaxModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }



        function editProject_partTime(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {
                if(data[0].Chplay_package != null){
                    $('.Chplay_status').show();
                    $('#Chplay_buildinfo_store_name_x1').val(data[0].Chplay_buildinfo_store_name_x);
                    $('#Chplay_buildinfo_store_name_x1').select2();
                    $('#Chplay_status1').val(data[0].Chplay_status);

                } else {
                    $('.Chplay_status').hide()
                }
                if(data[0].Amazon_package != null){
                    $('.Amazon_status').show();
                    $('#Amazon_buildinfo_store_name_x1').val(data[0].Amazon_buildinfo_store_name_x);
                    $('#Amazon_buildinfo_store_name_x1').select2();
                    $('#Amazon_status1').val(data[0].Amazon_status);
                } else {
                    $('.Amazon_status').hide()
                }
                if(data[0].Samsung_package != null){
                    $('.Samsung_status').show();
                    $('#Samsung_buildinfo_store_name_x1').val(data[0].Samsung_buildinfo_store_name_x);
                    $('#Samsung_buildinfo_store_name_x1').select2();
                    $('#Samsung_status1').val(data[0].Samsung_status);
                } else {
                    $('.Samsung_status').hide()
                }
                if(data[0].Xiaomi_package != null){
                    $('.Xiaomi_status').show();
                    $('#Xiaomi_buildinfo_store_name_x1').val(data[0].Xiaomi_buildinfo_store_name_x);
                    $('#Xiaomi_buildinfo_store_name_x1').select2();
                    $('#Xiaomi_status1').val(data[0].Xiaomi_status);
                } else {
                    $('.Xiaomi_status').hide()
                }
                if(data[0].Oppo_package != null){
                    $('.Oppo_status').show();
                    $('#Oppo_buildinfo_store_name_x1').val(data[0].Oppo_buildinfo_store_name_x);
                    $('#Oppo_buildinfo_store_name_x1').select2();
                    $('#Oppo_status1').val(data[0].Oppo_status);
                } else {
                    $('.Oppo_status').hide()
                }
                if(data[0].Vivo_package != null){
                    $('.Vivo_status').show();
                    $('#Vivo_buildinfo_store_name_x1').val(data[0].Vivo_buildinfo_store_name_x);
                    $('#Vivo_buildinfo_store_name_x1').select2();
                    $('#Vivo_status1').val(data[0].Vivo_status);
                } else {
                    $('.Vivo_status').hide()
                }
                if(data[0].Huawei_package != null){
                    $('.Huawei_status').show();
                    $('#Huawei_buildinfo_store_name_x1').val(data[0].Huawei_buildinfo_store_name_x);
                    $('#Huawei_buildinfo_store_name_x1').select2();
                    $('#Huawei_status1').val(data[0].Huawei_status);
                } else {
                    $('.Huawei_status').hide()
                }

                $('#part_time_project_id').val(data[0].projectid);
                $('#projectname1').val(data[0].projectname);
                $('#title_app1').val(data[0].title_app);

                $('#modelPartTimeHeading').html("Edit Project");
                $('#saveBtn').val("edit-project");
                $('#ajaxPartTimeModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }

        function editProject_Description_EN(id) {
            $.get('{{asset('project/editDes_EN')}}/'+id,function (data) {
                $('#project_id_edit_desEN').val(data.projectid);
                $('#summary_en').val(data.summary_en);
                $('#title_app_en').val(data.title_app);
                if(data.title_app){
                    $('#count_title_app_en').text(data.title_app.length);
                }else{
                    $('#count_title_app_en').text(0);
                }

                if(data.summary_en){
                    $('#count_summary_en').text(data.summary_en.length);
                }else{
                    $('#count_summary_en').text(0);
                }

                if(data.des_en){
                    tinymce.get('des_en').setContent(data.des_en);
                }else {
                    tinymce.get('des_en').setContent('');
                }
                $('#modelEditDesEN').html("Edit Description");
                $('#saveBtn').val("edit-des-en");
                $('#editDesEN').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
        function editProject_Description_VN(id) {
            $.get('{{asset('project/editDes_VN')}}/'+id,function (data) {
                $('#project_id_edit_DesVN').val(data.projectid);
                $('#summary_vn').val(data.summary_vn);
                $('#title_app_vn').val(data.title_app);

                if(data.title_app){
                    $('#count_title_app_vn').text(data.title_app.length);
                }else{
                    $('#count_title_app_vn').text(0);
                }

                if(data.summary_vn){
                    $('#count_summary_vn').text(data.summary_vn.length);
                }else{
                    $('#count_summary_vn').text(0);
                }

                if(data.des_vn){
                    tinymce.get('des_vn').setContent(data.des_vn);
                }else{
                    tinymce.get('des_vn').setContent('');
                }
                $('#modelEditDesVN').html("Chỉnh sửa mô tả");
                $('#saveBtn').val("edit-des-vn");
                $('#editDesVN').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }

        function quickEditProject(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {
                $('#quick_project_id').val(data[0].projectid);
                $('#quick_buildinfo_vernum').val(data[0].buildinfo_vernum);
                $('#quick_buildinfo_verstr').val(data[0].buildinfo_verstr);
                $('#quick_buildinfo_console').val(data[0].buildinfo_console);
                $('#modelQuickHeading').html("Quick Edit Project");
                $('#saveQBtn').val("quick-edit-project");
                $('#ajaxQuickModel').modal('show');
            })
        }
        function showPolicy_Chplay(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {
                if(data[2] == null) { data[2] = {store_name: "(NO STORE NAME)"}}
                if(data[1].policy1){
                    $('.policy-1').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy1 = data[1].policy1
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[2].store_name)
                        .replaceAll("STORE_NAME_X", data[2].store_name);
                    $('#policy1').val(policy1);
                }else {
                    $('.policy-1').hide();
                }
                if(data[1].policy2) {
                    $('.policy-2').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy2 = data[1].policy2
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[2].store_name)
                        .replaceAll("STORE_NAME_X", data[2].store_name);
                    $('#policy2').val(policy2);
                }else {
                    $('.policy-2').hide();
                }
                $('#modelHeadingPolicy').html("Show Policy");
                $('#showPolicy').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
        function showPolicy_Amazon(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {
                if(data[5] == null) { data[5] = {amazon_store_name: "(NO STORE NAME)"}}
                if(data[1].policy1){
                    $('.policy-1').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy1 = data[1].policy1
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[5].amazon_store_name)
                        .replaceAll("STORE_NAME_X", data[5].amazon_store_name);
                    $('#policy1').val(policy1);
                }else {
                    $('.policy-1').hide();
                }
                if(data[1].policy2) {
                    $('.policy-2').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy2 = data[1].policy2
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[5].storamazon_store_namee_name)
                        .replaceAll("STORE_NAME_X", data[5].amazon_store_name);
                    $('#policy2').val(policy2);
                }else {
                    $('.policy-2').hide();
                }
                $('#modelHeadingPolicy').html("Show Policy");
                $('#showPolicy').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
        function showPolicy_Samsung(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {
                if(data[6] == null) { data[6] = {samsung_store_name: "(NO STORE NAME)"}}
                if(data[1].policy1){
                    $('.policy-1').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy1 = data[1].policy1
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[6].samsung_store_name)
                        .replaceAll("STORE_NAME_X", data[6].samsung_store_name);
                    $('#policy1').val(policy1);
                }else {
                    $('.policy-1').hide();
                }
                if(data[1].policy2) {
                    $('.policy-2').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy2 = data[1].policy2
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[6].samsung_store_name)
                        .replaceAll("STORE_NAME_X", data[6].samsung_store_name);
                    $('#policy2').val(policy2);
                }else {
                    $('.policy-2').hide();
                }
                $('#modelHeadingPolicy').html("Show Policy");
                $('#showPolicy').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
        function showPolicy_Xiaomi(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {
                if(data[7] == null) { data[7] = {xiaomi_store_name: "(NO STORE NAME)"}}
                if(data[1].policy1){
                    $('.policy-1').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy1 = data[1].policy1
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[7].xiaomi_store_name)
                        .replaceAll("STORE_NAME_X", data[7].xiaomi_store_name);
                    $('#policy1').val(policy1);
                }else {
                    $('.policy-1').hide();
                }
                if(data[1].policy2) {
                    $('.policy-2').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy2 = data[1].policy2
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[7].xiaomi_store_name)
                        .replaceAll("STORE_NAME_X", data[7].xiaomi_store_name);
                    $('#policy2').val(policy2);
                }else {
                    $('.policy-2').hide();
                }
                $('#modelHeadingPolicy').html("Show Policy");
                $('#showPolicy').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
        function showPolicy_Oppo(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {
                if(data[8] == null) { data[8] = {oppo_store_name: "(NO STORE NAME)"}}
                if(data[1].policy1){
                    $('.policy-1').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy1 = data[1].policy1
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[8].oppo_store_name)
                        .replaceAll("STORE_NAME_X", data[8].oppo_store_name);
                    $('#policy1').val(policy1);
                }else {
                    $('.policy-1').hide();
                }
                if(data[1].policy2) {
                    $('.policy-2').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy2 = data[1].policy2
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[8].oppo_store_name)
                        .replaceAll("STORE_NAME_X", data[8].oppo_store_name);
                    $('#policy2').val(policy2);
                }else {
                    $('.policy-2').hide();
                }
                $('#modelHeadingPolicy').html("Show Policy");
                $('#showPolicy').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
        function showPolicy_Vivo(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {
                if(data[9] == null) { data[9] = {vivo_store_name: "(NO STORE NAME)"}}
                if(data[1].policy1){
                    $('.policy-1').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy1 = data[1].policy1
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[9].vivo_store_name)
                        .replaceAll("STORE_NAME_X", data[9].vivo_store_name);
                    $('#policy1').val(policy1);
                }else {
                    $('.policy-1').hide();
                }

                if(data[1].policy2) {
                    $('.policy-2').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy2 = data[1].policy2
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[9].vivo_store_name)
                        .replaceAll("STORE_NAME_X", data[9].vivo_store_name);
                    $('#policy2').val(policy2);
                }else {
                    $('.policy-2').hide();
                }
                $('#modelHeadingPolicy').html("Show Policy");
                $('#showPolicy').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
        function showPolicy_Huawei(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {

                if(data[16] == null) { data[16] = {huawei_store_name: "(NO STORE NAME)"}}
                if(data[1].policy1){
                    $('.policy-1').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy1 = data[1].policy1
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[16].huawei_store_name)
                        .replaceAll("STORE_NAME_X", data[16].huawei_store_name);
                    $('#policy1').val(policy1);
                }else {
                    $('.policy-1').hide();
                }

                if(data[1].policy2) {
                    $('.policy-2').show();
                    if(data[0].buildinfo_app_name_x == null){
                        var app_name_x = '(NO APP NAME)'
                    }else{
                        var app_name_x = data[0].buildinfo_app_name_x;
                    }
                    let policy2 = data[1].policy2
                        .replaceAll("{APP_NAME_X}", app_name_x)
                        .replaceAll("APP_NAME_X", app_name_x)
                        .replaceAll("{STORE_NAME_X}", data[16].huawei_store_name)
                        .replaceAll("STORE_NAME_X", data[16].huawei_store_name);
                    $('#policy2').val(policy2);
                }else {
                    $('.policy-2').hide();
                }
                $('#modelHeadingPolicy').html("Show Policy");
                $('#showPolicy').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
    </script>
    <script>
        $("#AddDaForm").submit(function (e) {
            e.preventDefault();
            let data = new FormData(document.getElementById('AddDaForm'));
            $.ajax({
                url:"{{route('da.create')}}",
                type: "post",
                data:data,
                processData: false,
                contentType: false,
                dataType: 'json',
                beForeSend : () => {
                },
                success:function (data) {
                    if(data.errors){
                        for( var count=0 ; count <data.errors.length; count++){
                            $("#AddDaForm").notify(
                                data.errors[count],"error",
                                { position:"right" }
                            );
                        }
                    }
                    $.notify(data.success, "success");
                    $('#AddDaForm').trigger("reset");
                    $('#addMaDa').modal('hide');

                    if(typeof data.du_an == 'undefined'){
                        data.du_an = {};
                    }
                    if(typeof rebuildMadaOption == 'function'){
                        rebuildMadaOption(data.du_an)
                    }
                }
            });

        });
        $("#AddTempForm").submit(function (e) {
            e.preventDefault();
            let data = new FormData(document.getElementById('AddTempForm'));
            $.ajax({
                url:"{{route('template.create')}}",
                type: "post",
                data:data,
                processData: false,
                contentType: false,
                dataType: 'json',
                beForeSend : () => {
                },
                success:function (data) {
                    var ads = jQuery.parseJSON(data.temp[0].ads);

                    if(ads.ads_id != null){
                        $('#Chplay_ads_id').show();
                        $('#Amazon_ads_id').show();
                        $('#Samsung_ads_id').show();
                        $('#Xiaomi_ads_id').show();
                        $('#Oppo_ads_id').show();
                        $('#Vivo_ads_id').show();
                    }else {
                        $('#Chplay_ads_id').hide();
                        $('#Amazon_ads_id').hide();
                        $('#Samsung_ads_id').hide();
                        $('#Xiaomi_ads_id').hide();
                        $('#Oppo_ads_id').hide();
                        $('#Vivo_ads_id').hide();
                    }
                    if(ads.ads_banner != null){
                        $('#Chplay_ads_banner').show();
                        $('#Amazon_ads_banner').show();
                        $('#Xiaomi_ads_banner').show();
                        $('#Samsung_ads_banner').show();
                        $('#Oppo_ads_banner').show();
                        $('#Vivo_ads_banner').show();
                    }else {
                        $('#Chplay_ads_banner').hide();
                        $('#Amazon_ads_banner').hide();
                        $('#Xiaomi_ads_banner').hide();
                        $('#Samsung_ads_banner').hide();
                        $('#Oppo_ads_banner').hide();
                        $('#Vivo_ads_banner').hide();
                    }
                    if(ads.ads_inter != null){
                        $('#Chplay_ads_inter').show();
                        $('#Amazon_ads_inter').show();
                        $('#Xiaomi_ads_inter').show();
                        $('#Samsung_ads_inter').show();
                        $('#Oppo_ads_inter').show();
                        $('#Vivo_ads_inter').show();
                    }else {
                        $('#Chplay_ads_inter').hide();
                        $('#Amazon_ads_inter').hide();
                        $('#Xiaomi_ads_inter').hide();
                        $('#Samsung_ads_inter').hide();
                        $('#Oppo_ads_inter').hide();
                        $('#Vivo_ads_inter').hide();
                    }
                    if(ads.ads_reward != null){
                        $('#Chplay_ads_reward').show();
                        $('#Amazon_ads_reward').show();
                        $('#Samsung_ads_reward').show();
                        $('#Xiaomi_ads_reward').show();
                        $('#Oppo_ads_reward').show();
                        $('#Vivo_ads_reward').show();
                    }else {
                        $('#Chplay_ads_reward').hide();
                        $('#Amazon_ads_reward').hide();
                        $('#Samsung_ads_reward').hide();
                        $('#Xiaomi_ads_reward').hide();
                        $('#Oppo_ads_reward').hide();
                        $('#Vivo_ads_reward').hide();
                    }
                    if(ads.ads_native != null){
                        $('#Chplay_ads_native').show();
                        $('#Amazon_ads_native').show();
                        $('#Samsung_ads_native').show();
                        $('#Xiaomi_ads_native').show();
                        $('#Oppo_ads_native').show();
                        $('#Vivo_ads_native').show();
                    }else {
                        $('#Chplay_ads_native').hide();
                        $('#Amazon_ads_native').hide();
                        $('#Samsung_ads_native').hide();
                        $('#Xiaomi_ads_native').hide();
                        $('#Oppo_ads_native').hide();
                        $('#Vivo_ads_native').hide();
                    }
                    if(ads.ads_open != null){
                        $('#Chplay_ads_open').show();
                        $('#Amazon_ads_open').show();
                        $('#Samsung_ads_open').show();
                        $('#Xiaomi_ads_open').show();
                        $('#Oppo_ads_open').show();
                        $('#Vivo_ads_open').show();
                    }else {
                        $('#Chplay_ads_open').hide();
                        $('#Amazon_ads_open').hide();
                        $('#Samsung_ads_open').hide();
                        $('#Xiaomi_ads_open').hide();
                        $('#Oppo_ads_open').hide();
                        $('#Vivo_ads_open').hide();
                    }

                    if(data.errors){
                        for( var count=0 ; count <data.errors.length; count++){
                            $("#AddTempForm").notify(
                                data.errors[count],"error",
                                { position:"right" }
                            );
                        }
                    }
                    $.notify(data.success, "success");
                    $('#AddTempForm').trigger("reset");
                    $('#addTemplate').modal('hide');
                    if(typeof data.temp == 'undefined'){
                        data.temp = {};
                    }
                    if(typeof rebuildTemplateOption == 'function'){
                        rebuildTemplateOption(data.temp)
                    }
                }
            });

        });
        $("#keystoreForm").submit(function (e) {
            e.preventDefault();
            let data = new FormData(document.getElementById('keystoreForm'));
            $.ajax({
                url:"{{route('keystore.create')}}",
                type: "post",
                data:data,
                processData: false,
                contentType: false,
                dataType: 'json',
                beForeSend : () => {
                },
                success:function (data) {
                    if(data.errors){
                        for( var count=0 ; count <data.errors.length; count++){
                            $("#keystoreForm").notify(
                                data.errors[count],"error",
                                { position:"right" }
                            );
                        }
                    }
                    $.notify(data.success, "success");
                    $('#keystoreForm').trigger("reset");
                    $('#addKeystore').modal('hide');

                    if(typeof data.keys == 'undefined'){
                        data.keys = {};
                    }
                    if(typeof rebuildKeystoreOption == 'function'){
                        rebuildKeystoreOption(data.keys)
                    }
                }
            });

        });
        $('.choose_template').change(function (){
            var template = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{asset('project/select-template')}}',
                type: "post",
                data: {
                    template:template,
                    _token:_token
                },
                success:function (data){
                    var ads = jQuery.parseJSON(data.ads);
                    $('#Chplay_package').attr("placeholder",data.package);
                    $('#Amazon_package').attr("placeholder",data.package);
                    $('#Samsung_package').attr("placeholder",data.package);
                    $('#Xiaomi_package').attr("placeholder",data.package);
                    $('#Oppo_package').attr("placeholder",data.package);
                    $('#Vivo_package').attr("placeholder",data.package);
                    $('#Huawei_package').attr("placeholder",data.package);
                    if(data.Chplay_category != null){
                        $('.market_chplay').show();
                        $('.a_chplay').show();
                    }else {
                        $('.market_chplay').hide();
                        $('.a_chplay').hide();
                    }

                    if(data.Amazon_category != null){
                        $('.market_amazon').show();
                        $('.a_amazon').show();
                    }else {
                        $('.market_amazon').hide();
                        $('.a_amazon').hide();
                    }

                    if(data.Samsung_category != null){
                        $('.market_samsung').show();
                        $('.a_samsung').show();
                    }else {
                        $('.market_samsung').hide();
                        $('.a_samsung').hide();
                    }

                    if(data.Xiaomi_category != null){
                        $('.market_xiaomi').show();
                        $('.a_xiaomi').show();
                    }else {
                        $('.market_xiaomi').hide();
                        $('.a_xiaomi').hide();
                    }

                    if(data.Oppo_category != null){
                        $('.market_oppo').show();
                        $('.a_oppo').show();
                    }else {
                        $('.market_oppo').hide();
                        $('.a_oppo').hide();
                    }

                    if(data.Vivo_category != null){
                        $('.market_vivo').show();
                        $('.a_vivo').show();
                    }else {
                        $('.a_vivo').hide();
                        $('.market_vivo').hide();
                    }

                    if(data.Huawei_category != null){
                        $('.market_huawei').show();
                        $('.a_huawei').show();
                    }else {
                        $('.a_huawei').hide();
                        $('.market_huawei').hide();
                    }

                    if(ads != null){
                        if(ads.ads_id != null){
                            $('#Chplay_ads_id').show();
                            $('#Amazon_ads_id').show();
                            $('#Samsung_ads_id').show();
                            $('#Xiaomi_ads_id').show();
                            $('#Oppo_ads_id').show();
                            $('#Vivo_ads_id').show();
                            $('#Huawei_ads_id').show();
                        }else {
                            $('#Chplay_ads_id').hide();
                            $('#Amazon_ads_id').hide();
                            $('#Samsung_ads_id').hide();
                            $('#Xiaomi_ads_id').hide();
                            $('#Oppo_ads_id').hide();
                            $('#Vivo_ads_id').hide();
                            $('#Huawei_ads_id').hide();
                        }
                        if(ads.ads_banner != null){
                            $('#Chplay_ads_banner').show();
                            $('#Amazon_ads_banner').show();
                            $('#Xiaomi_ads_banner').show();
                            $('#Samsung_ads_banner').show();
                            $('#Oppo_ads_banner').show();
                            $('#Vivo_ads_banner').show();
                            $('#Huawei_ads_banner').show();
                        }else {
                            $('#Chplay_ads_banner').hide();
                            $('#Amazon_ads_banner').hide();
                            $('#Xiaomi_ads_banner').hide();
                            $('#Samsung_ads_banner').hide();
                            $('#Oppo_ads_banner').hide();
                            $('#Vivo_ads_banner').hide();
                            $('#Huawei_ads_banner').hide();
                        }
                        if(ads.ads_inter != null){
                            $('#Chplay_ads_inter').show();
                            $('#Amazon_ads_inter').show();
                            $('#Xiaomi_ads_inter').show();
                            $('#Samsung_ads_inter').show();
                            $('#Oppo_ads_inter').show();
                            $('#Vivo_ads_inter').show();
                            $('#Huawei_ads_inter').show();
                        }else {
                            $('#Chplay_ads_inter').hide();
                            $('#Amazon_ads_inter').hide();
                            $('#Xiaomi_ads_inter').hide();
                            $('#Samsung_ads_inter').hide();
                            $('#Oppo_ads_inter').hide();
                            $('#Vivo_ads_inter').hide();
                            $('#Huawei_ads_inter').hide();
                        }
                        if(ads.ads_reward != null){
                            $('#Chplay_ads_reward').show();
                            $('#Amazon_ads_reward').show();
                            $('#Samsung_ads_reward').show();
                            $('#Xiaomi_ads_reward').show();
                            $('#Oppo_ads_reward').show();
                            $('#Vivo_ads_reward').show();
                            $('#Huawei_ads_reward').show();
                        }else {
                            $('#Chplay_ads_reward').hide();
                            $('#Amazon_ads_reward').hide();
                            $('#Samsung_ads_reward').hide();
                            $('#Xiaomi_ads_reward').hide();
                            $('#Oppo_ads_reward').hide();
                            $('#Vivo_ads_reward').hide();
                            $('#Huawei_ads_reward').hide();
                        }
                        if(ads.ads_native != null){
                            $('#Chplay_ads_native').show();
                            $('#Amazon_ads_native').show();
                            $('#Samsung_ads_native').show();
                            $('#Xiaomi_ads_native').show();
                            $('#Oppo_ads_native').show();
                            $('#Vivo_ads_native').show();
                            $('#Huawei_ads_native').show();
                        }else {
                            $('#Chplay_ads_native').hide();
                            $('#Amazon_ads_native').hide();
                            $('#Samsung_ads_native').hide();
                            $('#Xiaomi_ads_native').hide();
                            $('#Oppo_ads_native').hide();
                            $('#Vivo_ads_native').hide();
                            $('#Huawei_ads_native').hide();
                        }
                        if(ads.ads_open != null){
                            $('#Chplay_ads_open').show();
                            $('#Amazon_ads_open').show();
                            $('#Samsung_ads_open').show();
                            $('#Xiaomi_ads_open').show();
                            $('#Oppo_ads_open').show();
                            $('#Vivo_ads_open').show();
                            $('#Huawei_ads_open').show();
                        }else {
                            $('#Chplay_ads_open').hide();
                            $('#Amazon_ads_open').hide();
                            $('#Samsung_ads_open').hide();
                            $('#Xiaomi_ads_open').hide();
                            $('#Oppo_ads_open').hide();
                            $('#Vivo_ads_open').hide();
                            $('#Huawei_ads_open').hide();
                        }

                        if(ads.ads_start != null){
                            $('.ads_start').show();

                            $('#Chplay_ads_start').show();
                            $('#Amazon_ads_start').show();
                            $('#Samsung_ads_start').show();
                            $('#Xiaomi_ads_start').show();
                            $('#Oppo_ads_start').show();
                            $('#Vivo_ads_start').show();
                            $('#Huawei_ads_start').show();
                        }else {
                            $('.ads_start').hide();
                            $('#Chplay_ads_start').hide();
                            $('#Amazon_ads_start').hide();
                            $('#Samsung_ads_start').hide();
                            $('#Xiaomi_ads_start').hide();
                            $('#Oppo_ads_start').hide();
                            $('#Vivo_ads_start').hide();
                            $('#Huawei_ads_start').hide();
                        }

                        if(ads.ads_banner_huawei != null){
                            $('#Chplay_ads_banner_huawei').show();
                            $('#Amazon_ads_banner_huawei').show();
                            $('#Xiaomi_ads_banner_huawei').show();
                            $('#Samsung_ads_banner_huawei').show();
                            $('#Oppo_ads_banner_huawei').show();
                            $('#Vivo_ads_banner_huawei').show();
                            $('#Huawei_ads_banner_huawei').show();
                        }else {
                            $('#Chplay_ads_banner_huawei').hide();
                            $('#Amazon_ads_banner_huawei').hide();
                            $('#Xiaomi_ads_banner_huawei').hide();
                            $('#Samsung_ads_banner_huawei').hide();
                            $('#Oppo_ads_banner_huawei').hide();
                            $('#Vivo_ads_banner_huawei').hide();
                            $('#Huawei_ads_banner_huawei').hide();
                        }

                        if(ads.ads_inter_huawei != null){
                            $('#Chplay_ads_inter_huawei').show();
                            $('#Amazon_ads_inter_huawei').show();
                            $('#Xiaomi_ads_inter_huawei').show();
                            $('#Samsung_ads_inter_huawei').show();
                            $('#Oppo_ads_inter_huawei').show();
                            $('#Vivo_ads_inter_huawei').show();
                            $('#Huawei_ads_inter_huawei').show();
                        }else {
                            $('#Chplay_ads_inter_huawei').hide();
                            $('#Amazon_ads_inter_huawei').hide();
                            $('#Xiaomi_ads_inter_huawei').hide();
                            $('#Samsung_ads_inter_huawei').hide();
                            $('#Oppo_ads_inter_huawei').hide();
                            $('#Vivo_ads_inter_huawei').hide();
                            $('#Huawei_ads_inter_huawei').hide();
                        }

                        if(ads.ads_native_huawei != null){
                            $('#Chplay_ads_native_huawei').show();
                            $('#Amazon_ads_native_huawei').show();
                            $('#Xiaomi_ads_native_huawei').show();
                            $('#Samsung_ads_native_huawei').show();
                            $('#Oppo_ads_native_huawei').show();
                            $('#Vivo_ads_native_huawei').show();
                            $('#Huawei_ads_native_huawei').show();
                        }else {
                            $('#Chplay_ads_native_huawei').hide();
                            $('#Amazon_ads_native_huawei').hide();
                            $('#Xiaomi_ads_native_huawei').hide();
                            $('#Samsung_ads_native_huawei').hide();
                            $('#Oppo_ads_native_huawei').hide();
                            $('#Vivo_ads_native_huawei').hide();
                            $('#Huawei_ads_native_huawei').hide();
                        }

                        if(ads.ads_reward_huawei != null){
                            $('#Chplay_ads_reward_huawei').show();
                            $('#Amazon_ads_reward_huawei').show();
                            $('#Xiaomi_ads_reward_huawei').show();
                            $('#Samsung_ads_reward_huawei').show();
                            $('#Oppo_ads_reward_huawei').show();
                            $('#Vivo_ads_reward_huawei').show();
                            $('#Huawei_ads_reward_huawei').show();
                        }else {
                            $('#Chplay_ads_reward_huawei').hide();
                            $('#Amazon_ads_reward_huawei').hide();
                            $('#Xiaomi_ads_reward_huawei').hide();
                            $('#Samsung_ads_rewardr_huawei').hide();
                            $('#Oppo_ads_reward_huawei').hide();
                            $('#Vivo_ads_reward_huawei').hide();
                            $('#Huawei_ads_reward_huawei').hide();
                        }

                        if(ads.ads_splash_huawei != null){
                            $('#Chplay_ads_splash_huawei').show();
                            $('#Amazon_ads_splash_huawei').show();
                            $('#Xiaomi_ads_splash_huawei').show();
                            $('#Samsung_ads_splash_huawei').show();
                            $('#Oppo_ads_splash_huawei').show();
                            $('#Vivo_ads_splash_huawei').show();
                            $('#Huawei_ads_splash_huawei').show();
                        }else {
                            $('#Chplay_ads_splash_huawei').hide();
                            $('#Amazon_ads_splash_huawei').hide();
                            $('#Xiaomi_ads_splash_huawei').hide();
                            $('#Samsung_ads_splash_huawei').hide();
                            $('#Oppo_ads_splash_huawei').hide();
                            $('#Vivo_ads_splash_huawei').hide();
                            $('#Huawei_ads_splash_huawei').hide();
                        }

                        if(ads.ads_roll_huawei != null){
                            $('#Chplay_ads_roll_huawei').show();
                            $('#Amazon_ads_roll_huawei').show();
                            $('#Xiaomi_ads_roll_huawei').show();
                            $('#Samsung_ads_roll_huawei').show();
                            $('#Oppo_ads_roll_huawei').show();
                            $('#Vivo_ads_roll_huawei').show();
                            $('#Huawei_ads_roll_huawei').show();
                        }else {
                            $('#Chplay_ads_roll_huawei').hide();
                            $('#Amazon_ads_roll_huawei').hide();
                            $('#Xiaomi_ads_roll_huawei').hide();
                            $('#Samsung_ads_roll_huawei').hide();
                            $('#Oppo_ads_roll_huawei').hide();
                            $('#Vivo_ads_roll_huawei').hide();
                            $('#Huawei_ads_roll_huawei').hide();
                        }
                    }else {
                        $('#Chplay_ads_id').hide();
                        $('#Chplay_ads_banner').hide();
                        $('#Chplay_ads_inter').hide();
                        $('#Chplay_ads_reward').hide();
                        $('#Chplay_ads_native').hide();
                        $('#Chplay_ads_open').hide();
                        $('#Chplay_ads_start').hide();
                        $('#Chplay_ads_roll_huawei').hide();
                        $('#Chplay_ads_banner_huawei').hide();
                        $('#Chplay_ads_inter_huawei').hide();
                        $('#Chplay_ads_reward_huawei').hide();
                        $('#Chplay_ads_native_huawei').hide();
                        $('#Chplay_ads_splash_huawei').hide();

                        $('#Amazon_ads_id').hide();
                        $('#Amazon_ads_banner').hide();
                        $('#Amazon_ads_inter').hide();
                        $('#Amazon_ads_reward').hide();
                        $('#Amazon_ads_native').hide();
                        $('#Amazon_ads_open').hide();
                        $('#Amazon_ads_start').hide();
                        $('#Amazon_ads_roll_huawei').hide();
                        $('#Amazon_ads_banner_huawei').hide();
                        $('#Amazon_ads_inter_huawei').hide();
                        $('#Amazon_ads_reward_huawei').hide();
                        $('#Amazon_ads_native_huawei').hide();
                        $('#Amazon_ads_splash_huawei').hide();

                        $('#Xiaomi_ads_id').hide();
                        $('#Xiaomi_ads_banner').hide();
                        $('#Xiaomi_ads_inter').hide();
                        $('#Xiaomi_ads_reward').hide();
                        $('#Xiaomi_ads_native').hide();
                        $('#Xiaomi_ads_open').hide();
                        $('#Xiaomi_ads_start').hide();
                        $('#Xiaomi_ads_roll_huawei').hide();
                        $('#Xiaomi_ads_banner_huawei').hide();
                        $('#Xiaomi_ads_inter_huawei').hide();
                        $('#Xiaomi_ads_reward_huawei').hide();
                        $('#Xiaomi_ads_native_huawei').hide();
                        $('#Xiaomi_ads_splash_huawei').hide();

                        $('#Samsung_ads_id').hide();
                        $('#Samsung_ads_banner').hide();
                        $('#Samsung_ads_inter').hide();
                        $('#Samsung_ads_reward').hide();
                        $('#Samsung_ads_native').hide();
                        $('#Samsung_ads_open').hide();
                        $('#Samsung_ads_start').hide();
                        $('#Samsung_ads_roll_huawei').hide();
                        $('#Samsung_ads_banner_huawei').hide();
                        $('#Samsung_ads_inter_huawei').hide();
                        $('#Samsung_ads_reward_huawei').hide();
                        $('#Samsung_ads_native_huawei').hide();
                        $('#Samsung_ads_splash_huawei').hide();

                        $('#Oppo_ads_id').hide();
                        $('#Oppo_ads_banner').hide();
                        $('#Oppo_ads_inter').hide();
                        $('#Oppo_ads_reward').hide();
                        $('#Oppo_ads_native').hide();
                        $('#Oppo_ads_open').hide();
                        $('#Oppo_ads_start').hide();
                        $('#Oppo_ads_roll_huawei').hide();
                        $('#Oppo_ads_banner_huawei').hide();
                        $('#Oppo_ads_inter_huawei').hide();
                        $('#Oppo_ads_reward_huawei').hide();
                        $('#Oppo_ads_native_huawei').hide();
                        $('#Oppo_ads_splash_huawei').hide();

                        $('#Vivo_ads_id').hide();
                        $('#Vivo_ads_banner').hide();
                        $('#Vivo_ads_inter').hide();
                        $('#Vivo_ads_reward').hide();
                        $('#Vivo_ads_native').hide();
                        $('#Vivo_ads_open').hide();
                        $('#Vivo_ads_start').hide();
                        $('#Vivo_ads_roll_huawei').hide();
                        $('#Vivo_ads_banner_huawei').hide();
                        $('#Vivo_ads_inter_huawei').hide();
                        $('#Vivo_ads_reward_huawei').hide();
                        $('#Vivo_ads_native_huawei').hide();
                        $('#Vivo_ads_splash_huawei').hide();

                        $('#Huawei_ads_id').hide();
                        $('#Huawei_ads_banner').hide();
                        $('#Huawei_ads_inter').hide();
                        $('#Huawei_ads_reward').hide();
                        $('#Huawei_ads_native').hide();
                        $('#Huawei_ads_open').hide();
                        $('#Huawei_ads_start').hide();
                        $('#Huawei_ads_roll_huawei').hide();
                        $('#Huawei_ads_banner_huawei').hide();
                        $('#Huawei_ads_inter_huawei').hide();
                        $('#Huawei_ads_reward_huawei').hide();
                        $('#Huawei_ads_native_huawei').hide();
                        $('#Huawei_ads_splash_huawei').hide();
                    }

                    if(ads.ads_id != null || ads.ads_banner != null || ads.ads_inter != null|| ads.ads_reward != null||ads.ads_native != null||ads.ads_open != null )
                    {
                        $('.ads_admod').show();
                    }else{
                        $('.ads_admod').hide() ;
                    }
                    if(ads.ads_id_huawei != null || ads.ads_banner_huawei != null || ads.ads_inter_huawei != null|| ads.ads_reward_huawei != null||ads.ads_native_huawei != null||ads.ads_open_huawei != null )
                    {
                        $('.ads_huawei').show();
                    }else{
                        $('.ads_huawei').hide() ;
                    }
                }
            });
        });


        $('#Chplay_buildinfo_store_name_x').change(function (){
            var store_name = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{route('select_store_name_chplay')}}',
                type: "post",
                data: {
                    store_name:store_name,
                    _token:_token
                },
                success:function (data){
                    $('#chplay_dev_ga').text(data[0]+ ' | '+ data[1])

                }
            });
        });
        $('#Amazon_buildinfo_store_name_x').change(function (){
            var store_name = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{route('select_store_name_amazon')}}',
                type: "post",
                data: {
                    store_name:store_name,
                    _token:_token
                },
                success:function (data){
                    $('#amazon_dev_ga').text(data[0]+ ' | '+ data[1])

                }
            });
        });
        $('#Samsung_buildinfo_store_name_x').change(function (){
            var store_name = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{route('select_store_name_samsung')}}',
                type: "post",
                data: {
                    store_name:store_name,
                    _token:_token
                },
                success:function (data){
                    $('#samsung_dev_ga').text(data[0]+ ' | '+ data[1])

                }
            });
        });
        $('#Xiaomi_buildinfo_store_name_x').change(function (){
            var store_name = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{route('select_store_name_xiaomi')}}',
                type: "post",
                data: {
                    store_name:store_name,
                    _token:_token
                },
                success:function (data){
                    $('#xiaomi_dev_ga').text(data[0]+ ' | '+ data[1])

                }
            });
        });
        $('#Oppo_buildinfo_store_name_x').change(function (){
            var store_name = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{route('select_store_name_oppo')}}',
                type: "post",
                data: {
                    store_name:store_name,
                    _token:_token
                },
                success:function (data){
                    $('#oppo_dev_ga').text(data[0]+ ' | '+ data[1])

                }
            });
        });
        $('#Vivo_buildinfo_store_name_x').change(function (){
            var store_name = $(this).val();
            var _token = $('input[name=_token]').val();

            $.ajax({
                url: '{{route('select_store_name_vivo')}}',
                type: "post",
                data: {
                    store_name:store_name,
                    _token:_token
                },
                success:function (data){
                    $('#vivo_dev_ga').text(data[0]+ ' | '+ data[1])

                }
            });
        });
        $('#Huawei_buildinfo_store_name_x').change(function (){
            var store_name = $(this).val();
            var _token = $('input[name=_token]').val();

            $.ajax({
                url: '{{route('select_store_name_huawei')}}',
                type: "post",
                data: {
                    store_name:store_name,
                    _token:_token
                },
                success:function (data){
                    $('#huawei_dev_ga').text(data[0]+ ' | '+ data[1])

                }
            });
        });

        $('#buildinfo_keystore').change(function (){
            var project_id = $('#project_id').val();
            var buildinfo_keystore = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{asset('project/select-buildinfo_keystore')}}',
                type: "post",
                data: {
                    buildinfo_keystore:buildinfo_keystore,
                    project_id:project_id,
                    _token:_token
                },
                success:function (data){
                    var sha_256 = 'null';
                    if(data[0].SHA_256_keystore){
                        sha_256 = data[0].SHA_256_keystore
                    }
                    $('#p_buildinfo_keystore').text(sha_256)
                    if(data[1].Chplay_keystore_profile == '0' || data[1].Chplay_keystore_profile == null ){
                        $('#Chplay_keystore_profile').val(data[0].name_keystore);
                        $('#Chplay_keystore_profile').select2();
                        $('#p_buildinfo_keystore_chplay').text(sha_256)
                    }
                    if(data[1].Amazon_keystore_profile == '0' || data[1].Amazon_keystore_profile == null ){
                        $('#Amazon_keystore_profile').val(data[0].name_keystore);
                        $('#Amazon_keystore_profile').select2();
                        $('#p_buildinfo_keystore_amazon').text(sha_256)
                    }
                    if(data[1].Samsung_keystore_profile == '0' || data[1].Samsung_keystore_profile == null ){
                        $('#Samsung_keystore_profile').val(data[0].name_keystore);
                        $('#Samsung_keystore_profile').select2();
                        $('#p_buildinfo_keystore_samsung').text(sha_256)
                    }
                    if(data[1].Xiaomi_keystore_profile == '0' || data[1].Xiaomi_keystore_profile == null ){
                        $('#Xiaomi_keystore_profile').val(data[0].name_keystore);
                        $('#Xiaomi_keystore_profile').select2();
                        $('#p_buildinfo_keystore_xiaomi').text(sha_256)
                    }
                    if(data[1].Oppo_keystore_profile == '0' || data[1].Oppo_keystore_profile == null ){
                        $('#Oppo_keystore_profile').val(data[0].name_keystore);
                        $('#Oppo_keystore_profile').select2();
                        $('#p_buildinfo_keystore_oppo').text(sha_256)
                    }
                    if(data[1].Vivo_keystore_profile == '0' || data[1].Vivo_keystore_profile == null ){
                        $('#Vivo_keystore_profile').val(data[0].name_keystore);
                        $('#Vivo_keystore_profile').select2();
                        $('#p_buildinfo_keystore_vivo').text(sha_256)
                    }
                    if(data[1].Huawei_keystore_profile == '0' || data[1].Huawei_keystore_profile == null ){
                        $('#Huawei_keystore_profile').val(data[0].name_keystore);
                        $('#Huawei_keystore_profile').select2();
                        $('#p_buildinfo_keystore_huawei').text(sha_256)
                    }
                }
            });
        });

        $('#Chplay_keystore_profile').change(function (){
            var buildinfo_keystore = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{asset('project/select-buildinfo_keystore')}}',
                type: "post",
                data: {
                    buildinfo_keystore:buildinfo_keystore,
                    _token:_token
                },
                success:function (data){

                    var sha_256 = 'null';
                    if(data[0].SHA_256_keystore){
                        sha_256 = data[0].SHA_256_keystore
                    }
                    $('#p_buildinfo_keystore_chplay').text(sha_256)
                }
            });
        });
        $('#Amazon_keystore_profile').change(function (){
            var buildinfo_keystore = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{asset('project/select-buildinfo_keystore')}}',
                type: "post",
                data: {
                    buildinfo_keystore:buildinfo_keystore,
                    _token:_token
                },
                success:function (data){

                    var sha_256 = 'null';
                    if(data[0].SHA_256_keystore){
                        sha_256 = data[0].SHA_256_keystore
                    }
                    $('#p_buildinfo_keystore_amazon').text(sha_256)
                }
            });
        });
        $('#Samsung_keystore_profile').change(function (){
            var buildinfo_keystore = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{asset('project/select-buildinfo_keystore')}}',
                type: "post",
                data: {
                    buildinfo_keystore:buildinfo_keystore,
                    _token:_token
                },
                success:function (data){

                    var sha_256 = 'null';
                    if(data[0].SHA_256_keystore){
                        sha_256 = data[0].SHA_256_keystore
                    }
                    $('#p_buildinfo_keystore_samsung').text(sha_256)
                }
            });
        });
        $('#Xiaomi_keystore_profile').change(function (){
            var buildinfo_keystore = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{asset('project/select-buildinfo_keystore')}}',
                type: "post",
                data: {
                    buildinfo_keystore:buildinfo_keystore,
                    _token:_token
                },
                success:function (data){

                    var sha_256 = 'null';
                    if(data[0].SHA_256_keystore){
                        sha_256 = data[0].SHA_256_keystore
                    }
                    $('#p_buildinfo_keystore_xiaomi').text(sha_256)
                }
            });
        });
        $('#Oppo_keystore_profile').change(function (){
            var buildinfo_keystore = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{asset('project/select-buildinfo_keystore')}}',
                type: "post",
                data: {
                    buildinfo_keystore:buildinfo_keystore,
                    _token:_token
                },
                success:function (data){

                    var sha_256 = 'null';
                    if(data[0].SHA_256_keystore){
                        sha_256 = data[0].SHA_256_keystore
                    }
                    $('#p_buildinfo_keystore_oppo').text(sha_256)
                }
            });
        });
        $('#Vivo_keystore_profile').change(function (){
            var buildinfo_keystore = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{asset('project/select-buildinfo_keystore')}}',
                type: "post",
                data: {
                    buildinfo_keystore:buildinfo_keystore,
                    _token:_token
                },
                success:function (data){

                    var sha_256 = 'null';
                    if(data[0].SHA_256_keystore){
                        sha_256 = data[0].SHA_256_keystore
                    }
                    $('#p_buildinfo_keystore_vivo').text(sha_256)
                }
            });
        });
        $('#Huawei_keystore_profile').change(function (){
            var buildinfo_keystore = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url: '{{asset('project/select-buildinfo_keystore')}}',
                type: "post",
                data: {
                    buildinfo_keystore:buildinfo_keystore,
                    _token:_token
                },
                success:function (data){

                    var sha_256 = 'null';
                    if(data[0].SHA_256_keystore){
                        sha_256 = data[0].SHA_256_keystore
                    }
                    $('#p_buildinfo_keystore_huawei').text(sha_256)
                }
            });
        });
    </script>
    <script>
        function rebuildMadaOption(du_an){
            var elementSelect = $("#ma_da");

            if(elementSelect.length <= 0){
                return false;
            }
            elementSelect.empty();

            for(var da of du_an){
                elementSelect.append(
                    $("<option></option>", {
                        value : da.id
                    }).text(da.ma_da)
                );
            }
        }
        function rebuildTemplateOption(template){
            var elementSelect = $("#template");
            if(elementSelect.length <= 0){
                return false;
            }
            elementSelect.empty();

            for(var temp of template){
                elementSelect.append(
                    $("<option></option>", {
                        value : temp.id
                    }).text(temp.template)
                );
            }
        }
        function rebuildKeystoreOption(keystore){
            var elementSelect = $("#buildinfo_keystore");

            if(elementSelect.length <= 0){
                return false;
            }
            elementSelect.empty();

            for(var keys of keystore){
                elementSelect.append(
                    $("<option></option>", {
                        value : keys.name_keystore
                    }).text(keys.name_keystore)
                );
            }
        }


        document.getElementById("button_buildinfo_keystore").addEventListener("click", copy);
        document.getElementById("button_buildinfo_keystore_chplay").addEventListener("click", copy_key_chplay);
        document.getElementById("button_buildinfo_keystore_amazon").addEventListener("click", copy_key_amazon);
        document.getElementById("button_buildinfo_keystore_samsung").addEventListener("click", copy_key_samsung);
        document.getElementById("button_buildinfo_keystore_xiaomi").addEventListener("click", copy_key_xiaomi);
        document.getElementById("button_buildinfo_keystore_oppo").addEventListener("click", copy_key_oppo);
        document.getElementById("button_buildinfo_keystore_vivo").addEventListener("click", copy_key_vivo);
        document.getElementById("button_buildinfo_keystore_huawei").addEventListener("click", copy_key_huawei);
        function copy() {
            var copyText = document.getElementById("p_buildinfo_keystore");
            var textArea = document.createElement("textarea");
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("Copy");
            textArea.remove();
        }
        function copy_key_chplay() {
            var copyText = document.getElementById("p_buildinfo_keystore_chplay");
            var textArea = document.createElement("textarea");
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("Copy");
            textArea.remove();
        }
        function copy_key_amazon() {
            var copyText = document.getElementById("p_buildinfo_keystore_amazon");
            var textArea = document.createElement("textarea");
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("Copy");
            textArea.remove();
        }
        function copy_key_samsung() {
            var copyText = document.getElementById("p_buildinfo_keystore_samsung");
            var textArea = document.createElement("textarea");
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("Copy");
            textArea.remove();
        }
        function copy_key_xiaomi() {
            var copyText = document.getElementById("p_buildinfo_keystore_xiaomi");
            var textArea = document.createElement("textarea");
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("Copy");
            textArea.remove();
        }
        function copy_key_oppo() {
            var copyText = document.getElementById("p_buildinfo_keystore_oppo");
            var textArea = document.createElement("textarea");
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("Copy");
            textArea.remove();
        }
        function copy_key_vivo() {
            var copyText = document.getElementById("p_buildinfo_keystore_vivo");
            var textArea = document.createElement("textarea");
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("Copy");
            textArea.remove();
        }
        function copy_key_huawei() {
            var copyText = document.getElementById("p_buildinfo_keystore_huawei");
            var textArea = document.createElement("textarea");
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("Copy");
            textArea.remove();
        }

        function copyTitleEN() {
            let textarea = document.getElementById("title_app_en");
            textarea.select();
            document.execCommand("copy");
        }
        function copySumEN() {
            let textarea = document.getElementById("summary_en");
            textarea.select();
            document.execCommand("copy");
        }
        function copyDesEN() {
            tinyMCE.execCommand('selectAll',true,'des_en');
            tinyMCE.execCommand('copy',true,'des_en');
        }

        function copyTitleVN() {
            let textarea = document.getElementById("title_app_vn");
            textarea.select();
            document.execCommand("copy");
        }
        function copySumVN() {
            let textarea = document.getElementById("summary_vn");
            textarea.select();
            document.execCommand("copy");
        }
        function copyDesVN() {
            let textarea = document.getElementById("des_vn");
            tinyMCE.execCommand('selectAll',true,textarea);
            tinyMCE.execCommand('copy',true,textarea);
        }

        $("#title_app_en").on("input", function() {
            $("#count_title_app_en").text(this.value.length);
        });
        $("#summary_en").on("input", function() {
            $("#count_summary_en").text(this.value.length);
        });

        $("#title_app_vn").on("input", function() {
            $("#count_title_app_vn").text(this.value.length);
        });
        $("#summary_vn").on("input", function() {
            $("#count_summary_vn").text(this.value.length);
        });

        function copyPackage(e) {
            var copyText = $(e).attr('data-text');
            var textarea = document.createElement("textarea");
            textarea.textContent = copyText;
            textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
        }


    </script>


@endsection


