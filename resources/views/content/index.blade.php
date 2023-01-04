{{--@extends('layouts.master')--}}

{{--@section('css')--}}

{{--<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />--}}
{{--<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />--}}

{{--<link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />--}}


{{--<link href="{{ URL::asset('assets/libs/lightgallery/css/lightgallery.css') }}" rel="stylesheet" type="text/css" />--}}


{{--<link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">--}}




{{--<!-- Responsive datatable examples -->--}}
{{--<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />--}}

{{--<!-- Sweet-Alert  -->--}}
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>--}}

{{--<!-- Select2 Js  -->--}}
{{--<link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />--}}

{{--<!-- Dropzone css -->--}}
{{--<link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />--}}



{{--@endsection--}}


{{--@section('breadcrumb')--}}
{{--<div class="col-sm-6">--}}
{{--    <h4 class="page-title">Content</h4>--}}
{{--</div>--}}
{{--<div class="col-sm-6">--}}
{{--    <div class="float-right">--}}
{{--        <a class="btn btn-success" href="javascript:void(0)" id="createNewDesign">Create Or Update</a>--}}
{{--    </div>--}}
{{--</div>--}}
{{--@include('modals.content')--}}
{{--@endsection--}}
{{--@section('content')--}}

{{--    <div class="row">--}}
{{--        <div class="col-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>Project Name</th>--}}
{{--                            <th>Title </th>--}}
{{--                            <th>Summary</th>--}}
{{--                            <th style="width: 40%">Description </th>--}}
{{--                            <th>Action</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div> <!-- end col -->--}}
{{--    </div> <!-- end row -->--}}
{{--@endsection--}}
{{--@section('script')--}}

{{--<!-- Required datatable js -->--}}
{{--<script src="plugins/datatables/jquery.dataTables.min.js"></script>--}}
{{--<script src="plugins/datatables/dataTables.bootstrap4.min.js"></script>--}}
{{--<!-- Buttons examples -->--}}
{{--<script src="plugins/datatables/dataTables.buttons.min.js"></script>--}}
{{--<script src="plugins/datatables/buttons.bootstrap4.min.js"></script>--}}
{{--<script src="plugins/datatables/jszip.min.js"></script>--}}
{{--<script src="plugins/datatables/pdfmake.min.js"></script>--}}
{{--<script src="plugins/datatables/vfs_fonts.js"></script>--}}
{{--<script src="plugins/datatables/buttons.html5.min.js"></script>--}}
{{--<script src="plugins/datatables/buttons.print.min.js"></script>--}}
{{--<script src="plugins/datatables/buttons.colVis.min.js"></script>--}}
{{--<!-- Responsive examples -->--}}
{{--<script src="plugins/datatables/dataTables.responsive.min.js"></script>--}}
{{--<script src="plugins/datatables/responsive.bootstrap4.min.js"></script>--}}

{{--<!-- Datatable init js -->--}}
{{--<script src="assets/pages/datatables.init.js"></script>--}}

{{--<!-- Plugins js -->--}}
{{--<script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>--}}

{{--<script src="plugins/select2/js/select2.min.js"></script>--}}

{{--<script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('/assets/libs/lightgallery/js/lightgallery-all.js') }}"></script>--}}

{{--<!--Summernote js-->--}}
{{--<script src="plugins/tinymce/tinymce.min.js"></script>--}}
{{--<script src="plugins/summernote/summernote-bs4.min.js"></script>--}}
{{--<script src="assets/pages/form-editors.int.js"></script>--}}
{{--<script type="text/javascript">--}}

{{--    $(function () {--}}
{{--        $.ajaxSetup({--}}
{{--            headers: {--}}
{{--                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--            }--}}
{{--        });--}}

{{--        var table = $('.data-table').DataTable({--}}

{{--            processing: true,--}}
{{--            serverSide: true,--}}
{{--            ajax: {--}}
{{--                url: "{{ route('content.getIndex') }}",--}}
{{--                type: 'post',--}}
{{--            },--}}
{{--            columns: [--}}
{{--                {data: 'projectid', name: 'projectid'},--}}
{{--                {data: 'title', name: 'title'},--}}
{{--                {data: 'summary', name: 'summary'},--}}
{{--                {data: 'description', name: 'description'},--}}
{{--                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},--}}
{{--            ],--}}

{{--        });--}}
{{--        var _id = null;--}}
{{--        $(document).on('change', '#project_id', function () {--}}
{{--            var projectID = $(this).select2('data')[0].id;--}}
{{--            $('#pro_id').val(projectID);--}}

{{--            _id = $('#pro_id').val();--}}

{{--            $.get('{{asset('content/edit')}}/'+_id,function (data) {--}}
{{--                var langs = data.lang;--}}

{{--                $.each( langs, function( key, value ) {--}}
{{--                    $('#content_summary_'+value.id).val(value.pivot.summary);--}}
{{--                    $('#content_title_'+value.id).val(value.pivot.title);--}}
{{--                    // $('#content_description_'+value.id).val(value.pivot.description);--}}
{{--                    if(value.pivot.description){--}}
{{--                        tinymce.get('content_description_'+value.id).setContent(value.pivot.description);--}}
{{--                    }else {--}}
{{--                        tinymce.get('content_description_'+value.id).setContent('');--}}
{{--                    }--}}
{{--                })--}}
{{--            })--}}
{{--        });--}}


{{--        $('#createNewDesign').click(function () {--}}
{{--            $('.project_select').show();--}}
{{--            $('#saveBtn').val("create-design");--}}
{{--            $('#project_id_content').val('');--}}
{{--            $('#contentForm').trigger("reset");--}}
{{--            $('#modelHeadingContent').html("Thêm mới ");--}}
{{--            $('#ajaxModelContent').modal('show');--}}

{{--            $("#project_id").select2({--}}
{{--                minimumInputLength: 2,--}}
{{--                ajax: {--}}
{{--                    url: '{{route('design.project_show')}}',--}}
{{--                    dataType: 'json',--}}
{{--                    type: "GET",--}}
{{--                    // quietMillis: 50,--}}
{{--                    data: function(params) {--}}
{{--                        return {--}}
{{--                            q: params.term, // search term--}}
{{--                            page: params.page--}}
{{--                        };--}}
{{--                    },--}}
{{--                    processResults: function(data) {--}}
{{--                        return {--}}
{{--                            results: $.map(data, function (item) {--}}
{{--                                return {--}}
{{--                                    text: item.name,--}}
{{--                                    // slug: item.slug,--}}
{{--                                    id: item.id--}}
{{--                                }--}}
{{--                            })--}}
{{--                        };--}}
{{--                    },--}}
{{--                    // cache: true--}}
{{--                },--}}
{{--            });--}}
{{--        });--}}

{{--        $('#contentForm').on('submit',function (event){--}}
{{--            event.preventDefault();--}}
{{--            var formData = new FormData($("#contentForm")[0]);--}}
{{--            // var row_id = $('#saveBtnEditDesign').val();--}}
{{--            $.ajax({--}}
{{--                data: formData,--}}
{{--                url: "{{ route('content.create') }}",--}}
{{--                type: "POST",--}}
{{--                dataType: 'json',--}}
{{--                processData: false,--}}
{{--                contentType: false,--}}
{{--                success: function (data) {--}}
{{--                    if(data.success){--}}
{{--                        $.notify(data.success, "success");--}}
{{--                        table.draw();--}}
{{--                    }--}}
{{--                    if(data.errors){--}}
{{--                        $.notify(data.errors, "error");--}}
{{--                    }--}}
{{--                },--}}
{{--            });--}}
{{--        });--}}


{{--        $(document).on('click','.deleteProjectLang', function (data){--}}
{{--            var _id = $(this).data("id");--}}
{{--            var remove = $(this).parent().parent();--}}
{{--            swal({--}}
{{--                    title: "Bạn có chắc muốn xóa?",--}}
{{--                    text: "Your will not be able to recover this imaginary file!",--}}
{{--                    type: "warning",--}}
{{--                    showCancelButton: true,--}}
{{--                    confirmButtonClass: "btn-danger",--}}
{{--                    confirmButtonText: "Xác nhận xóa!",--}}
{{--                    closeOnConfirm: false--}}
{{--                },--}}
{{--                function(){--}}
{{--                    $.ajax({--}}
{{--                        type: "get",--}}
{{--                        url: "{{ asset("design/delete") }}/" + _id,--}}
{{--                        success: function (data) {--}}
{{--                            if(data.success){--}}
{{--                                remove.slideUp(300,function() {--}}
{{--                                    remove.remove();--}}
{{--                                })--}}
{{--                            }--}}
{{--                        },--}}
{{--                        error: function (data) {--}}
{{--                            console.log('Error:', data);--}}
{{--                        }--}}
{{--                    });--}}
{{--                    swal("Đã xóa!", "Your imaginary file has been deleted.", "success");--}}
{{--                });--}}
{{--        });--}}


{{--        $(document).on('click','.editContent', function (data){--}}
{{--            var _id = $(this).data("id");--}}
{{--            $('#contentForm').trigger("reset");--}}
{{--            $('.project_select').hide();--}}
{{--            $.get('{{asset('content/edit')}}/'+_id,function (data) {--}}
{{--                $('#ajaxModelContent').modal('show');--}}

{{--                $('#modelHeadingContent').html("Chỉnh sửa "+data.projectname);--}}
{{--                $('.modal').on('hidden.bs.modal', function (e) {--}}
{{--                    $('body').addClass('modal-open');--}}
{{--                });--}}

{{--                $('#pro_id').val(data.projectid);--}}


{{--                var langs = data.lang;--}}

{{--                $.each( langs, function( key, value ) {--}}
{{--                    $('#content_summary_'+value.id).val(value.pivot.summary);--}}
{{--                    $('#content_title_'+value.id).val(value.pivot.title);--}}
{{--                    if(value.pivot.description){--}}
{{--                        tinymce.get('content_description_'+value.id).setContent(value.pivot.description);--}}
{{--                    }else {--}}
{{--                        tinymce.get('content_description_'+value.id).setContent('');--}}
{{--                    }--}}
{{--                })--}}
{{--            })--}}
{{--        });--}}
{{--    });--}}


{{--    </script>--}}
{{--@endsection--}}




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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />






@endsection

@section('content')
    @include('modals.content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="contentTable" class="table table-striped table-bordered dt-responsive data-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width: 20%">Project Name</th>
                                    <th style="width: 60%">Title </th>
                                    <th style="width: 20%">Action</th>
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


    {{--<!-- Dropzone js -->--}}
    <script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
            $("#project_id").select2({
                placeholder: "Select a customer",
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
                initSelection : function (element, callback) {
                    var data = [];
                    $(element.val()).each(function () {
                        data.push({id: this, text: this});
                    });
                    callback(data);
                }
            });

            var table = $('#contentTable').DataTable({

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('content.getIndex') }}",
                    type: 'post',
                },
                columns: [
                    {data: 'projectid', name: 'projectid'},
                    {data: 'title', name: 'title'},
                    // {data: 'summary', name: 'summary'},
                    // {data: 'description', name: 'description'},
                    {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
                ],




                drawCallback: function (settings) {
                    $('[data-toggle="popover"]').popover({
                        trigger: "hover",
                        container: "body",
                        html: true,
                        // placement: "top"
                    });
                },

            });

            $('#createNewContent').click(function () {
                // $('#saveBtn').val("create-content");
                $('#design_id').val('');
                $('#designForm').trigger("reset");
                $('#modelHeadingContent').html("Cập nhật");
                $('#ajaxModelContent').modal('show');
                $('#pro_id').val('');
                $('#pro_id').trigger('change.select2');
            });


            $('#project_id').on('select2:selecting', function(e) {
                var _id = e.params.args.data.id;
                $('#pro_id').val(_id);
                $.get('{{asset('content/edit')}}/'+_id,function (data) {
                    $('#contentForm').trigger("reset");
                    var langs = data.lang;


                    $.each( langs, function( key, value ) {


                            $('#content_keywords_'+value.id).val(value.pivot.project_keywords);
                            $('#content_summary_'+value.id).val(value.pivot.summary);
                            $('#content_title_'+value.id).val(value.pivot.title);
                            $('#content_description_'+value.id).val(value.pivot.description);


                        $.each(JSON.parse(atob(value.pivot.adss)), function(i, item) {
                            $.each(item, function(k, v) {
                                $('#content_'+i+'_adss_'+value.id+'_'+k).val(v);
                                $('#content_'+i+'_adss_'+value.id+'_'+k).on('change keyup', function () {
                                    $('#count_'+i+'_adss_'+value.id+'_'+k).html(this.value.length)
                                });
                            })
                        });

                        $('#content_title_'+value.id).on('change keyup', function () {
                            $('#count_title_app_'+value.lang_code).html(this.value.length)
                        });
                        $('#content_summary_'+value.id).on('change keyup', function () {
                            $('#count_summary_'+value.lang_code).html(this.value.length)
                        });

                    })
                })
            });


            const serialize_form = form => JSON.stringify(
                Array.from(new FormData(form).entries())
                    .reduce((m, [ key, value ]) => Object.assign(m, { [key]: value }), {})
            );

            $('#contentForm').on('submit', function (event) {
                event.preventDefault();
                const formData = new FormData($("#contentForm")[0]);
                // var formData = ($("#contentForm").serializeArray());

                $.ajax({
                    data: formData,
                    url: "{{ route('content.create') }}",
                    type: "POST",
                    // dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.errors) {
                            $("#contentForm").notify(data.errors, "error");

                        }
                        if (data.success) {
                            $.notify(data.success, "success");
                            $("#contentForm")[0].reset();
                            $('#ajaxModelContent').modal('hide');
                            table.draw();
                        }
                    },
                });
            });

            {{--$('#designFormEdit').on('submit',function (event){--}}
            {{--    event.preventDefault();--}}
            {{--    var formData = new FormData($("#designFormEdit")[0]);--}}
            {{--    // var row_id = $('#saveBtnEditDesign').val();--}}
            {{--    $.ajax({--}}
            {{--        data: formData,--}}
            {{--        url: "{{ route('design.update') }}",--}}
            {{--        type: "POST",--}}
            {{--        dataType: 'json',--}}
            {{--        processData: false,--}}
            {{--        contentType: false,--}}
            {{--        success: function (data) {--}}
            {{--            if(data.success){--}}
            {{--                // var status = data.data.status--}}
            {{--                // let html--}}
            {{--                //--}}
            {{--                // switch(status) {--}}
            {{--                //     case '0':--}}
            {{--                //         html ='<span style="font-size: 100%" class="badge badge-secondary">Gửi chờ duyệt</span>' ;--}}
            {{--                //         break;--}}
            {{--                //     case '1':--}}
            {{--                //         html = '<span style="font-size: 100%" class="badge badge-info">Đã chỉnh sửa, cần duyệt lại</span>';--}}
            {{--                //         break;--}}
            {{--                //     case '2':--}}
            {{--                //         html = '<span style="font-size: 100%" class="badge badge-warning">Fail, cần chỉnh sửa</span>';--}}
            {{--                //         break;--}}
            {{--                //     case '3':--}}
            {{--                //         html = '<span style="font-size: 100%" class="badge badge-danger">Fail, Project loại khỏi dự án</span>';--}}
            {{--                //         break;--}}
            {{--                //     case '4':--}}
            {{--                //         html = '<span style="font-size: 100%" class="badge badge-success">Done, Kết thúc Project</span>';--}}
            {{--                //         break;--}}
            {{--                // }--}}
            {{--                // var row = table.row().data()--}}
            {{--                // // var row_data = row.data();--}}
            {{--                // //--}}
            {{--                // // // row_data[1] = html;--}}
            {{--                // row.data().draw(false);--}}

            {{--                $.notify(data.success, "success");--}}
            {{--                $('#designFormEdit').trigger("reset");--}}
            {{--                $('#ajaxModelEdit').modal('hide');--}}
            {{--                table.draw();--}}

            {{--            }--}}
            {{--        },--}}
            {{--    });--}}



            {{--});--}}
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
            $(document).on('click','.editContent', function (data){
                var _id = $(this).data("id");
                var _name= $(this).data("name");
                $('#ajaxModelContent').modal('show');

                $("#project_id").select2("trigger", "select", {
                    data: {
                        id: _id,
                        text: _name,
                    }
                });
            });
        });


    </script>
@endsection


