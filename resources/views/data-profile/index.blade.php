@extends('layouts.master')

@section('css')

<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />




<!-- Sweet-Alert  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />


@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Data </h4>
</div>
@can('template-preview-index')
<div class="col-sm-6">
    <div class="float-right">
        <a class="btn btn-success" href="javascript:void(0)" id="createNewDataProfile"> Create New</a>
    </div>
</div>
@endcan
@include('modals.dataProfile')
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
{{--                    <table class="table table-bordered data-table">--}}
                     <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th style="width: 15%">Name</th>
                            <th style="width: 15%">File</th>
                            <th style="width: 20%">Ghi chú</th>
                            <th style="width: 10%">Action</th>
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
            displayLength: 50,

            ajax: {
                url: "{{ route('data_profile.getIndex') }}",
                type: "post"
            },
            columns: [
                {data: 'data_name'},
                {data: 'data_file'},
                {data: 'data_note'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
        });

        $('#createNewDataProfile').click(function () {

            $("#data_file").attr("required", "true");
            $('#saveBtn').val("create-data-profile");
            $('#dataProfileForm').trigger("reset");
            $('#dataProfileLabel').html("Thêm mới data");
            $('#dataProfileModal').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });
        $('#dataProfileForm').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#dataProfileForm")[0]);
            if($('#saveBtn').val() == 'create-data-profile'){
                $.ajax({
                    data: formData,
                    url: "{{ route('data_profile.create') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#dataProfileForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#dataProfileForm').trigger("reset");
                            $('#dataProfileModal').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-data-profile'){
                $.ajax({
                    data: formData,
                    url: "{{ route('data_profile.update') }}",
                    type: "post",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#dataProfileForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#dataProfileForm').trigger("reset");
                            $('#dataProfileModal').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
        });


        $('#CategoryTemplateChildForm').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#CategoryTemplateChildForm")[0]);
            if($('#saveBtnChild').val() == 'add-category-template'){
                $.ajax({
                    data: formData,
                    url: "{{ route('category_template.create') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#CategoryTemplateChildForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#CategoryTemplateChildForm').trigger("reset");
                            $('#categoryTemplateChildModel').modal('hide');
                            table.draw();

                            if(typeof data.cate_temp == 'undefined'){
                                data.cate_temp = {};
                            }
                            if(typeof rebuildCateTempOption == 'function'){
                                rebuildCateTempOption(data.cate_temp)
                            }
                        }
                    },
                });
            }
            if($('#saveBtnChild').val() == 'edit-category-template'){
                $.ajax({
                    data: formData,
                    url: "{{ route('category_template.update') }}",
                    type: "post",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#CategoryTemplateChildForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#CategoryTemplateChildForm').trigger("reset");
                            $('#categoryTemplateChildModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
        });
        $(document).on('click','.deleteDataprofile', function (data){
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
                        url: "{{ asset("data-profile/delete") }}/" + id,
                        success: function (data) {
                            if(data.errors){
                                swal({
                                    title: "Error!",
                                    text: data.errors,
                                    type: "error",
                                    timer: 1500,
                                });
                            }
                            if(data.success){
                                table.draw();
                                swal({
                                    title: "Đã xóa!",
                                    text: "Your imaginary file has been deleted.",
                                    type: "success",
                                    timer: 1000,
                                });
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });

                });
        });
    });
</script>
<script>
    function editDataprofile(id) {
        $.get('{{asset('data-profile/edit')}}/'+id,function (data) {
            $('#data_id').val(data.id);
            $('#data_name').val(data.data_name);
            $('#data_note').val(data.data_note);
            $('#data_file').removeAttr('required');


            $('#saveBtn').val("edit-data-profile");
            $('#dataProfileLabel').html("Edit");
            $('#dataProfileModal').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });


        })
    }
    $('select[id="category_template_parent_child"]').on('change', function(){

        var text = $('select[id=category_template_parent_child]').find(':selected').text();
        var replate = $('select[id=category_template_parent_child]').find(':selected').text()
        console.log(text,replate)
        $("#category_template_child_child").val($('select[id=category_template_parent_child]').find(':selected').text()+ ' - ');
    });
    function rebuildCateTempOption(cate_temp){
        var elementSelect = $("#category_template_parent_child");
        if(elementSelect.length <= 0){
            return false;
        }
        elementSelect.empty();
        elementSelect.append(
            $("<option value=''></option>").text('--None--')
        );
        for(var item of cate_temp){
            elementSelect.append(
                $("<option></option>", {
                    value : item.id
                }).text(item.category_template_name)
            );
        }
    }
</script>
@endsection






