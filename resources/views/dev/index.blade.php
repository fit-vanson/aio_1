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
        <h4 class="page-title">Quản lý Dev</h4>
    </div>
    <div class="col-sm-6">
        <div class="float-right">
            <a class="btn btn-success" href="javascript:void(0)" id="createNewDev"> Create New</a>
        </div>
    </div>
    @include('modals.dev')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
{{--                            <th>IMG</th>--}}
                            <th>Ga name</th>
                            <th>Dev name</th>
                            <th>Gmail </th>
                            <th style="width: 10%">Tổng App | App Release | App Check </th>
                            <th>Thuộc tính</th>
{{--                            <th>Thuộc tính</th>--}}
                            <th>Link | Web | Fanpage |Policy</th>
                            <th>Trạng thái</th>
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

    <script src="plugins/select2/js/select2.min.js"></script>
    <script>
        $("#id_ga").select2({});
        $("#gmail_gadev_chinh").select2({});
    </script>
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
                ajax: {
                    url: "{{ route('dev.getIndex') }}",
                    type: "post"
                },
                columns: [
                    // {data: 'info_logo'},
                    {data: 'id_ga'},
                    {data: 'dev_name'},
                    {data: 'gmail_gadev_chinh'},
                    {data: 'project_count',searchable: false,className: "text-center"},
                    {data: 'thuoc_tinh',className: "text-center"},
                    // {data: 'info_phone'},
                    {data: 'info_url'},
                    {data: 'status'},
                    {data: 'action', className: "text-center",name: 'action', orderable: false, searchable: false},
                ],
                order:[1,'asc']
            });
            $('#createNewDev').click(function () {
                $('#saveBtn').val("create");
                $('#id').val('');
                $('#devAmazonForm').trigger("reset");
                $('#modelHeading').html("Thêm mới");
                $('#ajaxModelDev').modal('show');
                $('#id_ga').select2();
                $('#gmail_gadev_chinh').select2();
                $('#gmail_gadev_phu_1').select2();
                $('#gmail_gadev_phu_2').select2();
                $('#profile_info').select2();

            });
            $('#devForm').on('submit',function (event){
                event.preventDefault();
                if($('#saveBtn').val() == 'create'){
                    $.ajax({
                        data: $('#devForm').serialize(),
                        url: "{{ route('dev.create') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#devForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#devForm').trigger("reset");
                                $('#ajaxModelDev').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#saveBtn').val() == 'edit-dev'){
                    $.ajax({
                        data: $('#devForm').serialize(),
                        url: "{{ route('dev.update') }}",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#devForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#devForm').trigger("reset");
                                $('#ajaxModelDev').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }

            });
            $(document).on('click','.deleteDev', function (data){
                var id = $(this).data("id");

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
                            url: "{{ asset("dev/delete") }}/" + id,
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
        function editDev(id) {
            $.get('{{asset('dev/edit')}}/'+id,function (data) {
                $('#dev_id').val(data.id);
                $('#store_name').val(data.store_name);
                $('#dev_name').val(data.dev_name);
                $('#ma_hoa_don').val(data.ma_hoa_don);

                $('#id_ga').val(data.id_ga);
                $('#id_ga').select2();


                $('#gmail_gadev_chinh').val(data.gmail_gadev_chinh);
                $('#gmail_gadev_chinh').select2();

                $('#gmail_gadev_phu_1').val(data.gmail_gadev_phu_1);
                $('#gmail_gadev_phu_1').select2();

                $('#gmail_gadev_phu_2').val(data.gmail_gadev_phu_2);
                $('#gmail_gadev_phu_2').select2();

                $('#info_phone').val(data.info_phone);
                $('#pass').val(data.pass);
                $('#info_andress').val(data.info_andress);
                $('#info_company').val(data.info_company);
                $('#profile_info').val(data.profile_info);
                $('#profile_info').select2();
                $('#info_url').val(data.info_url);
                $('#info_logo').val(data.info_logo);
                $('#info_banner').val(data.info_banner);
                $('#info_policydev').val(data.info_policydev);
                $('#info_fanpage').val(data.info_fanpage);
                $('#info_web').val(data.info_web);
                $('#status').val(data.status);
                $('#note').val(data.note);

                if(data.thuoc_tinh == 1){
                    $('.thuoc_tinh').show();
                    $('.info_company').hide();
                    $("#individual1").prop('checked', true);
                }else{
                    $('.thuoc_tinh').show();
                    $('.info_company').show();

                    $("#company1").prop('checked', true);
                }

                $('#modelHeading').html("Edit");
                $('#saveBtn').val("edit-dev");
                $('#ajaxModelDev').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
    </script>
    <script>
        $("#addGaDevForm").submit(function (e) {
            e.preventDefault();
            let data = new FormData(document.getElementById('addGaDevForm'));
            $.ajax({
                url:"{{route('gadev.create')}}",
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
                            $("#addGaDevForm").notify(
                                data.errors[count],"error",
                                { position:"right" }
                            );
                        }
                    }
                    $.notify(data.success, "success");
                    $('#addGaDevForm').trigger("reset");
                    $('#addGaDev').modal('hide');
                    if(typeof data.allGa_dev == 'undefined'){
                        data.allGa_dev = {};                    }
                    if(typeof rebuildMailOption == 'function'){
                        rebuildMailOption(data.allGa_dev)
                    }
                }
            });
        });
        function getit(){
            var select = $('#profile_info').val();
            var radio = document.querySelector('input[name="attribute1"]:checked').value;
            if(radio == 1) {
                $.get('{{asset('profile/show?ID=')}}'+select,function (data) {
                    $('#info_company').val('');
                    $('.info_company').hide();
                    $('#info_andress').val(data.profile.profile_add);
                });
            }else {
                $.get('{{asset('profile/show?ID=')}}'+select,function (data) {
                    if(data.profile.company[0]){
                        $('.info_company').show();
                        $('#info_company').val(data.profile.company[0].name_en);
                        $('#info_andress').val(data.profile.company[0].dia_chi);
                    }
                });
            }
        }

        $('select').on('change', function() {
            var radio = document.querySelector('input[name="attribute1"]:checked').value;
            $.get('{{asset('profile/show?ID=')}}' + this.value, function (data) {
                if(radio != 1){
                    if (data.profile.company[0]) {
                        $('.info_company').show();
                        $('#info_company').val(data.profile.company[0].name_en);
                        $('#info_andress').val(data.profile.company[0].dia_chi);
                    } else {
                        $('.info_company').hide();
                        $('#info_company').val('');
                        $('#info_andress').val(data.profile.profile_add);
                    }
                }else {
                    $('.info_company').hide();
                    $('#info_company').val('');
                    $('#info_andress').val(data.profile.profile_add);
                }
            });
        });

    </script>
    <script>
        function rebuildMailOption(mails){
            var elementSelect = $("#amazon_email");
            if(elementSelect.length <= 0){
                return false;
            }
            elementSelect.empty();
            for(var m of mails){
                elementSelect.append(
                    $("<option></option>", {
                        value : m.id
                    }).text(m.gmail)
                );
            }
        }
    </script>
@endsection






