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

@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Quản lý Project</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
        <a class="btn btn-success" href="javascript:void(0)" id="createNewProject"> Create New Project</a>
    </div>
</div>

@include('modals.project2')

@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th >ID</th>
                            <th width="10%">Logo</th>
                            <th width="20%">Mã dự án</th>
                            <th width="30%">Package</th>
                            <th width="30%">Trạng thái Ứng dụng | Policy</th>
                            <th width="10%">Action</th>
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
<script src="plugins/select2/js/select2.min.js"></script>

<script>
    $("#template").select2({});
    $("#ma_da").select2({});
    $("#buildinfo_store_name_x").select2({});
</script>


<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            displayLength: 5,
            lengthMenu: [5, 10, 25, 50, 75, 100],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('project2.getIndex') }}",
                type: "post"
            },
            columns: [
                {data: 'updated_at', name: 'updated_at',},
                {data: 'logo', name: 'logo',orderable: false},
                {data: 'ma_da', name: 'ma_da'},
                {data: 'package', name: 'package',orderable: false},
                {data: 'status', name: 'status',orderable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            columnDefs: [
                {
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                }
            ],
            order: [[ 0, 'desc' ]]
        });

        $('#createNewProject').click(function () {
            $('#saveBtn').val("create-project");
            $('#project_id').val('');
            $("#avatar").attr("src","img/logo.png");
            $('#projectForm2').trigger("reset");
            $('#template').select2();
            $('#ma_da').select2();
            $('#modelHeading').html("Thêm mới Project");
            $('#ajaxModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });


        $('#projectForm2').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#projectForm2")[0]);
            if($('#saveBtn').val() == 'create-project'){
                $.ajax({
                    // data: $('#projectForm2').serialize(),
                    data: formData,
                    url: "{{ route('project2.create') }}",
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
                    url: "{{ route('project2.update') }}",
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
                            table.draw();
                        }
                    },
                });
            }
        });
        $('#projectQuickForm').on('submit',function (event){
            event.preventDefault();
            if($('#saveQBtn').val() == 'quick-edit-project'){
                $.ajax({
                    data: $('#projectQuickForm').serialize(),
                    url: "{{ route('project2.updateQuick') }}",
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
                            table.draw();
                        }
                    },
                });

            }

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
                        url: "{{ asset("project2/delete") }}/" + project_id,
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
    });
