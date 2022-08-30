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
    <h4 class="page-title">Quản lý Keystore</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
        <a class="btn btn-success" href="javascript:void(0)" id="createNewKeystore"> Create New</a>
    </div>
</div>
@include('modals.keystore')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @can('keystore-add')
                    <div class="card-body">
                        <div class="button-items console_status_button">
                            <button type="button" class="btn btn-primary waves-effect waves-light" id="createMultiple">Multiple</button>

                        </div>
                    </div>
                @endcan
                <div class="card-body">

                     <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Tên Keystore</th>
                            <th>Pass Keystore</th>
                            <th>Aliases</th>
                            <th>Pass Aliases</th>
                            <th>SHA_256 Keystore</th>
                            <th>File</th>
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
                url: "{{ route('keystore.getIndex') }}",
                type: "post"
            },
            columns: [
                {data: 'name_keystore'},
                {data: 'pass_keystore'},
                {data: 'aliases_keystore'},
                {data: 'pass_aliases'},
                {data: 'SHA_256_keystore'},
                {data: 'file'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
            "columnDefs": [
                { "orderable": false, "targets": [0,2,3] }
            ],
            order:[1,'asc']

        });

        $('#createNewKeystore').click(function () {
            $('#saveBtn').val("create-keystore");
            $('#keystore_id').val('');
            $('#keystoreForm').trigger("reset");
            $('#modelHeading').html("Thêm mới Keystore");
            $('#ajaxModel').modal('show');
        });
        $('#keystoreForm').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#keystoreForm")[0]);
            if($('#saveBtn').val() == 'create-keystore'){
                $.ajax({
                    data: formData,
                    url: "{{ route('keystore.create') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#keystoreForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#keystoreForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-keystore'){
                $.ajax({
                    data: formData,
                    url: "{{ route('keystore.update') }}",
                    type: "post",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#keystoreForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#keystoreForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });

            }

        });

        $(document).on('click','.deleteKeystore', function (data){
            var keystore_id = $(this).data("id");
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
                        url: "{{ asset("keystore/delete") }}/" + keystore_id,
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

        $('#createMultiple').on('click', function () {
            $('#addKeystoreMultiple').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });


        $('#KeystoreMultipleForm button').click(function (event){
            event.preventDefault();
            $.ajax({
                data: $('#KeystoreMultipleForm').serialize(),
                url: "{{ route('keystore.updateMultiple')}}",
                type: "post",
                dataType: 'json',
                success: function (data) {
                    if(data.errors){
                        $.notify(data.errors, "error");
                    }
                    if(data.success){
                        $.notify(data.success, "success");
                        $('#KeystoreMultipleForm').trigger("reset");
                        $('#addKeystoreMultiple').modal('hide');
                        table.draw();
                    }
                },
            });



        });
    });
</script>

<script>
    function editKeytore(id) {
        $.get('{{asset('keystore/edit')}}/'+id,function (data) {
            $('#keystore_id').val(data.id);
            $('#name_keystore').val(data.name_keystore);
            $('#pass_keystore').val(data.pass_keystore);
            $('#aliases_keystore').val(data.aliases_keystore);
            $('#pass_aliases').val(data.pass_aliases);
            $('#SHA_256_keystore').val(data.SHA_256_keystore);
            // $('#keystore_file').val(data.file);
            $("#keystore_file").attr("src","img/logo.png");
            $('#note').val(data.note);

            $('#modelHeading').html("Edit");
            $('#saveBtn').val("edit-keystore");
            $('#ajaxModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        })
    }




</script>



@endsection






