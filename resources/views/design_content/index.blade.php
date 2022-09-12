@extends('layouts.master')

@section('css')

/*<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />*/
/*<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />*/

<link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ URL::asset('assets/libs/lightgallery/css/lightgallery.css') }}" rel="stylesheet" type="text/css" />


{{--<link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">--}}







<!-- Responsive datatable examples -->
/*<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />*/

<!-- Sweet-Alert  -->
/*<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">*/
/*<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>*/

<!-- Select2 Js  -->
/*<link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />*/

<!-- Dropzone css -->
{{--<link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />--}}

<style>
    .gallery
    {
        overflow: hidden;
        overflow-x: scroll;
    }

    .thumbnails
    {
        /* Arbitrarily large number */
        width: 3800px;
    }
</style>

@endsection


@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Duyệt app</h4>
</div>

@endsection
@section('content')

    <div class="row">

        <div class="col-lg-2 col-md-2">
            <div class="card">
                <div class="card-body">
{{--                    <p class="card-text">--}}
{{--                        Duyệt app--}}
{{--                    </p>--}}
                    <ul class="list-group" style="height: 1000px; overflow: auto">
                        @for($i=1;$i<50;$i++)
                        <a href="javascript:void(0)" id="project_{{$i}}" class="showProject" data-id="{{$i}}">
                            <li class="list-group-item">{{$i}} project</li>
                        </a>
                        @endfor

                    </ul>
                </div>
            </div>
        </div>

        <!-- end col -->


        <div class="col-lg-10">
            <div class="card">
                <div class="card-body " id="project_detail" style="display: none" >

                    <form id="browseappForm" name="browseappForm" class="form-horizontal">

                        <div class="row">
                            <div class="form-group col-lg-2">
                                <label for="name">Logo</label>
                                <p class="card-title-desc"><img class="d-block img-fluid" src="{{ URL::asset('assets/images/logo.png') }}" height="200" width="200px" alt="First slide"></p>
                            </div>

                            <div class="form-group col-lg-8">
                                <label for="name">Ghi chú</label>
                                <textarea id="note" name="note" class="form-control" rows="9" ></textarea>
                            </div>



                            <div class="col-lg-2 align-self-center">

                                <a href="javascript:void(0)" class="btn btn-success btn-block" style="height: 100px; display:flex;align-items:center; justify-content:center; font-size: 20px" id="btnDuyet"  data-value="1">
                                    Duyệt
                                </a>
                                <a href="javascript:void(0)" class="btn btn-warning btn-block" style="height: 100px; display:flex;align-items:center; justify-content:center; font-size: 20px" id="btnChinh_sua"  data-value="1">
                                    Chỉnh sửa
                                </a>
{{--                                <button type="submit" class="btn btn-success btn-block" style="height: 100px; font-size: 20px " id="" >Duyệt</button>--}}
{{--                                <button type="submit" class="btn btn-warning btn-block" style="height: 100px; font-size: 20px " id="btnChinh_sua" >Chỉnh sửa</button>--}}

{{--                                <input type="button" class="btn btn-success btn-block" style="height: 100px; font-size: 20px " value="Duyệt">--}}
{{--                                <input type="button" class="btn btn-warning btn-block" style="height: 100px; font-size: 20px " value="Chỉnh sửa">--}}
                            </div>
                        </div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Viet Nam</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">English</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active p-3 gallery" id="home" role="tabpanel">
                                <p> Viet Nam ở đây</p>
                                <div class="light_gallery thumbnails" id="light_gallery">
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/1.jpg') }}" title="Project 1">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/1.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/2.jpg') }}" title="Project 2">
                                        <div class="img-responsive ">
                                            <img src="{{ URL::asset('/assets/images/small/2.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/3.jpg') }}" title="Project 3">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/3.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/4.jpg') }}" title="Project 4">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/4.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/5.jpg') }}" title="Project 5">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/5.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/6.jpg') }}" title="Project 6">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/6.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/7.jpg') }}" title="Project 6">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/7.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/8.jpg') }}" title="Project 6">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/8.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/bn.png') }}" title="Project 1">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/bn.png') }}" alt="" height="500">
                                        </div>
                                    </a>
                                </div>

                            </div>
                            <div class="tab-pane p-3" id="profile" role="tabpanel">
                                <p> English ở đây</p>

                                <div class="light_gallery" id="light_gallery" style="width: 100%">

                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/1.jpg') }}" title="Project 1">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/1.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/2.jpg') }}" title="Project 2">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/2.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/3.jpg') }}" title="Project 3">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/3.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/4.jpg') }}" title="Project 4">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/4.jpg') }}" alt="" height="500">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/5.jpg') }}" title="Project 5">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/5.jpg') }}" alt="" height="250">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/6.jpg') }}" title="Project 6">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/6.jpg') }}" alt="" height="250">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/7.jpg') }}" title="Project 6">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/7.jpg') }}" alt="" height="250">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/8.jpg') }}" title="Project 6">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/8.jpg') }}" alt="" height="250">
                                        </div>
                                    </a>
                                    <a class="float-left" style="margin:5px" href="{{ URL::asset('/assets/images/small/bn.png') }}" title="Project 1">
                                        <div class="img-responsive">
                                            <img src="{{ URL::asset('/assets/images/small/bn.png') }}" alt="" height="250">
                                        </div>
                                    </a>
                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')

