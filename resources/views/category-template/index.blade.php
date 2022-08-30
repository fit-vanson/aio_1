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
    <h4 class="page-title">Category Template</h4>
</div>
@can('template-preview-index')
<div class="col-sm-6">
    <div class="float-right">
        <a class="btn btn-success" href="javascript:void(0)" id="createNewCategotyTemp"> Create New</a>
    </div>
</div>
@endcan
@include('modals.categoryTemplate')
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
                            <th style="width: 15%">Template Preview</th>
                            <th style="width: 15%">Parent</th>
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
        var groupColumn = 1;
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            displayLength: 50,

            ajax: {
                url: "{{ route('category_template.getIndex') }}",
                type: "post"
            },
            columns: [

                {data: 'category_template_name'},
                {data: 'category_template_parent'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                        );
                        last = group;
                    }
                } );
            }

        });

        $('#createNewCategotyTemp').click(function () {
            $('#saveBtn').val("create-category-template");
            $('#category_template_child_id').val('');
            $("#category_template_parent_child").val('');
            $("#category_template_parent_child").select2({});
            $('#saveBtnChild').val("add-category-template");
            $('#CategoryTemplateChildForm').trigger("reset");
            $('#childModalLabel').html("Thêm mới Category");
            $('#categoryTemplateChildModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });
        $('#CategoryTemplateForm').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#CategoryTemplateForm")[0]);
            if($('#saveBtn').val() == 'create-template-preview'){
                $.ajax({
                    data: formData,
                    url: "{{ route('template-preview.create') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#templatePreviewForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#templatePreviewForm').trigger("reset");
                            $('#template_previewModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-category-template'){
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
                                $("#CategoryTemplateForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#CategoryTemplateForm').trigger("reset");
                            $('#categoryTemplate').modal('hide');
                            $('#categoryTemplateChildModel').modal('hide');
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
        $(document).on('click','.deleteCategoryTemplate', function (data){
            var template_id = $(this).data("id");
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
                        url: "{{ asset("category_template/delete") }}/" + template_id,
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
    function editCategoryTemplate(id) {
        $.get('{{asset('category_template/edit')}}/'+id,function (data) {

            if (data[0].category_template_parent == 0){

                $('#category_template_id').val(data[0].id);
                $('#category_template_name').val(data[0].category_template_name);

                $('#saveBtn').val("edit-category-template");
                $('#exampleModalLabel').html("Edit");
                $('#categoryTemplate').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }else {
                var parentSelect = $('#category_template_parent_child');
                if(parentSelect.length <= 0){
                    return false;
                }
                parentSelect.empty();
                for(var item of data[1]){
                    parentSelect.append(
                        $("<option></option>", {
                            value : item.id
                        }).text(item.category_template_name)
                    );
                }
                $("#category_template_parent_child").select2({});
                $("#category_template_child_id").val(data[0].id);
                $("#category_template_child_child").val(data[0].category_template_name);

                $('#saveBtnChild').val("edit-category-template");
                $('#childModalLabel').html("Edit Child");
                $('#categoryTemplateChildModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }

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






