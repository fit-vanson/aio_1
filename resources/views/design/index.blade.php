@extends('layouts.master')

@section('css')

<link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/lightgallery/css/lightgallery.css') }}" rel="stylesheet" type="text/css" />
<!-- Select2 Js  -->
<link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

@endsection

@section('content')
    @include('modals.design')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="designTable" class="table table-striped table-bordered dt-responsive data-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width: 20%;">Project Name</th>
                                    <th style="width: 30%;">Ngôn ngữ </th>
                                    <th style="width: 20%;">Status</th>
                                    <th style="width: 10%;">User </th>
                                    <th style="width: 20%;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')

<!-- Plugins js -->
<script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>
<script src="{{ URL::asset('/assets/js/customs.js') }}"></script>


<!-- Dropzone js -->
<script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>

<script src="plugins/select2/js/select2.min.js"></script>

<script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/lightgallery/js/lightgallery-all.js') }}"></script>


<script type="text/javascript">
    Dropzone.autoDiscover = false;
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#designTable').DataTable({

            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('design.getIndex') }}",
                type: 'post',
            },
            columns: [
                {data: 'projectid', name: 'projectid'},
                {data: 'lang_id', name: 'lang_id',orderable: false},
                // {data: 'preview', name: 'preview'},
                // {data: 'video', name: 'video'},
                {data: 'status_design', name: 'status_design',orderable: false},
                {data: 'user_design', name: 'user_design'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],

            columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        var status ='';
                        switch (data){
                            case 0:
                                status ='<span style="font-size: 100%" class="badge badge-secondary">Gửi chờ duyệt</span>' ;
                                break;
                            case 1:
                                status = '<span style="font-size: 100%" class="badge badge-info">Đã chỉnh sửa, cần duyệt lại</span>';
                                break;
                            case 2:
                                status = '<span style="font-size: 100%" class="badge badge-warning">Fail, cần chỉnh sửa</span>';
                                break;
                            case 3:
                                status = '<span style="font-size: 100%" class="badge badge-danger">Fail, Project loại khỏi dự án</span>';
                                break;
                            case 4:
                                status = '<span style="font-size: 100%" class="badge badge-success">Done, Kết thúc Project</span>';
                                break;
                        }
                        return status
                    },
                    targets: [2]
                }
            ],

            initComplete: function () {
                this.api().columns([2]).every( function () {
                    var column = this;
                    var select = $('<select class="form-control"><option value="">Trạng thái</option></select>')
                        .appendTo( $(column.header()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search( val ? val : '', true, false )
                                .draw();
                        } );

                    $.each([0,1,2,3,4], function ( d, j ) {
                        var status ='';
                        switch (d){
                            case 0:
                                status ='<span style="font-size: 100%" class="badge badge-secondary">Gửi chờ duyệt</span>' ;
                                break;
                            case 1:
                                status = '<span style="font-size: 100%" class="badge badge-info">Đã chỉnh sửa, cần duyệt lại</span>';
                                break;
                            case 2:
                                status = '<span style="font-size: 100%" class="badge badge-warning">Fail, cần chỉnh sửa</span>';
                                break;
                            case 3:
                                status = '<span style="font-size: 100%" class="badge badge-danger">Fail, Project loại khỏi dự án</span>';
                                break;
                            case 4:
                                status = '<span style="font-size: 100%" class="badge badge-success">Done, Kết thúc Project</span>';
                                break;
                        }
                        select.append( '<option value="'+d+'">'+status+'</option>' )
                    } );
                } );
            },

        });
        {{--var _id = null;--}}
        {{--var _name = null;--}}
        {{--$(document).on('change', '#project_id', function () {--}}
        {{--    var projectID = $(this).select2('data')[0].id;--}}
        {{--    var projectName = $(this).select2('data')[0].text;--}}
        {{--    $('#pro_id').val(projectID);--}}
        {{--    $('#pro_text').val(projectName);--}}
        {{--    _id = $('#pro_id').val();--}}
        {{--    _name = $('#pro_text').val();--}}
        {{--    $('div.dz-success').remove();--}}
        {{--});--}}
        $('.dropzone').each(function() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var options = $(this).attr('id');
            var lang = $(this).data("lang");
            var lang_code = $(this).data("lang_code");
            var maxfile = $(this).data("maxfile");
            var extfile = $(this).data("ext");
            var dropParamName = $(this).data("name");
            const getMeSomeUrl = () => {
                return '{{route('design.create')}}?projectid=' + _id + '&projectname=' + _name+'&action=' + options + '&lang_code=' + lang_code + '&lang=' + lang
            }
            $(this).dropzone({
                url: '#',
                headers: {
                    'x-csrf-token': CSRF_TOKEN,
                },
                paramName: dropParamName,
                maxFiles: maxfile,
                maxFilesize: 20000,
                parallelUploads: 20,
                uploadMultiple: true,
                acceptedFiles: extfile,
                addRemoveLinks: true,
                dictRemoveFile: 'Xoá',
                autoProcessQueue: false,

                init: function () {
                    var _this = this; // For the closure

                    this.on('success', function (file, response) {
                        // _this.removeFile(file);
                        // if (response.success) {
                        //     $.notify(_name,  "success");
                        //     table.draw();
                        // }
                        // if (response.errors) {
                        //     _this.removeFile(file);
                        //     $.notify(response.errors, "error");
                        // }
                    });
                },
            });
        })

        $('#createNewDesign').click(function () {
            $('.project_select').show();
            $('#saveBtn_design').val("create-design");
            $('#design_id').val('');
            $('#designForm').trigger("reset");
            $('#modelHeading').html("Thêm mới");
            $('#ajaxModel').modal('show');

            $("#project_id").select2({
                minimumInputLength: 2,
                ajax: {
                    url: '{{route('design.project_show')}}',
                    dataType: 'json',
                    type: "GET",
                    // quietMillis: 50,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    // slug: item.slug,
                                    id: item.id
                                }
                            })
                        };
                    },
                    // cache: true
                },
            });

            // myDropzone.destroy();
            // myDropzone = new Dropzone('.dropzone', myDropzoneOptions);
        });


        $('#designForm').on('submit', function (event) {
            event.preventDefault();
            var formData = new FormData($("#designForm")[0]);
            formData.append('logo', $("#logo")[0].dropzone.getAcceptedFiles()[0]);
            <?php
                foreach ($lags as $lang){
            ?>
                    formData.append('markets[{{$lang->id}}][banner]', $("#banner_{{$lang->lang_code}}")[0].dropzone.getAcceptedFiles()[0]);
                    formData.append('markets[{{$lang->id}}][video]', $("#video_{{$lang->lang_code}}")[0].dropzone.getAcceptedFiles()[0]);

                    $.each($("#preview_{{$lang->lang_code}}")[0].dropzone.getAcceptedFiles(),
                        function(a,b){
                    formData.append('markets[{{$lang->id}}][preview][]', $("#preview_{{$lang->lang_code}}")[0].dropzone.getAcceptedFiles()[a]);
                    });
            <?php
                }
            ?>

            if ($('#saveBtn_design').val() == 'create-design') {
                $.ajax({
                    data: formData,
                    url: "{{ route('design.create') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.errors) {
                            $("#designForm").notify(data.errors, "error");

                        }
                        if (data.success) {
                            $.notify(data.success, "success");
                            $("#designForm")[0].reset();
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if ($('#saveBtn_template').val() == 'edit-template') {
                $.ajax({
                    data: formData,
                    url: "{{ route('template.update') }}",
                    type: "post",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.errors) {
                            for (var count = 0; count < data.errors.length; count++) {
                                $("#templateForm").notify(
                                    data.errors[count], "error",
                                    {position: "right"}
                                );
                            }
                        }
                        if (data.success) {
                            $.notify(data.success, "success");
                            $('#templateForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });

            }
        });







        $('#designFormEdit').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#designFormEdit")[0]);
            // var row_id = $('#saveBtnEditDesign').val();
            $.ajax({
                data: formData,
                url: "{{ route('design.update') }}",
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    if(data.success){
                        // var status = data.data.status
                        // let html
                        //
                        // switch(status) {
                        //     case '0':
                        //         html ='<span style="font-size: 100%" class="badge badge-secondary">Gửi chờ duyệt</span>' ;
                        //         break;
                        //     case '1':
                        //         html = '<span style="font-size: 100%" class="badge badge-info">Đã chỉnh sửa, cần duyệt lại</span>';
                        //         break;
                        //     case '2':
                        //         html = '<span style="font-size: 100%" class="badge badge-warning">Fail, cần chỉnh sửa</span>';
                        //         break;
                        //     case '3':
                        //         html = '<span style="font-size: 100%" class="badge badge-danger">Fail, Project loại khỏi dự án</span>';
                        //         break;
                        //     case '4':
                        //         html = '<span style="font-size: 100%" class="badge badge-success">Done, Kết thúc Project</span>';
                        //         break;
                        // }
                        // var row = table.row().data()
                        // // var row_data = row.data();
                        // //
                        // // // row_data[1] = html;
                        // row.data().draw(false);

                        $.notify(data.success, "success");
                        $('#designFormEdit').trigger("reset");
                        $('#ajaxModelEdit').modal('hide');
                        table.draw();

                    }
                },
            });



        });
        $(document).on('click','.deleteProjectLang', function (data){
            var _id = $(this).data("id");
            var remove = $(this).parent().parent();
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
                        url: "{{ asset("design/delete") }}/" + _id,
                        success: function (data) {
                            if(data.success){
                                remove.slideUp(300,function() {
                                    remove.remove();
                                })
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                    swal("Đã xóa!", "Your imaginary file has been deleted.", "success");
                });
        });
        $(document).on('click','.editProjectLang', function (data){
            var _id = $(this).data("id");
            // var row_id = $(this).data("id_row");



            $.get('{{asset('design/edit')}}/'+_id,function (data) {
                // $('#saveBtnEditDesign').val(row_id);
                // $('#ajaxModelEdit').modal('show');
                $('#ajaxModel').modal('show');
                $('#pro_id').val(data.projectid);
                $('#pro_text').val(data.projectname);
                $('.project_select').hide();


                // $('.modal').on('hidden.bs.modal', function (e) {
                //     $('body').addClass('modal-open');
                // });
                // $('#design_id_edit').val(data.projectid);
                // $('#status').val(data.status_design);
                // $('#notes').val(data.notes_design);
            })
        });






    });


    </script>
@endsection


