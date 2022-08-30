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
        <h4 class="page-title">Quản lý Device</h4>
    </div>
    <div class="col-sm-6">
        <div class="float-right">
            <a class="btn btn-success" href="javascript:void(0)" id="createNewDevice"> Create New</a>
        </div>
    </div>
    @include('modals.device')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Android</th>
                            <th>verrelease</th>
                            <th>sdk</th>
                            <th>productmodel</th>
                            <th>productbrand</th>
                            <th>productname</th>
                            <th>productmanufacturer</th>

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
                displayLength: 200,
                lengthMenu: [50, 100,200, 500,1000],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('device.getIndex') }}",
                    type :"Post",
                },
                columns: [
                    {data: 'id'},
                    {data: 'android'},
                    {data: 'verrelease'},
                    {data: 'sdk'},
                    {data: 'productmodel'},
                    {data: 'productbrand'},
                    {data: 'productname'},
                    {data: 'productmanufacturer'},
                    {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
                ],
            });
            $('#createNewDevice').click(function () {
                $('#saveBtn').val("create-device");
                $('#deviceForm').trigger("reset");
                $('#modelHeading').html("Thêm mới");
                $('#ajaxModel').modal('show');

            });
            $('#deviceForm').on('submit',function (event){
                event.preventDefault();
                if($('#saveBtn').val() == 'create-device'){
                    $.ajax({
                        data: $('#deviceForm').serialize(),
                        url: "{{ route('device.create') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#deviceForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#deviceForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#saveBtn').val() == 'edit-device'){
                    $.ajax({
                        data: $('#deviceForm').serialize(),
                        url: "{{ route('device.update') }}",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#deviceForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#deviceForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }

            });
            $(document).on('click','.deleteDevice', function (data){
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
                            url: "{{ asset("device-info/delete") }}/" + id,
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
        function editDevice(id) {
            $.get('{{asset('device-info/edit')}}/'+id,function (data) {
                $('#id').val(data.id);
                $('#android').val(data.android);
                $('#verrelease').val(data.verrelease);
                $('#buildid').val(data.buildid);
                $('#displayid').val(data.displayid);
                $('#incremental').val(data.incremental);
                $('#sdk').val(data.sdk);
                $('#builddate').val(data.builddate);
                $('#builddateutc').val(data.builddateutc);
                $('#productmodel').val(data.productmodel);
                $('#productbrand').val(data.productbrand);
                $('#productname').val(data.productname);
                $('#productdevice').val(data.productdevice);
                $('#productboard').val(data.productboard);
                $('#productmanufacturer').val(data.productmanufacturer);
                $('#description').val(data.description);
                $('#fingerprint').val(data.fingerprint);
                $('#characteristics').val(data.characteristics);
                $('#datagoc').val(data.datagoc);
                $('#note').val(data.note);
                $('#status').val(data.status);

                $('#modelHeading').html("Edit");
                $('#saveBtn').val("edit-device");
                $('#ajaxModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
    </script>

@endsection