</script>
<script>
    function editProject(id) {
        $.get('{{asset('project2/edit')}}/'+id,function (data) {
            console.log(data)
            var Chplay_ads = '';
            var Amazon_ads = '';
            var Samsung_ads = '';
            var Xiaomi_ads = '';
            var Oppo_ads = '';
            var Vivo_ads = '';
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
            if(data[0].logo) {
                $("#avatar").attr("src","../uploads/project/"+data[0].projectname+"/thumbnail/"+data[0].logo);
            }else {
                $("#avatar").attr("src","img/logo.png");
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
            $('#buildinfo_sdk').val(data[0].buildinfo_sdk);
            $('#buildinfo_link_policy_x').val(data[0].buildinfo_link_policy_x);
            $('#buildinfo_link_youtube_x').val(data[0].buildinfo_link_youtube_x);
            $('#buildinfo_link_fanpage').val(data[0].buildinfo_link_fanpage);
            $('#buildinfo_api_key_x').val(data[0].buildinfo_api_key_x);
            $('#buildinfo_link_website').val(data[0].buildinfo_link_website);


            $('#Chplay_package').val(data[0].Chplay_package);
            $('#Chplay_ads_id').val(Chplay_ads.ads_id);
            $('#Chplay_ads_banner').val(Chplay_ads.ads_banner);
            $('#Chplay_ads_inter').val(Chplay_ads.ads_inter);
            $('#Chplay_ads_reward').val(Chplay_ads.ads_reward);
            $('#Chplay_ads_native').val(Chplay_ads.ads_native);
            $('#Chplay_ads_open').val(Chplay_ads.ads_open);
            $('#Chplay_buildinfo_store_name_x').val(data[0].Chplay_buildinfo_store_name_x);
            $('#Chplay_buildinfo_link_store').val(data[0].Chplay_buildinfo_link_store);
            $('#Chplay_buildinfo_link_app').val(data[0].Chplay_buildinfo_link_app);
            $('#Chplay_buildinfo_email_dev_x').val(data[0].Chplay_buildinfo_email_dev_x);
            $('#Chplay_status').val(data[0].Chplay_status);

            $('#Amazon_package').val(data[0].Amazon_package);
            $('#Amazon_ads_id').val(Amazon_ads.ads_id);
            $('#Amazon_ads_banner').val(Amazon_ads.ads_banner);
            $('#Amazon_ads_inter').val(Amazon_ads.ads_inter);
            $('#Amazon_ads_reward').val(Amazon_ads.ads_reward);
            $('#Amazon_ads_native').val(Amazon_ads.ads_native);
            $('#Amazon_ads_open').val(Amazon_ads.ads_open);
            $('#Amazon_buildinfo_store_name_x').val(data[0].Amazon_buildinfo_store_name_x);
            $('#Amazon_buildinfo_link_store').val(data[0].Amazon_buildinfo_link_store);
            $('#Amazon_buildinfo_link_app').val(data[0].Amazon_buildinfo_link_app);
            $('#Amazon_buildinfo_email_dev_x').val(data[0].Amazon_buildinfo_email_dev_x);
            $('#Amazon_status').val(data[0].Amazon_status);

            $('#Samsung_package').val(data[0].Samsung_package);
            $('#Samsung_ads_id').val(Samsung_ads.ads_id);
            $('#Samsung_ads_banner').val(Samsung_ads.ads_banner);
            $('#Samsung_ads_inter').val(Samsung_ads.ads_inter);
            $('#Samsung_ads_reward').val(Samsung_ads.ads_reward);
            $('#Samsung_ads_native').val(Samsung_ads.ads_native);
            $('#Samsung_ads_open').val(Samsung_ads.ads_open);
            $('#Samsung_buildinfo_store_name_x').val(data[0].Samsung_buildinfo_store_name_x);
            $('#Samsung_buildinfo_link_store').val(data[0].Samsung_buildinfo_link_store);
            $('#Samsung_buildinfo_link_app').val(data[0].Samsung_buildinfo_link_app);
            $('#Samsung_buildinfo_email_dev_x').val(data[0].Samsung_buildinfo_email_dev_x);
            $('#Samsung_status').val(data[0].Samsung_status);

            $('#Xiaomi_package').val(data[0].Xiaomi_package);
            $('#Xiaomi_ads_id').val(Xiaomi_ads.ads_id);
            $('#Xiaomi_ads_banner').val(Xiaomi_ads.ads_banner);
            $('#Xiaomi_ads_inter').val(Xiaomi_ads.ads_inter);
            $('#Xiaomi_ads_reward').val(Xiaomi_ads.ads_reward);
            $('#Xiaomi_ads_native').val(Xiaomi_ads.ads_native);
            $('#Xiaomi_ads_open').val(Xiaomi_ads.ads_open);
            $('#Xiaomi_buildinfo_store_name_x').val(data[0].Xiaomi_buildinfo_store_name_x);
            $('#Xiaomi_buildinfo_link_store').val(data[0].Xiaomi_buildinfo_link_store);
            $('#Xiaomi_buildinfo_link_app').val(data[0].Xiaomi_buildinfo_link_app);
            $('#Xiaomi_buildinfo_email_dev_x').val(data[0].Xiaomi_buildinfo_email_dev_x);
            $('#Xiaomi_status').val(data[0].Xiaomi_status);

            $('#Oppo_package').val(data[0].Oppo_package);
            $('#Oppo_ads_id').val(Oppo_ads.ads_id);
            $('#Oppo_ads_banner').val(Oppo_ads.ads_banner);
            $('#Oppo_ads_inter').val(Oppo_ads.ads_inter);
            $('#Oppo_ads_reward').val(Oppo_ads.ads_reward);
            $('#Oppo_ads_native').val(Oppo_ads.ads_native);
            $('#Oppo_ads_open').val(Oppo_ads.ads_open);
            $('#Oppo_buildinfo_store_name_x').val(data[0].Oppo_buildinfo_store_name_x);
            $('#Oppo_buildinfo_link_store').val(data[0].Oppo_buildinfo_link_store);
            $('#Oppo_buildinfo_link_app').val(data[0].Oppo_buildinfo_link_app);
            $('#Oppo_buildinfo_email_dev_x').val(data[0].Oppo_buildinfo_email_dev_x);
            $('#Oppo_status').val(data[0].Oppo_status);

            $('#Vivo_package').val(data[0].Vivo_package);
            $('#Vivo_ads_id').val(Vivo_ads.ads_id);
            $('#Vivo_ads_banner').val(Vivo_ads.ads_banner);
            $('#Vivo_ads_inter').val(Vivo_ads.ads_inter);
            $('#Vivo_ads_reward').val(Vivo_ads.ads_reward);
            $('#Vivo_ads_native').val(Vivo_ads.ads_native);
            $('#Vivo_ads_open').val(Vivo_ads.ads_open);
            $('#Vivo_buildinfo_store_name_x').val(data[0].Vivo_buildinfo_store_name_x);
            $('#Vivo_buildinfo_link_store').val(data[0].Vivo_buildinfo_link_store);
            $('#Vivo_buildinfo_link_app').val(data[0].Vivo_buildinfo_link_app);
            $('#Vivo_buildinfo_email_dev_x').val(data[0].Vivo_buildinfo_email_dev_x);
            $('#Vivo_status').val(data[0].Vivo_status);

            $('#modelHeading').html("Edit Project");
            $('#saveBtn').val("edit-project");
            $('#ajaxModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        })
    }
    function quickEditProject(id) {
        $.get('{{asset('project2/edit')}}/'+id,function (data) {
            $('#quick_project_id').val(data[0].projectid);
            $('#quick_buildinfo_vernum').val(data[0].buildinfo_vernum);
            $('#quick_buildinfo_verstr').val(data[0].buildinfo_verstr);
            $('#quick_buildinfo_console').val(data[0].buildinfo_console);
            $('#modelQuickHeading').html("Quick Edit Project");
            $('#saveQBtn').val("quick-edit-project");
            $('#ajaxQuickModel').modal('show');
        })
    }
    function showPolicy(id) {
        $.get('{{asset('project2/edit')}}/'+id,function (data) {
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
        console.log(template)
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
</script>


@endsection


