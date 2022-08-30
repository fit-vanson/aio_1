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
        <h4 class="page-title">Quản lý Dự án</h4>
    </div>
    <div class="col-sm-6">
        <div class="float-right">
            <a class="btn btn-success" href="javascript:void(0)" id="createNewDa"> Create New</a>
        </div>
    </div>
    @include('modals.da')
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
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th style="width:5%">STT</th>
                            <th class="all" style="width:15%">Mã dự án</th>
                            <th class="all" style="width:25%">Chủ đề</th>
                            <th class="all" style="width:25%">Key words</th>
                            <th style="width:5%">Link store</th>
                            <th class="all" style="width:20%">Ghi chú</th>
                            <th class="all" style="width:5%">Action</th>
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
                responsive: true,
                ajax: "{{ route('da.index') }}",

                columns: [
                    { "data": null,"sortable": true,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'ma_da'},
                    {data: 'chude'},
                    {data: 'keywords'},
                    {data: 'link_store_vietmmo'},
                    {data: 'note'},
                    {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
                ],
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            if(data != null){
                                return "<div class='text-wrap width-200'>" + data + "</div>";
                            }else {
                                return '';
                            }
                        },
                        targets: [3,5]
                    }
                ]
            });
            $('.data-table td').css('white-space','initial');
            $('#createNewDa').click(function () {
                $('#saveBtn').val("create-da");
                $('#da_id').val('');
                $('#daForm').trigger("reset");
                $('#modelHeading').html("Thêm mới");
                $('#ajaxModel').modal('show');
            });
            $('#daForm').on('submit',function (event){
                event.preventDefault();
                if($('#saveBtn').val() == 'create-da'){
                    $.ajax({
                        data: $('#daForm').serialize(),
                        url: "{{ route('da.create') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#daForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#daForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#saveBtn').val() == 'edit-da'){
                    $.ajax({
                        data: $('#daForm').serialize(),
                        url: "{{ route('da.update') }}",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#daForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#daForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }

            });
            $(document).on('click','.editDa', function (data){
                var da_id = $(this).data('id');

                $('#modelHeading').html("Edit");
                $('#saveBtn').val("edit-da");
                $('#ajaxModel').modal('show');
                $.ajax({
                    data: $('#daForm').serialize(),
                    url: "{{ asset("da/edit") }}/" + da_id,
                    type: "get",
                    dataType: 'json',
                    success: function (data) {
                        $('#da_id').val(data.id);
                        $('#ma_da').val(data.ma_da);
                        $('#chude').val(data.chude);
                        $('#keywords').val(data.keywords);
                        $('#link_store_vietmmo').val(data.link_store_vietmmo);
                        $('#note').val(data.note);
                    }
                });

            });

            $(document).on('click','.deleteDa', function (data){
                var da_id = $(this).data("id");
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
                        url: "{{ asset("da/delete") }}/" + da_id,
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
@endsection