<!-- Required datatable js -->

<script src="plugins/select2/js/select2.min.js"></script>

<script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>



<script src="{{ URL::asset('/assets/libs/lightgallery/js/lightgallery-all.js') }}"></script>




<script type="text/javascript">


    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.light_gallery').lightGallery({});











        {{--var table = $('.data-table').DataTable({--}}

        {{--    processing: true,--}}
        {{--    serverSide: true,--}}
        {{--    ajax: {--}}
        {{--        url: "{{ route('content.getIndex') }}",--}}
        {{--        type: 'post',--}}
        {{--    },--}}
        {{--    columns: [--}}
        {{--        {data: 'projectid', name: 'projectid'},--}}
        {{--        // {data: 'title', name: 'title'},--}}
        {{--        // {data: 'summary', name: 'summary'},--}}
        {{--        // {data: 'description', name: 'description'},--}}
        {{--        // {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},--}}
        {{--    ],--}}

        {{--});--}}
        // var _id = null;
        {{--$(document).on('change', '#project_id', function () {--}}
        {{--    var projectID = $(this).select2('data')[0].id;--}}
        {{--    $('#pro_id').val(projectID);--}}

        {{--    _id = $('#pro_id').val();--}}

        {{--    $.get('{{asset('content/edit')}}/'+_id,function (data) {--}}
        {{--        var langs = data.lang;--}}

        {{--        $.each( langs, function( key, value ) {--}}
        {{--            $('#content_summary_'+value.id).val(value.pivot.summary);--}}
        {{--            $('#content_title_'+value.id).val(value.pivot.title);--}}
        {{--            // $('#content_description_'+value.id).val(value.pivot.description);--}}
        {{--            if(value.pivot.description){--}}
        {{--                tinymce.get('content_description_'+value.id).setContent(value.pivot.description);--}}
        {{--            }else {--}}
        {{--                tinymce.get('content_description_'+value.id).setContent('');--}}
        {{--            }--}}
        {{--        })--}}
        {{--    })--}}
        {{--});--}}


        {{--$('#createNewDesign').click(function () {--}}
        {{--    $('.project_select').show();--}}
        {{--    $('#saveBtn').val("create-design");--}}
        {{--    $('#project_id_content').val('');--}}
        {{--    $('#contentForm').trigger("reset");--}}
        {{--    $('#modelHeadingContent').html("Thêm mới ");--}}
        {{--    $('#ajaxModelContent').modal('show');--}}

        {{--    $("#project_id").select2({--}}
        {{--        minimumInputLength: 2,--}}
        {{--        ajax: {--}}
        {{--            url: '{{route('design.project_show')}}',--}}
        {{--            dataType: 'json',--}}
        {{--            type: "GET",--}}
        {{--            // quietMillis: 50,--}}
        {{--            data: function(params) {--}}
        {{--                return {--}}
        {{--                    q: params.term, // search term--}}
        {{--                    page: params.page--}}
        {{--                };--}}
        {{--            },--}}
        {{--            processResults: function(data) {--}}
        {{--                return {--}}
        {{--                    results: $.map(data, function (item) {--}}
        {{--                        return {--}}
        {{--                            text: item.name,--}}
        {{--                            // slug: item.slug,--}}
        {{--                            id: item.id--}}
        {{--                        }--}}
        {{--                    })--}}
        {{--                };--}}
        {{--            },--}}
        {{--            // cache: true--}}
        {{--        },--}}
        {{--    });--}}
        {{--});--}}

        {{--$('#browseappForm').on('submit',function (event){--}}

        {{--    alert($('#btnButton').val())--}}
        {{--    // event.preventDefault();--}}
        {{--    // var formData = new FormData($("#contentForm")[0]);--}}
        {{--    // var row_id = $('#saveBtnEditDesign').val();--}}
        {{--    if($('#saveBtn').val() == 'create'){--}}

        {{--    }--}}
        {{--    if($('#saveBtn').val() == 'edit'){--}}

        {{--    }--}}
        {{--    --}}{{--$.ajax({--}}
        {{--    --}}{{--    data: formData,--}}
        {{--    --}}{{--    url: "{{ route('content.create') }}",--}}
        {{--    --}}{{--    type: "POST",--}}
        {{--    --}}{{--    dataType: 'json',--}}
        {{--    --}}{{--    processData: false,--}}
        {{--    --}}{{--    contentType: false,--}}
        {{--    --}}{{--    success: function (data) {--}}
        {{--    --}}{{--        if(data.success){--}}
        {{--                $.notify('OK', "success");--}}
        {{--    --}}{{--            table.draw();--}}
        {{--    --}}{{--        }--}}
        {{--    --}}{{--        if(data.errors){--}}
        {{--    --}}{{--            $.notify(data.errors, "error");--}}
        {{--    --}}{{--        }--}}
        {{--    --}}{{--    },--}}
        {{--    --}}{{--});--}}
        {{--});--}}


        {{--$(document).on('click','.deleteProjectLang', function (data){--}}
        {{--    var _id = $(this).data("id");--}}
        {{--    var remove = $(this).parent().parent();--}}
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
        {{--                url: "{{ asset("design/delete") }}/" + _id,--}}
        {{--                success: function (data) {--}}
        {{--                    if(data.success){--}}
        {{--                        remove.slideUp(300,function() {--}}
        {{--                            remove.remove();--}}
        {{--                        })--}}
        {{--                    }--}}
        {{--                },--}}
        {{--                error: function (data) {--}}
        {{--                    console.log('Error:', data);--}}
        {{--                }--}}
        {{--            });--}}
        {{--            swal("Đã xóa!", "Your imaginary file has been deleted.", "success");--}}
        {{--        });--}}
        {{--});--}}

        $(document).on('click','#btnDuyet', function (data){
            $.notify('OK', "success");
            $('#project_detail').hide()
        })
        $(document).on('click','#btnChinh_sua', function (data){
            $.notify('OK', "warning");
            var a = $('.showProject').val();
            $('#project_detail').hide()
            $('#project_1').remove()
        })

        $(document).on('click','.showProject', function (data){
            var _id = $(this).data("id");
            $('#project_detail').show()

            {{--$.get('{{asset('content/edit')}}/'+_id,function (data) {--}}
            {{--    $('#ajaxModelContent').modal('show');--}}

            {{--    $('#modelHeadingContent').html("Chỉnh sửa "+data.projectname);--}}
            {{--    $('.modal').on('hidden.bs.modal', function (e) {--}}
            {{--        $('body').addClass('modal-open');--}}
            {{--    });--}}

            {{--    $('#pro_id').val(data.projectid);--}}


            {{--    var langs = data.lang;--}}

            {{--    $.each( langs, function( key, value ) {--}}
            {{--        $('#content_summary_'+value.id).val(value.pivot.summary);--}}
            {{--        $('#content_title_'+value.id).val(value.pivot.title);--}}
            {{--        if(value.pivot.description){--}}
            {{--            tinymce.get('content_description_'+value.id).setContent(value.pivot.description);--}}
            {{--        }else {--}}
            {{--            tinymce.get('content_description_'+value.id).setContent('');--}}
            {{--        }--}}
            {{--    })--}}
            {{--})--}}
        });
    });


    </script>
@endsection


