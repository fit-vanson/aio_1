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
        <h4 class="page-title">Quản lý Dev Huawei</h4>
    </div>
    <div class="col-sm-6">
        <div class="float-right">
            <a class="btn btn-success" href="javascript:void(0)" id="createNewDev"> Create New</a>
        </div>
    </div>
    @include('modals.dev-huawei')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Thuoc tính</th>
                            <th>Ga name</th>
                            <th>Dev name</th>
                            <th>Store name</th>
                            <th>Gmail </th>
                            <th>Pass </th>
                            <th>Trạng thái</th>
                            <th>Ghi chú</th>
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
                    url: "{{ route('dev_huawei.getIndex') }}",
                    type: "post"
                },
                columns: [
                    {data: 'huawei_attribute'},
                    {data: 'huawei_ga_name'},
                    {data: 'huawei_dev_name'},
                    {data: 'huawei_store_name'},
                    {data: 'huawei_email'},
                    {data: 'project'},
                    {data: 'huawei_status'},
                    {data: 'huawei_note'},
                    {data: 'action', className: "text-center",name: 'action', orderable: false, searchable: false},
                ],
                order:[1,'asc']
            });
            $('#createNewDev').click(function () {
                $('#saveBtn').val("create");
                $('#id').val('');
                $('#devhuaweiForm').trigger("reset");
                $('#modelHeading').html("Thêm mới Huawei");
                $('#ajaxModelDev').modal('show');
                $("#huawei_ga_name").select2({});
                $("#huawei_email").select2({});
            });
            $('#devhuaweiForm').on('submit',function (event){
                event.preventDefault();
                if($('#saveBtn').val() == 'create'){
                    $.ajax({
                        data: $('#devhuaweiForm').serialize(),
                        url: "{{ route('dev_huawei.create') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#devhuaweiForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#devhuaweiForm').trigger("reset");
                                $('#ajaxModelDev').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#saveBtn').val() == 'edit'){
                    $.ajax({
                        data: $('#devhuaweiForm').serialize(),
                        url: "{{ route('dev_huawei.update') }}",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#devhuaweiForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#devhuaweiForm').trigger("reset");
                                $('#ajaxModelDev').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }

            });

            $(document).on('click','.deleteDevhuawei', function (data){
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
                            url: "{{ asset("dev-huawei/delete") }}/" + id,
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
        function editDevhuawei(id) {
            $.get('{{asset('dev-huawei/edit')}}/'+id,function (data) {
                $('#id').val(data.id);

                if(data.huawei_attribute != 0){
                    $("#individual").prop('checked', true);
                }else{
                    $("#company").prop('checked', true);
                }

                $('#huawei_email').val(data.huawei_email);
                $('#huawei_email').select2();
                $('#huawei_ga_name').val(data.huawei_ga_name);
                $('#huawei_ga_name').select2();
                $('#huawei_note').val(data.huawei_note);
                $('#huawei_pass').val(data.huawei_pass);
                $('#huawei_status').val(data.huawei_status);
                $('#huawei_store_name').val(data.huawei_store_name);
                $('#huawei_phone').val(data.huawei_phone);
                $('#huawei_profile_info').val(data.huawei_profile_info);
                $('#huawei_profile_info').select2();
                $('#huawei_company').val(data.huawei_company);
                $('#huawei_dev_name').val(data.huawei_dev_name);
                $('#huawei_dev_client_secret').val(data.huawei_dev_client_secret);
                $('#huawei_dev_client_id').val(data.huawei_dev_client_id);
                $('#modelHeading').html("Edit");
                $('#saveBtn').val("edit");
                $('#ajaxModelDev').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }

        $('select').on('change', function() {
            var radio = document.querySelector('input[name="attribute"]:checked').value;
            $.get('{{asset('profile/show?ID=')}}'+this.value,function (data) {
                if(radio != 1){
                    if (data.profile.company[0]) {
                        $('#huawei_company').val(data.profile.company[0].name_en);
                        $('#huawei_add').val(data.profile.company[0].dia_chi);
                    } else {
                        $('#huawei_company').val('');
                        $('#huawei_add').val(data.profile.profile_add);
                    }
                }else {
                    $('#huawei_company').val('');
                    $('#huawei_add').val(data.profile.profile_add);
                }
            });
        });

        function getit(){
            var select = $('#huawei_profile_info').val();
            var radio = document.querySelector('input[name="attribute"]:checked').value;
            $.get('{{asset('profile/show?ID=')}}'+select,function (data) {
                if(radio == 1) {
                    $('#huawei_company').val('');
                    $('#huawei_add').val(data.profile.profile_add);
                }else {
                    if(data.profile.company[0]){
                        $('#huawei_company').val(data.profile.company[0].name_en);
                        $('#huawei_add').val(data.profile.company[0].dia_chi);
                    }
                }
            });
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

    </script>
    <script>
        function rebuildMailOption(mails){
            var elementSelect = $("#huawei_email");


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






