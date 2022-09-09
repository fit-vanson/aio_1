@extends('layouts.master')

@section('css')

<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ URL::asset('assets/libs/lightgallery/css/lightgallery.css') }}" rel="stylesheet" type="text/css" />


<link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">




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
    <h4 class="page-title">Content</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
        <a class="btn btn-success" href="javascript:void(0)" id="createNewDesign">Create Or Update</a>
    </div>
</div>
@include('modals.content')
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
                            <th>Title </th>
                            <th>Summary</th>
                            <th style="width: 40%">Description </th>
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

<!--Summernote js-->
<script src="plugins/tinymce/tinymce.min.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script src="assets/pages/form-editors.int.js"></script>
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
            ajax: {
                url: "{{ route('content.getIndex') }}",
                type: 'post',
            },
            columns: [
                {data: 'projectid', name: 'projectid'},
                {data: 'title', name: 'title'},
                {data: 'summary', name: 'summary'},
                {data: 'description', name: 'description'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],

        });
        var _id = null;
        $(document).on('change', '#project_id', function () {
            var projectID = $(this).select2('data')[0].id;
            $('#pro_id').val(projectID);

            _id = $('#pro_id').val();

            $.get('{{asset('content/edit')}}/'+_id,function (data) {
                var langs = data.lang;

                $.each( langs, function( key, value ) {
                    $('#content_summary_'+value.id).val(value.pivot.summary);
                    $('#content_title_'+value.id).val(value.pivot.title);
                    // $('#content_description_'+value.id).val(value.pivot.description);
                    if(value.pivot.description){
                        tinymce.get('content_description_'+value.id).setContent(value.pivot.description);
                    }else {
                        tinymce.get('content_description_'+value.id).setContent('');
                    }
                })
            })
        });


        $('#createNewDesign').click(function () {
            $('.project_select').show();
            $('#saveBtn').val("create-design");
            $('#project_id_content').val('');
            $('#contentForm').trigger("reset");
            $('#modelHeadingContent').html("Thêm mới ");
            $('#ajaxModelContent').modal('show');

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

        $('#contentForm').on('submit',function (event){
            event.preventDefault();
            var formData = new FormData($("#contentForm")[0]);
            // var row_id = $('#saveBtnEditDesign').val();
            $.ajax({
                data: formData,
                url: "{{ route('content.create') }}",
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    if(data.success){
                        $.notify(data.success, "success");
                        table.draw();
                    }
                    if(data.errors){
                        $.notify(data.errors, "error");
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


        $(document).on('click','.editContent', function (data){
            var _id = $(this).data("id");
            $('#contentForm').trigger("reset");
            $('.project_select').hide();
            $.get('{{asset('content/edit')}}/'+_id,function (data) {
                $('#ajaxModelContent').modal('show');

                $('#modelHeadingContent').html("Chỉnh sửa "+data.projectname);
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });

                $('#pro_id').val(data.projectid);


                var langs = data.lang;

                $.each( langs, function( key, value ) {
                    $('#content_summary_'+value.id).val(value.pivot.summary);
                    $('#content_title_'+value.id).val(value.pivot.title);
                    if(value.pivot.description){
                        tinymce.get('content_description_'+value.id).setContent(value.pivot.description);
                    }else {
                        tinymce.get('content_description_'+value.id).setContent('');
                    }
                })
            })
        });
    });


    </script>
@endsection


