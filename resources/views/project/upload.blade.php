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
    /*.gallery*/
    /*{*/
    /*    overflow: hidden;*/
    /*    overflow-x: scroll;*/
    /*}*/

    /*.thumbnails*/
    /*{*/
    /*    !* Arbitrarily large number *!*/
    /*    width: 3800px;*/
    /*}*/
    /*.img-container {*/
    .img-list {
         /*height: 500px; */
        width: 100%;

        white-space: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
    }

    .img_class {
        white-space: nowrap;
        width: auto;
        height: 200px;
    }
</style>

@endsection


@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Upload</h4>

</div>

@endsection
@section('content')

    <div class="row">
        <div style="width: 9%;min-width: 200px">
            <div class="card">
                <div class="card-body">
                    <ul class="list-group" style="height: 1000px; overflow: auto">
                        @if(isset($projects))

                            @foreach($projects as $key=>$project)
                            <a href="javascript:void(0)" id="project_{{$project->projectid}}" class="showProject" data-id="{{$project->projectid}}">
                                <li class="list-group-item list_project_{{$project->projectid}}">{{$project->projectname}}</li>
                            </a>
                            @endforeach
                        @endif
                    </ul>

                </div>
            </div>
        </div>



        <!-- end col -->

        <div style="width: 88%">
            <div class="card">
                <div class="card-body " id="project_detail" >

                    <form id="browseappForm" name="browseappForm" class="form-horizontal">
                        <input type="hidden" name="project_id" id="project_id">
                        <h4><span id="pro_name"></span>
                            <span style="font-weight: 500;" id="template"></span>
                            <span style="font-weight: 500;" id="title_app"></span></h4>

                        <div class="row">

                            <div class="form-group col-lg-3">
                                <label for="name">Logo</label>
                                <p class="card-title-desc">
                                    <img id="logo_project" class="d-block img-fluid" src="" height="200" width="200px" alt="">
                                </p>
                            </div>
                            <div class="form-group col-lg-9">
                                    <table class="table table-bordered table-striped mb-0">
                                        <tbody id="market_upload" >


                                        </tbody>
                                    </table>


                            </div>
                        </div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist" id="tablist"></ul>
                        <!-- Tab panes -->
                        <div class="tab-content" id="tab_content">
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
        $(document).on('click', '#btnDuyet', function (data) {
            var formData = new FormData($("#browseappForm")[0])
            $.ajax({
                data: formData,
                url: "{{route('design_content.update')}}?action=1",
                type: "post",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    $.notify(data.success, "success")
                    $('#project_detail').hide()
                    $('#project_' + data.id).remove()
                }
            });
        });

        $(document).on('click', '#btnChinh_sua', function (data) {
            var formData = new FormData($("#browseappForm")[0])
            $.ajax({
                data: formData,
                url: "{{route('design_content.update')}}?action=0",
                type: "post",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    $.notify(data.success, "success")
                    $('#project_detail').hide()
                    $('#project_' + data.id).remove()
                }
            });
        })

        $(document).on('click', '.showProject', function (data) {
            var _id = $(this).data("id");

            // console.log(_id);
            $('#project_detail').show()
            $('.list_project_' + _id).addClass('active')

            $.get('{{asset('project/edit')}}/' + _id, function (data) {


                console.log(data)

                $("#logo_project").attr("src", "../storage/projects/" + data.da.ma_da + '/' + data.projectname + "/" + data.logo);
                $('#notes_design').val(data.notes_design);
                $('#project_id').val(data.projectid);
                $('#pro_name').html(data.projectname);
                $('#template').html(' - (' + data.ma_template.template + ') - ');
                $('#title_app').html(data.title_app);
                var tablist = '';
                var tab_content = ''
                var active = '';
                var market = '';
                $.each(data.lang, function (key, value) {

                    tablist += '<li class="nav-item '+active+'">' +
                        '<a class="nav-link" data-toggle="tab" href="#' + value.lang_code + '" role="tab">' +
                        '<span class="d-block d-sm-none"><i class="fas fa-home"></i></span>' +
                        '<span class="d-none d-sm-block">' + value.lang_name + '</span>' +
                        '</a></li>';
                    var preview = '';
                    for (var i = 1; i <= 8; i++) {
                        preview +=
                            '<a class="img_class" style="margin:5px" href="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/pr' + i + '.jpg" title="preview ' + i + '">' +
                            '<img  src="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/pr' + i + '.jpg" alt="" height="200">' +
                            '</a>'
                    }
                    tab_content += '<div class="tab-pane p-3 gallery  '+active+'" id="' + value.lang_code + '" role="tabpanel">' +

                        '<div class="card-body d-flex justify-content-center"><div class="row"><div class="form-group col-lg-9">'+
                        '<table class="table table-bordered table-striped mb-0">'+
                        '<tbody id="market_upload" >'+

                        '<tr>'+
                        '<th style="width: 20%">Title</th>'+
                        '<td style="width: 80%">'+value.lang_code+'</td>'+
                        '</tr>'+
                        '<tr>'+
                        '<th style="width: 20%">Summary</th>'+
                        '<td style="width: 80%">dddddddddddddđ</td>'+
                        '</tr>'+
                        '<tr>'+
                        '<th style="width: 20%">Description</th>'+
                        '<td style="width: 80%">dddddddddddddđ</td>'+
                        '</tr>'+

                        '</tbody></table></div></div></div>'+

                        '<div class="light_gallery  d-flex justify-content-center img-list" id="light_gallery">'
                        + preview +

                        '<a class="img_class" style="margin:5px" href="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/bn.jpg" title="preview ' + i + '">' +
                        // '<div class="img-responsive img-container">' +
                        '<img src="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/bn.jpg" alt="" height="200">' +
                        // '</div>' +
                        '</a>' +
                        '</div></div>';
                })




                var categories = [];
                $.each(data.ma_template.category, function (key, value) {
                    categories[value.market_id] = value.value

                })
                console.log(categories)

                $.each(data.markets, function (key, value) {
                    console.log(value)


                    market += '<tr>'+
                        '<th style="width: 10%">'+value.market_name+'</th>'+
                        '<td style="width: 10%">'+value.pivot.dev_id+'</td>'+
                        '<td style="width: 20%">'+categories[value.id]+'</td>'+
                        '<td style="width: 20%">'+value.pivot.app_name_x+'</td>'+
                        '<td style="width: 30%">'+value.pivot.package+'</td>'+
                        '<td style="width: 10%">Down</td>'+
                        '<td style="width: 10%">com</td>'+
                        '</tr>';


                })
                $('#tablist').html(tablist)
                $('#tab_content').html(tab_content)
                $('#market_upload').html(market)
            });


        });
    })

    function copy (e) {
        var copyText = $(e).attr('data-text');
        var textarea = document.createElement("textarea");
        textarea.textContent = copyText;
        textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
    }


    </script>
@endsection


