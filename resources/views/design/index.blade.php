@extends('layouts.master')

@section('css')

<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ URL::asset('assets/libs/lightgallery/css/lightgallery.css') }}" rel="stylesheet" type="text/css" />







<!-- Responsive datatable examples -->
<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- Sweet-Alert  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<!-- Select2 Js  -->
<link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

<!-- Dropzone css -->
<link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />



@endsection


@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Design</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
        <a class="btn btn-success" href="javascript:void(0)" id="createNewDesign">Thêm mới</a>
    </div>
</div>
@include('modals.design')
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Ngôn ngữ </th>
                            <th style="width: 30%">Logo | Banner | Preview</th>
                            <th style="width: 5%"> Video</th>
                            <th>Status</th>
                            <th>User </th>
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

<!-- Plugins js -->
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

        var groupColumn = 0;
        var table = $('.data-table').DataTable({

            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('design.getIndex') }}",
                type: 'post',
            },
            columns: [
                {data: 'project_id', name: 'project_id'},
                {data: 'lang_id', name: 'lang_id'},
                {data: 'preview', name: 'preview'},
                {data: 'video', name: 'video'},
                {data: 'status', name: 'status'},
                {data: 'user_design', name: 'user_design'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
            // order: [[1, 'asc']],
            rowGroup: {
                dataSrc: 0
            },
            columnDefs: [{ visible: false, targets: groupColumn }],
            drawCallback: function (settings) {



                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;
                api
                    .column(groupColumn, { page: 'current' })
                    .data()
                    .each(function (group, i) {
                        if (last !== group) {
                            $(rows)
                                .eq(i)
                                .before('<tr class="group"><td colspan="8">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
                    disableOn: 700,
                    type: 'iframe',
                    mainClass: 'mfp-fade',
                    removalDelay: 160,
                    preloader: false,
                    fixedContentPos: false
                });
                $('.light_gallery').lightGallery({});
            },
        });





        var _id = null;
        var _name = null;
        $(document).on('change', '#project_id', function () {
            var projectID = $(this).select2('data')[0].id;
            var projectName = $(this).select2('data')[0].text;
            $('#pro_id').val(projectID);
            $('#pro_text').val(projectName);
            _id = $('#pro_id').val();
            _name = $('#pro_text').val();
            $('div.dz-success').remove();
        });
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
                url: getMeSomeUrl,
                headers: {
                    'x-csrf-token': CSRF_TOKEN,
                },
                paramName: dropParamName,
                // maxFiles: maxfile,
                maxFilesize: 20,
                parallelUploads: 10,
                uploadMultiple: true,
                acceptedFiles: extfile,
                // addRemoveLinks: true,
                timeout: 0,
                // dictRemoveFile: 'Xoá',
                // autoProcessQueue: false,

                init: function () {
                    var _this = this; // For the closure

                    this.on('success', function (file, response) {
                        // _this.removeFile(file);
                        if (response.success) {
                            $.notify(_name,  "success");
                            table.draw();
                        }
                        if (response.errors) {
                            _this.removeFile(file);
                            $.notify(response.errors, "error");
                        }
                    });
                },
            });
        })

        $('#createNewDesign').click(function () {
            $('#saveBtn').val("create-design");
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
        });

        $('#designForm').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#designForm")[0]);
            if($('#saveBtn').val() == 'create-design'){
                $.ajax({
                    data: formData,
                    url: "{{ route('design.create') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        // if(data.errors){
                        //     for( var count=0 ; count <data.errors.length; count++){
                        //         $("#permissionForm").notify(
                        //             data.errors[count],"error",
                        //             { position:"right" }
                        //         );
                        //     }
                        // }
                        // if(data.success){
                        //     $.notify(data.success, "success");
                        //     $('#permissionForm').trigger("reset");
                        //     $('#ajaxModel').modal('hide');
                        //     table.draw();
                        // }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-permission'){
                $.ajax({
                    data: $('#permissionForm').serialize(),
                    url: "{{ route('permission.update') }}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#permissionForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#permissionForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });

            }

        });

        $(document).on('click','.deletePer', function (data){
            var permission_id = $(this).data("id");
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
                        url: "{{ asset("permission/delete") }}/" + permission_id,
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





        function editPer(id) {
            $.get('{{asset('permission/edit')}}/'+id,function (data) {
                $('#modelHeading').html("Chỉnh sửa Phân quyền");
                $('#saveBtn').val("edit-permission");
                $('#ajaxModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });

                $('#permission_id').val(data[0].id);
                $('#name').val(data[0].name);
                $('#display_name').val(data[0].display_name)
            })
        }
    </script>
@endsection


