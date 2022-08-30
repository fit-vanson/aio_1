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
    <h4 class="page-title">Check Api</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">

        <a class="btn btn-success" href="javascript:void(0)" id="createNewCheckAPI"> Thêm mới</a>

    </div>
</div>
@include('modals.checkapi')
@endsection
@section('content')



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Tên</th>
                            <th>URL</th>
                            <th>Type</th>
                            <th width="20px">Action</th>
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
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="plugins/select2/js/select2.min.js"></script>
    <script src="plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js"></script>
    <script src="plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>
    <!-- Plugins Init js -->
    <script src="assets/pages/form-advanced.js"></script>





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


<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],

            processing: true,
            serverSide: true,
            ajax:{
                url: "{{ route('checkapi.getIndex') }}",
                type: "POST",
            },
            columns: [
                // { "data": null,"sortable": true,
                //     render: function (data, type, row, meta) {
                //         return meta.row + meta.settings._iDisplayStart + 1;
                //     }
                // },

                {data: 'checkapi_name', name: 'checkapi_name'},
                {data: 'checkapi_url', name: 'checkapi_url'},
                {data: 'checkapi_type', name: 'checkapi_type'},

                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('#createNewCheckAPI').click(function () {
            $('#saveBtn').val("create-checkapi");
            $('#id').val('');
            $('#checkapiForm').trigger("reset");
            $('#checkapiHeading').html("Thêm mới");
            $('#checkapiModel').modal('show');
        });
        $('#checkapiForm').on('submit',function (event){
            event.preventDefault();
            if($('#saveBtn').val() == 'create-checkapi'){
                $.ajax({
                    data: $('#checkapiForm').serialize(),
                    url: "{{ route('checkapi.create') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#checkapiForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#checkapiForm').trigger("reset");
                            $('#checkapiModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-checkapi'){
                $.ajax({
                    data: $('#checkapiForm').serialize(),
                    url: "{{ route('checkapi.update') }}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#checkapiForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#checkapiForm').trigger("reset");
                            $('#checkapiModel').modal('hide');
                            table.draw();
                        }
                    },
                });

            }

        });
        $(document).on('click','.deleteCheckAPI', function (data){
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
                        url: "{{ asset("checkapi/delete") }}/" + id,
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
    function editCheckAPI(id) {

        $.get('{{asset('checkapi/edit')}}/'+id,function (data) {
            $('#checkapiHeading').html("Edit");
            $('#saveBtn').val("edit-checkapi");
            $('#checkapiModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });

            console.log(data)




            $('#id').val(data.id);
            $('#checkapi_code').val(data.checkapi_code);
            $('#checkapi_name').val(data.checkapi_name);
            $('#checkapi_url').val(data.checkapi_url);





        })
    }
</script>
@endsection


