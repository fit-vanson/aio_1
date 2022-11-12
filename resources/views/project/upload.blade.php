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
                            <table id="project_uploadTable" class="table table-striped table-bordered dt-responsive data-table">
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
                    <input type="hidden" name="project_id" id="project_id">
                    <h4><span id="pro_name"></span>
                        <span style="font-weight: 500;" id="template"></span>
                        <span style="font-weight: 500;" id="title_app"></span>
                        <a href="" id="download"  target="_blank" class="btn btn-success">Download</a>
                    </h4>
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label for="name">Logo</label>
                            <p class="card-title-desc">
                                <img id="logo_project" class="d-block img-fluid" src="" width="200px" alt="">
                            </p>
                        </div>
                        <div class="form-group col-lg-9">
                            <table class="table table-bordered table-striped mb-0">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>DEV name</th>
                                    <th>SHA 1</th>
                                    <th>SHA 256</th>
                                    <th>Category</th>
                                    <th>App name X</th>
                                    <th>Package</th>
                                    <th>APK AAB</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="market_upload">

                                </tbody>
                            </table>


                        </div>
                    </div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" id="tablist"></ul>
                    <!-- Tab panes -->
                    <div class="tab-content" id="tab_content">
                    </div>



                </div>
            </div>
        </div>

    </div> <!-- end row -->


@endsection
@section('script')

<!-- Required datatable js -->

<script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

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

        var table = $('#project_uploadTable').DataTable({
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
                url: "{{ route('project.getProjectUpload')}}",
                type: "post",
            },
            dom: 'lfrtp',
            columns: [
                {data: 'projectname', name: 'projectname'},

            ],
            order: [[ 0, 'desc' ]],
        });

        $(document).on('click', '.showProject', function (data) {
            var _id = $(this).data("id");
            $('#project_detail').show()
            $('.list_project_' + _id).addClass('active')
            $.get('{{asset('project/edit')}}/' + _id, function (data) {
                $("#logo_project").attr("src", "../storage/projects/" + data.da.ma_da + '/' + data.projectname + "/" + data.logo);
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
                    {{--for (var i = 1; i <= 8; i++) {--}}
                    {{--        preview +=--}}
                    {{--            '<a class="image float-left" style="margin:5px" href="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/pr' + i + '.jpg" title=" ' + value.lang_name + ' Preview ' + i + '">' +--}}
                    {{--            '<div class="img-responsive img-container">' +--}}
                    {{--            '<img  src="{{ URL::asset('/storage/projects') }}/' + data.da.ma_da + '/' + data.projectname + '/' + value.lang_code + '/pr' + i + '.jpg" alt="' + value.lang_name + ' Preview ' + i + '" height="200">' +--}}
                    {{--            '</div>' +--}}
                    {{--            '</a>'--}}


                    {{--}--}}

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

                        '<div class="card-body d-flex "><div class="row"><div class="form-group col-lg-9">'+
                        '<table class="table table-bordered table-striped mb-0">'+
                        '<tbody id="lang_upload" >'+
                        '<tr>'+
                            '<th>Title</td>'+
                            '<td class="copyButton">'+value.pivot.title+'</button></td>'+
                        '</tr>'+
                        '<tr>'+
                            '<th>Summary</td>'+
                            '<td class="copyButton">'+value.pivot.summary+'</button></td>'+
                        '</tr>'+
                        '<tr>'+
                            '<th>Description</td>'+
                            '<td class="copyButton1111" id="copyButton1111">'+value.pivot.description+'</td>'+
                        '</tr>'+
                        '</tbody></table>' +
                        '</div></div></div>'+
                        '<div class="popup-gallery">'
                        + preview + banner + video +
                        '</div></div>';
                })

                var categories = [];
                $.each(data.ma_template.category, function (key, value) {
                    categories[value.market_id] = value.value
                })

                $.each(data.markets, function (key, value) {
                    if(value.pivot.package){
                        var status_upload = dev_name = download_apk = download_aab =  sha1 = sha256 ='';
                        switch (value.pivot.status_upload) {
                            case 0 :
                                status_upload = '<input class="btn btn-secondary disabled" data-value="'+value.pivot.id +'"   type="button" value="Mặc định"/>';
                                break;
                            case 1:
                                status_upload = '<input class="btn btn-primary submit_upload_status" data-value="'+value.pivot.id +'" type="button" value="Upload"/>';
                                break;
                            case 2:
                                status_upload = '<input class="btn btn-warning submit_upload_status" data-value="'+value.pivot.id +'"   type="button" value="Update"/>';
                                break;
                            case 3:
                                status_upload = '<input class="btn btn-success disabled" data-value="'+value.pivot.id +'"   type="button" value="Hoàn thành"/>';
                                break;

                        }
                        if(value.pivot.aab_link){
                            download_aab = '<a href="'+value.pivot.aab_link+'"  target="_blank"><img src="img/icon/aab.png" height="50px"  alt=""></a>';
                        }

                        if(value.pivot.apk_link){
                            download_apk = '<a href="'+value.pivot.apk_link+'"  target="_blank"><img src="img/icon/apk.png" height="50px"  alt=""></a>';
                        }



                        if(value.pivot.dev_id){
                            dev_name = value.pivot.dev.dev_name;
                        }
                        if(value.pivot.keystores){
                            sha1 = value.pivot.keystores.SHA_1_keystore;
                            sha256 = value.pivot.keystores.SHA_256_keystore;
                        }
                        market += '<tr>'+
                            '<th>'+value.market_name+'</th>'+
                            '<td>'+dev_name+'</td>'+
                            '<td><div class="truncate copyButton">'+sha1+'</div></td>'+
                            '<td><div class="truncate copyButton">'+sha256+'</div></td>'+
                            '<td>'+categories[value.id]+'</td>'+
                            '<td><div class="truncate copyButton">'+value.pivot.app_name_x +'</div></td>'+
                            '<td><div class="truncate copyButton">'+value.pivot.package +'</div></td>'+
                            '<td>'+download_apk+download_aab+'</td>'+
                            '<td>'+status_upload+'</td>'+
                            '</tr>';
                    }
                })
                $('#tablist').html(tablist)
                $('#tab_content').html(tab_content)
                $('#market_upload').html(market)




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
        $(document).on("click", ".submit_upload_status", function(){
            var id = $(this).data('value')
            var submit = $(this)
            $.ajax({
                type: 'get',
                url: '{{asset('project/update_upload_status')}}/'+id,
                success: function (data) {
                    submit.removeClass('btn-primary submit_upload_status');
                    submit.addClass('btn-success disabled');
                    submit.val("Hoàn thành");
                    $.notify(' success ', "success");
                    // table.draw()
                },
            });
        })
    })


    </script>
@endsection


