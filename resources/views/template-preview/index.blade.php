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
    <h4 class="page-title">Template Preview</h4>
</div>
@can('template-preview-index')
<div class="col-sm-6">
    <div class="float-right">
        <a class="btn btn-success" href="javascript:void(0)" id="createNewTemplatePreview"> Create New</a>
    </div>
</div>
@endcan
@include('modals.template-preview')
@include('modals.buildpreview')
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="button-items status_app_button">
                        <button type="button" class="btn btn-primary waves-effect waves-light" id="build_preview">Build Preview</button>
                    </div>
                </div>
                <div class="card-body">
{{--                    <table class="table table-bordered data-table">--}}
                     <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Logo</th>
                            <th style="width: 15%">Template Preview</th>
                            <th style="width: 15%">File</th>
                            <th>Category Template</th>
                            <th style="width: 5%">Number</th>
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
                url: "{{ route('template-preview.getIndex') }}",
                type: "post"
            },
            columns: [
                {data: 'tp_logo'},
                {data: 'tp_name'},
                {data: 'tp_sc'},
                {data: 'tp_category'},
                {data: 'sum_script'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
            order:[1,'asc']

        });

        $('#createNewTemplatePreview').click(function () {
            $('#saveBtn').val("create-template-preview");
            $('#tp_id').val('');
            $("#category_template").val('');
            $("#tp_sc").val('');
            $("#tp_data").val('');
            $("#category_template").select2({});
            $("#tp_data").select2({});
            $("#avatar").attr("src","img/frame_demo.png");
            // $('#templatePreviewForm').trigger("reset");
            $('#modelHeading').html("Template Preview");
            $('#template_previewModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });
        $('#templatePreviewForm').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#templatePreviewForm")[0]);
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
            if($('#saveBtn').val() == 'edit-template-preview'){
                $.ajax({
                    data: formData,
                    url: "{{ route('template-preview.update') }}",
                    type: "post",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        console.log(data)
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
        });
        $(document).on('click','.deleteTemplatePreview', function (data){
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
                        url: "{{ asset("template-preview/delete") }}/" + template_id,
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
    $('#build_preview').click(function () {
        $('#template_frame').val('');

        $("#category_template_frame").val('');
        $("#category_template_text").val('');
        $("#template_frame_preview").val('');
        $("#preview_frame").attr('src','');
        $("#preview_text").attr('src','');
        $("#preview_out").attr('src','');

        $('.template_availavble').show();

        $('.buildPreviewTemplateFrame').show();
        $('.buildPreviewTemplateFrame :input').removeClass("disabled").prop("disabled", false);

        $('.template_custom').hide();
        $('.template_custom :input').removeClass("disabled").prop("disabled", true);

        $('.template_availavble').show();
        $('.template_availavble :input').removeClass("disabled").prop("disabled", false);

        $('.data_custom').hide();
        $('.data_custom :input').removeClass("disabled").prop("disabled", true);

        $("#category_template_frame").select2({});
        $("#category_template_text").select2({});
        $("#template_frame_preview").select2({});
        $("#template_text_preview").select2({});

        $("#font_size").select2({});
        $("#font_size_small").select2({});
        $("#category_child_template_text").select2({});
        $('#saveBtn').val("create-preview");
        $('#buildpreviewForm').trigger("reset");
        $('#buildpreviewModalLabel').html("Template Preview");
        $('#buildpreviewModal').modal('show');
        $('.modal').on('hidden.bs.modal', function (e) {
            $('body').addClass('modal-open');
        });
    });

    $('input[type=radio][name=template123]').change(function() {
        if (this.value == 'template_availavble') {
            $('.template_availavble').show();
            $('.template_custom').hide();
            $('.template_custom :input').removeClass("disabled").prop("disabled", true);
            $('.template_availavble :input').removeClass("disabled").prop("disabled", false);


        }
        else if (this.value == 'template_custom') {
            $('.template_custom').show();
            $('.template_availavble').hide();
            $('.template_availavble :input').removeClass("disabled").prop("disabled", true);
            $('.template_custom :input').removeClass("disabled").prop("disabled", false);


        }
    });

    $('input[type=radio][name=data123]').change(function() {
        if (this.value == 'data_availavble') {
            $('.data_custom').hide();
            $('.data_custom :input').removeClass("disabled").prop("disabled", true);

        }
        else if (this.value == 'data_custom') {
            $('.data_custom').show();
            $('.data_custom :input').removeClass("disabled").prop("disabled", false);

        }
    });
    $('#category_template_frame').on('change',function(e){
        var id=$(this).val();
        $.ajax({
            type:'get',
            url:'{{asset('category_template_frame/get-temp-preview')}}/'+id,
            // data:id,
        }).done(function(res){
            var elementSelect = $('#template_frame_preview');
            if(elementSelect.length <= 0){
                return false;
            }
            elementSelect.empty();
            elementSelect.append(
                $("<option value='0'></option>").text('---Random---')
            );
            for(var item of res.tempPreview){
                elementSelect.append(
                    $("<option></option>", {
                        value : item.id
                    }).text(item.tp_name)
                );
            }
        });
    });
    $('#template_frame_preview').on('change',function(e){
        $('#color_frame').show();
        var id=$(this).val();
        var html = '<div class="row">';
        $.ajax({
            type:'get',
            url:'{{asset('category_template_frame/get-temp-preview')}}/'+id,
            // data:id,
        }).done(function(res){
            if(res.frame.tp_blue){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_blue" value="Blue" required> '+
                    '<label class="form-check-label" for="tp_blue"  >Blue</label></div>'
            }else {
                html += '';
            }
            if(res.frame.tp_black){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_black" value="Black" required> '+
                    '<label class="form-check-label" for="tp_black">Black</label></div>'
            }else {
                html += '';
            }
            if(res.frame.tp_while){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_while" value="While" required> '+
                    '<label class="form-check-label" for="tp_while">While</label></div>'
            }else {
                html += '';
            }
            if(res.frame.tp_yellow){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_yellow" value="Yellow" required >'+
                    '<label class="form-check-label" for="tp_yellow">Yellow</label></div>'
            }else {
                html += '';
            }
            if(res.frame.tp_pink){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_pink" value="Pink" required>'+
                    '<label class="form-check-label" for="tp_pink">Pink</label></div>'
            }else {
                html += '';
            }
            html += "</div>"


            $('#color_frame').html(html)

            $('#preview_frame').attr('src',('file-manager/TemplatePreview/logo/')+res.frame.tp_logo)
        });
    });

    $('#category_template_text').on('change',function(e){
        var id=$(this).val();
        $.ajax({
            type:'get',
            url:'{{asset('category_template/get-cate-temp-parent')}}/'+id,

        }).done(function(res){
            var elementSelect = $('#category_child_template_text');
            if(elementSelect.length <= 0){
                return false;
            }
            elementSelect.empty();
            elementSelect.append(
                $("<option ></option>").text('---Vui lòng chọn---')
            );
            for(var item of res.cateParent){
                elementSelect.append(
                    $("<option></option>", {
                        value : item.id
                    }).text(item.category_template_name)
                );
            }
        });
    });

    $('#category_child_template_text').on('change',function(e){
        var id=$(this).val();
        $.ajax({
            type:'get',
            url:'{{asset('category_template/get-cate-temp-parent')}}/'+id,

        }).done(function(res){
            console.log(res)
            var elementSelect = $('#template_text_preview');
            if(elementSelect.length <= 0){
                return false;
            }
            elementSelect.empty();
            elementSelect.append(
                $("<option value='0'></option>").text('---Random---')
            );
            for(var item of res.textPreview){
                elementSelect.append(
                    $("<option></option>", {
                        value : item.id
                    }).text(item.tt_name)
                );
            }
        });
    });

    $('#template_text_preview').on('change',function(e){
        var id=$(this).val();
        $.ajax({
            type:'get',
            url:'{{asset('category_template/get-cate-temp-parent')}}/'+id,

        }).done(function(res){
            $('#preview_text').attr('src',('file-manager/TemplateTextPreview/logo/')+res.text.tt_logo)
        });
    });

    $('#buildpreviewForm').on('submit',function (event){
        event.preventDefault();
        var formData = new FormData($("#buildpreviewForm")[0]);

        $.ajax({
            data: formData,
            url: "{{ route('build_preview.create') }}",
            type: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (data) {
                if(data.errors){
                    for( var count=0 ; count <data.errors.length; count++){
                        $("#buildpreviewForm").notify(
                            data.errors[count],"error",
                            { position:"right" }
                        );
                    }
                }
                if(data.success){
                    console.log(data)
                    $.notify(data.success, "success");
                    $('#preview_out').attr('src','file-manager/BuildTemplate/'+data.out)
                }
            },
        });

    });

</script>
<script>
    function editTemplatePreview(id) {
        $.get('{{asset('template-preview/edit')}}/'+id,function (data) {

            $('#tp_id').val(data.id);
            $("#avatar").attr("src",'file-manager/TemplatePreview/logo/'+data.tp_logo);
            $('#tp_name').val(data.tp_name);
            $('#tp_script').val(data.tp_script);
            $('#tp_script_1').val(data.tp_script_1);
            $('#tp_script_2').val(data.tp_script_2);
            $('#tp_script_3').val(data.tp_script_3);
            $('#tp_script_4').val(data.tp_script_4);
            $('#tp_script_5').val(data.tp_script_5);
            $('#tp_script_6').val(data.tp_script_6);
            $('#tp_script_7').val(data.tp_script_7);
            $('#tp_script_8').val(data.tp_script_8);

            $('#category_template').val(data.tp_category);
            $("#category_template").select2({});

            $('#tp_data').val(data.tp_data);
            $("#tp_data").select2({});


            if(data.tp_black !=0){
                $("#tp_black").prop('checked', true);
            }else{
                $("#tp_black").prop('checked', false);
            }

            if(data.tp_blue !=0){
                $("#tp_blue").prop('checked', true);
            }else{
                $("#tp_blue").prop('checked', false);
            }

            if(data.tp_while !=0){
                $("#tp_while").prop('checked', true);
            }else{
                $("#tp_while").prop('checked', false);
            }

            if(data.tp_pink !=0){
                $("#tp_pink").prop('checked', true);
            }else{
                $("#tp_pink").prop('checked', false);
            }

            if(data.tp_yellow !=0){
                $("#tp_yellow").prop('checked', true);
            }else{
                $("#tp_yellow").prop('checked', false);
            }

            $('#modelHeading').html("Edit");
            $('#saveBtn').val("edit-template-preview");
            $('#template_previewModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        })
    }

    function buildTemplatePreview(id) {
        $.get('{{asset('template-preview/edit')}}/'+id,function (data) {
            var html = '<div class="row">';
            $('.buildPreviewTemplateFrame').hide();
            $('.buildPreviewTemplateFrame :input').removeClass("disabled").prop("disabled", true);
            $('.template_custom').hide();
            $('.template_custom :input').removeClass("disabled").prop("disabled", true);

            $('.data_custom').hide();
            $('.data_custom :input').removeClass("disabled").prop("disabled", true);

            $('.template_availavble').show();
            $('.template_availavble :input').removeClass("disabled").prop("disabled", false);


            if(data.tp_blue){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_blue" value="Blue" required> '+
                    '<label class="form-check-label" for="tp_blue"  >Blue</label></div>'
            }else {
                html += '';
            }
            if(data.tp_black){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_black" value="Black" required> '+
                    '<label class="form-check-label" for="tp_black">Black</label></div>'
            }else {
                html += '';
            }
            if(data.tp_while){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_while" value="While" required> '+
                    '<label class="form-check-label" for="tp_while">While</label></div>'
            }else {
                html += '';
            }
            if(data.tp_yellow){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_yellow" value="Yellow" required >'+
                    '<label class="form-check-label" for="tp_yellow">Yellow</label></div>'
            }else {
                html += '';
            }
            if(data.tp_pink){
                html += '<div class="col-lg-2"><input class="form-check-input" type="radio" name="color_text" id="tp_pink" value="Pink" required>'+
                    '<label class="form-check-label" for="tp_pink">Pink</label></div>'
            }else {
                html += '';
            }
            html += "</div>"
            $('#color_frame').html(html)
            console.log(data)

            $('#template_frame').val(data.id);

            $("#category_template_text").select2({});
            $("#category_child_template_text").select2({});
            $("#template_text_preview").select2({});

            $('#modelHeading').html("Edit");
            $('#saveBtn').val("edit-template-preview");
            $('#buildpreviewModal').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        })
    }

    $("#CategoryTemplateFrameForm").submit(function (e) {
        e.preventDefault();
        let data = new FormData(document.getElementById('CategoryTemplateFrameForm'));
        $.ajax({
            url:"{{route('category_template_frame.create')}}",
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
                        $("#CategoryTemplateFrameForm").notify(
                            data.errors[count],"error",
                            { position:"right" }
                        );
                    }
                }
                $.notify(data.success, "success");
                $('#CategoryTemplateFrameForm').trigger("reset");
                $('#categoryTemplateFrame').modal('hide');

                if(typeof data.cate_temp == 'undefined'){
                    data.cate_temp = {};
                }
                if(typeof rebuildCateTempOption == 'function'){
                    rebuildCateTempOption(data.cate_temp)
                }
            }
        });

    });
    function rebuildCateTempOption(cate_temp){
        var elementSelect = $("#category_template");

        if(elementSelect.length <= 0){
            return false;
        }
        elementSelect.empty();
        for(var item of cate_temp){
            elementSelect.append(
                $("<option></option>", {
                    value : item.id
                }).text(item.category_template_frames_name)
            );
        }
    }
</script>
@endsection






