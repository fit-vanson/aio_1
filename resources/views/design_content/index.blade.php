@extends('layouts.master')

@section('css')


    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
    {{--<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.7/css/scroller.dataTables.min.css">

    <link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/lightgallery/css/lightgallery.css') }}" rel="stylesheet" type="text/css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>



    <style>
        div.dataTables_wrapper  div.dataTables_filter {
            width: 100%;
            float: none;
            text-align: center;
        }

        /*.img-list {*/
        /*     !*height: 500px; *!*/
        /*    width: 100%;*/

        /*    white-space: nowrap;*/
        /*    overflow-x: auto;*/
        /*    overflow-y: hidden;*/
        /*}*/

        /*.img_class {*/
        /*    white-space: nowrap;*/
        /*    width: auto;*/
        /*    height: 200px;*/
        /*}*/
    </style>

@endsection

@section('content')

    <div class="row">
        <div class="col-2">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="project_designTable" class="table table-striped table-bordered dt-responsive data-table">
                                <thead>
                                <tr>
                                    <th style="width: 20%">Mã Project</th>
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

        <div class="col-10">
            <div class="card">
                <div class="card-body " id="project_detail" style="display:none;" >
                    <form id="browseappForm" name="browseappForm" class="form-horizontal">
                        <input type="hidden" name="project_id" id="project_id">
                        <h4><span id="pro_name"></span>
                            <span style="font-weight: 500;" id="template"></span>
                            <span style="font-weight: 500;" id="title_app"></span>
                            <a href="" id="download"  target="_blank" class="btn btn-success">Download</a>
                        </h4>

                        <div class="row">

                            <div class="form-group col-lg-2">
                                <label for="name">Logo</label>
                                <p class="card-title-desc">
                                    <img id="logo_project" class="d-block img-fluid" src="" height="200" width="200px" alt="First slide">
                                </p>

                            </div>
                            <div class="form-group col-lg-8">
                                <label for="name">Ghi chú</label>
                                <textarea id="notes_design" name="notes_design" class="form-control" rows="9" ></textarea>
                            </div>
                            <div class="col-lg-2 align-self-center">

                                <a href="javascript:void(0)" class="btn btn-success btn-block" style="height: 100px; display:flex;align-items:center; justify-content:center; font-size: 20px" id="btnDuyet"   >
                                    Duyệt
                                </a>
                                <a href="javascript:void(0)" class="btn btn-warning btn-block" style="height: 100px; display:flex;align-items:center; justify-content:center; font-size: 20px" id="btnChinh_sua">
                                    Chỉnh sửa
                                </a>
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

    </div> <!-- end row -->


@endsection
@section('script')

    <!-- Required datatable js -->

    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
    {{--<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>--}}



    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    {{--<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>--}}
    <script src="https://cdn.datatables.net/scroller/2.0.7/js/dataTables.scroller.min.js"></script>

    <script src="plugins/select2/js/select2.min.js"></script>
    <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/lightgallery/js/lightgallery-all.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>
    <script src="plugins/tinymce/tinymce.min.js"></script>


    <script type="text/javascript">

        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#project_designTable').DataTable({
                displayLength: 20,
                bLengthChange: false,

                // searching: false,
                scrollY: 950,
                scroller: {
                    loadingIndicator: true
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('design_content.getIndex')}}",
                    type: "post",
                },
                dom: 'lfrtp',
                columns: [
                    {data: 'projectname', name: 'projectname'},

                ],
                order: [[ 0, 'desc' ]],
            });

            $(document).on('click','#btnDuyet', function (data){
                var formData = new FormData($("#browseappForm")[0])
                var row = table.row( $(this).parents('tr') );
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
                        $('#project_'+data.id).remove();
                        row.remove().draw();

                    }
                });
            });

            $(document).on('click','#btnChinh_sua', function (data){
                var formData = new FormData($("#browseappForm")[0])
                var row = table.row( $(this).parents('tr') );
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
                        $('#project_'+data.id).remove()
                        row.remove().draw();
                    }
                });
            })

            $(document).on('click','.showProject', function (data){
                var _id = $(this).data("id");

                // console.log(_id);
                $('#project_detail').show()
                $('.list_project_'+_id).addClass('active')

                {{--$.get('{{asset('design-content/edit')}}/'+_id,function (data) {--}}

                {{--    $("#logo_project").attr("src","../storage/projects/"+data.da.ma_da+'/'+data.projectname+"/"+data.logo);--}}
                {{--    $('#notes_design').val(data.notes_design);--}}
                {{--    $('#project_id').val(data.projectid);--}}
                {{--    $('#pro_name').html(data.projectname);--}}
                {{--    $('#template').html(' - ('+data.ma_template.template+') - ');--}}
                {{--    $('#title_app').html(data.title_app);--}}
                {{--    var tablist = '';--}}
                {{--    var tab_content = ''--}}
                {{--    var active = '';--}}
                {{--    $.each( data.lang, function( key, value ) {--}}
                {{--        if(value.lang_code == 'en'){--}}
                {{--            active = 'active'--}}
                {{--        }--}}
                {{--        tablist += '<li class="nav-item">'+--}}
                {{--            '<a class="nav-link '+active+'" data-toggle="tab" href="#'+value.lang_code+'" role="tab">'+--}}
                {{--            '<span class="d-block d-sm-none"><i class="fas fa-home"></i></span>'+--}}
                {{--            '<span class="d-none d-sm-block">'+value.lang_name+'</span>'+--}}
                {{--            '</a></li>';--}}
                {{--        var preview = '';--}}
                {{--        for(var i = 1; i <= 8; i++) {--}}
                {{--            preview +=--}}
                {{--                '<a class="img_class" style="margin:5px" href="{{ URL::asset('/storage/projects') }}/'+data.da.ma_da+'/'+data.projectname+'/'+value.lang_code+'/pr'+i+'.jpg" title="preview '+i+'">' +--}}
                {{--                // '<div class="img-responsive img-container">' +--}}
                {{--                '<img  src="{{ URL::asset('/storage/projects') }}/'+data.da.ma_da+'/'+data.projectname+'/'+value.lang_code+'/pr'+i+'.jpg" alt="" height="500">' +--}}
                {{--                // '</div>' +--}}
                {{--                '</a>'--}}
                {{--        }--}}
                {{--        tab_content += '<div class="tab-pane '+active+' p-3 gallery" id="'+value.lang_code+'" role="tabpanel">'+--}}
                {{--            '<div class="light_gallery img-list" id="light_gallery">'--}}
                {{--            +preview+--}}

                {{--            '<a class="img_class" style="margin:5px" href="{{ URL::asset('/storage/projects') }}/'+data.da.ma_da+'/'+data.projectname+'/'+value.lang_code+'/bn.jpg" title="preview '+i+'">' +--}}
                {{--            // '<div class="img-responsive img-container">' +--}}
                {{--            '<img src="{{ URL::asset('/storage/projects') }}/'+data.da.ma_da+'/'+data.projectname+'/'+value.lang_code+'/bn.jpg" alt="" height="500">' +--}}
                {{--            // '</div>' +--}}
                {{--            '</a>'+--}}

                {{--            '<a class="img_class" style="margin:5px" href="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/video.mp4" >' +--}}
                {{--            '<video  height="500" controls>'+--}}
                {{--            '<source src="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/video.mp4" type="video/mp4">'+--}}
                {{--            '<source src="movie.ogg" type="video/ogg">'+--}}
                {{--            'Your browser does not support the video tag.'+--}}
                {{--            '</video>'+--}}
                {{--            '</a>'+--}}

                {{--            '</div></div>'--}}
                {{--    })--}}

                {{--    $('#tablist').html(tablist)--}}
                {{--    $('#tab_content').html(tab_content)--}}
                {{--})--}}

                $.get('{{asset('design-content/edit')}}/' + _id, function (data) {
                    $("#logo_project").attr("src", "../storage/projects/" + data.da.ma_da + '/' + data.projectname + "/lg114.png");
                    $('#notes_design').val(data.notes_design);
                    $('#project_id').val(data.projectid);
                    $('#pro_name').html(data.projectname);
                    $('#template').html(' - (' + data.ma_template.template + ') - ');
                    $('#title_app').html(data.title_app);
                    // $('#download').data("download",data.projectid); //setter
                    $('#download').attr("href", "project/download/"+_id) //setter
                    var tablist = '';
                    var tab_content = ''
                    var active = '';
                    var market = '';
                    $.each(data.lang, function (key, value) {
                        if(value.id == 2){
                            active = 'active'
                        }else {
                            active ='';
                        }
                        tablist += '<li class="nav-item ">' +
                            '<a class="nav-link '+active+'" data-toggle="tab" href="#' + value.lang_code + '" role="tab">' +
                            '<span class="d-block d-sm-none"><i class="fas fa-home"></i></span>' +
                            '<span class="d-none d-sm-block">' + value.lang_name + '</span>' +
                            '</a></li>';
                        var preview = video = banner = '';
                        if(value.pivot.preview){
                            for (var i = 1; i <= value.pivot.preview; i++) {
                                preview +=
                                    '<a class="image float-left" style="margin:5px" href="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/pr' + i + '.jpg" title=" ' + value.lang_name + ' Preview ' + i + '">' +
                                    '<div class="img-responsive img-container">' +
                                    '<img  src="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/pr' + i + '.jpg" alt="' + value.lang_name + ' Preview ' + i + '" height="200">' +
                                    '</div>' +
                                    '</a>'
                            }
                        }

                        if(value.pivot.video){
                            video = '<a class="video" style="margin:5px" href="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/video.mp4" title="'+ value.lang_name +' Video">' +
                                '<div class="img-responsive img-container">' +
                                '<img src="{{ URL::asset('/img') }}'+ '/video.png" alt="'+ value.lang_name +' Video" height="200">' +
                                '</div>' +
                                '</a>';
                        }

                        if(value.pivot.banner){
                            banner =  '<a class="image float-left" style="margin:5px" href="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/bn.jpg" title="'+ value.lang_name +' Banner">' +
                                '<div class="img-responsive img-container">' +
                                '<img src="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/bn.jpg" alt="'+ value.lang_name +' Banner" height="200">' +
                                '</div>' +
                                '</a>' ;
                        }
                        tab_content += '<div class="tab-pane p-3 gallery  '+active+'" id="' + value.lang_code + '" role="tabpanel">' +
                            '<div class="popup-gallery">'
                            + preview + banner + video +
                            '</div></div>';
                    })
                    $('#tablist').html(tablist)
                    $('#tab_content').html(tab_content)

                    $('.popup-gallery').magnificPopup({
                        delegate: 'a',
                        type: 'image',
                        gallery: {
                            enabled: true,
                            navigateByImgClick: true,
                            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                        },
                        callbacks: {
                            elementParse: function(item) {
                                console.log(item.el[0].className);
                                if(item.el[0].className == 'video') {
                                    item.type = 'iframe',
                                        item.iframe = {
                                            patterns: {
                                                youtube: {
                                                    index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

                                                    id: 'v=', // String that splits URL in a two parts, second part should be %id%
                                                    // Or null - full URL will be returned
                                                    // Or a function that should return %id%, for example:
                                                    // id: function(url) { return 'parsed id'; }

                                                    src: '//www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe.
                                                },
                                                vimeo: {
                                                    index: 'vimeo.com/',
                                                    id: '/',
                                                    src: '//player.vimeo.com/video/%id%?autoplay=1'
                                                },
                                                gmaps: {
                                                    index: '//maps.google.',
                                                    src: '%id%&output=embed'
                                                }
                                            }
                                        }
                                } else {
                                    item.type = 'image',
                                        item.tLoading = 'Loading image #%curr%...',
                                        item.mainClass = 'mfp-img-mobile',
                                        item.image = {
                                            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
                                        }
                                }

                            }
                        }
                    });
                });
            });
        })


    </script>
@endsection


