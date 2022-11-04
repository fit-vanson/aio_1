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
                                status = '<span style="font-size: 100%" class="badge badge-success">Duyệt (Pass)</span>';
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

                    $.each([0,1,2,4], function ( d, j ) {
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
                            // case 3:
                            //     status = '<span style="font-size: 100%" class="badge badge-danger">Fail, Project loại khỏi dự án</span>';
                            //     break;
                            case 4:
                                status = '<span style="font-size: 100%" class="badge badge-success">Done, Kết thúc Project</span>';
                                break;
                        }
                        select.append( '<option value="'+d+'">'+status+'</option>' )
                    } );
                } );
            },

        });



        {{--$('.dropzone').each(function() {--}}
        {{--    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');--}}
        {{--    // var options = $(this).attr('id');--}}
        {{--    // var lang = $(this).data("lang");--}}
        {{--    // var lang_code = $(this).data("lang_code");--}}
        {{--    var maxfile = $(this).data("maxfile");--}}
        {{--    var extfile = $(this).data("ext");--}}
        {{--    var dropParamName = $(this).data("name");--}}

        {{--    --}}{{--const getMeSomeUrl = () => {--}}
        {{--    --}}{{--    return '{{route('design.create')}}?projectid=' + _id + '&projectname=' + _name+'&action=' + options + '&lang_code=' + lang_code + '&lang=' + lang--}}
        {{--    --}}{{--}--}}

        {{--    $(this).dropzone({--}}
        {{--        url: '#',--}}
        {{--        headers: {--}}
        {{--            'x-csrf-token': CSRF_TOKEN,--}}
        {{--        },--}}
        {{--        paramName: dropParamName,--}}
        {{--        maxFiles: maxfile,--}}
        {{--        maxFilesize: 20000,--}}
        {{--        parallelUploads: 20,--}}
        {{--        uploadMultiple: true,--}}
        {{--        acceptedFiles: extfile,--}}
        {{--        addRemoveLinks: true,--}}
        {{--        dictRemoveFile: 'Xoá',--}}
        {{--        autoProcessQueue: false,--}}
        {{--        init: function () {--}}
        {{--            var _this = this; // For the closure--}}
        {{--            this.on('success', function (file, response) {--}}
        {{--            });--}}
        {{--        },--}}
        {{--    });--}}

        {{--})--}}




        var myDropzoneOptions = {
            url: '#',
            autoProcessQueue: false,
            addRemoveLinks: true,
            dictRemoveFile: 'Xoá',
            parallelUploads: 20,
            uploadMultiple: true,
            thumbnailWidth: 120,
            thumbnailHeight: 120,
            thumbnailMethod:"crop",
            init: function (data) {
                this.on("processing", function(file) {
                    this.options.url = "/some-other-url";
                });
                // myDropzone.processQueue();
                // var mockFile = { name: "Filename 1.pdf", size: 12345678 };
                // this.files.push(mockFile);    // add to files array
                // this.emit("addedfile", mockFile);
                // this.emit("thumbnail", mockFile, '1111');
                // this.emit("complete", mockFile);
                // this.on("addedfile", function(file) { alert("Added file."); });
            }
        };


        var logoDropzone = new Dropzone('#logo', myDropzoneOptions);


        <?php
            foreach ($lags as $lang){
        ?>
                var {{$lang->lang_code}}_banner_Dropzone = new Dropzone('#banner_{{$lang->lang_code}}', myDropzoneOptions);
                {{--var banner_Dropzone = new Dropzone('#banner_{{$lang->lang_code}}', myDropzoneOptions);--}}
                var {{$lang->lang_code}}_video_Dropzone = new Dropzone('#video_{{$lang->lang_code}}', myDropzoneOptions);
                {{--var video_Dropzone = new Dropzone('#video_{{$lang->lang_code}}', myDropzoneOptions);--}}
                var {{$lang->lang_code}}_preview_Dropzone = new Dropzone('#preview_{{$lang->lang_code}}', myDropzoneOptions);
                {{--var preview_Dropzone = new Dropzone('#preview_{{$lang->lang_code}}', myDropzoneOptions);--}}

        <?php
            }
        ?>



        $('.modal').on('hidden.bs.modal', function (e) {
            <?php
                foreach ($lags as $lang){
            ?>
                    logoDropzone.removeAllFiles();
                    logoDropzone.removeAllFiles(true);

                    {{$lang->lang_code}}_banner_Dropzone.removeAllFiles();
                    {{$lang->lang_code}}_banner_Dropzone.removeAllFiles(true);

                    {{$lang->lang_code}}_video_Dropzone.removeAllFiles();
                    {{$lang->lang_code}}_video_Dropzone.removeAllFiles(true);

                    {{$lang->lang_code}}_preview_Dropzone.removeAllFiles();
                    {{$lang->lang_code}}_preview_Dropzone.removeAllFiles(true);
            <?php
                }
            ?>
        });


        $('#createNewDesign').click(function () {
            $('.project_select').show();
            $('#saveBtn_design').val("create-design");
            $('#design_id').val('');
            $('#designForm').trigger("reset");
            $('#modelHeading').html("Thêm mới");
            $('#ajaxModel').modal('show');
            $('#project_id').val('');
            $('#project_id').trigger('change.select2');

        });


        $('#project_id').on('select2:selecting', function(e) {
            var project_id = '';
            var _id = e.params.args.data.id;

            $.get('{{asset('design/edit')}}/'+_id,function (data) {
                var langs = data.lang;
                console.log(data)
                // $.each(langs, function ($k,$v){


                    // console.log($v)


                    var path = 'storage/projects/'+data.da.ma_da+'/'+data.projectname+'/en/';
                    // if($v.pivot.banner != 0){
                        var banner = { name: 'bn.jpg'};
                        en_banner_Dropzone.emit("addedfile", banner);
                        en_banner_Dropzone.emit("complete", banner);
                        en_banner_Dropzone.emit("success", banner);
                        en_banner_Dropzone.emit("thumbnail", banner,path + 'bn.jpg');
                        en_banner_Dropzone.files.push( banner );
                    // }
{{--                    if(v.pivot.video != 0){--}}
{{--                        var video = { name: 'video.mp4'};--}}
{{--                        video_Dropzone.emit("addedfile", video);--}}
{{--                        video_Dropzone.emit("complete", video);--}}
{{--                        video_Dropzone.emit("success", video);--}}
{{--                        video_Dropzone.emit("thumbnail", video,path + 'video.mp4');--}}
{{--                        video_Dropzone.files.push( video );--}}
{{--                    }--}}
{{--                    if(v.pivot.preview != 0){--}}
{{--                        console.log(v.pivot.preview)--}}
{{--                        var apkFile = { name: 'bn.jpg'};--}}
{{--                        // banner_Dropzone.emit("addedfile", apkFile);--}}
{{--                        // banner_Dropzone.emit("complete", apkFile);--}}
{{--                        // banner_Dropzone.emit("success", apkFile);--}}
{{--                        // banner_Dropzone.emit("thumbnail", apkFile,'img/apk.png');--}}
{{--                        // banner_Dropzone.files.push( apkFile );--}}
{{--                    }--}}
//                 })



{{--                <?php--}}
{{--                foreach ($lags as $lang){--}}
{{--//                    dd($lang);--}}
{{--                ?>--}}

{{--                var apkFile = { name: 'bn.jpg'};--}}
{{--                // logoDropzone.removeAllFiles();--}}
{{--                // logoDropzone.removeAllFiles(true);--}}

{{--                banner_{{$lang->lang_code}}_Dropzone.removeAllFiles();--}}
{{--                banner_{{$lang->lang_code}}_Dropzone.removeAllFiles(true);--}}

{{--                video_{{$lang->lang_code}}_Dropzone.removeAllFiles();--}}
{{--                video_{{$lang->lang_code}}_Dropzone.removeAllFiles(true);--}}

{{--                preview_{{$lang->lang_code}}_Dropzone.removeAllFiles();--}}
{{--                preview_{{$lang->lang_code}}_Dropzone.removeAllFiles(true);--}}
{{--                <?php--}}
{{--                }--}}
{{--                ?>--}}


            })




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

            $.get('{{asset('design/edit')}}/'+_id,function (data) {
                $('#ajaxModel').modal('show');
                $("#project_id").select2("trigger", "select", {
                    data: {
                        id: data.projectid,
                        text: data.projectname,
                    }
                });

            })
        });






    });


    </script>
@endsection


