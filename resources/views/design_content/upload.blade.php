@extends('layouts.master')

@section('css')

/*<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />*/
/*<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />*/

<link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ URL::asset('assets/libs/lightgallery/css/lightgallery.css') }}" rel="stylesheet" type="text/css" />










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
        height: 800px;
    }
</style>

@endsection


@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Upload app</h4>

</div>

@endsection
@section('content')

    <div class="row">

{{--        <div style="width: 9%;min-width: 150px">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <p class="card-text">--}}
{{--                        Duyệt app--}}
{{--                    </p>--}}
{{--                    <ul class="list-group" style="height: 1000px; overflow: auto">--}}
{{--                        @if(isset($projects))--}}

{{--                            @foreach($projects as $key=>$project)--}}
{{--                            <a href="javascript:void(0)" id="project_{{$project->projectid}}" class="showProject" data-id="{{$project->projectid}}">--}}
{{--                                <li class="list-group-item">{{$project->projectname}}</li>--}}
{{--                            </a>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}

{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}



        <!-- end col -->


    @if(isset($project))





        <div class="col-lg-12">
            <div class="card">
                <div class="card-body " id="project_detail" >

                    <form id="browseappForm" name="browseappForm" class="form-horizontal">
                        <input type="hidden" name="project_id" id="project_id">
                        <h4><span id="pro_name">{{$project->projectname}}</span>
                            <span style="font-weight: 500;" id="template"> - {{$project->matemplate->template}}</span>
                            <span style="font-weight: 500;" id="title_app"> - {{$project->title_app}}</span>
                            <span style="font-weight: 600;" class="badge badge-success">  Download</span>
                        </h4>

                        <div class="row">

                            <div class="form-group col-lg-2">
                                <label for="name">Logo</label>
                                <p class="card-title-desc">
                                    <img id="logo_project" class="d-block img-fluid" src="../storage/projects/{{$project->da->ma_da}}/{{$project->projectname}}/lg114.png" height="200" width="200px" alt="{{$project->projectname}}">
                                </p>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="name">Ghi chú</label>
                                <textarea id="notes_design" name="notes_design" class="form-control" rows="9" >{{$project->notes_design}}</textarea>
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="name">Market</label>
                                <div id="accordion">
                                    <div class="card mb-1">
                                        <div class="card-header p-3" id="headingOne">
                                            <h6 class="m-0 font-14">
                                                <a href="#collapseOne" class="text-dark collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                                                    Collapsible Group Item #1
                                                </a>
                                            </h6>
                                        </div>

                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                            <div class="card-body">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-1">
                                        <div class="card-header p-3" id="headingTwo">
                                            <h6 class="m-0 font-14">
                                                <a href="#collapseTwo" class="text-dark collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseTwo">
                                                    Collapsible Group Item #2
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                            <div class="card-body">
                                                Anim pariatur cliche reprehenderit, enim eiusmod high life
                                                accusamus terry richardson ad squid. 3 wolf moon officia
                                                aute, non cupidatat skateboard dolor brunch. Food truck
                                                quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor,
                                                sunt aliqua put a bird on it squid single-origin coffee
                                                nulla assumenda shoreditch et. Nihil anim keffiyeh
                                                helvetica, craft beer labore wes anderson cred nesciunt
                                                sapiente ea proident. Ad vegan excepteur butcher vice lomo.
                                                Leggings occaecat craft beer farm-to-table, raw denim
                                                aesthetic synth nesciunt you probably haven't heard of them
                                                accusamus labore sustainable VHS.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-0">
                                        <div class="card-header p-3" id="headingThree">
                                            <h6 class="m-0 font-14">
                                                <a href="#collapseThree" class="text-dark collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseThree">
                                                    Collapsible Group Item #3
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                            <div class="card-body">
                                                Anim pariatur cliche reprehenderit, enim eiusmod high life
                                                accusamus terry richardson ad squid. 3 wolf moon officia
                                                aute, non cupidatat skateboard dolor brunch. Food truck
                                                quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor,
                                                sunt aliqua put a bird on it squid single-origin coffee
                                                nulla assumenda shoreditch et. Nihil anim keffiyeh
                                                helvetica, craft beer labore wes anderson cred nesciunt
                                                sapiente ea proident. Ad vegan excepteur butcher vice lomo.
                                                Leggings occaecat craft beer farm-to-table, raw denim
                                                aesthetic synth nesciunt you probably haven't heard of them
                                                accusamus labore sustainable VHS.
                                            </div>
                                        </div>
                                    </div>
                                </div>
{{--                                <textarea id="notes_design" name="notes_design" class="form-control" rows="9" >{{$project->notes_design}}</textarea>--}}
                            </div>
{{--                            <div class="col-lg-2 align-self-center">--}}

{{--                                <a href="javascript:void(0)" class="btn btn-success btn-block" style="height: 100px; display:flex;align-items:center; justify-content:center; font-size: 20px" id="btnDuyet"   >--}}
{{--                                    Duyệt--}}
{{--                                </a>--}}
{{--                                <a href="javascript:void(0)" class="btn btn-warning btn-block" style="height: 100px; display:flex;align-items:center; justify-content:center; font-size: 20px" id="btnChinh_sua">--}}
{{--                                    Chỉnh sửa--}}
{{--                                </a>--}}
{{--                            </div>--}}
                        </div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist" id="tablist">
                            @foreach($project->lang as $key=>$lag)
                                <li class="nav-item">
                                    <a class="nav-link @if($lag->lang_code == 'en') active @endif" data-toggle="tab" href="#{{$lag->lang_code}}" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">{{$lag->lang_name}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content" id="tab_content">
                            @foreach($project->lang as $key=>$lag)
                                <div class="tab-pane @if($lag->lang_code == 'en') active @endif p-3" id="{{$lag->lang_code}}" role="tabpanel">
                                    <div class="card">
                                        <div class="card-body">
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-12">
                                                    <label for="name">Title App
                                                        <span class="font-13 text-muted" id="count_title_app_en"></span>
                                                        <button type="button" onclick="copyTitleEN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button>
                                                    </label>
                                                    <input type="text" id="content_title_{{$lag->id}}" name="content[{{$lag->id}}][title]" class="form-control">

                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label for="name">Summary
                                                        <span class="font-13 text-muted" id="count_summary_en"></span>
                                                        <button type="button" onclick="copySumEN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button>
                                                    </label>
                                                    <input type="text" id="content_summary_{{$lag->id}}" name="content[{{$lag->id}}][summary]" class="form-control">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label for="name">Description</label><button type="button" onclick="copyDesEN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>
                                                    <textarea class="tinymce" id="content_description_{{$lag->id}}" name="content[{{$lag->id}}][description]"></textarea>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label for="name">Preview Banner</label>
                                                    <div class="light_gallery img-list" id="light_gallery">
                                                        @for($i=1; $i<=8; $i++ )
{{--                                                                <div class="img-responsive img-container">--}}
                                                                    <a class="img_class" style="margin:5px" href="{{url('storage/projects')}}/{{$project->da->ma_da}}/{{$project->projectname}}/{{$lag->lang_code}}/pr{{$i}}.jpg" title="preview {{$i}}">
                                                                        <img src="{{url('storage/projects')}}/{{$project->da->ma_da}}/{{$project->projectname}}/{{$lag->lang_code}}/pr{{$i}}.jpg" alt="" height="500">
                                                                    </a>
{{--                                                                </div>--}}

                                                        @endfor
                                                            <a class="img_class" style="margin:5px" href="{{url('storage/projects')}}/{{$project->da->ma_da}}/{{$project->projectname}}/{{$lag->lang_code}}/bn.jpg" title="banner">
                                                                <img src="{{url('storage/projects')}}/{{$project->da->ma_da}}/{{$project->projectname}}/{{$lag->lang_code}}/bn.jpg" alt="" height="500">
                                                            </a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif

        <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')

<!-- Required datatable js -->

<script src="plugins/select2/js/select2.min.js"></script>
<script src="plugins/tinymce/tinymce.min.js"></script>
<script src="assets/pages/form-editors.int.js"></script>

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


    });


    </script>
@endsection


