@extends('layouts.master')

@section('css')

    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>


    <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        /*table {*/
        /*    width: 100%;*/
        /*    border-collapse: collapse;*/
        /*    table-layout: fixed;*/
        /*}*/

        .cell-breakWord {
            width: 20%;
            word-wrap: break-word;
        }
    </style>


@endsection

@section('content')
    @include('modals.template')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="TemplateTable" class="table table-striped table-bordered dt-responsive data-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="display: none;">ID</th>
                                    <th style="width: 10%">Logo</th>
                                    <th style="width: 30%">Tên Template</th>
                                    <th style="width: 20%">Phân loại</th>
                                    <th style="width: 25%">Thông tin Template</th>
                                    <th style="width: 5%">Type</th>
                                    <th style="width: 10%">Action</th>
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



<script type="text/javascript">

    Dropzone.autoDiscover = false;


    $(function () {
        $('.table-responsive').responsiveTable({
            // addDisplayAllBtn: 'btn btn-secondary'
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#TemplateTable').DataTable({
            processing: true,
            serverSide: true,
            displayLength: 50,
            lengthMenu: [25, 50, 100, 200, 500, 1000],
            ajax: {
                url: "{{ route('template.getIndex') }}",
                type: "post"
            },
            columns: [
                {data: 'id', visible: false},
                {data: 'template_logo'},
                {data: 'template'},
                {data: 'category'},
                {data: 'script'},
                {data: 'template_type'},
                {data: 'action', className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
            "columnDefs": [
                {"orderable": false, "targets": [0, 3]}
            ],
            order: [0, 'desc']

        });

        $(document).on('change', '#template', function () {
            var _text = $(this).val();
            $('#template_ver').text(_text + '_');
        })


        var myDropzoneOptions = {
            url: '.',
            autoProcessQueue: false,
            addRemoveLinks: true,
            dictRemoveFile: 'Xoá',
            parallelUploads: 20,
            uploadMultiple: true,
            thumbnailWidth: 120,
            thumbnailHeight: 120,
            thumbnailMethod:"crop",
            init: function () {
                // myDropzone.processQueue();
            }

        };

        var myDropzone = new Dropzone('#file_template', myDropzoneOptions);
        $('.modal').on('hidden.bs.modal', function (e) {
            myDropzone.removeAllFiles();
            myDropzone.removeAllFiles(true);
        });


        $('#createNewTemplate').click(function () {
            $('#saveBtn_template').val("create-template");
            $('#template_id').val('');
            $("#templateForm")[0].reset();
            $('#modelHeading').html("Thêm mới Template");
            $('#ajaxModel').modal('show');


            $('#template').prop("disabled", false);
            $('#ver_build').val(1);
            $('#template_ver').text('');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
            myDropzone.destroy();
            myDropzone = new Dropzone('#file_template', myDropzoneOptions);
        });

        $('#templateForm').on('submit', function (event) {
            event.preventDefault();
            var formData = new FormData($("#templateForm")[0]);
            $.each($("#file_template")[0].dropzone.getAcceptedFiles(),
                function(a,b){
                    formData.append('template_files[]', $("#file_template")[0].dropzone.getAcceptedFiles()[a]);
                });
            if ($('#saveBtn_template').val() == 'create-template') {
                $.ajax({
                    data: formData,
                    url: "{{ route('template.create') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
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
                            // $('#templateForm').trigger("reset");
                            $("#templateForm")[0].reset();
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


        $(document).on('click', '.editTemplate', function (data) {
            var template_id = $(this).data("id");
            $.ajax({
                type: "get",
                url: "{{ asset("template/edit") }}/" + template_id,
                success: function (data) {

                    $('#modelHeading').html("Edit");
                    $('#saveBtn_template').val("edit-template");
                    $('#ajaxModel').modal('show');
                    $('.modal').on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });

                    $('#template').prop( "disabled", true );
                    if(data.ads != null){
                        var ads = jQuery.parseJSON(data.ads);
                        $.each(ads, function (k,v){
                            if(v!= null){
                                $("#Check_"+k).prop('checked', true);
                            }else {
                                $("#Check_"+k).prop('checked', false);
                            }
                        })
                    }
                    if(data.logo) {
                        $("#avatar").attr("src","../uploads/template/"+data.template+"/thumbnail/"+data.logo);
                    }else {
                        $("#avatar").attr("src","img/logo.png");
                    }
                    $('#template_id').val(data.id);
                    $('#template').val(data.template);
                    $('#template_name').val(data.template_name);

                    var template_ver =  $('#template_ver').text(data.template+'_');
                    var a = data.ver_build ? data.ver_build.substring(template_ver.text().length) : '1';

                    $('#ver_build').val(a);
                    $('#script_copy').val(data.script_copy);
                    $('#script_img').val(data.script_img);
                    $('#script_svg2xml').val(data.script_svg2xml);
                    $('#script_file').val(data.script_file);
                    $('#permissions').val(data.permissions);
                    $('#policy1').val(data.policy1);
                    $('#policy2').val(data.policy2);
                    $('#note').val(data.note);
                    $('#link').val(data.link);
                    $('#package').val(data.package);
                    $('#convert_aab').val(data.convert_aab);
                    $('#status').val(data.status);
                    $.each(data.category, function (k,v){
                        $('#category_'+v.market_id).val(v.value);
                    });

                    // myDropzone = new Dropzone('#file_template', myDropzoneOptions);


                    console.log(data)

                    if(data.template_apk){
                        var apkFile = { name: data.template_apk};
                        myDropzone.emit("addedfile", apkFile);
                        myDropzone.emit("complete", apkFile);
                        myDropzone.emit("success", apkFile);
                        myDropzone.emit("thumbnail", apkFile,'img/apk.png');
                        myDropzone.files.push( apkFile );
                    }
                    if(data.template_data){
                        var dataFile = { name: data.template_data};
                        myDropzone.emit("addedfile", dataFile);
                        myDropzone.emit("complete", dataFile);
                        myDropzone.emit("success", dataFile);
                        myDropzone.emit("thumbnail", dataFile,'img/zip.png');
                        myDropzone.files.push( dataFile );
                    }
                    if(data.template_preview){
                        for( var v = 1 ; v  <= data.template_preview; v++){
                            var previewFile = { name: v+'.jpg'};
                            myDropzone.emit("addedfile", previewFile);
                            myDropzone.emit("complete", previewFile);
                            myDropzone.emit("success", previewFile);
                            myDropzone.emit("thumbnail", previewFile,'storage/template/'+data.template+'/'+v+'.jpg');
                            myDropzone.files.push( previewFile );
                        }
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

        $(document).on('click', '.deleteTemplate', function (data) {
            var template_id = $(this).data("id");


            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#02a499",
                cancelButtonColor: "#ec4561",
                confirmButtonText: "Yes, delete it!"
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "get",
                        url: "{{ asset("template/delete") }}/" + template_id,
                        success: function (data) {
                            if (data.error) {
                                $.notify(data.error, "error");
                            }
                            if (data.success) {
                                $.notify(data.success, "success");
                                table.draw();
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });

                }
            });


            {{--    swal({--}}
            {{--            title: "Bạn có chắc muốn xóa?",--}}
            {{--            text: "Your will not be able to recover this imaginary file!",--}}
            {{--            type: "warning",--}}
            {{--            showCancelButton: true,--}}
            {{--            confirmButtonClass: "btn-danger",--}}
            {{--            confirmButtonText: "Xác nhận xóa!",--}}
            {{--            closeOnConfirm: false--}}
            {{--        },--}}
            {{--        function(){--}}
            {{--            $.ajax({--}}
            {{--                type: "get",--}}
            {{--                url: "{{ asset("template/delete") }}/" + template_id,--}}
            {{--                success: function (data) {--}}
            {{--                    if(data.error){--}}
            {{--                        $.notify(data.error , "error");--}}
            {{--                    }--}}
            {{--                    // table.draw();--}}
            {{--                },--}}
            {{--                error: function (data) {--}}
            {{--                    console.log('Error:', data);--}}
            {{--                }--}}
            {{--            });--}}
            {{--            // swal("Đã xóa!", "Your imaginary file has been deleted.", "success");--}}
            {{--        });--}}
        });


        $(document).on('click', '.checkDataTemplate', function (data) {
            var id = $(this).data("id");
            swal({
                    title: "Bạn có chắc muốn check Data?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Xác nhận!",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        type: "get",
                        url: "{{ asset("project/checkData") }}/" + id,
                        success: function (data) {
                            table.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                    swal("OK!", '', "success");
                });
        });

    })





        {{--$('.dropzone').each(function() {--}}
        {{--    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');--}}
        {{--    var options = $(this).attr('id');--}}
        {{--    var lang = $(this).data("lang");--}}
        {{--    var lang_code = $(this).data("lang_code");--}}
        {{--    var maxfile = $(this).data("maxfile");--}}
        {{--    var extfile = $(this).data("ext");--}}
        {{--    var dropParamName = $(this).data("name");--}}
        {{--    const getMeSomeUrl = () => {--}}
        {{--        return '{{route('design.create')}}?projectid=' + _id + '&projectname=' + _name+'&action=' + options + '&lang_code=' + lang_code + '&lang=' + lang--}}
        {{--    }--}}


        {{--$('#file_template').dropzone({--}}
        {{--    url: '{{route('template.store')}}',--}}
        {{--    headers: {--}}
        {{--        'x-csrf-token':  $('meta[name="csrf-token"]').attr('content'),--}}
        {{--    },--}}
        {{--    paramName:  $('#file_template').data("name"),--}}
        {{--    parallelUploads: 10,--}}
        {{--    uploadMultiple: true,--}}
        {{--    // acceptedFiles: extfile,--}}
        {{--    addRemoveLinks: true,--}}
        {{--    timeout: 0,--}}
        {{--    dictRemoveFile: 'Xoá',--}}
        {{--    autoProcessQueue: false,--}}
        {{--    init: function () {--}}
        {{--        var myDropzone = this;--}}
        {{--        // Update selector to match your button--}}
        {{--        $("#saveBtn_template").click(function (e) {--}}
        {{--            e.preventDefault();--}}
        {{--            myDropzone.processQueue();--}}
        {{--        });--}}

        {{--        this.on('sendingmultiple', function(file, xhr, formData) {--}}
        {{--            // Append all form inputs to the formData Dropzone will POST--}}
        {{--            var data = $('#templateForm').serializeArray();--}}
        {{--            $.each(data, function(key, el) {--}}
        {{--                formData.append(el.name, el.value);--}}
        {{--            });--}}
        {{--        });--}}
        {{--    }--}}
        {{--});--}}


</script>

{{--<script>--}}

{{--    function editTemplate(id) {--}}



{{--        $.get('{{asset('template/edit')}}/'+id,function (data) {--}}
{{--            $('#template').prop( "disabled", true );--}}



{{--            // $.each(data.ads, function (k,v){--}}
{{--            //     console.log(k)--}}
{{--            //     console.log(v)--}}
{{--            // })--}}

{{--            if(data.ads != null){--}}
{{--                var ads = jQuery.parseJSON(data.ads);--}}
{{--                $.each(ads, function (k,v){--}}
{{--                    if(v!= null){--}}
{{--                        $("#Check_"+k).prop('checked', true);--}}
{{--                    }else {--}}
{{--                        $("#Check_"+k).prop('checked', false);--}}
{{--                    }--}}
{{--                })--}}
{{--            }--}}

{{--            if(data.logo) {--}}
{{--                $("#avatar").attr("src","../uploads/template/"+data.template+"/thumbnail/"+data.logo);--}}
{{--            }else {--}}
{{--                $("#avatar").attr("src","img/logo.png");--}}
{{--            }--}}


{{--            $('#template_id').val(data.id);--}}
{{--            $('#template').val(data.template);--}}
{{--            $('#template_name').val(data.template_name);--}}

{{--            var template_ver =  $('#template_ver').text(data.template+'_');--}}
{{--            var a = data.ver_build ? data.ver_build.substring(template_ver.text().length) : '1';--}}

{{--            $('#ver_build').val(a);--}}

{{--            $('#script_copy').val(data.script_copy);--}}
{{--            $('#script_img').val(data.script_img);--}}
{{--            $('#script_svg2xml').val(data.script_svg2xml);--}}
{{--            $('#script_file').val(data.script_file);--}}
{{--            $('#permissions').val(data.permissions);--}}
{{--            $('#policy1').val(data.policy1);--}}
{{--            $('#policy2').val(data.policy2);--}}
{{--            $('#note').val(data.note);--}}
{{--            $('#link').val(data.link);--}}
{{--            $('#package').val(data.package);--}}
{{--            $('#convert_aab').val(data.convert_aab);--}}
{{--            $('#status').val(data.status);--}}
{{--            $.each(data.category, function (k,v){--}}
{{--                $('#category_'+v.market_id).val(v.value);--}}
{{--            });--}}








{{--            $('#modelHeading').html("Edit");--}}
{{--            $('#saveBtn_template').val("edit-template");--}}
{{--            $('#ajaxModel').modal('show');--}}
{{--            $('.modal').on('hidden.bs.modal', function (e) {--}}
{{--                $('body').addClass('modal-open');--}}
{{--            });--}}
{{--        })--}}
{{--    }--}}
{{--</script>--}}



@endsection






