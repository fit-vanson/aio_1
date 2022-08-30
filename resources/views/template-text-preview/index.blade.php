@extends('layouts.master')

@section('css')

<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />


<!-- Sweet-Alert  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>




@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Template Text Preview</h4>
</div>
@can('template-preview-index')
<div class="col-sm-6">
    <div class="float-right">
        <a class="btn btn-success" href="javascript:void(0)" id="createNewTemplateTextPreview"> Create New</a>
    </div>
</div>
@endcan
@include('modals.template-text-preview')
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
                            <th>Logo</th>
                            <th>Template Text Preview</th>
                            <th>File </th>
                            <th>Category </th>
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
                url: "{{ route('template-text-preview.getIndex') }}",
                type: "post"
            },
            columns: [
                {data: 'tt_logo'},
                {data: 'tt_name'},
                {data: 'tt_file'},
                {data: 'tt_category'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
            order:[1,'asc']
        });

        $('#createNewTemplateTextPreview').click(function () {
            $('#saveBtn').val("create-template-text-preview");
            $('#tt_id').val('');
            $("#avatar").attr("src","img/text_demo.png");

            $("#category_template_parent").val('');
            $("#category_template_parent").select2({});
            $("#category_template_child").val('');
            $("#category_template_child").select2({});
            $('#templateTextPreviewForm').trigger("reset");
            $('#modelHeading').html("Template Text Preview");
            $('#template_text_previewModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });
        $('#templateTextPreviewForm').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#templateTextPreviewForm")[0]);
            if($('#saveBtn').val() == 'create-template-text-preview'){
                $.ajax({
                    data: formData,
                    url: "{{ route('template-text-preview.create') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#templateTextPreviewForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#templateTextPreviewForm').trigger("reset");
                            $('#template_text_previewModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-template-text-preview'){
                $.ajax({
                    data: formData,
                    url: "{{ route('template-text-preview.update') }}",
                    type: "post",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#templateTextPreviewForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#templateTextPreviewForm').trigger("reset");
                            $('#template_text_previewModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
        });
        $(document).on('click','.deleteTemplateTextPreview', function (data){
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
                        url: "{{ asset("template-text-preview/delete") }}/" + template_id,
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
    function editTemplateTextPreview(id) {
        $.get('{{asset('template-text-preview/edit')}}/'+id,function (data) {
            $('#tt_id').val(data.id);
            $('#tt_name').val(data.tt_name);
            $("#avatar").attr("src",'file-manager/TemplateTextPreview/logo/'+data.tt_logo);
            $('#category_template_parent').val(data.category_template.category_template_parent);
            $("#category_template_parent").select2({});
            $.ajax({
                type:'get',
                url:'{{asset('category_template/get-cate-temp-parent')}}/'+data.category_template.category_template_parent,
            }).done(function(res){
                var elementSelect = $('#category_template_child');
                if(elementSelect.length <= 0){
                    return false;
                }
                elementSelect.empty();
                for(var item of res.cateParent){
                    elementSelect.append(
                        $("<option></option>", {
                            value : item.id
                        }).text(item.category_template_name)
                    );
                }
            });
            $('#category_template_child').val(data.tt_category);
            $("#category_template_child").select2({});

            $('#tt_text_1').val(data.tt_text_1);
            $('#tt_text_2').val(data.tt_text_2);
            $('#tt_text_3').val(data.tt_text_3);
            $('#tt_text_4').val(data.tt_text_4);
            $('#tt_text_5').val(data.tt_text_5);
            $('#tt_text_6').val(data.tt_text_6);
            $('#tt_text_7').val(data.tt_text_7);
            $('#tt_text_8').val(data.tt_text_8);

            $('#modelHeading').html("Edit");
            $('#saveBtn').val("edit-template-text-preview");
            $('#template_text_previewModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        })
    }

    $("#CategoryTemplateForm").submit(function (e) {
        e.preventDefault();
        let data = new FormData(document.getElementById('CategoryTemplateForm'));
        $.ajax({
            url:"{{route('category_template.create')}}",
            type: "post",
            data:data,
            processData: false,
            contentType: false,
            dataType: 'json',
            beForeSend : () => {
            },
            success:function (data) {
                console.log(data)
                if(data.errors){
                    for( var count=0 ; count <data.errors.length; count++){
                        $("#CategoryTemplateForm").notify(
                            data.errors[count],"error",
                            { position:"right" }
                        );
                    }
                }
                $.notify(data.success, "success");
                $('#CategoryTemplateForm').trigger("reset");
                $('#categoryTemplate').modal('hide');

                if(typeof data.cate_temp == 'undefined'){
                    data.cate_temp = {};
                }
                if(typeof rebuildCateTempOption == 'function'){
                    rebuildCateTempOption(data.cate_temp)
                }
            }
        });

    });

    $("#CategoryTemplateChildForm").submit(function (e) {
        e.preventDefault();
        let data = new FormData(document.getElementById('CategoryTemplateChildForm'));
        $.ajax({
            url:"{{route('category_template.create')}}",
            type: "post",
            data:data,
            processData: false,
            contentType: false,
            dataType: 'json',
            beForeSend : () => {
            },
            success:function (data) {
                console.log(data)
                if(data.errors){
                    for( var count=0 ; count <data.errors.length; count++){
                        $("#CategoryTemplateForm").notify(
                            data.errors[count],"error",
                            { position:"right" }
                        );
                    }
                }
                $.notify(data.success, "success");
                $('#CategoryTemplateChildForm').trigger("reset");
                $('#categoryTemplateChildModel').modal('hide');

                if(typeof data.allCateTempChild == 'undefined'){
                    data.allCateTempChild = {};
                }
                if(typeof rebuildCateTempOption == 'function'){
                    rebuildCateTempChildOption(data.allCateTempChild)
                }
            }
        });

    });

    function rebuildCateTempOption(cate_temp){
        var elementSelect = $("#category_template_parent");
        var elementSelect1 = $("#category_template_parent_child");
        if(elementSelect.length <= 0 || elementSelect1.length <= 0 ){
            return false;
        }
        elementSelect.empty();
        elementSelect1.empty();

        for(var item of cate_temp){
            elementSelect.append(
                $("<option></option>", {
                    value : item.id
                }).text(item.category_template_name)
            );
            elementSelect1.append(
                $("<option></option>", {
                    value : item.id
                }).text(item.category_template_name)
            );
        }
    }
    function rebuildCateTempChildOption(cate_temp){
        var elementSelect1 = $("#category_template_child");
        if( elementSelect1.length <= 0 ){
            return false;
        }
        elementSelect1.empty();
        for(var item of cate_temp){
            elementSelect1.append(
                $("<option></option>", {
                    value : item.id
                }).text(item.category_template_name)
            );
        }
    }

    $('#category_template_parent').on('change',function(e){
        var id=$(this).val();
        $.ajax({
            type:'get',
            url:'{{asset('category_template/get-cate-temp-parent')}}/'+id,
            // data:id,
        }).done(function(res){
            var elementSelect = $('#category_template_child');
            if(elementSelect.length <= 0){
                return false;
            }
            elementSelect.empty();
            for(var item of res.cateParent){
                elementSelect.append(
                    $("<option></option>", {
                        value : item.id
                    }).text(item.category_template_name)
                );
            }
        });
    });

    $('select[id="category_template_parent_child"]').on('change', function(){
        $("#category_template_child_child").val($('select[id=category_template_parent_child]').find(':selected').text()+ ' - ');
    });

    $('#categoryTemplateChild').click(function () {
        $("#category_template_parent_child").val($('#category_template_parent').val());
        $("#category_template_parent_child").select2({});
        $("#category_template_child_child").val($('select[id=category_template_parent_child]').find(':selected').text()+ ' - ');
        $('#modelHeading').html("Template Text Preview");
        $('#categoryTemplateChildModel').modal('show');
    });



</script>
@endsection






