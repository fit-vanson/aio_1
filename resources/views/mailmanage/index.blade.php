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
    <h4 class="page-title">Quản lý Mail</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
        @can('mail_manage-add')
        <a class="btn btn-success" href="javascript:void(0)" id="createNewMail">Thêm mới</a>
        @endcan
    </div>
</div>
@include('modals.mailmanage')
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên mail</th>
                            <th>Code 2FA</th>
                            <th>Time Update</th>
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
            ajax: "{{ route('mail_manage.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'email', name: 'email'},
                {data: 'secret_code', name: 'secret_code'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ]
        });
        setInterval( function () {
            table.ajax.reload();
        }, 18000 );
        $('#createNewMail').click(function () {
            $('#saveBtn').val("create-mail");
            $('#id').val('');
            $('#mailForm').trigger("reset");
            $('#modelHeading').html("Thêm mới email");
            $('#ajaxModel').modal('show');
        });
        $('#mailForm').on('submit',function (event){
            event.preventDefault();
            if($('#saveBtn').val() == 'create-mail'){
                $.ajax({
                    data: $('#mailForm').serialize(),
                    url: "{{ route('mail_manage.create') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#mailForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#mailForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-mail'){
                $.ajax({
                    data: $('#mailForm').serialize(),
                    url: "{{ route('mail_manage.update') }}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#mailForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#mailForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });

            }

        });
        $(document).on('click','.deleteMail', function (data){
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
                        url: "{{ asset("mail_manage/delete") }}/" + id,
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

    function editMail(id) {

        $.get('{{asset('mail_manage/edit')}}/'+id,function (data) {
            $('#modelHeading').html("Edit Mail");
            $('#saveBtn').val("edit-mail");
            $('#ajaxModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });

            $('#id').val(data.id);
            $('#email').val(data.email);
            $('#secret_code').val(data.secret_code)
        })
    }

</script>
@endsection


